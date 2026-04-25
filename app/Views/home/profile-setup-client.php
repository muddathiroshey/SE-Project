<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Complete Your Profile — Nexus</title>
<link rel="stylesheet" href="../../../public/assets/css/style.css">
<link rel="stylesheet" href="../../../public/assets/css/profile-setup-client.css">
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
    <!-- STEP 1: PERSONAL INFORMATION -->
    <div class="wizard-step-panel active" id="step1">
      <div class="page-header">
        <div class="breadcrumb">Step 1 of 2</div>
        <h2>Your Personal Information</h2>
        <p class="mt-4">Please provide your personal details.</p>
      </div>

      <!-- LEGAL NAME -->
      <div class="form-group">
        <label class="form-label">Full Name (as on legal document)</label>
        <input type="text" id="personalName" class="form-control" placeholder="Enter your full name">
        <p class="error-message" id="personalName-error">Please enter your full name (letters only)</p>
        <p class="input-hint">This will be verified against your ID during verification</p>
      </div>

      <!-- DATE OF BIRTH -->
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Date of Birth (DD/MM/YYYY)</label>
          <input type="text" id="personalDOB" class="form-control" placeholder="DD/MM/YYYY">
          <p class="error-message" id="personalDOB-error">Please enter a valid date in DD/MM/YYYY format</p>
        </div>
        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <input type="text" id="personalPhone" class="form-control" placeholder="+20 or 0020 (Egypt example)">
          <p class="error-message" id="personalPhone-error">Please enter a valid phone number starting with + or 00</p>
        </div>
      </div>

      <!-- BIO -->
      <div class="form-group">
        <label class="form-label">Bio / About You</label>
        <textarea id="personalBio" class="form-control" placeholder="Tell us about yourself and your needs (50-200 words)…"></textarea>
        <p class="input-hint">This helps us understand your requirements better</p>
      </div>

      <div class="step-nav">
        <div></div>
        <div>
          <span class="step-counter">Step 1 of 2</span>
          <button class="btn btn-primary" onclick="goToStep(2)" style="margin-left:12px;">Continue to Verification →</button>
        </div>
      </div>
    </div>

    <!-- STEP 2: VERIFICATION -->
    <div class="wizard-step-panel" id="step2">
      <div class="page-header">
        <div class="breadcrumb">Step 2 of 2</div>
        <h2>Identity Verification</h2>
        <p class="mt-4">Upload your ID to verify your identity.</p>
      </div>

      <!-- ID VERIFICATION -->
      <div class="card" style="padding:24px;margin-bottom:24px;">
        <div style="display:flex;align-items:flex-start;gap:16px;">
          <div style="font-size:2rem;">🪪</div>
          <div style="flex:1;">
            <h3 style="margin:0 0 12px 0;font-size:1rem;">Identity Verification (Required)</h3>
            <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload a clear photo of your government-issued ID (Passport, National ID, or Driver's License)</p>
            
            <div class="upload-zone" id="idUploadZone" ondrop="handleFilesDrop(event, 'id')" ondragover="addDragHover(event)" ondragleave="removeDragHover(event)">
              <div style="font-size:2rem;margin-bottom:8px;">📤</div>
              <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
              <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">JPG, PNG, GIF, WebP • Max 10MB</p>
              <input type="file" id="idFile" style="display:none;" accept="image/*" onchange="previewFile(this, 'id')">
              <input type="hidden" id="idFileSelected" value="">
            </div>
            <div id="idFilePreview" style="display:none;margin-top:12px;"></div>
            <p class="error-message" id="idUploadZone-error">Please upload your ID document (image only)</p>
          </div>
        </div>
      </div>

      <!-- INFO BANNER -->
      <div style="background:var(--ivory-card);border-left:3px solid var(--gold);padding:16px;border-radius:var(--radius-sm);margin-top:24px;">
        <p style="margin:0;font-size:.875rem;color:var(--ink);">
          <strong>What happens next?</strong> Our verification team will review your document within 24-48 hours. We'll notify you once your account is verified and ready to use.
        </p>
      </div>

      <div class="step-nav">
        <button class="btn btn-outline" onclick="goToStep(1)">← Back</button>
        <div>
          <span class="step-counter">Step 2 of 2</span>
          <button class="btn btn-primary" onclick="submitProfile()" style="margin-left:12px;">Complete & Submit →</button>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
let currentStep = 1;
let formData = {
  personalName: '',
  personalDOB: '',
  personalPhone: '',
  personalBio: '',
  idFileName: ''
};

function goToStep(step) {
  if (step < currentStep) {
    currentStep = step;
    updateUI();
    return;
  }
  
  // Validate current step
  if (currentStep === 1 && !validateStep1()) {
    return;
  }
  
  currentStep = step;
  updateUI();
}

function updateUI() {
  // Hide all panels
  document.querySelectorAll('.wizard-step-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('step' + currentStep).classList.add('active');
  
  // Update sidebar
  for (let i = 1; i <= 2; i++) {
    const dot = document.getElementById('dot' + i);
    const title = document.getElementById('t' + i);
    
    if (i < currentStep) {
      dot.classList.add('done');
      dot.classList.remove('active');
      dot.textContent = '✓';
      title.classList.add('done');
      title.classList.remove('active');
    } else if (i === currentStep) {
      dot.classList.add('active');
      dot.classList.remove('done');
      dot.textContent = i;
      title.classList.add('active');
      title.classList.remove('done');
    } else {
      dot.classList.remove('active', 'done');
      dot.textContent = i;
      title.classList.remove('active', 'done');
    }
  }
  
  window.scrollTo(0, 0);
}

function validateStep1() {
  clearErrors();
  let isValid = true;
  
  // Validate name (letters only)
  const name = document.getElementById('personalName').value.trim();
  const nameRegex = /^[a-zA-Z\s]+$/;
  if (!name || !nameRegex.test(name)) {
    showError('personalName');
    isValid = false;
  }
  
  // Validate DOB
  const dob = document.getElementById('personalDOB').value.trim();
  const dobRegex = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/\d{4}$/;
  if (!dob || !dobRegex.test(dob)) {
    showError('personalDOB');
    isValid = false;
  }
  
  // Validate phone
  const phone = document.getElementById('personalPhone').value.trim();
  const phoneRegex = /^(\+|00)[0-9]{1,15}$/;
  if (!phone || !phoneRegex.test(phone)) {
    showError('personalPhone');
    isValid = false;
  }
  
  formData.personalName = name;
  formData.personalDOB = dob;
  formData.personalPhone = phone;
  formData.personalBio = document.getElementById('personalBio').value.trim();
  
  return isValid;
}

function validateStep2() {
  clearErrors();
  
  const idUploaded = document.getElementById('idFileSelected').value !== '';
  if (!idUploaded) {
    showError('idUploadZone');
    return false;
  }
  
  return true;
}

function clearErrors() {
  document.querySelectorAll('.form-control').forEach(elem => {
    elem.classList.remove('error');
  });
  document.querySelectorAll('.error-message').forEach(elem => {
    elem.classList.remove('show');
  });
}

function showError(fieldId) {
  const field = document.getElementById(fieldId);
  const errorMsg = document.getElementById(fieldId + '-error');
  
  if (field && field.classList.contains('form-control')) {
    field.classList.add('error');
  }
  
  if (errorMsg) {
    errorMsg.classList.add('show');
  }
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
  if (filename.endsWith('.pdf')) return '📕';
  if (filename.match(/\.(jpg|jpeg|png|gif)$/i)) return '🖼️';
  return '📄';
}

function formatFileSize(bytes) {
  if (!bytes) return '';
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function previewFile(input, type) {
  const file = input.files[0];
  if (!file) return;
  
  // Validate file type
  const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
  if (!validImageTypes.includes(file.type)) {
    alert('ID document must be an image file (JPG, PNG, GIF, or WebP)');
    input.value = '';
    return;
  }
  
  document.getElementById('idFileSelected').value = file.name;
  formData.idFileName = file.name;
  showFilePreview(type, file);
}

function showFilePreview(type, file) {
  const uploadZone = document.getElementById(type + 'UploadZone');
  const previewDiv = document.getElementById(type + 'FilePreview');
  
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
  
  uploadZone.classList.remove('error');
  const errorMsg = document.getElementById(type + 'UploadZone-error');
  if (errorMsg) {
    errorMsg.classList.remove('show');
  }
}

function removeFilePreview(type) {
  const uploadZone = document.getElementById(type + 'UploadZone');
  const previewDiv = document.getElementById(type + 'FilePreview');
  const fileInput = document.getElementById(type + 'File');
  
  fileInput.value = '';
  document.getElementById(type + 'FileSelected').value = '';
  formData.idFileName = '';
  
  previewDiv.style.display = 'none';
  uploadZone.style.display = 'block';
}

function submitProfile() {
  if (!validateStep2()) {
    return;
  }
  
  // TODO: Send data to PHP backend
  window.location.href = 'dashboard-client.html';
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
  // Upload zone clicks
  const idZone = document.getElementById('idUploadZone');
  if (idZone) {
    idZone.addEventListener('click', () => document.getElementById('idFile').click());
  }
  
  // Personal name field - letters only
  const personalNameField = document.getElementById('personalName');
  if (personalNameField) {
    personalNameField.addEventListener('input', function() {
      this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
      if (this.value.trim() && /^[a-zA-Z\s]+$/.test(this.value)) {
        this.classList.remove('error');
        document.getElementById('personalName-error').classList.remove('show');
      }
    });
  }
  
  // Personal DOB field - DD/MM/YYYY format
  const personalDobField = document.getElementById('personalDOB');
  if (personalDobField) {
    personalDobField.addEventListener('input', function() {
      let value = this.value.replace(/[^0-9/]/g, '');
      if (value.length === 2 && !value.includes('/')) {
        value = value + '/';
      } else if (value.length === 5 && value.split('/').length === 2) {
        value = value + '/';
      }
      this.value = value;
      if (value && /^\d{2}\/\d{2}\/\d{4}$/.test(value)) {
        this.classList.remove('error');
        document.getElementById('personalDOB-error').classList.remove('show');
      }
    });
  }
  
  // Personal phone field
  const personalPhoneField = document.getElementById('personalPhone');
  if (personalPhoneField) {
    personalPhoneField.addEventListener('input', function() {
      let value = this.value.trim();
      this.value = value.replace(/[^0-9+]/g, '');
      if (this.value && /^(\+|00)[0-9]{1,15}$/.test(this.value)) {
        this.classList.remove('error');
        document.getElementById('personalPhone-error').classList.remove('show');
      }
    });
  }
});
</script>

</body>
</html>
