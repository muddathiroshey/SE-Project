<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Client Dashboard — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/dashboard-client-empty.css">
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
        <span class="notif-count" style="margin-left:auto;display:none;">0</span>
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
        <span class="notif-count" style="margin-left:auto;display:none;">0</span>
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
        <span class="notif-count" style="margin-left:auto;background:transparent;border-color:var(--rust);color:var(--rust);display:none;">0</span>
      </a>
      <a class="sidebar-link" href="messages.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 1h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-3l-4 3v-3H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>
        Messages
      </a>
    </div>
  </aside>

  <!-- CONTENT AREA -->
  <div class="content-area">
    <!-- VERIFICATION SUCCESS BANNER -->
    <div style="background:linear-gradient(135deg, #2d7f4d 0%, #1e5c35 100%);color:var(--ivory);padding:20px;border-radius:var(--radius-md);margin-bottom:32px;display:flex;align-items:center;gap:16px;">
      <div style="font-size:1.5rem;flex-shrink:0;">✓</div>
      <div style="flex:1;">
        <div style="font-weight:700;margin-bottom:4px;">Account Verified</div>
        <div style="font-size:0.9rem;opacity:0.75;">Your account has been verified successfully. You can now post projects and receive bids from experts.</div>
      </div>
    </div>

    <!-- PAGE HEADER -->
    <div style="margin-bottom:40px;">
      <div style="font-size:0.85rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:8px;">Dashboard</div>
      <h1 style="font-size:2.2rem;font-weight:300;color:var(--ink);margin:0 0 8px 0;">Welcome, Amira.</h1>
      <p style="font-size:1rem;color:var(--ink-mid);margin:0;">You have <strong>0 active projects</strong> and <strong>0 pending bids</strong>.</p>
    </div>

    <!-- EMPTY STATE -->
    <div class="empty-state">
      <div class="empty-state-icon">📋</div>
      <div class="empty-state-title">Get Started with Your First Project</div>
      <div class="empty-state-description">
        Post your first project to connect with top experts. Browse our marketplace to find the perfect specialist for your needs.
      </div>
      <a href="post-job.html" class="empty-state-cta">Post Your First Project</a>
      
      <div style="margin-top:40px;padding:32px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);max-width:500px;">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:16px;">How it works</h3>
        <ol style="text-align:left;font-size:0.9rem;line-height:1.8;color:var(--ink-mid);padding-left:20px;">
          <li style="margin-bottom:12px;"><strong>Post a Project</strong> — Describe your requirements and budget</li>
          <li style="margin-bottom:12px;"><strong>Receive Bids</strong> — Qualified experts respond with proposals</li>
          <li style="margin-bottom:12px;"><strong>Review & Select</strong> — Compare bids and select your expert</li>
          <li><strong>Work & Pay</strong> — Collaborate securely with funds held in escrow</li>
        </ol>
      </div>
    </div>
  </div>
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
