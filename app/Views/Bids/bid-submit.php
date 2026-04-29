<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/specialist/bid-submit.php
    Template: Bid Submission & Management
    Role:     specialist (authenticated)
    Route:    /jobs/{job_id}/bid          POST  → submit new bid
              /jobs/{job_id}/bid/{bid_id} GET   → view/edit existing bid
              /jobs/{job_id}/bid/{bid_id} PATCH → update before window closes
              /jobs/{job_id}/bid/{bid_id} DELETE → withdraw bid
    ============================================================
    PHP Data contract (from BidController):
      $job            — full job record
      $client         — client + org
      $milestones     — client's proposed milestones
      $bid            — null (new) | existing BidRecord
      $canWithdraw    — bool: within 48-hour withdrawal window
      $withdrawDeadline — Carbon timestamp (bid_submitted_at + 48h)
      $hoursRemaining — int: hours left in withdrawal window
      $specialist     — authenticated specialist
      $matchScore     — int 0-100
      $errors         — validation errors array (on re-render after fail)
    ============================================================
-->
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- PHP: <title>Bid on: <?= htmlspecialchars($job['title']) ?> · Nexus</title> -->
  <title>Bid on: MENA Expansion — Cross-Border Contract Review · Nexus</title>
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="assets/bid-submit.css">
</head>

<body>

  <!-- ══════════ TOPNAV ══════════
     PHP: include 'partials/topnav.php'; ['role'=>'specialist','user'=>$specialist]
-->
  <nav class="topnav">
    <div class="container">
      <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
      <div class="topnav-links">
        <!-- PHP: <a href="/jobs/<?= $job['slug'] ?>">← Back to Job</a> -->
        <a href="job-view.html">← Back to Job</a>
        <a href="dashboard-freelancer.html">Dashboard</a>
      </div>
      <div class="topnav-actions">
        <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">
          🔔 <span class="notif-count" style="position:absolute;top:2px;right:2px;">7</span>
        </a>
        <div class="dropdown">
          <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
            <div class="avatar-badge">
              <div class="avatar avatar-sm">DR</div>
            </div>
            <!-- PHP: htmlspecialchars($specialist['display_name']) -->
            <span style="font-size:.875rem;font-weight:700;">Dr. Rania K.</span>
            <span style="color:var(--ink-faint);">▾</span>
          </div>
          <div class="dropdown-menu hidden" id="user-dd">
            <div class="dropdown-item"
              style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">
              Client Account</div>
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

  <!-- PAGE HEADER -->
  <div style="background:var(--ivory-card);border-bottom:1px solid var(--border);padding:28px 0;">
    <div class="container" style="max-width:1200px;">
      <!-- PHP: $bid ? 'Edit Your Proposal' : 'Submit a Proposal' -->
      <div class="breadcrumb"
        style="font-family:var(--font-mono);font-size:.75rem;color:var(--ink-muted);margin-bottom:8px;">
        Jobs <span style="margin:0 6px;color:var(--ink-faint);">›</span>
        <!-- PHP: htmlspecialchars(Str::limit($job['title'],40)) -->
        MENA Expansion — Contract Review
        <span style="margin:0 6px;color:var(--ink-faint);">›</span>
        Submit Proposal
      </div>
      <div class="flex justify-between items-center">
        <div>
          <h2 style="font-family:var(--font-display);font-size:1.6rem;font-weight:500;margin-bottom:4px;">Submit Your
            Proposal</h2>
          <p style="font-size:.875rem;color:var(--ink-muted);">Customise your bid, set your own milestone schedule, and
            write your cover letter. You have <strong>48 hours</strong> to withdraw after submission.</p>
        </div>
        <div class="flex items-center gap-10">
          <!-- PHP: <span class="badge badge-gold" style="font-size:.75rem;">Ref: <?= $job['ref'] ?></span> -->
          <span class="badge badge-default font-mono" style="font-size:.75rem;">NX-2025-4821</span>
        </div>
      </div>
    </div>
  </div>

  <div style="max-width:1200px;margin:0 auto;padding:36px 32px 80px;">

    <!-- PHP: if($bid && $bid['status'] !== 'draft'): show status bar -->
    <!-- EXAMPLE: submitted state — uncomment whichever state applies
  <div class="bid-status-bar submitted">
    <span style="font-size:1.2rem;">✓</span>
    <div><strong>Proposal Submitted</strong> — Apr 12, 2025 · 14:22 GMT+2. FinCorp Egypt will be notified.</div>
  </div>
  -->

    <!-- JOB CONTEXT BANNER -->
    <div class="job-context-banner">
      <div class="job-context-niche">⚖️</div>
      <div style="flex:1;min-width:0;">
        <!-- PHP: htmlspecialchars($job['title']) -->
        <div class="job-context-title">MENA Expansion — Cross-Border Contract Review</div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:4px;">
          <span class="badge badge-verified" style="font-size:.625rem;">Legal Consulting</span>
          <span class="text-xs text-muted font-mono">$12,000 · 3 milestones · 49 days</span>
          <span class="text-xs text-muted">·</span>
          <!-- PHP: $bidCount.' proposals received' -->
          <span class="text-xs text-muted">7 proposals received</span>
          <span class="text-xs text-muted">·</span>
        </div>
      </div>
      <div style="display:flex;gap:8px;flex-shrink:0;">
        <!-- PHP: client org -->
        <div style="text-align:right;">
          <div style="font-weight:700;font-size:.875rem;">FinCorp Egypt</div>
          <div style="margin-top:4px;display:flex;gap:4px;justify-content:flex-end;">
            <span class="badge badge-verified badge-dot" style="font-size:.6rem;">Verified</span>
          </div>
        </div>
      </div>
    </div>

    <!-- FORM + SIDEBAR -->
    <!-- PHP: <form method="POST" action="/jobs/<?= $job['id'] ?>/bid" enctype="multipart/form-data" id="bid-form"> -->
    <form id="bid-form" onsubmit="return handleSubmit(event)">
      <!-- PHP: csrf_field() -->
      <!-- PHP: <input type="hidden" name="_method" value="<?= $bid ? 'PATCH' : 'POST' ?>"> -->

      <div class="bid-shell" style="padding:0;gap:36px;">

        <!-- ── LEFT: FORM ── -->
        <div>

          <!-- ════ SECTION A: COVER LETTER ════ -->
          <div class="form-section">
            <div class="form-section-label">A — Cover Letter</div>
            <p class="form-section-desc">Write directly to the client. Address the project's specific challenges — this
              is the first thing they read. Specialists who reference the brief directly are 3× more likely to be
              shortlisted.</p>

            <div class="form-group">
              <label class="form-label">
                Proposal Message
                <span class="text-muted font-mono"
                  style="font-size:.7rem;text-transform:none;letter-spacing:0;font-weight:400;margin-left:8px;">Sent to
                  client immediately on submission</span>
              </label>
              <textarea class="form-control" rows="8" id="cover-letter" name="cover_letter" placeholder="Dear Amira,

I am a qualified commercial lawyer with 9 years of cross-border MENA practice, and I have reviewed your project brief carefully. I note your specific need for GDPR cross-border transfer analysis alongside Egyptian, UAE, and KSA compliance — this intersection is precisely the area I specialise in.

My proposed approach for Phase 1 would be..." oninput="countChars(this,1500,'clc');syncSummary()"></textarea>
              <div class="flex justify-between mt-4">
                <span class="field-error" id="err-cover">Please write a proposal of at least 100 characters.</span>
                <span class="char-counter" id="clc" style="margin-left:auto;">0 / 1500</span>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">
                Key Differentiators
                <span class="text-muted font-mono"
                  style="font-size:.7rem;text-transform:none;letter-spacing:0;font-weight:400;margin-left:8px;">Why you
                  over the other bidders</span>
              </label>
              <textarea class="form-control" rows="3" id="differentiators" name="differentiators"
                placeholder="e.g. I have advised 4 SaaS companies on Egyptian-UAE market entry, am admitted to the Cairo Bar and have direct DIFC Courts experience. I am fluent in Arabic, English, and French."
                oninput="countChars(this,400,'dc')"></textarea>
              <div class="flex justify-between mt-4">
                <p class="form-hint">Optional but strongly recommended for this client.</p>
                <span class="char-counter" id="dc" style="margin-left:auto;">0 / 400</span>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Relevant Past Work</label>
              <textarea class="form-control" rows="3" id="past-work" name="past_work"
                placeholder="Describe 1–2 comparable projects you have completed. Be specific about jurisdiction, contract type, and outcome. Do not disclose confidential client names."
                oninput="countChars(this,500,'pwc')"></textarea>
              <span class="char-counter" id="pwc" style="text-align:right;display:block;margin-top:4px;">0 / 500</span>
            </div>
          </div>

          <!-- ════ SECTION B: BUDGET ════ -->
          <div class="form-section">
            <div class="form-section-label">B — Budget</div>
            <p class="form-section-desc">You may accept the client's budget or propose a different total. A clear
              rationale is required if your bid differs by more than 15%.</p>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Your Total Bid</label>
                <div class="input-affix-wrap">
                  <span class="input-prefix">$</span>
                  <input type="number" class="form-control has-prefix" id="bid-total" name="bid_total" value="12000"
                    min="500" step="100" oninput="syncSummary();updateDelta()">
                </div>
                <!-- PHP: $job['total_budget'] -->
                <p class="form-hint mt-4">Client budget: <strong class="font-mono">$12,000</strong></p>
                <span class="field-error" id="err-total">Please enter a valid bid amount (min $500).</span>
              </div>
              <div class="form-group">
                <label class="form-label">Your Effective Rate</label>
                <div class="input-affix-wrap">
                  <input type="text" class="form-control has-suffix" id="effective-rate" readonly
                    style="background:var(--ivory-deep);color:var(--ink-mid);" value="$245 / day">
                  <span class="input-suffix">est.</span>
                </div>
                <p class="form-hint mt-4">Based on your total bid ÷ estimated days.</p>
              </div>
            </div>

            <!-- DELTA INDICATOR -->
            <div id="budget-delta" style="display:none;" class="flex items-center gap-10 mb-16">
              <span id="delta-badge" class="ms-delta-badge neutral">± $0 vs client budget</span>
              <span id="delta-pct" class="text-xs text-muted font-mono"></span>
            </div>

            <div class="form-group" id="rationale-group" style="display:none;">
              <label class="form-label">Budget Rationale <span style="color:var(--rust);font-size:.75rem;">Required —
                  your bid differs by more than 15%</span></label>
              <textarea class="form-control" rows="3" id="bid-rationale" name="bid_rationale"
                placeholder="Explain why your bid is higher or lower than the client's stated budget…"></textarea>
              <span class="field-error" id="err-rationale">Please explain your budget difference.</span>
            </div>

            <!-- COMPETITION CONTEXT (anonymous) -->
            <div
              style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-md);padding:18px 20px;">
              <div
                style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;font-family:var(--font-body);">
                Anonymous Competition Context</div>
              <!-- PHP: from $bidContext (aggregated, never individual bids) -->
              <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;text-align:center;">
                <div>
                  <div style="font-family:var(--font-mono);font-size:1rem;font-weight:600;color:var(--sage);">$9,800
                  </div>
                  <div class="text-xs text-muted">Lowest Bid</div>
                </div>
                <div>
                  <div style="font-family:var(--font-mono);font-size:1rem;font-weight:600;">$11,500</div>
                  <div class="text-xs text-muted">Median Bid</div>
                </div>
                <div>
                  <div style="font-family:var(--font-mono);font-size:1rem;font-weight:600;color:var(--rust);">$16,200
                  </div>
                  <div class="text-xs text-muted">Highest Bid</div>
                </div>
              </div>
              <div style="position:relative;margin-top:14px;height:10px;background:var(--border);border-radius:5px;">
                <div
                  style="position:absolute;left:0;top:0;height:100%;width:calc((9800/18000)*100%);background:var(--border);border-radius:5px;">
                </div>
                <div style="position:absolute;top:-3px;height:16px;width:3px;background:var(--sage);border-radius:2px;"
                  id="low-marker" style="left:calc((9800/18000)*100%);"></div>
                <div style="position:absolute;top:-3px;height:16px;width:3px;background:var(--ink);border-radius:2px;"
                  id="med-marker" style="left:calc((11500/18000)*100%);"></div>
                <div style="position:absolute;top:-3px;height:16px;width:3px;background:var(--rust);border-radius:2px;"
                  id="high-marker" style="left:calc((16200/18000)*100%);"></div>
                <div
                  style="position:absolute;top:-5px;height:20px;width:4px;background:var(--gold);border-radius:2px;transition:left .3s;"
                  id="your-marker"></div>
              </div>
              <div class="text-xs text-muted font-mono mt-6 text-center">Your bid position — <span id="bid-pos-label"
                  style="color:var(--gold);font-weight:700;">At client budget</span></div>
            </div>
          </div>

          <!-- ════ SECTION C: MILESTONES ════ -->
          <div class="form-section">
            <div class="form-section-label">C — Milestone Schedule</div>
            <p class="form-section-desc">Edit each milestone name, duration, and payment amount to match your proposed
              delivery plan. Changes are highlighted and shown to the client clearly. The client must approve any
              deviations before a contract is issued.</p>

            <!-- MILESTONE MODE TOGGLE -->
            <div style="display:flex;gap:10px;margin-bottom:20px;align-items:center;">
              <div
                style="display:flex;gap:0;border:1.5px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;">
                <button type="button" class="btn" id="btn-accept"
                  style="border-radius:0;border:none;background:var(--gold-pale);color:var(--ink);padding:8px 16px;font-size:.8rem;"
                  onclick="setMsMode('accept')">
                  ✓ Accept Client Milestones
                </button>
                <button type="button" class="btn" id="btn-custom"
                  style="border-radius:0;border:none;background:transparent;color:var(--ink-muted);padding:8px 16px;font-size:.8rem;"
                  onclick="setMsMode('custom')">
                  ✎ Propose Custom Schedule
                </button>
              </div>
              <span id="ms-edit-count" class="text-xs text-muted font-mono" style="display:none;"></span>
            </div>

            <!-- MILESTONES: EDITABLE -->
            <!-- PHP: foreach($milestones as $i=>$m): -->
            <div id="ms-list">

              <div class="ms-edit-item" id="ms-item-0" data-original-name="Initial Document Review &amp; Gap Analysis"
                data-original-duration="14" data-original-amount="3000">
                <div class="ms-edit-header">
                  <div class="ms-num">1</div>
                  <div style="flex:1;">
                    <div style="font-weight:700;font-size:.875rem;" id="ms-display-0">Initial Document Review &amp; Gap
                      Analysis</div>
                    <div class="text-xs text-muted font-mono mt-2">Client proposed: 14 days · $3,000</div>
                  </div>
                  <span class="ms-original-badge" id="ms-badge-0">Unchanged</span>
                  <button type="button" class="btn btn-ghost btn-sm" onclick="toggleMs(0)">Edit</button>
                </div>
                <div class="ms-edit-body" id="ms-body-0" style="display:none;">
                  <div class="ms-field-grid">
                    <div class="form-group" style="margin:0;">
                      <label class="form-label">Milestone Name</label>
                      <input type="text" class="form-control" id="ms-name-0" name="milestones[0][name]"
                        value="Initial Document Review &amp; Gap Analysis" oninput="syncMs(0)">
                    </div>
                    <div class="form-group" style="margin:0;">
                      <label class="form-label">Duration</label>
                      <div class="input-affix-wrap">
                        <input type="number" class="form-control has-suffix" id="ms-dur-0"
                          name="milestones[0][duration]" value="14" min="1" max="180" oninput="syncMs(0);syncSummary()">
                        <span class="input-suffix">days</span>
                      </div>
                    </div>
                    <div class="form-group" style="margin:0;">
                      <label class="form-label">Amount</label>
                      <div class="input-affix-wrap">
                        <span class="input-prefix">$</span>
                        <input type="number" class="form-control has-prefix" id="ms-amt-0" name="milestones[0][amount]"
                          value="3000" min="100" step="50" oninput="syncMs(0);recalcTotal();syncSummary()">
                      </div>
                    </div>
                  </div>
                  <div class="form-group mt-12" style="margin-bottom:0;">
                    <label class="form-label">Deliverables for This Phase</label>
                    <textarea class="form-control" rows="2" id="ms-del-0" name="milestones[0][deliverables]"
                      placeholder="List what you will deliver at the end of this milestone…">Gap analysis report (PDF), document inventory log, jurisdiction risk register draft</textarea>
                  </div>
                  <div id="ms-delta-row-0" class="mt-8" style="display:none;"></div>
                </div>
              </div>

              <div class="ms-edit-item" id="ms-item-1" data-original-name="Jurisdiction-Specific Legal Analysis"
                data-original-duration="21" data-original-amount="4500">
                <div class="ms-edit-header">
                  <div class="ms-num">2</div>
                  <div style="flex:1;">
                    <div style="font-weight:700;font-size:.875rem;" id="ms-display-1">Jurisdiction-Specific Legal
                      Analysis</div>
                    <div class="text-xs text-muted font-mono mt-2">Client proposed: 21 days · $4,500</div>
                  </div>
                  <span class="ms-original-badge" id="ms-badge-1">Unchanged</span>
                  <button type="button" class="btn btn-ghost btn-sm" onclick="toggleMs(1)">Edit</button>
                </div>
                <div class="ms-edit-body" id="ms-body-1" style="display:none;">
                  <div class="ms-field-grid">
                    <div class="form-group" style="margin:0;">
                      <label class="form-label">Milestone Name</label>
                      <input type="text" class="form-control" id="ms-name-1" name="milestones[1][name]"
                        value="Jurisdiction-Specific Legal Analysis" oninput="syncMs(1)">
                    </div>
                    <div class="form-group" style="margin:0;">
                      <label class="form-label">Duration</label>
                      <div class="input-affix-wrap">
                        <input type="number" class="form-control has-suffix" id="ms-dur-1"
                          name="milestones[1][duration]" value="21" min="1" max="180" oninput="syncMs(1);syncSummary()">
                        <span class="input-suffix">days</span>
                      </div>
                    </div>
                    <div class="form-group" style="margin:0;">
                      <label class="form-label">Amount</label>
                      <div class="input-affix-wrap">
                        <span class="input-prefix">$</span>
                        <input type="number" class="form-control has-prefix" id="ms-amt-1" name="milestones[1][amount]"
                          value="4500" min="100" step="50" oninput="syncMs(1);recalcTotal();syncSummary()">
                      </div>
                    </div>
                  </div>
                  <div class="form-group mt-12" style="margin-bottom:0;">
                    <label class="form-label">Deliverables for This Phase</label>
                    <textarea class="form-control" rows="2" id="ms-del-1" name="milestones[1][deliverables]"
                      placeholder="List what you will deliver at the end of this milestone…">Full legal analysis per jurisdiction (EGY/UAE/KSA/GDPR), risk matrix, recommended contract framework</textarea>
                  </div>
                  <div id="ms-delta-row-1" class="mt-8" style="display:none;"></div>
                </div>
              </div>

              <div class="ms-edit-item" id="ms-item-2"
                data-original-name="Revised Contracts &amp; Final Advisory Report" data-original-duration="14"
                data-original-amount="4500">
                <div class="ms-edit-header">
                  <div class="ms-num">3</div>
                  <div style="flex:1;">
                    <div style="font-weight:700;font-size:.875rem;" id="ms-display-2">Revised Contracts &amp; Final
                      Advisory Report</div>
                    <div class="text-xs text-muted font-mono mt-2">Client proposed: 14 days · $4,500</div>
                  </div>
                  <span class="ms-original-badge" id="ms-badge-2">Unchanged</span>
                  <button type="button" class="btn btn-ghost btn-sm" onclick="toggleMs(2)">Edit</button>
                </div>
                <div class="ms-edit-body" id="ms-body-2" style="display:none;">
                  <div class="ms-field-grid">
                    <div class="form-group" style="margin:0;">
                      <label class="form-label">Milestone Name</label>
                      <input type="text" class="form-control" id="ms-name-2" name="milestones[2][name]"
                        value="Revised Contracts &amp; Final Advisory Report" oninput="syncMs(2)">
                    </div>
                    <div class="form-group" style="margin:0;">
                      <label class="form-label">Duration</label>
                      <div class="input-affix-wrap">
                        <input type="number" class="form-control has-suffix" id="ms-dur-2"
                          name="milestones[2][duration]" value="14" min="1" max="180" oninput="syncMs(2);syncSummary()">
                        <span class="input-suffix">days</span>
                      </div>
                    </div>
                    <div class="form-group" style="margin:0;">
                      <label class="form-label">Amount</label>
                      <div class="input-affix-wrap">
                        <span class="input-prefix">$</span>
                        <input type="number" class="form-control has-prefix" id="ms-amt-2" name="milestones[2][amount]"
                          value="4500" min="100" step="50" oninput="syncMs(2);recalcTotal();syncSummary()">
                      </div>
                    </div>
                  </div>
                  <div class="form-group mt-12" style="margin-bottom:0;">
                    <label class="form-label">Deliverables for This Phase</label>
                    <textarea class="form-control" rows="2" id="ms-del-2" name="milestones[2][deliverables]"
                      placeholder="List what you will deliver at the end of this milestone…">Redrafted contract suite (AR+EN), GDPR SCC addenda, final advisory report, 1 stakeholder Q&amp;A session</textarea>
                  </div>
                  <div id="ms-delta-row-2" class="mt-8" style="display:none;"></div>
                </div>
              </div>

            </div><!-- end ms-list -->

            <!-- ADD MILESTONE (custom mode only) -->
            <button type="button" class="add-row-btn w-full mt-8" id="btn-add-ms"
              style="display:none;padding:11px 14px;border:1.5px dashed var(--border-dark);border-radius:var(--radius-sm);background:none;cursor:pointer;font-size:.875rem;color:var(--ink-muted);font-family:var(--font-body);transition:all .15s;"
              onclick="addMilestone()">
              + Add Milestone
            </button>

            <!-- MILESTONE TOTAL -->
            <div
              style="display:flex;justify-content:space-between;align-items:center;padding:14px 18px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);margin-top:12px;">
              <span style="font-weight:700;">Milestone Total</span>
              <div class="flex items-center gap-10">
                <span style="font-family:var(--font-display);font-size:1.3rem;font-weight:300;"
                  id="ms-total-display">$12,000</span>
                <span id="ms-total-delta" style="display:none;"></span>
              </div>
            </div>
            <div id="ms-total-mismatch" class="field-error mt-6" style="display:none;">⚠ Milestone total does not match
              your bid amount. They must be equal before submitting.</div>

          </div>

          <!-- ════ SECTION D: TIMELINE & AVAILABILITY ════ -->
          <div class="form-section">
            <div class="form-section-label">D — Timeline &amp; Start Availability</div>
            <p class="form-section-desc">Tell the client when you can start and how you'll manage their deadline. This
              feeds directly into the contract.</p>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Estimated Start Date</label>
                <div class="input-affix-wrap">
                  <input type="date" class="form-control" id="start-date" name="start_date"
                    min="<?php /* echo date('Y-m-d', strtotime('+2 days')) */ ?>" value="">
                </div>
                <p class="form-hint mt-4">Must be within 14 days of contract signing per client requirement.</p>
              </div>
              <div class="form-group">
                <label class="form-label">Estimated Total Duration</label>
                <div class="input-affix-wrap">
                  <input type="number" class="form-control has-suffix" id="total-duration" value="49" min="1" readonly
                    style="background:var(--ivory-deep);color:var(--ink-mid);">
                  <span class="input-suffix">days</span>
                </div>
                <p class="form-hint mt-4">Auto-calculated from your milestone durations.</p>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Your Preferred Interview/Check-in Slots</label>
              <p class="form-hint mb-8">The client requires up to 3 video calls. Select slots you're reliably available
                — GMT+2.</p>
              <button type="button" class="btn btn-outline btn-sm mb-12" onclick="openAddSlotModal()">+ Add Availability Slot</button>
              <div id="avail-slots-list" style="margin-bottom:12px;"></div>
              <!-- PHP: hidden input populated by JS for form submission -->
              <input type="hidden" name="availability_slots" id="availability-slots-input">
            </div>
          </div>

          <!-- ════ SECTION E: ATTACHMENTS ════ -->
          <div class="form-section">
            <div class="form-section-label">E — Supporting Attachments</div>
            <p class="form-section-desc">Upload portfolio samples, anonymised case studies, relevant credentials, or a
              credentials summary. Max 5 files, 10MB each. NDA applies to everything you share.</p>

            <div class="file-drop" id="file-drop-zone" onclick="document.getElementById('bid-files').click()"
              ondragover="event.preventDefault();this.classList.add('drag-over')"
              ondragleave="this.classList.remove('drag-over')" ondrop="handleDrop(event)">
              <div style="font-size:1.8rem;opacity:.45;">📎</div>
              <div class="file-drop-label"><strong>Click to upload</strong> or drag &amp; drop</div>
              <div class="file-drop-hint">PDF · DOCX · ZIP · Max 10MB each · Up to 5 files</div>
            </div>
            <input type="file" id="bid-files" name="attachments[]" multiple accept=".pdf,.docx,.zip"
              style="display:none;" onchange="handleFiles(this)">
            <div id="file-list"></div>
          </div>

          <!-- ════ SECTION F: TERMS & CONFIRM ════ -->
          <div class="form-section" style="margin-bottom:0;">
            <div class="form-section-label">F — Terms &amp; Submission</div>

            <!-- NDA NOTICE -->
            <div
              style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-md);padding:16px 18px;margin-bottom:20px;display:flex;gap:12px;align-items:flex-start;font-size:.8125rem;">
              <span style="font-size:1.1rem;">🔏</span>
              <div>
                <strong>NDA auto-generated on shortlisting.</strong> If FinCorp shortlists your proposal, a standard
                Nexus NDA (2 years, $10,000 liquidated damages, Egyptian Civil Law) will be sent to you for digital
                signature before the full brief is shared. Submitting this proposal does not trigger the NDA.
              </div>
            </div>

            <!-- WITHDRAWAL POLICY -->
            <div
              style="background:var(--gold-pale);border:1px solid var(--gold-light);border-radius:var(--radius-md);padding:14px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start;font-size:.8125rem;">
              <span>↩</span>
              <div>
                <strong>48-hour withdrawal window.</strong> After submitting, you may withdraw this proposal within 48
                hours — no questions asked. After that window closes, withdrawal requires a formal request and may
                affect your response-rate score.
              </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px;">
              <label style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;">
                <input type="checkbox" id="agree-accurate" name="agree_accurate"
                  style="accent-color:var(--gold);margin-top:3px;" onchange="syncAgree()">
                <span>My proposal is accurate and I am available to start within the stated timeframe.</span>
              </label>
              <label style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;">
                <input type="checkbox" id="agree-qualified" name="agree_qualified"
                  style="accent-color:var(--gold);margin-top:3px;" onchange="syncAgree()">
                <span>I meet the stated credential and experience requirements for this engagement.</span>
              </label>
              <label style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;">
                <input type="checkbox" id="agree-terms" name="agree_terms"
                  style="accent-color:var(--gold);margin-top:3px;" onchange="syncAgree()">
                <span>I agree to the Nexus Platform <a href="#" style="color:var(--gold);">Terms of Service</a> and
                  understand that submitting this proposal creates a binding bid record on the platform.</span>
              </label>
            </div>
            <span class="field-error" id="err-agree">Please confirm all three checkboxes before submitting.</span>

            <div class="flex gap-12 items-center">
              <button type="submit" class="btn btn-primary btn-lg" id="submit-btn"
                style="opacity:.5;cursor:not-allowed;" disabled>
                ✦ Submit Proposal
              </button>
              <button type="button" class="btn btn-outline" onclick="saveDraft()">
                💾 Save Draft
              </button>
              <a href="job-view.html" class="btn btn-ghost">Cancel</a>
            </div>

          </div>

        </div><!-- end left form -->

        <!-- ── RIGHT: LIVE SUMMARY ── -->
        <div class="bid-summary-panel">

          <!-- LIVE BID SUMMARY -->
          <div class="summary-card">
            <div
              style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:14px;font-family:var(--font-body);">
              Your Proposal — Live Summary</div>

            <!-- MILESTONES -->
            <div id="summary-milestones" style="margin-bottom:8px;">
              <div
                style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
                <span class="text-muted">Phase 1</span>
                <span class="font-mono font-bold" id="sum-ms-0">14d · $3,000</span>
              </div>
              <div
                style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
                <span class="text-muted">Phase 2</span>
                <span class="font-mono font-bold" id="sum-ms-1">21d · $4,500</span>
              </div>
              <div
                style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
                <span class="text-muted">Phase 3</span>
                <span class="font-mono font-bold" id="sum-ms-2">14d · $4,500</span>
              </div>
            </div>

            <div class="summary-total">
              <span style="font-size:.875rem;color:var(--ink-muted);">Total Bid</span>
              <span class="summary-total-val" id="sum-total">$12,000 <span id="sum-edited-marker" class="edited-marker"
                  style="display:none;">edited</span></span>
            </div>
            <div class="summary-row mt-4" style="border:none;padding-top:8px;">
              <span class="summary-label">Est. Duration</span>
              <span class="summary-val" id="sum-duration">49 days</span>
            </div>
            <div class="summary-row">
              <span class="summary-label">Start Date</span>
              <span class="summary-val" id="sum-start">Not set</span>
            </div>
            <div class="summary-row">
              <span class="summary-label">Attachments</span>
              <span class="summary-val" id="sum-files">None</span>
            </div>
            <div class="summary-row">
              <span class="summary-label">Cover Letter</span>
              <span class="summary-val" id="sum-cover">0 chars</span>
            </div>
          </div>

          <!-- PLATFORM FEES -->
          <div class="summary-card">
            <div
              style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;font-family:var(--font-body);">
              Your Earnings Breakdown</div>
            <div class="summary-row" style="border:none;padding:5px 0;">
              <span class="summary-label">Your Bid</span>
              <span class="summary-val" id="earn-bid">$12,000</span>
            </div>
            <div class="summary-row" style="border:none;padding:5px 0;">
              <!-- PHP: $specialist['fee_tier']['rate'].'%' -->
              <span class="summary-label">Platform Fee (6.5%)</span>
              <span class="summary-val" id="earn-fee" style="color:var(--rust);">− $780</span>
            </div>
            <div style="height:1px;background:var(--border);margin:8px 0;"></div>
            <div style="display:flex;justify-content:space-between;font-weight:700;font-size:.9375rem;">
              <span>You Receive</span>
              <span class="font-mono" id="earn-net" style="color:var(--sage);">$11,220</span>
            </div>
          </div>

          <!-- WITHDRAWAL WINDOW (shown only after submission) -->
          <!-- PHP: if($bid && $canWithdraw): -->
          <div class="withdrawal-clock" id="withdrawal-widget" style="display:none;">
            <div class="flex justify-between items-center mb-4">
              <div style="font-weight:700;font-size:.875rem;">↩ Withdrawal Window</div>
              <span class="badge badge-pending badge-dot" style="font-size:.625rem;">Active</span>
            </div>
            <!-- PHP: $hoursRemaining.'h '.$minutesRemaining.'m remaining' -->
            <div style="font-family:var(--font-mono);font-size:1.1rem;font-weight:600;" id="withdraw-timer">47:58
              remaining</div>
            <div class="clock-bar mt-8">
              <div class="clock-fill" id="clock-fill" style="width:99.9%;"></div>
            </div>
            <div class="text-xs text-muted mt-6">
              <!-- PHP: 'Closes: '.$withdrawDeadline->format('M j, g:i A T') -->
              Closes: Apr 14, 2025 · 14:22 GMT+2
            </div>
            <button type="button" class="btn btn-danger btn-sm w-full mt-12" style="justify-content:center;"
              onclick="document.getElementById('withdraw-modal').classList.remove('hidden')">
              Withdraw Proposal
            </button>
          </div>

          <!-- CLIENT TRUST QUICK-LOOK -->
          <div class="summary-card">
            <div
              style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;font-family:var(--font-body);">
              Client Trust Signals</div>
            <div class="summary-row" style="padding:6px 0;border:none;"><span class="summary-label">Payment
                Reliability</span><span class="summary-val" style="color:var(--sage);">100%</span></div>
            <div class="summary-row" style="padding:6px 0;border:none;"><span class="summary-label">Dispute
                Rate</span><span class="summary-val">2.1%</span></div>
            <div class="summary-row" style="padding:6px 0;border:none;"><span class="summary-label">Completed
                Projects</span><span class="summary-val">12</span></div>
            <div class="summary-row" style="padding:6px 0;border:none;border-bottom:none;"><span
                class="summary-label">Withdrawal Window</span><span class="summary-val">48h after submission</span>
            </div>
            <a href="client-profile-public.html"
              style="font-size:.8125rem;color:var(--gold);display:block;margin-top:10px;">View full client profile →</a>
          </div>

        </div>

      </div>
    </form>
  </div>

  <!-- ══════ WITHDRAW MODAL ══════ -->
  <div id="withdraw-modal" class="modal-backdrop hidden">
    <div class="modal modal-sm">
      <div class="modal-header">
        <div>
          <h3 style="color:var(--rust);">Withdraw Proposal</h3>
          <p class="text-sm text-muted mt-4">You are within your 48-hour withdrawal window. No penalty applies.</p>
        </div>
        <button class="modal-close"
          onclick="document.getElementById('withdraw-modal').classList.add('hidden')">✕</button>
      </div>
      <div class="modal-body">
        <!-- PHP: $bidCount.' specialists have also bid' — context -->
        <div
          style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px;font-size:.875rem;">
          <strong>MENA Expansion — Cross-Border Contract Review</strong><br>
          <span class="text-muted">Your proposal of <span class="font-mono">$12,000</span> · Submitted Apr 12,
            2025</span>
        </div>
        <div class="form-group">
          <label class="form-label">Reason for Withdrawal <span class="text-muted font-mono"
              style="font-size:.7rem;font-weight:400;text-transform:none;letter-spacing:0;">Optional — helps us
              improve</span></label>
          <select class="form-control" name="withdraw_reason">
            <option value="">— Select a reason —</option>
            <option>I accepted another project</option>
            <option>My availability has changed</option>
            <option>Project scope doesn't match my expertise</option>
            <option>I reconsidered my pricing</option>
            <option>Other</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" onclick="document.getElementById('withdraw-modal').classList.add('hidden')">Keep
          My Proposal</button>
        <!-- PHP: action="/jobs/{id}/bid/{bid_id}/withdraw" method="DELETE" -->
        <button class="btn btn-danger" onclick="confirmWithdraw()">Confirm Withdrawal</button>
      </div>
    </div>
  </div>

  <!-- SUCCESS MODAL -->
  <div id="success-modal" class="modal-backdrop hidden">
    <div class="modal modal-sm" style="text-align:center;">
      <div class="modal-body" style="padding:48px 32px;">
        <div style="font-size:3rem;margin-bottom:20px;">✦</div>
        <h3 style="margin-bottom:10px;">Proposal Submitted</h3>
        <p class="text-sm text-muted mb-6">Your proposal for <strong>MENA Expansion — Cross-Border Contract
            Review</strong> has been sent to FinCorp Egypt.</p>
        <p class="text-sm text-muted mb-24">You have <strong>48 hours</strong> to withdraw. You'll be notified when
          reviewed or shortlisted.</p>
        <div class="font-mono text-xs text-muted mb-24">Ref: BID-NX-4821-DR · Submitted Apr 12, 14:22 GMT+2</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <a href="dashboard-freelancer.html" class="btn btn-primary" style="justify-content:center;">Back to
            Dashboard</a>
          <button class="btn btn-outline" style="justify-content:center;" onclick="showWithdrawalWidget()">View
            Withdrawal Window</button>
        </div>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast-stack" id="toast-stack"></div>

  <!-- ADD AVAILABILITY SLOT MODAL -->
  <div id="add-slot-modal" class="modal-backdrop hidden">
    <div class="modal modal-sm">
      <div class="modal-header">
        <h3>Add Availability Slot</h3>
        <button class="modal-close" onclick="closeAddSlotModal()">✕</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Day of Week</label>
          <select id="slot-day" class="form-control">
            <option>Monday</option>
            <option>Tuesday</option>
            <option>Wednesday</option>
            <option>Thursday</option>
            <option>Friday</option>
            <option>Saturday</option>
            <option>Sunday</option>
          </select>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">From</label>
            <select id="slot-start-time" class="form-control">
              <option>09:00</option>
              <option>10:00</option>
              <option>11:00</option>
              <option>12:00</option>
              <option>13:00</option>
              <option>14:00</option>
              <option>15:00</option>
              <option>16:00</option>
              <option>17:00</option>
              <option>18:00</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">To</label>
            <select id="slot-end-time" class="form-control">
              <option>10:00</option>
              <option>11:00</option>
              <option>12:00</option>
              <option>13:00</option>
              <option>14:00</option>
              <option>15:00</option>
              <option>16:00</option>
              <option>17:00</option>
              <option>18:00</option>
              <option>19:00</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" onclick="closeAddSlotModal()">Cancel</button>
        <button class="btn btn-primary" onclick="addAvailabilitySlot()">Add Slot</button>
      </div>
    </div>
  </div>

  <script>
    /* ── DROPDOWN TOGGLE ── */
    function toggleDD() {
      document.getElementById('user-dd').classList.toggle('hidden');
    }
    document.addEventListener('click', e => {
      if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
    });

    const CLIENT_BUDGET = 12000;
    const FEE_RATE = 0.065;
    let msMode = 'accept';
    let fileCount = 0;

    /* ── TABS / SYNC ── */
    function syncSummary() {
      // Cover letter length
      const cl = document.getElementById('cover-letter')?.value?.length || 0;
      document.getElementById('sum-cover').textContent = cl + ' chars';
      document.getElementById('sum-cover').className = 'summary-val' + (cl < 100 ? ' ' : '');

      // Total
      const total = getCurrentTotal();
      document.getElementById('sum-total').querySelector('.edited-marker') ||
        (document.getElementById('sum-total').innerHTML = '$' + total.toLocaleString() +
          (total !== CLIENT_BUDGET ? ' <span id="sum-edited-marker" class="edited-marker">edited</span>' : ''));
      document.getElementById('earn-bid').textContent = '$' + total.toLocaleString();
      const fee = Math.round(total * FEE_RATE);
      document.getElementById('earn-fee').textContent = '− $' + fee.toLocaleString();
      document.getElementById('earn-net').textContent = '$' + (total - fee).toLocaleString();

      // Duration
      const dur = [0, 1, 2].reduce((s, i) => s + (parseInt(document.getElementById('ms-dur-' + i)?.value) || 0), 0);
      document.getElementById('sum-duration').textContent = dur + ' days';
      document.getElementById('total-duration').value = dur;

      // Start date
      const sd = document.getElementById('start-date')?.value;
      document.getElementById('sum-start').textContent = sd ? new Date(sd).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Not set';

      // Files
      document.getElementById('sum-files').textContent = fileCount > 0 ? fileCount + ' attached' : 'None';

      // Per-milestone
      [0, 1, 2].forEach(i => {
        const d = document.getElementById('ms-dur-' + i)?.value || 0;
        const a = document.getElementById('ms-amt-' + i)?.value || 0;
        const el = document.getElementById('sum-ms-' + i);
        if (el) el.textContent = d + 'd · $' + Number(a).toLocaleString();
      });

      checkMsMatch();
      updateEffectiveRate();
      updateBidPosition();
    }

    function getCurrentTotal() {
      return parseInt(document.getElementById('bid-total')?.value) || 0;
    }

    function recalcTotal() {
      const msTotal = [0, 1, 2].reduce((s, i) => s + (parseInt(document.getElementById('ms-amt-' + i)?.value) || 0), 0);
      const el = document.getElementById('ms-total-display');
      if (el) el.textContent = '$' + msTotal.toLocaleString();
      const delta = msTotal - CLIENT_BUDGET;
      const badge = document.getElementById('ms-total-delta');
      if (badge) {
        if (delta !== 0) {
          badge.style.display = '';
          badge.className = 'ms-delta-badge ' + (delta > 0 ? 'up' : 'down');
          badge.textContent = (delta > 0 ? '+' : '') + '$' + Math.abs(delta).toLocaleString();
        } else { badge.style.display = 'none'; }
      }
      // Sync bid total to milestone sum
      const bidInput = document.getElementById('bid-total');
      if (bidInput) { bidInput.value = msTotal; updateDelta(); }
    }

    function updateDelta() {
      const val = getCurrentTotal();
      const diff = val - CLIENT_BUDGET;
      const pct = ((diff / CLIENT_BUDGET) * 100).toFixed(1);
      const badge = document.getElementById('delta-badge');
      const pctEl = document.getElementById('delta-pct');
      const deltaWrap = document.getElementById('budget-delta');
      if (!badge) return;
      deltaWrap.style.display = '';
      badge.textContent = (diff > 0 ? '+' : '') + '$' + Math.abs(diff).toLocaleString() + ' vs client budget';
      badge.className = 'ms-delta-badge ' + (diff > 0 ? 'up' : diff < 0 ? 'down' : 'neutral');
      if (pctEl) pctEl.textContent = '(' + (diff >= 0 ? '+' : '') + pct + '%)';
      // Show rationale if > 15%
      const rationaleGroup = document.getElementById('rationale-group');
      if (rationaleGroup) rationaleGroup.style.display = Math.abs(parseFloat(pct)) > 15 ? 'block' : 'none';
    }

    function updateEffectiveRate() {
      const total = getCurrentTotal();
      const days = [0, 1, 2].reduce((s, i) => s + (parseInt(document.getElementById('ms-dur-' + i)?.value) || 0), 0);
      const rate = days > 0 ? Math.round(total / days) : 0;
      const el = document.getElementById('effective-rate');
      if (el) el.value = '$' + rate.toLocaleString() + ' / day';
    }

    function checkMsMatch() {
      const msTotal = [0, 1, 2].reduce((s, i) => s + (parseInt(document.getElementById('ms-amt-' + i)?.value) || 0), 0);
      const bidTotal = getCurrentTotal();
      const el = document.getElementById('ms-total-mismatch');
      if (el) el.style.display = msTotal !== bidTotal && bidTotal > 0 ? 'block' : 'none';
    }

    /* ── MILESTONE TOGGLE ── */
    function toggleMs(i) {
      const body = document.getElementById('ms-body-' + i);
      const btn = document.querySelector(`#ms-item-${i} .btn-ghost`);
      const open = body.style.display === 'block';
      body.style.display = open ? 'none' : 'block';
      if (btn) btn.textContent = open ? 'Edit' : 'Done';
    }

    function syncMs(i) {
      const item = document.getElementById('ms-item-' + i);
      const orig = item.dataset;
      const name = document.getElementById('ms-name-' + i)?.value || '';
      const dur = document.getElementById('ms-dur-' + i)?.value || 0;
      const amt = document.getElementById('ms-amt-' + i)?.value || 0;
      const changed = name !== orig.originalName || parseInt(dur) !== parseInt(orig.originalDuration) || parseInt(amt) !== parseInt(orig.originalAmount);
      item.classList.toggle('edited', changed);
      document.getElementById('ms-badge-' + i).textContent = changed ? '✎ Edited' : 'Unchanged';
      document.getElementById('ms-display-' + i).textContent = name;

      // Delta row
      const deltaRow = document.getElementById('ms-delta-row-' + i);
      if (deltaRow) {
        if (changed) {
          const durDiff = parseInt(dur) - parseInt(orig.originalDuration);
          const amtDiff = parseInt(amt) - parseInt(orig.originalAmount);
          const parts = [];
          if (durDiff !== 0) parts.push(`<span class="ms-delta-badge ${durDiff > 0 ? 'up' : 'down'}" style="font-size:.7rem;">${durDiff > 0 ? '+' : ''}${durDiff}d</span>`);
          if (amtDiff !== 0) parts.push(`<span class="ms-delta-badge ${amtDiff > 0 ? 'up' : 'down'}" style="font-size:.7rem;">${amtDiff > 0 ? '+' : ''}\$${Math.abs(amtDiff).toLocaleString()}</span>`);
          deltaRow.style.display = parts.length ? 'flex' : 'none';
          deltaRow.style.gap = '6px';
          deltaRow.innerHTML = '<span class="text-xs text-muted">Changes from original:</span>' + parts.join('');
        } else { deltaRow.style.display = 'none'; }
      }

      // Edit count badge
      const editedCount = [0, 1, 2].filter(j => document.getElementById('ms-item-' + j)?.classList.contains('edited')).length;
      const cntEl = document.getElementById('ms-edit-count');
      if (cntEl) { cntEl.textContent = editedCount > 0 ? editedCount + ' milestone(s) edited' : ''; cntEl.style.display = editedCount > 0 ? '' : 'none'; }
    }

    /* ── MILESTONE MODE ── */
    function setMsMode(mode) {
      msMode = mode;
      const isCustom = mode === 'custom';
      document.getElementById('btn-accept').style.background = !isCustom ? 'var(--gold-pale)' : 'transparent';
      document.getElementById('btn-accept').style.color = !isCustom ? 'var(--ink)' : 'var(--ink-muted)';
      document.getElementById('btn-custom').style.background = isCustom ? 'var(--gold-pale)' : 'transparent';
      document.getElementById('btn-custom').style.color = isCustom ? 'var(--ink)' : 'var(--ink-muted)';
      document.getElementById('btn-add-ms').style.display = isCustom ? 'flex' : 'none';
      document.querySelectorAll('.ms-edit-item .btn-ghost').forEach(b => { b.style.display = isCustom ? '' : 'none'; });
      if (!isCustom) {
        // Reset all to originals
        [0, 1, 2].forEach(i => {
          const item = document.getElementById('ms-item-' + i);
          if (!item) return;
          document.getElementById('ms-name-' + i).value = item.dataset.originalName;
          document.getElementById('ms-dur-' + i).value = item.dataset.originalDuration;
          document.getElementById('ms-amt-' + i).value = item.dataset.originalAmount;
          document.getElementById('ms-body-' + i).style.display = 'none';
          syncMs(i);
        });
        recalcTotal(); syncSummary();
      }
    }

    let addedMsCount = 3;
    function addMilestone() {
      const list = document.getElementById('ms-list');
      const d = document.createElement('div');
      const idx = addedMsCount;
      d.className = 'ms-edit-item open';
      d.id = 'ms-item-' + idx;
      d.dataset.originalName = '';
      d.dataset.originalDuration = '0';
      d.dataset.originalAmount = '0';
      d.innerHTML = `<div class="ms-edit-header"><div class="ms-num">${idx + 1}</div><div style="flex:1;"><div style="font-weight:700;font-size:.875rem;" id="ms-display-${idx}">New Milestone</div></div><span class="ms-original-badge" id="ms-badge-${idx}" style="background:var(--gold-pale);border-color:var(--gold-light);color:#7A5C10;">+ New</span><button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('.ms-edit-item').remove();recalcTotal();syncSummary()">🗑 Remove</button></div><div class="ms-edit-body" id="ms-body-${idx}" style="display:block;"><div class="ms-field-grid"><div class="form-group" style="margin:0;"><label class="form-label">Milestone Name</label><input type="text" class="form-control" id="ms-name-${idx}" name="milestones[${idx}][name]" placeholder="Phase name…" oninput="syncMs(${idx})"></div><div class="form-group" style="margin:0;"><label class="form-label">Duration</label><div class="input-affix-wrap"><input type="number" class="form-control has-suffix" id="ms-dur-${idx}" name="milestones[${idx}][duration]" value="14" min="1" max="180" oninput="syncMs(${idx});syncSummary()"><span class="input-suffix">days</span></div></div><div class="form-group" style="margin:0;"><label class="form-label">Amount</label><div class="input-affix-wrap"><span class="input-prefix">$</span><input type="number" class="form-control has-prefix" id="ms-amt-${idx}" name="milestones[${idx}][amount]" value="0" min="100" step="50" oninput="syncMs(${idx});recalcTotal();syncSummary()"></div></div></div><div class="form-group mt-12" style="margin-bottom:0;"><label class="form-label">Deliverables</label><textarea class="form-control" rows="2" name="milestones[${idx}][deliverables]" placeholder="List deliverables for this phase…"></textarea></div></div>`;
      list.appendChild(d);
      addedMsCount++;
    }

    /* ── AVAILABILITY SLOTS ── */
    let availabilitySlots = [];

    function openAddSlotModal() {
      document.getElementById('add-slot-modal').classList.remove('hidden');
    }

    function closeAddSlotModal() {
      document.getElementById('add-slot-modal').classList.add('hidden');
    }

    function addAvailabilitySlot() {
      const day = document.getElementById('slot-day').value;
      const startTime = document.getElementById('slot-start-time').value;
      const endTime = document.getElementById('slot-end-time').value;

      if (!day || !startTime || !endTime) {
        showToast('Please select day and times.', 'warn');
        return;
      }

      const shortDay = day.substring(0, 3);
      const slot = `${shortDay} ${startTime}–${endTime}`;
      availabilitySlots.push(slot);

      renderSlots();
      closeAddSlotModal();
      showToast('Availability slot added.', 'success');
    }

    function removeAvailabilitySlot(index) {
      availabilitySlots.splice(index, 1);
      renderSlots();
    }

    function renderSlots() {
      const list = document.getElementById('avail-slots-list');
      const input = document.getElementById('availability-slots-input');

      list.innerHTML = '';
      availabilitySlots.forEach((slot, index) => {
        const item = document.createElement('div');
        item.className = 'avail-slot-item';
        item.innerHTML = `
          <span class="avail-slot-item-text">${slot}</span>
          <button type="button" class="avail-slot-item-remove" onclick="removeAvailabilitySlot(${index})">✕</button>
        `;
        list.appendChild(item);
      });

      if (input) input.value = availabilitySlots.join(',');
    }

    /* ── FILE UPLOAD ── */
    function handleFiles(input) { processFiles(Array.from(input.files)); }
    function handleDrop(e) { e.preventDefault(); document.getElementById('file-drop-zone').classList.remove('drag-over'); processFiles(Array.from(e.dataTransfer.files)); }
    function processFiles(files) {
      const list = document.getElementById('file-list');
      files.slice(0, 5 - fileCount).forEach(f => {
        if (f.size > 10485760) { showToast('File "' + f.name + '" exceeds 10MB limit.', 'warn'); return; }
        fileCount++;
        const size = f.size > 1048576 ? (f.size / 1048576).toFixed(1) + ' MB' : (f.size / 1024).toFixed(0) + ' KB';
        const ext = f.name.split('.').pop().toLowerCase();
        const icons = { pdf: '📄', docx: '📝', zip: '📦' };
        const row = document.createElement('div');
        row.className = 'file-row';
        row.innerHTML = `<div class="file-row-icon">${icons[ext] || '📁'}</div><span style="flex:1;font-weight:600;font-size:.875rem;">${f.name}</span><span style="font-family:var(--font-mono);font-size:.75rem;color:var(--ink-muted);">${size}</span><span class="badge badge-pending" style="font-size:.625rem;">Staged</span><button type="button" style="background:none;border:none;cursor:pointer;color:var(--rust);font-size:.875rem;padding:0 4px;" onclick="this.parentNode.remove();fileCount--;syncSummary()">✕</button>`;
        list.appendChild(row);
        syncSummary();
      });
    }

    /* ── VALIDATION & SUBMIT ── */
    function syncAgree() {
      const a = document.getElementById('agree-accurate')?.checked;
      const q = document.getElementById('agree-qualified')?.checked;
      const t = document.getElementById('agree-terms')?.checked;
      const btn = document.getElementById('submit-btn');
      const all = a && q && t;
      btn.disabled = !all;
      btn.style.opacity = all ? '1' : '.5';
      btn.style.cursor = all ? 'pointer' : 'not-allowed';
    }

    function handleSubmit(e) {
      e.preventDefault();
      let valid = true;

      const cl = document.getElementById('cover-letter');
      if (!cl?.value || cl.value.trim().length < 100) {
        cl?.classList.add('invalid');
        document.getElementById('err-cover').classList.add('show');
        cl?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        valid = false;
      } else { cl.classList.remove('invalid'); document.getElementById('err-cover').classList.remove('show'); }

      const bt = document.getElementById('bid-total');
      if (!bt?.value || parseInt(bt.value) < 500) {
        bt?.classList.add('invalid');
        document.getElementById('err-total').classList.add('show');
        if (valid) bt?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        valid = false;
      } else { bt.classList.remove('invalid'); document.getElementById('err-total').classList.remove('show'); }

      const msTotal = [0, 1, 2].reduce((s, i) => s + (parseInt(document.getElementById('ms-amt-' + i)?.value) || 0), 0);
      if (msTotal !== parseInt(bt?.value || 0)) {
        document.getElementById('ms-total-mismatch').style.display = 'block';
        if (valid) document.getElementById('ms-total-mismatch').scrollIntoView({ behavior: 'smooth', block: 'center' });
        valid = false;
      }

      const ratGroup = document.getElementById('rationale-group');
      if (ratGroup?.style.display !== 'none') {
        const rat = document.getElementById('bid-rationale');
        if (!rat?.value || rat.value.trim().length < 20) {
          rat?.classList.add('invalid');
          document.getElementById('err-rationale').classList.add('show');
          if (valid) rat?.scrollIntoView({ behavior: 'smooth', block: 'center' });
          valid = false;
        } else { rat.classList.remove('invalid'); document.getElementById('err-rationale').classList.remove('show'); }
      }

      if (!valid) return false;
      // PHP: form submits to POST /jobs/{id}/bid
      document.getElementById('success-modal').classList.remove('hidden');
      return false;
    }

    function saveDraft() {
      showToast('Draft saved. You can return to this proposal from your dashboard.', 'info');
    }

    function updateBidPosition() {
      const val = getCurrentTotal();
      const low = 9800, high = 18000;
      const pct = Math.min(Math.max(((val - low) / (high - low)) * 100, 0), 100);
      const m = document.getElementById('your-marker');
      if (m) m.style.left = pct + '%';
      const lbl = document.getElementById('bid-pos-label');
      if (lbl) {
        if (val < 9800) lbl.textContent = 'Below lowest bid';
        else if (val < 11000) lbl.textContent = 'Competitive — below median';
        else if (val < 12500) lbl.textContent = 'At or near client budget';
        else if (val < 14000) lbl.textContent = 'Above client budget';
        else lbl.textContent = 'Significantly above budget';
      }
    }

    /* ── WITHDRAWAL ── */
    function confirmWithdraw() {
      document.getElementById('withdraw-modal').classList.add('hidden');
      showToast('Proposal withdrawn successfully. Your response-rate score is unaffected.', 'info');
      setTimeout(() => { window.location.href = 'dashboard-freelancer.html'; }, 2500);
    }

    function showWithdrawalWidget() {
      document.getElementById('success-modal').classList.add('hidden');
      document.getElementById('withdrawal-widget').style.display = '';
      startWithdrawTimer(48 * 3600);
    }

    function startWithdrawTimer(seconds) {
      let s = seconds;
      const fill = document.getElementById('clock-fill');
      const timer = document.getElementById('withdraw-timer');
      const total = 48 * 3600;
      const tick = () => {
        if (s <= 0) { timer.textContent = 'Window closed'; fill.style.width = '0%'; return; }
        const h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60), sec = s % 60;
        timer.textContent = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(sec).padStart(2, '0')} remaining`;
        const pct = (s / total) * 100;
        fill.style.width = pct + '%';
        if (pct < 25) { fill.classList.add('low'); document.getElementById('withdrawal-widget').classList.add('warning'); }
        s--;
      };
      tick();
      setInterval(tick, 1000);
    }

    /* ── TOAST ── */
    function showToast(msg, type = 'success') {
      const s = document.getElementById('toast-stack');
      const icons = { success: '✓', warn: '⚠', info: 'ℹ' };
      const cls = { success: 'success', warn: 'warning', info: '' };
      s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type === 'warn' ? 'Required' : type === 'info' ? 'Notice' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
      setTimeout(() => s.innerHTML = '', 4500);
    }

    /* ── CHAR COUNTER ── */
    function countChars(el, max, id) {
      const n = el.value.length;
      const c = document.getElementById(id);
      if (!c) return;
      c.textContent = `${n} / ${max}`;
      c.className = 'char-counter' + (n > max ? ' over' : n > max * .9 ? ' warn' : '');
      syncSummary();
    }

    /* ── INIT ── */
    syncSummary();
    updateDelta();
    setMsMode('accept');
    document.getElementById('start-date')?.addEventListener('change', syncSummary);
    document.getElementById('bid-total')?.addEventListener('input', syncSummary);
  </script>
</body>

</html>