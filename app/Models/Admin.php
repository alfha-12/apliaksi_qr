<?php
namespace App\Models;

use App\Core\Database;

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByUsername(string $username)
    {
        return $this->db->fetch(
            "SELECT * FROM admins WHERE username = ?",
            [$username]
        );
    }

    public function findById(int $id)
    {
        return $this->db->fetch(
            "SELECT * FROM admins WHERE id = ?",
            [$id]
        );
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function create(string $username, string $password, string $name)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->db->query(
            "INSERT INTO admins (username, password, name, created_at) VALUES (?, ?, ?, NOW())",
            [$username, $hash, $name]
        );
        return $this->db->lastInsertId();
    }
}
