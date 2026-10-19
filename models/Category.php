<?php
/**
 * Category model
 */

declare(strict_types=1);

class Category
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(bool $activeOnly = false): array
    {
        $sql = 'SELECT * FROM categories';
        if ($activeOnly) {
            $sql .= ' WHERE status = 1';
        }
        $sql .= ' ORDER BY sort_order ASC, id ASC';

        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM categories')->fetchColumn();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (name_en, name_ar, sort_order, status) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['name_en'],
            $data['name_ar'],
            (int) $data['sort_order'],
            (int) $data['status'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE categories SET name_en = ?, name_ar = ?, sort_order = ?, status = ? WHERE id = ?'
        );

        return $stmt->execute([
            $data['name_en'],
            $data['name_ar'],
            (int) $data['sort_order'],
            (int) $data['status'],
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
