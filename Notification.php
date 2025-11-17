<?php
declare(strict_types=1);
require_once 'Database.php';

class Notification {
    public function __construct(private Database $db) {}
    
    public function create(int $userId, string $message): array {
        $notification = [
            'userId' => $userId,
            'message' => $message,
            'read' => false,
            'createdAt' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('notifications', $notification);
    }
    
    public function getAll(int $userId): array {
        return $this->db->findAll('notifications', 'userId', $userId);
    }
    
    public function markAsRead(int $notificationId): ?array {
        return $this->db->update('notifications', $notificationId, ['read' => true]);
    }
}
?>