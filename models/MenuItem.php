<?php
/**
 * Menu Item model
 */

declare(strict_types=1);

class MenuItem
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(bool $activeOnly = false): array
    {
        $sql = 'SELECT m.*, c.name_en AS category_name_en, c.name_ar AS category_name_ar
                FROM menu_items m
                LEFT JOIN categories c ON c.id = m.category_id';
        if ($activeOnly) {
            $sql .= ' WHERE m.status = 1 AND c.status = 1';
        }
        $sql .= ' ORDER BY c.sort_order ASC, m.sort_order ASC, m.id ASC';

        return $this->db->query($sql)->fetchAll();
    }

    public function byCategory(?int $categoryId = null, bool $activeOnly = true): array
    {
        $sql = 'SELECT m.*, c.name_en AS category_name_en, c.name_ar AS category_name_ar
                FROM menu_items m
                INNER JOIN categories c ON c.id = m.category_id
                WHERE 1=1';
        $params = [];

        if ($activeOnly) {
            $sql .= ' AND m.status = 1 AND c.status = 1';
        }

        if ($categoryId !== null && $categoryId > 0) {
            $sql .= ' AND m.category_id = ?';
            $params[] = $categoryId;
        }

        $sql .= ' ORDER BY m.sort_order ASC, m.id ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function search(string $query, ?int $categoryId = null): array
    {
        $sql = 'SELECT m.*, c.name_en AS category_name_en, c.name_ar AS category_name_ar
                FROM menu_items m
                INNER JOIN categories c ON c.id = m.category_id
                WHERE m.status = 1 AND c.status = 1
                  AND (m.name_en LIKE ? OR m.name_ar LIKE ?
                       OR m.description_en LIKE ? OR m.description_ar LIKE ?)';
        $like = '%' . $query . '%';
        $params = [$like, $like, $like, $like];

        if ($categoryId !== null && $categoryId > 0) {
            $sql .= ' AND m.category_id = ?';
            $params[] = $categoryId;
        }

        $sql .= ' ORDER BY m.sort_order ASC, m.id ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM menu_items WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM menu_items')->fetchColumn();
    }

    public function recent(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT m.*, c.name_en AS category_name_en, c.name_ar AS category_name_ar
             FROM menu_items m
             LEFT JOIN categories c ON c.id = m.category_id
             ORDER BY m.created_at DESC, m.id DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO menu_items
            (category_id, name_en, name_ar, description_en, description_ar, price, status, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            (int) $data['category_id'],
            $data['name_en'],
            $data['name_ar'],
            $data['description_en'],
            $data['description_ar'],
            $data['price'],
            (int) $data['status'],
            (int) $data['sort_order'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE menu_items SET
                category_id = ?, name_en = ?, name_ar = ?,
                description_en = ?, description_ar = ?,
                price = ?, status = ?, sort_order = ?
             WHERE id = ?'
        );

        return $stmt->execute([
            (int) $data['category_id'],
            $data['name_en'],
            $data['name_ar'],
            $data['description_en'],
            $data['description_ar'],
            $data['price'],
            (int) $data['status'],
            (int) $data['sort_order'],
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM menu_items WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
