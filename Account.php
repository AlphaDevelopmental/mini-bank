<?php
declare(strict_types=1);
require_once 'Database.php';
require_once 'Notification.php';
require_once 'Transaction.php';
require_once 'config.php';

class Account {
    private Notification $notification;
    private Transaction $transaction;
    
    public function __construct(private Database $db) {
        $this->notification = new Notification($db);
        $this->transaction = new Transaction($db);
    }
    
    public function createAccount(int $userId, string $userName): array {
        $accountNumber = $this->generateAccountNumber();
        $account = [
            'userId' => $userId,
            'accountNumber' => $accountNumber,
            'balance' => 0.00,
            'status' => 'active',
            'createdAt' => date('Y-m-d H:i:s')
        ];
        
        $newAccount = $this->db->insert('accounts', $account);
        $this->notification->create($userId, "Welcome! Your account $accountNumber has been created.");
        
        return $newAccount;
    }
    
    public function getAccount(int $userId): ?array {
        return $this->db->find('accounts', 'userId', $userId);
    }
    
    public function getAccountByNumber(string $accountNumber): ?array {
        return $this->db->find('accounts', 'accountNumber', $accountNumber);
    }
    
    public function getAllAccounts(): array {
        return $this->db->findAll('accounts');
    }
    
    public function requestDeposit(int $userId, float $amount): array {
        $request = [
            'userId' => $userId,
            'type' => TransactionType::DEPOSIT->value,
            'amount' => $amount,
            'status' => RequestStatus::PENDING->value,
            'createdAt' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('pendingRequests', $request);
        $this->notification->create($userId, "Deposit request of $$amount submitted. Awaiting admin approval.");
        
        return ['success' => true, 'message' => 'Deposit request submitted'];
    }
    
    public function requestWithdrawal(int $userId, float $amount): array {
        $account = $this->getAccount($userId);
        
        if ($account['balance'] < $amount) {
            return ['success' => false, 'message' => 'Insufficient funds'];
        }
        
        $request = [
            'userId' => $userId,
            'type' => TransactionType::WITHDRAWAL->value,
            'amount' => $amount,
            'status' => RequestStatus::PENDING->value,
            'createdAt' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('pendingRequests', $request);
        $this->notification->create($userId, "Withdrawal request of $$amount submitted. Awaiting admin approval.");
        
        return ['success' => true, 'message' => 'Withdrawal request submitted'];
    }
    
    public function transfer(int $fromUserId, string $toAccountNumber, float $amount): array {
        $fromAccount = $this->getAccount($fromUserId);
        $toAccount = $this->getAccountByNumber($toAccountNumber);
        
        if (!$toAccount) {
            return ['success' => false, 'message' => 'Recipient account not found'];
        }
        
        if ($fromAccount['accountNumber'] === $toAccountNumber) {
            return ['success' => false, 'message' => 'Cannot transfer to same account'];
        }
        
        if ($fromAccount['balance'] < $amount) {
            return ['success' => false, 'message' => 'Insufficient funds'];
        }
        
        // Deduct from sender
        $newFromBalance = $fromAccount['balance'] - $amount;
        $this->db->update('accounts', $fromAccount['id'], ['balance' => $newFromBalance]);
        $this->transaction->add($fromUserId, TransactionType::TRANSFER, $amount, "Transfer to $toAccountNumber");
        $this->notification->create($fromUserId, "Sent $$amount to $toAccountNumber. New balance: $$newFromBalance");
        
        // Add to recipient
        $newToBalance = $toAccount['balance'] + $amount;
        $this->db->update('accounts', $toAccount['id'], ['balance' => $newToBalance]);
        $this->transaction->add($toAccount['userId'], TransactionType::RECEIVED, $amount, "Received from {$fromAccount['accountNumber']}");
        $this->notification->create($toAccount['userId'], "Received $$amount from {$fromAccount['accountNumber']}");
        
        return ['success' => true, 'balance' => $newFromBalance];
    }
    
    private function generateAccountNumber(): string {
        return '30' . str_pad((string)rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    }
}
?>