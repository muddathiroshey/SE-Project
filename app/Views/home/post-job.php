<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Post a Project — Nexus</title>
<link rel="stylesheet" href="assets/style.css">
<style>
.wizard-shell { display:grid;grid-template-columns:300px 1fr;gap:0;min-height:100vh; }
.wizard-left {
  background:var(--ink);padding:40px 32px;
  border-right:1px solid rgba(255,255,255,.08);
  position:sticky;top:0;
  height:100vh;overflow-y:auto;
}
.wizard-left-logo { font-family:var(--font-display);font-size:1.4rem;color:var(--ivory);margin-bottom:40px; }
.wizard-left-logo span { color:var(--gold); }
.wizard-left-step {
  display:flex;gap:14px;align-items:flex-start;
  padding:14px 0;border-bottom:1px solid rgba(255,255,255,.06);
  cursor:pointer;
}
.wizard-left-step:last-child { border-bottom:none; }
.wzl-dot {
  width:30px;height:30px;border-radius:50%;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-size:.75rem;font-weight:700;font-family:var(--font-mono);
  border:1.5px solid rgba(255,255,255,.2);color:rgba(247,244,239,.4);
}
.wzl-dot.done { background:var(--sage);border-color:var(--sage);color:#fff; }
.wzl-dot.active { background:var(--gold);border-color:var(--gold);color:var(--ink); }
.wzl-title { font-size:.875rem;font-weight:700;color:rgba(247,244,239,.4); }
.wzl-title.active { color:var(--ivory); }
.wzl-title.done { color:rgba(247,244,239,.7); }
.wzl-sub { font-size:.75rem;color:rgba(247,244,239,.3);margin-top:2px; }
.wizard-right { padding:48px 60px; }
.wizard-step-panel { display:none; }
.wizard-step-panel.active { display:block; }
.niche-select-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:8px; }
.niche-select-card {
  border:1.5px solid var(--border);border-radius:var(--radius-md);
  padding:18px 14px;text-align:center;cursor:pointer;
  transition:all .15s;background:var(--ivory-card);
}
.niche-select-card:hover { border-color:var(--gold-light);background:var(--gold-pale); }
.niche-select-card.selected { border-color:var(--gold);background:var(--gold-pale);box-shadow:0 0 0 2px rgba(201,168,76,.2); }
.niche-card-icon { font-size:1.8rem;margin-bottom:8px; }
.niche-card-name { font-size:.875rem;font-weight:700;color:var(--ink-mid); }
.niche-select-card.selected .niche-card-name { color:var(--ink); }
.milestone-builder-row {
  display:grid;grid-template-columns:2fr 1fr 1fr auto;
  gap:12px;align-items:center;margin-bottom:12px;
}
.milestone-num-badge {
  width:28px;height:28px;border-radius:50%;
  background:var(--gold-pale);border:1.5px solid var(--gold-light);
  display:flex;align-items:center;justify-content:center;
  font-size:.75rem;font-weight:700;font-family:var(--font-mono);
  flex-shrink:0;
}
.milestone-total {
  background:var(--ivory-deep);border:1px solid var(--border);
  border-radius:var(--radius-sm);padding:12px 16px;
  display:flex;justify-content:space-between;align-items:center;
  margin-top:8px;
}
.budget-slider-wrap { margin:12px 0; }
.budget-preview {
  background:var(--gold-pale);border:1px solid var(--gold-light);
  border-radius:var(--radius-sm);padding:16px;
  display:flex;justify-content:space-between;align-items:center;
  margin-top:16px;font-size:.875rem;
}
.step-nav { display:flex;justify-content:space-between;align-items:center;margin-top:40px;padding-top:24px;border-top:1px solid var(--border); }
.nda-preview {
  background:var(--ivory-deep);border:1.5px dashed var(--border-dark);
  border-radius:var(--radius-md);padding:24px;margin:16px 0;
  font-family:var(--font-mono);font-size:.75rem;color:var(--ink-muted);
  line-height:1.8;
}
.nda-preview strong { color:var(--ink); }
.upload-zone {
  border:2px dashed var(--gold-light);border-radius:var(--radius-md);padding:32px;text-align:center;cursor:pointer;transition:all .15s;background:var(--gold-pale);
}
.upload-zone:hover {
  border-color:var(--gold);background:var(--gold-pale);
  box-shadow:0 0 0 2px rgba(201,168,76,.1);
}
.upload-zone.drag-over {
  border-color:var(--gold);background:var(--gold-pale);
  box-shadow:0 0 0 3px rgba(201,168,76,.2);
}
.upload-zone.error {
  border-color:#d32f2f !important;
  border-width:2px !important;
  background:rgba(211, 47, 47, 0.05) !important;
}
.file-preview {
  display:flex;align-items:center;gap:12px;padding:16px;background:var(--gold-pale);border-radius:var(--radius-md);border:1px solid var(--gold-light);
}
.file-preview-icon { font-size:1.5rem; }
.file-preview-info { flex:1;text-align:left; }
.file-preview-name { font-weight:600;font-size:.9rem;color:var(--ink); }
.file-preview-size { font-size:.75rem;color:var(--ink-muted);margin-top:2px; }
.file-preview-remove { padding:4px 8px;font-size:.75rem;color:var(--red);cursor:pointer;background:none;border:none; }
.field-error { display:none;margin-top:8px;font-size:.8rem;color:var(--rust);font-weight:700; }
.field-error.show { display:block; }
.input-invalid { border-color:var(--rust) !important; box-shadow:0 0 0 2px rgba(197,79,46,.15); }
.milestone-row-error { grid-column:1 / -1;margin-top:-4px; }
.exit-confirm-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(22, 25, 28, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1100;
}
.exit-confirm-modal {
  width: min(460px, calc(100vw - 32px));
  background: var(--ivory-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
  padding: 24px;
}
.exit-confirm-title {
  margin: 0 0 10px 0;
  font-size: 1.15rem;
  font-weight: 700;
  color: var(--ink);
}
.exit-confirm-text {
  margin: 0;
  font-size: 0.92rem;
  color: var(--ink-mid);
  line-height: 1.55;
}
.exit-confirm-actions {
  margin-top: 18px;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
.review-section {
  margin-bottom: 24px;
  padding: 20px 24px;
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  background: var(--ivory-card);
}
.review-section h4 {
  font-size: .9375rem;
  margin-bottom: 14px;
}
.review-row {
  display: flex;
  justify-content: space-between;
  gap: 18px;
  margin-bottom: 9px;
  font-size: .875rem;
}
.review-row:last-child { margin-bottom: 0; }
.review-row .label { color: var(--ink-muted); }
.review-row .val {
  color: var(--ink);
  font-family: var(--font-mono);
  font-weight: 600;
  text-align: right;
}
.niche-question-group {
  border-top: 1px solid var(--border);
  padding-top: 22px;
  margin-top: 22px;
}
.niche-question-heading {
  font-size: .95rem;
  margin-bottom: 14px;
}
.niche-answer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 10px;
}
.niche-answer-card {
  min-height: 58px;
  padding: 10px 12px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius-sm);
  background: var(--ivory-card);
  color: var(--ink-mid);
  text-align: left;
  font: inherit;
  font-size: .84rem;
  font-weight: 700;
  cursor: pointer;
  transition: all .15s;
}
.niche-answer-card:hover {
  border-color: var(--gold-light);
  background: var(--gold-pale);
}
.niche-answer-card.selected {
  border-color: var(--gold);
  background: var(--gold-pale);
  color: var(--ink);
  box-shadow: 0 0 0 2px rgba(201,168,76,.16);
}
.niche-answer-grid.input-invalid .niche-answer-card {
  border-color: var(--rust);
}
.language-pair-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(22, 25, 28, 0.55);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 1200;
}
.language-pair-modal {
  width: min(680px, calc(100vw - 32px));
  background: var(--ivory-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
  padding: 24px;
}
.language-pair-title {
  margin: 0 0 8px 0;
  font-size: 1.08rem;
  font-weight: 700;
  color: var(--ink);
}
.language-pair-text {
  margin: 0 0 18px 0;
  color: var(--ink-mid);
  font-size: .9rem;
  line-height: 1.5;
}
.language-pair-grid {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  gap: 12px;
  align-items: end;
}
.language-swap-btn {
  width: 42px;
  height: 42px;
  padding: 0;
  justify-content: center;
  font-size: 1.1rem;
}
.language-pair-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}
@media (max-width: 620px) {
  .language-pair-grid { grid-template-columns: 1fr; }
  .language-swap-btn { width: 100%; }
}
</style>
</head>
<body>

<nav class="topnav">
  <div class="container">
    <div class="topnav-actions" style="margin-left:auto;">
      <a href="dashboard-client.html" id="cancel-btn" class="btn btn-ghost btn-sm">✕ Cancel</a>
    </div>
  </div>
</nav>

<div class="wizard-shell">

  <!-- WIZARD LEFT NAV -->
  <div class="wizard-left">
    <div class="wizard-left-logo">Nexus<span>.</span></div>
    <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(247,244,239,.3);margin-bottom:16px;font-weight:700;">Project Posting</div>

    <div class="wizard-left-step">
      <div class="wzl-dot active" id="dot1">1</div>
      <div><div class="wzl-title active" id="t1">Choose Niche</div><div class="wzl-sub">Discipline & category</div></div>
    </div>
    <div class="wizard-left-step">
      <div class="wzl-dot" id="dot2">2</div>
      <div><div class="wzl-title" id="t2">Project Details</div><div class="wzl-sub">Title, scope, requirements</div></div>
    </div>
    <div class="wizard-left-step">
      <div class="wzl-dot" id="dot3">3</div>
      <div><div class="wzl-title" id="t3">Milestones & Budget</div><div class="wzl-sub">Payment structure</div></div>
    </div>
    <div class="wizard-left-step">
      <div class="wzl-dot" id="dot4">4</div>
      <div><div class="wzl-title" id="t4">NDA & Privacy</div><div class="wzl-sub">Confidentiality settings</div></div>
    </div>
    <div class="wizard-left-step">
      <div class="wzl-dot" id="dot5">5</div>
      <div><div class="wzl-title" id="t5">Review & Post</div><div class="wzl-sub">Confirm & go live</div></div>
    </div>

    <div style="margin-top:40px;padding-top:24px;border-top:1px solid rgba(255,255,255,.08);">
      <div style="font-size:.75rem;color:rgba(247,244,239,.3);line-height:1.7;">
        <div>✦ All postings are reviewed for niche alignment</div>
        <div class="mt-8">✦ Invitation-only tender available in Step 4</div>
        <div class="mt-8">✦ Funds not deducted until contract signed</div>
      </div>
    </div>
  </div>

  <!-- WIZARD RIGHT PANELS -->
  <div class="wizard-right">
  <form id="project-post-form" method="post" action="post-job.php" enctype="multipart/form-data" novalidate>

    <!-- STEP 1: NICHE -->
    <div class="wizard-step-panel active" id="step1">
      <div class="page-header">
        <div class="breadcrumb">Step 1 of 5</div>
        <h2>Choose Your Project Niche</h2>
        <p class="mt-4">The niche determines what fields appear in your project brief. Choose the closest match to your deliverable.</p>
      </div>
      <div class="niche-select-grid">
        <div class="niche-select-card">
          <div class="niche-card-icon">🧠</div>
          <div class="niche-card-name">Data Science & ML</div>
        </div>
        <div class="niche-select-card">
          <div class="niche-card-icon">⚖️</div>
          <div class="niche-card-name">Legal Consulting</div>
        </div>
        <div class="niche-select-card">
          <div class="niche-card-icon">🌐</div>
          <div class="niche-card-name">Technical Translation</div>
        </div>
        <div class="niche-select-card">
          <div class="niche-card-icon">📈</div>
          <div class="niche-card-name">Financial Modelling</div>
        </div>
        <div class="niche-select-card">
          <div class="niche-card-icon">🔬</div>
          <div class="niche-card-name">Biomedical Research</div>
        </div>
        <div class="niche-select-card">
          <div class="niche-card-icon">🔐</div>
          <div class="niche-card-name">Cybersecurity Audit</div>
        </div>
      </div>
      <input type="hidden" id="selected-niche" name="niche" required>
      <input type="hidden" id="niche-answers-json" name="niche_answers_json">
      <input type="hidden" id="milestones-json" name="milestones_json">
      <input type="hidden" id="total-budget-input" name="total_budget" value="0">
      <input type="hidden" id="platform-fee-input" name="platform_fee" value="0">
      <input type="hidden" id="specialist-receives-input" name="specialist_receives" value="0">
      <input type="hidden" id="first-escrow-input" name="first_escrow_required" value="0">
      <div class="field-error" id="niche-error">Please choose a project niche before continuing.</div>
      <div class="step-nav">
        <div></div>
        <button type="button" class="btn btn-primary">Continue to Project Details →</button>
      </div>
    </div>

    <!-- STEP 2: PROJECT DETAILS -->
    <div class="wizard-step-panel" id="step2">
      <div class="page-header">
        <div class="breadcrumb">Step 2 of 5</div>
        <h2>Project Details</h2>
        <p class="mt-4">Add the main project information and requirements.</p>
      </div>
      <div class="form-group">
        <label class="form-label">Project Title</label>
        <input type="text" id="project-title" name="project_title" class="form-control" placeholder="Be specific and professional" required>
      </div>
      <div class="form-group">
        <label class="form-label">Project Brief</label>
        <textarea id="project-brief" name="project_brief" class="form-control" rows="5" placeholder="Describe the full scope of the legal work required..." required></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Project Details & Full Requirements</label>
        <textarea id="project-full-requirements" name="project_full_requirements" class="form-control" rows="6" placeholder="List detailed deliverables, technical requirements, constraints, and acceptance criteria..." required></textarea>
      </div>
      <div id="niche-question-container" class="niche-question-group"></div>
      <div class="form-group">
        <label class="form-label">Ideal Candidate</label>
        <textarea id="project-ideal-candidate" name="ideal_candidate" class="form-control" rows="4" placeholder="Describe the specialist experience, skills, background, or working style you are looking for..." required></textarea>
      </div>
      <div class="step-nav">
        <button type="button" class="btn btn-outline">← Back</button>
        <button type="button" class="btn btn-primary">Continue to Milestones →</button>
      </div>
    </div>

    <!-- STEP 3: MILESTONES -->
    <div class="wizard-step-panel" id="step3">
      <div class="page-header">
        <div class="breadcrumb">Step 3 of 5</div>
        <h2>Milestones &amp; Budget</h2>
        <p class="mt-4">Break your project into funded phases. Specialists begin each phase only after escrow is confirmed.</p>
      </div>
      <div id="milestone-list">
        <div class="milestone-builder-row" id="ms-0">
          <div style="display:flex;gap:10px;align-items:center;grid-column:1">
            <div class="milestone-num-badge">1</div>
            <input type="text" class="form-control milestone-name" name="milestones[0][name]" placeholder="Milestone name">
          </div>
          <input type="number" class="form-control milestone-duration" name="milestones[0][duration_days]" min="1" step="1" inputmode="numeric" placeholder="Duration (days)">
          <div style="position:relative;">
            <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--ink-faint);font-family:var(--font-mono);font-size:.875rem;">$</span>
            <input type="number" class="form-control milestone-amount" name="milestones[0][amount]" min="1" step="1" style="padding-left:26px;" placeholder="0" oninput="recalcTotal()">
          </div>
          <button type="button" class="btn btn-ghost btn-icon" style="opacity:.4;cursor:not-allowed;">🗑</button>
          <div class="field-error milestone-row-error"></div>
        </div>
      </div>
      <button type="button" class="btn btn-outline btn-sm mt-8" onclick="addMilestone()">+ Add Milestone</button>
      <div class="milestone-total mt-12">
        <span class="text-sm text-muted">Total Project Budget</span>
        <span style="font-family:var(--font-mono);font-size:1.1rem;font-weight:500;" id="ms-total">$0</span>
      </div>
      <div class="budget-preview">
        <div><div class="text-xs text-muted mb-4">Platform Fee (6.5%)</div><div class="font-mono font-bold" id="platform-fee">$0</div></div>
        <div><div class="text-xs text-muted mb-4">Specialist Receives</div><div class="font-mono font-bold" id="specialist-receives">$0</div></div>
        <div><div class="text-xs text-muted mb-4">First Escrow Lock</div><div class="font-mono font-bold" id="first-escrow-lock">$0</div></div>
      </div>
      <div style="margin-top:20px;display:flex;gap:8px;align-items:center;">
        <input type="checkbox" id="free-revisions" name="free_revisions" value="1" style="accent-color:var(--gold);" checked>
        <label for="free-revisions" class="text-sm">Include 2 free revisions per milestone (additional revisions billed at agreed rate)</label>
      </div>
      <div class="step-nav">
        <button type="button" class="btn btn-outline" onclick="goStep(2)">← Back</button>
        <button type="button" class="btn btn-primary">Continue to NDA Settings →</button>
      </div>
    </div>

    <!-- STEP 4: NDA -->
    <div class="wizard-step-panel" id="step4">
      <div class="page-header">
        <div class="breadcrumb">Step 4 of 5</div>
        <h2>NDA & Privacy Settings</h2>
        <p class="mt-4">An NDA is auto-generated when any specialist is shortlisted. Customize confidentiality terms below.</p>
      </div>

      <div style="display:flex;gap:12px;margin-bottom:24px;">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.875rem;"><input type="radio" id="nda-type-standard" name="nda_type" value="standard" checked style="accent-color:var(--gold);"> Standard Nexus NDA</label>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.875rem;"><input type="radio" id="nda-type-custom" name="nda_type" value="custom" style="accent-color:var(--gold);"> Upload Custom NDA</label>
      </div>

      <div id="nda-standard-fields">
        <div class="nda-preview">
          <strong>NON-DISCLOSURE AGREEMENT - AUTO-GENERATED PREVIEW</strong><br><br>
          This Non-Disclosure Agreement ("Agreement") is entered into between <strong>[CLIENT: Amira Tawfik / FinCorp Egypt]</strong> ("Disclosing Party") and <strong>[SPECIALIST: <span id="nda-specialist-name">To be added when specialist bids</span>]</strong> ("Receiving Party") for Project Ref. <strong>NX-2025-[XXXX]</strong>.<br><br>
          1. CONFIDENTIAL INFORMATION: All project details, documents, data, and communications shared through the Nexus Platform in connection with this engagement shall be treated as strictly confidential...<br><br>
          2. TERM: This Agreement remains in force for <strong>[<span id="nda-term-value">2 years</span>]</strong> following the conclusion of the engagement...<br><br>
          3. GOVERNING LAW: This Agreement is governed by the laws of <strong>[Egypt / to be determined per jurisdiction]</strong>...
          <br><br><em>[Full NDA generated upon specialist shortlisting]</em>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">NDA Duration</label>
            <select class="form-control" id="nda-duration" name="nda_duration" required>
              <option value="">Select NDA duration</option>
              <option>1 year</option>
              <option selected>2 years</option>
              <option>3 years</option>
              <option>Indefinite</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Liquidated Damages Clause</label>
            <select class="form-control" id="nda-damages" name="nda_damages" required>
              <option value="">Select damages clause</option>
              <option value="none">None (Standard)</option>
              <option value="10000" selected>$10,000 per breach</option>
              <option value="25000">$25,000 per breach</option>
              <option value="custom">Custom amount</option>
            </select>
            <div class="form-group" id="nda-custom-amount-wrap" style="display:none;margin-top:12px;">
              <label class="form-label">Custom Damages Amount (USD)</label>
              <input type="number" id="nda-custom-amount" name="nda_custom_amount" class="form-control" min="1" placeholder="Enter amount in USD">
            </div>
          </div>
        </div>
      </div>

      <div id="nda-upload-fields" style="display:none;">
        <div class="card" style="padding:24px;margin-bottom:24px;">
          <div style="display:flex;align-items:flex-start;gap:16px;">
            <div style="font-size:2rem;">📄</div>
            <div style="flex:1;">
              <h3 style="margin:0 0 12px 0;font-size:1rem;">Custom NDA</h3>
              <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload your own NDA document for this project.</p>

              <div class="upload-zone" id="ndaUploadZone" ondrop="handleFilesDrop(event, 'nda')" ondragover="addDragHover(event)" ondragleave="removeDragHover(event)" onclick="document.getElementById('ndaFile').click()">
                <div style="font-size:2rem;margin-bottom:8px;">📤</div>
                <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
                <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">PDF, DOC, DOCX • Max 10MB</p>
                <input type="file" id="ndaFile" name="nda_file" data-error-target="nda-upload-error" data-upload-zone="ndaUploadZone" style="display:none;" accept=".pdf,.doc,.docx" onchange="previewFile(this, 'nda')">
                <input type="hidden" id="ndaFileSelected" name="nda_file_selected" value="">
              </div>
              <div id="ndaFilePreview" style="display:none;margin-top:12px;"></div>
              <span id="ndaStatus" style="display:none;">Not uploaded</span>
              <div class="field-error" id="nda-upload-error">Please upload your custom NDA file.</div>
            </div>
          </div>
        </div>
      </div>

      <h4 class="mb-12">Profile Visibility Controls</h4>
      <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
        <label style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;">
          <input type="checkbox" id="profile-masking" name="profile_masking" value="1" style="accent-color:var(--gold);margin-top:3px;" checked>
          <span>Mask client organization name in specialist-visible project listing</span>
        </label>
      </div>

      <div class="form-group" style="margin-bottom:20px;">
        <label class="form-label" style="text-transform:uppercase;letter-spacing:.08em;">Visibility</label>
        <div style="display:flex;gap:18px;align-items:center;margin-bottom:8px;">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.875rem;">
            <input type="radio" name="nda_visibility" value="public" style="accent-color:var(--gold);">
            <span>Public (all verified specialists)</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.875rem;">
            <input type="radio" name="nda_visibility" value="invitation-only" checked style="accent-color:var(--gold);">
            <span>Invitation-only Tender</span>
          </label>
        </div>
        <p class="form-hint">Invitation-only projects are only visible to specialists you personally invite.</p>
      </div>

      <div class="step-nav">
        <button type="button" class="btn btn-outline">← Back</button>
        <button type="button" class="btn btn-primary">Continue to Review →</button>
      </div>
    </div>

    <!-- STEP 5: REVIEW & POST -->
    <div class="wizard-step-panel" id="step5">
      <div class="page-header">
        <div class="breadcrumb">Step 5 of 5 · Final Review</div>
        <h2>Review & Post Your Project</h2>
        <p class="mt-4">Verify all details before going live. You can edit this after posting.</p>
      </div>
      <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;margin-bottom:32px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;font-size:0.9rem;">
          <div>
            <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">PROJECT TITLE</div>
            <div style="color:var(--ink);" id="review-title">-</div>
          </div>
          <div>
            <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">NICHE</div>
            <div style="color:var(--ink);" id="review-niche">-</div>
          </div>
          <div>
            <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">BUDGET</div>
            <div style="color:var(--ink);" id="review-budget">-</div>
          </div>
          <div>
            <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">TIMELINE</div>
            <div style="color:var(--ink);" id="review-timeline">-</div>
          </div>
          <div>
            <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">PRIVACY LEVEL</div>
            <div style="color:var(--ink);" id="review-privacy">-</div>
          </div>
          <div style="grid-column:1 / -1;">
            <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">PROJECT BRIEF</div>
            <div style="color:var(--ink);" id="review-brief">-</div>
          </div>
          <div style="grid-column:1 / -1;">
            <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">PROJECT DETAILS & FULL REQUIREMENTS</div>
            <div style="color:var(--ink);" id="review-full-requirements">-</div>
          </div>
          <div style="grid-column:1 / -1;">
            <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">IDEAL CANDIDATE</div>
            <div style="color:var(--ink);" id="review-ideal-candidate">-</div>
          </div>
        </div>
      </div>
      <div class="review-section">
        <h4>Niche-Specific Requirements</h4>
        <div id="review-niche-answers"></div>
      </div>
      <div class="review-section">
        <h4>Milestones &amp; Budget</h4>
        <div id="review-milestones"></div>
        <div class="review-row" style="border-top:1px solid var(--border);padding-top:10px;margin-top:10px;">
          <span class="label">Total Budget</span>
          <span class="val" id="review-total-budget">$0</span>
        </div>
        <div class="review-row">
          <span class="label">First Escrow Required</span>
          <span class="val" id="review-first-escrow">$0</span>
        </div>
      </div>
      <div class="review-section">
        <h4>NDA &amp; Privacy</h4>
        <div class="review-row">
          <span class="label">NDA Type</span>
          <span class="val" id="review-nda-type">-</span>
        </div>
        <div class="review-row">
          <span class="label">Damages Clause</span>
          <span class="val" id="review-damages">-</span>
        </div>
        <div class="review-row">
          <span class="label">Profile Masking</span>
          <span class="val" id="review-profile-masking">-</span>
        </div>
      </div>
      <div style="background:#fef9f0;border:1px solid #f0d9ba;border-radius:var(--radius-md);padding:16px;margin-bottom:32px;">
        <div style="font-weight:700;color:var(--rust);font-size:0.9rem;margin-bottom:8px;">💡 Pro Tip</div>
        <div style="font-size:0.85rem;color:var(--ink-mid);">Projects with clear scope, realistic budget, and specific requirements attract higher-quality bids. You'll typically receive first responses within 2-3 hours.</div>
      </div>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <input type="checkbox" id="agree-terms" name="agree_terms" value="1" data-error-target="terms-error" required>
        <label for="agree-terms" style="font-size:0.9rem;color:var(--ink-mid);">I have read and agree to the <a href="#" style="color:var(--gold);text-decoration:none;font-weight:700;">Posting Guidelines & Terms</a></label>
      </div>
      <div class="field-error" id="terms-error">You must agree to the Posting Guidelines & Terms before posting.</div>
      <div class="step-nav">
        <button type="button" class="btn btn-outline">← Back</button>
        <button type="submit" class="btn btn-primary" id="post-btn">Post Project</button>
      </div>
    </div>

  </form>

  </div>
</div>

<div id="exit-confirm-backdrop" class="exit-confirm-backdrop" style="display:none;">
  <div class="exit-confirm-modal" role="dialog" aria-modal="true" aria-labelledby="exit-confirm-title">
    <h3 class="exit-confirm-title" id="exit-confirm-title">Exit Without Saving?</h3>
    <p class="exit-confirm-text">Are you sure you want to exit? Your changes will not be saved.</p>
    <div class="exit-confirm-actions">
      <button type="button" class="btn btn-outline" id="exit-stay-btn">Stay Here</button>
      <button type="button" class="btn btn-primary" id="exit-confirm-btn">Yes, Exit</button>
    </div>
  </div>
</div>

<div id="language-pair-backdrop" class="language-pair-backdrop">
  <div class="language-pair-modal" role="dialog" aria-modal="true" aria-labelledby="language-pair-title">
    <h3 class="language-pair-title" id="language-pair-title">Choose Language Pair</h3>
    <p class="language-pair-text">Select any source and target language. Use the swap control to reverse the pair.</p>
    <div class="language-pair-grid">
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label" for="source-language-select">Source Language</label>
        <select class="form-control" id="source-language-select"></select>
      </div>
      <button type="button" class="btn btn-outline language-swap-btn" id="language-swap-btn" aria-label="Swap source and target languages">⇄</button>
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label" for="target-language-select">Target Language</label>
        <select class="form-control" id="target-language-select"></select>
      </div>
    </div>
    <div class="field-error" id="language-pair-error">Choose two different languages.</div>
    <div class="language-pair-actions">
      <button type="button" class="btn btn-outline" id="language-pair-cancel">Cancel</button>
      <button type="button" class="btn btn-primary" id="language-pair-save">Use Pair</button>
    </div>
  </div>
</div>

<script>
let currentStep = 1;
let selectedNiche = '';
let activeLanguagePairField = null;
let activeLanguagePairCard = null;

const languageOptions = [
  'Afrikaans', 'Albanian', 'Amharic', 'Arabic', 'Armenian', 'Assamese', 'Aymara', 'Azerbaijani',
  'Bambara', 'Basque', 'Belarusian', 'Bengali', 'Bhojpuri', 'Bosnian', 'Bulgarian', 'Burmese',
  'Catalan', 'Cebuano', 'Chinese (Simplified)', 'Chinese (Traditional)', 'Corsican', 'Croatian', 'Czech',
  'Danish', 'Dhivehi', 'Dogri', 'Dutch', 'English', 'Esperanto', 'Estonian', 'Ewe',
  'Filipino', 'Finnish', 'French', 'Frisian', 'Galician', 'Georgian', 'German', 'Greek', 'Guarani', 'Gujarati',
  'Haitian Creole', 'Hausa', 'Hawaiian', 'Hebrew', 'Hindi', 'Hmong', 'Hungarian',
  'Icelandic', 'Igbo', 'Ilocano', 'Indonesian', 'Irish', 'Italian', 'Japanese', 'Javanese',
  'Kannada', 'Kazakh', 'Khmer', 'Kinyarwanda', 'Konkani', 'Korean', 'Krio', 'Kurdish (Kurmanji)', 'Kurdish (Sorani)', 'Kyrgyz',
  'Lao', 'Latin', 'Latvian', 'Lingala', 'Lithuanian', 'Luganda', 'Luxembourgish',
  'Macedonian', 'Maithili', 'Malagasy', 'Malay', 'Malayalam', 'Maltese', 'Maori', 'Marathi', 'Meiteilon', 'Mizo', 'Mongolian',
  'Nepali', 'Norwegian', 'Nyanja', 'Odia', 'Oromo', 'Pashto', 'Persian', 'Polish', 'Portuguese', 'Punjabi',
  'Quechua', 'Romanian', 'Russian', 'Samoan', 'Sanskrit', 'Scots Gaelic', 'Sepedi', 'Serbian', 'Sesotho', 'Shona', 'Sindhi', 'Sinhala', 'Slovak', 'Slovenian', 'Somali', 'Spanish', 'Sundanese', 'Swahili', 'Swedish',
  'Tajik', 'Tamil', 'Tatar', 'Telugu', 'Thai', 'Tigrinya', 'Tsonga', 'Turkish', 'Turkmen', 'Twi',
  'Ukrainian', 'Urdu', 'Uyghur', 'Uzbek', 'Vietnamese', 'Welsh', 'Xhosa', 'Yiddish', 'Yoruba', 'Zulu'
];

const nicheQuestionSets = {
  'Data Science & ML': [
    {
      id: 'data_source_status',
      label: 'Data Sources & Access Status',
      type: 'cards',
      options: ['Clean labeled dataset', 'Raw data needs preparation', 'Data access pending', 'Need data plan defined']
    },
    {
      id: 'ml_target_metric',
      label: 'Primary ML Task',
      type: 'cards',
      options: ['Prediction / classification', 'Forecasting', 'Segmentation / clustering', 'NLP / text analysis', 'Recommendation / ranking']
    },
    {
      id: 'ml_model_constraints',
      label: 'Model Constraints',
      type: 'cards',
      options: ['Interpretability required', 'Accuracy prioritized', 'Low-latency inference', 'Regulated / audited model', 'Research prototype']
    },
    {
      id: 'ml_deployment_context',
      label: 'Deployment Context',
      type: 'cards',
      options: ['Notebook handoff', 'API-ready model', 'Dashboard / report', 'Production pipeline', 'Explainability audit']
    }
  ],
  'Legal Consulting': [
    {
      id: 'legal_matter_type',
      label: 'Matter Type',
      type: 'cards',
      options: ['Contract review', 'Regulatory compliance', 'Due diligence', 'Dispute / arbitration', 'Entity setup', 'Policy drafting']
    },
    {
      id: 'legal_jurisdictions',
      label: 'Jurisdictions & Governing Law',
      type: 'cards',
      options: ['Single local jurisdiction', 'Cross-border MENA', 'EU / UK involved', 'US involved', 'International arbitration']
    },
    {
      id: 'legal_documents',
      label: 'Document Stage',
      type: 'cards',
      options: ['First draft needed', 'Existing draft review', 'Negotiation markup', 'Clause risk summary', 'Full agreement package']
    },
    {
      id: 'legal_deadline_risk',
      label: 'Deadline & Risk Sensitivity',
      type: 'cards',
      options: ['Low urgency', 'Signing date soon', 'Filing deadline', 'High confidentiality', 'Material business risk']
    }
  ],
  'Technical Translation': [
    {
      id: 'translation_language_pair',
      label: 'Source & Target Languages',
      type: 'cards',
      options: ['Arabic to English', 'English to Arabic', 'English to French', 'English to German', 'Other pair']
    },
    {
      id: 'translation_volume_format',
      label: 'Volume & File Formats',
      type: 'cards',
      options: ['Under 5k words', '5k-20k words', '20k+ words', 'Slides / tables', 'Subtitles / timed text']
    },
    {
      id: 'translation_domain',
      label: 'Technical Domain',
      type: 'cards',
      options: ['Legal', 'Medical / clinical', 'Engineering', 'Software / API docs', 'Finance', 'Academic research']
    },
    {
      id: 'translation_quality_controls',
      label: 'Terminology & Review Requirements',
      type: 'cards',
      options: ['Use existing glossary', 'Create terminology list', 'Certified translation', 'Native review pass', 'CAT tool required']
    }
  ],
  'Financial Modelling': [
    {
      id: 'finance_model_purpose',
      label: 'Model Purpose',
      type: 'cards',
      options: ['Fundraising / pitch deck', 'Valuation', 'Budget forecast', 'Project finance', 'M&A / due diligence', 'Scenario planning']
    },
    {
      id: 'finance_inputs',
      label: 'Available Financial Inputs',
      type: 'cards',
      options: ['Historical financials ready', 'KPIs only', 'Assumptions need building', 'Messy spreadsheet cleanup', 'No data yet']
    },
    {
      id: 'finance_outputs',
      label: 'Required Outputs',
      type: 'cards',
      options: ['3-statement model', 'DCF valuation', 'Unit economics', 'Sensitivity tables', 'Investor-ready workbook']
    },
    {
      id: 'finance_scenario_depth',
      label: 'Scenario Complexity',
      type: 'cards',
      options: ['Base case only', 'Base / upside / downside', 'Probabilistic scenarios', 'Multi-currency', 'Multi-entity consolidation']
    }
  ],
  'Biomedical Research': [
    {
      id: 'biomed_study_type',
      label: 'Study Or Research Type',
      type: 'cards',
      options: ['Literature review', 'Protocol design', 'Data analysis', 'Regulatory writing', 'Grant / manuscript support', 'Clinical evidence summary']
    },
    {
      id: 'biomed_data_materials',
      label: 'Data, Samples, Or Materials',
      type: 'cards',
      options: ['Dataset available', 'Lab outputs available', 'Patient cohort data', 'Papers only', 'Materials still pending']
    },
    {
      id: 'biomed_ethics',
      label: 'Ethics, Privacy & Compliance',
      type: 'cards',
      options: ['IRB approved', 'IRB pending', 'De-identified data', 'HIPAA / GDPR sensitive', 'No human subject data']
    },
    {
      id: 'biomed_deliverable_standard',
      label: 'Deliverable Standard',
      type: 'cards',
      options: ['Academic manuscript', 'Regulatory-grade report', 'Internal scientific memo', 'Statistical analysis plan', 'Grant-ready package']
    }
  ],
  'Cybersecurity Audit': [
    {
      id: 'security_asset_scope',
      label: 'Assets In Scope',
      type: 'cards',
      options: ['Web app', 'API', 'Cloud account', 'Network', 'Code repository', 'Multiple assets']
    },
    {
      id: 'security_audit_type',
      label: 'Audit Type',
      type: 'cards',
      options: ['Web app pentest', 'Cloud security review', 'Network assessment', 'Code review', 'Compliance gap assessment', 'Incident readiness']
    },
    {
      id: 'security_access_level',
      label: 'Access Level',
      type: 'cards',
      options: ['Black-box', 'Grey-box', 'White-box', 'Credentialed review', 'Read-only cloud/repo access']
    },
    {
      id: 'security_reporting_needs',
      label: 'Reporting & Remediation Expectations',
      type: 'cards',
      options: ['Executive report', 'Technical PoC detail', 'Compliance mapping', 'Remediation plan', 'Retest included']
    }
  ]
};

function getStepPanel(stepNumber) {
  return document.getElementById('step' + stepNumber);
}

function getFieldLabel(field) {
  const group = field.closest('.form-group');
  const label = group?.querySelector('.form-label');
  if (!label) return 'this field';
  return label.textContent.replace(/\s+/g, ' ').trim();
}

function getOrCreateFieldError(field) {
  const targetId = field.getAttribute('data-error-target');
  if (targetId) return document.getElementById(targetId);

  const group = field.closest('.form-group') || field.parentElement;
  if (!group) return null;
  let error = group.querySelector('.field-error');
  if (!error) {
    error = document.createElement('div');
    error.className = 'field-error';
    group.appendChild(error);
  }
  return error;
}

function showFieldError(field, message) {
  const error = getOrCreateFieldError(field);
  if (error) {
    error.textContent = message;
    error.classList.add('show');
  }
  field.classList.add('input-invalid');
  const uploadZoneId = field.getAttribute('data-upload-zone');
  if (uploadZoneId) {
    document.getElementById(uploadZoneId)?.classList.add('error');
  }
  const cardGroupId = field.getAttribute('data-card-group');
  if (cardGroupId) {
    document.getElementById(cardGroupId)?.classList.add('input-invalid');
  }
}

function clearFieldError(field) {
  field.classList.remove('input-invalid');
  const uploadZoneId = field.getAttribute('data-upload-zone');
  if (uploadZoneId) {
    document.getElementById(uploadZoneId)?.classList.remove('error');
  }
  const cardGroupId = field.getAttribute('data-card-group');
  if (cardGroupId) {
    document.getElementById(cardGroupId)?.classList.remove('input-invalid');
  }
  const targetId = field.getAttribute('data-error-target');
  const error = targetId
    ? document.getElementById(targetId)
    : (field.closest('.form-group') || field.parentElement)?.querySelector('.field-error');
  if (error) {
    error.classList.remove('show');
    error.textContent = '';
  }
}

function clearStepErrors(stepNumber) {
  const panel = getStepPanel(stepNumber);
  if (!panel) return;
  panel.querySelectorAll('.input-invalid').forEach(field => field.classList.remove('input-invalid'));
  panel.querySelectorAll('.niche-answer-grid.input-invalid').forEach(group => group.classList.remove('input-invalid'));
  panel.querySelectorAll('.upload-zone.error').forEach(zone => zone.classList.remove('error'));
  panel.querySelectorAll('.field-error').forEach(error => {
    error.classList.remove('show');
    if (error.id !== 'niche-error') error.textContent = '';
  });
}

function isFieldValid(field) {
  if (field.id === 'project-budget') {
    const value = Number(field.value);
    return !Number.isNaN(value) && value > 0;
  }
  if (field.type === 'file') return (field.files?.length || 0) > 0;
  if (field.type === 'checkbox') return field.checked;
  if (field.type === 'number') {
    if (field.value.trim() === '') return false;
    const value = Number(field.value);
    if (Number.isNaN(value)) return false;
    if (field.min && value < Number(field.min)) return false;
    return true;
  }
  return field.value.trim() !== '';
}

function getCustomErrorMessage(field) {
  const label = getFieldLabel(field).toLowerCase();
  if (field.id === 'project-budget') {
    return 'Please add a project budget before continuing.';
  }
  if (field.type === 'checkbox' && field.id === 'agree-terms') {
    return 'You must agree to the Posting Guidelines & Terms before posting.';
  }
  if (field.type === 'checkbox') {
    return 'This checkbox is required.';
  }
  if (field.type === 'file') {
    return 'Please upload your custom NDA file.';
  }
  if (field.tagName === 'SELECT') {
    return `Please select ${label}.`;
  }
  if (field.type === 'number') {
    return `Please enter a valid ${label}.`;
  }
  return `Please fill ${label}.`;
}

function renderNicheQuestions(niche) {
  const container = document.getElementById('niche-question-container');
  if (!container) return;

  const questions = nicheQuestionSets[niche] || [];
  container.innerHTML = '';

  if (!questions.length) {
    container.style.display = 'none';
    return;
  }

  container.style.display = 'block';
  const heading = document.createElement('h3');
  heading.className = 'niche-question-heading';
  heading.textContent = `${niche} Questions`;
  container.appendChild(heading);

  questions.forEach(question => {
    const group = document.createElement('div');
    const label = document.createElement('label');
    let field;

    group.className = 'form-group';
    label.className = 'form-label';
    label.setAttribute('for', `niche-${question.id}`);
    label.textContent = question.label;

    if (question.type === 'cards') {
      const grid = document.createElement('div');
      field = document.createElement('input');

      field.type = 'hidden';
      field.className = 'niche-question-field';
      field.dataset.cardGroup = `niche-card-group-${question.id}`;
      grid.className = 'niche-answer-grid';
      grid.id = field.dataset.cardGroup;

      question.options.forEach(optionText => {
        const card = document.createElement('button');
        card.type = 'button';
        card.className = 'niche-answer-card';
        card.dataset.value = optionText;
        if (question.id === 'translation_language_pair' && optionText === 'Other pair') {
          card.dataset.languagePairPicker = 'true';
        }
        card.textContent = optionText;
        card.setAttribute('aria-pressed', 'false');
        grid.appendChild(card);
      });

      group.append(label, field, grid);
    } else if (question.type === 'select') {
      field = document.createElement('select');
      field.className = 'form-control niche-question-field';
      question.options.forEach(optionText => {
        const option = document.createElement('option');
        option.value = optionText;
        option.textContent = optionText;
        field.appendChild(option);
      });
    } else if (question.type === 'textarea') {
      field = document.createElement('textarea');
      field.className = 'form-control niche-question-field';
      field.rows = question.rows || 3;
      field.placeholder = question.placeholder || '';
    } else {
      field = document.createElement('input');
      field.type = question.type || 'text';
      field.className = 'form-control niche-question-field';
      field.placeholder = question.placeholder || '';
      if (question.type === 'number') {
        field.min = question.min || '1';
        field.step = question.step || '1';
      }
    }

    field.id = `niche-${question.id}`;
    field.name = `niche_answers[${question.id}]`;
    field.required = true;
    field.dataset.answerId = question.id;
    field.dataset.reviewLabel = question.label;
    field.dataset.nicheQuestion = 'true';

    if (question.type !== 'cards') {
      group.append(label, field);
    }
    container.appendChild(group);
  });
}

function getNicheAnswers() {
  return Array.from(document.querySelectorAll('.niche-question-field')).map(field => {
    const value = field.tagName === 'SELECT'
      ? field.selectedOptions?.[0]?.textContent.trim()
      : field.value.trim();
    return {
      id: field.dataset.answerId || field.name,
      label: field.dataset.reviewLabel || getFieldLabel(field),
      value: value || '-'
    };
  });
}

function renderNicheAnswers() {
  const container = document.getElementById('review-niche-answers');
  if (!container) return;

  const answers = getNicheAnswers();
  container.innerHTML = '';

  if (!answers.length) {
    const row = document.createElement('div');
    row.className = 'review-row';
    row.innerHTML = '<span class="label">Questions</span><span class="val">Choose a niche to generate questions</span>';
    container.appendChild(row);
    return;
  }

  answers.forEach(answer => {
    const row = document.createElement('div');
    const label = document.createElement('span');
    const value = document.createElement('span');

    row.className = 'review-row';
    label.className = 'label';
    value.className = 'val';
    label.textContent = answer.label;
    value.textContent = answer.value;
    row.append(label, value);
    container.appendChild(row);
  });
}

function syncNicheAnswersPayload() {
  const payload = getNicheAnswers().map(answer => ({
    id: answer.id,
    label: answer.label,
    value: answer.value === '-' ? '' : answer.value
  }));
  const field = document.getElementById('niche-answers-json');
  if (field) field.value = JSON.stringify(payload);
}

function populateLanguageSelect(select, placeholder) {
  if (!select || select.options.length) return;

  const emptyOption = document.createElement('option');
  emptyOption.value = '';
  emptyOption.textContent = placeholder;
  select.appendChild(emptyOption);

  languageOptions.forEach(language => {
    const option = document.createElement('option');
    option.value = language;
    option.textContent = language;
    select.appendChild(option);
  });
}

function parseLanguagePair(value) {
  const parts = value.split(' to ');
  return {
    source: parts[0] || '',
    target: parts[1] || ''
  };
}

function setLanguagePairError(message) {
  const error = document.getElementById('language-pair-error');
  if (!error) return;
  error.textContent = message;
  error.classList.add('show');
}

function clearLanguagePairError() {
  const error = document.getElementById('language-pair-error');
  if (!error) return;
  error.classList.remove('show');
}

function openLanguagePairModal(field, card) {
  const backdrop = document.getElementById('language-pair-backdrop');
  const sourceSelect = document.getElementById('source-language-select');
  const targetSelect = document.getElementById('target-language-select');
  if (!backdrop || !sourceSelect || !targetSelect) return;

  activeLanguagePairField = field;
  activeLanguagePairCard = card;
  populateLanguageSelect(sourceSelect, 'Choose source language');
  populateLanguageSelect(targetSelect, 'Choose target language');

  const existingPair = parseLanguagePair(field.value || '');
  sourceSelect.value = existingPair.source || '';
  targetSelect.value = existingPair.target || '';
  clearLanguagePairError();
  backdrop.style.display = 'flex';
  sourceSelect.focus();
}

function closeLanguagePairModal() {
  const backdrop = document.getElementById('language-pair-backdrop');
  if (backdrop) backdrop.style.display = 'none';
  clearLanguagePairError();
  activeLanguagePairField = null;
  activeLanguagePairCard = null;
}

function saveLanguagePair() {
  const sourceSelect = document.getElementById('source-language-select');
  const targetSelect = document.getElementById('target-language-select');
  const source = sourceSelect?.value || '';
  const target = targetSelect?.value || '';

  if (!source || !target) {
    setLanguagePairError('Choose both source and target languages.');
    return;
  }

  if (source === target) {
    setLanguagePairError('Choose two different languages.');
    return;
  }

  if (!activeLanguagePairField || !activeLanguagePairCard) return;

  const pair = `${source} to ${target}`;
  const group = activeLanguagePairCard.closest('.form-group');
  activeLanguagePairField.value = pair;
  group?.querySelectorAll('.niche-answer-card').forEach(option => {
    option.classList.toggle('selected', option === activeLanguagePairCard);
    option.setAttribute('aria-pressed', option === activeLanguagePairCard ? 'true' : 'false');
  });
  activeLanguagePairCard.textContent = pair;
  clearFieldError(activeLanguagePairField);
  updateReviewSummary();
  closeLanguagePairModal();
}

function flipLanguagePair() {
  const sourceSelect = document.getElementById('source-language-select');
  const targetSelect = document.getElementById('target-language-select');
  if (!sourceSelect || !targetSelect) return;
  const source = sourceSelect.value;
  sourceSelect.value = targetSelect.value;
  targetSelect.value = source;
  clearLanguagePairError();
}

function money(value) {
  return '$' + Math.round(value || 0).toLocaleString();
}

function getSelectedOptionText(id) {
  const select = document.getElementById(id);
  return select?.selectedOptions?.[0]?.textContent.trim() || '';
}

function getSelectedRadioLabel(name) {
  const radio = document.querySelector(`input[name="${name}"]:checked`);
  return radio?.closest('label')?.textContent.replace(/\s+/g, ' ').trim() || '';
}

function compactDuration(value) {
  const normalized = value.trim().replace(/\s*days?\b/i, 'd');
  return /^\d+(?:\.\d+)?$/.test(normalized) ? `${normalized}d` : normalized;
}

function getMilestoneFields(row) {
  return {
    name: row.querySelector('.milestone-name'),
    duration: row.querySelector('.milestone-duration'),
    amount: row.querySelector('.milestone-amount')
  };
}

function getMilestones() {
  return Array.from(document.querySelectorAll('#milestone-list .milestone-builder-row'))
    .map(row => {
      const fields = getMilestoneFields(row);
      return {
        name: fields.name?.value.trim() || '',
        duration: fields.duration?.value.trim() || '',
        amount: parseFloat(fields.amount?.value) || 0
      };
    })
    .filter(milestone => milestone.name || milestone.duration || milestone.amount);
}

function isPositiveNumberField(field) {
  if (!field || field.value.trim() === '') return false;
  const value = Number(field.value);
  return !Number.isNaN(value) && value > 0;
}

function isMilestoneRowComplete(row) {
  const fields = getMilestoneFields(row);
  return Boolean(fields.name?.value.trim()) && isPositiveNumberField(fields.duration) && isPositiveNumberField(fields.amount);
}

function clearMilestoneRowError(row) {
  if (!row) return;
  row.querySelectorAll('.input-invalid').forEach(field => field.classList.remove('input-invalid'));
  const error = row.querySelector('.milestone-row-error');
  if (error) {
    error.classList.remove('show');
    error.textContent = '';
  }
}

function validateMilestones() {
  const rows = Array.from(document.querySelectorAll('#milestone-list .milestone-builder-row'));
  let isValid = true;

  rows.forEach((row, index) => {
    const fields = getMilestoneFields(row);
    const invalidFields = [];

    if (!fields.name?.value.trim()) invalidFields.push(fields.name);
    if (!isPositiveNumberField(fields.duration)) invalidFields.push(fields.duration);
    if (!isPositiveNumberField(fields.amount)) invalidFields.push(fields.amount);

    if (!invalidFields.length) {
      clearMilestoneRowError(row);
      return;
    }

    isValid = false;
    invalidFields.forEach(field => field?.classList.add('input-invalid'));

    const error = row.querySelector('.milestone-row-error');
    if (error) {
      error.textContent = `Milestone ${index + 1}: enter a name, duration in days, and budget before continuing.`;
      error.classList.add('show');
    }
  });

  return isValid;
}

function parseDurationDays(value) {
  const match = value.match(/(\d+(?:\.\d+)?)/);
  return match ? Number(match[1]) : 0;
}

function formatTimeline(milestones) {
  if (!milestones.length) return '-';
  const totalDays = milestones.reduce((sum, milestone) => sum + parseDurationDays(milestone.duration), 0);
  return totalDays ? `${totalDays}d across ${milestones.length} milestones` : `${milestones.length} milestones`;
}

function renderReviewMilestones(milestones) {
  const container = document.getElementById('review-milestones');
  if (!container) return;
  container.innerHTML = '';

  if (!milestones.length) {
    const row = document.createElement('div');
    row.className = 'review-row';
    row.innerHTML = '<span class="label">Milestones</span><span class="val">-</span>';
    container.appendChild(row);
    return;
  }

  milestones.forEach((milestone, index) => {
    const row = document.createElement('div');
    const label = document.createElement('span');
    const value = document.createElement('span');
    const parts = [
      milestone.name || 'Untitled milestone',
      milestone.duration ? compactDuration(milestone.duration) : '',
      money(milestone.amount)
    ].filter(Boolean);

    row.className = 'review-row';
    label.className = 'label';
    value.className = 'val';
    label.textContent = 'Milestone ' + (index + 1);
    value.textContent = parts.join(' · ');
    row.append(label, value);
    container.appendChild(row);
  });
}

function getDamagesSummary() {
  if (document.getElementById('nda-type-custom')?.checked) return 'Defined in uploaded NDA';
  const damages = document.getElementById('nda-damages');
  if (damages?.value === 'custom') {
    const customAmount = Number(document.getElementById('nda-custom-amount')?.value || 0);
    return customAmount ? `${money(customAmount)} per breach` : 'Custom amount';
  }
  return getSelectedOptionText('nda-damages') || '-';
}

function updateReviewSummary() {
  const title = document.getElementById('project-title')?.value.trim() || '-';
  const brief = document.getElementById('project-brief')?.value.trim() || '-';
  const fullRequirements = document.getElementById('project-full-requirements')?.value.trim() || '-';
  const idealCandidate = document.getElementById('project-ideal-candidate')?.value.trim() || '-';
  const milestones = getMilestones();
  const totalBudget = milestones.reduce((sum, milestone) => sum + milestone.amount, 0);
  const firstEscrow = milestones[0]?.amount || 0;
  const isCustomNda = document.getElementById('nda-type-custom')?.checked;
  const ndaDuration = getSelectedOptionText('nda-duration') || '2 years';
  const ndaFile = document.getElementById('ndaFileSelected')?.value || '';

  document.getElementById('review-title').textContent = title;
  document.getElementById('review-niche').textContent = selectedNiche || '-';
  document.getElementById('review-budget').textContent = totalBudget ? money(totalBudget) : '-';
  document.getElementById('review-timeline').textContent = formatTimeline(milestones);
  document.getElementById('review-privacy').textContent = getSelectedRadioLabel('nda_visibility') || '-';
  document.getElementById('review-brief').textContent = brief;
  document.getElementById('review-full-requirements').textContent = fullRequirements;
  document.getElementById('review-ideal-candidate').textContent = idealCandidate;
  renderNicheAnswers();
  renderReviewMilestones(milestones);
  document.getElementById('review-total-budget').textContent = money(totalBudget);
  document.getElementById('review-first-escrow').textContent = firstEscrow ? `${money(firstEscrow)} (on contract signing)` : '$0';
  document.getElementById('review-nda-type').textContent = isCustomNda ? `Custom NDA · ${ndaFile || 'upload pending'}` : `Standard Nexus NDA · ${ndaDuration}`;
  document.getElementById('review-damages').textContent = getDamagesSummary();
  document.getElementById('review-profile-masking').textContent = document.getElementById('profile-masking')?.checked ? 'Client org name hidden' : 'Client org name visible';
  syncNicheAnswersPayload();
  syncMilestonesPayload();
}

function syncNdaPreviewDuration() {
  const ndaDuration = document.getElementById('nda-duration');
  const ndaTermValue = document.getElementById('nda-term-value');
  if (!ndaDuration || !ndaTermValue) return;
  ndaTermValue.textContent = ndaDuration.value || '2 years';
}

function toggleCustomDamagesField() {
  const damagesSelect = document.getElementById('nda-damages');
  const customWrap = document.getElementById('nda-custom-amount-wrap');
  const customInput = document.getElementById('nda-custom-amount');
  if (!damagesSelect || !customWrap || !customInput) return;

  if (document.getElementById('nda-type-custom')?.checked) {
    customWrap.style.display = 'none';
    customInput.required = false;
    customInput.value = '';
    clearFieldError(customInput);
    return;
  }

  const isCustom = damagesSelect.value === 'custom';
  customWrap.style.display = isCustom ? 'block' : 'none';
  customInput.required = isCustom;

  if (!isCustom) {
    customInput.value = '';
    clearFieldError(customInput);
  }
}

let msCount = 1;
function renumberMilestones() {
  document.querySelectorAll('#milestone-list .milestone-builder-row').forEach((row, index) => {
    const badge = row.querySelector('.milestone-num-badge');
    if (badge) badge.textContent = index + 1;
  });
}

function syncMilestoneFieldNames() {
  document.querySelectorAll('#milestone-list .milestone-builder-row').forEach((row, index) => {
    row.id = `ms-${index}`;
    const fields = getMilestoneFields(row);
    if (fields.name) fields.name.name = `milestones[${index}][name]`;
    if (fields.duration) fields.duration.name = `milestones[${index}][duration_days]`;
    if (fields.amount) fields.amount.name = `milestones[${index}][amount]`;
  });
}

function syncMilestonesPayload() {
  const payload = getMilestones().map((milestone, index) => ({
    index,
    name: milestone.name,
    duration_days: milestone.duration,
    amount: milestone.amount
  }));
  const field = document.getElementById('milestones-json');
  if (field) field.value = JSON.stringify(payload);
}

function syncBudgetFields(total, platformFee, specialistReceives, firstEscrowLock) {
  const totalField = document.getElementById('total-budget-input');
  const platformField = document.getElementById('platform-fee-input');
  const receivesField = document.getElementById('specialist-receives-input');
  const escrowField = document.getElementById('first-escrow-input');

  if (totalField) totalField.value = String(Math.round(total || 0));
  if (platformField) platformField.value = String(Math.round(platformFee || 0));
  if (receivesField) receivesField.value = String(Math.round(specialistReceives || 0));
  if (escrowField) escrowField.value = String(Math.round(firstEscrowLock || 0));
}

function syncSubmissionFields() {
  syncMilestoneFieldNames();
  syncNicheAnswersPayload();
  syncMilestonesPayload();
  recalcTotal();
}

function addMilestone() {
  const list = document.getElementById('milestone-list');
  const div = document.createElement('div');
  div.className = 'milestone-builder-row';
  div.id = 'ms-'+msCount;
  div.innerHTML = `<div style="display:flex;gap:10px;align-items:center;"><div class="milestone-num-badge">${list.children.length+1}</div><input type="text" class="form-control milestone-name" placeholder="Milestone name"></div><input type="number" class="form-control milestone-duration" min="1" step="1" inputmode="numeric" placeholder="Duration (days)"><div style="position:relative;"><span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--ink-faint);font-family:var(--font-mono);font-size:.875rem;">$</span><input type="number" class="form-control milestone-amount" min="1" step="1" style="padding-left:26px;" placeholder="0" oninput="recalcTotal()"></div><button type="button" class="btn btn-ghost btn-icon" onclick="removeMS(this)">🗑</button><div class="field-error milestone-row-error"></div>`;
  list.appendChild(div);
  msCount++;
  syncMilestoneFieldNames();
  updateReviewSummary();
}
function removeMS(btn) {
  btn.closest('.milestone-builder-row').remove();
  renumberMilestones();
  syncMilestoneFieldNames();
  recalcTotal();
}
function recalcTotal() {
  const vals = Array.from(document.querySelectorAll('#milestone-list .milestone-amount')).map(i => parseFloat(i.value)||0);
  const total = vals.reduce((a,b) => a+b, 0);
  const platformFee = total * 0.065;
  const specialistReceives = total - platformFee;
  const firstEscrowLock = vals[0] || 0;

  document.getElementById('ms-total').textContent = '$'+total.toLocaleString();
  document.getElementById('platform-fee').textContent = money(platformFee);
  document.getElementById('specialist-receives').textContent = money(specialistReceives);
  document.getElementById('first-escrow-lock').textContent = money(firstEscrowLock);
  syncBudgetFields(total, platformFee, specialistReceives, firstEscrowLock);
  syncMilestonesPayload();
  updateReviewSummary();
}

function addDragHover(e) {
  e.preventDefault();
  e.stopPropagation();
  e.currentTarget.classList.add('drag-over');
}

function removeDragHover(e) {
  e.currentTarget.classList.remove('drag-over');
}

function handleFilesDrop(e, type) {
  e.preventDefault();
  e.stopPropagation();
  e.currentTarget.classList.remove('drag-over');

  const files = e.dataTransfer.files;
  if (files.length > 0) {
    const fileInput = document.getElementById(type + 'File');
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(files[0]);
    fileInput.files = dataTransfer.files;

    const event = new Event('change', { bubbles: true });
    fileInput.dispatchEvent(event);
  }
}

function getFileIcon(filename) {
  const normalized = filename.toLowerCase();
  if (normalized.endsWith('.pdf')) return '📕';
  if (normalized.match(/\.(doc|docx)$/i)) return '📄';
  return '📎';
}

function formatFileSize(bytes) {
  if (!bytes) return '';
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function previewFile(input, type) {
  if (type !== 'nda') return;
  const file = input.files[0];
  if (!file) return;

  document.getElementById('ndaFileSelected').value = file.name;
  document.getElementById('ndaStatus').textContent = file.name;
  showFilePreview('nda', file);
  clearFieldError(input);
  updateReviewSummary();
}

function showFilePreview(type, file) {
  const uploadZone = document.getElementById(type + 'UploadZone');
  const previewDiv = document.getElementById(type + 'FilePreview');
  if (!uploadZone || !previewDiv) return;

  const icon = getFileIcon(file.name);
  const size = formatFileSize(file.size);

  previewDiv.innerHTML = `
    <div class="file-preview">
      <div class="file-preview-icon">${icon}</div>
      <div class="file-preview-info">
        <div class="file-preview-name">${file.name}</div>
        <div class="file-preview-size">${size}</div>
      </div>
      <button type="button" class="file-preview-remove" onclick="removeFilePreview('${type}')">✕ Remove</button>
    </div>
  `;

  uploadZone.style.display = 'none';
  previewDiv.style.display = 'block';
}

function removeFilePreview(type) {
  const uploadZone = document.getElementById(type + 'UploadZone');
  const previewDiv = document.getElementById(type + 'FilePreview');
  const fileInput = document.getElementById(type + 'File');
  const selectedField = document.getElementById(type + 'FileSelected');
  const status = document.getElementById(type + 'Status');

  if (fileInput) fileInput.value = '';
  if (selectedField) selectedField.value = '';
  if (status) status.textContent = 'Not uploaded';
  if (previewDiv) previewDiv.style.display = 'none';
  if (uploadZone) uploadZone.style.display = 'block';
  updateReviewSummary();
}

function resetNdaUpload() {
  removeFilePreview('nda');
  const uploadFile = document.getElementById('ndaFile');
  if (uploadFile) clearFieldError(uploadFile);
}

function toggleNdaMode() {
  const standardRadio = document.getElementById('nda-type-standard');
  const customRadio = document.getElementById('nda-type-custom');
  const standardFields = document.getElementById('nda-standard-fields');
  const uploadFields = document.getElementById('nda-upload-fields');
  const uploadFile = document.getElementById('ndaFile');
  const ndaDuration = document.getElementById('nda-duration');
  const ndaDamages = document.getElementById('nda-damages');
  const ndaCustomAmount = document.getElementById('nda-custom-amount');

  if (!standardRadio || !customRadio || !standardFields || !uploadFields || !uploadFile || !ndaDuration || !ndaDamages || !ndaCustomAmount) return;

  const isCustomMode = customRadio.checked;

  standardFields.style.display = isCustomMode ? 'none' : 'block';
  uploadFields.style.display = isCustomMode ? 'block' : 'none';

  ndaDuration.required = !isCustomMode;
  ndaDamages.required = !isCustomMode;
  ndaCustomAmount.required = !isCustomMode && ndaDamages.value === 'custom';
  uploadFile.required = isCustomMode;

  if (isCustomMode) {
    clearFieldError(ndaDuration);
    clearFieldError(ndaDamages);
    clearFieldError(ndaCustomAmount);
    toggleCustomDamagesField();
  } else {
    resetNdaUpload();
    toggleCustomDamagesField();
  }
  updateReviewSummary();
}

function validateStep(stepNumber) {
  const panel = getStepPanel(stepNumber);
  if (!panel) return true;

  clearStepErrors(stepNumber);

  if (stepNumber === 1 && !selectedNiche) {
    document.getElementById('niche-error')?.classList.add('show');
    return false;
  }

  const requiredFields = panel.querySelectorAll('input[required], select[required], textarea[required]');
  let isValid = true;
  for (const field of requiredFields) {
    if (!isFieldValid(field)) {
      showFieldError(field, getCustomErrorMessage(field));
      isValid = false;
    }
  }

  if (stepNumber === 3 && !validateMilestones()) {
    isValid = false;
  }

  return isValid;
}

function updateStepUI() {
  // Hide all panels
  document.querySelectorAll('.wizard-step-panel').forEach(p => p.classList.remove('active'));
  // Show current step
  document.getElementById('step' + currentStep)?.classList.add('active');
  
  // Update progress dots and titles
  for (let i = 1; i <= 5; i++) {
    const dot = document.getElementById('dot' + i);
    const title = document.getElementById('t' + i);
    dot.classList.remove('done', 'active');
    title.classList.remove('done', 'active');
    
    if (i < currentStep) {
      dot.classList.add('done');
      dot.textContent = '✓';
      title.classList.add('done');
    } else if (i === currentStep) {
      dot.classList.add('active');
      dot.textContent = i;
      title.classList.add('active');
    } else {
      dot.textContent = i;
    }
  }
  updateReviewSummary();
}

function goStep(n) {
  currentStep = n;
  updateStepUI();
  document.querySelector('.wizard-right').scrollTop = 0;
}

function nextStep() {
  if (!validateStep(currentStep)) return;
  if (currentStep < 5) currentStep++;
  updateStepUI();
  document.querySelector('.wizard-right').scrollTop = 0;
}

function prevStep() {
  if (currentStep > 1) currentStep--;
  updateStepUI();
  document.querySelector('.wizard-right').scrollTop = 0;
}

function validateAllStepsForSubmit() {
  for (let stepNumber = 1; stepNumber <= 5; stepNumber++) {
    if (!validateStep(stepNumber)) {
      currentStep = stepNumber;
      updateStepUI();
      document.querySelector('.wizard-right').scrollTop = 0;
      return false;
    }
  }
  return true;
}

// Niche selection
document.querySelectorAll('.niche-select-card').forEach(card => {
  card.addEventListener('click', function() {
    document.querySelectorAll('.niche-select-card').forEach(c => c.classList.remove('selected'));
    this.classList.add('selected');
    selectedNiche = this.querySelector('.niche-card-name').textContent;
    document.getElementById('selected-niche').value = selectedNiche;
    document.getElementById('niche-error')?.classList.remove('show');
    renderNicheQuestions(selectedNiche);
    updateReviewSummary();
  });
});

document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
  const eventName = (field.type === 'checkbox' || field.tagName === 'SELECT') ? 'change' : 'input';
  field.addEventListener(eventName, function() {
    if (isFieldValid(this)) {
      clearFieldError(this);
    }
    updateReviewSummary();
  });
});

document.querySelectorAll('#project-title, #project-brief, #project-full-requirements, #project-ideal-candidate, #project-budget').forEach(field => {
  field?.addEventListener('input', updateReviewSummary);
});

document.getElementById('niche-question-container')?.addEventListener('input', function(e) {
  if (!e.target.matches('.niche-question-field')) return;
  if (isFieldValid(e.target)) clearFieldError(e.target);
  updateReviewSummary();
});
document.getElementById('niche-question-container')?.addEventListener('click', function(e) {
  const card = e.target.closest('.niche-answer-card');
  if (!card) return;

  const group = card.closest('.form-group');
  const field = group?.querySelector('.niche-question-field');
  if (!field) return;

  if (card.dataset.languagePairPicker === 'true') {
    openLanguagePairModal(field, card);
    return;
  }

  group.querySelectorAll('.niche-answer-card').forEach(option => {
    option.classList.toggle('selected', option === card);
    option.setAttribute('aria-pressed', option === card ? 'true' : 'false');
  });

  field.value = card.dataset.value || card.textContent.trim();
  clearFieldError(field);
  updateReviewSummary();
});
document.getElementById('niche-question-container')?.addEventListener('change', function(e) {
  if (!e.target.matches('.niche-question-field')) return;
  if (isFieldValid(e.target)) clearFieldError(e.target);
  updateReviewSummary();
});

document.querySelectorAll('#project-timeline, #privacy-level').forEach(field => {
  field?.addEventListener('change', updateReviewSummary);
});

document.getElementById('milestone-list')?.addEventListener('input', function(e) {
  if (!e.target.matches('input')) return;
  const row = e.target.closest('.milestone-builder-row');
  e.target.classList.remove('input-invalid');
  if (isMilestoneRowComplete(row)) clearMilestoneRowError(row);
  if (e.target.matches('.milestone-amount')) recalcTotal();
  else updateReviewSummary();
});
document.getElementById('milestone-list')?.addEventListener('keydown', function(e) {
  if (e.target.matches('.milestone-duration') && ['e', 'E', '+', '-', '.'].includes(e.key)) {
    e.preventDefault();
  }
});
document.querySelectorAll('input[name="nda_visibility"], #profile-masking').forEach(field => {
  field?.addEventListener('change', updateReviewSummary);
});
document.getElementById('nda-duration')?.addEventListener('change', function() {
  syncNdaPreviewDuration();
  updateReviewSummary();
});
document.getElementById('nda-damages')?.addEventListener('change', function() {
  toggleCustomDamagesField();
  updateReviewSummary();
});
document.getElementById('nda-custom-amount')?.addEventListener('input', function() {
  if (isFieldValid(this)) clearFieldError(this);
  updateReviewSummary();
});
document.getElementById('ndaFile')?.addEventListener('change', function() {
  if (isFieldValid(this)) clearFieldError(this);
  updateReviewSummary();
});
document.getElementById('nda-type-standard')?.addEventListener('change', toggleNdaMode);
document.getElementById('nda-type-custom')?.addEventListener('change', toggleNdaMode);

const cancelBtn = document.getElementById('cancel-btn');
const exitBackdrop = document.getElementById('exit-confirm-backdrop');
const exitStayBtn = document.getElementById('exit-stay-btn');
const exitConfirmBtn = document.getElementById('exit-confirm-btn');
const languagePairBackdrop = document.getElementById('language-pair-backdrop');
const languagePairCancel = document.getElementById('language-pair-cancel');
const languagePairSave = document.getElementById('language-pair-save');
const languageSwapBtn = document.getElementById('language-swap-btn');
const projectPostForm = document.getElementById('project-post-form');

function openExitConfirm() {
  if (!exitBackdrop) return;
  exitBackdrop.style.display = 'flex';
}

function closeExitConfirm() {
  if (!exitBackdrop) return;
  exitBackdrop.style.display = 'none';
}

cancelBtn?.addEventListener('click', function(e) {
  e.preventDefault();
  openExitConfirm();
});

exitStayBtn?.addEventListener('click', closeExitConfirm);

exitConfirmBtn?.addEventListener('click', function() {
  const targetUrl = cancelBtn?.getAttribute('href') || 'dashboard-client.html';
  window.location.href = targetUrl;
});

exitBackdrop?.addEventListener('click', function(e) {
  if (e.target === exitBackdrop) {
    closeExitConfirm();
  }
});

languagePairCancel?.addEventListener('click', closeLanguagePairModal);
languagePairSave?.addEventListener('click', saveLanguagePair);
languageSwapBtn?.addEventListener('click', flipLanguagePair);
languagePairBackdrop?.addEventListener('click', function(e) {
  if (e.target === languagePairBackdrop) {
    closeLanguagePairModal();
  }
});
document.getElementById('source-language-select')?.addEventListener('change', clearLanguagePairError);
document.getElementById('target-language-select')?.addEventListener('change', clearLanguagePairError);

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && languagePairBackdrop?.style.display === 'flex') {
    closeLanguagePairModal();
    return;
  }
  if (e.key === 'Escape' && exitBackdrop?.style.display === 'flex') {
    closeExitConfirm();
  }
});

// Next/Back buttons
document.querySelectorAll('.step-nav .btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    if (this.hasAttribute('onclick')) return;

    if (this.textContent.includes('Back') || this.textContent.includes('←')) {
      prevStep();
    } else if (this.textContent.includes('Continue') || this.textContent.includes('→')) {
      nextStep();
    } else if (this.textContent.includes('Post')) {
      if (!validateStep(5)) {
        e.preventDefault();
        return;
      }
      syncSubmissionFields();
    }
  });
});

projectPostForm?.addEventListener('submit', function(e) {
  if (!validateAllStepsForSubmit()) {
    e.preventDefault();
    return;
  }
  syncSubmissionFields();
});

// Clear terms validation state when user checks the box
document.getElementById('agree-terms')?.addEventListener('change', function() {
  if (this.checked) {
    clearFieldError(this);
  }
});

// Left nav step clicks
document.querySelectorAll('.wizard-left-step').forEach((step, idx) => {
  step.addEventListener('click', function() {
    const targetStep = idx + 1;
    if (targetStep > currentStep) {
      for (let stepToCheck = currentStep; stepToCheck < targetStep; stepToCheck++) {
        if (!validateStep(stepToCheck)) return;
      }
    }
    currentStep = targetStep;
    updateStepUI();
    document.querySelector('.wizard-right').scrollTop = 0;
  });
});

// Initialize UI
syncMilestoneFieldNames();
updateStepUI();
recalcTotal();
updateReviewSummary();
syncNdaPreviewDuration();
toggleCustomDamagesField();
toggleNdaMode();
syncSubmissionFields();
</script>

</body>
</html>
