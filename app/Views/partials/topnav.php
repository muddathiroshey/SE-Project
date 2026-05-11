<?php
// Reusable top navigation partial
// Expects: optional $unread_count, session user info
?>
<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="/">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="/dashboard">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;">🔔
        <span class="notif-count" style="position:absolute;top:2px;right:2px;"><?= htmlspecialchars($unread_count ?? 0) ?></span>
      </a>

      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge">
            <div class="avatar avatar-sm"><?php echo strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? ''), 0, 2)) ?: 'ME'; ?></div>
          </div>
          <span style="font-size:.875rem;font-weight:700;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Me'); ?></span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">
            <?php echo htmlspecialchars(($_SESSION['role'] ?? 'Account') . ' Account'); ?></div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/profile/edit">My Profile</a>
          <a class="dropdown-item" href="/wallet">Wallet &amp; Escrow</a>
          <a class="dropdown-item" href="/profile/setup">Account Settings</a>
          <hr class="dropdown-divider">
          <form method="POST" action="/logout" style="margin:0;">
            <button type="submit" class="dropdown-item" style="color:var(--rust);background:none;border:none;padding:8px 12px;text-align:left;width:100%;">Sign Out</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>
