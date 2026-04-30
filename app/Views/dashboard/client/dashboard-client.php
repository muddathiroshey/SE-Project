<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?></title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/dashboard-client.css">
</head>
<body>

<!-- TOPNAV -->
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
      <a class="sidebar-link active" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
        Dashboard
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Projects</div>
      <a class="sidebar-link" href="/post-job">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/></svg>
        Post New Project
      </a>
      <a class="sidebar-link" href="/projects">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12v12H2V2zm1 1v10h10V3H3z"/></svg>
        Active Projects
        <span class="notif-count" style="margin-left:auto;"><?= $active_projects_count ?></span>
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Bids</div>
      <a class="sidebar-link" href="/bids">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v7h10V4H3zm1 1h2v2H4V5zm4 0h2v2H8V5zm4 0h2v2h-2V5z"/></svg>
        My Bids
        <span class="notif-count" style="margin-left:auto;"><?= $pending_bids_count ?></span>
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Finance</div>
      <a class="sidebar-link" href="/escrow">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm0 2v6h12V6H2zm9 1h2v2h-2V7z"/></svg>
        Escrow &amp; Wallet
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

  <!-- CONTENT AREA -->
  <div class="content-area">

    <!-- PAGE HEADER -->
    <div style="margin-bottom:40px;">
      <div style="font-size:0.85rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:8px;">Dashboard</div>
      <h1 style="font-size:2.2rem;font-weight:300;color:var(--ink);margin:0 0 8px 0;">
        Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>.
      </h1>
      <p style="font-size:1rem;color:var(--ink-mid);margin:0;">
        You have <strong><?= $active_projects_count ?> active project<?= $active_projects_count != 1 ? 's' : '' ?></strong>
        and <strong><?= $pending_bids_count ?> pending bid<?= $pending_bids_count != 1 ? 's' : '' ?></strong> awaiting review.
      </p>
    </div>

    <!-- STATS GRID -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:40px;">
      <div style="padding:20px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);border-top:3px solid var(--gold);">
        <div style="font-size:1.8rem;font-weight:700;color:var(--ink);margin-bottom:4px;">
          $<?= number_format($escrow['active_escrow'], 0) ?>
        </div>
        <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;">In Escrow (Active)</div>
      </div>
      <div style="padding:20px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);border-top:3px solid var(--sage);">
        <div style="font-size:1.8rem;font-weight:700;color:var(--ink);margin-bottom:4px;"><?= $active_projects_count ?></div>
        <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;">Active Projects</div>
      </div>
      <div style="padding:20px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);border-top:3px solid var(--gold);">
        <div style="font-size:1.8rem;font-weight:700;color:var(--ink);margin-bottom:4px;"><?= $pending_bids_count ?></div>
        <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;">Pending Bids</div>
      </div>
      <div style="padding:20px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);border-top:3px solid var(--sage);">
        <div style="font-size:1.8rem;font-weight:700;color:var(--ink);margin-bottom:4px;">
          <?= $nearest_deadline !== null ? $nearest_deadline . ' days' : '—' ?>
        </div>
        <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;">Nearest Deadline</div>
      </div>
    </div>

    <!-- ESCROW OVERVIEW -->
    <div style="margin-bottom:40px;">
      <div class="card">
        <h3 class="mb-16">Escrow Overview</h3>
        <div style="margin-bottom:16px;">
          <div style="font-family:var(--font-display);font-size:1.6rem;font-weight:300;">
            $<?= number_format($escrow['total_escrow'], 0) ?>
          </div>
          <div class="text-xs text-muted">Total Locked in Escrow</div>
          <div style="margin-top:8px;display:flex;gap:16px;">
            <div>
              <div style="font-family:var(--font-mono);font-size:.875rem;">$<?= number_format($escrow['released_escrow'], 0) ?></div>
              <div class="text-xs text-muted">Released (YTD)</div>
            </div>
            <div>
              <div style="font-family:var(--font-mono);font-size:.875rem;">$<?= number_format($escrow['pending_escrow'], 0) ?></div>
              <div class="text-xs text-muted">Pending Release</div>
            </div>
            <div>
              <div style="font-family:var(--font-mono);font-size:.875rem;">$<?= number_format($escrow['active_escrow'], 0) ?></div>
              <div class="text-xs text-muted">In Active Projects</div>
            </div>
          </div>
        </div>

        <?php
          $released_pct = $escrow['total_escrow'] > 0
            ? round((($escrow['released_escrow'] + $escrow['pending_escrow']) / $escrow['total_escrow']) * 100)
            : 0;
          $active_pct = 100 - $released_pct;
        ?>
        <div class="progress-bar mb-4"><div class="progress-fill" style="width:<?= $released_pct ?>%;"></div></div>
        <div class="flex justify-between text-xs text-muted">
          <span>$<?= number_format($escrow['released_escrow'] + $escrow['pending_escrow'], 0) ?> released/pending (<?= $released_pct ?>%)</span>
          <span>$<?= number_format($escrow['active_escrow'], 0) ?> active (<?= $active_pct ?>%)</span>
        </div>

        <?php if ($next_payment): ?>
        <div style="margin-top:20px;padding:16px;background:var(--gold-pale);border-radius:var(--radius-md);margin-bottom:16px;">
          <div style="font-size:0.75rem;color:var(--ink-muted);text-transform:uppercase;letter-spacing:0.04em;font-weight:700;margin-bottom:8px;">Next Upcoming Payment</div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
              <div style="font-size:1.25rem;font-weight:700;color:var(--ink);margin-bottom:4px;">
                $<?= number_format($next_payment['amount'], 0) ?>
              </div>
              <div class="text-xs text-muted"><?= htmlspecialchars($next_payment['title']) ?></div>
            </div>
            <div style="text-align:right;">
              <div style="font-size:0.875rem;font-weight:700;color:var(--gold);margin-bottom:2px;">
                Due in <?= $next_payment['days_until'] ?> days
              </div>
              <div class="text-xs text-muted"><?= date('M j, Y', strtotime($next_payment['due_date'])) ?></div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <hr class="divider">
        <a href="/escrow" class="btn btn-outline btn-sm w-full" style="justify-content:center;">Manage Wallet &amp; Escrow →</a>
      </div>
    </div>

    <div class="two-col-grid">
      <!-- ACTIVE PROJECTS -->
      <div>
        <div class="section-header">
          <h2 class="section-title">Active Projects</h2>
          <a href="/projects" class="section-link">View All →</a>
        </div>

        <?php if (empty($active_projects)): ?>
          <div class="card" style="padding:24px;text-align:center;color:var(--ink-muted);">
            No active projects yet. <a href="/post-job" style="color:var(--gold);">Post one →</a>
          </div>
        <?php else: ?>
          <?php foreach ($active_projects as $project):
            $is_dispute = $project['status'] === 'in_dispute';
            $bar_class  = $is_dispute ? 'dispute' : ($project['niche'] === 'legal' ? 'legal' : '');
            $price_color = $is_dispute ? '#d32f2f' : 'var(--gold)';
            $days_left   = $project['nearest_deadline']
              ? 'Due in ' . $project['nearest_deadline'] . 'd'
              : '—';
          ?>
          <div class="card" style="padding:20px;margin-bottom:20px;">
            <div class="project-row" style="padding:0;border-bottom:none;margin-bottom:12px;">
              <div class="project-niche-bar <?= $bar_class ?>"></div>
              <div style="flex:1;">
                <div style="font-weight:700;font-size:.95rem;"><?= htmlspecialchars($project['title']) ?></div>
                <div style="font-size:0.8rem;color:var(--ink-muted);margin-top:4px;">
                  <?= strtoupper(htmlspecialchars($project['niche'])) ?>
                  · Phase <?= $project['current_phase'] ?> of <?= $project['total_phases'] ?>
                </div>
              </div>
              <div style="text-align:right;">
                <div style="font-size:1rem;font-weight:700;color:<?= $price_color ?>;">
                  $<?= number_format($project['budget'], 0) ?>
                </div>
                <?php if ($is_dispute): ?>
                  <span style="font-size:0.7rem;color:#d32f2f;font-weight:700;">View Dispute →</span>
                <?php else: ?>
                  <div style="font-size:0.75rem;color:var(--ink-muted);"><?= $days_left ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- INCOMING BIDS -->
      <div>
        <div class="section-header">
          <h2 class="section-title">Incoming Bids
            <span class="section-badge"><?= $pending_bids_count ?></span>
          </h2>
          <a href="/bids" class="section-link">View All →</a>
        </div>

        <?php if (empty($incoming_bids)): ?>
          <div class="card" style="padding:24px;text-align:center;color:var(--ink-muted);">
            No pending bids yet.
          </div>
        <?php else: ?>
          <?php foreach ($incoming_bids as $bid): ?>
          <div class="bid-card">
            <div class="bid-card-header">
              <div class="bid-avatar">
                <?= strtoupper(substr($bid['user_name'], 0, 2)) ?>
              </div>
              <div class="bid-card-info">
                <div class="bid-card-name"><?= htmlspecialchars($bid['user_name']) ?></div>
                <div class="bid-card-status">
                  <?= $bid['verified_status'] === 'approved' ? '✓ VERIFIED' : '⏳ PENDING VERIFICATION' ?>
                </div>
              </div>
              <div class="bid-card-price">$<?= number_format($bid['amount'], 0) ?></div>
            </div>
            <?php if ($bid['proposal']): ?>
            <div class="bid-card-quote">
              "<?= htmlspecialchars(substr($bid['proposal'], 0, 120)) ?>…"
            </div>
            <?php endif; ?>
            <div class="bid-card-actions">
              <a href="/bids/<?= $bid['id'] ?>" class="btn btn-primary btn-sm">Review Bid</a>
              <button class="btn btn-outline btn-sm">Schedule Interview</button>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
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
