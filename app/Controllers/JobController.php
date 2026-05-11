<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\Job;
use App\Models\Specialist;


class JobController extends Controller
{
    private Job $job;

    public function __construct()
    {
        parent::__construct();
        $this->job = new Job();
    }

    // ── GET /browse-jobs  (Specialist) ────────────────────────
    public function browseJobs(): void
    {
        $this->requireRole('Freelancer');

        $filters = [
            'niche'      => trim($_GET['niche']      ?? ''),
            'q'          => trim($_GET['q']           ?? ''),
            'budget_min' => $_GET['budget_min']       ?? '',
            'budget_max' => $_GET['budget_max']       ?? '',
        ];
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $result = $this->job->browse($filters, $page);
        $niches = $this->job->getNichesWithCounts();

        $specialistModel = new Specialist();
        $specialist      = $specialistModel->getByUserId((int) $_SESSION['user_id']);

        $this->view('dashboard/specialist/browse-jobs', [
            'jobs'       => $result['data'],
            'total'      => $result['total'],
            'pages'      => $result['pages'],
            'page'       => $page,
            'filters'    => $filters,
            'niches'     => $niches,
            'specialist' => $specialist,
        ]);
    }

    // ── GET /browse-experts  (Client) ─────────────────────────
    public function browseExperts(): void
    {
        $this->requireRole('Client');

        $filters = [
            'niche'          => trim($_GET['niche']          ?? ''),
            'q'              => trim($_GET['q']              ?? ''),
            'verified_only'  => (bool) ($_GET['verified']   ?? false),
        ];
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $result = $this->job->browseExperts($filters, $page);
        $niches = $this->job->getNichesWithCounts();

        $clientModel = new Client();
        $client      = $clientModel->getByUserId((int) $_SESSION['user_id']);

        $this->view('dashboard/client/browse-experts', [
            'experts' => $result['data'],
            'total'   => $result['total'],
            'pages'   => $result['pages'],
            'page'    => $page,
            'filters' => $filters,
            'niches'  => $niches,
            'client'  => $client,
        ]);
    }

    // ── GET /incoming-bids  (Client) ──────────────────────────
    public function incomingBids(): void
    {
        $this->requireRole('Client');

        $user_id     = (int) $_SESSION['user_id'];
        $clientModel = new Client();
        $client      = $clientModel->getByUserId($user_id);

        if (!$client) {
            $this->redirect('/profile/setup');
        }

        $filters = [
            'status' => trim($_GET['status'] ?? ''),
            'job_id' => (int) ($_GET['project'] ?? 0) ?: null,
        ];

        $bids     = $this->job->getIncomingBids((int) $client['id'], $filters);
        $projects = $this->job->getByClientId((int) $client['id']);

        // Stats banner
        $stats = [
            'total_bids'    => count($bids),
            'new_bids'      => count(array_filter($bids, fn($b) => $b['status'] === 'submitted')),
            'shortlisted'   => count(array_filter($bids, fn($b) => $b['status'] === 'shortlisted')),
            'projects_open' => count(array_filter($projects, fn($p) => $p['status'] === 'posted')),
        ];

        $this->view('dashboard/client/incoming-bids', [
            'bids'           => $bids,
            'projects'       => $projects,
            'stats'          => $stats,
            'active_filters' => $filters,
            'client'         => $client,
        ]);
    }

    // ── GET /my-bids  (Specialist) ────────────────────────────
    public function myBids(): void
    {
        $this->requireRole('Freelancer');

        $user_id = (int) $_SESSION['user_id'];
        $status  = trim($_GET['status'] ?? '');

        $proposals = $this->job->getMyBids($user_id, $status);
        $stats     = $this->job->getBidStats($user_id);

        $specialistModel = new Specialist();
        $specialist      = $specialistModel->getByUserId($user_id);

        $this->view('dashboard/specialist/my-bids', [
            'proposals'  => $proposals,
            'stats'      => $stats,
            'filters'    => ['status' => $status],
            'specialist' => $specialist,
        ]);
    }

    // ── GET /job-view?id={n} ──────────────────────────────────
    public function jobView(): void
    {
        $this->requireAuth();

        $id  = (int) ($_GET['id'] ?? 0);
        $job = $id ? $this->job->getById($id) : null;

        if (!$job) {
            $this->redirect('/browse-jobs');
        }

        $this->view('job/job-view', ['job' => $job]);
    }
}
