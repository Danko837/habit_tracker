<?php

declare(strict_types=1);

class Habit extends BaseModel
{
    public function allForUser(int $userId): array
    {
        $sql = 'SELECT h.*,
                   SUM(CASE WHEN hl.completed = 1 THEN 1 ELSE 0 END) AS completed_count,
                   MAX(CASE WHEN hl.completed = 1 THEN hl.log_date ELSE NULL END) AS last_completed,
                   MAX(CASE WHEN hl.log_date = CURDATE() AND hl.completed = 1 THEN 1 ELSE 0 END) AS completed_today
                FROM habits h
                LEFT JOIN habit_logs hl ON hl.habit_id = h.id
                WHERE h.user_id = :user_id
                GROUP BY h.id
                ORDER BY h.is_active DESC, h.created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function findForUser(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM habits WHERE id = :id AND user_id = :user_id LIMIT 1');
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $userId, array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO habits (user_id, title, description, frequency, target_count, start_date, is_active)
            VALUES (:user_id, :title, :description, :frequency, :target_count, :start_date, :is_active)');
        return $stmt->execute([
            ':user_id' => $userId,
            ':title' => $data['title'],
            ':description' => $data['description'] ?: null,
            ':frequency' => $data['frequency'],
            ':target_count' => $data['target_count'],
            ':start_date' => $data['start_date'],
            ':is_active' => $data['is_active'],
        ]);
    }

    public function update(int $id, int $userId, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE habits
            SET title = :title, description = :description, frequency = :frequency, target_count = :target_count,
                start_date = :start_date, is_active = :is_active
            WHERE id = :id AND user_id = :user_id');
        return $stmt->execute([
            ':id' => $id,
            ':user_id' => $userId,
            ':title' => $data['title'],
            ':description' => $data['description'] ?: null,
            ':frequency' => $data['frequency'],
            ':target_count' => $data['target_count'],
            ':start_date' => $data['start_date'],
            ':is_active' => $data['is_active'],
        ]);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM habits WHERE id = :id AND user_id = :user_id');
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }

    public function toggleToday(int $id, int $userId): bool
    {
        $habit = $this->findForUser($id, $userId);
        if (!$habit) {
            return false;
        }

        $stmt = $this->db->prepare('SELECT id, completed FROM habit_logs WHERE habit_id = :habit_id AND log_date = CURDATE() LIMIT 1');
        $stmt->execute([':habit_id' => $id]);
        $log = $stmt->fetch();

        if ($log) {
            $newValue = (int) !$log['completed'];
            $update = $this->db->prepare('UPDATE habit_logs SET completed = :completed WHERE id = :id');
            return $update->execute([':completed' => $newValue, ':id' => $log['id']]);
        }

        $insert = $this->db->prepare('INSERT INTO habit_logs (habit_id, log_date, completed) VALUES (:habit_id, CURDATE(), 1)');
        return $insert->execute([':habit_id' => $id]);
    }

    public function statsForUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active
            FROM habits WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        $habitStats = $stmt->fetch() ?: ['total' => 0, 'active' => 0];

        $logStmt = $this->db->prepare('SELECT COUNT(*) AS completed_today
            FROM habit_logs hl
            JOIN habits h ON h.id = hl.habit_id
            WHERE h.user_id = :user_id AND hl.log_date = CURDATE() AND hl.completed = 1');
        $logStmt->execute([':user_id' => $userId]);
        $logStats = $logStmt->fetch() ?: ['completed_today' => 0];

        return [
            'total' => (int) ($habitStats['total'] ?? 0),
            'active' => (int) ($habitStats['active'] ?? 0),
            'completed_today' => (int) ($logStats['completed_today'] ?? 0),
        ];
    }

    public function logsForUser(int $userId, int $days = 14): array
    {
        $stmt = $this->db->prepare('SELECT hl.log_date, COUNT(*) AS completed
            FROM habit_logs hl
            JOIN habits h ON h.id = hl.habit_id
            WHERE h.user_id = :user_id AND hl.completed = 1 AND hl.log_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
            GROUP BY hl.log_date
            ORDER BY hl.log_date DESC');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
