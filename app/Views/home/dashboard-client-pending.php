<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Client Dashboard — Nexus</title>
<link rel="stylesheet" href="assets/style.css">
<style>
body {
  display: flex;
  flex-direction: column;
  height: 100vh;
  margin: 0;
  padding: 0;
}

.main-layout {
  display: flex;
  flex: 1;
  overflow: hidden;
  width: 100%;
}

.sidebar {
  width: 280px;
  background: var(--ivory-card);
  border-right: 1px solid var(--border);
  overflow-y: auto;
  padding: 32px 24px;
}

.sidebar-section {
  margin-bottom: 32px;
}

.sidebar-label {
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--ink-faint);
  font-weight: 700;
  margin-bottom: 12px;
}

.sidebar-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 0;
  font-size: 0.9rem;
  color: var(--ink-mid);
  text-decoration: none;
  transition: all .15s;
  position: relative;
}

.sidebar-link:hover {
  color: var(--ink);
}

.sidebar-link.active {
  color: var(--ink-mid);
  font-weight: 400;
  border-left: 3px solid var(--gold);
  padding-left: 9px;
}

.sidebar-link.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}

.sidebar-link svg {
  width: 16px;
  height: 16px;
  flex-shrink: 0;
}

.notif-count {
  background: transparent;
  color: var(--gold);
  border: 2px solid var(--gold);
  font-size: 0.75rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 12px;
  min-width: 24px;
  text-align: center;
}

.content-area {
  flex: 1;
  overflow-y: auto;
  padding: 40px;
}

.verification-banner {
  background: linear-gradient(135deg, var(--rust) 0%, #c1441e 100%);
  color: var(--ivory);
  padding: 20px;
  border-radius: var(--radius-md);
  margin-bottom: 32px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.verification-banner-icon {
  font-size: 1.5rem;
  flex-shrink: 0;
}

.verification-banner-content {
  flex: 1;
}

.verification-banner-title {
  font-weight: 700;
  margin-bottom: 4px;
}

.verification-banner-text {
  font-size: 0.9rem;
  opacity: 0.9;
}

.stat-card-disabled {
  opacity: 0.6;
}

.section-badge {
  background: transparent;
  color: var(--gold);
  border: 2px solid var(--gold);
  font-size: 0.95rem;
  font-weight: 700;
  padding: 6px 12px;
  border-radius: 12px;
  margin-left: 8px;
}

.btn-primary {
  background: transparent;
  color: var(--gold);
  border: 2px solid var(--gold);
  font-weight: 700;
  cursor: pointer;
  transition: all .15s;
}

.btn-primary:hover:not(:disabled) {
  background: var(--gold);
  color: var(--ink);
}

.btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-outline {
  background: transparent;
  border: 1px solid var(--border);
  color: var(--ink-mid);
  padding: 8px 16px;
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: all .15s;
}

.btn-outline:hover:not(:disabled) {
  border-color: var(--gold);
  color: var(--ink);
}

.btn-outline:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 0.875rem;
}

.verification-steps {
  background: var(--ivory-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  padding: 24px;
  margin-top: 32px;
}

.verification-steps h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 16px;
}

.verification-step {
  display: flex;
  gap: 16px;
  margin-bottom: 16px;
}

.verification-step:last-child {
  margin-bottom: 0;
}

.step-number {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  background: var(--gold-pale);
  border-radius: 50%;
  color: var(--ink);
  font-weight: 700;
  flex-shrink: 0;
}

.step-content {
  flex: 1;
}

.step-title {
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 4px;
}

.step-description {
  font-size: 0.9rem;
  color: var(--ink-mid);
}

.step-status {
  font-size: 0.75rem;
  color: var(--gold);
  font-weight: 700;
  margin-top: 4px;
}
</style>
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
      <a class="sidebar-link disabled" href="#">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/></svg>
        Post New Project
        <span class="notif-count" style="margin-left:auto;background:var(--rust);color:var(--ivory);border:none;padding:2px 6px;">Locked</span>
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
      <a class="sidebar-link disabled" href="#">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm0 2v6h12V6H2zm9 1h2v2h-2V7z"/></svg>
        Escrow &amp; Wallet
        <span class="notif-count" style="margin-left:auto;background:var(--rust);color:var(--ivory);border:none;padding:2px 6px;">Locked</span>
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
    <!-- VERIFICATION BANNER -->
    <div class="verification-banner">
      <div class="verification-banner-icon">⏳</div>
      <div class="verification-banner-content">
        <div class="verification-banner-title">Account Verification Pending</div>
        <div class="verification-banner-text">
          Your account is being reviewed. You can browse experts and view your profile, but won't be able to post projects or receive bids until verification is complete.
        </div>
      </div>
    </div>

    <!-- PAGE HEADER -->
    <div style="margin-bottom:40px;">
      <div style="font-size:0.85rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:8px;">Dashboard</div>
      <h1 style="font-size:2.2rem;font-weight:300;color:var(--ink);margin:0 0 8px 0;">Welcome, Amira.</h1>
      <p style="font-size:1rem;color:var(--ink-mid);margin:0;">Your account is being verified. This typically takes 24-48 hours.</p>
    </div>

    <!-- VERIFICATION STEPS -->
    <div class="verification-steps">
      <h3>Your Verification Status</h3>
      
      <div class="verification-step">
        <div class="step-number">✓</div>
        <div class="step-content">
          <div class="step-title">Step 1: Account Created</div>
          <div class="step-description">Your account was successfully created and registered.</div>
          <div class="step-status">COMPLETED</div>
        </div>
      </div>

      <div class="verification-step">
        <div class="step-number">2</div>
        <div class="step-content">
          <div class="step-title">Step 2: Identity Verification</div>
          <div class="step-description">Our team is reviewing your identity documents. We'll verify your personal information and background.</div>
          <div class="step-status">IN PROGRESS</div>
        </div>
      </div>

      <div class="verification-step">
        <div class="step-number">3</div>
        <div class="step-content">
          <div class="step-title">Step 3: Approval Complete</div>
          <div class="step-description">Once verified, you'll have full access to post projects, receive bids, and manage escrow payments.</div>
          <div class="step-status">PENDING</div>
        </div>
      </div>
    </div>

    <!-- WHAT YOU CAN DO -->
    <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;margin-top:32px;">
      <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:16px;">While You Wait</h3>
      <ul style="padding-left:20px;font-size:0.9rem;color:var(--ink-mid);line-height:1.8;">
        <li style="margin-bottom:12px;">
          <strong>Browse Experts</strong> — Start exploring available experts and reading their portfolios
        </li>
        <li style="margin-bottom:12px;">
          <strong>Edit Profile</strong> — Add more details to your profile to help experts understand your needs better
        </li>
        <li>
          <strong>Search Projects</strong> — Look at similar projects to understand how the platform works
        </li>
      </ul>
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
