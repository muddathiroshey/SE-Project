<!DOCTYPE html>

<?php

$errors  = $errors ?? [];
$old     = $old ?? [];
$feeRate = (float) ($feeRate ?? 0.065);
$feePct  = round($feeRate * 100, 1);


function old(string $key, $fallback = '') {
    global $old;
    $val = $old[$key] ?? $fallback;
    return is_string($val) ? htmlspecialchars($val) : $val;
}

$oldMilestones = $old['milestones'] ?? [];

$errorStep = (int) ($old['_error_step'] ?? 1);

$availableNiches = $niches ?? [
    ['key' => 'data-science',    'icon' => '🧠', 'name' => 'Data Science & ML'],
    ['key' => 'legal',           'icon' => '⚖️', 'name' => 'Legal Consulting'],
    ['key' => 'translation',     'icon' => '🌐', 'name' => 'Technical Translation'],
    ['key' => 'finance',         'icon' => '📈', 'name' => 'Financial Modelling'],
    ['key' => 'biomedical',      'icon' => '🔬', 'name' => 'Biomedical Research'],
    ['key' => 'cybersecurity',   'icon' => '🔐', 'name' => 'Cybersecurity Audit'],
];
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Post a Project — Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/post-job.css">
<style>
.wizard-shell { display:grid;grid-template-columns:300px 1fr;gap:0;min-height:100vh; }
.wizard-left {
  background:var(--ink);padding:40px 32px;
  border-right:1px solid rgba(255,255,255,.08);
  position:sticky;top:0;height:100vh;overflow-y:auto;
}
.wizard-left-logo { font-family:var(--font-display);font-size:1.4rem;color:var(--ivory);margin-bottom:40px; }
.wizard-left-logo span { color:var(--gold); }
.wizard-left-step {
  display:flex;gap:14px;align-items:flex-start;
  padding:14px 0;border-bottom:1px solid rgba(255,255,255,.06);cursor:pointer;
}
.wizard-left-step:last-child { border-bottom:none; }
.wzl-dot {
  width:30px;height:30px;border-radius:50%;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-size:.75rem;font-weight:700;font-family:var(--font-mono);
  border:1.5px solid rgba(255,255,255,.2);color:rgba(247,244,239,.4);
}
.wzl-dot.done   { background:var(--sage);border-color:var(--sage);color:#fff; }
.wzl-dot.active { background:var(--gold);border-color:var(--gold);color:var(--ink); }
.wzl-title { font-size:.875rem;font-weight:700;color:rgba(247,244,239,.4); }
.wzl-title.active { color:var(--ivory); }
.wzl-title.done  { color:rgba(247,244,239,.7); }
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
.niche-select-card:hover  { border-color:var(--gold-light);background:var(--gold-pale); }
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
  font-size:.75rem;font-weight:700;font-family:var(--font-mono);flex-shrink:0;
}
.milestone-total {
  background:var(--ivory-deep);border:1px solid var(--border);
  border-radius:var(--radius-sm);padding:12px 16px;
  display:flex;justify-content:space-between;align-items:center;margin-top:8px;
}
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
  font-family:var(--font-mono);font-size:.75rem;color:var(--ink-muted);line-height:1.8;
}
.nda-preview strong { color:var(--ink); }
.upload-zone {
  border:2px dashed var(--gold-light);border-radius:var(--radius-md);
  padding:32px;text-align:center;cursor:pointer;transition:all .15s;background:var(--gold-pale);
}
.upload-zone:hover  { border-color:var(--gold);box-shadow:0 0 0 2px rgba(201,168,76,.1); }
.upload-zone.drag-over { border-color:var(--gold);box-shadow:0 0 0 3px rgba(201,168,76,.2); }
.upload-zone.error  { border-color:#d32f2f!important;background:rgba(211,47,47,.05)!important; }
.field-error { display:none;margin-top:8px;font-size:.8rem;color:var(--rust);font-weight:700; }
.field-error.show { display:block; }
.input-invalid { border-color:var(--rust)!important;box-shadow:0 0 0 2px rgba(197,79,46,.15); }
.review-section { margin-bottom:24px;padding:20px 24px;border:1px solid var(--border);border-radius:var(--radius-md);background:var(--ivory-card); }
.review-section h4 { font-size:.9375rem;margin-bottom:14px; }
.review-row { display:flex;justify-content:space-between;gap:18px;margin-bottom:9px;font-size:.875rem; }
.review-row:last-child { margin-bottom:0; }
.review-row .label { color:var(--ink-muted); }
.review-row .val   { color:var(--ink);font-family:var(--font-mono);font-weight:600;text-align:right; }
.niche-question-group { border-top:1px solid var(--border);padding-top:22px;margin-top:22px; }
.niche-answer-card {
  min-height:58px;padding:10px 12px;border:1.5px solid var(--border);
  border-radius:var(--radius-sm);background:var(--ivory-card);color:var(--ink-mid);
  text-align:left;font:inherit;font-size:.84rem;font-weight:700;cursor:pointer;transition:all .15s;
}
.niche-answer-card:hover  { border-color:var(--gold-light);background:var(--gold-pale); }
.niche-answer-card.selected { border-color:var(--gold);background:var(--gold-pale);color:var(--ink);box-shadow:0 0 0 2px rgba(201,168,76,.16); }
.exit-confirm-backdrop {
  position:fixed;inset:0;background:rgba(22,25,28,.55);
  display:flex;align-items:center;justify-content:center;z-index:1100;
}
.exit-confirm-modal {
  width:min(460px,calc(100vw - 32px));background:var(--ivory-card);
  border:1px solid var(--border);border-radius:var(--radius-md);
  box-shadow:0 20px 50px rgba(0,0,0,.18);padding:24px;
}
.language-pair-backdrop {
  position:fixed;inset:0;background:rgba(22,25,28,.55);
  display:none;align-items:center;justify-content:center;z-index:1200;
}
.language-pair-modal {
  width:min(680px,calc(100vw - 32px));background:var(--ivory-card);
  border:1px solid var(--border);border-radius:var(--radius-md);
  box-shadow:0 20px 50px rgba(0,0,0,.18);padding:24px;
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

    <?php
    $steps = [
      ['label' => 'Choose Niche',        'sub' => 'Discipline & category'],
      ['label' => 'Project Details',     'sub' => 'Title, scope, requirements'],
      ['label' => 'Milestones & Budget', 'sub' => 'Payment structure'],
      ['label' => 'NDA & Privacy',       'sub' => 'Confidentiality settings'],
      ['label' => 'Review & Post',       'sub' => 'Confirm & go live'],
    ];
    foreach ($steps as $i => $step):
      $n = $i + 1;
    ?>
    <div class="wizard-left-step" onclick="goStep(<?= $n ?>)">
      <div class="wzl-dot <?= $n === 1 ? 'active' : '' ?>" id="dot<?= $n ?>"><?= $n ?></div>
      <div>
        <div class="wzl-title <?= $n === 1 ? 'active' : '' ?>" id="t<?= $n ?>"><?= htmlspecialchars($step['label']) ?></div>
        <div class="wzl-sub"><?= htmlspecialchars($step['sub']) ?></div>
      </div>
    </div>
    <?php endforeach; ?>

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
      <div>⚠ <?= htmlspecialchars($error) ?></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form id="project-post-form" method="POST" action="/post-job" enctype="multipart/form-data" novalidate>
      <?php if (function_exists('csrf_field')): echo csrf_field(); endif; ?>

      <!-- Hidden inputs written by JS before submit -->
      <input type="hidden" id="selected-niche"            name="niche"                  value="<?= old('niche') ?>">
      <input type="hidden" id="niche-answers-json"        name="niche_answers_json"     value="<?= old('niche_answers_json') ?>">
      <input type="hidden" id="milestones-json"           name="milestones_json"        value="<?= old('milestones_json') ?>">
      <input type="hidden" id="total-budget-input"        name="total_budget"           value="<?= old('total_budget', 0) ?>">
      <input type="hidden" id="platform-fee-input"        name="platform_fee"           value="<?= old('platform_fee', 0) ?>">
      <input type="hidden" id="specialist-receives-input" name="specialist_receives"    value="<?= old('specialist_receives', 0) ?>">
      <input type="hidden" id="first-escrow-input"        name="first_escrow_required"  value="<?= old('first_escrow_required', 0) ?>">

      <!-- ════ STEP 1: NICHE ════ -->
      <div class="wizard-step-panel active" id="step1">
        <div class="page-header">
          <div class="breadcrumb">Step 1 of 5</div>
          <h2>Choose Your Project Niche</h2>
          <p class="mt-4">The niche determines what fields appear in your project brief. Choose the closest match to your deliverable.</p>
        </div>

        <div class="niche-select-grid">
          <?php foreach ($availableNiches as $niche): ?>
          <div class="niche-select-card <?= old('niche') === $niche['key'] ? 'selected' : '' ?>"
               data-niche="<?= htmlspecialchars($niche['key']) ?>"
               onclick="selectNiche(this, '<?= htmlspecialchars($niche['key']) ?>', '<?= htmlspecialchars($niche['name'], ENT_QUOTES) ?>')">
            <div class="niche-card-icon"><?= $niche['icon'] ?></div>
            <div class="niche-card-name"><?= htmlspecialchars($niche['name']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="field-error" id="niche-error">Please choose a project niche before continuing.</div>

        <div class="step-nav">
          <div></div>
          <button type="button" class="btn btn-primary" onclick="validateStep1()">Continue to Project Details →</button>
        </div>
      </div>

      <!-- ════ STEP 2: PROJECT DETAILS ════ -->
      <div class="wizard-step-panel" id="step2">
        <div class="page-header">
          <div class="breadcrumb">Step 2 of 5</div>
          <h2>Project Details</h2>
          <p class="mt-4">Add the main project information and requirements.</p>
        </div>

        <div class="form-group">
          <label class="form-label">Project Title</label>
          <input type="text" id="project-title" name="project_title" class="form-control <?= isset($errors['project_title']) ? 'input-invalid' : '' ?>"
            placeholder="Be specific and professional"
            value="<?= old('project_title') ?>" required>
          <?php if (!empty($errors['project_title'])): ?>
          <div class="field-error show"><?= htmlspecialchars($errors['project_title']) ?></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label">Project Brief</label>
          <textarea id="project-brief" name="project_brief" class="form-control <?= isset($errors['project_brief']) ? 'input-invalid' : '' ?>"
            rows="5" placeholder="Describe the full scope of the work required..." required><?= old('project_brief') ?></textarea>
          <?php if (!empty($errors['project_brief'])): ?>
          <div class="field-error show"><?= htmlspecialchars($errors['project_brief']) ?></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label">Full Requirements &amp; Deliverables</label>
          <textarea id="project-full-requirements" name="project_full_requirements" class="form-control"
            rows="6" placeholder="List detailed deliverables, technical requirements, constraints, and acceptance criteria..."><?= old('project_full_requirements') ?></textarea>
        </div>

        <!-- Niche-specific questions injected by JS -->
        <div id="niche-question-container" class="niche-question-group"></div>

        <div class="form-group">
          <label class="form-label">Ideal Candidate</label>
          <textarea id="project-ideal-candidate" name="ideal_candidate" class="form-control"
            rows="4" placeholder="Describe the specialist experience, skills, or background you are looking for..."><?= old('ideal_candidate') ?></textarea>
        </div>

        <div class="step-nav">
          <button type="button" class="btn btn-outline" onclick="goStep(1)">← Back</button>
          <button type="button" class="btn btn-primary" onclick="validateStep2()">Continue to Milestones →</button>
        </div>
      </div>

      <!-- ════ STEP 3: MILESTONES ════ -->
      <div class="wizard-step-panel" id="step3">
        <div class="page-header">
          <div class="breadcrumb">Step 3 of 5</div>
          <h2>Milestones &amp; Budget</h2>
          <p class="mt-4">Break your project into funded phases. Specialists begin each phase only after escrow is confirmed.</p>
        </div>

        <div id="milestone-list">
          <?php
          // Repopulate milestones from old POST, or start with one empty row
          $repopMs = !empty($oldMilestones) ? $oldMilestones : [['name' => '', 'duration_days' => '', 'amount' => '']];
          foreach ($repopMs as $i => $ms):
          ?>
          <div class="milestone-builder-row" id="ms-<?= $i ?>">
            <div style="display:flex;gap:10px;align-items:center;grid-column:1">
              <div class="milestone-num-badge"><?= $i + 1 ?></div>
              <input type="text" class="form-control milestone-name"
                name="milestones[<?= $i ?>][name]"
                placeholder="Milestone name"
                value="<?= htmlspecialchars($ms['name'] ?? '') ?>">
            </div>
            <input type="number" class="form-control milestone-duration"
              name="milestones[<?= $i ?>][duration_days]"
              min="1" step="1" inputmode="numeric"
              placeholder="Duration (days)"
              value="<?= htmlspecialchars($ms['duration_days'] ?? '') ?>">
            <div style="position:relative;">
              <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--ink-faint);font-family:var(--font-mono);font-size:.875rem;">$</span>
              <input type="number" class="form-control milestone-amount"
                name="milestones[<?= $i ?>][amount]"
                min="1" step="1" style="padding-left:26px;"
                placeholder="0"
                value="<?= htmlspecialchars($ms['amount'] ?? '') ?>"
                oninput="recalcTotal()">
            </div>
            <button type="button" class="btn btn-ghost btn-icon"
              style="<?= $i === 0 ? 'opacity:.4;cursor:not-allowed;' : '' ?>"
              onclick="<?= $i === 0 ? '' : 'removeMilestone(this,' . $i . ')' ?>">🗑</button>
            <div class="field-error milestone-row-error"></div>
          </div>
          <?php endforeach; ?>
        </div>

        <button type="button" class="btn btn-outline btn-sm mt-8" onclick="addMilestone()">+ Add Milestone</button>

        <div class="milestone-total mt-12">
          <span class="text-sm text-muted">Total Project Budget</span>
          <span style="font-family:var(--font-mono);font-size:1.1rem;font-weight:500;" id="ms-total">$0</span>
        </div>

        <div class="budget-preview">
          <div>
            <div class="text-xs text-muted mb-4">Platform Fee (<?= $feePct ?>%)</div>
            <div class="font-mono font-bold" id="platform-fee">$0</div>
          </div>
          <div>
            <div class="text-xs text-muted mb-4">Specialist Receives</div>
            <div class="font-mono font-bold" id="specialist-receives">$0</div>
          </div>
          <div>
            <div class="text-xs text-muted mb-4">First Escrow Lock</div>
            <div class="font-mono font-bold" id="first-escrow-lock">$0</div>
          </div>
        </div>

        <div style="margin-top:20px;display:flex;gap:8px;align-items:center;">
          <input type="checkbox" id="free-revisions" name="free_revisions" value="1"
            style="accent-color:var(--gold);"
            <?= !empty($old['free_revisions']) || empty($old) ? 'checked' : '' ?>>
          <label for="free-revisions" class="text-sm">Include 2 free revisions per milestone</label>
        </div>

        <div class="step-nav">
          <button type="button" class="btn btn-outline" onclick="goStep(2)">← Back</button>
          <button type="button" class="btn btn-primary" onclick="validateStep3()">Continue to NDA Settings →</button>
        </div>
      </div>

      <!-- ════ STEP 4: NDA ════ -->
      <div class="wizard-step-panel" id="step4">
        <div class="page-header">
          <div class="breadcrumb">Step 4 of 5</div>
          <h2>NDA &amp; Privacy Settings</h2>
          <p class="mt-4">An NDA is auto-generated when any specialist is shortlisted. Customize confidentiality terms below.</p>
        </div>

        <div style="display:flex;gap:12px;margin-bottom:24px;">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.875rem;">
            <input type="radio" id="nda-type-standard" name="nda_type" value="standard"
              style="accent-color:var(--gold);"
              <?= (old('nda_type', 'standard') === 'standard') ? 'checked' : '' ?>
              onchange="setNdaMode('standard')">
            Standard Nexus NDA
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.875rem;">
            <input type="radio" id="nda-type-custom" name="nda_type" value="custom"
              style="accent-color:var(--gold);"
              <?= (old('nda_type') === 'custom') ? 'checked' : '' ?>
              onchange="setNdaMode('custom')">
            Upload Custom NDA
          </label>
        </div>

        <div id="nda-standard-fields">
          <div class="nda-preview">
            <strong>NON-DISCLOSURE AGREEMENT — AUTO-GENERATED PREVIEW</strong><br><br>
            This Non-Disclosure Agreement is entered into between
            <strong><?= htmlspecialchars($client['org_name'] ?? $client['user_name'] ?? '[CLIENT]') ?></strong>
            ("Disclosing Party") and <strong>[SPECIALIST: To be added when specialist bids]</strong> ("Receiving Party").<br><br>
            1. CONFIDENTIAL INFORMATION: All project details, documents, data, and communications shared through the
            Nexus Platform shall be treated as strictly confidential...<br><br>
            2. TERM: This Agreement remains in force for <strong><span id="nda-term-value"><?= old('nda_duration', '2 years') ?></span></strong>
            following the conclusion of the engagement...<br><br>
            3. GOVERNING LAW: This Agreement is governed by applicable law per jurisdiction.
            <br><br><em>[Full NDA generated upon specialist shortlisting]</em>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">NDA Duration</label>
              <select class="form-control" id="nda-duration" name="nda_duration" onchange="document.getElementById('nda-term-value').textContent=this.value">
                <option value="">Select NDA duration</option>
                <?php foreach (['1 year', '2 years', '3 years', 'Indefinite'] as $opt): ?>
                <option <?= old('nda_duration', '2 years') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Liquidated Damages Clause</label>
              <select class="form-control" id="nda-damages" name="nda_damages" onchange="toggleCustomDamages(this)">
                <option value="">Select damages clause</option>
                <option value="none"  <?= old('nda_damages') === 'none'   ? 'selected' : '' ?>>None (Standard)</option>
                <option value="10000" <?= old('nda_damages', '10000') === '10000' ? 'selected' : '' ?>>$10,000 per breach</option>
                <option value="25000" <?= old('nda_damages') === '25000' ? 'selected' : '' ?>>$25,000 per breach</option>
                <option value="custom"<?= old('nda_damages') === 'custom' ? 'selected' : '' ?>>Custom amount</option>
              </select>
              <div id="nda-custom-amount-wrap" style="display:<?= old('nda_damages') === 'custom' ? 'block' : 'none' ?>;margin-top:12px;">
                <label class="form-label">Custom Damages Amount (USD)</label>
                <input type="number" id="nda-custom-amount" name="nda_custom_amount"
                  class="form-control" min="1" placeholder="Enter amount in USD"
                  value="<?= old('nda_custom_amount') ?>">
              </div>
            </div>
          </div>
        </div>

        <div id="nda-upload-fields" style="display:<?= old('nda_type') === 'custom' ? 'block' : 'none' ?>;">
          <div class="card" style="padding:24px;margin-bottom:24px;">
            <div style="display:flex;align-items:flex-start;gap:16px;">
              <div style="font-size:2rem;">📄</div>
              <div style="flex:1;">
                <h3 style="margin:0 0 12px 0;font-size:1rem;">Custom NDA</h3>
                <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload your own NDA document for this project.</p>
                <div class="upload-zone" id="ndaUploadZone"
                  ondrop="handleFilesDrop(event,'nda')"
                  ondragover="addDragHover(event)"
                  ondragleave="removeDragHover(event)"
                  onclick="document.getElementById('ndaFile').click()">
                  <div style="font-size:2rem;margin-bottom:8px;">📤</div>
                  <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
                  <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">PDF, DOC, DOCX · Max 10MB</p>
                  <input type="file" id="ndaFile" name="nda_file" style="display:none;"
                    accept=".pdf,.doc,.docx" onchange="previewFile(this,'nda')">
                </div>
                <div id="ndaFilePreview" style="display:none;margin-top:12px;"></div>
                <div class="field-error" id="nda-upload-error">Please upload your custom NDA file.</div>
              </div>
            </div>
          </div>
        </div>

        <h4 class="mb-12">Profile Visibility Controls</h4>
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
          <label style="display:flex;gap:10px;cursor:pointer;font-size:.875rem;align-items:flex-start;">
            <input type="checkbox" id="profile-masking" name="profile_masking" value="1"
              style="accent-color:var(--gold);margin-top:3px;"
              <?= !empty($old['profile_masking']) || empty($old) ? 'checked' : '' ?>>
            <span>Mask client organization name in specialist-visible project listing</span>
          </label>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
          <label class="form-label" style="text-transform:uppercase;letter-spacing:.08em;">Visibility</label>
          <div style="display:flex;gap:18px;align-items:center;margin-bottom:8px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.875rem;">
              <input type="radio" name="nda_visibility" value="public"
                style="accent-color:var(--gold);"
                <?= old('nda_visibility', 'public') === 'public' ? 'checked' : '' ?>>
              <span>Public (all verified specialists)</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.875rem;">
              <input type="radio" name="nda_visibility" value="invitation-only"
                style="accent-color:var(--gold);"
                <?= old('nda_visibility') === 'invitation-only' ? 'checked' : '' ?>>
              <span>Invitation-only Tender</span>
            </label>
          </div>
          <p class="form-hint">Invitation-only projects are only visible to specialists you personally invite.</p>
        </div>

        <div class="step-nav">
          <button type="button" class="btn btn-outline" onclick="goStep(3)">← Back</button>
          <button type="button" class="btn btn-primary" onclick="validateStep4()">Continue to Review →</button>
        </div>
      </div>

      <!-- ════ STEP 5: REVIEW & POST ════ -->
      <div class="wizard-step-panel" id="step5">
        <div class="page-header">
          <div class="breadcrumb">Step 5 of 5 · Final Review</div>
          <h2>Review &amp; Post Your Project</h2>
          <p class="mt-4">Verify all details before going live. You can edit this after posting.</p>
        </div>

        <!-- Live summary injected by JS -->
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;margin-bottom:32px;">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;font-size:.9rem;">
            <div>
              <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">PROJECT TITLE</div>
              <div id="review-title">—</div>
            </div>
            <div>
              <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">NICHE</div>
              <div id="review-niche">—</div>
            </div>
            <div>
              <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">BUDGET</div>
              <div id="review-budget">—</div>
            </div>
            <div>
              <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">TIMELINE</div>
              <div id="review-timeline">—</div>
            </div>
            <div>
              <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">PRIVACY LEVEL</div>
              <div id="review-privacy">—</div>
            </div>
            <div style="grid-column:1 / -1;">
              <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">PROJECT BRIEF</div>
              <div id="review-brief">—</div>
            </div>
            <div style="grid-column:1 / -1;">
              <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">FULL REQUIREMENTS</div>
              <div id="review-full-requirements">—</div>
            </div>
            <div style="grid-column:1 / -1;">
              <div style="color:var(--ink-muted);font-weight:700;margin-bottom:4px;">IDEAL CANDIDATE</div>
              <div id="review-ideal-candidate">—</div>
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
            <span class="val" id="review-nda-type">—</span>
          </div>
          <div class="review-row">
            <span class="label">Damages Clause</span>
            <span class="val" id="review-damages">—</span>
          </div>
          <div class="review-row">
            <span class="label">Profile Masking</span>
            <span class="val" id="review-profile-masking">—</span>
          </div>
        </div>

        <div style="background:#fef9f0;border:1px solid #f0d9ba;border-radius:var(--radius-md);padding:16px;margin-bottom:32px;">
          <div style="font-weight:700;color:var(--rust);font-size:.9rem;margin-bottom:8px;">💡 Pro Tip</div>
          <div style="font-size:.85rem;color:var(--ink-mid);">Projects with clear scope, realistic budget, and specific requirements attract higher-quality bids. You'll typically receive first responses within 2–3 hours.</div>
        </div>

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
          <input type="checkbox" id="agree-terms" name="agree_terms" value="1" required>
          <label for="agree-terms" style="font-size:.9rem;color:var(--ink-mid);">
            I have read and agree to the <a href="/terms" style="color:var(--gold);text-decoration:none;font-weight:700;">Posting Guidelines &amp; Terms</a>
          </label>
        </div>
        <div class="field-error" id="terms-error">You must agree to the Posting Guidelines &amp; Terms before posting.</div>

        <div class="step-nav">
          <button type="button" class="btn btn-outline" onclick="goStep(4)">← Back</button>
          <button type="submit" class="btn btn-primary" id="post-btn" onclick="return finalValidate()">Post Project</button>
        </div>
      </div>

    </form>
  </div><!-- end wizard-right -->
</div><!-- end wizard-shell -->

<!-- EXIT CONFIRM MODAL -->
<div id="exit-confirm-backdrop" class="exit-confirm-backdrop" style="display:none;">
  <div class="exit-confirm-modal" role="dialog" aria-modal="true">
    <h3 style="margin:0 0 10px 0;font-size:1.15rem;font-weight:700;">Exit Without Saving?</h3>
    <p style="margin:0;font-size:.92rem;color:var(--ink-mid);line-height:1.55;">Are you sure you want to exit? Your changes will not be saved.</p>
    <div style="margin-top:18px;display:flex;justify-content:flex-end;gap:10px;">
      <button type="button" class="btn btn-outline" id="exit-stay-btn">Stay Here</button>
      <button type="button" class="btn btn-primary" id="exit-confirm-btn">Yes, Exit</button>
    </div>
  </div>
</div>

<!-- LANGUAGE PAIR MODAL (for Technical Translation niche) -->
<div id="language-pair-backdrop" class="language-pair-backdrop">
  <div class="language-pair-modal" role="dialog" aria-modal="true">
    <h3 style="margin:0 0 8px 0;font-size:1.08rem;font-weight:700;">Choose Language Pair</h3>
    <p style="margin:0 0 18px 0;color:var(--ink-mid);font-size:.9rem;line-height:1.5;">Select any source and target language.</p>
    <div style="display:grid;grid-template-columns:1fr auto 1fr;gap:12px;align-items:end;">
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label" for="source-language-select">Source Language</label>
        <select class="form-control" id="source-language-select"></select>
      </div>
      <button type="button" class="btn btn-outline" id="language-swap-btn" style="width:42px;height:42px;padding:0;justify-content:center;font-size:1.1rem;" aria-label="Swap languages">⇄</button>
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label" for="target-language-select">Target Language</label>
        <select class="form-control" id="target-language-select"></select>
      </div>
    </div>
    <div class="field-error" id="language-pair-error">Choose two different languages.</div>
    <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
      <button type="button" class="btn btn-outline" id="language-pair-cancel">Cancel</button>
      <button type="button" class="btn btn-primary" id="language-pair-save">Use Pair</button>
    </div>
  </div>
</div>

<script>

const FEE_RATE    = <?= json_encode($feeRate) ?>;
const INIT_STEP   = <?= json_encode($errorStep) ?>;
const OLD_NICHE   = <?= json_encode(old('niche')) ?>;
const OLD_ANSWERS = <?= json_encode(old('niche_answers_json')) ?>;

const NICHES = <?= json_encode(array_map(fn($n) => [
    'key'  => $n['key'],
    'name' => $n['name'],
    'icon' => $n['icon'],
], $availableNiches)) ?>;

const OLD_MILESTONES = <?= json_encode(array_values($oldMilestones)) ?>;

let currentStep   = 1;
let selectedNiche = OLD_NICHE || '';
let selectedNicheLabel = '';
let nicheAnswers  = {};
let msRowCount    = <?= max(count($oldMilestones), 1) ?>;

function goStep(n) {
  if (n < 1 || n > 5) return;
  document.getElementById('step' + currentStep).classList.remove('active');
  document.getElementById('step' + n).classList.add('active');
  for (let i = 1; i <= 5; i++) {
    const dot = document.getElementById('dot' + i);
    const ttl = document.getElementById('t' + i);
    if (!dot || !ttl) continue;
    dot.className = 'wzl-dot' + (i < n ? ' done' : i === n ? ' active' : '');
    ttl.className = 'wzl-title' + (i < n ? ' done' : i === n ? ' active' : '');
  }
  currentStep = n;
  if (n === 5) populateReview();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function selectNiche(el, key, name) {
  document.querySelectorAll('.niche-select-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  selectedNiche = key;
  selectedNicheLabel = name;
  document.getElementById('selected-niche').value = key;
  document.getElementById('niche-error').classList.remove('show');
  renderNicheQuestions(key);
}

function validateStep1() {
  if (!selectedNiche) {
    document.getElementById('niche-error').classList.add('show');
    return;
  }
  goStep(2);
}

function validateStep2() {
  const title = document.getElementById('project-title')?.value?.trim();
  const brief = document.getElementById('project-brief')?.value?.trim();
  let valid = true;
  if (!title) {
    document.getElementById('project-title').classList.add('input-invalid');
    valid = false;
  }
  if (!brief || brief.length < 50) {
    document.getElementById('project-brief').classList.add('input-invalid');
    valid = false;
  }
  if (!valid) { showToast('Please fill in all required fields.', 'warn'); return; }
  goStep(3);
}

function validateStep3() {
  const amounts = Array.from(document.querySelectorAll('.milestone-amount')).map(i => parseFloat(i.value) || 0);
  if (amounts.every(a => a === 0)) {
    showToast('Please enter amounts for at least one milestone.', 'warn');
    return;
  }
  serializeMilestones();
  goStep(4);
}

function validateStep4() {
  const ndaType = document.querySelector('input[name="nda_type"]:checked')?.value;
  if (ndaType === 'custom') {
    const file = document.getElementById('ndaFile')?.files?.[0];
    if (!file) {
      document.getElementById('nda-upload-error').classList.add('show');
      return;
    }
  }
  goStep(5);
}

function finalValidate() {
  const agreed = document.getElementById('agree-terms')?.checked;
  if (!agreed) {
    document.getElementById('terms-error').classList.add('show');
    return false;
  }
  serializeMilestones();
  serializeNicheAnswers();
  return true;
}

function addMilestone() {
  const list = document.getElementById('milestone-list');
  const idx  = msRowCount;
  const row  = document.createElement('div');
  row.className = 'milestone-builder-row';
  row.id = 'ms-' + idx;
  row.innerHTML = `
    <div style="display:flex;gap:10px;align-items:center;grid-column:1">
      <div class="milestone-num-badge">${idx + 1}</div>
      <input type="text" class="form-control milestone-name" name="milestones[${idx}][name]" placeholder="Milestone name">
    </div>
    <input type="number" class="form-control milestone-duration" name="milestones[${idx}][duration_days]" min="1" step="1" inputmode="numeric" placeholder="Duration (days)">
    <div style="position:relative;">
      <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--ink-faint);font-family:var(--font-mono);font-size:.875rem;">$</span>
      <input type="number" class="form-control milestone-amount" name="milestones[${idx}][amount]" min="1" step="1" style="padding-left:26px;" placeholder="0" oninput="recalcTotal()">
    </div>
    <button type="button" class="btn btn-ghost btn-icon" onclick="removeMilestone(this,${idx})">🗑</button>
    <div class="field-error milestone-row-error"></div>`;
  list.appendChild(row);
  msRowCount++;
  renumberMilestones();
}

function removeMilestone(btn, idx) {
  document.getElementById('ms-' + idx)?.remove();
  renumberMilestones();
  recalcTotal();
}

function renumberMilestones() {
  document.querySelectorAll('#milestone-list .milestone-num-badge').forEach((b, i) => b.textContent = i + 1);
}

function recalcTotal() {
  const amounts = Array.from(document.querySelectorAll('.milestone-amount')).map(i => parseFloat(i.value) || 0);
  const total   = amounts.reduce((a, b) => a + b, 0);
  const fee     = Math.round(total * FEE_RATE);
  const first   = amounts[0] || 0;
  const receives = total - fee;

  document.getElementById('ms-total').textContent          = '$' + total.toLocaleString();
  document.getElementById('platform-fee').textContent      = '$' + fee.toLocaleString();
  document.getElementById('specialist-receives').textContent = '$' + receives.toLocaleString();
  document.getElementById('first-escrow-lock').textContent  = '$' + first.toLocaleString();

  document.getElementById('total-budget-input').value      = total;
  document.getElementById('platform-fee-input').value      = fee;
  document.getElementById('specialist-receives-input').value = receives;
  document.getElementById('first-escrow-input').value       = first;
}

function serializeMilestones() {
  const rows = [];
  document.querySelectorAll('#milestone-list .milestone-builder-row').forEach(row => {
    rows.push({
      name:          row.querySelector('.milestone-name')?.value || '',
      duration_days: row.querySelector('.milestone-duration')?.value || '',
      amount:        row.querySelector('.milestone-amount')?.value || '',
    });
  });
  document.getElementById('milestones-json').value = JSON.stringify(rows);
}

function setNdaMode(mode) {
  document.getElementById('nda-standard-fields').style.display = mode === 'standard' ? '' : 'none';
  document.getElementById('nda-upload-fields').style.display   = mode === 'custom'   ? 'block' : 'none';
}

function toggleCustomDamages(sel) {
  document.getElementById('nda-custom-amount-wrap').style.display = sel.value === 'custom' ? 'block' : 'none';
}

const NICHE_QUESTIONS = {
  'legal': [
    { key: 'engagement_type',    label: 'Engagement Type',         options: ['Contract Review', 'Advisory', 'Drafting', 'Arbitration'] },
    { key: 'jurisdictions',      label: 'Jurisdictions',           options: ['Egypt', 'UAE', 'KSA', 'Qatar', 'EU / GDPR', 'UK', 'USA', 'Cross-border MENA'] },
    { key: 'document_languages', label: 'Document Languages',      options: ['Arabic', 'English', 'French'] },
    { key: 'bar_admissions',     label: 'Required Bar Admissions', options: ['Cairo Bar', 'DIFC Courts', 'ADGM', 'Any MENA jurisdiction', 'None required'] },
  ],
  'data-science': [
    { key: 'model_type',    label: 'Model / Task Type',   options: ['Classification', 'Regression', 'NLP', 'Computer Vision', 'Time-Series', 'Recommendation'] },
    { key: 'ml_framework',  label: 'Preferred Framework', options: ['PyTorch', 'TensorFlow', 'Scikit-learn', 'HuggingFace', 'No preference'] },
    { key: 'dataset_size',  label: 'Dataset Size',        options: ['< 10K rows', '10K–1M rows', '> 1M rows', 'Not yet determined'] },
  ],
  'translation': [
    { key: 'subject_domain',  label: 'Subject Domain',     options: ['Legal', 'Medical', 'Technical', 'Financial', 'Marketing', 'General'] },
  ],
  'finance': [
    { key: 'model_type',   label: 'Model Type',       options: ['DCF', 'LBO', 'M&A', 'Project Finance', 'Startup Valuation', 'Other'] },
  ],
  'cybersecurity': [
    { key: 'audit_scope',         label: 'Audit Scope',         options: ['Web App', 'Network', 'Cloud Infrastructure', 'Endpoint', 'Full Stack'] },
    { key: 'compliance_standard', label: 'Compliance Standard', options: ['ISO 27001', 'SOC 2', 'NIST', 'GDPR', 'PCI-DSS', 'None required'] },
  ],
  'biomedical': [],
};

function renderNicheQuestions(nicheKey) {
  const container = document.getElementById('niche-question-container');
  const questions = NICHE_QUESTIONS[nicheKey] || [];
  if (!questions.length) { container.innerHTML = ''; return; }
  container.innerHTML = questions.map(q => `
    <div class="form-group niche-q" data-key="${q.key}">
      <label class="form-label">${q.label}</label>
      <div class="niche-answer-grid">
        ${q.options.map(opt => `
          <button type="button" class="niche-answer-card" onclick="toggleNicheAnswer(this,'${q.key}','${opt.replace(/'/g, "\\'")}')">
            ${opt}
          </button>`).join('')}
      </div>
    </div>`).join('');
}

function toggleNicheAnswer(el, key, val) {

  const isMulti = ['jurisdictions', 'document_languages', 'bar_admissions'].includes(key);
  if (!isMulti) {
    el.closest('.niche-answer-grid').querySelectorAll('.niche-answer-card').forEach(c => c.classList.remove('selected'));
    nicheAnswers[key] = val;
  } else {
    el.classList.toggle('selected');
    if (!Array.isArray(nicheAnswers[key])) nicheAnswers[key] = [];
    if (el.classList.contains('selected')) {
      nicheAnswers[key].push(val);
    } else {
      nicheAnswers[key] = nicheAnswers[key].filter(v => v !== val);
    }
    return;
  }
  el.classList.add('selected');
}

function serializeNicheAnswers() {
  document.getElementById('niche-answers-json').value = JSON.stringify(nicheAnswers);
}

function populateReview() {
  const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val || '—'; };
  set('review-title',            document.getElementById('project-title')?.value);
  set('review-niche',            selectedNicheLabel || selectedNiche);
  set('review-brief',            document.getElementById('project-brief')?.value);
  set('review-full-requirements',document.getElementById('project-full-requirements')?.value);
  set('review-ideal-candidate',  document.getElementById('project-ideal-candidate')?.value);

  const total = parseFloat(document.getElementById('total-budget-input')?.value) || 0;
  const days  = Array.from(document.querySelectorAll('.milestone-duration')).reduce((s, e) => s + (parseInt(e.value) || 0), 0);
  set('review-budget',   '$' + total.toLocaleString());
  set('review-timeline', days + ' days');

  const vis = document.querySelector('input[name="nda_visibility"]:checked')?.value;
  set('review-privacy', vis === 'invitation-only' ? 'Invitation-Only Tender' : 'Public');

  const msEl = document.getElementById('review-milestones');
  if (msEl) {
    msEl.innerHTML = Array.from(document.querySelectorAll('#milestone-list .milestone-builder-row')).map((row, i) => {
      const name = row.querySelector('.milestone-name')?.value || 'Milestone ' + (i + 1);
      const dur  = row.querySelector('.milestone-duration')?.value || '?';
      const amt  = parseFloat(row.querySelector('.milestone-amount')?.value) || 0;
      return `<div class="review-row"><span class="label">${i + 1}. ${name}</span><span class="val">${dur}d · $${amt.toLocaleString()}</span></div>`;
    }).join('');
  }

  set('review-total-budget', '$' + total.toLocaleString());
  const first = parseFloat(document.getElementById('first-escrow-input')?.value) || 0;
  set('review-first-escrow', '$' + first.toLocaleString());

  const ndaType = document.querySelector('input[name="nda_type"]:checked')?.value;
  set('review-nda-type', ndaType === 'custom' ? 'Custom NDA (uploaded)' : 'Standard Nexus NDA');
  const dmg = document.getElementById('nda-damages')?.value;
  set('review-damages', dmg === 'none' ? 'None' : dmg === 'custom'
    ? '$' + (document.getElementById('nda-custom-amount')?.value || '?') + ' per breach'
    : '$' + Number(dmg || 0).toLocaleString() + ' per breach');
  set('review-profile-masking', document.getElementById('profile-masking')?.checked ? 'Enabled' : 'Disabled');

  const naEl = document.getElementById('review-niche-answers');
  if (naEl) {
    const entries = Object.entries(nicheAnswers).filter(([, v]) => v && (!Array.isArray(v) || v.length));
    naEl.innerHTML = entries.length
      ? entries.map(([k, v]) => `<div class="review-row"><span class="label">${k.replace(/_/g,' ')}</span><span class="val">${Array.isArray(v) ? v.join(', ') : v}</span></div>`).join('')
      : '<p class="text-sm text-muted">No niche-specific answers.</p>';
  }
}

function addDragHover(e)    { e.preventDefault(); e.currentTarget.classList.add('drag-over'); }
function removeDragHover(e) { e.currentTarget.classList.remove('drag-over'); }
function handleFilesDrop(e, zone) {
  e.preventDefault();
  e.currentTarget.classList.remove('drag-over');
  const file = e.dataTransfer.files[0];
  if (file && zone === 'nda') {
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('ndaFile').files = dt.files;
    previewFile(document.getElementById('ndaFile'), 'nda');
  }
}
function previewFile(input, zone) {
  const file = input.files?.[0];
  if (!file) return;
  const size  = file.size > 1048576 ? (file.size / 1048576).toFixed(1) + ' MB' : (file.size / 1024).toFixed(0) + ' KB';
  const prevEl = document.getElementById(zone === 'nda' ? 'ndaFilePreview' : '');
  if (prevEl) {
    prevEl.style.display = 'flex';
    prevEl.innerHTML = `<span style="font-size:1.5rem;">📄</span><div style="flex:1;text-align:left;"><div style="font-weight:600;">${file.name}</div><div style="font-size:.75rem;color:var(--ink-muted);">${size}</div></div>`;
  }
  document.getElementById('nda-upload-error')?.classList.remove('show');
}

function showToast(msg, type = 'success') {
  const s = document.getElementById('toast-stack') || document.body;
  const el = document.createElement('div');
  el.className = 'toast-stack';
  el.innerHTML = `<div class="toast ${type==='warn'?'warning':'success'}"><span class="toast-icon">${type==='warn'?'⚠':'✓'}</span><div><div class="toast-title">${type==='warn'?'Required':'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 4000);
}

document.addEventListener('DOMContentLoaded', () => {

  if (OLD_NICHE) {
    const card = document.querySelector(`.niche-select-card[data-niche="${OLD_NICHE}"]`);
    if (card) {
      selectedNicheLabel = card.querySelector('.niche-card-name')?.textContent || OLD_NICHE;
      renderNicheQuestions(OLD_NICHE);
    }
  }

  if (OLD_MILESTONES.length > 0) recalcTotal();

  // Open the error step
  if (INIT_STEP > 1) goStep(INIT_STEP);

  let formDirty = false;
  document.getElementById('project-post-form')?.addEventListener('input', () => formDirty = true);
  window.addEventListener('beforeunload', e => {
    if (formDirty && currentStep > 1) { e.preventDefault(); e.returnValue = ''; }
  });
  document.getElementById('exit-stay-btn')?.addEventListener('click', () => {
    document.getElementById('exit-confirm-backdrop').style.display = 'none';
  });
  document.getElementById('exit-confirm-btn')?.addEventListener('click', () => {
    formDirty = false;
    window.location.href = '/dashboard';
  });
});
</script>
</body>
</html>