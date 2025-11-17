<?php
declare(strict_types=1);
require_once 'Database.php';
require_once 'config.php';

class Transaction {
    public function __construct(private Database $db) {}
    
    public function add(int $userId, TransactionType $type, float $amount, string $description): array {
        $transaction = [
            'userId' => $userId,
            'type' => $type->value,
            'amount' => $amount,
            'description' => $description,
            'date' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('transactions', $transaction);
    }
    
    public function getByUser(int $userId): array {
        return $this->db->findAll('transactions', 'userId', $userId);
    }
    
    public function getAll(): array {
        return $this->db->findAll('transactions');
    }
}
?>