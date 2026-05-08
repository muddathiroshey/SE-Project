<?php
namespace App\Models;

class Specialist
{
    private \mysqli $db;

    public function __construct()
    {
        $data = new Data();
        $this->db = $data->getDb();
    }

    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM specialistProfiles WHERE user_id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        return $row;
    }

    public function create(int $userId, array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO specialistProfiles
                (user_id, full_legal_name, date_of_birth, phone_number, primary_niche, education_level, summary, profile_status)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'approved')"
        );
        $stmt->bind_param(
            'issssss',
            $userId,
            $data['full_legal_name'],
            $data['date_of_birth'],
            $data['phone_number'],
            $data['primary_niche'],
            $data['education_level'],
            $data['summary']
        );
        $stmt->execute();
        $id = $stmt->affected_rows > 0 ? $this->db->insert_id : 0;
        $stmt->close();

        return $id;
    }

    public function syncSkills(int $userId, array $skills): void
    {
        $del = $this->db->prepare('DELETE FROM specialistSkills WHERE user_id = ?');
        $del->bind_param('i', $userId);
        $del->execute();
        $del->close();

        if (!$skills) {
            return;
        }

        $ins = $this->db->prepare('INSERT IGNORE INTO specialistSkills (user_id, skill_name) VALUES (?, ?)');
        foreach ($skills as $skill) {
            $skill = trim($skill);
            if ($skill === '') {
                continue;
            }
            $ins->bind_param('is', $userId, $skill);
            $ins->execute();
        }
        $ins->close();
    }

    public function addVerificationDocument(int $userId, array $doc): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO verificationDocuments (user_id, doc_type, doc_title, file_path, file_name)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'issss',
            $userId,
            $doc['doc_type'],
            $doc['doc_title'],
            $doc['file_path'],
            $doc['file_name']
        );
        $stmt->execute();
        $id = $stmt->affected_rows > 0 ? $this->db->insert_id : 0;
        $stmt->close();

        return $id;
    }

    public function verifyUser(int $userId): void
    {
        $stmt = $this->db->prepare('UPDATE userData SET is_verified = 1 WHERE id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();
    }
}
