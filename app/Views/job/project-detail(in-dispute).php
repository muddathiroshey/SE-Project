<?php
/**
 * Views/job/project-detail(in-dispute).php  — CLIENT dispute view
 * Injected by ProjectController::ProjectDetailInDispute()
 *
 * Same contract as project-detail.php PLUS:
 * $active_dispute  — disputes row (or null)
 */

$initials     = strtoupper(substr($_SESSION['user_name'] ?? 'CL', 0, 2));
$userName     = htmlspecialchars($_SESSION['user_name'] ?? 'Client');
$unread_notif = (int) ($_SESSION['notif_unread'] ?? 0);

$project_id  = (int) $project['project_id'];
$ref         = htmlspecialchars($project['contract_ref'] ?? 'CON-NX-' . $project_id);
$title       = htmlspecialchars($project['title'] ?? '');
$niche       = htmlspecialchars($project['primary_niche'] ?? $project['niche'] ?? '');
$total       = (float) $project['total_amount'];
$fee_rate    = (float) ($funds['fee_rate'] ?? 0.065);

$sp_name     = htmlspecialchars($project['specialist_name'] ?? 'Specialist');
$sp_initials = strtoupper(substr($project['specialist_name'] ?? 'SP', 0, 2));
$sp_rating   = number_format((float)($project['specialist_rating'] ?? 0), 1);

$dispute_ref = $active_dispute
    ? 'DSP-NX-' . (int)$active_dispute['id'] . '-' . date('Y')
    : 'DSP-NX-' . $project_id . '-' . date('Y');

$am            = $active_milestone;
$amDaysLeft    = $am ? (int)($am['days_left'] ?? 0) : null;
$amDeadline    = $am && $am['due_date'] ? date('M j, Y', strtotime($am['due_date'])) : '—';
$current_phase = count($done_milestones) + 1;
$total_phases  = count($milestones);

$getMsBadge = function($s) {
    $map = [
        'paid'               => ['cls' => 'badge-verified badge-dot', 'label' => 'Completed'],
        'approved'           => ['cls' => 'badge-verified badge-dot', 'label' => 'Completed'],
        'in_progress'        => ['cls' => 'badge-pending badge-dot',  'label' => 'In Progress'],
        'submitted'          => ['cls' => 'badge-pending badge-dot',  'label' => 'Under Review'],
        'revision_requested' => ['cls' => 'badge-gold',               'label' => 'Revision Requested'],
        'pending'            => ['cls' => 'badge-default',            'label' => '🔒 Locked'],
    ];
    return $map[$s] ?? ['cls' => 'badge-default', 'label' => ucfirst($s)];
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?> — Dispute Active — Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/project-detail.css">
<style>
.badge-danger { background:#FBE9E7; border:1px solid #F0B4AA; color:#D84040; }
</style>
</head>
<body>

<!-- TOPNAV -->
<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="/dashboard">Nexus<span>.</span></a>
    <div class="topnav-actions">
      <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;">
        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg>
        <?php if ($unread_notif > 0): ?><span class="notif-dot" style="position:absolute;top:2px;right:2px;"></span><?php endif; ?>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar avatar-sm"><?= $initials ?></div>
          <span style="font-size:.875rem;font-weight:700;"><?= $userName ?></span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Client Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/profile">My Profile</a>
          <a class="dropdown-item" href="/wallet">Wallet &amp; Escrow</a>
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

<!-- DISPUTE BANNER -->
<div style="background:#FBE9E7;border-bottom:2px solid #D84040;padding:16px 0;">
  <div class="container">
    <div style="display:flex;gap:12px;align-items:center;justify-content:space-between;">
      <div style="display:flex;gap:12px;align-items:flex-start;flex:1;">
        <span style="font-size:1.4rem;">⚖️</span>
        <div style="flex:1;">
          <div style="font-weight:700;color:#D84040;margin-bottom:2px;">Dispute Active — Project Frozen</div>
          <div style="font-size:.875rem;color:#B2423A;">
            No actions can be taken until this dispute is resolved.
            <?php if ($active_dispute && $active_dispute['status'] === 'under_review'): ?>
            Arbitrator is currently reviewing all evidence.
            <?php else: ?>
            Direct messaging with the specialist is disabled.
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div style="display:flex;gap:12px;align-items:center;white-space:nowrap;">
        <div style="font-size:.75rem;color:#B2423A;">Reference: <?= htmlspecialchars($dispute_ref) ?></div>
        <?php if ($active_dispute): ?>
        <a href="/dispute?id=<?= (int)$active_dispute['id'] ?>" class="btn btn-danger btn-sm"
           style="background:#D84040;color:#fff;border-color:#D84040;">View Dispute →</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- PROJECT HEADER -->
<div class="project-header-band">
  <div class="container">
    <div class="breadcrumb">Active Projects <span>›</span> <?= $ref ?></div>
    <div class="flex justify-between items-start mt-8">
      <div>
        <h2 style="margin-bottom:8px;"><?= $title ?></h2>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <span class="badge badge-gold"><?= $niche ?></span>
          <span class="badge badge-danger badge-dot">Dispute Active</span>
          <span class="text-sm text-muted font-mono">Ref: <?= $ref ?></span>
          <span class="text-sm text-muted">Phase <?= $current_phase ?> of <?= $total_phases ?> · Started <?= date('M d', strtotime($project['started_at'] ?? $project['created_at'] ?? 'now')) ?></span>
        </div>
      </div>
      <div>
        <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;">⚖️ Project Frozen</button>
      </div>
    </div>

    <div class="grid-4 mt-24">
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$<?= number_format($total, 0) ?></div>
        <div class="stat-label">Total Budget</div>
      </div>
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$<?= number_format($funds['escrowed'], 0) ?></div>
        <div class="stat-label">In Escrow (Phase <?= $current_phase ?>)</div>
      </div>
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$<?= number_format($funds['cleared'], 0) ?></div>
        <div class="stat-label">Released</div>
      </div>
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$<?= number_format($funds['on_hold'], 0) ?></div>
        <div class="stat-label">Frozen (Dispute)</div>
      </div>
    </div>
  </div>
</div>

<div class="container" style="padding-top:32px;padding-bottom:48px;">
  <div class="project-body">

    <!-- LEFT: MILESTONES (all frozen) -->
    <div>

      <!-- DONE MILESTONES -->
      <?php foreach ($done_milestones as $idx => $ms):
        $badge = $getMsBadge($ms['status']);
      ?>
      <div class="milestone-card done">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--sage);font-weight:700;margin-bottom:4px;">Phase <?= (int)$ms['sort_order'] + 1 ?> · Completed</div>
            <div class="milestone-title"><?= htmlspecialchars($ms['milestone_name']) ?></div>
          </div>
          <span class="badge <?= $badge['cls'] ?>"><?= $badge['label'] ?></span>
        </div>
        <div class="milestone-meta">
          <span>Duration: <strong><?= (int)$ms['duration_days'] ?> days</strong></span>
          <span>Released: <strong>$<?= number_format((float)$ms['amount'], 0) ?></strong></span>
        </div>
        <div class="progress-bar"><div class="progress-fill success" style="width:100%;"></div></div>
        <div style="margin-top:16px;">
          <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;">View Deliverables</button>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- ACTIVE MILESTONE (frozen) -->
      <?php if ($am): ?>
      <div class="milestone-card active">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:4px;">Phase <?= $current_phase ?> · In Progress</div>
            <div class="milestone-title"><?= htmlspecialchars($am['milestone_name']) ?></div>
          </div>
          <span class="badge badge-pending badge-dot">In Progress</span>
        </div>
        <div class="milestone-meta">
          <span>Duration: <strong><?= (int)$am['duration_days'] ?> days</strong></span>
          <span>Budget: <strong>$<?= number_format((float)$am['amount'], 0) ?></strong></span>
          <span>Deadline: <strong><?= $amDeadline ?><?= $amDaysLeft !== null ? " ({$amDaysLeft} days)" : '' ?></strong></span>
        </div>
        <div class="progress-bar mb-8"><div class="progress-fill" style="width:<?= (int)($am['progress_pct'] ?? 0) ?>%;"></div></div>

        <?php if (!empty($am['deliverables'])): ?>
        <div class="deliverable-list">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);font-weight:700;margin-bottom:8px;">Deliverables</div>
          <?php foreach (array_filter(array_map('trim', explode("\n", $am['deliverables']))) as $dkey => $del): ?>
          <div class="deliverable-item">
            <div class="deliverable-check partial">◐</div>
            <span style="flex:1;"><?= htmlspecialchars($del) ?></span>
            <div class="deliverable-actions">
              <button class="btn btn-ghost btn-sm" disabled style="opacity:.5;cursor:not-allowed;">View</button>
              <span class="badge badge-pending" style="font-size:.625rem;">Frozen</span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="revision-tracker">
          <div>
            <div style="font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:4px;">Revisions Used</div>
            <div class="revision-dots">
              <?php for ($r = 0; $r < $free_revisions; $r++): ?>
              <div class="rev-dot <?= $r < $revisions_used ? 'used' : '' ?>"></div>
              <?php endfor; ?>
            </div>
          </div>
          <div class="text-xs text-muted"><?= $revisions_used ?> of <?= $free_revisions ?> free revisions used</div>
        </div>

        <?php if ($wip_snapshots): ?>
        <div style="margin-top:16px;">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:8px;">WIP Snapshots</div>
          <?php foreach (array_slice($wip_snapshots, 0, 2) as $snap): ?>
          <div class="wip-snapshot">
            <div class="wip-icon">📓</div>
            <div style="flex:1;">
              <div style="font-weight:700;"><?= htmlspecialchars($snap['file_name']) ?></div>
              <div class="text-xs text-muted"><?= date('M d, H:i', strtotime($snap['uploaded_at'])) ?></div>
            </div>
            <button class="btn btn-ghost btn-sm" disabled style="opacity:.5;cursor:not-allowed;">View</button>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- ALL ACTIONS FROZEN -->
        <div style="margin-top:16px;display:flex;gap:10px;">
          <button class="btn btn-primary btn-sm" disabled style="opacity:.5;cursor:not-allowed;flex:1;text-align:center;">
            Actions Frozen — Dispute Active
          </button>
        </div>
      </div>
      <?php endif; ?>

      <!-- LOCKED MILESTONES (all frozen) -->
      <?php foreach ($locked_milestones as $lms): ?>
      <div class="milestone-card locked">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-faint);font-weight:700;margin-bottom:4px;">Phase <?= (int)$lms['sort_order'] + 1 ?> · Locked</div>
            <div class="milestone-title" style="color:var(--ink-faint);"><?= htmlspecialchars($lms['milestone_name']) ?></div>
          </div>
          <span class="badge badge-default">🔒 Locked</span>
        </div>
        <div class="text-sm text-muted">Budget: $<?= number_format((float)$lms['amount'], 0) ?></div>
        <div class="text-sm" style="color:#D84040;margin-top:6px;">⚖️ Project in Dispute — Actions Frozen</div>
        <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
          <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;">Set Deliverables &amp; QA</button>
        </div>
      </div>
      <?php endforeach; ?>

    </div><!-- /left -->

    <!-- RIGHT: SIDEBAR (frozen) -->
    <div>

      <div class="deadline-card" style="background:#FBE9E7;border-color:#F0B4AA;">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#D84040;margin-bottom:6px;">⚖️ Dispute Active</div>
        <div style="font-weight:700;margin-bottom:4px;">Reference: <?= htmlspecialchars($dispute_ref) ?></div>
        <?php if ($active_dispute): ?>
        <div style="font-size:.75rem;color:#B2423A;margin-top:4px;">
          Status: <?= ucfirst(str_replace('_', ' ', $active_dispute['status'])) ?><br>
          Opened: <?= date('M d, Y', strtotime($active_dispute['created_at'])) ?>
        </div>
        <?php endif; ?>
        <div style="margin-top:10px;font-size:.75rem;color:#B2423A;">Arbitrator verdict expected within 72h of filing.</div>
        <?php if ($active_dispute): ?>
        <a href="/dispute?id=<?= (int)$active_dispute['id'] ?>"
           class="btn btn-danger btn-sm" style="margin-top:10px;width:100%;justify-content:center;background:#D84040;color:#fff;border-color:#D84040;">
          View Dispute Details →
        </a>
        <?php endif; ?>
      </div>

      <!-- ESCROW SUMMARY (frozen) -->
      <div class="escrow-sidebar-card">
        <h4 style="font-size:.9rem;margin-bottom:12px;">Escrow Summary</h4>
        <?php foreach ($done_milestones as $ms): ?>
        <div class="escrow-line">
          <span>Phase <?= (int)$ms['sort_order'] + 1 ?> Released</span>
          <span class="font-mono" style="color:var(--sage);">$<?= number_format((float)$ms['amount'], 0) ?></span>
        </div>
        <?php endforeach; ?>
        <?php if ($am): ?>
        <div class="escrow-line">
          <span>Phase <?= $current_phase ?> Locked</span>
          <span class="font-mono" style="color:var(--gold);">$<?= number_format((float)$am['amount'], 0) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($funds['on_hold'] > 0): ?>
        <div class="escrow-line">
          <span>Frozen (Dispute)</span>
          <span class="font-mono" style="color:var(--rust);">$<?= number_format($funds['on_hold'], 0) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($funds['remaining_locked'] > 0): ?>
        <div class="escrow-line">
          <span>Remaining Phases</span>
          <span class="font-mono text-muted">$<?= number_format($funds['remaining_locked'], 0) ?></span>
        </div>
        <?php endif; ?>
        <div class="escrow-line" style="font-weight:700;">
          <span>Total</span>
          <span class="font-mono">$<?= number_format($total, 0) ?></span>
        </div>
        <a href="/wallet" class="btn btn-outline btn-sm w-full mt-12" style="justify-content:center;">View Wallet →</a>
      </div>

      <!-- SPECIALIST CARD (messaging disabled) -->
      <div class="escrow-sidebar-card">
        <h4 style="font-size:.9rem;margin-bottom:12px;">Specialist</h4>
        <div class="flex items-center gap-10 mb-12">
          <div class="avatar-badge"><div class="avatar avatar-md"><?= $sp_initials ?></div></div>
          <div>
            <div style="font-weight:700;font-size:.875rem;"><?= $sp_name ?></div>
            <div class="text-xs text-muted"><?= htmlspecialchars($project['primary_niche'] ?? '') ?></div>
            <?php if ($sp_rating > 0): ?>
            <div class="stars" style="font-size:.75rem;"><?= str_repeat('★', (int)round((float)$sp_rating)) ?></div>
            <?php endif; ?>
          </div>
        </div>
        <div class="text-xs text-muted mb-8">NDA Active</div>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <!-- Messaging disabled during dispute -->
          <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;justify-content:center;">
            💬 Messaging Disabled
          </button>
          <a href="/profile?user_id=<?= (int)$project['specialist_user_id'] ?>"
             class="btn btn-ghost btn-sm" style="justify-content:center;">View Profile</a>
        </div>
      </div>

    </div><!-- /right -->
  </div>
</div>

<div class="toast-stack" id="toast-stack"></div>

<script>
function toggleDD() { document.getElementById('user-dd').classList.toggle('hidden'); }
document.addEventListener('click', e => { if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden'); });
function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  const icons = {success:'✓', warn:'⚠', info:'ℹ'};
  const cls   = {success:'success', warn:'warning', info:''};
  s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type==='warn'?'Notice':'Info'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML='', 5000);
}
</script>
</body>
</html>