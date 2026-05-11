<?php
namespace App\Controllers;

use App\Core\Controller;


class PageController extends Controller
{
    public function chat(): void
    {
        $this->requireAuth();
        $this->view('chat');
    }

    public function dispute(): void
    {
        $this->requireAuth();
        $this->view('dispute/dispute');
    }

    public function browseExperts(): void
    {
        $this->requireRole('Client');
        $this->view('dashboard/client/browse-experts');
    }

    public function browseJobs(): void
    {
        $this->requireRole('Freelancer');
        $this->view('dashboard/specialist/browse-jobs');
    }

    public function incomingBids(): void
    {
        $this->requireRole('Client');
        $this->view('dashboard/client/incoming-bids');
    }

    public function adminDashboard(): void
    {
        $this->requireRole('Admin');
        $this->view('admin/admin-dashboard');
    }
}
