<?php
// views/dashboard/client/dashboard-client-empty.php
$user_name = htmlspecialchars($_SESSION['user_name'] ?? 'there');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Specialist Dashboard — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/dashboard-specialist.css">
</head>
<body>

<?php require __DIR__ . '/../../partials/topnav.php'; ?>

<div class="app-shell">

  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-label">Overview</div>
      <a class="sidebar-link active" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
        Dashboard
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Work</div>
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4a1 1 0 0 1 1-1h3l1 1h6a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4z"/></svg>
        Active Projects
        <span class="notif-count" style="margin-left:auto;">2</span>
      </a>
      <a class="sidebar-link" href="/dashboard/my-bids">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M3 2h10a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v1h8V4H4zm0 2v1h8V6H4z"/></svg>
        My Proposals
        <span class="notif-count" style="margin-left:auto;">5</span>
      </a>
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M6 1h4a1 1 0 0 1 1 1v2H5V2a1 1 0 0 1 1-1z"/><path d="M3 4h10v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z"/></svg>
        Completed Work
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Marketplace</div>
      <a class="sidebar-link" href="#">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M11 11l3 3-1 1-3-3v-1.4A5.5 5.5 0 1 1 11 11zM6.5 11A4.5 4.5 0 1 0 6.5 2a4.5 4.5 0 0 0 0 9z"/></svg>
        Browse Jobs
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Profile</div>
      <a class="sidebar-link" href="/profile">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M2 14s1-1.5 6-1.5S14 14 14 14v1H2v-1z"/></svg>
        My Profile
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Finance</div>
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12v8H2V4zm1 1v6h10V5H3zm2 2h2v2H5V7z"/></svg>
        Earnings &amp; Payouts
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Support</div>
      <a class="sidebar-link" href="/dispute">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1zm0 4a.75.75 0 0 0 0 1.5.75.75 0 0 0 0-1.5zm-.25 3v4.5h1.5V7h-1.5z"/></svg>
        Disputes
      </a>
      <a class="sidebar-link" href="/chat">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 1h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-3l-4 3v-3H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>
        Messages
      </a>
    </div>
  </aside>

  <main class="main-content">

    <!-- PAGE HEADER -->
    <div class="page-header flex justify-between items-center">
      <div>
        <div class="breadcrumb">Specialist Dashboard</div>
        <h2>Welcome back, Dr. Khalil.</h2>
        <p class="mt-4">You have <strong>2 active projects</strong> and <strong>8 new job matches</strong> in your niche.</p>
      </div>
    </div>

    <!-- STATS -->
    <div class="grid-5 mb-32">
      <div class="stat-card">
        <div class="stat-value">$36,200</div>
        <div class="stat-label">Total Earned (YTD)</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">2</div>
        <div class="stat-label">Active Projects</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">4.97</div>
        <div class="stat-label">Reputation Score</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">92%</div>
        <div class="stat-label">Acceptance Rate</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">4d</div>
        <div class="stat-label">Nearest Deadline</div>
      </div>
    </div>

    <div class="grid-2 mb-32">

      <!-- EARNINGS BREAKDOWN -->
      <div class="card">
        <h3 class="mb-4">Earnings Overview</h3>
        <div class="earnings-bar mb-16">
          <div class="earnings-segment" style="flex:3;background:var(--sage);">Cleared</div>
          <div class="earnings-segment" style="flex:1;background:var(--gold);">Pending</div>
          <div class="earnings-segment" style="flex:0.5;background:var(--border);color:var(--ink-muted);">Hold</div>
        </div>
        <div class="flex justify-between">
          <div><div style="font-family:var(--font-mono);font-weight:500;">$20,400</div><div class="text-xs text-muted">Cleared</div></div>
          <div><div style="font-family:var(--font-mono);font-weight:500;">$6,800</div><div class="text-xs text-muted">Pending</div></div>
          <div><div style="font-family:var(--font-mono);font-weight:500;">$1,000</div><div class="text-xs text-muted">On Hold</div></div>
        </div>
        <hr class="divider">
        <a href="/dashboard" class="btn btn-outline btn-sm w-full" style="justify-content:center;">View Full Earnings →</a>
      </div>

      <!-- NEAREST MILESTONE DEADLINE -->
      <div class="card">
        <h3 class="mb-4">Nearest Milestone Deadline</h3>
        <p class="mb-16 text-sm text-muted">Active project with the closest upcoming milestone.</p>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
          <div style="flex:1;min-width:240px;">
            <div style="font-weight:700;font-size:.9375rem;">MENA Expansion — Cross-Border Contract Review</div>
            <div class="text-xs text-muted" style="margin-top:6px;">NX-2025-4821 · Legal Consulting · Budget $12,000</div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;">
              <span class="badge badge-gold">Milestone 2 of 6</span>
              <span class="badge badge-info">Due in 4 days</span>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;min-width:120px;">
            <div style="font-size:2rem;font-weight:700;line-height:1;">4d</div>
            <a href="/project-detail" class="btn btn-primary btn-sm">Go To Project</a>
          </div>
        </div>
      </div>

    </div>

    <!-- JOB MATCHES + PROPOSALS -->
    <div class="grid-2">

      <div class="card card-flush">
        <div class="card-header flex justify-between items-center">
          <h3>Matched Jobs <span class="notif-count" style="margin-left:8px;">8</span></h3>
          <button class="btn btn-ghost btn-sm">Browse All</button>
        </div>
        <div class="card-body">

          <div class="job-match">
            <div class="flex justify-between items-start mb-6">
              <div style="font-weight:700;font-size:.9375rem;">Real-Time Anomaly Detection Pipeline</div>
              <span class="match-score">95% match</span>
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px;">
              <span class="tag">Python</span><span class="tag">Kafka</span><span class="tag">ML Ops</span>
              <span class="badge badge-gold">Data Science</span>
            </div>
            <div class="flex justify-between items-center">
              <span style="font-family:var(--font-mono);font-size:.875rem;">$12,000 — $18,000 · 6 milestones</span>
              <a href="/job-view" class="btn btn-outline btn-sm">View Full Details</a>
            </div>
          </div>

          <div class="job-match">
            <div class="flex justify-between items-start mb-6">
              <div style="font-weight:700;font-size:.9375rem;">NLP Sentiment Analysis — Arabic/English</div>
              <span class="match-score">88% match</span>
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px;">
              <span class="tag">NLP</span><span class="tag">Arabic</span><span class="tag">BERT</span>
              <span class="badge badge-gold">Data Science</span>
            </div>
            <div class="flex justify-between items-center">
              <span style="font-family:var(--font-mono);font-size:.875rem;">$7,500 · 4 milestones</span>
              <a href="/job-view" class="btn btn-outline btn-sm">View Full Details</a>
            </div>
          </div>

          <div class="job-match">
            <div class="flex justify-between items-start mb-6">
              <div style="font-weight:700;font-size:.9375rem;">Customer Segmentation — E-commerce Platform</div>
              <span class="match-score">81% match</span>
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px;">
              <span class="tag">Clustering</span><span class="tag">Tableau</span>
              <span class="badge badge-gold">Data Science</span>
            </div>
            <div class="flex justify-between items-center">
              <span style="font-family:var(--font-mono);font-size:.875rem;">$5,200 · 3 milestones</span>
              <a href="/job-view" class="btn btn-outline btn-sm">View Full Details</a>
            </div>
          </div>

        </div>
      </div>

      <div class="card card-flush">
        <div class="card-header flex justify-between items-center">
          <h3>Active Proposals</h3>
        </div>
        <div class="card-body">

          <div class="proposal-item">
            <div class="proposal-status-dot" style="background:var(--gold);"></div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.875rem;">Predictive Churn Model — FinCorp</div>
              <div class="text-xs text-muted">Submitted Apr 10 · 5 milestones · $9,200</div>
              <div class="text-xs" style="color:var(--gold);margin-top:2px;">Client reviewing</div>
            </div>
            <span class="badge badge-pending">Under Review</span>
          </div>

          <div class="proposal-item">
            <div class="proposal-status-dot" style="background:#1A4A8A;"></div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.875rem;">Fraud Detection — Microfinance</div>
              <div class="text-xs text-muted">Submitted Apr 8 · 4 milestones · $6,800</div>
              <div class="text-xs" style="color:var(--sage);margin-top:2px;">NDA sent — awaiting signature</div>
            </div>
            <span class="badge badge-info">Interview Scheduled</span>
          </div>

          <div class="proposal-item">
            <div class="proposal-status-dot" style="background:var(--border-dark);"></div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.875rem;">Supply Chain Optimization Model</div>
              <div class="text-xs text-muted">Submitted Apr 5 · 6 milestones · $14,000</div>
            </div>
            <span class="badge badge-default">Pending</span>
          </div>

          <div class="proposal-item">
            <div class="proposal-status-dot" style="background:var(--rust);"></div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.875rem;">Energy Demand Forecasting</div>
              <div class="text-xs text-muted">Submitted Apr 1 · 3 milestones · $4,400</div>
              <div class="text-xs" style="color:var(--rust);margin-top:2px;">Bid rejected · Feedback available</div>
            </div>
            <span class="badge badge-danger">Rejected</span>
          </div>

        </div>
      </div>

    </div>

  </main>
</div>

<script>
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});
</script>

</body>
</html>
