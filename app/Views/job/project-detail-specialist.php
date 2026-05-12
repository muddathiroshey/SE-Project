<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- PHP: <title><?= htmlspecialchars($project['title']) ?> — Nexus</title> -->
  <title>Predictive Churn Model — Nexus</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/project-detail-specialist.css">
</head>

<body>

  <?php require __DIR__ . '/../partials/topnav.php'; ?>


  <div class="proj-header-band">
    <div style="max-width:1280px;margin:0 auto;padding:0 40px;">
      <div class="flex justify-between items-start">
        <div>
          <div class="proj-status-bar">
            <!-- PHP: status badge -->
            <span class="badge badge-verified badge-dot" style="font-size:.7rem;">Active</span>
            <span class="badge badge-gold" style="font-size:.7rem;">Phase 2 of 5</span>
            <!-- PHP: $contract['ref'] -->
            <span class="badge badge-default font-mono" style="font-size:.7rem;">CON-NX-3812</span>
          </div>
          <!-- PHP: htmlspecialchars($project['title']) -->
          <h2 class="proj-title">Predictive Churn Model — FinCorp Q2</h2>
          <div class="proj-meta-row">
            <span>Client: <strong>FinCorp Egypt</strong></span>
            <span>·</span>
            <span>Data Science · ML</span>
            <span>·</span>
            <!-- PHP: date('M j, Y', $contract['started_at']) -->
            <span>Started Apr 3, 2025</span>
            <span>·</span>
            <!-- PHP: $contract['duration_days'].' days est.' -->
            <span>42 days est. delivery</span>
            <span>·</span>
            <a href="messages.html" style="color:var(--gold);font-weight:700;font-size:.8125rem;">💬 2 unread
              messages</a>
          </div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <!-- PHP: '$'.number_format($contract['total_value']) -->
          <div style="font-family:var(--font-display);font-size:1.8rem;font-weight:300;color:var(--ink);">$8,400</div>
          <div style="font-size:.75rem;color:var(--ink-muted);font-family:var(--font-mono);">Total Contract Value</div>
          <div style="margin-top:6px;display:flex;gap:8px;justify-content:flex-end;">
            <a href="client-profile-public.html" class="btn btn-outline btn-sm">View Client</a>
            <button class="btn btn-primary btn-sm" onclick="openSubmitModal()">📤 Submit Milestone</button>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- ══════════ MAIN CONTENT ══════════ -->
  <div class="proj-shell">

    <!-- ─── LEFT: WORK AREA ─── -->
    <div>

      <!-- ════ ACTIVE MILESTONE ════ -->
      <div id="ms-2">
        <div class="sec-label">Current Milestone — Phase 2</div>

        <div class="active-ms-card">
          <div class="amc-header">
            <div class="prog-circle-wrap">
              <svg width="56" height="56" viewBox="0 0 56 56">
                <circle cx="28" cy="28" r="22" fill="none" stroke="rgba(201,168,76,.15)" stroke-width="5" />
                <!-- PHP: stroke-dasharray = (2*pi*22) * ($activeMilestone['progress_pct']/100) -->
                <circle cx="28" cy="28" r="22" fill="none" stroke="#C9A84C" stroke-width="5" stroke-dasharray="94 138"
                  stroke-linecap="round" />
              </svg>
              <div class="pct-label">68%</div>
            </div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:1rem;color:var(--ink);margin-bottom:3px;">Phase 2 — Feature
                Engineering &amp; Model Training</div>
              <div style="display:flex;gap:12px;flex-wrap:wrap;font-size:.8125rem;color:var(--ink-muted);">
                <!-- PHP: $activeMilestone['amount'] -->
                <span class="font-mono" style="color:var(--gold);font-weight:700;">$3,360 escrowed</span>
                <span>·</span>
                <!-- PHP: $activeMilestone['deadline'] -->
                <span>Due <strong>Apr 19, 2025</strong></span>
                <span>·</span>
                <!-- PHP: $activeMilestone['duration_days'].' day phase' -->
                <span>21-day phase</span>
              </div>
            </div>
            <div class="flex items-center gap-8" style="flex-shrink:0;">
              <div class="dl-chip dl-soon">⏱ 4 days left</div>
              <button class="btn btn-primary btn-sm" onclick="openSubmitModal()">📤 Submit</button>
            </div>
          </div>

          <div class="amc-body">

            <!-- DELIVERABLES QA CHECKLIST -->
            <div class="sec-label" style="margin-bottom:12px;">Deliverables Checklist <span
                style="font-family:var(--font-mono);font-size:.7rem;color:var(--ink-muted);text-transform:none;letter-spacing:0;font-weight:400;margin-left:6px;">Must
                complete all before submitting</span></div>

            <div
              style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;margin-bottom:16px;">
              <!-- PHP: foreach($deliverables as $d): -->
              <div class="del-row" style="padding:11px 16px;">
                <div class="del-dot done" onclick="toggleDel(this)">✓</div>
                <div class="del-label done">Feature importance analysis — SHAP explainability report (PDF with top-20
                  features)</div>
                <span class="badge badge-verified" style="font-size:.625rem;flex-shrink:0;">Submitted</span>
              </div>
              <div class="del-row" style="padding:11px 16px;">
                <div class="del-dot" onclick="toggleDel(this)"></div>
                <div class="del-label">Trained XGBoost baseline + Random Forest comparison (Jupyter Notebook, clean
                  &amp; commented)</div>
                <span class="badge badge-default" style="font-size:.625rem;flex-shrink:0;">Not Submitted</span>
              </div>
              <div class="del-row" style="padding:11px 16px;">
                <div class="del-dot" onclick="toggleDel(this)"></div>
                <div class="del-label">Cross-validation report + hyperparameter tuning log (all folds documented)</div>
                <span class="badge badge-default" style="font-size:.625rem;flex-shrink:0;">Not Submitted</span>
              </div>
              <div class="del-row" style="padding:11px 16px;">
                <div class="del-dot" onclick="toggleDel(this)"></div>
                <div class="del-label">Updated README with setup instructions and data schema documentation</div>
                <span class="badge badge-default" style="font-size:.625rem;flex-shrink:0;">Not Submitted</span>
              </div>
            </div>

            <!-- PROGRESS BAR -->
            <div class="flex items-center gap-12 mb-20">
              <div class="progress-bar" style="flex:1;height:8px;">
                <div class="progress-fill" style="width:68%;"></div>
              </div>
              <span style="font-size:.8125rem;font-family:var(--font-mono);color:var(--ink-muted);white-space:nowrap;">2
                of 4 deliverables addressed</span>
            </div>

            <!-- WIP SNAPSHOTS -->
            <div class="sec-label" style="margin-bottom:10px;">WIP Snapshots <span
                style="font-size:.7rem;color:var(--ink-faint);text-transform:none;letter-spacing:0;font-weight:400;margin-left:6px;">Auto-archived
                every 24h · Visible to client</span></div>
            <!-- PHP: foreach($wip_snapshots as $snap): -->
            <div class="wip-row">
              <div class="wip-icon">📓</div>
              <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:.8125rem;">feature_engineering_v2.ipynb</div>
                <div class="text-xs text-muted font-mono">Apr 14 · 09:42 · 12.4 MB · Manual upload</div>
              </div>
              <button class="btn btn-ghost btn-sm">⬇</button>
              <button class="btn btn-ghost btn-icon" style="color:var(--rust);font-size:.75rem;"
                onclick="this.closest('.wip-row').remove()">✕</button>
            </div>
            <div class="wip-row">
              <div class="wip-icon">📓</div>
              <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:.8125rem;">model_comparison_draft.ipynb</div>
                <div class="text-xs text-muted font-mono">Apr 15 · 22:17 · 8.4 MB · Auto-archived</div>
              </div>
              <button class="btn btn-ghost btn-sm">⬇</button>
              <button class="btn btn-ghost btn-icon" style="color:var(--rust);font-size:.75rem;"
                onclick="this.closest('.wip-row').remove()">✕</button>
            </div>
            <div class="wip-row">
              <div class="wip-icon">📄</div>
              <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:.8125rem;">shap_analysis_v1.pdf</div>
                <div class="text-xs text-muted font-mono">Apr 13 · 16:00 · 3.1 MB · Manual upload · <span
                    style="color:var(--sage);">Accepted deliverable</span></div>
              </div>
              <button class="btn btn-ghost btn-sm">⬇</button>
            </div>
            <div style="display:flex;gap:8px;margin-top:10px;">
              <button class="btn btn-outline btn-sm"
                onclick="document.getElementById('wip-modal').classList.remove('hidden')">📁 Upload WIP
                Snapshot</button>
              <button class="btn btn-ghost btn-sm text-xs text-muted" style="font-size:.75rem;">View All 8
                Snapshots</button>
            </div>

            <!-- REVISION TRACKER -->
            <div class="rev-tracker">
              <div>
                <div
                  style="font-size:.65rem;text-transform:uppercase;letter-spacing:.1em;font-weight:700;color:var(--ink-muted);margin-bottom:6px;font-family:var(--font-body);">
                  Revision Tracker — Phase 2</div>
                <div style="display:flex;align-items:center;gap:8px;">
                  <div class="rev-dots">
                    <div class="rev-dot used" title="Revision 1 — used"></div>
                    <div class="rev-dot" title="Revision 2 — available"></div>
                  </div>
                  <span style="font-size:.8125rem;color:var(--ink-mid);">1 of 2 free revisions used</span>
                </div>
              </div>
              <div style="text-align:right;font-size:.8125rem;">
                <div class="text-xs text-muted">Additional revisions billed at</div>
                <div class="font-mono font-bold">$140 each</div>
              </div>
            </div>

          </div><!-- end amc-body -->
        </div><!-- end active-ms-card -->
      </div>

      <!-- ════ PAST MILESTONES ════ -->
      <div id="ms-1" style="margin-bottom:24px;">
        <div class="sec-label">Completed Milestones</div>

        <div class="past-ms-card">
          <div class="past-ms-header" onclick="togglePastMs(this)">
            <div
              style="width:28px;height:28px;border-radius:50%;background:var(--sage);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:700;font-family:var(--font-mono);flex-shrink:0;">
              ✓</div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.9375rem;">Phase 1 — Exploratory Data Analysis &amp; Baseline Models
              </div>
              <div style="font-size:.8125rem;color:var(--ink-muted);margin-top:2px;">Approved Apr 12, 2025 · <span
                  style="color:var(--sage);font-weight:700;">$1,680 cleared</span></div>
            </div>
            <span class="badge badge-verified badge-dot" style="font-size:.65rem;flex-shrink:0;">Approved</span>
            <span style="color:var(--ink-faint);font-size:.75rem;margin-left:8px;">▾</span>
          </div>
          <div class="past-ms-body" id="past-ms-body-0">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:16px;">
              <div
                style="padding:10px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;">
                <div class="text-xs text-muted mb-4">Duration</div>
                <div class="font-mono font-bold">12 days</div>
              </div>
              <div
                style="padding:10px;background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);text-align:center;">
                <div class="text-xs text-muted mb-4">Amount</div>
                <div class="font-mono font-bold" style="color:var(--sage);">$1,680</div>
              </div>
              <div
                style="padding:10px;background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);text-align:center;">
                <div class="text-xs text-muted mb-4">Cleared</div>
                <div class="font-mono font-bold" style="color:var(--sage);">Apr 12</div>
              </div>
            </div>
            <div
              style="font-size:.65rem;text-transform:uppercase;letter-spacing:.1em;font-weight:700;color:var(--ink-muted);margin-bottom:8px;font-family:var(--font-body);">
              Submitted Deliverables</div>
            <div class="del-row" style="padding:8px 0;">
              <div class="del-dot done" style="cursor:default;">✓</div>
              <div class="del-label done">EDA report — 7 key patterns, 3 segments identified (PDF)</div>
            </div>
            <div class="del-row" style="padding:8px 0;">
              <div class="del-dot done" style="cursor:default;">✓</div>
              <div class="del-label done">Baseline logistic regression + decision tree comparison (Notebook)</div>
            </div>
            <div class="del-row" style="padding:8px 0;border-bottom:none;">
              <div class="del-dot done" style="cursor:default;">✓</div>
              <div class="del-label done">Data quality assessment + missing value strategy (Markdown report)</div>
            </div>
            <!-- CLIENT FEEDBACK -->
            <div
              style="margin-top:14px;background:var(--ivory-card);border:1px solid var(--border);border-left:3px solid var(--sage);border-radius:0 var(--radius-sm) var(--radius-sm) 0;padding:12px 14px;font-size:.875rem;font-family:var(--font-display);font-style:italic;color:var(--ink-mid);">
              "Excellent Phase 1 delivery — the segment analysis uncovered the March cohort anomaly our team had missed.
              Notebooks are clean and well-documented."
            </div>
            <div class="flex items-center gap-8 mt-8">
              <div class="avatar avatar-sm">AT</div>
              <span class="text-xs text-muted">Amira Tawfik · FinCorp Egypt · Approved Apr 12</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ════ LOCKED MILESTONES ════ -->
      <div id="ms-locked" style="margin-bottom:24px;">
        <div class="sec-label">Upcoming Milestones</div>

        <!-- MS 3 -->
        <div class="locked-ms-card">
          <div
            style="width:28px;height:28px;border-radius:50%;background:var(--ivory-deep);border:1.5px dashed var(--border-dark);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-family:var(--font-mono);color:var(--ink-faint);flex-shrink:0;">
            3</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;color:var(--ink-mid);">Phase 3 — Model Evaluation &amp;
              Validation</div>
            <div class="text-xs text-muted font-mono mt-2">$840 · 7 days · Unlocks after Phase 2 approval</div>
          </div>
          <span class="badge badge-default" style="font-size:.65rem;flex-shrink:0;">Locked</span>
        </div>
        <!-- MS 4 -->
        <div class="locked-ms-card">
          <div
            style="width:28px;height:28px;border-radius:50%;background:var(--ivory-deep);border:1.5px dashed var(--border-dark);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-family:var(--font-mono);color:var(--ink-faint);flex-shrink:0;">
            4</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;color:var(--ink-mid);">Phase 4 — Production Pipeline &amp;
              Monitoring</div>
            <div class="text-xs text-muted font-mono mt-2">$1,680 · 14 days · Unlocks after Phase 3 approval</div>
          </div>
          <span class="badge badge-default" style="font-size:.65rem;flex-shrink:0;">Locked</span>
        </div>
        <!-- MS 5 -->
        <div class="locked-ms-card">
          <div
            style="width:28px;height:28px;border-radius:50%;background:var(--ivory-deep);border:1.5px dashed var(--border-dark);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-family:var(--font-mono);color:var(--ink-faint);flex-shrink:0;">
            5</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;color:var(--ink-mid);">Phase 5 — Handoff, Documentation &amp;
              Training</div>
            <div class="text-xs text-muted font-mono mt-2">$840 · 5 days · Unlocks after Phase 4 approval</div>
          </div>
          <span class="badge badge-default" style="font-size:.65rem;flex-shrink:0;">Locked</span>
        </div>
      </div>

      <!-- CONTRACT DETAILS -->
      <div class="sec-label">Contract Terms</div>
      <div
        style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;margin-bottom:36px;">
        <div class="fee-row" style="padding:11px 18px;"><span class="fee-lbl">Contract Reference</span><span
            class="fee-val font-mono">CON-NX-3812</span></div>
        <div class="fee-row" style="padding:11px 18px;"><span class="fee-lbl">NDA</span><span class="fee-val">Standard
            Nexus · 2yr · Signed Apr 3, 2025</span></div>
        <div class="fee-row" style="padding:11px 18px;"><span class="fee-lbl">Free Revisions</span><span
            class="fee-val">2 per milestone · Additional $140 each</span></div>
        <div class="fee-row" style="padding:11px 18px;"><span class="fee-lbl">Auto-Approval Window</span><span
            class="fee-val">72h after submission — then auto-clears</span></div>
        <div class="fee-row" style="padding:11px 18px;"><span class="fee-lbl">Dispute Window</span><span
            class="fee-val">14 days after final approval</span></div>
        <div class="fee-row" style="padding:11px 18px;border:none;"><span class="fee-lbl">Governing Law</span><span
            class="fee-val">Egyptian Civil Law · Cairo jurisdiction</span></div>
      </div>

    </div><!-- end left col -->

    <!-- ─── RIGHT: FUNDS + PROJECT INFO ─── -->
    <div>

      <!-- ════ FUNDS PANEL ════ -->
      <div class="funds-band">
        <div
          style="font-size:.6rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:rgba(247,244,239,.35);font-family:var(--font-body);margin-bottom:16px;position:relative;z-index:1;">
          Project Funds — CON-NX-3812</div>
        <div class="funds-grid" style="margin-bottom:20px;">
          <div class="fund-cell" style="padding-left:0;">
            <!-- PHP: '$'.number_format($funds['cleared']) -->
            <div class="fund-val" style="color:var(--sage);">$1,680</div>
            <div class="fund-lbl">Cleared</div>
            <div class="fund-sub" style="color:rgba(134,167,129,.8);">Phase 1 — paid</div>
          </div>
          <div class="funds-divider"></div>
          <div class="fund-cell">
            <!-- PHP: '$'.number_format($funds['escrowed']) -->
            <div class="fund-val" style="color:var(--gold);">$3,360</div>
            <div class="fund-lbl">In Escrow</div>
            <div class="fund-sub" style="color:rgba(201,168,76,.7);">Phase 2 locked</div>
          </div>
        </div>
        <div style="border-top:1px solid rgba(247,244,239,.12);padding-top:16px;position:relative;z-index:1;">
          <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:6px;">
            <span style="color:rgba(247,244,239,.45);">Remaining Locked (Phases 3–5)</span>
            <span style="font-family:var(--font-mono);color:rgba(247,244,239,.6);">$3,360</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:6px;">
            <span style="color:rgba(247,244,239,.45);">On Hold (None)</span>
            <span style="font-family:var(--font-mono);color:rgba(247,244,239,.4);">$0</span>
          </div>
          <div style="height:1px;background:rgba(247,244,239,.12);margin:10px 0;"></div>
          <div style="display:flex;justify-content:space-between;font-size:.875rem;font-weight:700;">
            <span style="color:rgba(247,244,239,.7);">Total Contract</span>
            <span style="font-family:var(--font-mono);color:var(--ivory);">$8,400</span>
          </div>
        </div>
      </div>

      <!-- COOLING PERIOD (Phase 1) -->
      <!-- PHP: if($funds['cooling'] > 0): -->
      <div class="cooling-wrap">
        <div class="flex justify-between items-center mb-6">
          <div><strong>Phase 1 — Cleared</strong></div>
          <span class="badge badge-verified" style="font-size:.65rem;">✓ Released</span>
        </div>
        <div style="font-size:.8rem;color:rgba(26,74,138,.8);">$1,680 cleared to your wallet Apr 12. Processing to your
          bank account.</div>
      </div>

      <!-- PHASE 2 ESCROW DETAIL -->
      <div
        style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:16px 18px;margin-bottom:14px;">
        <div
          style="font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;font-weight:700;color:var(--ink-muted);margin-bottom:12px;font-family:var(--font-body);">
          Phase 2 Escrow Detail</div>
        <div class="escrow-row">
          <div class="er-dot" style="background:var(--gold);"></div>
          <div style="flex:1;">
            <div style="font-weight:700;">Feature Engineering &amp; Model Training</div>
            <div class="text-xs text-muted">Client locked on contract signing · Releases on approval</div>
          </div>
          <span class="er-amt" style="color:var(--gold);">$3,360</span>
        </div>
        <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
          <div class="fee-row" style="padding:5px 0;"><span class="fee-lbl" style="font-size:.8125rem;">Phase 2
              Gross</span><span class="fee-val font-mono" style="font-size:.8125rem;">$3,360</span></div>
          <div class="fee-row" style="padding:5px 0;"><span class="fee-lbl" style="font-size:.8125rem;">Platform Fee
              (6.5%)</span><span class="fee-val font-mono" style="font-size:.8125rem;color:var(--rust);">−$219</span>
          </div>
          <div style="display:flex;justify-content:space-between;padding-top:8px;font-weight:700;font-size:.9375rem;">
            <span>You Receive</span>
            <span class="font-mono" style="color:var(--sage);">$3,141</span>
          </div>
          <div class="text-xs text-muted font-mono mt-6">Released within 24h of client approval or 72h auto-approval
          </div>
        </div>
      </div>

      <!-- FULL PROJECT EARNINGS BREAKDOWN -->
      <div
        style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:16px 18px;margin-bottom:14px;">
        <div
          style="font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;font-weight:700;color:var(--ink-muted);margin-bottom:12px;font-family:var(--font-body);">
          All Milestones — Earnings Forecast</div>
        <!-- PHP: foreach($milestones as $ms): -->
        <div class="fee-row" style="padding:7px 0;">
          <span class="fee-lbl" style="display:flex;align-items:center;gap:6px;"><span
              style="color:var(--sage);font-size:.8rem;">✓</span> Phase 1</span>
          <span class="fee-val font-mono" style="color:var(--sage);">$1,571 <span
              style="font-size:.7rem;font-weight:400;color:var(--ink-faint);">(net)</span></span>
        </div>
        <div class="fee-row" style="padding:7px 0;">
          <span class="fee-lbl" style="display:flex;align-items:center;gap:6px;"><span
              style="color:var(--gold);font-size:.8rem;">◉</span> Phase 2</span>
          <span class="fee-val font-mono" style="color:var(--gold);">$3,141 <span
              style="font-size:.7rem;font-weight:400;color:var(--ink-faint);">pending</span></span>
        </div>
        <div class="fee-row" style="padding:7px 0;">
          <span class="fee-lbl" style="display:flex;align-items:center;gap:6px;"><span
              style="color:var(--ink-faint);font-size:.8rem;">○</span> Phase 3</span>
          <span class="fee-val font-mono" style="color:var(--ink-muted);">$786</span>
        </div>
        <div class="fee-row" style="padding:7px 0;">
          <span class="fee-lbl" style="display:flex;align-items:center;gap:6px;"><span
              style="color:var(--ink-faint);font-size:.8rem;">○</span> Phase 4</span>
          <span class="fee-val font-mono" style="color:var(--ink-muted);">$1,571</span>
        </div>
        <div class="fee-row" style="padding:7px 0;">
          <span class="fee-lbl" style="display:flex;align-items:center;gap:6px;"><span
              style="color:var(--ink-faint);font-size:.8rem;">○</span> Phase 5</span>
          <span class="fee-val font-mono" style="color:var(--ink-muted);">$786</span>
        </div>
        <div style="height:1px;background:var(--border);margin:10px 0;"></div>
        <div style="display:flex;justify-content:space-between;font-weight:700;font-size:.9375rem;">
          <span>Total Net (after 6.5%)</span>
          <span class="font-mono" style="color:var(--sage);">$7,855</span>
        </div>
        <div class="text-xs text-muted font-mono mt-4">$1,571 cleared · $3,141 pending · $3,143 upcoming</div>
      </div>

      <!-- CLIENT CARD -->
      <div class="client-mini-card">
        <div
          style="font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;font-weight:700;color:var(--ink-muted);margin-bottom:12px;font-family:var(--font-body);">
          Your Client</div>
        <div class="flex items-center gap-12 mb-12">
          <div class="avatar avatar-sm"
            style="background:var(--ink);color:var(--gold);font-size:.8rem;font-weight:700;">FC</div>
          <div>
            <div style="font-weight:700;font-size:.9375rem;">FinCorp Egypt</div>
            <div class="text-xs text-muted">Amira Tawfik · Head of Analytics</div>
          </div>
          <span class="badge badge-verified badge-dot" style="font-size:.65rem;margin-left:auto;">Verified</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;font-size:.8125rem;">
          <div class="flex justify-between"><span class="text-muted">Payment Reliability</span><span
              style="color:var(--sage);font-weight:700;">100%</span></div>
          <div class="flex justify-between"><span class="text-muted">Avg. Approval Speed</span><span
              style="color:var(--sage);font-weight:700;">38h</span></div>
          <div class="flex justify-between"><span class="text-muted">Response Time</span><span>~2h</span></div>
        </div>
        <div style="display:flex;gap:8px;margin-top:12px;">
          <a href="messages.html" class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">💬 Message</a>
          <a href="client-profile-public.html" class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;">View
            Profile</a>
        </div>
      </div>

      <!-- QUICK ACTIONS -->
      <div style="display:flex;flex-direction:column;gap:8px;">
        <a href="dispute.html" class="btn btn-ghost btn-sm w-full" style="justify-content:center;color:var(--rust);">⚖
          Raise Dispute or Issue</a>
      </div>

    </div><!-- end right col -->
  </div><!-- end proj-shell -->


  <!-- ══════════ SUBMIT MILESTONE MODAL ══════════ -->
  <div id="submit-modal" class="modal-backdrop hidden">
    <div class="modal" style="max-width:680px;">
      <div class="modal-header">
        <div>
          <h3>Submit Phase 2 Deliverables</h3>
          <p class="text-sm text-muted mt-4">Complete the QA checklist and upload your files. The client gets 72h to
            review — then auto-approves.</p>
        </div>
        <button class="modal-close" onclick="document.getElementById('submit-modal').classList.add('hidden')">✕</button>
      </div>
      <div class="modal-body">

        <!-- QA GATE -->
        <div
          style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-md);padding:16px 18px;margin-bottom:18px;">
          <div
            style="font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;font-weight:700;color:var(--rust);margin-bottom:12px;font-family:var(--font-body);">
            QA Checklist — Required Before Submission</div>
          <label class="qa-item"><input type="checkbox" id="qa-0" onchange="checkQA()"> All milestone deliverables
            listed in the contract are included</label>
          <label class="qa-item"><input type="checkbox" id="qa-1" onchange="checkQA()"> Code / notebooks run without
            errors from a clean environment</label>
          <label class="qa-item"><input type="checkbox" id="qa-2" onchange="checkQA()"> SHAP explainability report
            included as PDF</label>
          <label class="qa-item"><input type="checkbox" id="qa-3" onchange="checkQA()"> No hardcoded file paths,
            credentials, or personal data</label>
          <label class="qa-item"><input type="checkbox" id="qa-4" onchange="checkQA()"> README and inline comments
            updated</label>
        </div>

        <!-- DELIVERABLE SELECTION -->
        <div
          style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:16px 18px;margin-bottom:18px;">
          <div
            style="font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;font-weight:700;color:var(--gold);margin-bottom:12px;font-family:var(--font-body);">
            Select Deliverable to Submit</div>
          <div style="display:flex;flex-direction:column;gap:6px;">
            <label
              style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;padding:12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);transition:border-color .12s;">
              <input type="radio" name="del-select" value="del-1"
                style="accent-color:var(--gold);margin-top:3px;flex-shrink:0;" onchange="checkQA()">
              <div>
                <strong>Trained XGBoost baseline + Random Forest comparison</strong>
                <div class="text-xs text-muted" style="margin-top:2px;">Jupyter Notebook, clean &amp; commented</div>
              </div>
              <span class="badge badge-default" style="font-size:.625rem;flex-shrink:0;margin-left:auto;">Not
                Submitted</span>
            </label>
            <label
              style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;padding:12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);transition:border-color .12s;">
              <input type="radio" name="del-select" value="del-2"
                style="accent-color:var(--gold);margin-top:3px;flex-shrink:0;" onchange="checkQA()">
              <div>
                <strong>Cross-validation report + hyperparameter tuning log</strong>
                <div class="text-xs text-muted" style="margin-top:2px;">All folds documented</div>
              </div>
              <span class="badge badge-default" style="font-size:.625rem;flex-shrink:0;margin-left:auto;">Not
                Submitted</span>
            </label>
            <label
              style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;padding:12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);transition:border-color .12s;">
              <input type="radio" name="del-select" value="del-3"
                style="accent-color:var(--gold);margin-top:3px;flex-shrink:0;" onchange="checkQA()">
              <div>
                <strong>Updated README with setup instructions</strong>
                <div class="text-xs text-muted" style="margin-top:2px;">Data schema documentation included</div>
              </div>
              <span class="badge badge-default" style="font-size:.625rem;flex-shrink:0;margin-left:auto;">Not
                Submitted</span>
            </label>
          </div>
        </div>

        <!-- FILE UPLOAD -->
        <div class="form-group">
          <label class="form-label">Upload Deliverable Files</label>
          <div id="del-upload-zone"
            style="border:1.5px dashed var(--border-dark);border-radius:var(--radius-md);padding:22px;text-align:center;cursor:pointer;background:var(--ivory-deep);"
            onclick="document.getElementById('del-file-input').click()">
            <div style="font-size:1.5rem;opacity:.4;margin-bottom:6px;">📤</div>
            <div style="font-size:.875rem;color:var(--ink-mid);"><strong style="color:var(--gold);">Click to
                upload</strong> or drag &amp; drop</div>
            <div style="font-size:.75rem;font-family:var(--font-mono);color:var(--ink-faint);">.ipynb · .pdf · .zip ·
              .py · Max 100MB each</div>
          </div>
          <input type="file" id="del-file-input" multiple style="display:none;" onchange="handleDelUpload(this)">
          <div id="del-staged-files" style="margin-top:8px;"></div>
        </div>

        <div class="form-group">
          <label class="form-label">Submission Notes <span class="text-muted font-mono"
              style="font-size:.7rem;font-weight:400;text-transform:none;letter-spacing:0;">Sent to client with
              deliverables</span></label>
          <textarea class="form-control" rows="4" id="submission-notes"
            placeholder="Describe what you've built and anything the client should know when reviewing — key findings, how to run the notebook, any known limitations, recommended next steps…"></textarea>
        </div>

        <div id="qa-warn"
          style="display:none;background:#FBEAE7;border:1px solid #F0C4BC;border-radius:var(--radius-sm);padding:12px 14px;font-size:.8125rem;color:var(--rust);">
          ⚠ Please complete all QA checklist items and select a deliverable before submitting.
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-outline"
          onclick="document.getElementById('submit-modal').classList.add('hidden')">Cancel</button>
        <button class="btn btn-primary" id="submit-confirm-btn" style="opacity:.5;cursor:not-allowed;" disabled
          onclick="confirmSubmit()">📤 Submit for Client Review</button>
      </div>
    </div>
  </div>

  <!-- WIP SNAPSHOT MODAL -->
  <div id="wip-modal" class="modal-backdrop hidden">
    <div class="modal modal-sm">
      <div class="modal-header">
        <div>
          <h3>Upload WIP Snapshot</h3>
          <p class="text-sm text-muted mt-4">Snapshots are archived and visible to the client as proof of ongoing work.
            They are NOT submitted deliverables.</p>
        </div>
        <button class="modal-close" onclick="document.getElementById('wip-modal').classList.add('hidden')">✕</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">File</label>
          <div
            style="border:1.5px dashed var(--border-dark);border-radius:var(--radius-md);padding:18px;text-align:center;cursor:pointer;background:var(--ivory-deep);">
            <div style="font-size:.875rem;color:var(--ink-mid);"><strong style="color:var(--gold);">Click to
                upload</strong> · Max 100MB</div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Snapshot Note</label>
          <input type="text" class="form-control" placeholder="e.g. End-of-day progress — model training loop at 75%">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline"
          onclick="document.getElementById('wip-modal').classList.add('hidden')">Cancel</button>
        <button class="btn btn-primary"
          onclick="document.getElementById('wip-modal').classList.add('hidden');showToast('WIP snapshot uploaded and archived.')">Upload
          Snapshot</button>
      </div>
    </div>
  </div>

  <!-- SCOPE AMENDMENT COUNTER MODAL -->
  <div id="amend-counter-modal" class="modal-backdrop hidden">
    <div class="modal modal-sm">
      <div class="modal-header">
        <div>
          <h3>Respond to Scope Amendment</h3>
          <p class="text-sm text-muted mt-4">The client proposes adding a Fairness Audit Report to Phase 3 (+$400).</p>
        </div>
        <button class="modal-close"
          onclick="document.getElementById('amend-counter-modal').classList.add('hidden')">✕</button>
      </div>
      <div class="modal-body">
        <div style="display:flex;gap:8px;flex-direction:column;">
          <label
            style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;padding:12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);">
            <input type="radio" name="amend-resp" value="accept" style="accent-color:var(--gold);margin-top:3px;">
            <div><strong>Accept as proposed (+$400)</strong>
              <div class="text-xs text-muted mt-2">Agree to add Fairness Audit Report to Phase 3 for $400 additional.
              </div>
            </div>
          </label>
          <label
            style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;padding:12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);">
            <input type="radio" name="amend-resp" value="counter" style="accent-color:var(--gold);margin-top:3px;">
            <div><strong>Counter-propose a different amount</strong>
              <div class="text-xs text-muted mt-2">Set your own price for the additional scope.</div>
            </div>
          </label>
          <label
            style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;padding:12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);">
            <input type="radio" name="amend-resp" value="decline" style="accent-color:var(--gold);margin-top:3px;">
            <div><strong>Decline — original scope only</strong>
              <div class="text-xs text-muted mt-2">Keep the project as originally contracted. No changes.</div>
            </div>
          </label>
        </div>
        <div class="form-group mt-14">
          <label class="form-label">Message to Client</label>
          <textarea class="form-control" rows="3"
            placeholder="Optional: explain your decision or ask for clarification…"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline"
          onclick="document.getElementById('amend-counter-modal').classList.add('hidden')">Cancel</button>
        <button class="btn btn-primary"
          onclick="document.getElementById('amend-counter-modal').classList.add('hidden');showToast('Amendment response sent to FinCorp Egypt.')">Send
          Response</button>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast-stack" id="toast-stack"></div>

  <script>
    /* ── SUBMIT MODAL ── */
    function openSubmitModal() {
      document.getElementById('submit-modal').classList.remove('hidden');
      checkQA();
    }
    function checkQA() {
      const qaOk = [0, 1, 2, 3, 4].every(i => document.getElementById('qa-' + i)?.checked);
      const delSelected = document.querySelector('input[name="del-select"]:checked');
      const ready = qaOk && delSelected;
      const btn = document.getElementById('submit-confirm-btn');
      const warn = document.getElementById('qa-warn');
      if (btn) { btn.disabled = !ready; btn.style.opacity = ready ? '1' : '.5'; btn.style.cursor = ready ? 'pointer' : 'not-allowed'; }
      if (warn) warn.style.display = ready ? 'none' : '';
      // highlight selected radio label
      document.querySelectorAll('input[name="del-select"]').forEach(r => {
        r.closest('label').style.borderColor = r.checked ? 'var(--gold)' : 'var(--border)';
        r.closest('label').style.background = r.checked ? 'var(--gold-pale)' : '';
      });
    }
    function confirmSubmit() {
      const qaOk = [0, 1, 2, 3, 4].every(i => document.getElementById('qa-' + i)?.checked);
      const delSelected = document.querySelector('input[name="del-select"]:checked');
      if (!qaOk || !delSelected) { document.getElementById('qa-warn').style.display = ''; return; }
      document.getElementById('submit-modal').classList.add('hidden');
      showToast('Phase 2 deliverables submitted to FinCorp Egypt. They have 72h to review. Auto-approves Apr 18 if no response.');
    }

    /* ── FILE UPLOAD ── */
    function handleDelUpload(input) {
      const list = document.getElementById('del-staged-files');
      Array.from(input.files).forEach(f => {
        const size = f.size > 1048576 ? (f.size / 1048576).toFixed(1) + ' MB' : (f.size / 1024).toFixed(0) + ' KB';
        const ext = f.name.split('.').pop().toLowerCase();
        const icons = { pdf: '📄', ipynb: '📓', py: '🐍', zip: '📦' };
        const row = document.createElement('div');
        row.style.cssText = 'display:flex;align-items:center;gap:10px;padding:9px 12px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-top:6px;font-size:.8125rem;';
        row.innerHTML = `<span>${icons[ext] || '📁'}</span><span style="flex:1;font-weight:600;">${f.name}</span><span style="color:var(--ink-muted);font-family:var(--font-mono);font-size:.75rem;">${size}</span><span class="badge badge-pending" style="font-size:.625rem;">Staged</span><button style="background:none;border:none;cursor:pointer;color:var(--rust);font-size:.875rem;" onclick="this.parentNode.remove();checkUploadZone()">✕</button>`;
        list.appendChild(row);
      });
      document.getElementById('del-upload-zone').style.display = 'none';
    }
    function checkUploadZone() {
      const list = document.getElementById('del-staged-files');
      if (!list.children.length) document.getElementById('del-upload-zone').style.display = '';
    }

    /* ── DELIVERABLE TOGGLE ── */
    function toggleDel(el) {
      if (el.classList.contains('done')) { el.classList.remove('done'); el.textContent = ''; el.closest('.del-row').querySelector('.del-label').classList.remove('done'); }
      else { el.classList.add('done'); el.textContent = '✓'; el.closest('.del-row').querySelector('.del-label').classList.add('done'); }
    }

    /* ── PAST MS TOGGLE ── */
    function togglePastMs(header) {
      const body = header.nextElementSibling;
      if (body) body.classList.toggle('open');
      const chev = header.querySelector('span:last-child');
      if (chev) chev.style.transform = body.classList.contains('open') ? 'rotate(180deg)' : '';
    }

    /* ── SCROLL ── */
    function scrollToMs(id) {
      const el = document.getElementById(id);
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    /* ── AMENDMENT ── */
    function approveAmendment() {
      document.querySelector('.amendment-card')?.remove();
      showToast('Scope amendment accepted. Phase 3 updated with Fairness Audit Report (+$400).');
    }
    function declineAmendment() {
      document.querySelector('.amendment-card')?.remove();
      showToast('Amendment declined. Original scope remains.', 'info');
    }

    /* ── TOAST ── */
    function showToast(msg, type = 'success') {
      const s = document.getElementById('toast-stack');
      const icons = { success: '✓', warn: '⚠', info: 'ℹ' };
      const cls = { success: 'success', warn: 'warning', info: '' };
      s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type === 'warn' ? 'Notice' : type === 'info' ? 'Info' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
      setTimeout(() => s.innerHTML = '', 5000);
    }
    /* ── USER DROPDOWN ── */
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