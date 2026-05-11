<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Post a Project — Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
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

<?php require __DIR__ . '/../partials/topnav.php'; ?>

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
  <?php if (!empty($errors)): ?>
    <div class="field-error show" style="display:block;margin-bottom:18px;padding:14px 16px;border:1px solid var(--rust);border-radius:var(--radius-sm);background:rgba(197,79,46,.08);">
      <?php foreach ($errors as $error): ?>
        <div><?php echo htmlspecialchars($error); ?></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <form id="project-post-form" method="post" action= "/post-job" enctype="multipart/form-data" novalidate>

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

<script src="/assets/js/post-job.js"></script>
</body>
</html>
