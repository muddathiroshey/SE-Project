<?php
namespace App\Models;

class Client
{
    private \mysqli $db;

    public function __construct()
    {
        $data     = new Data();
        $this->db = $data->getDb();
    }

    // ── READ ──────────────────────────────────────────

    public function getByUserId(int $user_id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT cp.*, u.user_email, u.user_name, u.is_verified
            FROM clientProfile cp
            JOIN userData u ON u.id = cp.user_id
            WHERE cp.user_id = ?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    public function getKycDocuments(int $client_id): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM clientKycDocuments WHERE client_id = ? ORDER BY uploaded_at DESC"
        );
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getNichePrefs(int $client_id): array
    {
        $stmt = $this->db->prepare(
            "SELECT niche_name FROM clientNichePrefs WHERE client_id = ?"
        );
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return array_column($rows, 'niche_name');
    }

    public function getKeywords(int $client_id): array
    {
        $stmt = $this->db->prepare(
            "SELECT keyword FROM clientKeywords WHERE client_id = ?"
        );
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return array_column($rows, 'keyword');
    }

    // ── WRITE ─────────────────────────────────────────

    public function create(int $user_id, array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO clientProfile
                (user_id, job_title, country, timezone, phone_number, hiring_description)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "isssss",
            $user_id,
            $data['job_title'],
            $data['country'],
            $data['timezone'],
            $data['phone_number'],
            $data['hiring_description']
        );
        $stmt->execute();
        $id = $stmt->affected_rows > 0 ? $this->db->insert_id : 0;
        $stmt->close();
        return $id;
    }

    public function update(int $client_id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE clientProfile SET
                job_title          = ?,
                country            = ?,
                timezone           = ?,
                phone_number       = ?,
                org_name           = ?,
                org_type           = ?,
                org_industry       = ?,
                org_industry_other = ?,
                org_website        = ?,
                org_reg_country    = ?,
                org_reg_number     = ?,
                org_bio            = ?,
                org_address        = ?,
                hiring_description = ?,
                tax_jurisdiction   = ?,
                vat_number         = ?,
                tax_id             = ?,
                billing_address    = ?,
                currency           = ?,
                profile_active     = ?,
                show_project_count = ?,
                show_spend_band    = ?,
                allow_messages     = ?
             WHERE id = ?"
        );
        $stmt->bind_param(
            "sssssssssssssssssssiiiis",
            $data['job_title'],
            $data['country'],
            $data['timezone'],
            $data['phone_number'],
            $data['org_name'],
            $data['org_type'],
            $data['org_industry'],
            $data['org_industry_other'],
            $data['org_website'],
            $data['org_reg_country'],
            $data['org_reg_number'],
            $data['org_bio'],
            $data['org_address'],
            $data['hiring_description'],
            $data['tax_jurisdiction'],
            $data['vat_number'],
            $data['tax_id'],
            $data['billing_address'],
            $data['currency'],
            $data['profile_active'],
            $data['show_project_count'],
            $data['show_spend_band'],
            $data['allow_messages'],
            $client_id
        );
        $stmt->execute();
        $ok = $stmt->affected_rows >= 0;
        $stmt->close();
        return $ok;
    }

    public function updateLogo(int $client_id, string $path): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE clientProfile SET logo_path = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $path, $client_id);
        $stmt->execute();
        $ok = $stmt->affected_rows > 0;
        $stmt->close();
        return $ok;
    }

    // ── VERIFY (demo — auto-approve on ID upload) ─────

    public function verify(int $user_id, int $client_id): void
    {
        $stmt = $this->db->prepare(
            "UPDATE userData SET is_verified = 1 WHERE id = ?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->db->prepare(
            "UPDATE clientProfile SET kyc_status = 'verified', kyc_verified_at = NOW() WHERE id = ?"
        );
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $stmt->close();
    }

    // ── KYC DOCUMENTS ─────────────────────────────────

    public function addKycDocument(int $client_id, array $doc): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO clientKycDocuments (client_id, doc_type, doc_title, file_path, file_name)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "issss",
            $client_id,
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

    public function deleteKycDocument(int $doc_id, int $client_id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM clientKycDocuments WHERE id = ? AND client_id = ?"
        );
        $stmt->bind_param("ii", $doc_id, $client_id);
        $stmt->execute();
        $ok = $stmt->affected_rows > 0;
        $stmt->close();
        return $ok;
    }

    // ── NICHE PREFS ───────────────────────────────────

    public function syncNichePrefs(int $client_id, array $niches): void
    {
        $del = $this->db->prepare("DELETE FROM clientNichePrefs WHERE client_id = ?");
        $del->bind_param("i", $client_id);
        $del->execute();
        $del->close();

        if (empty($niches)) return;

        $ins = $this->db->prepare(
            "INSERT IGNORE INTO clientNichePrefs (client_id, niche_name) VALUES (?, ?)"
        );
        foreach ($niches as $niche) {
            $niche = trim($niche);
            if ($niche === '') continue;
            $ins->bind_param("is", $client_id, $niche);
            $ins->execute();
        }
        $ins->close();
    }

    // ── KEYWORDS ──────────────────────────────────────

    public function syncKeywords(int $client_id, array $keywords): void
    {
        $del = $this->db->prepare("DELETE FROM clientKeywords WHERE client_id = ?");
        $del->bind_param("i", $client_id);
        $del->execute();
        $del->close();

        if (empty($keywords)) return;

        $ins = $this->db->prepare(
            "INSERT IGNORE INTO clientKeywords (client_id, keyword) VALUES (?, ?)"
        );
        foreach ($keywords as $kw) {
            $kw = trim($kw);
            if ($kw === '') continue;
            $ins->bind_param("is", $client_id, $kw);
            $ins->execute();
        }
        $ins->close();
    }
}