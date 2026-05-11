<?php
// Expected variables from AdminController::disputeDetail():
// $dispute   — [ id, ref, project_title, project_ref, opened_at, arbiter_assigned_at,
//                status_step (1-6), escrow_amount, milestone_title,
//                claimant(name,initials,org,contracts,disputes_filed),
//                respondent(name,initials,location,contracts,disputes_received),
//                claimant_summary, respondent_summary,
//                arbiter(name,initials,ref),
//                timeline[], evidence[], claimant_args[], respondent_args[],
//                saferoom_messages[], verdict_deadline, sanction(name,tier,notes) ]
$steps = ['Dispute Filed','Evidence Assembled','Arbiter Assigned','Under Review','Verdict Issued','Funds Released'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dispute Center — Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/admin-dispute.css">
</head>
<body>

<nav class="topnav" style="background:var(--ink);border-bottom:1px solid rgba(247,244,239,.1);">
  <div class="container" style="max-width:100%;padding:0 32px;">
    <a class="topnav-logo" href="/admin" style="color:var(--ivory);">Nexus<span style="color:var(--gold);">.</span></a>
    <div class="topnav-links"><a href="/admin" style="color:rgba(247,244,239,.6);">Dashboard</a></div>
    <div class="topnav-actions">
      <div class="flex items-center gap-8">
        <div class="avatar avatar-sm" style="background:var(--gold);color:var(--ink);font-size:.75rem;font-weight:700;"><?= strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? ''), 0, 2)) ?: 'ME' ?></div>
        <span style="font-size:.875rem;font-weight:700;color:var(--ivory);"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Me') ?></span>
        <span class="role-badge rb-super" style="font-size:.6rem;"><?= htmlspecialchars(($_SESSION['role'] ?? 'Account') . ' Account') ?></span>
      </div>
    </div>
  </div>
</nav>

<div class="admin-shell">
  <main class="admin-main">

<div class="dispute-hero">
  <div class="container">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
      <a href="/admin/disputes" class="btn btn-ghost btn-sm">← Back to Disputes</a>
      <div class="breadcrumb">Projects <span>›</span> <?= htmlspecialchars($dispute['project_ref'] ?? '') ?> <span>›</span> Dispute Center</div>
    </div>
    <div class="flex justify-between items-start mt-8 mb-24">
      <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
          <h2><?= htmlspecialchars($dispute['project_title'] ?? '') ?></h2>
          <span class="badge badge-danger badge-dot">Dispute Active</span>
        </div>
        <div style="display:flex;gap:12px;font-size:.8125rem;color:var(--ink-muted);">
          <span>Ref: <?= htmlspecialchars($dispute['ref'] ?? '') ?></span>
          <span>·</span>
          <span>Opened: <?= date('M j, Y', strtotime($dispute['opened_at'] ?? 'now')) ?></span>
          <span>·</span>
          <span>Arbiter assigned: <?= date('M j', strtotime($dispute['arbiter_assigned_at'] ?? 'now')) ?></span>
        </div>
      </div>
    </div>
    <div class="dispute-status-bar">
      <?php foreach ($steps as $i => $label):
        $step = $i + 1;
        $cls  = $step < ($dispute['status_step'] ?? 1) ? 'done' : ($step === ($dispute['status_step'] ?? 1) ? 'active' : '');
      ?>
        <div class="dispute-status-step <?= $cls ?>"><?= $step ?> · <?= htmlspecialchars($label) ?></div>
      <?php endforeach ?>
    </div>
  </div>
</div>

<div class="container" style="padding-top:32px;padding-bottom:48px;">
  <div class="dispute-body">

    <!-- LEFT COLUMN -->
    <div>
      <div class="safroom-notice">
        <span style="font-size:1.2rem;">🔒</span>
        <div>
          <strong>Safe-Room Communication Active</strong><br>
          All direct messaging between parties is suspended during dispute. Communication is restricted to this monitored channel, overseen by Arbiter <?= htmlspecialchars($dispute['arbiter']['name'] ?? '') ?>. All messages in this thread are admissible as evidence.
        </div>
      </div>

      <div class="tabs mb-24">
        <button class="tab-item active" onclick="switchTab(0)">Overview</button>
        <button class="tab-item" onclick="switchTab(1)">Evidence Package</button>
        <button class="tab-item" onclick="switchTab(2)">Arguments</button>
        <button class="tab-item" onclick="switchTab(3)">Safe-Room Chat</button>
        <button class="tab-item" onclick="switchTab(4)">Verdict</button>
      </div>

      <!-- OVERVIEW -->
      <div id="dt-0">
        <h3 class="mb-16">Dispute Summary</h3>
        <div class="card card-sm mb-16">
          <div class="form-row" style="margin-bottom:0;">
            <div>
              <div class="text-xs text-muted mb-4">Disputed Milestone</div>
              <div style="font-weight:700;"><?= htmlspecialchars($dispute['milestone_title'] ?? '') ?></div>
              <div class="text-xs text-muted font-mono mt-4">$<?= number_format($dispute['escrow_amount'] ?? 0) ?> in escrow</div>
            </div>
            <div>
              <div class="text-xs text-muted mb-4">Claimed By</div>
              <div style="font-weight:700;">Client (<?= htmlspecialchars($dispute['claimant']['name'] ?? '') ?>)</div>
              <div class="text-xs text-muted mt-4">Quality below contracted standard</div>
            </div>
          </div>
        </div>

        <div class="party-card claimant mb-0">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:10px;">Client — Claimant</div>
          <div class="flex items-center gap-12 mb-10">
            <div class="avatar avatar-sm"><?= htmlspecialchars($dispute['claimant']['initials'] ?? '') ?></div>
            <div>
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($dispute['claimant']['name'] ?? '') ?></div>
              <div class="text-xs text-muted"><?= htmlspecialchars($dispute['claimant']['org'] ?? '') ?></div>
            </div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);"><?= htmlspecialchars($dispute['claimant_summary'] ?? '') ?></p>
        </div>

        <div class="party-card respondent">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#7A5C10;margin-bottom:10px;">Specialist — Respondent</div>
          <div class="flex items-center gap-12 mb-10">
            <div class="avatar avatar-sm"><?= htmlspecialchars($dispute['respondent']['initials'] ?? '') ?></div>
            <div>
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($dispute['respondent']['name'] ?? '') ?></div>
              <div class="text-xs text-muted"><?= htmlspecialchars($dispute['respondent']['niche'] ?? '') ?> · <?= htmlspecialchars($dispute['respondent']['location'] ?? '') ?></div>
            </div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);"><?= htmlspecialchars($dispute['respondent_summary'] ?? '') ?></p>
        </div>

        <hr class="divider">
        <h3 class="mb-16">Dispute Timeline</h3>
        <?php foreach ($dispute['timeline'] ?? [] as $event): ?>
          <div class="timeline-item">
            <div class="timeline-dot <?= htmlspecialchars($event['dot_class'] ?? '') ?>" style="<?= !empty($event['dot_color']) ? 'background:' . htmlspecialchars($event['dot_color']) . ';' : '' ?>"></div>
            <div>
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($event['title']) ?></div>
              <div class="text-xs text-muted"><?= date('M j, Y', strtotime($event['at'])) ?>, <?= date('H:i', strtotime($event['at'])) ?> · <?= htmlspecialchars($event['note'] ?? '') ?></div>
            </div>
          </div>
        <?php endforeach ?>
      </div>

      <!-- EVIDENCE PACKAGE -->
      <div id="dt-1" class="hidden">
        <div class="flex justify-between items-center mb-16">
          <h3>Evidence Package</h3>
          <span class="badge badge-default"><?= count($dispute['evidence'] ?? []) ?> items · Auto-assembled <?= date('M j', strtotime($dispute['opened_at'] ?? 'now')) ?></span>
        </div>
        <div class="verify-band mb-16">
          <span>🤖</span>
          <div style="font-size:.8125rem;">All evidence below was automatically assembled by the Nexus system at the time the dispute was filed. No items have been added, removed, or altered. Audit hash: <span class="font-mono"><?= htmlspecialchars($dispute['audit_hash'] ?? 'sha256:—') ?></span></div>
        </div>
        <?php
        $lastGroup = null;
        foreach ($dispute['evidence'] ?? [] as $ev):
          if ($ev['group'] !== $lastGroup): $lastGroup = $ev['group'];
        ?>
            <?php if (!$loop->first ?? false): ?><hr class="divider"><?php endif ?>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:10px;"><?= htmlspecialchars($ev['group']) ?></div>
          <?php endif ?>
          <div class="evidence-item">
            <div class="evidence-icon"><?= htmlspecialchars($ev['icon'] ?? '📄') ?></div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($ev['title']) ?></div>
              <div class="evidence-meta"><?= htmlspecialchars($ev['meta']) ?></div>
              <div class="evidence-source">Source: <?= htmlspecialchars($ev['source']) ?></div>
            </div>
            <a href="/admin/disputes/<?= (int)($dispute['id'] ?? 0) ?>/evidence/<?= (int)$ev['id'] ?>" class="btn btn-ghost btn-sm">View</a>
          </div>
        <?php endforeach ?>
      </div>

      <!-- ARGUMENTS -->
      <div id="dt-2" class="hidden">
        <h3 class="mb-16">Party Arguments</h3>
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:10px;">Client — Claimant Arguments</div>
        <?php foreach ($dispute['claimant_args'] ?? [] as $i => $arg): ?>
          <div class="argument-item claimant">
            <div class="argument-header"><?= $i + 1 ?>. <?= htmlspecialchars($arg['title']) ?></div>
            <?= htmlspecialchars($arg['body']) ?>
          </div>
        <?php endforeach ?>
        <hr class="divider">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#7A5C10;margin-bottom:10px;">Specialist — Respondent Arguments</div>
        <?php foreach ($dispute['respondent_args'] ?? [] as $i => $arg): ?>
          <div class="argument-item respondent">
            <div class="argument-header"><?= $i + 1 ?>. <?= htmlspecialchars($arg['title']) ?></div>
            <?= htmlspecialchars($arg['body']) ?>
          </div>
        <?php endforeach ?>
        <hr class="divider">
      </div>

      <!-- SAFE-ROOM CHAT -->
      <div id="dt-3" class="hidden">
        <div style="background:#FBE9E7;border:1.5px solid var(--rust);border-radius:var(--radius-md);padding:14px 18px;margin-bottom:20px;font-size:.8125rem;color:var(--rust);">
          🔒 <strong>Monitored Safe-Room.</strong> All messages are recorded, reviewed by Arbiter <?= htmlspecialchars($dispute['arbiter']['name'] ?? '') ?>, and included in the evidence package. Standard messaging is suspended.
        </div>
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:20px;height:600px;display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
          <div style="text-align:center;font-size:.75rem;font-family:var(--font-mono);color:var(--ink-faint);margin-bottom:8px;">
            SAFE-ROOM OPENED · <?= strtoupper(date('M j, H:i', strtotime($dispute['opened_at'] ?? 'now'))) ?>
          </div>
          <div id="safe-room-messages" style="flex:1;min-height:0;overflow-y:auto;display:flex;flex-direction:column;gap:12px;padding-right:8px;">
            <?php foreach ($dispute['saferoom_messages'] ?? [] as $msg):
              $cls = $msg['role'] === 'arbiter' ? 'saferoom' : ($msg['role'] === 'respondent' ? 'in' : 'out');
            ?>
              <?php if ($msg['role'] !== 'arbiter'): ?>
                <div class="chat-sender-info <?= $msg['role'] === 'respondent' ? 'left' : 'right' ?>">
                  <?= ucfirst($msg['role']) ?> · <?= date('H:i', strtotime($msg['created_at'])) ?>
                </div>
              <?php endif ?>
              <div class="chat-bubble <?= $cls ?>" style="max-width:<?= $msg['role'] === 'arbiter' ? '100%' : '75%' ?>;<?= $msg['role'] === 'claimant' ? 'align-self:flex-end;' : '' ?>">
                <?php if ($msg['role'] === 'arbiter'): ?>⚖️ Arbiter <?= htmlspecialchars($dispute['arbiter']['name'] ?? '') ?>: <?php endif ?>
                <?= htmlspecialchars($msg['text']) ?>
              </div>
            <?php endforeach ?>
          </div>
        </div>
        <div class="chat-input-group" style="display:flex;gap:10px;align-items:flex-end;margin-top:12px;">
          <textarea class="chat-textarea" rows="2" placeholder="Type a Safe-Room message…" onkeypress="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendSafeRoomMessage();}"></textarea>
          <button class="chat-btn-circle send" title="Send message" onclick="sendSafeRoomMessage()">↑</button>
        </div>
      </div>

      <!-- VERDICT -->
      <div id="dt-4" class="hidden">
        <div class="verdict-card">
          <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:16px;">⚖️ Issue Verdict</div>
          <h3 style="margin-bottom:6px;">Determine Fund Allocation</h3>
          <p style="font-size:.8125rem;color:var(--ink-muted);margin-bottom:24px;">Set the percentage each party receives from the escrowed <strong>$<?= number_format($dispute['escrow_amount'] ?? 0) ?></strong>. This decision is final and will be recorded in the dispute file.</p>

          <div style="margin-bottom:24px;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:10px;">
              <div>
                <span style="font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);">Claimant</span>
                <span style="font-family:var(--font-mono);font-size:1.1rem;font-weight:700;margin-left:6px;" id="claimant-pct">50%</span>
                <span style="font-family:var(--font-mono);font-size:.8125rem;color:var(--ink-muted);margin-left:4px;" id="claimant-amount">($<?= number_format(($dispute['escrow_amount'] ?? 0) / 2) ?>)</span>
              </div>
              <div style="text-align:right;">
                <span style="font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#9A6800;">Respondent</span>
                <span style="font-family:var(--font-mono);font-size:1.1rem;font-weight:700;margin-left:6px;" id="respondent-pct">50%</span>
                <span style="font-family:var(--font-mono);font-size:.8125rem;color:var(--ink-muted);margin-left:4px;" id="respondent-amount">($<?= number_format(($dispute['escrow_amount'] ?? 0) / 2) ?>)</span>
              </div>
            </div>
            <input type="range" id="split-slider" min="0" max="100" value="50" style="width:100%;cursor:pointer;accent-color:var(--ink);height:6px;" oninput="updateSplit(this.value)">
            <div style="display:flex;justify-content:space-between;margin-top:6px;">
              <span class="text-xs text-muted">0% → Full refund to Claimant</span>
              <span class="text-xs text-muted">100% → Full payout to Respondent</span>
            </div>
          </div>

          <div class="verdict-split mb-20" id="verdict-preview-bar">
            <div class="verdict-split-a" id="preview-claimant" style="width:50%;">Claimant 50%</div>
            <div class="verdict-split-b" id="preview-respondent" style="width:50%;">Respondent 50%</div>
          </div>

          <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:24px;">
            <span class="text-xs text-muted" style="align-self:center;margin-right:4px;">Quick:</span>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="100" onclick="setSplit(100)">100% Claimant</button>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="70" onclick="setSplit(70)">70 / 30</button>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="50" onclick="setSplit(50)">50 / 50</button>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="30" onclick="setSplit(30)">30 / 70</button>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="0" onclick="setSplit(0)">100% Respondent</button>
          </div>

          <hr class="divider">
          <div class="form-group" style="margin-top:20px;">
            <label class="form-label">Verdict Statement</label>
            <textarea class="form-control" id="verdict-message" rows="5" placeholder="Write your verdict reasoning…"></textarea>
            <div class="text-xs text-muted mt-4">This statement will be visible to both parties and cannot be edited after submission.</div>
          </div>
          <div style="display:flex;gap:12px;align-items:center;margin-top:20px;">
            <button class="btn btn-primary btn-lg" type="button" onclick="issueVerdict()">⚖️ Issue Verdict</button>
            <span class="text-xs text-muted">This action is final and irreversible.</span>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT SIDEBAR -->
    <div>
      <div class="arbitrator-card">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:16px;">Dispute Parties</div>
        <div style="border-left:3px solid var(--rust);padding-left:12px;margin-bottom:16px;">
          <div style="font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:6px;">Claimant</div>
          <div class="flex items-center gap-10 mb-6">
            <div class="avatar avatar-sm" style="flex-shrink:0;"><?= htmlspecialchars($dispute['claimant']['initials'] ?? '') ?></div>
            <div>
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($dispute['claimant']['name'] ?? '') ?></div>
              <div class="text-xs text-muted"><?= htmlspecialchars($dispute['claimant']['org'] ?? '') ?> · Client</div>
            </div>
          </div>
          <div style="font-size:.75rem;color:var(--ink-muted);">
            <div class="flex justify-between mb-2"><span>Contracts completed</span><span class="font-mono"><?= (int)($dispute['claimant']['contracts'] ?? 0) ?></span></div>
            <div class="flex justify-between"><span>Disputes filed</span><span class="font-mono"><?= (int)($dispute['claimant']['disputes_filed'] ?? 0) ?></span></div>
          </div>
        </div>
        <hr style="border:none;border-top:1px solid var(--border);margin-bottom:16px;">
        <div style="border-left:3px solid var(--gold);padding-left:12px;">
          <div style="font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#9A6800;margin-bottom:6px;">Respondent</div>
          <div class="flex items-center gap-10 mb-6">
            <div class="avatar avatar-sm" style="flex-shrink:0;"><?= htmlspecialchars($dispute['respondent']['initials'] ?? '') ?></div>
            <div>
              <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($dispute['respondent']['name'] ?? '') ?></div>
              <div class="text-xs text-muted"><?= htmlspecialchars($dispute['respondent']['niche'] ?? '') ?> · Specialist</div>
            </div>
          </div>
          <div style="font-size:.75rem;color:var(--ink-muted);">
            <div class="flex justify-between mb-2"><span>Contracts completed</span><span class="font-mono"><?= (int)($dispute['respondent']['contracts'] ?? 0) ?></span></div>
            <div class="flex justify-between"><span>Disputes received</span><span class="font-mono"><?= (int)($dispute['respondent']['disputes_received'] ?? 0) ?></span></div>
          </div>
        </div>
      </div>

      <div class="card card-sm mb-16">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Escrowed Funds</div>
        <div style="font-family:var(--font-display);font-size:2rem;font-weight:300;margin-bottom:4px;">$<?= number_format($dispute['escrow_amount'] ?? 0) ?></div>
        <div class="text-xs text-muted mb-12"><?= htmlspecialchars($dispute['milestone_title'] ?? '') ?> — frozen during dispute</div>
        <div class="progress-bar"><div class="progress-fill danger" style="width:100%;background:var(--rust);"></div></div>
        <div class="text-xs text-muted mt-4">Funds will not be released or refunded until verdict is issued.</div>
      </div>

      <?php if (!empty($dispute['sanction'])): ?>
      <div class="card card-sm mb-16">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">User Sanction Status</div>
        <div style="font-size:.875rem;font-weight:700;margin-bottom:6px;"><?= htmlspecialchars($dispute['sanction']['name']) ?></div>
        <?php
        $sTierClass = ['warn' => 'sanction-warn', 'limit' => 'sanction-limit', 'ban' => 'sanction-ban'];
        $sTierLabel = ['warn' => '⚠ Warning Issued', 'limit' => '⛔ Limited Ban', 'ban' => '⛔ Permanent Ban'];
        $t = $dispute['sanction']['tier'];
        ?>
        <span class="<?= $sTierClass[$t] ?? '' ?>"><?= $sTierLabel[$t] ?? '' ?></span>
        <div class="text-xs text-muted mt-8"><?= htmlspecialchars($dispute['sanction']['notes'] ?? '') ?></div>
        <hr class="divider">
        <div style="font-size:.875rem;font-weight:700;margin-bottom:6px;">Sanction Tiers</div>
        <div style="font-size:.75rem;color:var(--ink-muted);line-height:1.9;">Tier 1: Warning<br>Tier 2: Limited Access<br>Tier 3: Permanent Ban</div>
      </div>
      <?php endif ?>

      <div class="card card-sm">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Verdict Timeline</div>
        <div style="font-size:.8125rem;color:var(--ink-mid);">
          <div class="flex justify-between mb-8"><span>Filed</span><span class="font-mono"><?= date('M j, H:i', strtotime($dispute['opened_at'] ?? 'now')) ?></span></div>
          <div class="flex justify-between mb-8"><span>Arbiter assigned</span><span class="font-mono"><?= date('M j, H:i', strtotime($dispute['arbiter_assigned_at'] ?? 'now')) ?></span></div>
          <div class="flex justify-between mb-8"><span>Expected verdict</span><span class="font-mono"><?= date('M j, H:i', strtotime($dispute['verdict_deadline'] ?? 'now')) ?></span></div>
          <div class="flex justify-between"><span>Appeal window</span><span class="font-mono">48h post-verdict</span></div>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="toast-stack" id="toast-stack"></div>

<script>
const ESCROW_TOTAL = <?= (int)($dispute['escrow_amount'] ?? 0) ?>;
function switchTab(i) {
  document.querySelectorAll('.tabs .tab-item').forEach((t,j) => t.classList.toggle('active', i===j));
  for(let j=0;j<5;j++){const el=document.getElementById('dt-'+j);if(el)el.classList.toggle('hidden',i!==j);}
}
function sendSafeRoomMessage() {
  const ta=document.querySelector('.chat-textarea'),c=document.getElementById('safe-room-messages');
  if(!ta||!c||!ta.value.trim())return;
  const d=document.createElement('div');d.className='chat-bubble saferoom';d.style.maxWidth='100%';
  d.textContent='⚖️ Arbiter: '+ta.value.trim();c.appendChild(d);c.scrollTop=c.scrollHeight;ta.value='';ta.focus();
}
function updateSplit(val){
  const cp=100-parseInt(val),rp=parseInt(val);
  const ca=Math.round(ESCROW_TOTAL*cp/100),ra=ESCROW_TOTAL-ca;
  document.getElementById('claimant-pct').textContent=cp+'%';
  document.getElementById('respondent-pct').textContent=rp+'%';
  document.getElementById('claimant-amount').textContent='($'+ca.toLocaleString()+')';
  document.getElementById('respondent-amount').textContent='($'+ra.toLocaleString()+')';
  document.getElementById('split-slider').value=val;
  const pa=document.getElementById('preview-claimant'),pb=document.getElementById('preview-respondent');
  pa.style.width=Math.max(cp,0)+'%';pb.style.width=Math.max(rp,0)+'%';
  pa.textContent=cp>8?'Claimant '+cp+'%':'';pb.textContent=rp>8?'Respondent '+rp+'%':'';
  pa.style.display=cp===0?'none':'';pb.style.display=rp===0?'none':'';
  document.querySelectorAll('[data-preset]').forEach(b=>{
    const active=parseInt(b.dataset.preset)===cp;
    b.style.background=active?'var(--ink)':'';b.style.color=active?'var(--ivory)':'';b.style.borderColor=active?'var(--ink)':'';
  });
}
function setSplit(cp){updateSplit(100-cp);}
function issueVerdict(){
  const msg=document.getElementById('verdict-message').value.trim();
  if(!msg){showToast('Please write a verdict statement before issuing.');return;}
  const cp=100-parseInt(document.getElementById('split-slider').value);
  showToast('Verdict issued: Claimant '+cp+'% / Respondent '+(100-cp)+'%. Both parties notified.');
}
function showToast(msg){
  const t=document.createElement('div');t.className='toast success';
  t.innerHTML=`<span class="toast-icon">✓</span><div><div class="toast-title">Done</div><div class="toast-body">${msg}</div></div>`;
  (document.getElementById('toast-stack')||document.body).appendChild(t);setTimeout(()=>t.remove(),4000);
}
</script>
</body>
</html>