<?php
$user_id  = (int) ($_SESSION['user_id'] ?? 0);
$role     = $_SESSION['role'] ?? '';
$userName = htmlspecialchars($_SESSION['user_name'] ?? 'User');
$initials = strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 2));
$isAdmin  = in_array($role, ['Admin', 'Arbitrator']);

// Status → progress step index (0-based)
$statusStep = [
    'open'         => 1,
    'under_review' => 3,
    'resolved'     => 5,
    'closed'       => 5,
];

$steps = [
    '1 · Dispute Filed',
    '2 · Evidence Assembled',
    '3 · Arbiter Assigned',
    '4 · Under Review',
    '5 · Verdict Issued',
    '6 · Funds Released',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dispute Center — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/dispute.css">
</head>
<body>

<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="/">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="/dashboard">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="/notifications" class="btn btn-ghost btn-icon"><svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg></a>
      <div class="dropdown">
        <button type="button" class="btn btn-ghost btn-icon" style="display:flex;align-items:center;gap:10px;" onclick="toggleProfileDD()">
          <div class="avatar avatar-sm"><?= $initials ?></div>
          <span style="font-size:.875rem;font-weight:700;"><?= $userName ?></span>
          <span style="color:var(--ink-faint);">▾</span>
        </button>
        <div class="dropdown-menu hidden" id="profile-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;"><?= htmlspecialchars($role) ?> Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/profile">My Profile</a>
          <a class="dropdown-item" href="/wallet">Wallet</a>
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

<?php if (!empty($dispute)): /* ══ DETAIL VIEW ══ */
  $d        = $dispute;
  $curStep  = $statusStep[$d['status']] ?? 1;
  $isParty  = ($d['raised_by'] == $user_id || $d['against'] == $user_id);
  $isOpen   = in_array($d['status'], ['open','under_review']);
?>

<!-- DISPUTE HERO -->
<div class="dispute-hero">
  <div class="container">
    <div class="breadcrumb">
      Projects <span>›</span>
      NX-<?= date('Y') ?>-<?= (int)$d['project_id'] ?>
      <span>›</span> Dispute Center
    </div>
    <div class="flex justify-between items-start mt-8 mb-24">
      <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
          <h2><?= htmlspecialchars($d['project_title']) ?></h2>
          <span class="badge badge-danger badge-dot">
            <?= ucfirst(str_replace('_', ' ', $d['status'])) ?>
          </span>
        </div>
        <div style="display:flex;gap:12px;font-size:.8125rem;color:var(--ink-muted);">
          <span>Ref: DSP-NX-<?= (int)$d['id'] ?>-<?= date('Y') ?></span>
          <span>·</span>
          <span>Opened: <?= date('M d, Y', strtotime($d['created_at'])) ?></span>
          <?php if ($d['arbitrator_name']): ?>
          <span>·</span>
          <span>Arbiter: <?= htmlspecialchars($d['arbitrator_name']) ?></span>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- progress bar -->
    <div class="dispute-status-bar">
      <?php foreach ($steps as $i => $label): ?>
      <div class="dispute-status-step <?= $i < $curStep ? 'done' : ($i === $curStep ? 'active' : '') ?>">
        <?= $label ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div class="container" style="padding-top:32px;padding-bottom:48px;">
  <div class="dispute-body">

    <!-- LEFT COLUMN -->
    <div>

      <!-- SAFE-ROOM NOTICE -->
      <?php if ($isOpen): ?>
      <div class="safroom-notice">
        <span style="font-size:1.2rem;">🔒</span>
        <div>
          <strong>Safe-Room Communication Active</strong><br>
          All direct messaging between parties is suspended during dispute. Communication is restricted to this monitored channel.
          <?php if ($d['arbitrator_name']): ?>
          Overseen by Arbiter <?= htmlspecialchars($d['arbitrator_name']) ?>.
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- TABS -->
      <div class="tabs mb-24">
        <button class="tab-item active" onclick="switchTab(0)">Overview</button>
        <button class="tab-item" onclick="switchTab(1)">Arguments</button>
        <button class="tab-item" onclick="switchTab(2)">Safe-Room Chat</button>
        <button class="tab-item" onclick="switchTab(3)">Verdict</button>
      </div>

      <!-- ── TAB 0: OVERVIEW ── -->
      <div id="dt-0">
        <h3 class="mb-16">Dispute Summary</h3>
        <div class="card card-sm mb-16">
          <div class="form-row" style="margin-bottom:0;">
            <div>
              <div class="text-xs text-muted mb-4">Disputed Milestone</div>
              <div style="font-weight:700;"><?= htmlspecialchars($d['milestone_name'] ?? 'General Dispute') ?></div>
            </div>
            <div>
              <div class="text-xs text-muted mb-4">Filed By</div>
              <div style="font-weight:700;"><?= htmlspecialchars($d['raised_by_name']) ?></div>
            </div>
            <div>
              <div class="text-xs text-muted mb-4">Against</div>
              <div style="font-weight:700;"><?= htmlspecialchars($d['against_name']) ?></div>
            </div>
          </div>
        </div>

        <!-- Claimant block -->
        <div class="party-card claimant mb-0">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:10px;">
            <?= $d['raised_by'] == $user_id ? 'You' : htmlspecialchars($d['raised_by_name']) ?> — Claimant
          </div>
          <div class="flex items-center gap-12 mb-10">
            <div class="avatar avatar-sm"><?= strtoupper(substr($d['raised_by_name'], 0, 2)) ?></div>
            <div>
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($d['raised_by_name']) ?></div>
            </div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);"><?= nl2br(htmlspecialchars($d['reason'])) ?></p>
        </div>

        <!-- Respondent block -->
        <div class="party-card respondent">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#7A5C10;margin-bottom:10px;">
            <?= $d['against'] == $user_id ? 'You' : htmlspecialchars($d['against_name']) ?> — Respondent
          </div>
          <div class="flex items-center gap-12 mb-10">
            <div class="avatar avatar-sm"><?= strtoupper(substr($d['against_name'], 0, 2)) ?></div>
            <div>
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($d['against_name']) ?></div>
            </div>
          </div>
          <?php
            // Show first respondent message from thread if available
            $respondentMsg = '';
            foreach ($messages as $m) {
                if ($m['user_id'] == $d['against']) { $respondentMsg = $m['body']; break; }
            }
          ?>
          <?php if ($respondentMsg): ?>
          <p style="font-size:.875rem;color:var(--ink-mid);"><?= nl2br(htmlspecialchars($respondentMsg)) ?></p>
          <?php else: ?>
          <p style="font-size:.875rem;color:var(--ink-faint);">Respondent statement pending…</p>
          <?php endif; ?>
        </div>

        <hr class="divider">
        <h3 class="mb-16">Dispute Timeline</h3>
        <div class="timeline-item">
          <div class="timeline-dot" style="background:var(--rust);"></div>
          <div>
            <div style="font-weight:700;font-size:.875rem;">Dispute opened by <?= htmlspecialchars($d['raised_by_name']) ?></div>
            <div class="text-xs text-muted"><?= date('M d, Y · H:i', strtotime($d['created_at'])) ?></div>
          </div>
        </div>
        <?php if ($d['arbitrator_name']): ?>
        <div class="timeline-item">
          <div class="timeline-dot admin"></div>
          <div>
            <div style="font-weight:700;font-size:.875rem;">Arbiter <?= htmlspecialchars($d['arbitrator_name']) ?> assigned</div>
          </div>
        </div>
        <?php endif; ?>
        <?php if ($d['status'] === 'resolved' || $d['status'] === 'closed'): ?>
        <div class="timeline-item">
          <div class="timeline-dot" style="background:var(--sage);"></div>
          <div>
            <div style="font-weight:700;font-size:.875rem;">Dispute resolved</div>
            <div class="text-xs text-muted"><?= $d['resolved_at'] ? date('M d, Y · H:i', strtotime($d['resolved_at'])) : '' ?></div>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- ── TAB 1: ARGUMENTS ── -->
      <div id="dt-1" class="hidden">
        <h3 class="mb-16">Party Arguments</h3>

        <?php
          $claimantMsgs   = array_filter($messages, fn($m) => $m['user_id'] == $d['raised_by']);
          $respondentMsgs = array_filter($messages, fn($m) => $m['user_id'] == $d['against']);
        ?>

        <?php if ($claimantMsgs): ?>
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:10px;">
          <?= htmlspecialchars($d['raised_by_name']) ?> — Claimant
        </div>
        <?php foreach ($claimantMsgs as $m): ?>
        <div class="argument-item claimant"><?= nl2br(htmlspecialchars($m['body'])) ?></div>
        <?php endforeach; ?>
        <hr class="divider">
        <?php endif; ?>

        <?php if ($respondentMsgs): ?>
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#7A5C10;margin-bottom:10px;">
          <?= htmlspecialchars($d['against_name']) ?> — Respondent
        </div>
        <?php foreach ($respondentMsgs as $m): ?>
        <div class="argument-item respondent"><?= nl2br(htmlspecialchars($m['body'])) ?></div>
        <?php endforeach; ?>
        <hr class="divider">
        <?php endif; ?>

        <?php if ($isOpen && $isParty): ?>
        <form method="POST" action="/dispute/message">
          <input type="hidden" name="dispute_id" value="<?= (int)$d['id'] ?>">
          <div class="form-group">
            <label class="form-label">Submit Additional Argument</label>
            <textarea name="body" id="argument-textarea" class="form-control" rows="4"
              placeholder="Add a formal argument to the dispute record…" required minlength="10"></textarea>
            <div class="text-xs text-muted mt-4">Arguments are logged to the dispute record and visible to the arbiter.</div>
          </div>
          <button class="btn btn-primary btn-sm" type="submit">Submit Argument</button>
        </form>
        <?php endif; ?>
      </div>

      <!-- ── TAB 2: SAFE-ROOM CHAT ── -->
      <div id="dt-2" class="hidden">
        <div style="background:#FBE9E7;border:1.5px solid var(--rust);border-radius:var(--radius-md);padding:14px 18px;margin-bottom:20px;font-size:.8125rem;color:var(--rust);">
          🔒 <strong>Monitored Safe-Room.</strong> All messages are recorded and included in the evidence package.
        </div>
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:20px;display:flex;flex-direction:column;gap:12px;max-height:500px;overflow:hidden;">
          <div class="chat-sender-info" style="text-align:center;">
            SAFE-ROOM OPENED · <?= strtoupper(date('M d, H:i', strtotime($d['created_at']))) ?>
          </div>
          <div id="safe-room-messages" style="flex:1;min-height:200px;overflow-y:auto;display:flex;flex-direction:column;gap:12px;padding-right:8px;">
            <?php foreach ($messages as $m):
              $isMine = ($m['user_id'] == $user_id);
              $isArb  = (isset($d['arbitrator_id']) && $m['user_id'] == $d['arbitrator_id']);
              $mTime  = date('H:i', strtotime($m['created_at']));
            ?>
            <?php if ($isArb): ?>
            <div class="chat-bubble saferoom" style="max-width:100%;">
              ⚖️ <?= htmlspecialchars($m['user_name']) ?>: <?= nl2br(htmlspecialchars($m['body'])) ?>
            </div>
            <?php elseif ($isMine): ?>
            <div class="chat-sender-info right">You · <?= $mTime ?></div>
            <div class="chat-bubble out" style="max-width:75%;align-self:flex-end;">
              <?= nl2br(htmlspecialchars($m['body'])) ?>
            </div>
            <?php else: ?>
            <div class="chat-sender-info left"><?= htmlspecialchars($m['user_name']) ?> · <?= $mTime ?></div>
            <div class="chat-bubble in" style="max-width:75%;">
              <?= nl2br(htmlspecialchars($m['body'])) ?>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php if (empty($messages)): ?>
            <div class="chat-sender-info" style="text-align:center;">No messages yet.</div>
            <?php endif; ?>
          </div>

          <?php if ($isOpen && ($isParty || $isAdmin)): ?>
          <form method="POST" action="/dispute/message" id="safe-room-form">
            <input type="hidden" name="dispute_id" value="<?= (int)$d['id'] ?>">
            <div class="chat-input-group" style="display:flex;gap:10px;align-items:flex-end;margin-top:12px;">
              <textarea name="body" class="chat-textarea" rows="2"
                placeholder="Type a Safe-Room message…" required
                onkeypress="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();document.getElementById('safe-room-form').submit();}"></textarea>
              <button type="submit" class="chat-btn-circle send" title="Send message">↑</button>
            </div>
          </form>
          <?php else: ?>
          <div class="text-xs text-muted" style="text-align:center;margin-top:8px;">
            <?= $d['status'] === 'resolved' ? 'This dispute has been resolved. Chat is closed.' : 'You do not have permission to post in this safe-room.' ?>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- ── TAB 3: VERDICT ── -->
      <div id="dt-3" class="hidden">
        <?php if ($d['status'] === 'resolved' || $d['status'] === 'closed'): ?>
        <div class="verdict-card">
          <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--sage);margin-bottom:16px;">⚖️ Arbiter Verdict — Issued</div>
          <h3 style="margin-bottom:12px;">Dispute Resolved</h3>
          <p style="font-size:.875rem;color:var(--ink-mid);margin-bottom:20px;">
            <?= nl2br(htmlspecialchars($d['resolution'] ?? 'No resolution notes provided.')) ?>
          </p>
          <div class="text-xs text-muted">Resolved: <?= $d['resolved_at'] ? date('M d, Y · H:i', strtotime($d['resolved_at'])) : '—' ?></div>
        </div>

        <?php else: ?>
        <div class="verdict-card">
          <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:16px;">⚖️ Arbiter Verdict — Pending</div>
          <h3 style="margin-bottom:12px;">Verdict not yet issued</h3>
          <p style="font-size:.875rem;color:var(--ink-mid);margin-bottom:20px;">
            The arbiter is currently reviewing all evidence and arguments. A verdict is typically issued within 72 hours of dispute filing.
          </p>
          <div class="progress-bar mb-8"><div class="progress-fill" style="width:55%;"></div></div>
          <div class="text-xs text-muted">Review in progress.</div>
          <hr class="divider">
          <div style="font-size:.8125rem;color:var(--ink-muted);">
            <strong>Once the verdict is issued, the arbiter may:</strong><br>
            · Release 100% to the specialist (delivery accepted)<br>
            · Refund 100% to the client (delivery rejected)<br>
            · Split the escrowed amount (partial acceptance)<br>
            · Order a mandatory free revision
          </div>
        </div>

        <?php if ($isAdmin): ?>
        <hr class="divider">
        <form method="POST" action="/dispute/resolve" class="mt-24">
          <input type="hidden" name="dispute_id" value="<?= (int)$d['id'] ?>">
          <div class="form-group">
            <label class="form-label">Arbitrator Resolution</label>
            <textarea name="resolution" class="form-control" rows="5"
              placeholder="Provide the official verdict and resolution…" required minlength="10"></textarea>
          </div>
          <button class="btn btn-danger" type="submit">Close &amp; Issue Verdict</button>
        </form>
        <?php endif; ?>
        <?php endif; ?>
      </div>

    </div><!-- /left -->

    <!-- RIGHT SIDEBAR -->
    <div>

      <?php if ($d['arbitrator_name']): ?>
      <div class="arbitrator-card">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Assigned Arbiter</div>
        <div class="flex items-center gap-12 mb-12">
          <div class="avatar avatar-md" style="background:var(--ink);color:var(--ivory);">
            <?= strtoupper(substr($d['arbitrator_name'], 0, 2)) ?>
          </div>
          <div>
            <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($d['arbitrator_name']) ?></div>
            <div class="text-xs text-muted">Dispute Mediator</div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div class="card card-sm mb-16">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Dispute Status</div>
        <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:300;margin-bottom:4px;">
          <?= ucfirst(str_replace('_', ' ', $d['status'])) ?>
        </div>
        <div class="text-xs text-muted">
          Opened: <?= date('M d, Y', strtotime($d['created_at'])) ?>
        </div>
      </div>

      <div class="card card-sm mb-16">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Verdict Timeline</div>
        <div style="font-size:.8125rem;color:var(--ink-mid);">
          <div class="flex justify-between mb-8"><span>Filed</span><span class="font-mono"><?= date('M d, H:i', strtotime($d['created_at'])) ?></span></div>
          <?php if ($d['resolved_at']): ?>
          <div class="flex justify-between mb-8"><span>Resolved</span><span class="font-mono"><?= date('M d, H:i', strtotime($d['resolved_at'])) ?></span></div>
          <?php else: ?>
          <div class="flex justify-between mb-8"><span>Expected verdict</span><span class="font-mono">Within 72h of filing</span></div>
          <?php endif; ?>
          <div class="flex justify-between"><span>Appeal window</span><span class="font-mono">48h post-verdict</span></div>
        </div>
      </div>

      <?php if ($isOpen && $isParty): ?>
      <button class="btn btn-outline btn-sm w-full" onclick="document.getElementById('appeal-modal').classList.remove('hidden')">
        Request Appeal
      </button>
      <?php endif; ?>

    </div><!-- /right sidebar -->
  </div><!-- /dispute-body -->
</div>

<?php else: /* ══ LIST VIEW ══ */ ?>

<div class="container" style="padding-top:40px;padding-bottom:48px;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
    <h2 style="font-family:var(--font-display);font-size:1.8rem;font-weight:400;">Dispute Center</h2>
    <button class="btn btn-outline btn-sm" onclick="document.getElementById('open-dispute-modal').classList.remove('hidden')">
      + Open New Dispute
    </button>
  </div>

  <?php if (empty($disputes)): ?>
  <div style="text-align:center;padding:60px 0;color:var(--ink-muted);">
    <div style="font-size:2.5rem;margin-bottom:16px;">⚖️</div>
    <div style="font-weight:600;margin-bottom:8px;">No disputes</div>
    <div class="text-sm">You have no active or past disputes.</div>
  </div>
  <?php else: foreach ($disputes as $d):
    $isOpen = in_array($d['status'], ['open','under_review']);
  ?>
  <div class="card card-sm mb-16" style="<?= $isOpen ? 'border-left:3px solid var(--rust);' : '' ?>">
    <div class="flex justify-between items-start">
      <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
          <a href="/dispute?id=<?= (int)$d['id'] ?>" style="font-weight:700;font-size:.9375rem;color:var(--ink);">
            <?= htmlspecialchars($d['project_title']) ?>
          </a>
          <span class="badge badge-<?= $isOpen ? 'danger' : 'verified' ?>">
            <?= ucfirst(str_replace('_', ' ', $d['status'])) ?>
          </span>
        </div>
        <div class="text-xs text-muted">
          DSP-NX-<?= (int)$d['id'] ?>-<?= date('Y') ?> ·
          Opened <?= date('M d, Y', strtotime($d['created_at'])) ?>
          <?php if ($d['raised_by_name']): ?> · Filed by <?= htmlspecialchars($d['raised_by_name']) ?><?php endif; ?>
        </div>
      </div>
      <a href="/dispute?id=<?= (int)$d['id'] ?>" class="btn btn-outline btn-sm">View →</a>
    </div>
  </div>
  <?php endforeach; endif; ?>
</div>

<!-- OPEN NEW DISPUTE MODAL -->
<div id="open-dispute-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>File a Dispute</h3>
        <p class="text-sm text-muted mt-4">Provide details about the issue. The platform will assemble an evidence package automatically.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('open-dispute-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <form method="POST" action="/dispute/open">
        <div class="form-group">
          <label class="form-label">Project ID</label>
          <input type="number" name="project_id" class="form-control" placeholder="e.g. 42" required>
        </div>
        <div class="form-group">
          <label class="form-label">Other Party User ID</label>
          <input type="number" name="against" class="form-control" placeholder="User ID" required>
        </div>
        <div class="form-group">
          <label class="form-label">Reason for Dispute</label>
          <textarea name="reason" class="form-control" rows="5"
            placeholder="Describe the issue clearly (min. 20 characters)…" required minlength="20"></textarea>
        </div>
        <div class="modal-footer" style="padding:0;margin-top:16px;">
          <button type="button" class="btn btn-outline" onclick="document.getElementById('open-dispute-modal').classList.add('hidden')">Cancel</button>
          <button type="submit" class="btn btn-danger">File Dispute</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php endif; ?>

<!-- APPEAL MODAL -->
<div id="appeal-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Request Verdict Appeal</h3>
        <p class="text-sm text-muted mt-4">Appeals are reviewed by a senior arbiter. You must provide valid grounds.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('appeal-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Grounds for Appeal</label>
        <select class="form-control">
          <option>Evidence was overlooked or not considered</option>
          <option>New evidence has emerged since the verdict</option>
          <option>Arbiter demonstrated procedural bias</option>
          <option>Verdict contradicts written contract terms</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Describe Your Appeal</label>
        <textarea class="form-control" rows="5" placeholder="Provide a clear, factual basis for your appeal…"></textarea>
      </div>
      <div class="verify-band">
        <span>ℹ️</span>
        <div style="font-size:.8125rem;">If your appeal is rejected, a $75 fee will be deducted from your next payout.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('appeal-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('appeal-modal').classList.add('hidden')">Submit Appeal</button>
    </div>
  </div>
</div>

<div class="toast-stack" id="toast-stack"></div>

<script>
function switchTab(i) {
  document.querySelectorAll('.tabs .tab-item').forEach((t,j) => t.classList.toggle('active', i===j));
  for(let j=0;j<5;j++) { const el=document.getElementById('dt-'+j); if(el) el.classList.toggle('hidden',i!==j); }
}
function toggleProfileDD() {
  document.getElementById('profile-dd')?.classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('profile-dd')?.classList.add('hidden');
});
// Auto-scroll safe-room messages to bottom
const sr = document.getElementById('safe-room-messages');
if (sr) sr.scrollTop = sr.scrollHeight;

function showToast(msg) {
  const toast = document.createElement('div');
  toast.className = 'toast success';
  toast.innerHTML = `<span class="toast-icon">✓</span><div><div class="toast-title">Done</div><div class="toast-body">${msg}</div></div>`;
  (document.getElementById('toast-stack') || document.body).appendChild(toast);
  setTimeout(() => toast.remove(), 4000);
}
</script>
</body>
</html>