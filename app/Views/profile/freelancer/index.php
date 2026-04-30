<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Complete Your Profile — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/profile-setup.css">
</head>
<body>

<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-actions">
      <span class="text-sm text-muted">Profile Setup</span>
    </div>
  </div>
</nav>

<div class="wizard-shell">

  <!-- WIZARD LEFT NAV -->
  <div class="wizard-left">
    <div class="wizard-left-logo">Nexus<span>.</span></div>
    <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(247,244,239,.3);margin-bottom:16px;font-weight:700;">Specialist Setup</div>

    <div class="wizard-left-step" onclick="goToStep(1)">
      <div class="wzl-dot active" id="dot1">1</div>
      <div><div class="wzl-title active" id="t1">Professional Profile</div><div class="wzl-sub">Personal & skills info</div></div>
    </div>
    <div class="wizard-left-step" onclick="goToStep(2)">
      <div class="wzl-dot" id="dot2">2</div>
      <div><div class="wzl-title" id="t2">Verification</div><div class="wzl-sub">KYC & credentials</div></div>
    </div>
    <div class="wizard-left-step" onclick="goToStep(3)">
      <div class="wzl-dot" id="dot3">3</div>
      <div><div class="wzl-title" id="t3">Review</div><div class="wzl-sub">Confirm & submit</div></div>
    </div>

    <div style="margin-top:40px;padding-top:24px;border-top:1px solid rgba(255,255,255,.08);">
      <div style="font-size:.75rem;color:rgba(247,244,239,.3);line-height:1.8;">
        <div>✦ Verification typically takes 24-48 hours</div>
        <div class="mt-12">✦ Keep documents clearly visible</div>
        <div class="mt-12">✦ All information is encrypted</div>
        <div class="mt-12">✦ You can edit profile before verification</div>
      </div>
    </div>
  </div>

  <!-- WIZARD RIGHT PANELS -->
  <div class="wizard-right">

    <!-- STEP 1: PROFESSIONAL PROFILE -->
    <div class="wizard-step-panel active" id="step1">
      <div class="page-header">
        <div class="breadcrumb">Step 1 of 3</div>
        <h2>Professional Profile</h2>
        <p class="mt-4">Tell us about yourself and your expertise. This information will be visible to clients.</p>
      </div>

      <!-- LEGAL NAME -->
      <div class="form-group">
        <label class="form-label">Full Name (as on legal document)</label>
        <input type="text" id="fullName" class="form-control" placeholder="Enter your full name exactly as it appears in your ID" value="">
        <p class="error-message" id="fullName-error">Please enter your full name (letters only)</p>
        <p class="input-hint">This will be verified against your ID during KYC</p>
      </div>

      <!-- DATE OF BIRTH -->
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Date of Birth (dd/mm/yyyy)</label>
          <input type="text" id="dateOfBirth" class="form-control" placeholder="DD/MM/YYYY">
          <p class="error-message" id="dateOfBirth-error">Please enter a valid date in DD/MM/YYYY format (must be 18+)</p>
          <p class="input-hint">Must be 18+ to work on Nexus</p>
        </div>
        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <input type="text" id="phoneNumber" class="form-control" placeholder="+20 or 0020 (Egypt example)">
          <p class="error-message" id="phoneNumber-error">Please enter a valid phone number starting with + or 00</p>
        </div>
      </div>

      <!-- PRIMARY NICHE -->
      <div class="form-group">
        <label class="form-label">Primary Niche / Discipline</label>
        <select name="primaryNiche" id="primaryNiche" class="form-control" onchange="updateSkillsForNiche()">
          <option value="">Select your primary niche</option>
          <option value="data-science">Data Science & Machine Learning</option>
          <option value="legal">Legal Consulting & Compliance</option>
          <option value="translation">Technical Translation & Localization</option>
          <option value="financial">Financial Modelling & Analysis</option>
          <option value="biomedical">Biomedical Research & Publishing</option>
          <option value="cybersecurity">Cybersecurity Audit & Analysis</option>
        </select>
        <p class="error-message" id="primaryNiche-error">Please select your primary niche</p>
      </div>

      <!-- SKILLS -->
      <div class="form-group">
        <label class="form-label">Your Core Skills (select at least 1)</label>
        <p class="input-hint" style="margin-bottom:12px;">Choose skills that match your primary niche. You can add more in your full profile.</p>
        <div class="skill-grid" id="skillGrid">
          <!-- Skills will be populated based on niche selection -->
        </div>
        <p id="skillGrid-error" class="error-message" style="margin-top:12px;">Please select at least 1 skill</p>
        <p style="font-size:.75rem;color:#666;margin-bottom:16px;">Skills selected: <span id="skillCount">0</span> / 1 minimum</p>
      </div>

      <!-- EDUCATION LEVEL -->
      <div class="form-group">
        <label class="form-label">Education Level</label>
        <div class="education-select" id="educationSelect">
          <div class="education-card" onclick="selectEducation(this, 'high-school')">
            <div class="education-level">High School</div>
            <div class="education-sub">High school diploma or equivalent</div>
          </div>
          <div class="education-card" onclick="selectEducation(this, 'bachelor')">
            <div class="education-level">Bachelor's Degree</div>
            <div class="education-sub">4-year university degree</div>
          </div>
          <div class="education-card" onclick="selectEducation(this, 'master')">
            <div class="education-level">Master's Degree</div>
            <div class="education-sub">Graduate degree (MSc, MBA, etc.)</div>
          </div>
          <div class="education-card" onclick="selectEducation(this, 'phd')">
            <div class="education-level">PhD / Doctorate</div>
            <div class="education-sub">Doctoral degree</div>
          </div>
        </div>
        <p class="error-message" id="educationSelect-error">Please select your education level</p>
      </div>

      <!-- PROFESSIONAL SUMMARY -->
      <div class="form-group">
        <label class="form-label">Professional Summary (Optional)</label>
        <textarea class="form-control" rows="4" placeholder="Brief overview of your experience and what you specialize in (50-200 words)…"></textarea>
      </div>

      <div class="step-nav">
        <div></div>
        <div>
          <span class="step-counter">Step 1 of 3</span>
          <button class="btn btn-primary" onclick="goToStep(2)" style="margin-left:12px;">Continue to Verification →</button>
        </div>
      </div>
    </div>

    <!-- STEP 2: VERIFICATION -->
    <div class="wizard-step-panel" id="step2">
      <div class="page-header">
        <div class="breadcrumb">Step 2 of 3</div>
        <h2>Verification Documents</h2>
        <p class="mt-4">Upload documents to verify your identity and credentials. All information is encrypted and stored securely.</p>
      </div>

      <!-- IDENTITY VERIFICATION -->
      <div class="card" style="padding:24px;margin-bottom:24px;">
        <div style="display:flex;align-items:flex-start;gap:16px;">
          <div style="font-size:2rem;">🪪</div>
          <div style="flex:1;">
            <h3 style="margin:0 0 12px 0;font-size:1rem;">Identity Verification (KYC)</h3>
            <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload a clear photo of your government-issued ID (Passport, National ID, or Driver's License)</p>
            
            <div class="upload-zone" id="idUploadZone" ondrop="handleFilesDrop(event, 'id')" ondragover="addDragHover(event)" ondragleave="removeDragHover(event)">
              <div style="font-size:2rem;margin-bottom:8px;">📤</div>
              <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
              <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">JPG, PNG, PDF • Max 10MB</p>
              <input type="file" id="idFile" style="display:none;" accept="image/*,.pdf" onchange="previewFile(this, 'id')">
              <input type="hidden" id="idFileSelected" value="">
            </div>
            <div id="idFilePreview" style="display:none;margin-top:12px;"></div>
            <span id="idStatus" style="display:none;">Not uploaded</span>
            <p class="error-message" id="idUploadZone-error">Please upload your ID document</p>

          </div>
        </div>
      </div>

      <!-- EDUCATION / DEGREE PROOF (MANDATORY) -->
      <div class="card" style="padding:24px;margin-bottom:24px;">
        <div style="display:flex;align-items:flex-start;gap:16px;">
          <div style="font-size:2rem;">🎓</div>
          <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
              <h3 style="margin:0;font-size:1rem;">Education / Degree Proof</h3>
              <span class="badge" style="background:var(--red);color:white;font-size:.65rem;padding:3px 8px;">Required</span>
            </div>
            <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload your degree or educational certificate matching your education level.</p>
            
            <div class="upload-zone" id="educationUploadZone" ondrop="handleFilesDrop(event, 'education')" ondragover="addDragHover(event)" ondragleave="removeDragHover(event)">
              <div style="font-size:2rem;margin-bottom:8px;">📤</div>
              <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
              <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">JPG, PNG, PDF • Max 10MB</p>
              <input type="file" id="educationFile" style="display:none;" accept="image/*,.pdf" onchange="previewFile(this, 'education')">
              <input type="hidden" id="educationFileSelected" value="">
            </div>
            <div id="educationFilePreview" style="display:none;margin-top:12px;"></div>
            <span id="educationStatus" style="display:none;">Not uploaded</span>
            <p class="error-message" id="educationUploadZone-error">Please upload your education proof</p>

          </div>
        </div>
      </div>

      <!-- CURRICULUM VITAE -->
      <div class="card" style="padding:24px;margin-bottom:24px;">
        <div style="display:flex;align-items:flex-start;gap:16px;">
          <div style="font-size:2rem;">📄</div>
          <div style="flex:1;">
            <h3 style="margin:0 0 12px 0;font-size:1rem;">Curriculum Vitae (CV)</h3>
            <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload your CV or resume showcasing your professional experience.</p>
            
            <div class="upload-zone" id="cvUploadZone" ondrop="handleFilesDrop(event, 'cv')" ondragover="addDragHover(event)" ondragleave="removeDragHover(event)">
              <div style="font-size:2rem;margin-bottom:8px;">📤</div>
              <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
              <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">JPG, PNG, PDF • Max 10MB</p>
              <input type="file" id="cvFile" style="display:none;" accept="image/*,.pdf" onchange="previewFile(this, 'cv')">
              <input type="hidden" id="cvFileSelected" value="">
            </div>
            <div id="cvFilePreview" style="display:none;margin-top:12px;"></div>
            <span id="cvStatus" style="display:none;">Not uploaded</span>

          </div>
        </div>
      </div>

      <!-- ADDITIONAL CERTIFICATES -->
      <div class="card" style="padding:24px;">
        <div style="display:flex;align-items:flex-start;gap:16px;">
          <div style="font-size:2rem;">📜</div>
          <div style="flex:1;">
            <h3 style="margin:0 0 12px 0;font-size:1rem;">Additional Certificates</h3>
            <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload additional professional certifications with titles. You can add multiple certificates.</p>
            
            <div id="certificatesList" style="margin-bottom:16px;">
              <!-- Certificate entries will be added here -->
            </div>
            
            <button type="button" class="btn btn-outline" onclick="addCertificateField()" style="width:100%;margin-bottom:16px;border-color:var(--sage);color:var(--sage);">+ Add Certificate</button>
            
            <p style="font-size:.75rem;color:var(--ink-muted);">✓ Certificates added: <span id="certStatus">0</span></p>
          </div>
        </div>
      </div>

      <!-- INFO BANNER -->
      <div style="background:var(--ivory-card);border-left:3px solid var(--gold);padding:16px;border-radius:var(--radius-sm);margin-top:24px;">
        <p style="margin:0;font-size:.875rem;color:var(--ink);">
          <strong>What happens next?</strong> Our compliance team will review your documents within 24-48 hours. We'll notify you once verification is complete. You can continue working on your profile while we review.
        </p>
      </div>

      <div class="step-nav">
        <button class="btn btn-outline" onclick="goToStep(1)">← Back to Profile</button>
        <div>
          <span class="step-counter">Step 2 of 3</span>
          <button class="btn btn-primary" onclick="goToStep(3)" style="margin-left:12px;">Continue to Review →</button>
        </div>
      </div>
    </div>

    <!-- STEP 3: REVIEW & SUBMIT -->
    <div class="wizard-step-panel" id="step3">
      <div class="page-header">
        <div class="breadcrumb">Step 3 of 3</div>
        <h2>Review & Complete Setup</h2>
        <p class="mt-4">Review your information before submitting. You can edit details anytime in your profile settings.</p>
      </div>

      <!-- PROFILE SUMMARY -->
      <div class="card" style="margin-bottom:24px;">
        <div style="padding:24px;border-bottom:1px solid var(--border);">
          <h3 style="margin:0;font-size:1rem;">Personal Information</h3>
        </div>
        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:24px;">
          <div>
            <div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Full Name</div>
            <div id="reviewName" style="font-size:.95rem;font-weight:600;">—</div>
          </div>
          <div>
            <div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Date of Birth</div>
            <div id="reviewDOB" style="font-size:.95rem;font-weight:600;">—</div>
          </div>
          <div>
            <div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Phone Number</div>
            <div id="reviewPhone" style="font-size:.95rem;font-weight:600;">—</div>
          </div>
          <div>
            <div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Primary Niche</div>
            <div id="reviewNiche" style="font-size:.95rem;font-weight:600;">—</div>
          </div>
          <div>
            <div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Education Level</div>
            <div id="reviewEducation" style="font-size:.95rem;font-weight:600;">—</div>
          </div>
          <div>
            <div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Selected Skills</div>
            <div id="reviewSkillsSelected" style="font-size:.95rem;font-weight:600;">—</div>
          </div>
        </div>
        <div style="padding:0 24px 24px 24px;border-top:1px solid var(--border);margin-top:16px;padding-top:16px;">
          <div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:8px;">Professional Summary</div>
          <div id="reviewBio" style="font-size:.9rem;color:var(--ink);line-height:1.5;">—</div>
        </div>
      </div>

      <!-- SKILLS & CERTIFICATES SUMMARY -->
      <div class="card" style="margin-bottom:24px;">
        <div style="padding:24px;border-bottom:1px solid var(--border);">
          <h3 style="margin:0;font-size:1rem;">Skills & Certificates</h3>
        </div>
        <div style="padding:24px;display:flex;flex-wrap:wrap;gap:8px;" id="reviewSkillsCerts">
          <span style="color:var(--ink-muted);">—</span>
        </div>
      </div>

      <!-- DOCUMENTS SUMMARY -->
      <div class="card" style="margin-bottom:24px;">
        <div style="padding:24px;border-bottom:1px solid var(--border);">
          <h3 style="margin:0;font-size:1rem;">Verification Documents</h3>
        </div>
        <div style="padding:24px;" id="reviewDocuments">
          <div style="color:var(--ink-muted);">No documents uploaded</div>
        </div>
      </div>

      <!-- TERMS & CONDITIONS -->
      <div id="agreeTermsGroup" style="background:var(--ivory-card);border:1px solid var(--border);padding:16px;border-radius:var(--radius-sm);margin-bottom:24px;">
        <div style="display:flex;gap:10px;align-items:flex-start;">
          <input type="checkbox" id="agreeTerms" style="width:18px;height:18px;margin-top:2px;cursor:pointer;">
          <label for="agreeTerms" style="cursor:pointer;flex:1;font-size:.875rem;">
            I confirm that all information provided is accurate and complete. I understand that providing false information may result in account suspension.
          </label>
        </div>
        <p class="checkbox-error" id="agreeTerms-error">Please agree to the terms and conditions</p>
      </div>

      <div class="step-nav">
        <button class="btn btn-outline" onclick="goToStep(2)">← Back to Documents</button>
        <div>
          <span class="step-counter">Step 3 of 3</span>
          <button class="btn btn-primary" onclick="submitProfile()" style="margin-left:12px;">Complete & Submit →</button>
        </div>
      </div>
    </div>

  </div>
</div>

<script>

</script>

</body>
</html>
