<?php
declare(strict_types=1);

// Require PHP 8.3+
if (PHP_VERSION_ID < 80300) {
    die('PHP 8.3 or higher is required');
}

// Database Configuration
define('DB_FILE', __DIR__ . '/data.json');

// CORS Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Error Handling
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Session
session_start();

// Enums for Transaction Types
enum TransactionType: string {
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';
    case TRANSFER = 'transfer';
    case RECEIVED = 'received';
}

enum RequestStatus: string {
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

enum UserRole: string {
    case USER = 'user';
    case ADMIN = 'admin';
}
?>