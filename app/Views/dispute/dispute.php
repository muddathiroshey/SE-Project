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
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="dashboard-client.html">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.html" class="btn btn-ghost btn-icon">🔔</a>
      <div class="dropdown">
        <button type="button" class="btn btn-ghost btn-icon" style="display:flex;align-items:center;gap:10px;" onclick="toggleProfileDD()">
          <div class="avatar avatar-sm">AT</div>
          <span style="font-size:.875rem;font-weight:700;">Dr. Rania K.</span>
          <span style="color:var(--ink-faint);">▾</span>
        </button>
        <div class="dropdown-menu hidden" id="profile-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Freelancer Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="expert-profile.html">My Profile</a>
          <a class="dropdown-item" href="escrow-wallet.html">Earnings &amp; Wallet</a>
          <a class="dropdown-item" href="#">Account Settings</a>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="login.html" style="color:var(--rust);">Sign Out</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- DISPUTE HERO -->
<div class="dispute-hero">
  <div class="container">
    <div class="breadcrumb">Projects <span>›</span> NX-2025-3801 <span>›</span> Dispute Center</div>
    <div class="flex justify-between items-start mt-8 mb-24">
      <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
          <h2>Annual Report — DE/EN Translation</h2>
          <span class="badge badge-danger badge-dot">Dispute Active</span>
        </div>
        <div style="display:flex;gap:12px;font-size:.8125rem;color:var(--ink-muted);">
          <span>Ref: DSP-NX-3801-2025</span>
          <span>·</span>
          <span>Opened: Apr 13, 2025</span>
          <span>·</span>
          <span>Arbiter assigned: Apr 14</span>
        </div>
      </div>
    </div>
    <div class="dispute-status-bar">
      <div class="dispute-status-step done">1 · Dispute Filed</div>
      <div class="dispute-status-step done">2 · Evidence Assembled</div>
      <div class="dispute-status-step done">3 · Arbiter Assigned</div>
      <div class="dispute-status-step active">4 · Under Review</div>
      <div class="dispute-status-step">5 · Verdict Issued</div>
      <div class="dispute-status-step">6 · Funds Released</div>
    </div>
  </div>
</div>

<div class="container" style="padding-top:32px;padding-bottom:48px;">
  <div class="dispute-body">

    <!-- LEFT COLUMN -->
    <div>

      <!-- SAFE-ROOM NOTICE -->
      <div class="safroom-notice">
        <span style="font-size:1.2rem;">🔒</span>
        <div>
          <strong>Safe-Room Communication Active</strong><br>
          All direct messaging between parties is suspended during dispute. Communication is restricted to this monitored channel, overseen by Arbiter M. Hassan. All messages in this thread are admissible as evidence.
        </div>
      </div>

      <!-- TABS -->
      <div class="tabs mb-24">
        <button class="tab-item active" onclick="switchTab(0)">Overview</button>
        <button class="tab-item" onclick="switchTab(1)">Arguments</button>
        <button class="tab-item" onclick="switchTab(2)">Safe-Room Chat</button>
        <button class="tab-item" onclick="switchTab(3)">Verdict</button>
      </div>

      <!-- OVERVIEW -->
      <div id="dt-0">
        <h3 class="mb-16">Dispute Summary</h3>
        <div class="card card-sm mb-16">
          <div class="form-row" style="margin-bottom:0;">
            <div>
              <div class="text-xs text-muted mb-4">Disputed Milestone</div>
              <div style="font-weight:700;">Phase 3 — Final Translation Delivery</div>
              <div class="text-xs text-muted font-mono mt-4">$1,400 in escrow</div>
            </div>
            <div>
              <div class="text-xs text-muted mb-4">Claimed By</div>
              <div style="font-weight:700;">Client (Amira Tawfik)</div>
              <div class="text-xs text-muted mt-4">Quality below contracted standard</div>
            </div>
          </div>
        </div>

        <div class="party-card claimant mb-0">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:10px;">Client — Claimant</div>
          <div class="flex items-center gap-12 mb-10">
            <div class="avatar avatar-sm">AT</div>
            <div><div style="font-weight:700;font-size:.875rem;">Amira Tawfik</div><div class="text-xs text-muted">FinCorp Egypt</div></div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);">The Phase 3 German translation contains 14 identified terminology errors and deviates from the established glossary agreed upon in the contract. These errors are material and affect the accuracy of the published report.</p>
        </div>
        <div class="party-card respondent">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#7A5C10;margin-bottom:10px;">Specialist — Respondent</div>
          <div class="flex items-center gap-12 mb-10">
            <div class="avatar avatar-sm">LB</div>
            <div><div style="font-weight:700;font-size:.875rem;">Lena Bergmann</div><div class="text-xs text-muted">Technical Translator · Berlin</div></div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);">The 14 flagged terms were translated in alignment with current DIN EN ISO standards, which supersede the glossary provided. I submitted a terminology rationale document in my delivery which appears to have been overlooked. I maintain the delivery meets the contracted standard.</p>
        </div>

        <hr class="divider">
        <h3 class="mb-16">Dispute Timeline</h3>
        <div class="timeline-item">
          <div class="timeline-dot" style="background:var(--rust);"></div>
          <div><div style="font-weight:700;font-size:.875rem;">Dispute opened by client</div><div class="text-xs text-muted">Apr 13, 14:22 · Quality challenge on Phase 3 delivery</div></div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot system"></div>
          <div><div style="font-weight:700;font-size:.875rem;">Evidence Package auto-assembled</div><div class="text-xs text-muted">Apr 13, 14:22 · 23 items collected automatically</div></div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot admin"></div>
          <div><div style="font-weight:700;font-size:.875rem;">Arbiter M. Hassan assigned</div><div class="text-xs text-muted">Apr 14, 09:05 · Niche: Technical Translation · Load: 2 active cases</div></div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div><div style="font-weight:700;font-size:.875rem;">Respondent statement submitted</div><div class="text-xs text-muted">Apr 14, 15:44 · Lena Bergmann</div></div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot admin"></div>
          <div><div style="font-weight:700;font-size:.875rem;">Arbiter reviewing evidence</div><div class="text-xs text-muted">Apr 15 (today) · Expected verdict within 72h</div></div>
        </div>
      </div>

      <!-- ARGUMENTS -->
      <div id="dt-1" class="hidden">
        <h3 class="mb-16">Party Arguments</h3>
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:10px;">Client — Claimant Arguments</div>
        <div class="argument-item claimant">
          <div class="argument-header">1. Terminology Deviations</div>
          The delivered translation contains 14 terms that deviate from the contractually agreed glossary. These are not stylistic choices — they represent factual inaccuracies in a financial document that will be submitted to regulators.
        </div>
        <div class="argument-item claimant">
          <div class="argument-header">2. Lack of Prior Communication</div>
          The specialist did not communicate her intention to deviate from the glossary prior to submission. No amendment was requested or approved. This constitutes a unilateral contract deviation.
        </div>
        <hr class="divider">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#7A5C10;margin-bottom:10px;">Specialist — Respondent Arguments</div>
        <div class="argument-item respondent">
          <div class="argument-header">1. ISO Standard Compliance</div>
          The 14 terms were translated per DIN EN ISO 704:2022 standards, which take precedence over client-specific glossaries for regulated financial translations under German law. A rationale document was included in the delivery.
        </div>
        <div class="argument-item respondent">
          <div class="argument-header">2. Rationale Document Was Delivered</div>
          I proactively included a 4-page Terminology Rationale document explaining each deviation. The client's claim that this was "overlooked" suggests the delivery was not fully reviewed before the dispute was filed.
        </div>
        <div class="argument-item respondent">
          <div class="argument-header">3. QA Checklist Completed</div>
          All 4 platform QA checklist items were marked complete prior to submission. I am willing to offer 1 free revision addressing any terms the client wishes to revert, as a goodwill gesture.
        </div>
        <hr class="divider">
        <div class="form-group">
          <label class="form-label">Submit Additional Argument</label>
          <textarea id="argument-textarea" class="form-control" rows="4" placeholder="Add a formal argument to the dispute record…"></textarea>
          <div class="text-xs text-muted mt-4">Arguments submitted here are logged to the dispute record and visible to the arbiter.</div>
        </div>
        <button class="btn btn-primary btn-sm" type="button" onclick="submitArgument()">Submit Argument</button>
      </div>

      <!-- SAFE-ROOM CHAT -->
      <div id="dt-2" class="hidden">
        <div style="background:#FBE9E7;border:1.5px solid var(--rust);border-radius:var(--radius-md);padding:14px 18px;margin-bottom:20px;font-size:.8125rem;color:var(--rust);">
          🔒 <strong>Monitored Safe-Room.</strong> All messages are recorded, reviewed by Arbiter M. Hassan, and included in the evidence package. Standard messaging is suspended.
        </div>
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:20px;height:calc(110vh - 170px);max-height:calc(110vh - 170px);display:flex;flex-direction:column;gap:12px;overflow:hidden;">
          <div class="chat-sender-info" style="text-align:center;margin-bottom:8px;">SAFE-ROOM OPENED · APR 13, 14:22</div>
          <div id="safe-room-messages" style="flex:1;min-height:0;overflow-y:auto;display:flex;flex-direction:column;gap:12px;padding-right:8px;">
            <div class="chat-bubble saferoom" style="max-width:100%;">⚖️ Arbiter M. Hassan: Both parties — please submit your full written arguments via the Arguments tab. Use this channel only for direct factual clarifications. I will not respond to persuasive statements here.</div>
            <div class="chat-sender-info left">Lena Bergmann · 19:08</div>
            <div class="chat-bubble in" style="max-width:75%;">Lena Bergmann: I would like to confirm — was the Terminology Rationale document reviewed before the dispute was filed?</div>
            <div class="chat-sender-info right">You · 19:08</div>
            <div class="chat-bubble out" style="max-width:75%;align-self:flex-end;">We received the delivery files but the rationale document was in a sub-folder that was not immediately visible. We have since reviewed it.</div>
            <div class="chat-bubble saferoom" style="max-width:100%;">⚖️ Arbiter M. Hassan: Noted. This is a relevant factual clarification and has been added to the evidence record.</div>
          </div>
          <div id="chat-input-panel">
          <div id="chat-attachment-preview" class="chat-attachment-preview hidden"></div>
          <div class="chat-input-group" style="display:flex;gap:10px;align-items:flex-end;margin-top:12px;">
            <textarea class="chat-textarea" rows="2" placeholder="Type a Safe-Room message…" onkeypress="if(event.key==='Enter' && !event.shiftKey){event.preventDefault();sendSafeRoomMessage();}"></textarea>
            <button class="chat-btn-circle send" title="Send message" onclick="sendSafeRoomMessage()">↑</button>
            <button class="chat-btn-circle" title="Attach file" onclick="document.getElementById('file-input-hidden').click()">📎</button>
            <input type="file" id="file-input-hidden" onchange="handleFileAttachment(event)">
          </div>
        </div>
        </div>
      </div>

      <!-- VERDICT -->
      <div id="dt-3" class="hidden">
        <div class="verdict-card">
          <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:16px;">⚖️ Arbiter Verdict — Pending</div>
          <h3 style="margin-bottom:12px;">Verdict not yet issued</h3>
          <p style="font-size:.875rem;color:var(--ink-mid);margin-bottom:20px;">The arbiter is currently reviewing all evidence and arguments. A verdict is expected within <strong>72 hours</strong> of dispute filing (by Apr 16, 14:22 GMT+2).</p>
          <div class="progress-bar mb-8"><div class="progress-fill" style="width:55%;"></div></div>
          <div class="text-xs text-muted">Estimated 55% of review complete based on typical case duration.</div>
          <hr class="divider">
          <div style="font-size:.8125rem;color:var(--ink-muted);">
            <strong>Once the verdict is issued, the arbiter may:</strong><br>
            · Release 100% to the specialist (delivery accepted)<br>
            · Release 100% to the client (delivery rejected, refund)<br>
            · Split the escrowed amount (partial acceptance)<br>
            · Order a mandatory free revision
          </div>
        </div>
        <hr class="divider">
        <h4 class="mb-12">Sample Verdict Format</h4>
        <div class="verdict-card" style="border-color:var(--border);">
          <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--sage);margin-bottom:12px;">⚖️ Example Verdict Structure</div>
          <div class="verdict-split mb-12">
            <div class="verdict-split-a" style="width:30%;">Client 30%</div>
            <div class="verdict-split-b" style="width:70%;">Specialist 70%</div>
          </div>
          <div style="font-size:.875rem;color:var(--ink-mid);">The arbiter finds that the specialist's use of ISO-standard terminology was technically defensible but was not communicated in advance per the contract's amendment clause. A 70/30 split reflects the quality of work delivered versus the procedural contract breach.</div>
        </div>
      </div>

    </div>

    <!-- RIGHT SIDEBAR -->
    <div>

      <div class="arbitrator-card">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Assigned Arbiter</div>
        <div class="flex items-center gap-12 mb-12">
          <div class="avatar avatar-md" style="background:var(--ink);color:var(--ivory);">MH</div>
          <div>
            <div style="font-weight:700;font-size:.875rem;">Mohammed Hassan</div>
            <div class="text-xs text-muted">Dispute Mediator · Technical Translation</div>
            <div class="text-xs text-muted mt-2">Load: 2 active cases</div>
          </div>
        </div>
        <div style="font-size:.75rem;color:var(--ink-muted);">
          <div class="flex justify-between mb-4"><span>Cases resolved</span><span class="font-mono">214</span></div>
          <div class="flex justify-between mb-4"><span>Avg. verdict time</span><span class="font-mono">58h</span></div>
          <div class="flex justify-between"><span>Appeal rate</span><span class="font-mono">4.2%</span></div>
        </div>
      </div>

      <div class="card card-sm mb-16">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Escrowed Funds</div>
        <div style="font-family:var(--font-display);font-size:2rem;font-weight:300;margin-bottom:4px;">$1,400</div>
        <div class="text-xs text-muted mb-12">Phase 3 escrow — frozen during dispute</div>
        <div class="progress-bar"><div class="progress-fill danger" style="width:100%;background:var(--rust);"></div></div>
        <div class="text-xs text-muted mt-4">Funds will not be released or refunded until verdict is issued.</div>
      </div>

      <div class="card card-sm mb-16">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">User Sanction Status</div>
        <div style="font-size:.875rem;font-weight:700;margin-bottom:6px;">Lena Bergmann</div>
        <span class="sanction-warn">⚠ Warning Issued</span>
        <div class="text-xs text-muted mt-8">First procedural warning for failure to communicate contract deviation in advance. No access restrictions applied.</div>
        <hr class="divider">
        <div style="font-size:.875rem;font-weight:700;margin-bottom:6px;">Sanction Tiers</div>
        <div style="font-size:.75rem;color:var(--ink-muted);line-height:1.9;">
          Tier 1: Warning (current)<br>
          Tier 2: Limited Access<br>
          Tier 3: Permanent Ban
        </div>
      </div>

      <div class="card card-sm">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Verdict Timeline</div>
        <div style="font-size:.8125rem;color:var(--ink-mid);">
          <div class="flex justify-between mb-8"><span>Filed</span><span class="font-mono">Apr 13, 14:22</span></div>
          <div class="flex justify-between mb-8"><span>Arbiter assigned</span><span class="font-mono">Apr 14, 09:05</span></div>
          <div class="flex justify-between mb-8"><span>Expected verdict</span><span class="font-mono">Apr 16, 14:22</span></div>
          <div class="flex justify-between"><span>Appeal window</span><span class="font-mono">48h post-verdict</span></div>
        </div>
      </div>

    </div>
  </div>
</div>

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
        <textarea class="form-control" rows="5" placeholder="Provide a clear, factual basis for your appeal. Appeals based on disagreement with the verdict alone will not be accepted…"></textarea>
      </div>
      <div class="verify-band">
        <span>ℹ️</span>
        <div style="font-size:.8125rem;">If your appeal is rejected, a $75 appeal processing fee will be deducted from your next payout. Accepted appeals incur no fee.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('appeal-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('appeal-modal').classList.add('hidden')">Submit Appeal</button>
    </div>
  </div>
</div>

<!-- TOAST STACK -->
<div class="toast-stack" id="toast-stack"></div>

<script>
function switchTab(i) {
  document.querySelectorAll('.tabs .tab-item').forEach((t,j) => t.classList.toggle('active', i===j));
  for(let j=0;j<5;j++) { const el = document.getElementById('dt-'+j); if(el) el.classList.toggle('hidden', i!==j); }
}

let pendingAttachment = null;

function sendSafeRoomMessage() {
  const textarea = document.querySelector('.chat-textarea');
  const container = document.getElementById('safe-room-messages');
  const attachmentPreview = document.getElementById('chat-attachment-preview');
  
  if (!textarea || !container || (!textarea.value.trim() && !pendingAttachment)) return;
  
  const message = textarea.value.trim();
  const now = new Date();
  const time = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
  
  // Create wrapper for info + bubble
  const messageDiv = document.createElement('div');
  messageDiv.style.display = 'flex';
  messageDiv.style.flexDirection = 'column';
  messageDiv.style.alignItems = 'flex-end';
  messageDiv.style.gap = '6px';
  messageDiv.style.alignSelf = 'flex-end';
  
  const infoDiv = document.createElement('div');
  infoDiv.className = 'chat-sender-info';
  infoDiv.style.textAlign = 'right';
  infoDiv.textContent = `You · ${time}`;
  
  const bubbleDiv = document.createElement('div');
  bubbleDiv.className = 'chat-bubble out';
  bubbleDiv.style.alignSelf = 'flex-end';
  
  if (message) {
    const textNode = document.createElement('div');
    textNode.textContent = message;
    bubbleDiv.appendChild(textNode);
  }
  
  if (pendingAttachment) {
    const attachmentNode = document.createElement('div');
    attachmentNode.className = 'chat-attachment-item';
    attachmentNode.textContent = `📎 ${pendingAttachment.name}`;
    bubbleDiv.appendChild(attachmentNode);
  }
  
  messageDiv.appendChild(infoDiv);
  messageDiv.appendChild(bubbleDiv);
  container.appendChild(messageDiv);
  
  // Auto-scroll to bottom
  container.scrollTop = container.scrollHeight;
  
  // Clear textarea and attachment preview
  textarea.value = '';
  pendingAttachment = null;
  if (attachmentPreview) {
    attachmentPreview.classList.add('hidden');
    attachmentPreview.innerHTML = '';
  }
  textarea.focus();
}

function handleFileAttachment(event) {
  const files = event.target.files;
  if (!files || !files.length) return;
  
  const file = files[0];
  pendingAttachment = {
    name: file.name,
    size: (file.size / 1024).toFixed(1) + ' KB',
    type: file.type
  };
  
  const attachmentPreview = document.getElementById('chat-attachment-preview');
  if (attachmentPreview) {
    attachmentPreview.classList.remove('hidden');
    attachmentPreview.innerHTML = `
      <div class="chat-attachment-info">
        <div class="chat-attachment-name">${pendingAttachment.name}</div>
        <div class="chat-attachment-meta">${pendingAttachment.size}</div>
      </div>
      <button type="button" class="chat-attachment-remove" onclick="removeChatAttachment()">✕</button>
    `;
  }
  
  showToast(`File "${pendingAttachment.name}" attached to message.`);
  event.target.value = '';
}

function removeChatAttachment() {
  pendingAttachment = null;
  const attachmentPreview = document.getElementById('chat-attachment-preview');
  if (attachmentPreview) {
    attachmentPreview.classList.add('hidden');
    attachmentPreview.innerHTML = '';
  }
}

function submitArgument() {
  const textarea = document.getElementById('argument-textarea');
  if (!textarea) return;

  const value = textarea.value.trim();
  if (!value) {
    showToast('Please enter an argument before submitting.');
    return;
  }

  textarea.value = '';
  showToast('Your argument has been submitted for arbiter review.');
}

function showToast(msg) {
  const toast = document.createElement('div');
  toast.className = 'toast success';
  toast.innerHTML = `<span class="toast-icon">✓</span><div><div class="toast-title">Done</div><div class="toast-body">${msg}</div></div>`;
  
  const stack = document.getElementById('toast-stack') || document.body;
  stack.appendChild(toast);
  
  setTimeout(() => toast.remove(), 4000);
}

function toggleProfileDD() {
  const menu = document.getElementById('profile-dd');
  if (!menu) return;
  menu.classList.toggle('hidden');
}

document.addEventListener('click', e => {
  const profileMenu = document.getElementById('profile-dd');
  if (!profileMenu) return;
  if (!e.target.closest('.dropdown')) {
    profileMenu.classList.add('hidden');
  }
});
</script>
</body>
</html>
