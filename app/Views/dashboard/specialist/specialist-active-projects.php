<?php
if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('initials')) {
    function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name));
        $letters = '';

        foreach ($parts as $part) {
            if ($part !== '') {
                $letters .= strtoupper(substr($part, 0, 1));
            }

            if (strlen($letters) >= 2) {
                break;
            }
        }

        return $letters !== '' ? $letters : 'NA';
    }
}

$project_rows = isset($projects) && is_array($projects) ? $projects : [];
$active_projects_count = $active_projects_count
    ?? (is_numeric($active_projects ?? null) ? (int) $active_projects : count($project_rows));
$user_name_raw = $_SESSION['user_name'] ?? ($specialist['user_name'] ?? 'Specialist');
$user_name = e($user_name_raw);
$user_initials = initials($user_name_raw);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Specialist Active Projects - Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/specialist-active-projects.css">
</head>
<body>

<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="/dashboard">Nexus<span>.</span></a>
    <div class="topnav-actions">
      <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;">
        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm"><?= e($user_initials) ?></div></div>
          <span style="font-size:.875rem;font-weight:700;"><?= $user_name ?></span>
          <span style="color:var(--ink-faint);">v</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Specialist Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/profile">My Profile</a>
          <a class="dropdown-item" href="/escrow">Wallet &amp; Escrow</a>
          <a class="dropdown-item" href="/settings">Account Settings</a>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/login" style="color:var(--rust);">Sign Out</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="app-shell">
  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-label">Overview</div>
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
        Dashboard
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Work</div>
      <a class="sidebar-link active" href="/projects">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4a1 1 0 0 1 1-1h3l1 1h6a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4z"/></svg>
        Active Projects
        <span class="notif-count" style="margin-left:auto;"><?= $active_projects_count ?></span>
      </a>
      <a class="sidebar-link" href="/dashboard/my-bids">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M3 2h10a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v1h8V4H4zm0 2v1h8V6H4z"/></svg>
        My Proposals
      </a>
      <a class="sidebar-link" href="/projects/completed">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M6 1h4a1 1 0 0 1 1 1v2H5V2a1 1 0 0 1 1-1z"/><path d="M3 4h10v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z"/></svg>
        Completed Work
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Marketplace</div>
      <a class="sidebar-link" href="/jobs">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M11 11l3 3-1 1-3-3v-1.4A5.5 5.5 0 1 1 11 11zM6.5 11A4.5 4.5 0 1 0 6.5 2a4.5 4.5 0 0 0 0 9z"/></svg>
        Browse Jobs
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

  <main class="main-content">
    <div class="page-header flex justify-between items-center">
      <div>
        <div class="breadcrumb">Dashboard <span style="margin:0 6px;color:var(--ink-faint);">&gt;</span> Projects</div>
        <h2>Active Projects</h2>
        <p class="mt-4">
          <?= $active_projects_count ?> active contract<?= $active_projects_count === 1 ? '' : 's' ?>
        </p>
      </div>
    </div>

    <div class="stat-strip">
      <div class="strip-cell">
        <div class="strip-val"><?= $active_projects_count ?></div>
        <div class="strip-lbl">All Active</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:#1A4A8A;">0</div>
        <div class="strip-lbl">Review Requested</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:var(--gold);">0</div>
        <div class="strip-lbl">In Revision</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:var(--rust);">0</div>
        <div class="strip-lbl">In Dispute</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:var(--gold);">$0</div>
        <div class="strip-lbl">Pending Funds</div>
      </div>
    </div>

    <?php if (empty($project_rows)): ?>
      <div class="empty-state">
        <div class="empty-icon">[]</div>
        <h4 style="font-family:var(--font-display);font-size:1.3rem;font-weight:500;margin-bottom:8px;">No active projects yet</h4>
        <p class="text-sm text-muted">New contracts will appear here after a client accepts your proposal.</p>
      </div>
    <?php else: ?>
      <?php foreach ($project_rows as $index => $p):
        $project_id = (int) ($p['project_id'] ?? $p['id'] ?? 0);
        $niche = $p['primary_niche'] ?? $p['niche'] ?? 'Project';
        $client_name = $p['client_name'] ?? ($p['client']['name'] ?? 'Client');
        $client_verified = (bool) ($p['client_verified'] ?? ($p['client']['verified'] ?? false));
        $title = $p['title'] ?? ('Active Project #' . $project_id);
        $contract_ref = $p['contract_ref'] ?? ('CON-NX-' . str_pad((string) $project_id, 4, '0', STR_PAD_LEFT));
        $started_at = !empty($p['created_at']) ? date('M j, Y', strtotime($p['created_at'])) : 'Recently';
        $progress_pct = (int) ($p['progress_pct'] ?? 0);
        $progress_pct = max(0, min(100, $progress_pct));
      ?>
      <a href="/projects/<?= $project_id ?>" class="proj-card status-active" style="text-decoration:none;display:block;" id="pc-<?= $project_id ?: $index ?>">
        <div class="proj-card-body">
          <div class="proj-niche-icon ni-data"><?= e(strtoupper(substr($niche, 0, 1))) ?></div>
          <div style="min-width:0;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap;">
              <div style="font-family:var(--font-display);font-size:1.05rem;font-weight:600;color:var(--ink);"><?= e($title) ?></div>
              <span class="status-pill sp-active">Active</span>
            </div>
            <div style="display:flex;gap:14px;font-size:.8125rem;color:var(--ink-muted);flex-wrap:wrap;margin-bottom:10px;">
              <span class="flex items-center gap-6">
                <div class="avatar avatar-sm" style="width:20px;height:20px;font-size:.6rem;flex-shrink:0;"><?= e(initials($client_name)) ?></div>
                <?= e($client_name) ?>
                <?php if ($client_verified): ?>
                  <span class="badge badge-verified badge-dot" style="font-size:.575rem;">Verified</span>
                <?php endif; ?>
              </span>
              <span>&middot;</span>
              <span class="font-mono"><?= e($contract_ref) ?></span>
              <span>&middot;</span>
              <span><?= e($niche) ?></span>
              <span>&middot;</span>
              <span>Started <?= e($started_at) ?></span>
            </div>
            <div style="font-size:.75rem;color:var(--ink-muted);margin-top:5px;">
              Project is active. Add milestone fields to the projects schema to show phase progress here.
            </div>
          </div>
          <div class="proj-right">
            <div class="proj-value">$<?= number_format((float) ($p['total_value'] ?? 0), 0) ?></div>
            <div class="proj-value-sub">$<?= number_format((float) ($p['paid_to_date'] ?? 0), 0) ?> paid</div>
            <div style="margin-top:10px;">
              <div class="dl-chip soon">Active</div>
            </div>
          </div>
        </div>
        <div class="proj-progress-row">
          <div class="progress-bar" style="flex:1;height:6px;">
            <div class="progress-fill" style="width:<?= $progress_pct ?>%;"></div>
          </div>
          <span style="font-size:.75rem;font-family:var(--font-mono);color:var(--ink-muted);white-space:nowrap;"><?= $progress_pct ?>% complete</span>
          <span style="margin-left:auto;" class="flex gap-8">
            <span class="btn btn-ghost btn-sm" style="font-size:.75rem;pointer-events:none;">View Details</span>
          </span>
        </div>
      </a>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class="empty-state hidden" id="empty-state">
      <div class="empty-icon">[]</div>
      <h4 style="font-family:var(--font-display);font-size:1.3rem;font-weight:500;margin-bottom:8px;">No projects match this filter</h4>
      <p class="text-sm text-muted">Try clearing your filter.</p>
    </div>
  </main>
</div>

<div class="toast-stack" id="toast-stack"></div>

<script>
function setChip(el, key) {
  document.querySelectorAll('.fchip').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  const cards = document.querySelectorAll('.proj-card');
  let any = false;
  cards.forEach(card => {
    let show = true;
    if (key === 'review') show = card.classList.contains('status-pending-review');
    if (key === 'dispute') show = card.classList.contains('status-dispute');
    if (key === 'deadline') show = card.classList.contains('status-active') || card.classList.contains('status-overdue');
    card.style.display = show ? '' : 'none';
    if (show) any = true;
  });
  document.getElementById('empty-state').classList.toggle('hidden', any);
}

function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  s.innerHTML = `<div class="toast ${type === 'info' ? '' : 'success'}"><span class="toast-icon">${type === 'info' ? 'i' : 'OK'}</span><div><div class="toast-title">${type === 'info' ? 'Info' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML = '', 4000);
}

function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}

document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) {
    document.getElementById('user-dd')?.classList.add('hidden');
  }
});
</script>
</body>
</html>
