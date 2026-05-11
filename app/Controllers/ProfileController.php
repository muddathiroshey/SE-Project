<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\Data;
use App\Models\Specialist;


class ProfileController extends Controller
{
    private Client $profile;
    private Specialist $specialistProfile;
    private Data $auth;

    public function __construct()
    {
        parent::__construct();
        $this->profile = new Client();
        $this->specialistProfile = new Specialist();
        $this->auth    = new Data();
    }

    // ── GET /profile ──────────────────────────────────

    public function index(): void
    {
        $this->requireAuth();

        $user_id = (int) $_SESSION['user_id'];
        if (($_SESSION['role'] ?? '') === 'Freelancer') {
            if (!$this->specialistProfile->getByUserId($user_id)) {
                header("Location: /profile/setup");
                exit();
            }

            header("Location: /profile/edit");
            exit();
        }

        $client  = $this->profile->getByUserId($user_id);

        if (!$client) {
            header("Location: /profile/setup");
            exit();
        }

        header("Location: /profile/edit");
        exit();
    }

    // ── GET /profile/setup ────────────────────────────

    public function setup(): void
    {
        $this->requireAuth();
        if (($_SESSION['role'] ?? '') === 'Freelancer') {
            if ($this->specialistProfile->getByUserId((int) $_SESSION['user_id'])) {
                header("Location: /profile/edit");
                exit();
            }

            $this->view('profile/freelancer/index');
            return;
        }

        $this->requireRole('Client');

        $user_id = (int) $_SESSION['user_id'];

        if ($this->profile->getByUserId($user_id)) {
            header("Location: /profile/edit");
            exit();
        }

        $this->view('profile/client/index');
    }

    // ── POST /profile/setup ───────────────────────────

    public function store(): void
    {
        $this->requireAuth();
        if (($_SESSION['role'] ?? '') === 'Freelancer') {
            $this->storeSpecialist();
            return;
        }

        $this->requireRole('Client');

        $user_id = (int) $_SESSION['user_id'];
        $errors  = [];

        $name  = trim($_POST['personalName']  ?? '');
        $dob   = trim($_POST['personalDOB']   ?? '');
        $phone = trim($_POST['personalPhone'] ?? '');
        $bio   = trim($_POST['personalBio']   ?? '');

        if (!$name || !preg_match('/^[\p{L}\s]+$/u', $name)) {
            $errors[] = 'Full name is required (letters only).';
        }
        if (!preg_match('/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/', $dob)) {
            $errors[] = 'Date of birth must be in DD/MM/YYYY format.';
        }
        if (!preg_match('/^(\+|00)\d{6,15}$/', $phone)) {
            $errors[] = 'Phone number must start with + or 00.';
        }

        $upload    = $_FILES['idFile'] ?? null;
        $id_path   = '';
        $id_name   = '';
        $allowed   = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_bytes = 10 * 1024 * 1024;

        if (!$upload || $upload['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'ID document upload is required.';
        } elseif (!in_array($upload['type'], $allowed, true)) {
            $errors[] = 'ID must be an image (JPG, PNG, GIF, WebP).';
        } elseif ($upload['size'] > $max_bytes) {
            $errors[] = 'ID image must be 10 MB or less.';
        }

        if ($errors) {
            $this->view('profile/client/index', ['errors' => $errors]);
            return;
        }

        // --- Move uploaded ID ---
        $ext        = pathinfo($upload['name'], PATHINFO_EXTENSION);
        $id_name    = $upload['name'];
        $upload_dir = __DIR__ . '/../../public/uploads/kyc/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $id_path = 'uploads/kyc/' . $user_id . '_id_' . time() . '.' . $ext;

        if (!move_uploaded_file($upload['tmp_name'], $upload_dir . basename($id_path))) {
            $this->view('profile/client/index', [
                'errors' => ['File upload failed. Please try again.']
            ]);
            return;
        }

        // --- Insert profile ---
        $client_id = $this->profile->create($user_id, [
            'job_title'          => '',
            'country'            => '',
            'timezone'           => '',
            'phone_number'       => $phone,
            'hiring_description' => $bio,
        ]);

        if (!$client_id) {
            $this->view('profile/client/index', [
                'errors' => ['Could not create profile. Please try again.']
            ]);
            return;
        }

        // --- Attach ID document ---
        $this->profile->addKycDocument($client_id, [
            'doc_type'  => 'identity',
            'doc_title' => 'Government ID — ' . $name,
            'file_path' => $id_path,
            'file_name' => $id_name,
        ]);

        // --- Auto-verify (demo) ---
        $this->profile->verify($user_id, $client_id);
        $_SESSION['is_verified'] = 1;

        header("Location: /dashboard");
        exit();
    }

    // ── GET /profile/edit ─────────────────────────────

    public function edit(): void
    {
        $this->requireAuth();
        if (($_SESSION['role'] ?? '') === 'Freelancer') {
            $this->view('profile/freelancer/specialist-profile-edit');
            return;
        }

        $this->requireRole('Client');

        $user_id = (int) $_SESSION['user_id'];
        $client  = $this->profile->getByUserId($user_id);

        if (!$client) {
            header("Location: /profile/setup");
            exit();
        }

        $this->view('profile/client/client-profile-edit', [
            'client'      => $client,
            'kyc_docs'    => $this->profile->getKycDocuments($client['id']),
            'niche_prefs' => $this->profile->getNichePrefs($client['id']),
            'keywords'    => $this->profile->getKeywords($client['id']),
        ]);
    }

    // ── POST /profile/update ──────────────────────────

    public function update(): void
    {
        $this->requireAuth();
        $this->requireRole('Client');

        $user_id = (int) $_SESSION['user_id'];
        $client  = $this->profile->getByUserId($user_id);

        if (!$client) {
            header("Location: /profile/setup");
            exit();
        }

        $client_id = (int) $client['id'];
        $errors    = [];

        $phone = trim($_POST['phone_number'] ?? '');
        if ($phone && !preg_match('/^(\+|00)\d{6,15}$/', $phone)) {
            $errors[] = 'Phone number must start with + or 00.';
        }

        if ($errors) {
            $this->view('profile/client/client-profile-edit', [
                'client'      => $client,
                'kyc_docs'    => $this->profile->getKycDocuments($client_id),
                'niche_prefs' => $this->profile->getNichePrefs($client_id),
                'keywords'    => $this->profile->getKeywords($client_id),
                'errors'      => $errors,
            ]);
            return;
        }

        // --- Handle logo upload ---
        $logo = $_FILES['logo'] ?? null;
        if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
            $allowed_img = ['image/jpeg', 'image/png', 'image/svg+xml'];
            $max_bytes   = 5 * 1024 * 1024;

            if (!in_array($logo['type'], $allowed_img, true)) {
                $errors[] = 'Logo must be PNG, JPG, or SVG.';
            } elseif ($logo['size'] > $max_bytes) {
                $errors[] = 'Logo must be 5 MB or less.';
            } else {
                $ext        = pathinfo($logo['name'], PATHINFO_EXTENSION);
                $upload_dir = __DIR__ . '/../../public/uploads/logos/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $logo_path = 'uploads/logos/' . $client_id . '_logo_' . time() . '.' . $ext;

                if (move_uploaded_file($logo['tmp_name'], $upload_dir . basename($logo_path))) {
                    $this->profile->updateLogo($client_id, $logo_path);
                }
            }
        }

        // --- Main profile update ---
        $this->profile->update($client_id, [
            'job_title'          => trim($_POST['job_title']          ?? ''),
            'country'            => trim($_POST['country']            ?? ''),
            'timezone'           => trim($_POST['timezone']           ?? ''),
            'phone_number'       => $phone,
            'org_name'           => trim($_POST['org_name']           ?? ''),
            'org_type'           => trim($_POST['org_type']           ?? ''),
            'org_industry'       => trim($_POST['org_industry']       ?? ''),
            'org_industry_other' => trim($_POST['org_industry_other'] ?? ''),
            'org_website'        => trim($_POST['org_website']        ?? ''),
            'org_reg_country'    => trim($_POST['org_reg_country']    ?? ''),
            'org_reg_number'     => trim($_POST['org_reg_number']     ?? ''),
            'org_bio'            => trim($_POST['org_bio']            ?? ''),
            'org_address'        => trim($_POST['org_address']        ?? ''),
            'hiring_description' => trim($_POST['hiring_description'] ?? ''),
            'tax_jurisdiction'   => trim($_POST['tax_jurisdiction']   ?? ''),
            'vat_number'         => trim($_POST['vat_number']         ?? ''),
            'tax_id'             => trim($_POST['tax_id']             ?? ''),
            'billing_address'    => trim($_POST['billing_address']    ?? ''),
            'currency'           => trim($_POST['currency']           ?? 'USD'),
            'profile_active'     => isset($_POST['profile_active'])     ? 1 : 0,
            'show_project_count' => isset($_POST['show_project_count']) ? 1 : 0,
            'show_spend_band'    => isset($_POST['show_spend_band'])     ? 1 : 0,
            'allow_messages'     => isset($_POST['allow_messages'])      ? 1 : 0,
        ]);

        // --- Sync multi-value fields ---
        $niches   = array_filter(explode(',', $_POST['niche_prefs'] ?? ''));
        $keywords = array_filter(explode(',', $_POST['keywords']    ?? ''));

        $this->profile->syncNichePrefs($client_id, $niches);
        $this->profile->syncKeywords($client_id, $keywords);

        header("Location: /profile/edit?saved=1");
        exit();
    }

    // POST /profile/kyc/delete 

    public function deleteKycDoc(): void
    {
        $this->requireAuth();
        $this->requireRole('Client');

        $user_id = (int) $_SESSION['user_id'];
        $client  = $this->profile->getByUserId($user_id);
        $doc_id  = (int) ($_POST['doc_id'] ?? 0);

        if ($client && $doc_id) {
            $this->profile->deleteKycDocument($doc_id, (int) $client['id']);
        }

        header("Location: /profile/edit#sec-kyc");
        exit();
    }

    private function storeSpecialist(): void
    {
        $this->requireRole('Freelancer');

        $user_id = (int) $_SESSION['user_id'];
        $errors = [];

        $name = trim($_POST['full_name'] ?? '');
        $dob = trim($_POST['date_of_birth'] ?? '');
        $phone = trim($_POST['phone_number'] ?? '');
        $niche = trim($_POST['primary_niche'] ?? '');
        $education = trim($_POST['education_level'] ?? '');
        $skills = array_filter(array_map('trim', explode(',', $_POST['skills'] ?? '')));

        if (!$name || !preg_match('/^[\p{L}\s]+$/u', $name)) {
            $errors[] = 'Full name is required (letters only).';
        }
        if (!preg_match('/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/', $dob)) {
            $errors[] = 'Date of birth must be in DD/MM/YYYY format.';
        }
        if (!preg_match('/^(\+|00)\d{6,15}$/', $phone)) {
            $errors[] = 'Phone number must start with + or 00.';
        }
        if ($niche === '') {
            $errors[] = 'Primary niche is required.';
        }
        if (!in_array($education, ['high-school', 'bachelor', 'master', 'phd'], true)) {
            $errors[] = 'Education level is required.';
        }
        if (!$skills) {
            $errors[] = 'Select at least one skill.';
        }

        $idUpload = $_FILES['id_file'] ?? null;
        $educationUpload = $_FILES['education_file'] ?? null;
        foreach ([[$idUpload, 'ID document'], [$educationUpload, 'Education proof']] as [$upload, $label]) {
            if (!$upload || $upload['error'] !== UPLOAD_ERR_OK) {
                $errors[] = "{$label} upload is required.";
            }
        }

        if ($errors) {
            $this->view('profile/freelancer/index', ['errors' => $errors]);
            return;
        }

        [$day, $month, $year] = explode('/', $dob);
        $profileId = $this->specialistProfile->create($user_id, [
            'full_legal_name' => $name,
            'date_of_birth' => "{$year}-{$month}-{$day}",
            'phone_number' => $phone,
            'primary_niche' => $niche,
            'education_level' => $education,
            'summary' => '',
        ]);

        if (!$profileId) {
            $this->view('profile/freelancer/index', ['errors' => ['Could not create specialist profile.']]);
            return;
        }

        $this->specialistProfile->syncSkills($user_id, $skills);
        $this->storeSpecialistDocument($user_id, $idUpload, 'identity', 'Government ID');
        $this->storeSpecialistDocument($user_id, $educationUpload, 'education', 'Education proof');
        $this->specialistProfile->verifyUser($user_id);
        $_SESSION['is_verified'] = 1;

        header("Location: /dashboard");
        exit();
    }

    private function storeSpecialistDocument(int $userId, array $upload, string $type, string $title): void
    {
        $uploadDir = __DIR__ . '/../../public/uploads/kyc/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($upload['name'], PATHINFO_EXTENSION);
        $safeName = $userId . '_' . $type . '_' . time() . '.' . $ext;
        if (move_uploaded_file($upload['tmp_name'], $uploadDir . $safeName)) {
            $this->specialistProfile->addVerificationDocument($userId, [
                'doc_type' => $type,
                'doc_title' => $title,
                'file_path' => 'uploads/kyc/' . $safeName,
                'file_name' => $upload['name'],
            ]);
        }
    }

    //HELPERS 

    protected function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    protected function requireRole(string $role): void
    {
        $this->requireAuth();

        if (($_SESSION['role'] ?? '') !== $role) {
            $this->redirect('/dashboard');
        }
    }
}
