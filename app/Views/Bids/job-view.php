<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/specialist/job-view.php
    Template: Posted Job — Specialist View & Bid Placement
    Role:     specialist (authenticated)
    Route:    /jobs/{job_slug}   |   /jobs/{job_id}
    ============================================================
    PHP Data contract (passed from JobController::show()):
      $job          — full job record (all wizard fields)
      $client       — posting client + org
      $milestones   — ordered array of milestone rows
      $ndaRequired  — bool
      $bidCount     — int, total proposals received so far
      $myBid        — null | existing proposal for this specialist
      $canBid       — bool (verified, not already hired, etc.)
      $blockReason  — null | string explaining why canBid=false
      $similarJobs  — array(3) of related postings
      $specialist   — authenticated user record
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- PHP: <title><?= htmlspecialchars($job['title']) ?> — Nexus</title> -->
<title>MENA Expansion — Cross-Border Contract Review · Nexus</title>
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/job-view.css">
</head>
<body>

<!-- ══════════════════ TOPNAV
     PHP: include 'partials/topnav.php';
     Pass: ['role'=>'specialist','user'=>$specialist]
-->
<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-links">
      <!-- PHP: back href determined by HTTP_REFERER or default -->
      <a href="browse-experts.html">← Back to Jobs</a>
      <a href="dashboard-freelancer.html">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔<span class="notif-count" style="position:absolute;top:2px;right:2px;">7</span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">DR</div></div>
          <!-- PHP: <?= htmlspecialchars($specialist['display_name']) ?> -->
          <span style="font-size:.875rem;font-weight:700;">Dr. Rania K.</span>
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

<!-- ══════════════════ JOB HERO ══════════════════ -->
<div class="job-hero">
  <div class="container">
    <div class="job-hero-inner">

      <!-- NICHE ICON -->
      <!-- PHP: $nicheIcons = ['Legal Consulting'=>'⚖️','Data Science'=>'🧠','Translation'=>'🌐',...] -->
      <div class="job-niche-icon">⚖️</div>

      <div style="flex:1;">

        <!-- BREADCRUMB -->
        <div style="font-size:.75rem;font-family:var(--font-mono);color:var(--ink-muted);margin-bottom:8px;">
          <!-- PHP: htmlspecialchars($job['niche']) ?> · <?= htmlspecialchars($job['engagement_type']) -->
          Legal Consulting &nbsp;·&nbsp; Contract Review &amp; Advisory
        </div>

        <!-- TITLE -->
        <!-- PHP: <h1 ...><?= htmlspecialchars($job['title']) ?></h1> -->
        <h1 style="font-family:var(--font-display);font-size:1.8rem;font-weight:500;margin-bottom:10px;line-height:1.2;">
          MENA Expansion — Cross-Border Contract Review
        </h1>

        <!-- META ROW -->
        <div class="job-meta-row">
          <!-- PHP: foreach niche-specific fields as key=>val -->
          <div class="job-meta-item">
            <span>📍</span>
            <!-- PHP: implode(' · ', $job['jurisdictions']) -->
            Egypt
          </div>
          <div class="job-meta-item">
            <span>⚖️</span>
            <!-- PHP: $job['governing_law'] -->
            Cross-border MENA
          </div>
        </div>

        <!-- BADGES -->
        <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
          <!-- PHP: if($job['visibility']==='public'): -->
          <span class="badge badge-default">🌐 Public Listing</span>
          <!-- PHP: if($job['nda_required']): -->
          <span class="badge badge-pending">🔏 NDA Required on Shortlist</span>
          <!-- PHP: if($job['interview_required']): -->
          <span class="badge badge-default">🎙 Technical Interview</span>
          <!-- PHP: <span class="badge badge-default font-mono" style="font-size:.625rem;">Ref: <?= $job['ref'] ?></span> -->
          <span class="badge badge-default font-mono" style="font-size:.625rem;">Ref: NX-2025-4821</span>
        </div>

      </div>

      <!-- CLIENT MINI (top right) -->
      <div style="flex-shrink:0;text-align:right;min-width:160px;">
        <div style="font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);font-weight:700;margin-bottom:8px;font-family:var(--font-body);">Posted by</div>
        <!-- PHP: link to /client/{$client['slug']} -->
        <a href="client-profile-public.html" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;color:var(--ink);">
          <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:var(--ink);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:.875rem;font-weight:600;color:var(--gold);">FC</div>
          <div style="text-align:left;">
            <!-- PHP: htmlspecialchars($client['org']['name']) -->
            <div style="font-weight:700;font-size:.875rem;">FinCorp Egypt</div>
            <div class="text-xs text-muted">Financial Services · Cairo</div>
          </div>
        </a>
        <div style="margin-top:10px;">
          <span class="badge badge-verified badge-dot" style="font-size:.625rem;">Verified</span>
        </div>
        <!-- PHP: date('M j, Y', $job['posted_at']) -->
        <div class="text-xs text-muted font-mono mt-8">Posted Apr 10, 2025</div>
      </div>

    </div><!-- end hero-inner -->

    <!-- STATS BAR -->
    <div class="job-stats-bar">
      <!-- PHP: each driven by $job fields -->
      <div class="job-stat">
        <!-- PHP: '$'.number_format($job['total_budget']) -->
        <div class="val">$12,000</div>
        <div class="lbl">Total Budget</div>
      </div>
      <div class="job-stat">
        <!-- PHP: count($job['milestones']).' phases' -->
        <div class="val">3</div>
        <div class="lbl">Milestones</div>
      </div>
      <div class="job-stat">
        <!-- PHP: $job['total_duration_days'].' days est.' -->
        <div class="val">49d</div>
        <div class="lbl">Duration</div>
      </div>
      <div class="job-stat">
        <!-- PHP: '$'.number_format($job['first_escrow']).' locked at signing' -->
        <div class="val">$3,000</div>
        <div class="lbl">First Escrow</div>
      </div>
      <div class="job-stat">
        <!-- PHP: $bidCount -->
        <div class="val">7</div>
        <div class="lbl">Proposals So Far</div>
      </div>
    </div>

  </div>
</div><!-- end job-hero -->

<!-- ══════════════════ BODY ══════════════════ -->
<div class="container">
  <div class="job-body">

    <!-- ─── LEFT: JOB DETAIL ─── -->
    <div>

      <!-- TABS -->
      <div class="tabs mt-24 mb-28">
        <button class="tab-item active" onclick="switchTab(0)">Project Brief</button>
        <button class="tab-item" onclick="switchTab(1)">Milestones</button>
        <button class="tab-item" onclick="switchTab(2)">NDA &amp; Privacy</button>
        <button class="tab-item" onclick="switchTab(3)">Client Pro  file</button>
      </div>

      <!-- ══ TAB 0: PROJECT BRIEF ══ -->
      <div id="tab-0">

        <div class="job-section">
          <div class="job-section-title">Project Brief</div>
          <!-- PHP: nl2br(htmlspecialchars($job['brief'])) -->
          <p style="margin-bottom:12px;">We are a mid-size Egyptian technology company preparing to expand into UAE and KSA markets. We require a comprehensive review of our standard SaaS agreements, distributor contracts, and employment terms across all three jurisdictions, with specific attention to data residency requirements and GDPR cross-border transfer provisions.</p>
          <p style="margin-bottom:12px;">The engagement must deliver actionable legal recommendations, a revised contract suite, and a jurisdiction-specific risk register. All deliverables must be production-ready — not advisory memos.</p>
          <p>We have previously engaged legal consultants for similar work and expect a structured, milestone-driven approach with clear sign-off gates at each phase.</p>
        </div>

        <div class="job-section">
          <div class="job-section-title">Niche-Specific Details</div>
          <!-- PHP: foreach($job['niche_fields'] as $field): -->
          <div class="niche-field-row">
            <div class="niche-field-label">Engagement Type</div>
            <div class="niche-field-value">Contract Review</div>
          </div>
          <div class="niche-field-row">
            <div class="niche-field-label">Jurisdictions</div>
            <div class="niche-field-value">Cross-border MENA</div>
          </div>
          <div class="niche-field-row">
            <div class="niche-field-label">Governing Law</div>
            <div class="niche-field-value">Multiple / To be determined per jurisdiction</div>
          </div>
          <div class="niche-field-row">
            <div class="niche-field-label">Required Bar Admissions</div>
            <div class="niche-field-value">Cairo Bar and/or DIFC Courts experience required</div>
          </div>
          <div class="niche-field-row">
            <div class="niche-field-label">Document Languages</div>
            <div class="niche-field-value">Arabic · English · French</div>
          </div>
          <div class="niche-field-row">
            <div class="niche-field-label">Industry Context</div>
            <div class="niche-field-value">SaaS / Technology · Financial Services-adjacent</div>
          </div>
        </div>

        <div class="job-section">
          <div class="job-section-title">Full Project Requirements</div>
          <!-- PHP: nl2br(htmlspecialchars($job['full_requirements'])) -->
          <p style="margin-bottom:10px;">The successful specialist will be required to:</p>
          <ul style="padding-left:20px;color:var(--ink-mid);font-size:.9375rem;line-height:2;">
            <li>Conduct a gap analysis of our current SaaS agreements against Egyptian, UAE, and KSA commercial law requirements</li>
            <li>Review and redraft distribution and reseller agreements for cross-border use</li>
            <li>Audit employment contract templates for compliance in each jurisdiction</li>
            <li>Produce a GDPR cross-border transfer risk register with recommended Standard Contractual Clauses where applicable</li>
            <li>Deliver all revised contracts in both Arabic and English</li>
            <li>Attend up to 3 video calls for stakeholder review</li>
          </ul>
        </div>

        <div class="job-section">
          <div class="job-section-title">Ideal Specialist Profile</div>
          <!-- PHP: nl2br(htmlspecialchars($job['ideal_candidate'])) -->
          <p>We are looking for a qualified commercial lawyer with a minimum of 7 years of cross-border practice experience in the MENA region. Prior experience advising technology or SaaS companies on cross-border market entry is strongly preferred. Fluency in Arabic and English is mandatory; French is advantageous.</p>
        </div>

        <div class="job-section">
          <div class="job-section-title">Timeline &amp; Delivery</div>
          <div style="display:flex;gap:24px;flex-wrap:wrap;">
            <div>
              <div class="text-xs text-muted mb-4">Estimated Duration</div>
              <!-- PHP: $job['timeline_label'] -->
              <div style="font-weight:700;">49 days (3 phases)</div>
            </div>
            <div>
              <div class="text-xs text-muted mb-4">Expected Start</div>
              <div style="font-weight:700;">Within 2 weeks of contract signing</div>
            </div>
            <div>
              <div class="text-xs text-muted mb-4">Free Revisions / Phase</div>
              <!-- PHP: $job['free_revisions'] -->
              <div style="font-weight:700;">2 revisions included</div>
            </div>
            <div>
              <div class="text-xs text-muted mb-4">Proposal Visibility</div>
              <!-- PHP: $job['visibility']==='public' ? 'Public' : 'Invitation-Only' -->
              <div style="font-weight:700;">Public</div>
            </div>
          </div>
        </div>

      </div><!-- end tab-0 -->

      <!-- ══ TAB 1: MILESTONES ══ -->
      <div id="tab-1" class="hidden">

        <p class="text-sm text-muted mb-20">Funds are locked in escrow per milestone. You begin each phase only after the client confirms the previous phase escrow and you both sign off. Payments release on bilateral milestone approval.</p>

        <!-- PHP: foreach($milestones as $i=>$m): -->
        <div class="milestone-display-item">
          <div class="milestone-display-num">1</div>
          <div class="milestone-display-body">
            <!-- PHP: htmlspecialchars($m['name']) -->
            <div class="milestone-display-name">Initial Document Review &amp; Gap Analysis</div>
            <div class="milestone-display-meta">
              <!-- PHP: $m['duration'].' days' -->
              <span>⏱ 14 days</span>
            </div>
          </div>
          <div class="milestone-display-amount">
            <!-- PHP: '$'.number_format($m['amount']) -->
            <div style="font-family:var(--font-mono);font-weight:600;font-size:1rem;">$3,000</div>
            <div class="text-xs text-muted">on approval</div>
          </div>
        </div>

        <div class="milestone-display-item">
          <div class="milestone-display-num">2</div>
          <div class="milestone-display-body">
            <div class="milestone-display-name">Jurisdiction-Specific Legal Analysis</div>
            <div class="milestone-display-meta">
              <span>⏱ 21 days</span>
            </div>
          </div>
          <div class="milestone-display-amount">
            <div style="font-family:var(--font-mono);font-weight:600;font-size:1rem;">$4,500</div>
            <div class="text-xs text-muted">on approval</div>
          </div>
        </div>

        <div class="milestone-display-item">
          <div class="milestone-display-num">3</div>
          <div class="milestone-display-body">
            <div class="milestone-display-name">Revised Contracts &amp; Final Advisory Report</div>
            <div class="milestone-display-meta">
              <span>⏱ 14 days</span>
            </div>
          </div>
          <div class="milestone-display-amount">
            <div style="font-family:var(--font-mono);font-weight:600;font-size:1rem;">$4,500</div>
            <div class="text-xs text-muted">on approval</div>
          </div>
        </div>

        <!-- TOTALS -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);margin-top:16px;">
          <span style="font-weight:700;">Total Project Value</span>
          <span style="font-family:var(--font-mono);font-size:1.2rem;font-weight:600;">$12,000</span>
        </div>

        <div class="verify-band mt-16">
          <span>🔒</span>
          <div style="font-size:.8125rem;">
            <strong>First escrow ($3,000) is locked at contract signing.</strong> You will not begin Phase 1 work until this is confirmed. Each subsequent milestone's escrow is locked before you start that phase. Auto-approval triggers after 72h if the client does not review.
          </div>
        </div>

        <hr class="divider">
        <h4 class="mb-8" style="font-size:.9rem;">Revision Policy</h4>
        <p class="text-sm text-muted">2 free revisions are included per milestone. Additional revisions are billed at a separately agreed rate, logged and tracked by the platform. Revision requests must be submitted within the client's inspection window.</p>

      </div><!-- end tab-1 -->

      
      <!-- ══ TAB 2: NDA & PRIVACY ══ -->
      <div id="tab-2" class="hidden">

        <div class="job-section">
          <div class="job-section-title">NDA Terms</div>
          <div class="verify-band mb-16">
            <span>🔏</span>
            <div style="font-size:.8125rem;">
              <strong>NDA is required for this engagement.</strong> It will be auto-generated and sent to you via the platform if the client shortlists your proposal. You must sign before accessing the full project brief and any attached materials.
            </div>
          </div>

          <!-- PHP: $job['nda_type']==='standard' ? show below : show 'Custom NDA on shortlist' -->
          <div class="niche-field-row"><div class="niche-field-label">NDA Type</div><div class="niche-field-value">Standard Nexus NDA</div></div>
          <div class="niche-field-row"><div class="niche-field-label">Duration</div><div class="niche-field-value">2 years from engagement end</div></div>
          <div class="niche-field-row"><div class="niche-field-label">Liquidated Damages</div><div class="niche-field-value">$10,000 per breach</div></div>
          <div class="niche-field-row"><div class="niche-field-label">Governing Law (NDA)</div><div class="niche-field-value">Egyptian Civil Law</div></div>
          <div class="niche-field-row"><div class="niche-field-label">Applies To</div><div class="niche-field-value">All project materials, communications, client identity, and deliverables</div></div>
        </div>

        <div class="job-section">
          <div class="job-section-title">Your Obligations</div>
          <p class="text-sm text-muted">By submitting a proposal you acknowledge that you have read and agree to the project scope and the NDA terms outlined above. Accepting a contract on this project constitutes a binding signature of the auto-generated NDA.</p>
        </div>

      </div><!-- end tab-2 -->

      <!-- ══ TAB 3: CLIENT PROFILE ══ -->
      <div id="tab-3" class="hidden">

        <!-- CLIENT SUMMARY — mirrors client-profile-public content -->
        <div style="display:flex;gap:20px;align-items:flex-start;margin-bottom:24px;">
          <div style="width:72px;height:72px;border-radius:var(--radius-md);background:var(--ink);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.5rem;font-weight:600;color:var(--gold);flex-shrink:0;">FC</div>
          <div>
            <h3 style="font-size:1.2rem;margin-bottom:4px;">FinCorp Egypt</h3>
            <div class="text-sm text-muted mb-8">Corporate · Financial Services · Cairo, Egypt</div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
              <span class="badge badge-verified badge-dot" style="font-size:.625rem;">Verified</span>
            </div>
          </div>
        </div>

        <!-- PHP: $client['org']['bio'] -->
        <p class="mb-16" style="font-size:.9rem;">FinCorp Egypt is a mid-market financial services company providing corporate banking, investment advisory, and digital payment infrastructure across North Africa and the GCC. They engage specialized consultants for high-stakes projects requiring structured milestone delivery and verified credentials.</p>

        <div class="client-mini mb-20">
          <!-- PHP: $client['stats'] -->
          <div class="client-mini-stat"><span class="text-muted">Projects Completed</span><span class="font-mono font-bold">12</span></div>
          <div class="client-mini-stat"><span class="text-muted">Dispute Rate</span><span class="font-mono font-bold" style="color:var(--sage);">2.1%</span></div>
          <div class="client-mini-stat"><span class="text-muted">Payment Reliability</span><span class="font-mono font-bold" style="color:var(--sage);">100%</span></div>
          <div class="client-mini-stat"><span class="text-muted">Repeat Hire Rate</span><span class="font-mono font-bold">71%</span></div>
          <div class="client-mini-stat"><span class="text-muted">Auto-Approval Window</span><span class="font-mono">72h</span></div>
        </div>

        <a href="client-profile-public.html" class="btn btn-outline btn-sm">View Full Client Profile →</a>

      </div><!-- end tab-4 -->

    </div><!-- end left column -->

        <!-- FOOTER — always visible -->

      <!-- CLIENT TRUST -->
      <div class="client-mini mt-16">
        <div style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;font-family:var(--font-body);">Client Quick Stats</div>
        <div class="client-mini-stat"><span class="text-muted">Completed Projects</span><span class="font-mono font-bold">12</span></div>
        <div class="client-mini-stat"><span class="text-muted">Payment Reliability</span><span class="font-mono font-bold" style="color:var(--sage);">100%</span></div>
        <div class="client-mini-stat"><span class="text-muted">Avg. Approval Time</span><span class="font-mono font-bold" style="color:var(--sage);">38h</span></div>
        <div class="client-mini-stat"><span class="text-muted">Dispute Rate</span><span class="font-mono">2.1%</span></div>
        <a href="client-profile-public.html" style="display:block;text-align:center;margin-top:12px;font-size:.8125rem;color:var(--gold);">View full client profile →</a>
      </div>

    </div><!-- end right column -->

  </div><!-- end job-body -->
</div><!-- end container -->

<!-- ══════════════════ MODALS ══════════════════ -->

<!-- SUCCESS MODAL — PHP: shown on $bidSubmitted===true or via JS after AJAX -->
<div id="success-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm" style="text-align:center;">
    <div class="modal-body" style="padding:48px 32px;">
      <div style="font-size:3rem;margin-bottom:20px;">✦</div>
      <h3 style="margin-bottom:10px;">Proposal Submitted</h3>
      <p class="text-sm text-muted mb-8">Your proposal for <strong>MENA Expansion — Cross-Border Contract Review</strong> has been sent to FinCorp Egypt.</p>
      <p class="text-sm text-muted mb-24">You'll be notified when they review it. If shortlisted, an NDA will be sent for your signature before the full brief is shared.</p>
      <!-- PHP: <span class="font-mono text-xs text-muted">Proposal Ref: BID-<?= $newBid['id'] ?></span> -->
      <span class="font-mono text-xs text-muted" id="bid-ref">Proposal Ref: BID-NX-4821-DR</span>
      <div style="display:flex;flex-direction:column;gap:10px;margin-top:24px;">
        <a href="dashboard-freelancer.html" class="btn btn-primary" style="justify-content:center;">Back to Dashboard</a>
        <button class="btn btn-outline" style="justify-content:center;" onclick="document.getElementById('success-modal').classList.add('hidden')">View Proposal Details</button>
      </div>
    </div>
  </div>
</div>

<!-- DRAFT SAVED TOAST -->
<div class="toast-stack" id="toast-stack"></div>

<script>
/* ─── DROPDOWN TOGGLE ─── */
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});

/* ─── TAB SWITCHING ─── */
function switchTab(i) {
  document.querySelectorAll('.tabs .tab-item').forEach((t,j) => t.classList.toggle('active', i===j));
  ['tab-0','tab-1','tab-2','tab-3',].forEach((id,j) => {
    const el = document.getElementById(id);
    if(el) el.classList.toggle('hidden', i!==j);
  });
}

/* ─── BID PANEL STEPS ─── */
function setBidStep(i) {
  ['bpanel-0','bpanel-1','bpanel-2','bpanel-3'].forEach((id,j) => {
    const el = document.getElementById(id);
    if(el) el.classList.toggle('active', i===j);
  });
  ['bstep-0','bstep-1','bstep-2','bstep-3'].forEach((id,j) => {
    const el = document.getElementById(id);
    if(!el) return;
    el.classList.remove('active','done');
    if(j < i) el.classList.add('done');
    else if(j === i) el.classList.add('active');
  });
  // Update summary panel
  document.getElementById('summary-budget').textContent = document.getElementById('bid-total')?.value ? '$'+Number(document.getElementById('bid-total').value).toLocaleString() : '—';
  document.getElementById('summary-delivery').textContent = document.getElementById('bid-delivery')?.value || '—';
  const files = document.querySelectorAll('#prop-files .uploaded-file-row');
  document.getElementById('summary-attach').textContent = files.length ? files.length+' file(s)' : 'None';
}

/* ─── CHAR COUNTER ─── */
function countChars(el, max, id) {
  const n = el.value.length;
  const c = document.getElementById(id);
  if(!c) return;
  c.textContent = `${n} / ${max}`;
  c.className = 'char-counter' + (n > max ? ' over' : n > max * .9 ? ' warn' : '');
}

/* ─── BID STEP VALIDATION ─── */
function validateBidStep0() {
  const txt = document.getElementById('proposal-text');
  if(!txt || txt.value.trim().length < 80) {
    txt?.classList.add('input-invalid');
    showToast('Please write a proposal message of at least 80 characters.', 'warn');
    txt?.focus();
    return;
  }
  txt.classList.remove('input-invalid');
  setBidStep(1);
}
function validateBidStep1() {
  const amt = document.getElementById('bid-total');
  if(!amt || !amt.value || Number(amt.value) < 500) {
    amt?.classList.add('input-invalid');
    showToast('Please enter a valid bid amount.', 'warn');
    amt?.focus();
    return;
  }
  amt.classList.remove('input-invalid');
  setBidStep(2);
}

/* ─── BID AMOUNT LIVE DISPLAY ─── */
function updateBidCalc() {
  const val = document.getElementById('bid-total')?.value;
  document.getElementById('bid-display').textContent = val ? '$'+Number(val).toLocaleString() : '—';
}

/* ─── MILESTONE MODE ─── */
function toggleMsMode(mode) {
  document.getElementById('ms-accept-view').style.display = mode === 'accept' ? '' : 'none';
  document.getElementById('ms-propose-view').style.display = mode === 'propose' ? '' : 'none';
  document.getElementById('ms-accept-btn').style.borderColor = mode==='accept'?'var(--gold)':'var(--border)';
  document.getElementById('ms-accept-btn').style.background = mode==='accept'?'var(--gold-pale)':'';
  document.getElementById('ms-propose-btn').style.borderColor = mode==='propose'?'var(--gold)':'var(--border)';
  document.getElementById('ms-propose-btn').style.background = mode==='propose'?'var(--gold-pale)':'';
}

let propMsCount = 1;
function addProposedMs() {
  propMsCount++;
  const list = document.getElementById('proposed-ms-list');
  const d = document.createElement('div');
  d.style.cssText = 'display:grid;grid-template-columns:1fr 80px 90px auto;gap:8px;margin-bottom:8px;align-items:center;';
  d.innerHTML = `<input type="text" class="form-control" placeholder="Milestone name" style="font-size:.8125rem;"><input type="text" class="form-control" placeholder="e.g. 14d" style="font-size:.8125rem;"><div style="position:relative;"><span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-family:var(--font-mono);font-size:.8125rem;color:var(--ink-muted);">$</span><input type="number" class="form-control" style="padding-left:22px;font-size:.8125rem;" oninput="recalcProposed()"></div><button class="btn btn-ghost btn-icon" style="color:var(--rust);font-size:.875rem;" onclick="this.closest('div').remove();recalcProposed()">🗑</button>`;
  list.appendChild(d);
}
function recalcProposed() {
  const vals = Array.from(document.querySelectorAll('#proposed-ms-list input[type="number"]')).map(i => parseFloat(i.value)||0);
  const t = vals.reduce((a,b)=>a+b,0);
  document.getElementById('proposed-total').textContent = '$'+t.toLocaleString();
}

/* ─── FILE UPLOADS ─── */
function handlePropFiles(input) {
  const target = document.getElementById('prop-files');
  Array.from(input.files).forEach(f => {
    const size = f.size > 1048576 ? (f.size/1048576).toFixed(1)+' MB' : (f.size/1024).toFixed(0)+' KB';
    const row = document.createElement('div');
    row.style.cssText = 'display:flex;align-items:center;gap:10px;padding:8px 12px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-top:6px;font-size:.8125rem;';
    row.innerHTML = `<span>📄</span><span style="flex:1;font-weight:600;">${f.name}</span><span style="color:var(--ink-muted);font-family:var(--font-mono);font-size:.75rem;">${size}</span><button style="background:none;border:none;cursor:pointer;color:var(--rust);font-size:.875rem;" onclick="this.parentNode.remove()">✕</button>`;
    target.appendChild(row);
  });
}

/* ─── SUBMIT BID ─── */
function submitBid() {
  if(!document.getElementById('bid-agree')?.checked) {
    showToast('Please confirm you agree before submitting.', 'warn');
    return;
  }
  // PHP: this button submits the form to POST /jobs/{id}/bid
  document.getElementById('success-modal').classList.remove('hidden');
}

/* ─── SAVE DRAFT ─── */
function saveDraft() {
  // PHP: AJAX POST /jobs/{id}/bid/draft
  showToast('Draft saved. You can return to this proposal from your dashboard.');
}

/* ─── COPY LINK ─── */
function copyLink() {
  // PHP: window.location.href is the canonical job URL
  navigator.clipboard?.writeText(window.location.href).then(()=>{
    document.getElementById('copied-msg').textContent = 'Link copied!';
    setTimeout(()=>document.getElementById('copied-msg').textContent='', 2500);
  });
}

/* ─── TOAST ─── */
function showToast(msg, type) {
  const s = document.getElementById('toast-stack');
  const isWarn = type === 'warn';
  s.innerHTML = `<div class="toast ${isWarn?'warning':'success'}"><span class="toast-icon">${isWarn?'⚠':'✓'}</span><div><div class="toast-title">${isWarn?'Required':'Saved'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(()=>s.innerHTML='', 4000);
}

/* ─── PROPOSAL TEXT input-invalid class removal ─── */
document.getElementById('proposal-text')?.addEventListener('input', function() {
  if(this.value.length >= 80) this.classList.remove('input-invalid');
});
document.getElementById('bid-total')?.addEventListener('input', function() {
  if(Number(this.value) >= 500) this.classList.remove('input-invalid');
});
</script>
</body>
</html>
