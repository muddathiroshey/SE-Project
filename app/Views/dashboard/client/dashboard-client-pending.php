<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?></title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/dashboard-client-pending.css">
</head>
<body>

<nav class="topnav">
  <div class="container">
    <div class="topnav-actions" style="margin-left: auto;">
      <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">
        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg><span style="position:absolute;top:0;right:0;width:8px;height:8px;background:var(--rust);border-radius:50%;"></span>
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
          <a class="dropdown-item" href="/profile">My Profile</a>
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
  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-label">Overview</div>
      <a class="sidebar-link active" href="/dashboard">
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
      <a class="sidebar-link" href="/projects">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12v12H2V2zm1 1v10h10V3H3z"/></svg>
        Active Projects
      </a>
      <a class="sidebar-link" href="#">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M4 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 2v1h6V3H5zm0 3v1h6V6H5zm0 3v1h4V9H5z"/></svg>
        Completed
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Marketplace</div>
      <a class="sidebar-link" href="/browse-experts">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a4 4 0 1 1 0 8A4 4 0 0 1 8 1zm0 9c-3.3 0-6 1.6-6 3v1h12v-1c0-1.4-2.7-3-6-3z"/></svg>
        Browse Experts
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
      <a class="sidebar-link" href="/messages">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 1h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-3l-4 3v-3H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>
        Messages
      </a>
    </div>
  </aside>

  <div class="content-area">
    <div class="verification-banner">
      <div class="verification-banner-icon">⏳</div>
      <div class="verification-banner-content">
        <div class="verification-banner-title">Account Verification Pending</div>
        <div class="verification-banner-text">
          Your account is being reviewed. You can browse experts and view your profile, but won't be able to post projects or receive bids until verification is complete.
        </div>
      </div>
    </div>

    <?php if (($doc_status ?? null) === 'rejected'): ?>
    <div style="background:#fff0f0;border:1px solid #f5c6c6;border-radius:var(--radius-md);padding:16px;margin-bottom:24px;color:#c0392b;">
      <strong>Verification Failed</strong> — Your document was rejected. Please re-upload a clearer copy of your ID.
      <a href="/profile/setup" style="color:#c0392b;font-weight:700;margin-left:8px;">Re-upload →</a>
    </div>
    <?php endif; ?>

    <div style="margin-bottom:40px;">
      <div style="font-size:0.85rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:8px;">Dashboard</div>
      <h1 style="font-size:2.2rem;font-weight:300;color:var(--ink);margin:0 0 8px 0;">
        Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>.
      </h1>
      <p style="font-size:1rem;color:var(--ink-mid);margin:0;">Your account is being verified. This typically takes 24-48 hours.</p>
    </div>

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
          <div class="step-status"><?= ($doc_status ?? null) === 'rejected' ? 'REJECTED' : 'IN PROGRESS' ?></div>
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

    <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;margin-top:32px;">
      <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:16px;">While You Wait</h3>
      <ul style="padding-left:20px;font-size:0.9rem;color:var(--ink-mid);line-height:1.8;">
        <li style="margin-bottom:12px;"><strong>Browse Experts</strong> — Start exploring available experts and reading their portfolios</li>
        <li style="margin-bottom:12px;"><strong>Edit Profile</strong> — Add more details to your profile to help experts understand your needs better</li>
        <li><strong>Search Projects</strong> — Look at similar projects to understand how the platform works</li>
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
