<?php
// views/client/index.php
$errors = $errors ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Complete Your Profile — Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/profile-setup-client.css">
</head>
<body>

<div class="wizard-shell">

  <!-- LEFT SIDEBAR -->
  <div class="wizard-left">
    <div class="wizard-left-logo">Nex<span>us</span></div>
    <div class="wizard-left-step" onclick="goToStep(1)">
      <div class="wzl-circle" id="dot1">1</div>
      <div>
        <div class="wzl-title" id="t1">Personal Information</div>
        <div class="wzl-sub">Your details</div>
      </div>
    </div>
    <div class="wizard-left-step" onclick="goToStep(2)">
      <div class="wzl-circle" id="dot2">2</div>
      <div>
        <div class="wzl-title" id="t2">Verification</div>
        <div class="wzl-sub">Upload document proof</div>
      </div>
    </div>
  </div>

  <!-- RIGHT CONTENT -->
  <div class="wizard-right">

    <!-- PHP errors — shown on step 1 by default, JS moves to step 2 if upload error -->
    <?php if (!empty($errors)): ?>
      <div class="error-banner" id="php-errors">
        <ul style="margin:0;padding-left:18px;">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form
      id="profile-form"
      method="POST"
      action="/profile/setup"
      enctype="multipart/form-data"
      novalidate
    >

      <!-- ── STEP 1: PERSONAL INFORMATION ── -->
      <div class="wizard-step-panel active" id="step1">
        <div class="page-header">
          <div class="breadcrumb">Step 1 of 2</div>
          <h2>Your Personal Information</h2>
          <p class="mt-4">Please provide your personal details.</p>
        </div>

        <div class="form-group">
          <label class="form-label">Full Name (as on legal document)</label>
          <input
            type="text"
            id="personalName"
            name="personalName"
            class="form-control"
            placeholder="Enter your full name"
            value="<?= htmlspecialchars($_POST['personalName'] ?? '') ?>"
          >
          <p class="error-message" id="personalName-error">Please enter your full name (letters only)</p>
          <p class="input-hint">This will be verified against your ID during verification</p>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Date of Birth (DD/MM/YYYY)</label>
            <input
              type="text"
              id="personalDOB"
              name="personalDOB"
              class="form-control"
              placeholder="DD/MM/YYYY"
              value="<?= htmlspecialchars($_POST['personalDOB'] ?? '') ?>"
            >
            <p class="error-message" id="personalDOB-error">Please enter a valid date in DD/MM/YYYY format</p>
          </div>
          <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input
              type="text"
              id="personalPhone"
              name="personalPhone"
              class="form-control"
              placeholder="+20 or 0020 (Egypt example)"
              value="<?= htmlspecialchars($_POST['personalPhone'] ?? '') ?>"
            >
            <p class="error-message" id="personalPhone-error">Please enter a valid phone number starting with + or 00</p>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Bio / About You</label>
          <textarea
            id="personalBio"
            name="personalBio"
            class="form-control"
            placeholder="Tell us about yourself and your needs…"
          ><?= htmlspecialchars($_POST['personalBio'] ?? '') ?></textarea>
          <p class="input-hint">This helps us understand your requirements better</p>
        </div>

        <div class="step-nav">
          <div></div>
          <div>
            <span class="step-counter">Step 1 of 2</span>
            <button type="button" class="btn btn-primary" onclick="goToStep(2)" style="margin-left:12px;">
              Continue to Verification →
            </button>
          </div>
        </div>
      </div>

      <!-- ── STEP 2: VERIFICATION ── -->
      <div class="wizard-step-panel" id="step2">
        <div class="page-header">
          <div class="breadcrumb">Step 2 of 2</div>
          <h2>Identity Verification</h2>
          <p class="mt-4">Upload your ID to verify your identity.</p>
        </div>

        <div class="card" style="padding:24px;margin-bottom:24px;">
          <div style="display:flex;align-items:flex-start;gap:16px;">
            <div style="font-size:2rem;">🪪</div>
            <div style="flex:1;">
              <h3 style="margin:0 0 12px 0;font-size:1rem;">Identity Verification (Required)</h3>
              <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">
                Upload a clear photo of your government-issued ID (Passport, National ID, or Driver's License)
              </p>

              <div
                class="upload-zone"
                id="idUploadZone"
                ondrop="handleFilesDrop(event,'id')"
                ondragover="addDragHover(event)"
                ondragleave="removeDragHover(event)"
              >
                <div style="font-size:2rem;margin-bottom:8px;">📤</div>
                <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
                <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">JPG, PNG, GIF, WebP · Max 10MB</p>
                <input
                  type="file"
                  id="idFile"
                  name="idFile"
                  style="display:none;"
                  accept="image/*"
                  onchange="previewFile(this,'id')"
                >
                <input type="hidden" id="idFileSelected" value="">
              </div>

              <div id="idFilePreview" style="display:none;margin-top:12px;"></div>
              <p class="error-message" id="idUploadZone-error">Please upload your ID document (image only)</p>
            </div>
          </div>
        </div>

        <div style="background:var(--ivory-card);border-left:3px solid var(--gold);padding:16px;border-radius:var(--radius-sm);margin-top:24px;">
          <p style="margin:0;font-size:.875rem;color:var(--ink);">
            <strong>What happens next?</strong>
            Our verification team will review your document within 24–48 hours.
            We'll notify you once your account is verified and ready to use.
          </p>
        </div>

        <div class="step-nav">
          <button type="button" class="btn btn-outline" onclick="goToStep(1)">← Back</button>
          <div>
            <span class="step-counter">Step 2 of 2</span>
            <button type="button" class="btn btn-primary" onclick="submitProfile()" style="margin-left:12px;">
              Complete &amp; Submit →
            </button>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>

<script>
let currentStep = 1;

// If PHP returned errors relating to the upload, land on step 2
<?php if (!empty($errors)): ?>
  const phpErrors = <?= json_encode($errors) ?>;
  const uploadErrors = ['ID document upload is required.', 'ID must be an image', 'ID image must be'];
  const onStep2 = phpErrors.some(e => uploadErrors.some(u => e.startsWith(u)));
  if (onStep2) currentStep = 2;
<?php endif; ?>

window.addEventListener('DOMContentLoaded', () => {
  updateUI();

  document.getElementById('idUploadZone')
    .addEventListener('click', () => document.getElementById('idFile').click());

  const nameField = document.getElementById('personalName');
  if (nameField) {
    nameField.addEventListener('input', function () {
      this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
      if (this.value.trim()) clearFieldError('personalName');
    });
  }

  const dobField = document.getElementById('personalDOB');
  if (dobField) {
    dobField.addEventListener('input', function () {
      let v = this.value.replace(/[^0-9/]/g, '');
      if (v.length === 2 && !v.includes('/')) v += '/';
      else if (v.length === 5 && v.split('/').length === 2) v += '/';
      this.value = v;
      if (/^\d{2}\/\d{2}\/\d{4}$/.test(v)) clearFieldError('personalDOB');
    });
  }

  const phoneField = document.getElementById('personalPhone');
  if (phoneField) {
    phoneField.addEventListener('input', function () {
      this.value = this.value.replace(/[^0-9+]/g, '');
      if (/^(\+|00)[0-9]{6,15}$/.test(this.value)) clearFieldError('personalPhone');
    });
  }
});

function goToStep(step) {
  if (step < currentStep) {
    currentStep = step;
    updateUI();
    return;
  }
  if (currentStep === 1 && !validateStep1()) return;
  currentStep = step;
  updateUI();
}

function updateUI() {
  document.querySelectorAll('.wizard-step-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('step' + currentStep).classList.add('active');

  for (let i = 1; i <= 2; i++) {
    const dot   = document.getElementById('dot' + i);
    const title = document.getElementById('t' + i);
    dot.classList.remove('active', 'done');
    title.classList.remove('active', 'done');

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

  window.scrollTo(0, 0);
}

function validateStep1() {
  clearErrors();
  let valid = true;

  const name = document.getElementById('personalName').value.trim();
  if (!name || !/^[\p{L}\s]+$/u.test(name)) { showError('personalName'); valid = false; }

  const dob = document.getElementById('personalDOB').value.trim();
  if (!/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/.test(dob)) { showError('personalDOB'); valid = false; }

  const phone = document.getElementById('personalPhone').value.trim();
  if (!/^(\+|00)[0-9]{6,15}$/.test(phone)) { showError('personalPhone'); valid = false; }

  return valid;
}

function validateStep2() {
  clearErrors();
  if (!document.getElementById('idFileSelected').value) {
    showError('idUploadZone');
    return false;
  }
  return true;
}

function submitProfile() {
  if (!validateStep2()) return;
  document.getElementById('profile-form').submit();
}

function clearErrors() {
  document.querySelectorAll('.form-control').forEach(el => el.classList.remove('error'));
  document.querySelectorAll('.error-message').forEach(el => el.classList.remove('show'));
}

function clearFieldError(id) {
  const f = document.getElementById(id);
  const e = document.getElementById(id + '-error');
  if (f) f.classList.remove('error');
  if (e) e.classList.remove('show');
}

function showError(fieldId) {
  const f = document.getElementById(fieldId);
  const e = document.getElementById(fieldId + '-error');
  if (f?.classList.contains('form-control')) f.classList.add('error');
  if (e) e.classList.add('show');
}

function addDragHover(e)    { e.preventDefault(); e.stopPropagation(); e.currentTarget.classList.add('drag-over'); }
function removeDragHover(e) { e.currentTarget.classList.remove('drag-over'); }

function handleFilesDrop(e, type) {
  e.preventDefault();
  e.stopPropagation();
  e.currentTarget.classList.remove('drag-over');
  const files = e.dataTransfer.files;
  if (!files.length) return;
  const input = document.getElementById(type + 'File');
  const dt = new DataTransfer();
  dt.items.add(files[0]);
  input.files = dt.files;
  input.dispatchEvent(new Event('change', { bubbles: true }));
}

function previewFile(input, type) {
  const file = input.files[0];
  if (!file) return;
  const allowed = ['image/jpeg','image/png','image/gif','image/webp'];
  if (!allowed.includes(file.type)) {
    alert('ID document must be an image file (JPG, PNG, GIF, or WebP)');
    input.value = '';
    return;
  }
  document.getElementById('idFileSelected').value = file.name;
  showFilePreview(type, file);
}

function showFilePreview(type, file) {
  const zone    = document.getElementById(type + 'UploadZone');
  const preview = document.getElementById(type + 'FilePreview');
  const size    = file.size > 1048576
    ? (file.size / 1048576).toFixed(1) + ' MB'
    : (file.size / 1024).toFixed(0) + ' KB';

  preview.innerHTML = `
    <div class="file-preview">
      <div class="file-preview-icon">🖼️</div>
      <div class="file-preview-info">
        <div class="file-preview-name">${file.name}</div>
        <div class="file-preview-size">${size}</div>
      </div>
      <button type="button" class="file-preview-remove" onclick="removeFilePreview('${type}')">✕ Remove</button>
    </div>`;

  zone.style.display    = 'none';
  preview.style.display = 'block';
  zone.classList.remove('error');
  const err = document.getElementById(type + 'UploadZone-error');
  if (err) err.classList.remove('show');
}

function removeFilePreview(type) {
  document.getElementById(type + 'File').value        = '';
  document.getElementById(type + 'FileSelected').value = '';
  document.getElementById(type + 'FilePreview').style.display = 'none';
  document.getElementById(type + 'UploadZone').style.display  = 'block';
}
</script>
</body>
</html>