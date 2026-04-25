<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Client Dashboard — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/dashboard-client.css">
</head>
<body>

<!-- TOPNAV -->
<nav class="topnav">
  <div class="container">
    <div class="topnav-actions" style="margin-left: auto;">
      <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔 <span style="position:absolute;top:0;right:0;width:8px;height:8px;background:var(--rust);border-radius:50%;"></span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">AT</div></div>
          <span style="font-size:.875rem;font-weight:700;">Amira T.</span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Client Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="#">My Profile</a>
          <a class="dropdown-item" href="escrow-wallet.html">Wallet &amp; Escrow</a>
          <a class="dropdown-item" href="#">Account Settings</a>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="login.html" style="color:var(--rust);">Sign Out</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="main-layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-label">Overview</div>
      <a class="sidebar-link active" href="dashboard-client.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
        Dashboard
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Projects</div>
      <a class="sidebar-link" href="post-job.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/></svg>
        Post New Project
      </a>
      <a class="sidebar-link" href="project-detail.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12v12H2V2zm1 1v10h10V3H3z"/></svg>
        Active Projects
        <span class="notif-count" style="margin-left:auto;">3</span>
      </a>
      <a class="sidebar-link" href="#">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M4 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 2v1h6V3H5zm0 3v1h6V6H5zm0 3v1h4V9H5z"/></svg>
        Completed
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Bids</div>
      <a class="sidebar-link" href="#">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v7h10V4H3zm1 1h2v2H4V5zm4 0h2v2H8V5zm4 0h2v2h-2V5z"/></svg>
        My Bids
        <span class="notif-count" style="margin-left:auto;">12</span>
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Marketplace</div>
      <a class="sidebar-link" href="browse-experts.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a4 4 0 1 1 0 8A4 4 0 0 1 8 1zm0 9c-3.3 0-6 1.6-6 3v1h12v-1c0-1.4-2.7-3-6-3z"/></svg>
        Browse Experts
      </a>
      <a class="sidebar-link" href="#">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M1 2h14v2H1V2zm0 4h14v2H1V6zm0 4h14v2H1v-2z"/></svg>
        Saved Experts
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Finance</div>
      <a class="sidebar-link" href="escrow-wallet.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm0 2v6h12V6H2zm9 1h2v2h-2V7z"/></svg>
        Escrow &amp; Wallet
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Support</div>
      <a class="sidebar-link" href="dispute.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1zm0 3a.75.75 0 0 0 0 1.5.75.75 0 0 0 0-1.5zm-.25 3v4.5h1.5V7h-1.5z"/></svg>
        Disputes
        <span class="notif-count" style="margin-left:auto;background:transparent;border-color:var(--rust);color:var(--rust);">1</span>
      </a>
      <a class="sidebar-link" href="messages.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 1h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-3l-4 3v-3H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>
        Messages
      </a>
    </div>
  </aside>

  <!-- CONTENT AREA -->
  <div class="content-area">
    <!-- PAGE HEADER -->
    <div style="margin-bottom:40px;">
      <div style="font-size:0.85rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:8px;">Dashboard</div>
      <h1 style="font-size:2.2rem;font-weight:300;color:var(--ink);margin:0 0 8px 0;">Welcome, Amira.</h1>
      <p style="font-size:1rem;color:var(--ink-mid);margin:0;">You have <strong>3 active projects</strong> and <strong>12 pending bids</strong> awaiting review.</p>
    </div>

    <!-- STATS GRID -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:40px;">
      <!-- Stat Card 1 -->
      <div style="padding:20px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);border-top:3px solid var(--gold);">
        <div style="font-size:1.8rem;font-weight:700;color:var(--ink);margin-bottom:4px;">$6,960</div>
        <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:12px;">In Escrow (Active)</div>
      </div>

      <!-- Stat Card 2 -->
      <div style="padding:20px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);border-top:3px solid var(--sage);">
        <div style="font-size:1.8rem;font-weight:700;color:var(--ink);margin-bottom:4px;">3</div>
        <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:12px;">Active Projects</div>
      </div>

      <!-- Stat Card 3 -->
      <div style="padding:20px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);border-top:3px solid var(--gold);">
        <div style="font-size:1.8rem;font-weight:700;color:var(--ink);margin-bottom:4px;">12</div>
        <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:12px;">Pending Bids</div>
      </div>

      <!-- Stat Card 4 -->
      <div style="padding:20px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);border-top:3px solid var(--sage);">
        <div style="font-size:1.8rem;font-weight:700;color:var(--ink);margin-bottom:4px;">4 days</div>
        <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:12px;">Nearest Deadline</div>
      </div>
    </div>

    <!-- ESCROW OVERVIEW -->
    <div style="margin-bottom:40px;">
      <div class="card">
        <h3 class="mb-16">Escrow Overview</h3>
        <div style="margin-bottom:16px;">
          <div>
            <div style="font-family:var(--font-display);font-size:1.6rem;font-weight:300;">$24,500</div>
            <div class="text-xs text-muted">Total Locked in Escrow</div>
            <div style="margin-top:8px;display:flex;gap:16px;">
              <div><div style="font-family:var(--font-mono);font-size:.875rem;">$15,440</div><div class="text-xs text-muted">Released (YTD)</div></div>
              <div><div style="font-family:var(--font-mono);font-size:.875rem;">$2,100</div><div class="text-xs text-muted">Pending Release</div></div>
              <div><div style="font-family:var(--font-mono);font-size:.875rem;">$6,960</div><div class="text-xs text-muted">In Active Projects</div></div>
            </div>
          </div>
        </div>
        <div class="progress-bar mb-4"><div class="progress-fill" style="width:72%;"></div></div>
        <div class="flex justify-between text-xs text-muted">
          <span>$17,540 released/pending (72%)</span><span>$6,960 active (28%)</span>
        </div>
        <div style="margin-top:20px;padding:16px;background:var(--gold-pale);border-radius:var(--radius-md);margin-bottom:16px;">
          <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:8px;">Next Upcoming Payment</div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
              <div style="font-size:1.25rem;font-weight:700;color:var(--ink);margin-bottom:4px;">$2,100</div>
              <div class="text-xs text-muted">Predictive Churn Model Milestone 2</div>
            </div>
            <div style="text-align:right;">
              <div style="font-size:0.875rem;font-weight:700;color:var(--gold);margin-bottom:2px;">Due in 4 days</div>
              <div class="text-xs text-muted">Apr 29, 2026</div>
            </div>
          </div>
        </div>
        <hr class="divider">
        <a href="escrow-wallet.html" class="btn btn-outline btn-sm w-full" style="justify-content:center;">Manage Wallet &amp; Escrow →</a>
      </div>
    </div>

    <div class="two-col-grid">
      <!-- ACTIVE PROJECTS SECTION -->
      <div>
        <div class="section-header">
          <h2 class="section-title">Active Projects</h2>
          <a href="#" class="section-link">View All →</a>
        </div>

        <!-- PROJECT 1 -->
        <div class="card" style="padding:20px;margin-bottom:20px;">
          <div class="project-row" style="padding:0;border-bottom:none;margin-bottom:12px;">
            <div class="project-niche-bar"></div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.95rem;">Predictive Churn Model — FinCorp</div>
              <div style="font-size:0.8rem;color:var(--ink-muted);margin-top:4px;">DATA SCIENCE · Phase 2 of 5</div>
            </div>
            <div style="text-align:right;">
              <div style="font-size:1rem;font-weight:700;color:var(--gold);">$8,400</div>
              <div style="font-size:0.75rem;color:var(--ink-muted);">Due in 4d</div>
            </div>
          </div>
        </div>

        <!-- PROJECT 2 -->
        <div class="card" style="padding:20px;margin-bottom:20px;">
          <div class="project-row" style="padding:0;border-bottom:none;margin-bottom:12px;">
            <div class="project-niche-bar legal"></div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.95rem;">Contract Review — MENA Expansion</div>
              <div style="font-size:0.8rem;color:var(--ink-muted);margin-top:4px;">LEGAL · Phase 1 of 3</div>
            </div>
            <div style="text-align:right;">
              <div style="font-size:1rem;font-weight:700;color:var(--gold);">$12,000</div>
              <div style="font-size:0.75rem;color:var(--ink-muted);">Due in 12d</div>
            </div>
          </div>
        </div>

        <!-- PROJECT 3 -->
        <div class="card" style="padding:20px;">
          <div class="project-row" style="padding:0;border-bottom:none;margin-bottom:12px;">
            <div class="project-niche-bar dispute"></div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.95rem;">Annual Report — DE Translation</div>
              <div style="font-size:0.8rem;color:var(--ink-muted);margin-top:4px;">IN DISPUTE · Phase 3 of 4</div>
            </div>
            <div style="text-align:right;">
              <div style="font-size:1rem;font-weight:700;color:#d32f2f;">$4,100</div>
              <span style="font-size:0.7rem;color:#d32f2f;font-weight:700;">View Dispute →</span>
            </div>
          </div>
        </div>
      </div>

      <!-- INCOMING BIDS SECTION -->
      <div>
        <div class="section-header">
          <h2 class="section-title">Incoming Bids<span class="section-badge">12</span></h2>
          <a href="#" class="section-link">View All →</a>
        </div>

        <!-- BID 1: VERIFIED -->
        <div class="bid-card">
          <div class="bid-card-header">
            <div class="bid-avatar">DR</div>
            <div class="bid-card-info">
              <div class="bid-card-name">Dr. Rania Khalil</div>
              <div class="bid-card-status">✓ VERIFIED</div>
            </div>
            <div class="bid-card-price">$9,200</div>
          </div>
          <div class="bid-card-quote">"I propose a 5-milestone approach with weekly model checkpoints and live notebooks for full transparency…"</div>
          <div class="bid-card-actions">
            <button class="btn btn-primary btn-sm">Review Bid</button>
            <button class="btn btn-outline btn-sm">Schedule Interview</button>
          </div>
        </div>

        <!-- BID 2: VERIFIED (Pending Verification) -->
        <div class="bid-card">
          <div class="bid-card-header">
            <div class="bid-avatar">MF</div>
            <div class="bid-card-info">
              <div class="bid-card-name">Marcus Fernandez</div>
              <div class="bid-card-status">✓ VERIFIED</div>
            </div>
            <div class="bid-card-price">$7,800</div>
          </div>
          <div class="bid-card-quote">"6-phase delivery with bi-weekly check-ins. My portfolio includes 4 similar churn models for banking clients…"</div>
          <div class="bid-card-actions">
            <button class="btn btn-primary btn-sm">Review Bid</button>
            <button class="btn btn-outline btn-sm">Schedule Interview</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="post-success-toast" class="success-toast" role="status" aria-live="polite">
  Project posted successfully
</div>

<script>
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});
(function showPostSuccessToastFromQuery() {
  const params = new URLSearchParams(window.location.search);
  if (params.get('posted') !== '1') return;

  const toast = document.getElementById('post-success-toast');
  if (!toast) return;

  requestAnimationFrame(() => {
    toast.classList.add('show');
  });

  setTimeout(() => {
    toast.classList.remove('show');
  }, 2400);

  // Remove the query flag so the toast does not reappear on refresh.
  params.delete('posted');
  const cleanQuery = params.toString();
  const cleanUrl = cleanQuery
    ? `${window.location.pathname}?${cleanQuery}${window.location.hash}`
    : `${window.location.pathname}${window.location.hash}`;
  window.history.replaceState({}, '', cleanUrl);
})();
</script>
</body>
</html>
