<?php
/**
 * Views/job/project-detail.php  — CLIENT view
 * Injected by ProjectController::ProjectDetail()
 *
 * $project           — projects row + joined client/specialist info
 * $milestones        — project_milestones[] ordered by sort_order
 * $active_milestone  — current in_progress milestone (or null)
 * $done_milestones   — paid/approved milestones[]
 * $locked_milestones — pending milestones[]
 * $wip_snapshots     — bid_attachments[] for current milestone
 * $funds             — [cleared, escrowed, on_hold, total, remaining_locked, fee_rate]
 * $messages_unread   — int
 * $revisions_used    — int
 * $free_revisions    — int
 * $revision_price    — float
 * $is_client         — bool
 * $is_specialist     — bool
 * $user_id           — int
 * $role              — string
 */

$initials    = strtoupper(substr($_SESSION['user_name'] ?? 'CL', 0, 2));
$userName    = htmlspecialchars($_SESSION['user_name'] ?? 'Client');
$unread_notif = (int) ($_SESSION['notif_unread'] ?? 0);

$project_id  = (int) $project['project_id'];
$ref         = htmlspecialchars($project['contract_ref'] ?? 'CON-NX-' . $project_id);
$title       = htmlspecialchars($project['title'] ?? '');
$niche       = htmlspecialchars($project['primary_niche'] ?? $project['niche'] ?? '');
$status      = $project['status'] ?? 'active';
$total       = (float) $project['total_amount'];
$fee_rate    = (float) ($funds['fee_rate'] ?? 0.065);

// Specialist info
$sp_name     = htmlspecialchars($project['specialist_name'] ?? 'Specialist');
$sp_initials = strtoupper(substr($project['specialist_name'] ?? 'SP', 0, 2));
$sp_rating   = number_format((float)($project['specialist_rating'] ?? 0), 1);

// Active milestone helpers
$am = $active_milestone;
$amDaysLeft  = $am ? (int)($am['days_left'] ?? 0) : null;
$amDeadline  = $am && $am['due_date'] ? date('M j, Y', strtotime($am['due_date'])) : '—';
$amUrgency   = match(true) {
    $amDaysLeft === null  => '',
    $amDaysLeft <= 1      => 'deadline-card urgent',
    $amDaysLeft <= 4      => 'deadline-card',
    default               => 'deadline-card',
};

// Milestone status helpers
$msBadge = [
    'paid'               => ['cls' => 'badge-verified badge-dot', 'label' => 'Completed'],
    'approved'           => ['cls' => 'badge-verified badge-dot', 'label' => 'Completed'],
    'in_progress'        => ['cls' => 'badge-pending badge-dot',  'label' => 'In Progress'],
    'submitted'          => ['cls' => 'badge-pending badge-dot',  'label' => 'Under Review'],
    'revision_requested' => ['cls' => 'badge-gold',               'label' => 'Revision Requested'],
    'pending'            => ['cls' => 'badge-default',            'label' => '🔒 Locked'],
];
$getMsBadge = fn($s) => $msBadge[$s] ?? ['cls' => 'badge-default', 'label' => ucfirst($s)];

// Phase count for header
$total_phases = count($milestones);
$done_count   = count($done_milestones);
$current_phase = $done_count + 1;

// All deliverables done? (enables Approve button)
// In real app, check deliverable status table. Here we use active milestone status.
$all_deliverables_done = $am && $am['status'] === 'submitted';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?> — Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/project-detail.css">
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

<!-- PROJECT HEADER -->
<div class="project-header-band">
  <div class="container">
    <div class="breadcrumb">Active Projects <span>›</span> <?= $ref ?></div>
    <div class="flex justify-between items-start mt-8">
      <div>
        <h2 style="margin-bottom:8px;"><?= $title ?></h2>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <span class="badge badge-gold"><?= $niche ?></span>
          <span class="badge badge-verified badge-dot">Active</span>
          <span class="text-sm text-muted font-mono">Ref: <?= $ref ?></span>
          <span class="text-sm text-muted">
            Phase <?= $current_phase ?> of <?= $total_phases ?>
            · Started <?= date('M d', strtotime($project['started_at'] ?? $project['created_at'] ?? 'now')) ?>
          </span>
        </div>
      </div>
      <div style="display:flex;gap:10px;">
        <?php if ($is_client): ?>
        <a href="/dispute/open?project_id=<?= $project_id ?>&against=<?= (int)$project['specialist_user_id'] ?>"
           class="btn btn-danger btn-sm">⚖️ Open Dispute</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="grid-4 mt-24">
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$<?= number_format($total, 0) ?></div>
        <div class="stat-label">Total Budget</div>
      </div>
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$<?= number_format($funds['escrowed'], 0) ?></div>
        <div class="stat-label">In Escrow<?php if ($am): ?> (Phase <?= $current_phase ?>)<?php endif; ?></div>
      </div>
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$<?= number_format($funds['cleared'], 0) ?></div>
        <div class="stat-label">Released</div>
      </div>
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">
          <?= $amDaysLeft !== null ? $amDaysLeft . 'd' : '—' ?>
        </div>
        <div class="stat-label">Days Until Next Deadline</div>
      </div>
    </div>
  </div>
</div>

<div class="container" style="padding-top:32px;padding-bottom:48px;">
  <div class="project-body">

    <!-- ══ LEFT: MILESTONES ══ -->
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
          <?php if ($ms['approved_at']): ?>
          <span>Completed: <strong><?= date('M d', strtotime($ms['approved_at'])) ?></strong></span>
          <?php endif; ?>
        </div>
        <div class="progress-bar"><div class="progress-fill success" style="width:100%;"></div></div>
        <div style="display:flex;gap:10px;align-items:center;margin-top:16px;">
          <button class="btn btn-outline btn-sm" onclick="openDeliverablesModal(<?= $idx ?>)">View Deliverables</button>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- ACTIVE MILESTONE -->
      <?php if ($am): ?>
      <div class="milestone-card active">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:4px;">
              Phase <?= (int)$am['sort_order'] + 1 ?> · <?= $am['status'] === 'submitted' ? 'Under Review' : 'In Progress' ?>
            </div>
            <div class="milestone-title"><?= htmlspecialchars($am['milestone_name']) ?></div>
          </div>
          <?php $amBadge = $getMsBadge($am['status']); ?>
          <span class="badge <?= $amBadge['cls'] ?>"><?= $amBadge['label'] ?></span>
        </div>
        <div class="milestone-meta">
          <span>Duration: <strong><?= (int)$am['duration_days'] ?> days</strong></span>
          <span>Budget: <strong>$<?= number_format((float)$am['amount'], 0) ?></strong></span>
          <span>Deadline: <strong><?= $amDeadline ?> <?= $amDaysLeft !== null ? "({$amDaysLeft} days)" : '' ?></strong></span>
        </div>
        <div class="progress-bar mb-8">
          <div class="progress-fill" style="width:<?= (int)($am['progress_pct'] ?? 0) ?>%;"></div>
        </div>
        <div class="flex justify-between text-xs text-muted mb-12">
          <span><?= (int)($am['progress_pct'] ?? 0) ?>% complete</span>
          <?php if (!empty($wip_snapshots)): ?>
          <span>WIP snapshot available</span>
          <?php endif; ?>
        </div>

        <!-- DELIVERABLES -->
        <?php if (!empty($am['deliverables_spec'])): ?>
        <div class="deliverable-list">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);font-weight:700;margin-bottom:8px;">Deliverables</div>
          <?php
            $deliverables_text = $am['deliverables'] ?? $am['deliverables_spec']['deliverables'] ?? '';
            $dels = array_filter(array_map('trim', explode("\n", $deliverables_text)));
          ?>
          <?php if ($dels): foreach ($dels as $dkey => $del): ?>
          <div class="deliverable-item" data-deliverable-key="del-<?= $dkey ?>">
            <div class="deliverable-check"></div>
            <span style="flex:1;color:var(--ink-muted);"><?= htmlspecialchars($del) ?></span>
            <span class="badge badge-default" style="font-size:.625rem;">Not Submitted</span>
          </div>
          <?php endforeach; else: ?>
          <div class="text-sm text-muted">No deliverables specified for this milestone.</div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- REVISION TRACKER -->
        <div class="revision-tracker">
          <div>
            <div style="font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:4px;">Revisions Used</div>
            <div class="revision-dots">
              <?php for ($r = 0; $r < $free_revisions; $r++): ?>
              <div class="rev-dot <?= $r < $revisions_used ? 'used' : '' ?>"></div>
              <?php endfor; ?>
            </div>
          </div>
          <div class="text-xs text-muted">
            <?= $revisions_used ?> of <?= $free_revisions ?> free revisions used<br>
            Additional: $<?= number_format($revision_price, 0) ?>/revision
          </div>
        </div>

        <!-- WIP SNAPSHOTS -->
        <?php if ($wip_snapshots): ?>
        <div style="margin-top:16px;">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:8px;">WIP Snapshots</div>
          <?php foreach (array_slice($wip_snapshots, 0, 3) as $snap): ?>
          <div class="wip-snapshot">
            <div class="wip-icon">📓</div>
            <div style="flex:1;">
              <div style="font-weight:700;"><?= htmlspecialchars($snap['file_name']) ?></div>
              <div class="text-xs text-muted">
                <?= date('M d, H:i', strtotime($snap['uploaded_at'])) ?>
                <?php if ($snap['file_size']): ?> · <?= round($snap['file_size'] / 1048576, 1) ?> MB<?php endif; ?>
              </div>
            </div>
            <button class="btn btn-ghost btn-sm" onclick="openWipSnapshot('<?= htmlspecialchars($snap['file_name']) ?>', '<?= date('M d', strtotime($snap['uploaded_at'])) ?>')">View</button>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- APPROVE BUTTON (Client only) -->
        <?php if ($is_client): ?>
        <div style="margin-top:16px;display:flex;gap:10px;">
          <button id="approve-release-btn"
            class="btn btn-primary btn-sm"
            <?= !$all_deliverables_done ? 'disabled' : '' ?>
            onclick="document.getElementById('approve-modal').classList.remove('hidden')">
            Approve &amp; Release Funds
          </button>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- LOCKED MILESTONES -->
      <?php if ($locked_milestones): ?>
      <?php foreach ($locked_milestones as $lms): ?>
      <div id="phase-<?= (int)$lms['sort_order'] + 1 ?>-card"
           class="milestone-card locked"
           data-prev-milestone-complete="<?= (count($done_milestones) >= (int)$lms['sort_order']) ? 'true' : 'false' ?>"
           data-deliverables-set="false"
           data-qa-set="false">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-faint);font-weight:700;margin-bottom:4px;">
              Phase <?= (int)$lms['sort_order'] + 1 ?> · Locked
            </div>
            <div class="milestone-title" style="color:var(--ink-faint);"><?= htmlspecialchars($lms['milestone_name']) ?></div>
          </div>
          <span class="badge badge-default">🔒 Locked</span>
        </div>
        <div class="text-sm text-muted">Budget: $<?= number_format((float)$lms['amount'], 0) ?></div>
        <div class="escrow-status-note text-sm text-muted" id="phase-<?= (int)$lms['sort_order'] + 1 ?>-escrow-note">
          Escrow not yet locked. Funds can be reserved before phase start.
        </div>
        <?php if ($is_client): ?>
        <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
          <button class="btn btn-outline btn-sm"
            onclick="openMilestonePlanModal('Phase <?= (int)$lms['sort_order'] + 1 ?>','<?= htmlspecialchars(addslashes($lms['milestone_name'])) ?>','phase-<?= (int)$lms['sort_order'] + 1 ?>-card')">
            Set Deliverables &amp; QA
          </button>
          <button class="btn btn-gold btn-sm lock-escrow-btn hidden"
            onclick="openEscrowLockModal('phase-<?= (int)$lms['sort_order'] + 1 ?>-card','Phase <?= (int)$lms['sort_order'] + 1 ?>','<?= htmlspecialchars(addslashes($lms['milestone_name'])) ?>','$<?= number_format((float)$lms['amount'], 0) ?>')">
            Lock Escrow
          </button>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>

    </div><!-- /left -->

    <!-- ══ RIGHT: SIDEBAR ══ -->
    <div>

      <!-- DEADLINE CARD -->
      <?php if ($am && $amDaysLeft !== null): ?>
      <div class="<?= $amUrgency ?>">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#9A6800;margin-bottom:6px;">⏱ Upcoming Deadline</div>
        <div style="font-weight:700;margin-bottom:4px;">Phase <?= $current_phase ?> Delivery</div>
        <div style="font-family:var(--font-mono);font-size:1.2rem;font-weight:500;"><?= $amDaysLeft ?> days remaining</div>
        <div style="font-size:.75rem;color:var(--ink-muted);margin-top:4px;"><?= $amDeadline ?></div>
        <div style="margin-top:10px;font-size:.75rem;">If specialist does not submit within the deadline, the platform will notify you.</div>
      </div>
      <?php endif; ?>

      <!-- ESCROW SUMMARY -->
      <div class="escrow-sidebar-card">
        <h4 style="font-size:.9rem;margin-bottom:12px;">Escrow Summary</h4>
        <?php foreach ($done_milestones as $idx => $ms): ?>
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

      <!-- SPECIALIST CARD -->
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
        <div class="text-xs text-muted mb-8">
          NDA: <?= ucfirst($project['nda_type'] ?? 'standard') ?>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <a href="/chat?with=<?= (int)$project['specialist_user_id'] ?>&project=<?= $project_id ?>"
             class="btn btn-outline btn-sm" style="justify-content:center;">
            💬 Message<?= $messages_unread > 0 ? " ({$messages_unread} unread)" : '' ?>
          </a>
          <a href="/profile?user_id=<?= (int)$project['specialist_user_id'] ?>"
             class="btn btn-ghost btn-sm" style="justify-content:center;">View Profile</a>
        </div>
      </div>

    </div><!-- /right -->
  </div><!-- /project-body -->
</div>

<!-- ══ APPROVE MODAL ══ -->
<div id="approve-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3>Approve Phase <?= $current_phase ?> &amp; Release Funds</h3>
      <button class="modal-close" onclick="document.getElementById('approve-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="verify-band mb-16">
        <span>💰</span>
        <div style="font-size:.8125rem;">
          <strong>$<?= $am ? number_format((float)$am['amount'], 0) : 0 ?></strong>
          will be released from escrow to <?= $sp_name ?> within 24h.
        </div>
      </div>
      <form method="POST" action="/project/approve">
        <input type="hidden" name="project_id"   value="<?= $project_id ?>">
        <input type="hidden" name="milestone_id" value="<?= $am ? (int)$am['id'] : 0 ?>">
        <div class="form-group">
          <label class="form-label">Feedback (optional)</label>
          <textarea name="feedback" class="form-control" rows="3" placeholder="Share your thoughts on the deliverables…"></textarea>
        </div>
        <div class="modal-footer" style="padding:0;margin-top:16px;">
          <button type="button" class="btn btn-outline" onclick="document.getElementById('approve-modal').classList.add('hidden')">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm Release</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ══ DELIVERABLES MODAL ══ -->
<div id="deliverables-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <h3 id="del-modal-title">Phase Deliverables</h3>
      <button class="modal-close" onclick="document.getElementById('deliverables-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div id="del-modal-body">
        <?php foreach ($done_milestones as $idx => $ms): ?>
        <div class="del-phase-block" id="del-phase-<?= $idx ?>" style="display:none;">
          <?php
            $dels = array_filter(array_map('trim', explode("\n", $ms['deliverables'] ?? '')));
          ?>
          <?php if ($dels): foreach ($dels as $del): ?>
          <div class="deliverable-file-card">
            <div class="deliverable-file-meta">
              <div class="file-name"><?= htmlspecialchars($del) ?></div>
              <div class="file-note">Delivered <?= $ms['approved_at'] ? date('M d, Y', strtotime($ms['approved_at'])) : '' ?></div>
            </div>
            <button class="btn btn-ghost btn-sm">Download</button>
          </div>
          <?php endforeach; else: ?>
          <p class="text-muted">No deliverable files recorded for this phase.</p>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('deliverables-modal').classList.add('hidden')">Close</button>
    </div>
  </div>
</div>

<!-- ══ ESCROW LOCK MODAL ══ -->
<div id="escrow-lock-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3 id="escrow-lock-modal-title">Lock Escrow</h3>
      <button class="modal-close" onclick="closeEscrowLockModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="verify-band mb-16">
        <span>🛡️</span>
        <div style="font-size:.8125rem;">
          <strong id="escrow-lock-amount">$0</strong> will be reserved for <strong id="escrow-lock-phase">this phase</strong>.
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Payment Method</label>
        <div class="payment-method-card active">
          <div class="card-logo">MC</div>
          <div class="payment-card-body">
            <div class="payment-card-title">Default Payment Method</div>
          </div>
          <span class="badge badge-verified" style="font-size:.625rem;">Default</span>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeEscrowLockModal()">Cancel</button>
      <button class="btn btn-primary" onclick="confirmEscrowLock()">Lock Escrow</button>
    </div>
  </div>
</div>

<!-- ══ MILESTONE PLAN MODAL ══ -->
<div id="milestone-deliverables-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <h3 id="milestone-plan-title">Plan Milestone</h3>
      <button class="modal-close" onclick="closeMilestonePlanModal()">✕</button>
    </div>
    <div class="modal-body">
      <p id="milestone-plan-subtitle" class="text-muted mb-16"></p>
      <div class="modal-tabs" style="display:flex;gap:10px;margin-bottom:18px;">
        <button type="button" class="modal-tab active" id="deliverables-tab" onclick="switchMilestoneTab('deliverables')">Deliverables</button>
        <button type="button" class="modal-tab" id="qa-tab" onclick="switchMilestoneTab('qa')">QA Checklist</button>
      </div>
      <div id="milestone-deliverables-section">
        <div id="deliverables-list"></div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="addDeliverableField()">+ Add Deliverable</button>
      </div>
      <div id="milestone-qa-section" class="hidden">
        <div id="qa-checklist"></div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="addQaField()">+ Add QA Question</button>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeMilestonePlanModal()">Cancel</button>
      <button class="btn btn-primary" onclick="saveMilestoneDeliverables()">Save</button>
    </div>
  </div>
</div>

<div class="toast-stack" id="toast-stack"></div>

<script>
function toggleDD() { document.getElementById('user-dd').classList.toggle('hidden'); }
document.addEventListener('click', e => { if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden'); });

function openDeliverablesModal(phaseIdx) {
  document.querySelectorAll('.del-phase-block').forEach(b => b.style.display = 'none');
  const block = document.getElementById('del-phase-' + phaseIdx);
  if (block) block.style.display = '';
  document.getElementById('del-modal-title').textContent = 'Phase ' + (phaseIdx + 1) + ' Deliverables';
  document.getElementById('deliverables-modal').classList.remove('hidden');
}

function openWipSnapshot(name, meta) {
  showToast('Previewing ' + name + ' · ' + meta, 'info');
}

// Escrow lock
window.currentEscrowCard = null;
function openEscrowLockModal(cardId, phaseLabel, subtitle, amount) {
  window.currentEscrowCard = cardId;
  document.getElementById('escrow-lock-modal-title').textContent = 'Lock Escrow for ' + phaseLabel;
  document.getElementById('escrow-lock-phase').textContent = phaseLabel;
  document.getElementById('escrow-lock-amount').textContent = amount;
  document.getElementById('escrow-lock-modal').classList.remove('hidden');
}
function closeEscrowLockModal() { document.getElementById('escrow-lock-modal').classList.add('hidden'); }
function confirmEscrowLock() {
  const card = document.getElementById(window.currentEscrowCard);
  if (card) {
    const note   = card.querySelector('.escrow-status-note');
    const btn    = card.querySelector('.lock-escrow-btn');
    const badge  = card.querySelector('.milestone-header .badge');
    if (note)  note.textContent = 'Escrow locked for this milestone.';
    if (badge) { badge.textContent = '🔒 Escrow Locked'; badge.className = 'badge badge-gold'; }
    if (btn)   { btn.textContent = 'Escrow Locked'; btn.disabled = true; }
  }
  closeEscrowLockModal();
  showToast('Escrow locked successfully.');
}

// Milestone plan
window.currentMilestonePlanCard = null;
function openMilestonePlanModal(title, subtitle, cardId) {
  window.currentMilestonePlanCard = cardId;
  document.getElementById('milestone-plan-title').textContent = 'Set Deliverables for ' + title;
  document.getElementById('milestone-plan-subtitle').textContent = subtitle;
  document.getElementById('deliverables-list').innerHTML = '';
  document.getElementById('qa-checklist').innerHTML = '';
  addDeliverableField(); addQaField();
  switchMilestoneTab('deliverables');
  document.getElementById('milestone-deliverables-modal').classList.remove('hidden');
}
function closeMilestonePlanModal() { document.getElementById('milestone-deliverables-modal').classList.add('hidden'); }
function switchMilestoneTab(tab) {
  document.getElementById('deliverables-tab').classList.toggle('active', tab === 'deliverables');
  document.getElementById('qa-tab').classList.toggle('active', tab === 'qa');
  document.getElementById('milestone-deliverables-section').classList.toggle('hidden', tab !== 'deliverables');
  document.getElementById('milestone-qa-section').classList.toggle('hidden', tab !== 'qa');
}
function addField(listId, placeholder) {
  const list = document.getElementById(listId);
  const idx  = list.children.length + 1;
  const row  = document.createElement('div');
  row.className = 'field-row';
  row.innerHTML = `<div class="field-number">${idx}</div><input type="text" class="field-input" placeholder="${placeholder} ${idx}"><button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('.field-row').remove()">✕</button>`;
  list.appendChild(row);
}
function addDeliverableField() { addField('deliverables-list', 'Deliverable'); }
function addQaField()          { addField('qa-checklist', 'QA item'); }
function saveMilestoneDeliverables() {
  const card = document.getElementById(window.currentMilestonePlanCard);
  if (card) { card.dataset.deliverablesSet = 'true'; card.dataset.qaSet = 'true'; }
  closeMilestonePlanModal();
  updateLockEscrowButtons();
  showToast('Deliverables & QA saved.');
}
function updateLockEscrowButtons() {
  document.querySelectorAll('.milestone-card.locked').forEach(card => {
    const prevComplete  = card.dataset.prevMilestoneComplete === 'true';
    const dSet = card.dataset.deliverablesSet === 'true';
    const qSet = card.dataset.qaSet === 'true';
    const btn  = card.querySelector('.lock-escrow-btn');
    if (btn) btn.classList.toggle('hidden', !(prevComplete && dSet && qSet));
  });
}
window.addEventListener('DOMContentLoaded', updateLockEscrowButtons);

function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  const icons = {success:'✓', warn:'⚠', info:'ℹ'};
  const cls   = {success:'success', warn:'warning', info:''};
  s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type==='warn'?'Notice':type==='info'?'Info':'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML='', 5000);
}
</script>
</body>
</html>