<?php
declare(strict_types=1);
require_once 'Database.php';
require_once 'Account.php';
require_once 'Transaction.php';
require_once 'Notification.php';
require_once 'config.php';

class Admin {
    private Account $account;
    private Transaction $transaction;
    private Notification $notification;
    
    public function __construct(private Database $db) {
        $this->account = new Account($db);
        $this->transaction = new Transaction($db);
        $this->notification = new Notification($db);
    }
    
    public function getPendingRequests(): array {
        return $this->db->findAll('pendingRequests', 'status', RequestStatus::PENDING->value);
    }
    
    public function approveRequest(int $requestId): array {
        $request = $this->db->find('pendingRequests', 'id', $requestId);
        
        if (!$request || $request['status'] !== RequestStatus::PENDING->value) {
            return ['success' => false, 'message' => 'Invalid request'];
        }
        
        $userAccount = $this->account->getAccount($request['userId']);
        $amount = $request['amount'];
        
        if ($request['type'] === TransactionType::DEPOSIT->value) {
            // Approve Deposit
            $newBalance = $userAccount['balance'] + $amount;
            $this->db->update('accounts', $userAccount['id'], ['balance' => $newBalance]);
            $this->transaction->add($request['userId'], TransactionType::DEPOSIT, $amount, 'Admin Approved Deposit');
            $this->notification->create($request['userId'], "Your deposit of $amount has been approved. New balance: $newBalance");
            
        } elseif ($request['type'] === TransactionType::WITHDRAWAL->value) {
            // Approve Withdrawal
            $newBalance = $userAccount['balance'] - $amount;
            $this->db->update('accounts', $userAccount['id'], ['balance' => $newBalance]);
            $this->transaction->add($request['userId'], TransactionType::WITHDRAWAL, $amount, 'Admin Approved Withdrawal');
            $this->notification->create($request['userId'], "Your withdrawal of $amount has been approved. New balance: $newBalance");
        }
        
        $this->db->update('pendingRequests', $requestId, ['status' => RequestStatus::APPROVED->value]);
        
        return ['success' => true, 'message' => 'Request approved'];
    }
    
    public function rejectRequest(int $requestId): array {
        $request = $this->db->find('pendingRequests', 'id', $requestId);
        
        if (!$request || $request['status'] !== RequestStatus::PENDING->value) {
            return ['success' => false, 'message' => 'Invalid request'];
        }
        
        $this->db->update('pendingRequests', $requestId, ['status' => RequestStatus::REJECTED->value]);
        $this->notification->create(
            $request['userId'],
            "Your {$request['type']} request of \${$request['amount']} has been rejected."
        );
        
        return ['success' => true, 'message' => 'Request rejected'];
    }
    
    public function getAllUsers(): array {
        return array_map(function($user) {
            unset($user['password']);
            $account = $this->account->getAccount($user['id']);
            $user['account'] = $account;
            return $user;
        }, array_filter($this->db->findAll('users'), fn($u) => $u['role'] === UserRole::USER->value));
    }
    
    public function getDashboardStats(): array {
        $users = $this->db->findAll('users');
        $accounts = $this->db->findAll('accounts');
        $transactions = $this->db->findAll('transactions');
        $pending = $this->getPendingRequests();
        
        $totalBalance = array_reduce($accounts, fn($sum, $acc) => $sum + $acc['balance'], 0);
        
        return [
            'totalUsers' => count(array_filter($users, fn($u) => $u['role'] === UserRole::USER->value)),
            'totalAccounts' => count($accounts),
            'totalBalance' => $totalBalance,
            'totalTransactions' => count($transactions),
            'pendingRequests' => count($pending)
        ];
    }
    
    public function changePassword(int $adminId, string $currentPassword, string $newPassword): array {
        $admin = $this->db->find('users', 'id', $adminId);
        
        if (!$admin || $admin['role'] !== UserRole::ADMIN->value) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        
        if (!password_verify($currentPassword, $admin['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'New password must be at least 6 characters'];
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->db->update('users', $adminId, ['password' => $hashedPassword]);
        
        return ['success' => true, 'message' => 'Password changed successfully'];
    }
}
?>