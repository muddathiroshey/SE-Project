<?php
/**
 * Views/dashboard/specialist/dashboard-specialist.php
 * Injected by DashboardController::buildSpecialistDashboard()
 *
 * $specialist            — userData row
 * $specialist_profile    — specialistProfiles row
 * $wallet_summary        — [cleared, pending, ytd, lifetime, this_month, last_month]
 * $wallet                — wallets row
 * $bid_stats             — [total, active, accepted, rejected, withdrawn, acceptance_rate]
 * $active_proposals      — bids[] with job_title, client_name, milestones_count
 * $nearest_milestone     — project_milestones row (or null)
 * $matched_jobs          — project_postings[] with client info + bid_count
 * $matched_jobs_count    — int
 * $active_projects_count — int
 * $unread_messages       — int
 */

$user_name   = htmlspecialchars($specialist['user_name'] ?? 'there');
$initials    = strtoupper(substr($specialist['user_name'] ?? 'SP', 0, 2));
$niche       = htmlspecialchars($specialist_profile['primary_niche'] ?? '');
$rating      = number_format((float)($specialist_profile['rating_avg'] ?? 0), 2);
$unread_notif = (int) ($_SESSION['notif_unread'] ?? 0);

// Earnings bar proportions
$cleared  = (float)($wallet_summary['cleared']  ?? 0);
$pending  = (float)($wallet_summary['pending']  ?? 0);
$ytd      = (float)($wallet_summary['ytd']      ?? 0);
$lifetime = (float)($wallet_summary['lifetime'] ?? 0);
$total_display = $cleared + $pending;
$clearPct  = $total_display > 0 ? round(($cleared / $total_display) * 100) : 60;
$pendPct   = $total_display > 0 ? round(($pending / $total_display) * 100) : 30;
$holdPct   = max(0, 100 - $clearPct - $pendPct);

// Bid stats
$totalBids    = (int)($bid_stats['total']           ?? 0);
$activeBids   = (int)($bid_stats['active']          ?? 0);
$acceptedBids = (int)($bid_stats['accepted']        ?? 0);
$accRate      = (int)($bid_stats['acceptance_rate'] ?? 0);

// Nearest milestone
$nm          = $nearest_milestone;
$nmDaysLeft  = $nm ? (int)$nm['days_left'] : null;
$nmUrgency   = match(true) {
    $nmDaysLeft === null  => '',
    $nmDaysLeft <= 1      => 'badge-danger',
    $nmDaysLeft <= 3      => 'badge-danger',
    $nmDaysLeft <= 7      => 'badge-gold',
    default               => 'badge-info',
};
$nmDaysLabel = match(true) {
    $nmDaysLeft === null  => '—',
    $nmDaysLeft === 0     => 'Due today',
    $nmDaysLeft === 1     => 'Due tomorrow',
    default               => "Due in {$nmDaysLeft}d",
};

// Proposal status badge map
$bidBadge = [
    'submitted'   => ['cls' => 'badge-pending', 'label' => 'Under Review'],
    'shortlisted' => ['cls' => 'badge-info',    'label' => 'Shortlisted'],
    'accepted'    => ['cls' => 'badge-verified', 'label' => 'Accepted'],
    'rejected'    => ['cls' => 'badge-danger',   'label' => 'Rejected'],
    'withdrawn'   => ['cls' => 'badge-default',  'label' => 'Withdrawn'],
];
$getBidBadge = fn($s) => $bidBadge[$s] ?? ['cls' => 'badge-default', 'label' => ucfirst($s)];

// Proposal dot colour
$bidDot = [
    'submitted'   => 'var(--gold)',
    'shortlisted' => '#1A4A8A',
    'accepted'    => 'var(--sage)',
    'rejected'    => 'var(--rust)',
    'withdrawn'   => 'var(--border-dark)',
];
$getBidDot = fn($s) => $bidDot[$s] ?? 'var(--border-dark)';

// Proposal sub-text
$bidSubtext = [
    'submitted'   => ['color' => 'var(--gold)',   'text' => 'Client reviewing'],
    'shortlisted' => ['color' => 'var(--sage)',   'text' => 'Shortlisted — NDA may be sent'],
    'accepted'    => ['color' => 'var(--sage)',   'text' => 'Bid accepted'],
    'rejected'    => ['color' => 'var(--rust)',   'text' => 'Bid rejected · Feedback may be available'],
    'withdrawn'   => ['color' => 'var(--ink-muted)', 'text' => 'You withdrew this proposal'],
];
$getBidSubtext = fn($s) => $bidSubtext[$s] ?? ['color' => 'var(--ink-muted)', 'text' => ''];

// Niche → icon
$nicheIcons = [
    'Data Science'           => '🧠',
    'Legal'                  => '⚖️',
    'Technical Translation'  => '🌐',
    'Financial Modeling'     => '📈',
    'Biomedical Research'    => '🔬',
    'Cybersecurity Audit'    => '🔐',
    'Software Development'   => '💻',
    'Engineering'            => '⚙️',
];
$getNicheIcon = fn($n) => $nicheIcons[$n] ?? '📋';

// Match-score simulation (based on niche match — refine later with real scorer)
$matchScore = fn($j) => $j['niche'] === ($specialist_profile['primary_niche'] ?? '') ? rand(80, 98) : rand(60, 79);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Specialist Dashboard — Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/dashboard-specialist.css">
</head>
<body>

<!-- ══ TOPNAV ══ -->
<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="/dashboard">Nexus<span>.</span></a>
    <div class="topnav-actions">

      <!-- Notification bell -->
      <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;">
        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg>
        <?php if ($unread_notif > 0): ?>
        <span class="notif-dot" style="position:absolute;top:2px;right:2px;"></span>
        <?php endif; ?>
      </a>

      <!-- Messages badge -->
      <?php if ($unread_messages > 0): ?>
      <a href="/chat" class="btn btn-ghost btn-icon" style="position:relative;">
        <svg viewBox="0 0 16 16" fill="currentColor" width="20" height="20"><path d="M2 1h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-3l-4 3v-3H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>
        <span class="notif-count" style="position:absolute;top:2px;right:2px;"><?= $unread_messages ?></span>
      </a>
      <?php endif; ?>

      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge">
            <?php if (!empty($specialist_profile['avatar_path'])): ?>
            <img src="<?= htmlspecialchars($specialist_profile['avatar_path']) ?>" class="avatar avatar-sm" alt="">
            <?php else: ?>
            <div class="avatar avatar-sm"><?= $initials ?></div>
            <?php endif; ?>
          </div>
          <span style="font-size:.875rem;font-weight:700;"><?= $user_name ?></span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">
            <?= $niche ?: 'Freelancer' ?>
          </div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/profile">My Profile</a>
          <a class="dropdown-item" href="/wallet">Earnings &amp; Wallet</a>
          <a class="dropdown-item" href="/profile/edit">Account Settings</a>
          <hr class="dropdown-divider">
          <form method="POST" action="/logout" style="margin:0;">
            <button class="dropdown-item" style="color:var(--rust);background:none;border:none;width:100%;text-align:left;cursor:pointer;">Sign Out</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="app-shell">

  <!-- ══ SIDEBAR ══ -->
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
        <?php if ($active_projects_count > 0): ?>
        <span class="notif-count" style="margin-left:auto;"><?= $active_projects_count ?></span>
        <?php endif; ?>
      </a>
      <a class="sidebar-link" href="/dashboard/my-bids">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M3 2h10a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v1h8V4H4zm0 2v1h8V6H4z"/></svg>
        My Proposals
        <?php if ($activeBids > 0): ?>
        <span class="notif-count" style="margin-left:auto;"><?= $activeBids ?></span>
        <?php endif; ?>
      </a>
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M6 1h4a1 1 0 0 1 1 1v2H5V2a1 1 0 0 1 1-1z"/><path d="M3 4h10v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z"/></svg>
        Completed Work
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Marketplace</div>
      <a class="sidebar-link" href="/jobs-view">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M11 11l3 3-1 1-3-3v-1.4A5.5 5.5 0 1 1 11 11zM6.5 11A4.5 4.5 0 1 0 6.5 2a4.5 4.5 0 0 0 0 9z"/></svg>
        Browse Jobs
        <?php if ($matched_jobs_count > 0): ?>
        <span class="notif-count" style="margin-left:auto;"><?= $matched_jobs_count ?></span>
        <?php endif; ?>
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
      <a class="sidebar-link" href="/wallet">
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
        <?php if ($unread_messages > 0): ?>
        <span class="notif-count" style="margin-left:auto;"><?= $unread_messages ?></span>
        <?php endif; ?>
      </a>
    </div>
  </aside>

  <!-- ══ MAIN ══ -->
  <main class="main-content">

    <!-- PAGE HEADER -->
    <div class="page-header flex justify-between items-center">
      <div>
        <div class="breadcrumb">Specialist Dashboard</div>
        <h2>Welcome back, <?= $user_name ?> .</h2>
        <p class="mt-4">
          You have <strong><?= $active_projects_count ?> active project<?= $active_projects_count != 1 ? 's' : '' ?></strong>
          <?php if ($matched_jobs_count > 0): ?>
          and <strong><?= $matched_jobs_count ?> new job match<?= $matched_jobs_count != 1 ? 'es' : '' ?></strong> in your niche.
          <?php endif; ?>
        </p>
      </div>
    </div>

    <!-- ── STATS ── -->
    <div class="grid-5 mb-32">
      <div class="stat-card">
        <div class="stat-value">$<?= number_format($ytd, 0) ?></div>
        <div class="stat-label">Total Earned (YTD)</div>
      </div>
      <div class="stat-card">
        <div class="stat-value"><?= $active_projects_count ?></div>
        <div class="stat-label">Active Projects</div>
      </div>
      <div class="stat-card">
        <div class="stat-value"><?= $rating ?></div>
        <div class="stat-label">Reputation Score</div>
      </div>
      <div class="stat-card">
        <div class="stat-value"><?= $accRate ?>%</div>
        <div class="stat-label">Acceptance Rate</div>
      </div>
      <div class="stat-card">
        <?php if ($nm): ?>
        <div class="stat-value"><?= $nmDaysLeft ?>d</div>
        <div class="stat-label">Nearest Deadline</div>
        <?php else: ?>
        <div class="stat-value">—</div>
        <div class="stat-label">No Upcoming Deadline</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="grid-2 mb-32">

      <!-- ── EARNINGS BREAKDOWN ── -->
      <div class="card">
        <h3 class="mb-4">Earnings Overview</h3>
        <div class="earnings-bar mb-16">
          <div class="earnings-segment" style="flex:<?= $clearPct ?>;background:var(--sage);">
            <?php if ($clearPct > 15): ?>Cleared<?php endif; ?>
          </div>
          <?php if ($pendPct > 0): ?>
          <div class="earnings-segment" style="flex:<?= $pendPct ?>;background:var(--gold);">
            <?php if ($pendPct > 10): ?>Pending<?php endif; ?>
          </div>
          <?php endif; ?>
          <?php if ($holdPct > 0): ?>
          <div class="earnings-segment" style="flex:<?= $holdPct ?>;background:var(--border);color:var(--ink-muted);">
            <?php if ($holdPct > 10): ?>Hold<?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
        <div class="flex justify-between">
          <div>
            <div style="font-family:var(--font-mono);font-weight:500;">$<?= number_format($cleared, 0) ?></div>
            <div class="text-xs text-muted">Cleared</div>
          </div>
          <div>
            <div style="font-family:var(--font-mono);font-weight:500;">$<?= number_format($pending, 0) ?></div>
            <div class="text-xs text-muted">Pending</div>
          </div>
          <div>
            <div style="font-family:var(--font-mono);font-weight:500;">$<?= number_format((float)($wallet['balance'] ?? 0), 0) ?></div>
            <div class="text-xs text-muted">Wallet Balance</div>
          </div>
        </div>
        <hr class="divider">
        <a href="/wallet" class="btn btn-outline btn-sm w-full" style="justify-content:center;">View Full Earnings →</a>
      </div>

      <!-- ── NEAREST MILESTONE DEADLINE ── -->
      <div class="card">
        <h3 class="mb-4">Nearest Milestone Deadline</h3>
        <?php if ($nm): ?>
        <p class="mb-16 text-sm text-muted">Active project with the closest upcoming milestone.</p>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
          <div style="flex:1;min-width:240px;">
            <div style="font-weight:700;font-size:.9375rem;"><?= htmlspecialchars($nm['project_title']) ?></div>
            <div class="text-xs text-muted" style="margin-top:6px;">
              NX-<?= date('Y') ?>-<?= (int)$nm['project_id'] ?>
              <?php if (!empty($nm['niche'])): ?> · <?= htmlspecialchars($nm['niche']) ?><?php endif; ?>
              <?php if (!empty($nm['project_budget'])): ?> · Budget $<?= number_format((float)$nm['project_budget'], 0) ?><?php endif; ?>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;">
              <span class="badge badge-gold">
                Milestone <?= (int)$nm['sort_order'] + 1 ?>
                <?php if (!empty($nm['total_milestones'])): ?> of <?= (int)$nm['total_milestones'] ?><?php endif; ?>
              </span>
              <span class="badge <?= $nmUrgency ?>"><?= $nmDaysLabel ?></span>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;min-width:120px;">
            <div style="font-size:2rem;font-weight:700;line-height:1;"><?= $nmDaysLeft ?>d</div>
            <a href="/project-detail?id=<?= (int)$nm['project_id'] ?>" class="btn btn-primary btn-sm">Go To Project</a>
          </div>
        </div>
        <?php else: ?>
        <p class="text-muted text-sm">No upcoming milestones. <a href="/browse-jobs">Browse jobs →</a></p>
        <?php endif; ?>
      </div>

    </div><!-- /grid-2 -->

    <!-- ── JOB MATCHES + PROPOSALS ── -->
    <div class="grid-2">

      <!-- MATCHED JOBS -->
      <div class="card card-flush">
        <div class="card-header flex justify-between items-center">
          <h3>
            Matched Jobs
            <?php if ($matched_jobs_count > 0): ?>
            <span class="notif-count" style="margin-left:8px;"><?= $matched_jobs_count ?></span>
            <?php endif; ?>
          </h3>
          <a href="/browse-jobs" class="btn btn-ghost btn-sm">Browse All</a>
        </div>
        <div class="card-body">

          <?php if (empty($matched_jobs)): ?>
          <div style="padding:24px;text-align:center;color:var(--ink-muted);">
            <div style="font-size:1.5rem;margin-bottom:8px;">🔍</div>
            No job matches right now.
            <a href="/browse-jobs" style="display:block;margin-top:8px;">Browse all jobs →</a>
          </div>

          <?php else: foreach ($matched_jobs as $j):
            $icon  = $getNicheIcon($j['niche']);
            $score = $matchScore($j);
            $ms    = count($j['milestones'] ?? []);
          ?>
          <div class="job-match">
            <div class="flex justify-between items-start mb-6">
              <div style="font-weight:700;font-size:.9375rem;">
                <?= $icon ?> <?= htmlspecialchars($j['project_title']) ?>
              </div>
              <span class="match-score"><?= $score ?>% match</span>
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px;">
              <span class="badge badge-gold"><?= htmlspecialchars($j['niche']) ?></span>
              <?php if ($j['nda_type'] !== 'standard'): ?>
              <span class="tag">🔏 NDA</span>
              <?php endif; ?>
              <?php if (!empty($j['client_verified'])): ?>
              <span class="badge badge-verified" style="font-size:.6rem;">✔ Verified Client</span>
              <?php endif; ?>
            </div>
            <div class="flex justify-between items-center">
              <span style="font-family:var(--font-mono);font-size:.875rem;">
                $<?= number_format((float)$j['total_budget'], 0) ?>
                <?php if ($ms > 0): ?> · <?= $ms ?> milestone<?= $ms != 1 ? 's' : '' ?><?php endif; ?>
                <?php if ($j['bid_count'] > 0): ?> · <?= (int)$j['bid_count'] ?> bid<?= $j['bid_count'] != 1 ? 's' : '' ?><?php endif; ?>
              </span>
              <a href="/job-view?id=<?= (int)$j['id'] ?>" class="btn btn-outline btn-sm">View Details</a>
            </div>
          </div>
          <?php endforeach; endif; ?>

        </div>
      </div>

      <!-- ACTIVE PROPOSALS -->
      <div class="card card-flush">
        <div class="card-header flex justify-between items-center">
          <h3>Active Proposals</h3>
          <a href="/dashboard/my-bids" class="btn btn-ghost btn-sm">View All</a>
        </div>
        <div class="card-body">

          <?php if (empty($active_proposals)): ?>
          <div style="padding:24px;text-align:center;color:var(--ink-muted);">
            <div style="font-size:1.5rem;margin-bottom:8px;">📋</div>
            No active proposals yet.
            <a href="/browse-jobs" style="display:block;margin-top:8px;">Find a job to bid on →</a>
          </div>

          <?php else: foreach ($active_proposals as $p):
            $badge   = $getBidBadge($p['status']);
            $dot     = $getBidDot($p['status']);
            $subtext = $getBidSubtext($p['status']);
          ?>
          <div class="proposal-item">
            <div class="proposal-status-dot" style="background:<?= $dot ?>;"></div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($p['job_title']) ?></div>
              <div class="text-xs text-muted">
                Submitted <?= date('M d', strtotime($p['submitted_at'])) ?>
                · <?= (int)$p['milestones_count'] ?> milestone<?= $p['milestones_count'] != 1 ? 's' : '' ?>
                · $<?= number_format((float)$p['total_bid_amount'], 0) ?>
                <?php if (!empty($p['client_name'])): ?> · <?= htmlspecialchars($p['client_name']) ?><?php endif; ?>
              </div>
              <?php if ($subtext['text']): ?>
              <div class="text-xs" style="color:<?= $subtext['color'] ?>;margin-top:2px;">
                <?= htmlspecialchars($subtext['text']) ?>
              </div>
              <?php endif; ?>
            </div>
            <span class="badge <?= $badge['cls'] ?>"><?= $badge['label'] ?></span>
          </div>
          <?php endforeach; endif; ?>

        </div>
      </div>

    </div><!-- /grid-2 -->

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