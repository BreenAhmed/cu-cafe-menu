<?php
/**
 * Admin model
 */

declare(strict_types=1);

class Admin
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, username, created_at FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function attemptLogin(string $username, string $password): bool
    {
        $admin = $this->findByUsername($username);

        if (!$admin || !password_verify($password, $admin['password'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION[ADMIN_SESSION_KEY] = (int) $admin['id'];
        $_SESSION['wl_admin_user'] = $admin['username'];

        return true;
    }

    public function logout(): void
    {
        unset($_SESSION[ADMIN_SESSION_KEY], $_SESSION['wl_admin_user']);
        session_regenerate_id(true);
    }
}
