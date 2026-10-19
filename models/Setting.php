<?php
/**
 * Settings model
 */

declare(strict_types=1);

class Setting
{
    private PDO $db;
    private static ?array $cache = null;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function get(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $row = $this->db->query('SELECT * FROM settings ORDER BY id ASC LIMIT 1')->fetch();

        self::$cache = $row ?: [
            'cafe_name' => 'CU Cafe',
            'cafe_name_ar' => 'سي يو',
            'logo' => 'cu_logo.png',
            'whatsapp' => '',
            'facebook' => '',
            'instagram' => '',
            'primary_color' => '#226F54',
            'secondary_color' => '#D33C3C',
            'welcome_text' => 'Fresh vibes. Soft sips. Sweet moments.',
            'welcome_text_ar' => 'أجواء منعشة. رشفات ناعمة. لحظات حلوة.',
            'contact_info' => '',
        ];

        return self::$cache;
    }

    public function update(array $data): bool
    {
        $current = $this->get();

        if (empty($current['id'])) {
            $stmt = $this->db->prepare(
                'INSERT INTO settings
                (cafe_name, cafe_name_ar, logo, whatsapp, facebook, instagram, primary_color, secondary_color, welcome_text, welcome_text_ar, contact_info)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $ok = $stmt->execute([
                $data['cafe_name'],
                $data['cafe_name_ar'] ?? '',
                $data['logo'] ?? 'cu_logo.png',
                $data['whatsapp'] ?? '',
                $data['facebook'] ?? '',
                $data['instagram'] ?? '',
                $data['primary_color'] ?? '#226F54',
                $data['secondary_color'] ?? '#D33C3C',
                $data['welcome_text'] ?? '',
                $data['welcome_text_ar'] ?? '',
                $data['contact_info'] ?? '',
            ]);
        } else {
            $stmt = $this->db->prepare(
                'UPDATE settings SET
                    cafe_name = ?, cafe_name_ar = ?, logo = ?, whatsapp = ?, facebook = ?, instagram = ?,
                    primary_color = ?, secondary_color = ?, welcome_text = ?, welcome_text_ar = ?, contact_info = ?
                 WHERE id = ?'
            );
            $ok = $stmt->execute([
                $data['cafe_name'],
                $data['cafe_name_ar'] ?? '',
                $data['logo'] ?? ($current['logo'] ?? 'cu_logo.png'),
                $data['whatsapp'] ?? '',
                $data['facebook'] ?? '',
                $data['instagram'] ?? '',
                $data['primary_color'] ?? '#226F54',
                $data['secondary_color'] ?? '#D33C3C',
                $data['welcome_text'] ?? '',
                $data['welcome_text_ar'] ?? '',
                $data['contact_info'] ?? '',
                (int) $current['id'],
            ]);
        }

        self::$cache = null;
        return $ok;
    }
}
