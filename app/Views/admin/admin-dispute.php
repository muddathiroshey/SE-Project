<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dispute Center — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/admin-dispute.css">

</head>
<body>

<?php require __DIR__ . '/../partials/topnav.php'; ?>

<div class="admin-shell">

  <main class="admin-main">

<!-- DISPUTE HERO -->
<div class="dispute-hero">
  <div class="container">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
      <a href="admin-disputes.html" class="btn btn-ghost btn-sm">← Back to Disputes</a>
      <div class="breadcrumb">Projects <span>›</span> NX-2025-3801 <span>›</span> Dispute Center</div>
    </div>
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

      <!-- EVIDENCE PACKAGE -->
      <div id="dt-1" class="hidden">
        <div class="flex justify-between items-center mb-16">
          <h3>Evidence Package</h3>
          <span class="badge badge-default">23 items · Auto-assembled Apr 13</span>
        </div>
        <div class="verify-band mb-16">
          <span>🤖</span>
          <div style="font-size:.8125rem;">All evidence below was automatically assembled by the Nexus system at the time the dispute was filed. No items have been added, removed, or altered. Audit hash: <span class="font-mono">sha256:3f8a9c...</span></div>
        </div>
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:10px;">Contract Documents</div>
        <div class="evidence-item">
          <div class="evidence-icon">📋</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Signed Contract — NX-2025-3801</div><div class="evidence-meta">Executed Apr 1, 2025 · Both parties · 14 pages</div><div class="evidence-source">Source: Contract Engine</div></div>
          <button class="btn btn-ghost btn-sm">View</button>
        </div>
        <div class="evidence-item">
          <div class="evidence-icon">📄</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Approved Terminology Glossary</div><div class="evidence-meta">Attached to contract · 42 terms · German/English</div><div class="evidence-source">Source: Contract Attachment</div></div>
          <button class="btn btn-ghost btn-sm">View</button>
        </div>
        <div class="evidence-item">
          <div class="evidence-icon">🔏</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">NDA — Signed Copy</div><div class="evidence-meta">Executed Apr 1, 2025</div><div class="evidence-source">Source: NDA Generator</div></div>
          <button class="btn btn-ghost btn-sm">View</button>
        </div>
        <hr class="divider">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:10px;">Deliverables</div>
        <div class="evidence-item">
          <div class="evidence-icon">📁</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Phase 3 Delivery — Final Translation Files (DE/EN)</div><div class="evidence-meta">Submitted Apr 12, 18:30 · 3 files · 2.1 MB total</div><div class="evidence-source">Source: Deliverable Submission</div></div>
          <button class="btn btn-ghost btn-sm">View</button>
        </div>
        <div class="evidence-item">
          <div class="evidence-icon">📝</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Terminology Rationale Document</div><div class="evidence-meta">Included in Phase 3 delivery · 4 pages</div><div class="evidence-source">Source: Deliverable Submission</div></div>
          <button class="btn btn-ghost btn-sm">View</button>
        </div>
        <hr class="divider">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:10px;">Communication Log (18 messages archived)</div>
        <div class="evidence-item">
          <div class="evidence-icon">💬</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Full Message Thread — NX-2025-3801</div><div class="evidence-meta">Apr 1 – Apr 13 · 18 messages · Encrypted archive</div><div class="evidence-source">Source: Communication Archiver</div></div>
          <button class="btn btn-ghost btn-sm">View Thread</button>
        </div>
        <div class="evidence-item">
          <div class="evidence-icon">📅</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">System Audit Trail</div><div class="evidence-meta">41 logged events · All status changes, uploads, approvals</div><div class="evidence-source">Source: Audit Log Engine</div></div>
          <button class="btn btn-ghost btn-sm">Download</button>
        </div>
      </div>

      <!-- ARGUMENTS -->
      <div id="dt-2" class="hidden">
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
      </div>

      <!-- SAFE-ROOM CHAT -->
      <div id="dt-3" class="hidden">
        <div style="background:#FBE9E7;border:1.5px solid var(--rust);border-radius:var(--radius-md);padding:14px 18px;margin-bottom:20px;font-size:.8125rem;color:var(--rust);">
          🔒 <strong>Monitored Safe-Room.</strong> All messages are recorded, reviewed by Arbiter M. Hassan, and included in the evidence package. Standard messaging is suspended.
        </div>
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:20px;height:600px;display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
          <div style="text-align:center;font-size:.75rem;font-family:var(--font-mono);color:var(--ink-faint);margin-bottom:8px;">SAFE-ROOM OPENED · APR 13, 14:22</div>
          <div id="safe-room-messages" style="flex:1;min-height:0;overflow-y:auto;display:flex;flex-direction:column;gap:12px;padding-right:8px;">
            <div class="chat-bubble saferoom" style="max-width:100%;">⚖️ Arbiter M. Hassan: Both parties — please submit your full written arguments via the Arguments tab. Use this channel only for direct factual clarifications. I will not respond to persuasive statements here.</div>
            <div class="chat-sender-info left">Respondent · 19:08</div>
            <div class="chat-bubble in" style="max-width:75%;">Respondent: I would like to confirm — was the Terminology Rationale document reviewed before the dispute was filed?</div>
            <div class="chat-sender-info right">Claimant · 19:08</div>
            <div class="chat-bubble out" style="max-width:75%;align-self:flex-end;">Claimant: We received the delivery files but the rationale document was in a sub-folder that was not immediately visible. We have since reviewed it.</div>
            <div class="chat-bubble saferoom" style="max-width:100%;">⚖️ Arbiter M. Hassan: Noted. This is a relevant factual clarification and has been added to the evidence record.</div>
          </div>
        </div>
        <div class="chat-input-group" style="display:flex;gap:10px;align-items:flex-end;margin-top:12px;">
          <textarea class="chat-textarea" rows="2" placeholder="Type a Safe-Room message…" onkeypress="if(event.key==='Enter' && !event.shiftKey){event.preventDefault();sendSafeRoomMessage();}"></textarea>
          <button class="chat-btn-circle send" title="Send message" onclick="sendSafeRoomMessage()">↑</button>
        </div>
      </div>

      <!-- VERDICT -->
      <div id="dt-4" class="hidden">
        <div class="verdict-card">
          <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:16px;">⚖️ Issue Verdict</div>
          <h3 style="margin-bottom:6px;">Determine Fund Allocation</h3>
          <p style="font-size:.8125rem;color:var(--ink-muted);margin-bottom:24px;">Set the percentage each party receives from the escrowed <strong>$1,400</strong>. This decision is final and will be recorded in the dispute file.</p>

          <!-- SPLIT SLIDER -->
          <div style="margin-bottom:24px;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:10px;">
              <div>
                <span style="font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);">Claimant</span>
                <span style="font-family:var(--font-mono);font-size:1.1rem;font-weight:700;margin-left:6px;" id="claimant-pct">50%</span>
                <span style="font-family:var(--font-mono);font-size:.8125rem;color:var(--ink-muted);margin-left:4px;" id="claimant-amount">($700)</span>
              </div>
              <div style="text-align:right;">
                <span style="font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#9A6800;">Respondent</span>
                <span style="font-family:var(--font-mono);font-size:1.1rem;font-weight:700;margin-left:6px;" id="respondent-pct">50%</span>
                <span style="font-family:var(--font-mono);font-size:.8125rem;color:var(--ink-muted);margin-left:4px;" id="respondent-amount">($700)</span>
              </div>
            </div>

            <input type="range" id="split-slider" min="0" max="100" value="50"
              style="width:100%;cursor:pointer;accent-color:var(--ink);height:6px;"
              oninput="updateSplit(this.value)">

            <div style="display:flex;justify-content:space-between;margin-top:6px;">
              <span class="text-xs text-muted">0% → Full refund to Claimant</span>
              <span class="text-xs text-muted">100% → Full payout to Respondent</span>
            </div>
          </div>

          <!-- LIVE PREVIEW BAR -->
          <div class="verdict-split mb-20" id="verdict-preview-bar">
            <div class="verdict-split-a" id="preview-claimant" style="width:50%;">Claimant 50%</div>
            <div class="verdict-split-b" id="preview-respondent" style="width:50%;">Respondent 50%</div>
          </div>

          <!-- QUICK PRESETS -->
          <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:24px;">
            <span class="text-xs text-muted" style="align-self:center;margin-right:4px;">Quick:</span>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="100" onclick="setSplit(100)">100% Claimant</button>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="70" onclick="setSplit(70)">70 / 30</button>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="50" onclick="setSplit(50)">50 / 50</button>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="30" onclick="setSplit(30)">30 / 70</button>
            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;" data-preset="0" onclick="setSplit(0)">100% Respondent</button>
          </div>

          <hr class="divider">

          <!-- VERDICT MESSAGE -->
          <div class="form-group" style="margin-top:20px;">
            <label class="form-label">Verdict Statement</label>
            <textarea class="form-control" id="verdict-message" rows="5" placeholder="Write your verdict reasoning. Explain the basis for the fund split, reference evidence or arguments, and state any additional orders (e.g. mandatory revision, sanctions)…"></textarea>
            <div class="text-xs text-muted mt-4">This statement will be visible to both parties, included in the dispute record, and cannot be edited after submission.</div>
          </div>

          <!-- SUBMIT -->
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

        <!-- CLAIMANT -->
        <div style="border-left:3px solid var(--rust);padding-left:12px;margin-bottom:16px;">
          <div style="font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:6px;">Claimant</div>
          <div class="flex items-center gap-10 mb-6">
            <div class="avatar avatar-sm" style="flex-shrink:0;">AT</div>
            <div>
              <div style="font-weight:700;font-size:.875rem;">Amira Talaat</div>
              <div class="text-xs text-muted">FinCorp Egypt · Client</div>
            </div>
          </div>
          <div style="font-size:.75rem;color:var(--ink-muted);">
            <div class="flex justify-between mb-2"><span>Contracts completed</span><span class="font-mono">18</span></div>
            <div class="flex justify-between"><span>Disputes filed</span><span class="font-mono">1</span></div>
          </div>
        </div>

        <hr style="border:none;border-top:1px solid var(--border);margin-bottom:16px;">

        <!-- RESPONDENT -->
        <div style="border-left:3px solid var(--gold);padding-left:12px;">
          <div style="font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#9A6800;margin-bottom:6px;">Respondent</div>
          <div class="flex items-center gap-10 mb-6">
            <div class="avatar avatar-sm" style="flex-shrink:0;">LB</div>
            <div>
              <div style="font-weight:700;font-size:.875rem;">Lena Bergmann</div>
              <div class="text-xs text-muted">Technical Translation · Specialist</div>
            </div>
          </div>
          <div style="font-size:.75rem;color:var(--ink-muted);">
            <div class="flex justify-between mb-2"><span>Contracts completed</span><span class="font-mono">42</span></div>
            <div class="flex justify-between"><span>Disputes received</span><span class="font-mono">1</span></div>
          </div>
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
  </main>
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

function sendSafeRoomMessage() {
  const textarea = document.querySelector('.chat-textarea');
  const container = document.getElementById('safe-room-messages');
  
  if (!textarea || !container || !textarea.value.trim()) return;
  
  const message = textarea.value.trim();
  
  const bubbleDiv = document.createElement('div');
  bubbleDiv.className = 'chat-bubble saferoom';
  bubbleDiv.style.maxWidth = '100%';
  bubbleDiv.textContent = '⚖️ Arbiter M. Hassan: ' + message;
  
  container.appendChild(bubbleDiv);
  
  container.scrollTop = container.scrollHeight;
  textarea.value = '';
  textarea.focus();
}

const ESCROW_TOTAL = 1400;

function updateSplit(val) {
  const claimantPct = 100 - parseInt(val);
  const respondentPct = parseInt(val);
  const claimantAmt = Math.round(ESCROW_TOTAL * claimantPct / 100);
  const respondentAmt = ESCROW_TOTAL - claimantAmt;

  // Update percentage labels
  document.getElementById('claimant-pct').textContent = claimantPct + '%';
  document.getElementById('respondent-pct').textContent = respondentPct + '%';
  document.getElementById('claimant-amount').textContent = '($' + claimantAmt.toLocaleString() + ')';
  document.getElementById('respondent-amount').textContent = '($' + respondentAmt.toLocaleString() + ')';

  // Update slider position
  document.getElementById('split-slider').value = val;

  // Update preview bar
  const previewA = document.getElementById('preview-claimant');
  const previewB = document.getElementById('preview-respondent');
  previewA.style.width = Math.max(claimantPct, 0) + '%';
  previewB.style.width = Math.max(respondentPct, 0) + '%';
  previewA.textContent = claimantPct > 8 ? 'Claimant ' + claimantPct + '%' : '';
  previewB.textContent = respondentPct > 8 ? 'Respondent ' + respondentPct + '%' : '';

  if (claimantPct === 0) { previewA.style.display = 'none'; previewB.style.display = ''; }
  else if (respondentPct === 0) { previewB.style.display = 'none'; previewA.style.display = ''; }
  else { previewA.style.display = ''; previewB.style.display = ''; }

  // Highlight active preset button
  const presets = document.querySelectorAll('[data-preset]');
  presets.forEach(btn => {
    if (parseInt(btn.dataset.preset) === claimantPct) {
      btn.style.background = 'var(--ink)';
      btn.style.color = 'var(--ivory)';
      btn.style.borderColor = 'var(--ink)';
    } else {
      btn.style.background = '';
      btn.style.color = '';
      btn.style.borderColor = '';
    }
  });
}

function setSplit(claimantPct) {
  updateSplit(100 - claimantPct);
}

function issueVerdict() {
  const msg = document.getElementById('verdict-message').value.trim();
  if (!msg) {
    showToast('Please write a verdict statement before issuing.');
    return;
  }
  const slider = document.getElementById('split-slider');
  const claimantPct = 100 - parseInt(slider.value);
  const respondentPct = parseInt(slider.value);
  showToast('Verdict issued: Claimant ' + claimantPct + '% / Respondent ' + respondentPct + '%. Both parties have been notified.');
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
