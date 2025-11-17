<?php
declare(strict_types=1);
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';
require_once 'Account.php';
require_once 'Transaction.php';
require_once 'Notification.php';
require_once 'Admin.php';

$db = new Database(DB_FILE);
$user = new User($db);
$account = new Account($db);
$transaction = new Transaction($db);
$notification = new Notification($db);
$admin = new Admin($db);

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true) ?? [];

try {
    echo match($action) {
        'register' => json_encode($user->register($input['name'], $input['email'], $input['password'])),
        
        'login' => json_encode($user->login($input['email'], $input['password'])),
        
        'getProfile' => json_encode($user->getProfile((int)$_GET['userId'])),
        
        'getAccount' => json_encode($account->getAccount((int)$_GET['userId'])),
        
        'requestDeposit' => json_encode($account->requestDeposit($input['userId'], (float)$input['amount'])),
        
        'requestWithdrawal' => json_encode($account->requestWithdrawal($input['userId'], (float)$input['amount'])),
        
        'transfer' => json_encode($account->transfer($input['userId'], $input['toAccount'], (float)$input['amount'])),
        
        'getTransactions' => json_encode($transaction->getByUser((int)$_GET['userId'])),
        
        'getNotifications' => json_encode($notification->getAll((int)$_GET['userId'])),
        
        'markAsRead' => json_encode($notification->markAsRead($input['notificationId'])),
        
        // Admin Actions
        'adminGetStats' => json_encode($admin->getDashboardStats()),
        
        'adminGetPendingRequests' => json_encode($admin->getPendingRequests()),
        
        'adminApproveRequest' => json_encode($admin->approveRequest($input['requestId'])),
        
        'adminRejectRequest' => json_encode($admin->rejectRequest($input['requestId'])),
        
        'adminGetAllUsers' => json_encode($admin->getAllUsers()),
        
        'adminGetAllTransactions' => json_encode($transaction->getAll()),
        
        'adminChangePassword' => json_encode($admin->changePassword($input['adminId'], $input['currentPassword'], $input['newPassword'])),
        
        default => json_encode(['error' => 'Invalid action'])
    };
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>