<?php
declare(strict_types=1);
require_once 'Database.php';
require_once 'config.php';

class User {
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_DURATION = 900; // 15 minutes in seconds
    
    public function __construct(private Database $db) {}
    
    public function register(string $name, string $email, string $password): array {
        if ($this->db->find('users', 'email', $email)) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        $user = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => UserRole::USER->value,
            'loginAttempts' => 0,
            'lockedUntil' => null,
            'createdAt' => date('Y-m-d H:i:s')
        ];
        
        $newUser = $this->db->insert('users', $user);
        
        // Create account
        require_once 'Account.php';
        $account = new Account($this->db);
        $account->createAccount($newUser['id'], $newUser['name']);
        
        return ['success' => true, 'message' => 'Registration successful', 'userId' => $newUser['id']];
    }
    
    public function login(string $email, string $password): array {
        $user = $this->db->find('users', 'email', $email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
        
        // Check if account is locked
        if ($this->isAccountLocked($user)) {
            $remainingTime = $this->getRemainingLockoutTime($user);
            return [
                'success' => false,
                'message' => "Account locked due to multiple failed attempts. Try again in $remainingTime minutes.",
                'locked' => true
            ];
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            $this->incrementLoginAttempts($user);
            $attemptsLeft = self::MAX_LOGIN_ATTEMPTS - ($user['loginAttempts'] + 1);
            
            if ($attemptsLeft <= 0) {
                $this->lockAccount($user);
                return [
                    'success' => false,
                    'message' => 'Account locked due to multiple failed attempts. Try again in 15 minutes.',
                    'locked' => true
                ];
            }
            
            return [
                'success' => false,
                'message' => "Invalid credentials. $attemptsLeft attempts remaining before account lockout."
            ];
        }
        
        // Successful login - reset attempts
        $this->resetLoginAttempts($user);
        
        $_SESSION['userId'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        return [
            'success' => true,
            'message' => 'Login successful',
            'userId' => $user['id'],
            'role' => $user['role']
        ];
    }
    
    private function isAccountLocked(array $user): bool {
        // Handle users without lockedUntil field (backward compatibility)
        if (!isset($user['lockedUntil']) || $user['lockedUntil'] === null) {
            return false;
        }
        
        $lockedUntil = strtotime($user['lockedUntil']);
        $now = time();
        
        if ($now < $lockedUntil) {
            return true;
        }
        
        // Lock expired, reset attempts
        $this->resetLoginAttempts($user);
        return false;
    }
    
    private function getRemainingLockoutTime(array $user): int {
        if (!isset($user['lockedUntil']) || $user['lockedUntil'] === null) {
            return 0;
        }
        
        $lockedUntil = strtotime($user['lockedUntil']);
        $now = time();
        $remainingSeconds = $lockedUntil - $now;
        return (int)ceil($remainingSeconds / 60);
    }
    
    private function incrementLoginAttempts(array $user): void {
        $attempts = ($user['loginAttempts'] ?? 0) + 1;
        $this->db->update('users', $user['id'], ['loginAttempts' => $attempts]);
    }
    
    private function lockAccount(array $user): void {
        $lockUntil = date('Y-m-d H:i:s', time() + self::LOCKOUT_DURATION);
        $this->db->update('users', $user['id'], [
            'loginAttempts' => self::MAX_LOGIN_ATTEMPTS,
            'lockedUntil' => $lockUntil
        ]);
    }
    
    private function resetLoginAttempts(array $user): void {
        $this->db->update('users', $user['id'], [
            'loginAttempts' => 0,
            'lockedUntil' => null
        ]);
    }
    
    public function getProfile(int $userId): ?array {
        $user = $this->db->find('users', 'id', $userId);
        unset($user['password']); // Don't send password
        return $user;
    }
    
    public function getAllUsers(): array {
        return array_map(function($user) {
            unset($user['password']);
            return $user;
        }, $this->db->findAll('users'));
    }
    
}
?>