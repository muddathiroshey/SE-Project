<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Project: Predictive Churn Model — Nexus</title>
<link rel="stylesheet" href="assets/style.css">
<style>
.project-header-band {
  background:var(--ivory-card);border-bottom:1px solid var(--border);
  padding:32px 0;
}
.badge-danger { background:#FBE9E7; border:1px solid #F0B4AA; color:#D84040; }
.project-body { display:grid;grid-template-columns:1fr 320px;gap:32px; }
.milestone-card {
  border:1.5px solid var(--border);border-radius:var(--radius-md);
  padding:24px;margin-bottom:16px;background:var(--ivory-card);
  transition:all .15s;
}
.milestone-card.active { border-color:var(--gold);box-shadow:0 0 0 3px rgba(201,168,76,.1); }
.milestone-card.done { border-color:var(--sage);background:#FBFEF9; }
.milestone-card.locked { opacity:.6; }
.milestone-header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px; }
.milestone-title { font-family:var(--font-display);font-size:1.1rem;font-weight:600; }
.milestone-meta { display:flex;gap:16px;margin-bottom:14px;font-size:.8125rem; }
.milestone-meta span { color:var(--ink-muted); }
.milestone-meta strong { color:var(--ink); }
.deliverable-list { margin:12px 0; }
.deliverable-item {
  display:flex;align-items:center;gap:10px;
  padding:8px 0;border-bottom:1px solid var(--border);
  font-size:.875rem;
}
.deliverable-item:last-child { border-bottom:none; }
.deliverable-check { width:18px;height:18px;border-radius:50%;border:1.5px solid var(--border);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.625rem; }
.deliverable-check.done { background:var(--sage);border-color:var(--sage);color:#fff; }
.deliverable-check.partial { background:var(--gold);border-color:var(--gold);color:var(--ink); }
.deliverable-actions { display:flex;align-items:center;gap:8px; }
.deliverable-actions button { min-width:90px; }
.deliverable-actions .badge { white-space:nowrap; }
.deliverable-preview-card { display:flex; justify-content:space-between; align-items:center; gap:16px; padding:18px 20px; background:var(--ivory-deep); border:1px solid var(--border); border-radius:var(--radius-sm); margin-bottom:18px; }
.deliverable-preview-left { display:flex; flex-direction:column; gap:4px; }
.deliverable-preview-name { font-weight:700; font-size:1rem; }
.deliverable-preview-meta { font-size:.875rem; color:var(--ink-muted); }
.deliverable-preview-download { min-width:110px; }
.modal-tabs { display:flex; gap:10px; margin-bottom:18px; }
.modal-tab { padding:10px 14px; border:1px solid var(--border); border-radius:999px; background:var(--ivory-deep); color:var(--ink); cursor:pointer; }
.modal-tab.active { background:var(--gold-pale); border-color:var(--gold); color:var(--ink); }
.field-row { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
.field-number { width:26px; height:26px; display:flex; align-items:center; justify-content:center; background:var(--ivory-deep); border:1px solid var(--border); border-radius:50%; font-size:.85rem; color:var(--ink-muted); }
.field-input { flex:1; padding:10px 12px; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:.875rem; }
.field-input:focus { outline:none; border-color:var(--gold); }
.review-action-row { display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;margin-top:18px; }
.review-request-panel { display:none; margin-top:16px; }
.review-request-panel textarea { min-height:110px; }
.review-request-note { font-size:.8125rem; color:var(--ink-muted); margin-top:10px; }
#approve-release-btn:disabled,
.btn-primary:disabled {
  background: var(--ivory-deep);
  border-color: var(--border);
  color: var(--ink-muted);
  opacity: 1;
  cursor: not-allowed;
  pointer-events: none;
}
.btn-primary:not(:disabled):hover {
  filter: brightness(0.92);
}
.loader-ring {
  width: 46px;
  height: 46px;
  margin: 0 auto;
  border: 5px solid var(--ivory-deep);
  border-top-color: var(--gold);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}
.success-mark {
  width: 68px;
  height: 68px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: rgba(123, 93, 20, 0.1);
  border: 1px solid rgba(201, 168, 76, 0.35);
}
.success-icon {
  font-size: 2rem;
  color: var(--sage);
}
@keyframes spin {
  to { transform: rotate(360deg); }
}
.deliverable-files { display:grid; gap:10px; margin-top:16px; }
.deliverable-file-card { display:flex; justify-content:space-between; align-items:center; padding:14px 16px; background:var(--ivory-deep); border:1px solid var(--border); border-radius:var(--radius-sm); gap:12px; }
.deliverable-file-meta { display:flex; flex-direction:column; gap:4px; }
.deliverable-file-meta .file-name { font-weight:700; }
.deliverable-file-meta .file-note { font-size:.75rem; color:var(--ink-muted); }
.revision-tracker {
  background:var(--ivory-deep);border-radius:var(--radius-sm);
  padding:10px 14px;margin-top:12px;font-size:.8125rem;
  display:flex;justify-content:space-between;align-items:center;
}
.revision-dots { display:flex;gap:4px; }
.rev-dot { width:10px;height:10px;border-radius:50%;background:var(--border); }
.rev-dot.used { background:var(--gold); }
.rev-dot.paid { background:var(--rust); }
.payment-method-card {
  border:1.5px solid var(--border); border-radius:var(--radius-md);
  padding:16px 18px; display:flex; align-items:center; gap:16px;
  margin-bottom:10px; cursor:pointer; transition:all .15s;
}
.payment-method-card:hover { border-color:var(--gold); }
.payment-method-card.active { border-color:var(--gold); background:var(--gold-pale); }
.card-logo { width:48px; height:32px; border-radius:4px; background:var(--ink); display:flex; align-items:center; justify-content:center; font-size:.625rem; font-family:var(--font-mono); color:var(--gold); letter-spacing:.06em; flex-shrink:0; }
.payment-card-body { flex:1; min-width:0; }
.payment-card-title { font-weight:700; font-size:.875rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.escrow-sidebar-card {
  background:var(--ivory-card);border:1px solid var(--border);
  border-radius:var(--radius-md);padding:20px;margin-bottom:16px;
}
.escrow-line { display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:.875rem; }
.escrow-line:last-child { border-bottom:none; }
.deadline-card {
  background:var(--gold-pale);border:1px solid var(--gold-light);
  border-radius:var(--radius-md);padding:16px;margin-bottom:16px;
  font-size:.8125rem;
}
.deadline-card.urgent { background:#FBE9E7;border-color:#F0B4AA; }
.wip-snapshot { display:flex;gap:10px;align-items:center;padding:10px 0;border-bottom:1px solid var(--border);font-size:.8125rem; }
.wip-snapshot:last-child { border-bottom:none; }
.wip-icon { width:32px;height:32px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:.875rem;flex-shrink:0; }
.scope-change-banner {
  background:#FBF6E2;border:1px solid #E8D88C;border-radius:var(--radius-md);
  padding:14px 18px;margin-bottom:20px;display:flex;gap:12px;align-items:flex-start;
  font-size:.875rem;
}
</style>
</head>
<body>

<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="dashboard-client.html">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">🔔 <span class="notif-count" style="position:absolute;top:2px;right:2px;">2</span></a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">AT</div></div>
          <span style="font-size:.875rem;font-weight:700;">Amira T.</span>
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

<!-- DISPUTE BANNER -->
<div style="background:#FBE9E7;border-bottom:2px solid #D84040;padding:16px 0;">
  <div class="container">
    <div style="display:flex;gap:12px;align-items:center;justify-content:space-between;">
      <div style="display:flex;gap:12px;align-items:flex-start;flex:1;">
        <span style="font-size:1.4rem;">⚖️</span>
        <div style="flex:1;">
          <div style="font-weight:700;color:#D84040;margin-bottom:2px;">Dispute Active — Project Frozen</div>
          <div style="font-size:.875rem;color:#B2423A;">No actions can be taken until this dispute is resolved. Direct messaging with the specialist is disabled.</div>
        </div>
      </div>
      <div style="display:flex;gap:12px;align-items:center;white-space:nowrap;">
        <div style="font-size:.75rem;color:#B2423A;">Reference: DSP-NX-3812-2025</div>
        <a href="dispute.html" class="btn btn-danger btn-sm" style="background:#D84040;color:#fff;border-color:#D84040;">View Dispute →</a>
      </div>
    </div>
  </div>
</div>

<!-- PROJECT HEADER -->
<div class="project-header-band">
  <div class="container">
    <div class="breadcrumb">Active Projects <span>›</span> NX-2025-3812</div>
    <div class="flex justify-between items-start mt-8">
      <div>
        <h2 style="margin-bottom:8px;">Predictive Churn Model — FinCorp Egypt</h2>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <span class="badge badge-gold">Data Science</span>
          <span class="badge badge-danger badge-dot">Dispute Active</span>
          <span class="text-sm text-muted font-mono">Ref: NX-2025-3812</span>
          <span class="text-sm text-muted">Phase 2 of 5 · Started Apr 3</span>
        </div>
      </div>
      <div style="display:flex;gap:10px;">
        <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;">⚖️ Project Frozen</button>
      </div>
    </div>
    <div class="grid-4 mt-24">
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$8,400</div>
        <div class="stat-label">Total Budget</div>
      </div>
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$3,360</div>
        <div class="stat-label">In Escrow (Phase 2)</div>
      </div>
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">$1,680</div>
        <div class="stat-label">Released (Phase 1)</div>
      </div>
      <div class="stat-card" style="padding:16px 20px;">
        <div class="stat-value" style="font-size:1.5rem;">4</div>
        <div class="stat-label">Days Until Next Deadline</div>
      </div>
    </div>
  </div>
</div>

<div class="container" style="padding-top:32px;padding-bottom:48px;">

  <div class="project-body">

    <!-- MILESTONES -->
    <div>

      <!-- PHASE 1 - DONE -->
      <div class="milestone-card done">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--sage);font-weight:700;margin-bottom:4px;">Phase 1 · Completed</div>
            <div class="milestone-title">Exploratory Data Analysis &amp; Baseline</div>
          </div>
          <span class="badge badge-verified badge-dot">Completed</span>
        </div>
        <div class="milestone-meta">
          <span>Duration: <strong>14 days</strong></span>
          <span>Released: <strong>$1,680</strong></span>
          <span>Completed: <strong>Apr 12</strong></span>
        </div>
        <div class="progress-bar"><div class="progress-fill success" style="width:100%;"></div></div>
        <div style="display:flex;gap:10px;align-items:center;margin-top:16px;">
          <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;">View Deliverables</button>
          <span class="text-xs text-muted">3 of 3 deliverables accepted · 0 revisions used</span>
        </div>
      </div>

      <!-- PHASE 2 - ACTIVE -->
      <div class="milestone-card active">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:4px;">Phase 2 · In Progress</div>
            <div class="milestone-title">Feature Engineering &amp; Model Training</div>
          </div>
          <span class="badge badge-pending badge-dot">In Progress</span>
        </div>
        <div class="milestone-meta">
          <span>Duration: <strong>21 days</strong></span>
          <span>Budget: <strong>$3,360</strong></span>
          <span>Deadline: <strong>Apr 19 (4 days)</strong></span>
        </div>
        <div class="progress-bar mb-8"><div class="progress-fill" style="width:68%;"></div></div>
        <div class="flex justify-between text-xs text-muted mb-12">
          <span>68% — last updated 6h ago</span>
          <span>WIP Snapshot available</span>
        </div>

        <div class="deliverable-list">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);font-weight:700;margin-bottom:8px;">Deliverables</div>
          <div class="deliverable-item" data-deliverable-key="shap-report">
            <div class="deliverable-check done">✓</div>
            <span style="flex:1;">Feature importance analysis (SHAP report)</span>
            <div class="deliverable-actions">
              <button class="btn btn-ghost btn-sm" disabled style="opacity:.5;cursor:not-allowed;">View</button>
              <span class="badge badge-verified" style="font-size:.625rem;">Accepted</span>
            </div>
          </div>
          <div class="deliverable-item" data-deliverable-key="xgboost-report">
            <div class="deliverable-check partial">◐</div>
            <span style="flex:1;">Trained XGBoost baseline + Random Forest comparison</span>
            <div class="deliverable-actions">
              <button class="btn btn-ghost btn-sm" disabled style="opacity:.5;cursor:not-allowed;">View</button>
              <span class="badge badge-pending" style="font-size:.625rem;">Under Review</span>
            </div>
          </div>
          <div class="deliverable-item" data-deliverable-key="cv-report">
            <div class="deliverable-check"></div>
            <span style="flex:1;color:var(--ink-muted);">Cross-validation report + hyperparameter tuning log</span>
            <span class="badge badge-default" style="font-size:.625rem;">Not Submitted</span>
          </div>
        </div>

        <div class="revision-tracker">
          <div>
            <div style="font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:4px;">Revisions Used</div>
            <div class="revision-dots">
              <div class="rev-dot used"></div>
              <div class="rev-dot"></div>
            </div>
          </div>
          <div class="text-xs text-muted">1 of 2 free revisions used<br>Additional: $140/revision</div>
        </div>

        <div style="margin-top:16px;">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:8px;">WIP Snapshots</div>
          <div class="wip-snapshot">
            <div class="wip-icon">📓</div>
            <div style="flex:1;"><div style="font-weight:700;">feature_engineering_v2.ipynb</div><div class="text-xs text-muted">Auto-archived · Apr 14, 09:42 GMT+2 · 12MB</div></div>
            <button class="btn btn-ghost btn-sm" disabled style="opacity:.5;cursor:not-allowed;">View</button>
          </div>
          <div class="wip-snapshot">
            <div class="wip-icon">📓</div>
            <div style="flex:1;"><div style="font-weight:700;">model_comparison_draft.ipynb</div><div class="text-xs text-muted">Auto-archived · Apr 15, 22:17 GMT+2 · 8.4MB</div></div>
            <button class="btn btn-ghost btn-sm" disabled style="opacity:.5;cursor:not-allowed;">View</button>
          </div>
        </div>

        <div style="margin-top:16px;display:flex;gap:10px;">
          <!-- DISPUTE MODE: All actions frozen -->
          <button id="approve-release-btn" class="btn btn-primary btn-sm" disabled style="opacity:.5;cursor:not-allowed;flex:1;text-align:center;" title="Actions unavailable while dispute is active">Actions Frozen — Dispute Active</button>
        </div>
      </div>

      <!-- PHASES 3–5 LOCKED -->
      <!-- BACKEND: set prev-milestone-complete based on the previous milestone state, and set deliverables/QA flags when milestone planning is saved. -->
      <!-- DISPUTE MODE: All actions disabled -->
      <div id="phase-3-card" class="milestone-card locked" data-prev-milestone-id="phase-2" data-prev-milestone-complete="false" data-deliverables-set="false" data-qa-set="false">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-faint);font-weight:700;margin-bottom:4px;">Phase 3 · Locked</div>
            <div class="milestone-title" style="color:var(--ink-faint);">Model Evaluation, Bias &amp; Fairness Audit</div>
          </div>
          <span class="badge badge-default">🔒 Locked</span>
        </div>
        <div class="text-sm text-muted">Budget: $840</div>
        <div id="phase-3-escrow-note" class="escrow-status-note text-sm" style="color:#D84040;">⚖️ Project in Dispute — Actions Frozen</div>
        <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
          <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;">Set Deliverables & QA</button>
          <button class="btn btn-gold btn-sm lock-escrow-btn hidden" disabled style="opacity:.5;cursor:not-allowed;">Lock Escrow</button>
        </div>
      </div>
      <!-- BACKEND: set prev-milestone-complete based on the previous milestone state, and set deliverables/QA flags when milestone planning is saved. -->
      <!-- DISPUTE MODE: All actions disabled -->
      <div id="phase-4-card" class="milestone-card locked" data-prev-milestone-id="phase-3" data-prev-milestone-complete="false" data-deliverables-set="false" data-qa-set="false">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-faint);font-weight:700;margin-bottom:4px;">Phase 4 · Locked</div>
            <div class="milestone-title" style="color:var(--ink-faint);">Production Pipeline &amp; MLFlow Integration</div>
          </div>
          <span class="badge badge-default">🔒 Locked</span>
        </div>
        <div class="text-sm text-muted">Budget: $1,680</div>
        <div id="phase-4-escrow-note" class="escrow-status-note text-sm" style="color:#D84040;">⚖️ Project in Dispute — Actions Frozen</div>
        <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
          <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;">Set Deliverables & QA</button>
          <button class="btn btn-gold btn-sm lock-escrow-btn hidden" disabled style="opacity:.5;cursor:not-allowed;">Lock Escrow</button>
        </div>
      </div>
      <!-- BACKEND: set prev-milestone-complete based on the previous milestone state, and set deliverables/QA flags when milestone planning is saved. -->
      <!-- DISPUTE MODE: All actions disabled -->
      <div id="phase-5-card" class="milestone-card locked" data-prev-milestone-id="phase-4" data-prev-milestone-complete="false" data-deliverables-set="false" data-qa-set="false">
        <div class="milestone-header">
          <div>
            <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-faint);font-weight:700;margin-bottom:4px;">Phase 5 · Locked</div>
            <div class="milestone-title" style="color:var(--ink-faint);">Final Handoff, Documentation &amp; Deployment</div>
          </div>
          <span class="badge badge-default">🔒 Locked</span>
        </div>
        <div class="text-sm text-muted">Budget: $840</div>
        <div id="phase-5-escrow-note" class="escrow-status-note text-sm" style="color:#D84040;">⚖️ Project in Dispute — Actions Frozen</div>
        <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
          <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;">Set Deliverables & QA</button>
          <button class="btn btn-gold btn-sm lock-escrow-btn hidden" disabled style="opacity:.5;cursor:not-allowed;">Lock Escrow</button>
        </div>
      </div>

    </div>

    <!-- SIDEBAR -->
    <div>

      <div class="deadline-card">
        <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:#9A6800;margin-bottom:6px;">⏱ Upcoming Deadline</div>
        <div style="font-weight:700;margin-bottom:4px;">Phase 2 Delivery</div>
        <div style="font-family:var(--font-mono);font-size:1.2rem;font-weight:500;">4 days remaining</div>
        <div style="font-size:.75rem;color:var(--ink-muted);margin-top:4px;">Apr 19, 2025 · 23:59 GMT+2</div>
        <div style="margin-top:10px;font-size:.75rem;">If client does not review within 72h of submission, milestone auto-approves.</div>
      </div>

      <div class="escrow-sidebar-card">
        <h4 style="font-size:.9rem;margin-bottom:12px;">Escrow Summary</h4>
        <div class="escrow-line"><span>Phase 1 Released</span><span class="font-mono" style="color:var(--sage);">$1,680</span></div>
        <div class="escrow-line"><span>Phase 2 Locked</span><span class="font-mono" style="color:var(--gold);">$3,360</span></div>
        <div class="escrow-line"><span>Remaining Phases</span><span class="font-mono text-muted">$3,360</span></div>
        <div class="escrow-line" style="font-weight:700;"><span>Total</span><span class="font-mono">$8,400</span></div>
        <a href="escrow-wallet.html" class="btn btn-outline btn-sm w-full mt-12" style="justify-content:center;">View Wallet →</a>
      </div>

      <div class="escrow-sidebar-card">
        <h4 style="font-size:.9rem;margin-bottom:12px;">Specialist</h4>
        <div class="flex items-center gap-10 mb-12">
          <div class="avatar-badge"><div class="avatar avatar-md">DR</div></div>
          <div>
            <div style="font-weight:700;font-size:.875rem;">Dr. Rania Khalil</div>
            <div class="text-xs text-muted">Data Scientist · Cairo</div>
            <div class="stars" style="font-size:.75rem;">★★★★★</div>
          </div>
        </div>
        <div class="text-xs text-muted mb-8">NDA Active · Signed Apr 3, 2025</div>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <button class="btn btn-outline btn-sm" disabled style="opacity:.5;cursor:not-allowed;justify-content:center;">💬 Messaging Disabled</button>
          <a href="expert-profile.html" class="btn btn-ghost btn-sm" style="justify-content:center;">View Profile</a>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ESCROW LOCK MODAL -->
<div id="escrow-lock-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3 id="escrow-lock-modal-title">Lock Escrow</h3>
      <button class="modal-close" onclick="closeEscrowLockModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="verify-band mb-16">
        <span>🛡️</span>
        <div style="font-size:.8125rem;"><strong id="escrow-lock-amount">$840</strong> will be reserved in escrow for <strong id="escrow-lock-phase">Phase 3</strong>.</div>
      </div>
      <p style="margin-bottom:16px;color:var(--ink-mid);">Once locked, this milestone's funds are held securely until the phase is approved or disputed. Select a payment method for this escrow transaction.</p>
      <div class="form-group">
        <label class="form-label">Choose payment method</label>
        <div class="payment-method-card active" data-payment-id="pm-001" onclick="selectEscrowPaymentMethod(this)">
          <div class="card-logo">MC</div>
          <div class="payment-card-body">
            <div class="payment-card-title">Mastercard ···· 4821</div>
            <div class="text-xs text-muted">Expires 09/27 · Primary</div>
          </div>
          <span class="badge badge-verified" style="font-size:.625rem;">Default</span>
        </div>
        <div class="payment-method-card" data-payment-id="pm-002" onclick="selectEscrowPaymentMethod(this)">
          <div class="card-logo" style="background:#1A3C87;">VISA</div>
          <div class="payment-card-body">
            <div class="payment-card-title">Visa ···· 2201</div>
            <div class="text-xs text-muted">Expires 03/26</div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeEscrowLockModal()">Cancel</button>
      <button class="btn btn-primary" onclick="confirmEscrowLock()">Lock Escrow</button>
    </div>
  </div>
</div>

<!-- ESCROW LOCK STATUS MODAL -->
<div id="escrow-lock-status-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3 id="escrow-lock-status-title">Processing Transaction</h3>
      <button class="modal-close" onclick="closeEscrowLockStatusModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="verify-band mb-16">
        <span id="escrow-lock-status-icon">⏳</span>
        <div id="escrow-lock-status-copy" style="font-size:.8125rem;">Locking escrow with <strong id="escrow-lock-status-payment">Mastercard ···· 4821</strong>.</div>
      </div>
      <div class="loader-ring" id="escrow-lock-status-loader"></div>
      <div class="success-mark hidden" id="escrow-lock-status-success">
        <div class="success-icon">✓</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary hidden" id="escrow-lock-status-done-btn" onclick="closeEscrowLockStatusModal()">Done</button>
    </div>
  </div>
</div>

<!-- APPROVE MILESTONE MODAL -->
<div id="approve-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3>⚖️ Project in Dispute</h3>
      <button class="modal-close" onclick="document.getElementById('approve-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="verify-band mb-16" style="background:#FBE9E7;border:1px solid #F0B4AA;color:#D84040;">
        <span>⚖️</span>
        <div style="font-size:.8125rem;"><strong>This project is currently under dispute.</strong> All actions are frozen until the dispute is resolved.</div>
      </div>
      <div class="form-group">
        <label class="form-label">Feedback (optional)</label>
        <textarea class="form-control" rows="3" placeholder="Share your thoughts on the deliverables…" disabled style="opacity:.5;cursor:not-allowed;"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('approve-modal').classList.add('hidden')">Close</button>
      <button class="btn btn-primary" disabled style="opacity:.5;cursor:not-allowed;">Actions Frozen</button>
    </div>
  </div>
</div>

<!-- VIEW DELIVERABLES MODAL -->
<div id="deliverables-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <h3>Phase 1 Deliverables</h3>
      <button class="modal-close" onclick="document.getElementById('deliverables-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <p style="margin-bottom:16px;color:var(--ink-muted);">Download completed files for Phase 1.</p>
      <div class="deliverable-file-card">
        <div class="deliverable-file-meta">
          <div class="file-name">EDA_Report.pdf</div>
          <div class="file-note">1.2 MB · Delivered Apr 12</div>
        </div>
        <button class="btn btn-ghost btn-sm">Download</button>
      </div>
      <div class="deliverable-file-card">
        <div class="deliverable-file-meta">
          <div class="file-name">Churn_Model_Summary.pptx</div>
          <div class="file-note">940 KB · Delivered Apr 12</div>
        </div>
        <button class="btn btn-ghost btn-sm">Download</button>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('deliverables-modal').classList.add('hidden')">Close</button>
    </div>
  </div>
</div>

<!-- DELIVERABLE REVIEW MODAL -->
<div id="deliverable-review-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <h3 id="review-file-title">Deliverable</h3>
      <button class="modal-close" onclick="closeDeliverableReview()">✕</button>
    </div>
    <div class="modal-body">
      <div id="review-file-status" style="margin-bottom:8px;font-size:.85rem;color:var(--ink-muted);"></div>
      <div class="deliverable-preview-card">
        <div class="deliverable-preview-badge" id="review-file-icon">PDF</div>
        <div class="deliverable-preview-info">
          <div class="deliverable-preview-name" id="review-file-preview-name">Deliverable</div>
          <div class="deliverable-preview-meta" id="review-file-meta">PDF · 1.2 MB</div>
        </div>
        <button class="btn btn-ghost btn-sm deliverable-preview-download" id="review-download-btn">Download</button>
      </div>
      <div class="review-action-row" id="review-action-row">
        <button class="btn btn-primary" id="review-accept-btn" disabled style="opacity:.5;cursor:not-allowed;">Accept</button>
        <button class="btn btn-outline" id="review-request-btn" disabled style="opacity:.5;cursor:not-allowed;">Request Revision</button>
      </div>
      <div class="review-request-panel" id="review-request-panel">
        <label class="form-label">Revision Request</label>
        <textarea class="form-control" id="revision-note" placeholder="Describe the change you'd like the specialist to make..."></textarea>
        <div class="review-action-row" style="justify-content:flex-start;">
          <button class="btn btn-outline btn-sm" onclick="sendRevisionRequest()">Send Request</button>
          <button class="btn btn-ghost btn-sm" onclick="toggleRevisionRequest()">Cancel</button>
        </div>
        <div class="review-request-note">This will notify the specialist and use one free revision from your allocated revision budget.</div>
      </div>
    </div>
  </div>
</div>

<!-- MILESTONE DELIVERABLES MODAL -->
<div id="milestone-deliverables-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <h3 id="milestone-plan-title">Plan Milestone Deliverables</h3>
      <button class="modal-close" onclick="closeMilestonePlanModal()">✕</button>
    </div>
    <div class="modal-body">
      <p id="milestone-plan-subtitle" style="margin-bottom:16px;color:var(--ink-muted);"></p>
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
      <div class="text-xs text-muted" style="margin-top:10px;">Once the milestone starts, deliverables can no longer be edited by the client.</div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeMilestonePlanModal()">Cancel</button>
      <button class="btn btn-primary" onclick="saveMilestoneDeliverables()">Save Deliverables</button>
    </div>
  </div>
</div>

<script>
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
function openDeliverablesModal() {
  document.getElementById('deliverables-modal').classList.remove('hidden');
}
function openDeliverableReview(title, status, key, fileType, fileSize) {
  document.getElementById('review-file-title').textContent = title;
  document.getElementById('review-file-preview-name').textContent = title;
  document.getElementById('review-file-icon').textContent = fileType;
  document.getElementById('review-file-status').textContent = status === 'accepted' ? 'Accepted' : 'Under review';
  document.getElementById('review-file-meta').textContent = `${fileType} · ${fileSize}`;
  document.getElementById('review-accept-btn').style.display = status === 'under-review' ? '' : 'none';
  document.getElementById('review-request-btn').style.display = status === 'under-review' ? '' : 'none';
  document.getElementById('review-request-panel').style.display = 'none';
  window.currentReviewKey = key;
  document.getElementById('deliverable-review-modal').classList.remove('hidden');
}
function closeDeliverableReview() {
  document.getElementById('revision-note').value = '';
  document.getElementById('review-request-panel').style.display = 'none';
  document.getElementById('deliverable-review-modal').classList.add('hidden');
}
function toggleRevisionRequest() {
  const panel = document.getElementById('review-request-panel');
  panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}
function switchMilestoneTab(tab) {
  document.getElementById('deliverables-tab').classList.toggle('active', tab === 'deliverables');
  document.getElementById('qa-tab').classList.toggle('active', tab === 'qa');
  document.getElementById('milestone-deliverables-section').classList.toggle('hidden', tab !== 'deliverables');
  document.getElementById('milestone-qa-section').classList.toggle('hidden', tab !== 'qa');
}
function addDeliverableField(value = '') {
  const list = document.getElementById('deliverables-list');
  const index = list.children.length + 1;
  const row = document.createElement('div');
  row.className = 'field-row';
  row.innerHTML = `
    <div class="field-number">${index}</div>
    <input type="text" class="field-input" placeholder="Deliverable ${index}" value="${value}">
    <button type="button" class="btn btn-ghost btn-sm" onclick="removeField(this)">✕</button>
  `;
  list.appendChild(row);
  updateFieldNumbers(list);
}
function addQaField(value = '') {
  const list = document.getElementById('qa-checklist');
  const index = list.children.length + 1;
  const row = document.createElement('div');
  row.className = 'field-row';
  row.innerHTML = `
    <div class="field-number">${index}</div>
    <input type="text" class="field-input" placeholder="QA item ${index}" value="${value}">
    <button type="button" class="btn btn-ghost btn-sm" onclick="removeField(this)">✕</button>
  `;
  list.appendChild(row);
  updateFieldNumbers(list);
}
function removeField(button) {
  const row = button.closest('.field-row');
  const list = row.parentElement;
  row.remove();
  updateFieldNumbers(list);
}
function updateFieldNumbers(list) {
  Array.from(list.children).forEach((row, idx) => {
    const number = row.querySelector('.field-number');
    if (number) number.textContent = idx + 1;
    const input = row.querySelector('.field-input');
    if (input) input.placeholder = list.id === 'deliverables-list' ? `Deliverable ${idx + 1}` : `QA item ${idx + 1}`;
  });
}
function openMilestonePlanModal(title, subtitle, cardId) {
  document.getElementById('milestone-plan-title').textContent = 'Set Deliverables for ' + title;
  document.getElementById('milestone-plan-subtitle').textContent = subtitle;
  document.getElementById('deliverables-list').innerHTML = '';
  document.getElementById('qa-checklist').innerHTML = '';
  window.currentMilestonePlanCard = cardId; // BACKEND: backend can also pass a milestone identifier here for tracking edits
  addDeliverableField();
  addQaField();
  switchMilestoneTab('deliverables');
  document.getElementById('milestone-deliverables-modal').classList.remove('hidden');
}
function openEscrowLockModal(cardId, phaseLabel, subtitle, amount) {
  window.currentEscrowCard = cardId;
  document.getElementById('escrow-lock-modal-title').textContent = 'Lock Escrow for ' + phaseLabel;
  document.getElementById('escrow-lock-phase').textContent = phaseLabel;
  document.getElementById('escrow-lock-amount').textContent = amount;
  document.getElementById('escrow-lock-modal').classList.remove('hidden');
  const cards = document.querySelectorAll('#escrow-lock-modal .payment-method-card');
  cards.forEach((card, index) => {
    card.classList.toggle('active', index === 0);
  });
  if (cards[0]) {
    window.currentEscrowPaymentLabel = cards[0].querySelector('.payment-card-title').textContent;
  }
}
function selectEscrowPaymentMethod(card) {
  const cards = card.parentElement.querySelectorAll('.payment-method-card');
  cards.forEach(c => c.classList.remove('active'));
  card.classList.add('active');
  const title = card.querySelector('.payment-card-title');
  window.currentEscrowPaymentLabel = title ? title.textContent : '';
}
function closeEscrowLockModal() {
  document.getElementById('escrow-lock-modal').classList.add('hidden');
}
function updateLockEscrowButtons() {
  document.querySelectorAll('.milestone-card.locked').forEach(card => {
    // BACKEND: these data attributes should be driven by milestone and plan state from server
    const prevComplete = card.dataset.prevMilestoneComplete === 'true';
    const deliverablesSet = card.dataset.deliverablesSet === 'true';
    const qaSet = card.dataset.qaSet === 'true';
    const lockButton = card.querySelector('.lock-escrow-btn');
    if (!lockButton) return;
    // only show lock escrow if previous phase is complete and deliverables + QA are set
    lockButton.classList.toggle('hidden', !(prevComplete && deliverablesSet && qaSet));
  });
}
function openEscrowLockStatusModal() {
  document.getElementById('escrow-lock-status-title').textContent = 'Processing Transaction';
  document.getElementById('escrow-lock-status-icon').textContent = '⏳';
  const paymentLabel = window.currentEscrowPaymentLabel || 'selected payment method';
  const phaseLabel = document.getElementById('escrow-lock-phase').textContent;
  document.getElementById('escrow-lock-status-payment').textContent = paymentLabel;
  document.getElementById('escrow-lock-status-copy').textContent = `Locking escrow for ${phaseLabel} using ${paymentLabel}.`;
  const doneBtn = document.getElementById('escrow-lock-status-done-btn');
  doneBtn.classList.add('hidden');
  document.getElementById('escrow-lock-status-loader').classList.remove('hidden');
  document.getElementById('escrow-lock-status-success').classList.add('hidden');
  document.getElementById('escrow-lock-status-modal').classList.remove('hidden');
  window.escrowLockStatusTimer = setTimeout(() => {
    document.getElementById('escrow-lock-status-title').textContent = 'Transaction Completed';
    document.getElementById('escrow-lock-status-icon').textContent = '✅';
    document.getElementById('escrow-lock-status-copy').textContent = `Escrow has been locked successfully for ${phaseLabel}.`;
    document.getElementById('escrow-lock-status-loader').classList.add('hidden');
    document.getElementById('escrow-lock-status-success').classList.remove('hidden');
    doneBtn.classList.remove('hidden');
  }, 3200);
}
function closeEscrowLockStatusModal() {
  document.getElementById('escrow-lock-status-modal').classList.add('hidden');
  if (window.escrowLockStatusTimer) {
    clearTimeout(window.escrowLockStatusTimer);
    window.escrowLockStatusTimer = null;
  }
}
function confirmEscrowLock() {
  const card = document.getElementById(window.currentEscrowCard);
  if (!card) {
    closeEscrowLockModal();
    return;
  }
  const button = card.querySelector('.lock-escrow-btn');
  const badge = card.querySelector('.milestone-header .badge');
  const note = card.querySelector('.escrow-status-note');
  if (badge) {
    badge.textContent = '🔒 Escrow Locked';
    badge.className = 'badge badge-gold';
  }
  if (note) {
    note.textContent = 'Escrow locked for this milestone.';
  }
  if (button) {
    button.textContent = 'Escrow Locked';
    button.disabled = true;
    button.className = 'btn btn-outline btn-sm lock-escrow-btn';
  }
  closeEscrowLockModal();
  openEscrowLockStatusModal();
}
function closeMilestonePlanModal() {
  document.getElementById('milestone-deliverables-modal').classList.add('hidden');
}
function saveMilestoneDeliverables() {
  const deliverables = Array.from(document.querySelectorAll('#deliverables-list .field-input')).map(i => i.value.trim()).filter(Boolean);
  const qaItems = Array.from(document.querySelectorAll('#qa-checklist .field-input')).map(i => i.value.trim()).filter(Boolean);
  if (!deliverables.length) {
    alert('Please add at least one deliverable.');
    return;
  }
  if (!qaItems.length) {
    alert('Please add at least one QA item.');
    return;
  }
  // persist milestone plan state for the selected locked milestone card
  const planCard = document.getElementById(window.currentMilestonePlanCard);
  if (planCard) {
    planCard.dataset.deliverablesSet = 'true';
    planCard.dataset.qaSet = 'true';
    // BACKEND: submit deliverables/QA data here, and ultimately set planCard.dataset.prevMilestoneComplete from server state as needed
  }
  closeMilestonePlanModal();
  updateLockEscrowButtons();
  alert('Milestone deliverables and QA checklist saved.');
}
window.addEventListener('DOMContentLoaded', () => {
  updateLockEscrowButtons();
});
function acceptDeliverable() {
  const item = document.querySelector('[data-deliverable-key="' + window.currentReviewKey + '"]');
  if (item) {
    const badge = item.querySelector('.badge');
    const check = item.querySelector('.deliverable-check');
    if (badge) { badge.textContent = 'Accepted'; badge.className = 'badge badge-verified'; }
    if (check) { check.textContent = '✓'; check.className = 'deliverable-check done'; }
  }
  closeDeliverableReview();
  checkApproveReleaseButton();
  alert('Deliverable accepted.');
}
function checkApproveReleaseButton() {
  const deliverables = document.querySelectorAll('.deliverable-item');
  const allAccepted = Array.from(deliverables).every(item => {
    const badge = item.querySelector('.badge');
    return badge && badge.textContent.trim() === 'Accepted';
  });
  const button = document.getElementById('approve-release-btn');
  if (button) button.disabled = !allAccepted;
}
function sendRevisionRequest() {
  const note = document.getElementById('revision-note').value.trim();
  if (!note) {
    alert('Please enter revision details before sending.');
    return;
  }
  closeDeliverableReview();
  alert('Revision request sent to the specialist.');
}
function openWipSnapshot(name, meta) {
  alert('Previewing ' + name + '\n' + meta + '\n\nThis would open the WIP snapshot preview in a real app.');
}
function initializeApprovalState() {
  checkApproveReleaseButton();
}
initializeApprovalState();
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});
</script>
</body>
</html>
