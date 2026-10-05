<?php
namespace App\Models;

use App\Core\Database;

class Review
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByPlace(int $placeId, bool $approvedOnly = true)
    {
        $sql = "SELECT * FROM reviews WHERE place_id = ?";
        if ($approvedOnly) {
            $sql .= " AND is_approved = 1";
        }
        $sql .= " ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, [$placeId]);
    }

    public function all(int $limit = 50, int $offset = 0)
    {
        return $this->db->fetchAll(
            "SELECT r.*, p.name as place_name 
             FROM reviews r
             JOIN places p ON r.place_id = p.id
             ORDER BY r.created_at DESC
             LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public function allByAdmin(int $adminId, int $limit = 50, int $offset = 0)
    {
        return $this->db->fetchAll(
            "SELECT r.*, p.name as place_name
             FROM reviews r
             JOIN places p ON r.place_id = p.id
             WHERE p.admin_id = ?
             ORDER BY r.created_at DESC
             LIMIT ? OFFSET ?",
            [$adminId, $limit, $offset]
        );
    }

    public function find(int $id)
    {
        return $this->db->fetch(
            "SELECT r.*, p.name as place_name 
             FROM reviews r
             JOIN places p ON r.place_id = p.id
             WHERE r.id = ?",
            [$id]
        );
    }

    public function create(array $data)
    {
        $this->db->query(
            "INSERT INTO reviews (place_id, reviewer_name, rating, comment, is_approved, created_at) 
             VALUES (?, ?, ?, ?, ?, NOW())",
            [
                $data['place_id'],
                $data['reviewer_name'] ?? 'Anonim',
                $data['rating'],
                $data['comment'] ?? '',
                $data['is_approved'] ?? 0  // Default perlu approval admin
            ]
        );
        return $this->db->lastInsertId();
    }

    public function approve(int $id)
    {
        $this->db->query(
            "UPDATE reviews SET is_approved = 1, updated_at = NOW() WHERE id = ?",
            [$id]
        );
        return true;
    }

    public function approveForAdmin(int $id, int $adminId)
    {
        $this->db->query(
            "UPDATE reviews r JOIN places p ON r.place_id = p.id
             SET r.is_approved = 1, r.updated_at = NOW()
             WHERE r.id = ? AND p.admin_id = ?",
            [$id, $adminId]
        );
    }

    public function reject(int $id)
    {
        $this->db->query("DELETE FROM reviews WHERE id = ?", [$id]);
        return true;
    }

    public function rejectForAdmin(int $id, int $adminId)
    {
        $this->db->query(
            "DELETE r FROM reviews r JOIN places p ON r.place_id = p.id
             WHERE r.id = ? AND p.admin_id = ?",
            [$id, $adminId]
        );
    }

    public function delete(int $id)
    {
        $this->db->query("DELETE FROM reviews WHERE id = ?", [$id]);
        return true;
    }

    public function deleteForAdmin(int $id, int $adminId)
    {
        $this->rejectForAdmin($id, $adminId);
    }

    public function countPending()
    {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM reviews WHERE is_approved = 0"
        );
        return $result['total'] ?? 0;
    }

    public function countPendingByAdmin(int $adminId)
    {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM reviews r
             JOIN places p ON r.place_id = p.id
             WHERE r.is_approved = 0 AND p.admin_id = ?",
            [$adminId]
        );
        return $result['total'] ?? 0;
    }

    public function countAll()
    {
        $result = $this->db->fetch("SELECT COUNT(*) as total FROM reviews");
        return $result['total'] ?? 0;
    }

    public function countAllByAdmin(int $adminId)
    {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM reviews r
             JOIN places p ON r.place_id = p.id WHERE p.admin_id = ?",
            [$adminId]
        );
        return $result['total'] ?? 0;
    }

    public function getStats()
    {
        return $this->db->fetch(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN is_approved = 1 THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN is_approved = 0 THEN 1 ELSE 0 END) as pending,
                COALESCE(AVG(CASE WHEN is_approved = 1 THEN rating END), 0) as avg_rating
             FROM reviews"
        );
    }

    public function getStatsByAdmin(int $adminId)
    {
        return $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN r.is_approved = 1 THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN r.is_approved = 0 THEN 1 ELSE 0 END) as pending,
                    COALESCE(AVG(CASE WHEN r.is_approved = 1 THEN r.rating END), 0) as avg_rating
             FROM reviews r JOIN places p ON r.place_id = p.id
             WHERE p.admin_id = ?",
            [$adminId]
        );
    }

    public function getMonthlyRecapByAdmin(int $adminId): array
    {
        return $this->db->fetchAll(
            "SELECT DATE_FORMAT(r.created_at, '%Y-%m') AS period,
                    DATE_FORMAT(r.created_at, '%M %Y') AS period_label,
                    COUNT(*) AS total_comments,
                    SUM(CASE WHEN r.is_approved = 1 THEN 1 ELSE 0 END) AS approved,
                    SUM(CASE WHEN r.is_approved = 0 THEN 1 ELSE 0 END) AS pending,
                    COALESCE(AVG(r.rating), 0) AS average_rating
             FROM reviews r
             JOIN places p ON r.place_id = p.id
             WHERE p.admin_id = ?
             GROUP BY DATE_FORMAT(r.created_at, '%Y-%m'), DATE_FORMAT(r.created_at, '%M %Y')
             ORDER BY period DESC",
            [$adminId]
        );
    }

    public function getYearlyRecapByAdmin(int $adminId): array
    {
        return $this->db->fetchAll(
            "SELECT YEAR(r.created_at) AS year,
                    COUNT(*) AS total_comments,
                    SUM(CASE WHEN r.is_approved = 1 THEN 1 ELSE 0 END) AS approved,
                    SUM(CASE WHEN r.is_approved = 0 THEN 1 ELSE 0 END) AS pending,
                    COALESCE(AVG(r.rating), 0) AS average_rating
             FROM reviews r
             JOIN places p ON r.place_id = p.id
             WHERE p.admin_id = ?
             GROUP BY YEAR(r.created_at)
             ORDER BY year DESC",
            [$adminId]
        );
    }
}
