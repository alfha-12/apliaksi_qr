<?php
namespace App\Models;

use App\Core\Database;

class Place
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Daftar publik: tempat aktif dari seluruh admin.
    public function all()
    {
        return $this->db->fetchAll(
            "SELECT p.*,
                    COUNT(r.id) as total_reviews,
                    COALESCE(AVG(r.rating), 0) as avg_rating
             FROM places p
             LEFT JOIN reviews r ON p.id = r.place_id AND r.is_approved = 1
             WHERE p.status = 'active'
             GROUP BY p.id
             ORDER BY p.created_at DESC"
        );
    }

    public function allByAdmin(int $adminId)
    {
        return $this->db->fetchAll(
            "SELECT p.*, 
                    COUNT(r.id) as total_reviews,
                    COALESCE(AVG(r.rating), 0) as avg_rating
             FROM places p
             LEFT JOIN reviews r ON p.id = r.place_id AND r.is_approved = 1
             WHERE p.admin_id = ?
             GROUP BY p.id
             ORDER BY p.created_at DESC"
            , [$adminId]
        );
    }

    public function findForAdmin(int $id, int $adminId)
    {
        return $this->db->fetch(
            "SELECT p.*, 
                    COUNT(r.id) as total_reviews,
                    COALESCE(AVG(r.rating), 0) as avg_rating
             FROM places p
             LEFT JOIN reviews r ON p.id = r.place_id AND r.is_approved = 1
             WHERE p.id = ? AND p.admin_id = ?
             GROUP BY p.id",
            [$id, $adminId]
        );
    }

    public function findByCode(string $code)
    {
        return $this->db->fetch(
            "SELECT p.*, 
                    COUNT(r.id) as total_reviews,
                    COALESCE(AVG(r.rating), 0) as avg_rating
             FROM places p
             LEFT JOIN reviews r ON p.id = r.place_id AND r.is_approved = 1
             WHERE p.barcode_code = ?
             GROUP BY p.id",
            [$code]
        );
    }

    public function create(array $data)
    {
        $code = $data['barcode_code'] ?? generateCode(10);
        
        $this->db->query(
            "INSERT INTO places (admin_id, name, description, address, category, barcode_code, image, status, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [
                $data['admin_id'],
                $data['name'],
                $data['description'] ?? '',
                $data['address'] ?? '',
                $data['category'] ?? 'umum',
                $code,
                $data['image'] ?? null,
                $data['status'] ?? 'active'
            ]
        );
        return $this->db->lastInsertId();
    }

    public function updateForAdmin(int $id, int $adminId, array $data)
    {
        $fields = [];
        $params = [];
        
        foreach (['name', 'description', 'address', 'category', 'image', 'status'] as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($fields)) return false;
        
        $params[] = $id;
        $params[] = $adminId;
        $this->db->query(
            "UPDATE places SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ? AND admin_id = ?",
            $params
        );
        return true;
    }

    public function deleteForAdmin(int $id, int $adminId)
    {
        // Review terhapus otomatis oleh foreign key ON DELETE CASCADE.
        $this->db->query("DELETE FROM places WHERE id = ? AND admin_id = ?", [$id, $adminId]);
        return true;
    }

    public function countByAdmin(int $adminId)
    {
        $result = $this->db->fetch("SELECT COUNT(*) as total FROM places WHERE admin_id = ?", [$adminId]);
        return $result['total'] ?? 0;
    }

    public function getCategories()
    {
        return [
            'restoran'   => 'Restoran',
            'kafe'       => 'Kafe',
            'hotel'      => 'Hotel',
            'wisata'     => 'Tempat Wisata',
            'toko'       => 'Toko / Mall',
            'kantor'     => 'Kantor',
            'fasilitas'  => 'Fasilitas Umum',
            'umum'       => 'Lainnya'
        ];
    }
}
