<?php
$errors      = $errors ?? ['login' => '', 'signup' => ''];
$active_form = $active_form ?? 'login';
function showError($e){ return !empty($e) ? '<p class="error-msg">'.$e.'</p>' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nexus — Sign In / Profile Setup</title>
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/signup.css">
<link rel="stylesheet" href="assets/login.css">


</head>
<body>

<!-- ══════════════════════════════════════════════════
      PAGE 1 — AUTH (Login / Signup)
══════════════════════════════════════════════════ -->
<div id="auth-page" class="visible">

  <div class="auth-shell">

    <!-- LEFT PANEL -->
    <div class="auth-panel-left">
      <a class="auth-logo" href="index.html">Nexus<span>.</span></a>
      <div class="auth-left-body">
        <h2 class="auth-headline">Professional Work,<br><em>Properly</em> Structured.</h2>
        <p class="auth-sub">Join the marketplace designed for engagements where credentials, milestones, and trust are not optional — they are foundational.</p>
        <div class="auth-feature">
          <div class="auth-feature-icon">
            <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>
          </div>
          <div class="auth-feature-text"><strong>Verified Credentials</strong><br>Every specialist undergoes multi-stage license and certification validation.</div>
        </div>
        <div class="auth-feature">
          <div class="auth-feature-icon">
            <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M240-160q-66 0-113-47t-47-113v-480h640v480q0 66-47 113t-113 47H240Zm0-80h480q33 0 56.5-23.5T800-320v-400H160v400q0 33 23.5 56.5T240-240Zm120-120h240v-80H360v80Zm0-160h240v-80H360v80ZM240-240v-480 480Z"/></svg>
          </div>
          <div class="auth-feature-text"><strong>Milestone-Secured Escrow</strong><br>Funds release only on bilateral approval. Auto-approval protects specialists from non-responsive clients.</div>
        </div>
        <div class="auth-feature">
          <div class="auth-feature-icon">
            <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="m438-452-57-57q-11-11-27.5-11T325-509q-12 12-12 28.5t12 28.5l86 86q11 11 27 11t27-11l170-170q12-12 11.5-28.5T634-593q-12-12-28.5-12T577-593L438-452ZM480-80q-83 0-141.5-58.5T280-280v-320q0-66 32-121.5T400-810l200-110 200 110q56 32 88 87.5T920-600v320q0 83-58.5 141.5T720-80H480Z"/></svg>
          </div>
          <div class="auth-feature-text"><strong>Arbitration &amp; Audit Trail</strong><br>Every action is logged. Disputes are resolved with assembled evidence, not memory.</div>
        </div>
        <div class="auth-quote">
          <p>"Nexus is the only platform where my legal credentials were treated as non-negotiable requirements, not optional additions."</p>
          <cite>— J. Moreau, International Trade Lawyer</cite>
        </div>
      </div>
      <div style="position:relative;z-index:1;">
        <p style="font-size:.75rem;font-family:var(--font-mono);color:rgba(247,244,239,.3);letter-spacing:.1em;">SECURED BY 256-BIT ENCRYPTION · KYC VERIFIED · ISO 27001</p>
      </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-panel-right">
      <div class="auth-form-box">
        <div style="margin-bottom:32px;">
          <h2 style="font-family:var(--font-display);font-size:1.8rem;font-weight:500;margin-bottom:6px;">Welcome back</h2>
          <p style="font-size:.9rem;color:var(--ink-muted);">Sign in to your Nexus account or create one below.</p>
        </div>

        <div class="auth-tabs">
          <button class="auth-tab <?= $active_form === 'login'  ? 'active' : '' ?>" onclick="showTab('login')">Sign In</button>
          <button class="auth-tab <?= $active_form === 'signup' ? 'active' : '' ?>" onclick="showTab('register')">Create Account</button>
        </div>

        <!-- LOGIN FORM -->
        <div id="login" <?= $active_form !== 'login' ? 'class="hidden"' : '' ?>>
          <form action="/login" method="post">
            <?= showError($errors['login']) ?>
            <div class="form-group">
              <label class="form-label">Email Address</label>
              <div class="input-icon-wrap">
                <span class="input-icon"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg></span>
                <input type="email" name="email" class="form-control" placeholder="you@organization.com" required>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label" style="display:flex;justify-content:space-between;">
                Password <a href="#" class="forgot-link">Forgot password?</a>
              </label>
              <div class="input-icon-wrap">
                <span class="input-icon"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm296.5-143.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••••" required>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
              <input type="checkbox" id="remember" name="remember" style="accent-color:var(--gold);">
              <label for="remember" style="font-size:.875rem;color:var(--ink-mid);">Remember me</label>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-full" style="justify-content:center;">Sign In to Nexus</button>
          </form>
        </div>

        <!-- REGISTER FORM -->
        <div id="register" <?= $active_form !== 'signup' ? 'class="hidden"' : '' ?>>
          <form action="/signup" method="post" id="signup-form">
            <?= showError($errors['signup']) ?>
            <div class="verify-badge">
              <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor" style="flex-shrink:0;margin-top:1px"><path d="M480-80q-83 0-141.5-58.5T280-280v-320q0-66 32-121.5T400-810l200-110 200 110q56 32 88 87.5T920-600v320q0 83-58.5 141.5T720-80H480Z"/></svg>
              <div>All accounts undergo identity verification before accessing the platform marketplace.</div>
            </div>
            <div class="form-group">
              <label class="form-label">I am joining as</label>
              <div class="role-selector">
                <div class="role-card selected" onclick="selectRole(this,'Client')">
                  <div class="role-card-icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M120-120v-560h160v-160h400v320h160v400H520v-160h-80v160H120Zm80-80h80v-80h-80v80Zm0-160h80v-80h-80v80Zm0-160h80v-80h-80v80Zm160 160h80v-80h-80v80Zm0-160h80v-80h-80v80Zm0-160h80v-80h-80v80Zm160 320h80v-80h-80v80Zm0-160h80v-80h-80v80Zm0-160h80v-80h-80v80Zm160 480h80v-80h-80v80Zm0-160h80v-80h-80v80Z"/></svg></div>
                  <div class="role-card-label">Client</div>
                </div>
                <div class="role-card" id="specialist-role-card" onclick="selectRole(this,'Freelancer')">
                  <div class="role-card-icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg></div>
                  <div class="role-card-label">Specialist</div>
                </div>
              </div>
              <input type="hidden" name="role" id="role-input" value="Client" required>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">First Name</label>
                <input type="text" name="fname" class="form-control" placeholder="Amira" required>
              </div>
              <div class="form-group">
                <label class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-control" placeholder="Tawfik">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Professional Email</label>
              <div class="input-icon-wrap">
                <span class="input-icon"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg></span>
                <input type="email" name="email" class="form-control" placeholder="amira@company.com" required>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Password</label>
              <div class="input-icon-wrap">
                <span class="input-icon"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm296.5-143.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg></span>
                <input type="password" name="password" class="form-control" placeholder="Min. 10 characters" required>
              </div>
              <p class="form-hint">Must include uppercase, number, and symbol.</p>
            </div>
            <div class="form-group">
              <label class="form-label">Confirm Password</label>
              <div class="input-icon-wrap">
                <span class="input-icon"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm296.5-143.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg></span>
                <input type="password" name="confirm_password" class="form-control" placeholder="••••••••••" required>
              </div>
            </div>
            <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:24px;">
              <input type="checkbox" id="terms" name="terms" style="accent-color:var(--gold);margin-top:3px;" required>
              <label for="terms" style="font-size:.8125rem;color:var(--ink-mid);">I agree to Nexus's <a href="platform-guide.html#terms" style="color:var(--gold);">Terms of Service</a> and acknowledge the <a href="platform-guide.html#kyc" style="color:var(--gold);">KYC Verification Policy</a>.</label>
            </div>
            <!-- 
              For Specialist role: instead of a normal POST submit, clicking the button
              transitions the user to the profile wizard on the client side.
              For Client role: normal form POST.
            -->
            <button type="button" id="signup-btn" class="btn btn-primary w-full" style="justify-content:center;" onclick="handleSignup()">Create Account &amp; Begin Verification</button>
          </form>
        </div>

        <p class="auth-bottom-note">Need help? <a href="platform-guide.html#support">Contact support</a></p>
      </div>
    </div>

  </div>
</div>

<!-- ══════════════════════════════════════════════════
     PAGE 2 — PROFILE SETUP WIZARD (Specialist only)
══════════════════════════════════════════════════ -->
<div id="wizard-page">

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
        <div><div class="wzl-title active" id="t1">Professional Profile</div><div class="wzl-sub">Personal &amp; skills info</div></div>
      </div>
      <div class="wizard-left-step" onclick="goToStep(2)">
        <div class="wzl-dot" id="dot2">2</div>
        <div><div class="wzl-title" id="t2">Verification</div><div class="wzl-sub">KYC &amp; credentials</div></div>
      </div>
      <div class="wizard-left-step" onclick="goToStep(3)">
        <div class="wzl-dot" id="dot3">3</div>
        <div><div class="wzl-title" id="t3">Review</div><div class="wzl-sub">Confirm &amp; submit</div></div>
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

        <div class="form-group">
          <label class="form-label">Full Name (as on legal document)</label>
          <input type="text" id="fullName" class="form-control" placeholder="Enter your full name exactly as it appears in your ID">
          <p class="error-message" id="fullName-error">Please enter your full name (letters only)</p>
          <p class="input-hint">This will be verified against your ID during KYC</p>
        </div>

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

        <div class="form-group">
          <label class="form-label">Primary Niche / Discipline</label>
          <select id="primaryNiche" class="form-control" onchange="updateSkillsForNiche()">
            <option value="">Select your primary niche</option>
            <option value="data-science">Data Science &amp; Machine Learning</option>
            <option value="legal">Legal Consulting &amp; Compliance</option>
            <option value="translation">Technical Translation &amp; Localization</option>
            <option value="financial">Financial Modelling &amp; Analysis</option>
            <option value="biomedical">Biomedical Research &amp; Publishing</option>
            <option value="cybersecurity">Cybersecurity Audit &amp; Analysis</option>
          </select>
          <p class="error-message" id="primaryNiche-error">Please select your primary niche</p>
        </div>

        <div class="form-group">
          <label class="form-label">Your Core Skills (select at least 1)</label>
          <p class="input-hint" style="margin-bottom:12px;">Choose skills that match your primary niche.</p>
          <div class="skill-grid" id="skillGrid"></div>
          <p id="skillGrid-error" class="error-message" style="margin-top:12px;">Please select at least 1 skill</p>
          <p style="font-size:.75rem;color:#666;margin-bottom:16px;">Skills selected: <span id="skillCount">0</span> / 1 minimum</p>
        </div>

        <div class="form-group">
          <label class="form-label">Education Level</label>
          <div class="education-select" id="educationSelect">
            <div class="education-card" onclick="selectEducation(this,'high-school')"><div class="education-level">High School</div><div class="education-sub">High school diploma or equivalent</div></div>
            <div class="education-card" onclick="selectEducation(this,'bachelor')"><div class="education-level">Bachelor's Degree</div><div class="education-sub">4-year university degree</div></div>
            <div class="education-card" onclick="selectEducation(this,'master')"><div class="education-level">Master's Degree</div><div class="education-sub">Graduate degree (MSc, MBA, etc.)</div></div>
            <div class="education-card" onclick="selectEducation(this,'phd')"><div class="education-level">PhD / Doctorate</div><div class="education-sub">Doctoral degree</div></div>
          </div>
          <p class="error-message" id="educationSelect-error">Please select your education level</p>
        </div>

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

        <!-- IDENTITY -->
        <div class="card" style="padding:24px;margin-bottom:24px;">
          <div style="display:flex;align-items:flex-start;gap:16px;">
            <div style="font-size:2rem;">🪪</div>
            <div style="flex:1;">
              <h3 style="margin:0 0 12px 0;font-size:1rem;">Identity Verification (KYC)</h3>
              <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload a clear photo of your government-issued ID (Passport, National ID, or Driver's License)</p>
              <div class="upload-zone" id="idUploadZone" ondrop="handleFilesDrop(event,'id')" ondragover="addDragHover(event)" ondragleave="removeDragHover(event)">
                <div style="font-size:2rem;margin-bottom:8px;">📤</div>
                <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
                <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">JPG, PNG, PDF • Max 10MB</p>
                <input type="file" id="idFile" style="display:none;" accept="image/*,.pdf" onchange="previewFile(this,'id')">
                <input type="hidden" id="idFileSelected" value="">
              </div>
              <div id="idFilePreview" style="display:none;margin-top:12px;"></div>
              <p class="error-message" id="idUploadZone-error">Please upload your ID document</p>
            </div>
          </div>
        </div>

        <!-- EDUCATION PROOF -->
        <div class="card" style="padding:24px;margin-bottom:24px;">
          <div style="display:flex;align-items:flex-start;gap:16px;">
            <div style="font-size:2rem;">🎓</div>
            <div style="flex:1;">
              <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                <h3 style="margin:0;font-size:1rem;">Education / Degree Proof</h3>
                <span class="badge" style="background:var(--red);color:white;font-size:.65rem;padding:3px 8px;">Required</span>
              </div>
              <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload your degree or educational certificate matching your education level.</p>
              <div class="upload-zone" id="educationUploadZone" ondrop="handleFilesDrop(event,'education')" ondragover="addDragHover(event)" ondragleave="removeDragHover(event)">
                <div style="font-size:2rem;margin-bottom:8px;">📤</div>
                <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
                <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">JPG, PNG, PDF • Max 10MB</p>
                <input type="file" id="educationFile" style="display:none;" accept="image/*,.pdf" onchange="previewFile(this,'education')">
                <input type="hidden" id="educationFileSelected" value="">
              </div>
              <div id="educationFilePreview" style="display:none;margin-top:12px;"></div>
              <p class="error-message" id="educationUploadZone-error">Please upload your education proof</p>
            </div>
          </div>
        </div>

        <!-- CV -->
        <div class="card" style="padding:24px;margin-bottom:24px;">
          <div style="display:flex;align-items:flex-start;gap:16px;">
            <div style="font-size:2rem;">📄</div>
            <div style="flex:1;">
              <h3 style="margin:0 0 12px 0;font-size:1rem;">Curriculum Vitae (CV)</h3>
              <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload your CV or resume showcasing your professional experience.</p>
              <div class="upload-zone" id="cvUploadZone" ondrop="handleFilesDrop(event,'cv')" ondragover="addDragHover(event)" ondragleave="removeDragHover(event)">
                <div style="font-size:2rem;margin-bottom:8px;">📤</div>
                <p style="margin:0 0 6px 0;font-weight:700;">Drag and drop or click to upload</p>
                <p style="margin:0;font-size:.8rem;color:var(--ink-muted);">JPG, PNG, PDF • Max 10MB</p>
                <input type="file" id="cvFile" style="display:none;" accept="image/*,.pdf" onchange="previewFile(this,'cv')">
                <input type="hidden" id="cvFileSelected" value="">
              </div>
              <div id="cvFilePreview" style="display:none;margin-top:12px;"></div>
            </div>
          </div>
        </div>

        <!-- ADDITIONAL CERTS -->
        <div class="card" style="padding:24px;">
          <div style="display:flex;align-items:flex-start;gap:16px;">
            <div style="font-size:2rem;">📜</div>
            <div style="flex:1;">
              <h3 style="margin:0 0 12px 0;font-size:1rem;">Additional Certificates</h3>
              <p style="margin:0 0 16px 0;font-size:.9rem;color:var(--ink-muted);">Upload additional professional certifications with titles. You can add multiple certificates.</p>
              <div id="certificatesList" style="margin-bottom:16px;"></div>
              <button type="button" class="btn btn-outline" onclick="addCertificateField()" style="width:100%;margin-bottom:16px;border-color:var(--sage);color:var(--sage);">+ Add Certificate</button>
              <p style="font-size:.75rem;color:var(--ink-muted);">✓ Certificates added: <span id="certStatus">0</span></p>
            </div>
          </div>
        </div>

        <div style="background:var(--ivory-card);border-left:3px solid var(--gold);padding:16px;border-radius:var(--radius-sm);margin-top:24px;">
          <p style="margin:0;font-size:.875rem;color:var(--ink);">
            <strong>What happens next?</strong> Our compliance team will review your documents within 24-48 hours. We'll notify you once verification is complete.
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
          <h2>Review &amp; Complete Setup</h2>
          <p class="mt-4">Review your information before submitting. You can edit details anytime in your profile settings.</p>
        </div>

        <div class="card" style="margin-bottom:24px;">
          <div style="padding:24px;border-bottom:1px solid var(--border);"><h3 style="margin:0;font-size:1rem;">Personal Information</h3></div>
          <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <div><div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Full Name</div><div id="reviewName" style="font-size:.95rem;font-weight:600;">—</div></div>
            <div><div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Date of Birth</div><div id="reviewDOB" style="font-size:.95rem;font-weight:600;">—</div></div>
            <div><div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Phone Number</div><div id="reviewPhone" style="font-size:.95rem;font-weight:600;">—</div></div>
            <div><div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Primary Niche</div><div id="reviewNiche" style="font-size:.95rem;font-weight:600;">—</div></div>
            <div><div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Education Level</div><div id="reviewEducation" style="font-size:.95rem;font-weight:600;">—</div></div>
            <div><div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:4px;">Selected Skills</div><div id="reviewSkillsSelected" style="font-size:.95rem;font-weight:600;">—</div></div>
          </div>
          <div style="padding:0 24px 24px 24px;border-top:1px solid var(--border);margin-top:16px;padding-top:16px;">
            <div style="font-size:.75rem;color:var(--ink-muted);font-weight:700;text-transform:uppercase;margin-bottom:8px;">Professional Summary</div>
            <div id="reviewBio" style="font-size:.9rem;color:var(--ink);line-height:1.5;">—</div>
          </div>
        </div>

        <div class="card" style="margin-bottom:24px;">
          <div style="padding:24px;border-bottom:1px solid var(--border);"><h3 style="margin:0;font-size:1rem;">Skills &amp; Certificates</h3></div>
          <div style="padding:24px;display:flex;flex-wrap:wrap;gap:8px;" id="reviewSkillsCerts"><span style="color:var(--ink-muted);">—</span></div>
        </div>

        <div class="card" style="margin-bottom:24px;">
          <div style="padding:24px;border-bottom:1px solid var(--border);"><h3 style="margin:0;font-size:1rem;">Verification Documents</h3></div>
          <div style="padding:24px;" id="reviewDocuments"><div style="color:var(--ink-muted);">No documents uploaded</div></div>
        </div>

        <div id="agreeTermsGroup" style="background:var(--ivory-card);border:1px solid var(--border);padding:16px;border-radius:var(--radius-sm);margin-bottom:24px;">
          <div style="display:flex;gap:10px;align-items:flex-start;">
            <input type="checkbox" id="agreeTerms" style="width:18px;height:18px;margin-top:2px;cursor:pointer;">
            <label for="agreeTerms" style="cursor:pointer;flex:1;font-size:.875rem;">I confirm that all information provided is accurate and complete. I understand that providing false information may result in account suspension.</label>
          </div>
          <p class="checkbox-error" id="agreeTerms-error">Please agree to the terms and conditions</p>
        </div>

        <div class="step-nav">
          <button class="btn btn-outline" onclick="goToStep(2)">← Back to Documents</button>
          <div>
            <span class="step-counter">Step 3 of 3</span>
            <button class="btn btn-primary" onclick="submitProfile()" style="margin-left:12px;">Complete &amp; Submit →</button>
          </div>
        </div>
      </div>

    </div><!-- /wizard-right -->
  </div><!-- /wizard-shell -->
</div><!-- /wizard-page -->

<script>
  /* ─── PAGE TRANSITION ─────────────────────────────── */
    function showWizard() {
        document.getElementById('auth-page').classList.remove('visible');
        document.getElementById('wizard-page').classList.add('visible');
        window.scrollTo(0, 0);
    }

    function handleSignup() {
        const role = document.getElementById('role-input').value;
        if (role === 'Freelancer') {
            // Specialist: transition to wizard instead of submitting
            showWizard();
        } else {
            // Client: normal form submit
            document.getElementById('signup-form').submit();
        }
    }

    /* ─── AUTH HELPERS ────────────────────────────────── */
    function showTab(t) {
        document.getElementById('login').classList.toggle('hidden', t !== 'login');
        document.getElementById('register').classList.toggle('hidden', t !== 'register');
        document.querySelectorAll('.auth-tab').forEach((el, i) => el.classList.toggle('active', (i === 0 && t === 'login') || (i === 1 && t === 'register')));
    }
    function selectRole(el, role) {
        document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('role-input').value = role;
    }

    /* ─── WIZARD STATE ────────────────────────────────── */
    let currentStep = 1;
    let selectedSkills = new Set();
    let selectedNiche = '';
    let selectedEducation = '';
    let certificates = [];
    let cvFileRef = null;

    const skillsByNiche = {
        'data-science': ['Python', 'Machine Learning', 'Data Analysis', 'SQL', 'Statistics', 'NLP', 'Deep Learning', 'MLOps', 'Time Series'],
        'legal': ['Contract Review', 'Legal Research', 'Compliance Audit', 'Risk Assessment', 'Legal Writing', 'Case Law Analysis', 'Document Drafting', 'Due Diligence'],
        'translation': ['Technical Writing', 'Localization', 'Terminology Management', 'Machine Translation', 'File Format Conversion', 'Quality Assurance', 'Domain Expertise', 'Cultural Adaptation'],
        'financial': ['Excel Modeling', 'Financial Analysis', 'Valuation', 'Forecasting', 'Investment Analysis', 'Risk Analysis', 'Budget Planning', 'Scenario Analysis'],
        'biomedical': ['Research Methodology', 'Data Analysis', 'Literature Review', 'Scientific Writing', 'Statistical Analysis', 'Lab Techniques', 'Regulatory Compliance', 'Publication Support'],
        'cybersecurity': ['Penetration Testing', 'Vulnerability Assessment', 'Security Audits', 'Risk Management', 'Threat Analysis', 'Security Protocols', 'Incident Response', 'Compliance']
    };
    const nicheDisplayNames = {
        'data-science': 'Data Science & Machine Learning', 'legal': 'Legal Consulting & Compliance',
        'translation': 'Technical Translation & Localization', 'financial': 'Financial Modelling & Analysis',
        'biomedical': 'Biomedical Research & Publishing', 'cybersecurity': 'Cybersecurity Audit & Analysis'
    };
    const educationDisplayNames = {
        'high-school': 'High School', 'bachelor': "Bachelor's Degree", 'master': "Master's Degree", 'phd': 'PhD / Doctorate'
    };
    let formData = {};

    function goToStep(step) {
        if (step < currentStep) { currentStep = step; updateUI(); return; }
        if (currentStep === 1 && !validateStep1()) return;
        if (currentStep === 2 && !validateStep2()) return;
        currentStep = step;
        updateUI();
        if (step === 3) { collectFormData(); displayReviewData(); }
    }

    function updateUI() {
        document.querySelectorAll('.wizard-step-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('step' + currentStep).classList.add('active');
        for (let i = 1; i <= 3; i++) {
            const dot = document.getElementById('dot' + i);
            const title = document.getElementById('t' + i);
            if (i < currentStep) {
                dot.className = 'wzl-dot done'; dot.textContent = '✓';
                title.className = 'wzl-title done';
            } else if (i === currentStep) {
                dot.className = 'wzl-dot active'; dot.textContent = i;
                title.className = 'wzl-title active';
            } else {
                dot.className = 'wzl-dot'; dot.textContent = i;
                title.className = 'wzl-title';
            }
        }
        window.scrollTo(0, 0);
    }

    function updateSkillsForNiche() {
        const niche = document.getElementById('primaryNiche').value;
        selectedNiche = niche; selectedSkills.clear();
        const sg = document.getElementById('skillGrid');
        sg.innerHTML = ''; sg.classList.remove('error');
        document.getElementById('skillGrid-error').classList.remove('show');
        if (niche && skillsByNiche[niche]) {
            skillsByNiche[niche].forEach(skill => {
                const b = document.createElement('div');
                b.className = 'skill-badge'; b.textContent = skill;
                b.onclick = function () { toggleSkill(this); };
                sg.appendChild(b);
            });
        }
        document.getElementById('skillCount').textContent = '0';
    }

    function toggleSkill(elem) {
        elem.classList.toggle('selected');
        const skill = elem.textContent;
        elem.classList.contains('selected') ? selectedSkills.add(skill) : selectedSkills.delete(skill);
        document.getElementById('skillCount').textContent = selectedSkills.size;
        if (selectedSkills.size >= 1) {
            document.getElementById('skillGrid').classList.remove('error');
            document.getElementById('skillGrid-error').classList.remove('show');
        }
    }

    function selectEducation(elem, value) {
        document.querySelectorAll('.education-card').forEach(e => e.classList.remove('selected', 'error'));
        elem.classList.add('selected'); selectedEducation = value;
        document.getElementById('educationSelect-error').classList.remove('show');
    }

    /* file upload helpers */
    function addDragHover(e) { e.preventDefault(); e.stopPropagation(); e.currentTarget.classList.add('drag-over'); }
    function removeDragHover(e) { e.currentTarget.classList.remove('drag-over'); }
    function handleFilesDrop(e, type) {
        e.preventDefault(); e.stopPropagation(); e.currentTarget.classList.remove('drag-over');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const fi = document.getElementById(type + 'File');
            const dt = new DataTransfer(); dt.items.add(files[0]); fi.files = dt.files;
            fi.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
    function getFileIcon(n) { if (n.endsWith('.pdf')) return '📕'; if (n.match(/\.(jpg|jpeg|png|gif)$/i)) return '🖼️'; return '📄'; }
    function formatFileSize(b) { if (!b) return ''; if (b < 1024) return b + ' B'; if (b < 1048576) return (b / 1024).toFixed(1) + ' KB'; return (b / 1048576).toFixed(1) + ' MB'; }

    function previewFile(input, type) {
        const file = input.files[0]; if (!file) return;
        if (type === 'id') {
            const valid = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!valid.includes(file.type)) { alert('ID document must be an image file (JPG, PNG, GIF, or WebP)'); input.value = ''; return; }
        }
        if (type === 'cv') cvFileRef = file;
        document.getElementById(type + 'FileSelected').value = file.name;
        showFilePreview(type, file);
    }

    function showFilePreview(type, file) {
        const uz = document.getElementById(type + 'UploadZone');
        const pd = document.getElementById(type + 'FilePreview');
        pd.innerHTML = `<div class="file-preview"><div class="file-preview-icon">${getFileIcon(file.name)}</div><div class="file-preview-info"><div class="file-preview-name">${file.name}</div><div class="file-preview-size">${formatFileSize(file.size)}</div></div><button type="button" class="file-preview-remove" onclick="removeFilePreview('${type}')">✕ Remove</button></div>`;
        uz.style.display = 'none'; pd.style.display = 'block';
        uz.classList.remove('error');
        const em = document.getElementById(type + 'UploadZone-error');
        if (em) em.classList.remove('show');
    }

    function removeFilePreview(type) {
        const uz = document.getElementById(type + 'UploadZone');
        const pd = document.getElementById(type + 'FilePreview');
        document.getElementById(type + 'File').value = '';
        document.getElementById(type + 'FileSelected').value = '';
        if (type === 'cv') cvFileRef = null;
        pd.style.display = 'none'; uz.style.display = 'block';
    }

    function addCertificateField() {
        const index = certificates.length;
        document.getElementById('certificatesList').insertAdjacentHTML('beforeend', `
        <div id="cert-group-${index}" style="padding:16px;background:var(--ivory-card);border-radius:var(--radius-md);margin-bottom:12px;border:1px solid var(--border);">
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Certificate Title</label>
              <input type="text" class="form-control" id="cert-title-${index}" placeholder="e.g., AWS Solutions Architect, PMP, CPA" onchange="updateCertificateTitle(${index})">
            </div>
            <div class="form-group">
              <label class="form-label">Upload Certificate</label>
              <div style="display:flex;gap:8px;">
                <input type="file" id="cert-file-${index}" style="display:none;" accept="image/*,.pdf" onchange="handleCertificateUpload(this,${index})">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('cert-file-${index}').click()" style="flex:1;">Choose File</button>
                <button type="button" class="btn btn-outline" style="padding:0 16px;color:var(--red);border-color:var(--red);" onclick="removeCertificate(${index})">✕</button>
              </div>
              <p id="cert-file-status-${index}" style="font-size:.75rem;color:var(--ink-muted);margin-top:4px;">No file selected</p>
            </div>
          </div>
        </div>`);
        certificates.push({ index, title: '', file: null });
        updateCertificateCount();
    }

    function updateCertificateTitle(index) { if (certificates[index]) certificates[index].title = document.getElementById('cert-title-' + index)?.value.trim(); }
    function handleCertificateUpload(input, index) { const f = input.files[0]; if (f) { certificates[index].file = f; document.getElementById('cert-file-status-' + index).textContent = 'File: ' + f.name; } }
    function removeCertificate(index) { document.getElementById('cert-group-' + index)?.remove(); certificates[index] = null; updateCertificateCount(); }
    function updateCertificateCount() { document.getElementById('certStatus').textContent = certificates.filter(c => c !== null && c.file !== null).length; }

    /* validation */
    function clearErrors() {
        document.querySelectorAll('.form-control,.education-card,.skill-grid').forEach(e => e.classList.remove('error'));
        document.querySelectorAll('.error-message').forEach(e => e.classList.remove('show'));
    }
    function showError(fid) {
        const f = document.getElementById(fid); const em = document.getElementById(fid + '-error');
        if (f) { if (f.classList.contains('form-control') || f.classList.contains('skill-grid')) f.classList.add('error'); else if (fid.includes('education')) document.querySelectorAll('.education-card').forEach(c => c.classList.add('error')); }
        if (em) em.classList.add('show');
    }
    function validateStep1() {
        clearErrors(); let ok = true;
        const name = document.getElementById('fullName').value.trim();
        if (!name || !/^[a-zA-Z\s]+$/.test(name)) { showError('fullName'); ok = false; }
        const dob = document.getElementById('dateOfBirth').value.trim();
        if (!dob || !/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/\d{4}$/.test(dob)) { showError('dateOfBirth'); ok = false; }
        else {
            const [d, m, y] = dob.split('/'); const bd = new Date(y, m - 1, d); const now = new Date();
            const age = now.getFullYear() - bd.getFullYear(); const md = now.getMonth() - bd.getMonth(); const dd = now.getDate() - bd.getDate();
            if (age < 18 || (age === 18 && md < 0) || (age === 18 && md === 0 && dd < 0)) { showError('dateOfBirth'); ok = false; }
        }
        const ph = document.getElementById('phoneNumber').value.trim();
        if (!ph || !/^(\+|00)[0-9]{1,15}$/.test(ph)) { showError('phoneNumber'); ok = false; }
        if (!document.getElementById('primaryNiche').value) { showError('primaryNiche'); ok = false; }
        if (selectedSkills.size < 1) { showError('skillGrid'); ok = false; }
        if (!selectedEducation) { showError('educationSelect'); ok = false; }
        return ok;
    }
    function validateStep2() {
        clearStep2Errors(); let ok = true;
        if (!document.getElementById('idFileSelected').value) { showStep2Error('idUploadZone'); ok = false; }
        if (!document.getElementById('educationFileSelected').value) { showStep2Error('educationUploadZone'); ok = false; }
        return ok;
    }
    function showStep2Error(fid) { document.getElementById(fid)?.classList.add('error'); document.getElementById(fid + '-error')?.classList.add('show'); }
    function clearStep2Errors() {
        document.querySelectorAll('.upload-zone').forEach(e => e.classList.remove('error'));
        document.querySelectorAll('#idUploadZone-error,#educationUploadZone-error').forEach(e => e.classList.remove('show'));
    }

    function collectFormData() {
        formData = {
            fullName: document.getElementById('fullName').value.trim(),
            dateOfBirth: document.getElementById('dateOfBirth').value.trim(),
            phoneNumber: document.getElementById('phoneNumber').value.trim(),
            niche: selectedNiche, nicheDisplay: nicheDisplayNames[selectedNiche] || '',
            skills: Array.from(selectedSkills),
            education: selectedEducation, educationDisplay: educationDisplayNames[selectedEducation] || '',
            summary: document.querySelector('.form-group textarea')?.value.trim() || '',
            idFileName: document.getElementById('idFileSelected').value || '',
            educationFileName: document.getElementById('educationFileSelected').value || '',
            cvFileName: document.getElementById('cvFileSelected').value || '',
            certificates: certificates.filter(c => c !== null && c.title).map(c => ({
                title: document.getElementById('cert-title-' + c.index)?.value.trim() || c.title,
                fileName: document.getElementById('cert-file-status-' + c.index)?.textContent || ''
            }))
        };
    }

    function displayReviewData() {
        document.getElementById('reviewName').textContent = formData.fullName || '—';
        document.getElementById('reviewDOB').textContent = formData.dateOfBirth || '—';
        document.getElementById('reviewPhone').textContent = formData.phoneNumber || '—';
        document.getElementById('reviewNiche').textContent = formData.nicheDisplay || '—';
        document.getElementById('reviewEducation').textContent = formData.educationDisplay || '—';
        document.getElementById('reviewSkillsSelected').textContent = formData.skills.length ? formData.skills.join(', ') : '—';
        document.getElementById('reviewBio').textContent = formData.summary || '—';

        const sc = document.getElementById('reviewSkillsCerts'); sc.innerHTML = '';
        formData.skills.forEach(s => { const b = document.createElement('span'); b.className = 'badge badge-gold'; b.textContent = s; sc.appendChild(b); });
        formData.certificates.forEach(c => { const b = document.createElement('span'); b.className = 'badge badge-default'; b.textContent = c.title; sc.appendChild(b); });
        if (!formData.skills.length && !formData.certificates.length) sc.innerHTML = '<span style="color:var(--ink-muted);">No skills or certificates</span>';

        const dc = document.getElementById('reviewDocuments');
        const docs = [];
        if (formData.idFileName) docs.push({ title: 'Identity Document', fileName: formData.idFileName });
        if (formData.educationFileName) docs.push({ title: 'Education Proof', fileName: formData.educationFileName });
        if (formData.cvFileName) docs.push({ title: 'Curriculum Vitae', fileName: formData.cvFileName });
        formData.certificates.forEach(c => docs.push({ title: c.title, fileName: c.fileName.replace('File: ', '') || c.title }));
        dc.innerHTML = docs.length ? docs.map(d => `<div style="padding:12px;background:var(--gold-pale);border-radius:var(--radius-sm);margin-bottom:12px;"><div style="font-weight:600;font-size:.9rem;">${d.title}</div><div style="font-size:.8rem;color:var(--ink-muted);margin-top:4px;">${d.fileName}</div><div style="font-size:.75rem;color:var(--gold);margin-top:6px;font-weight:600;">Under Review</div></div>`).join('') : '<div style="color:var(--ink-muted);">No documents uploaded</div>';
    }

    function validateStep3() {
        const atg = document.getElementById('agreeTermsGroup'); const em = document.getElementById('agreeTerms-error');
        if (!document.getElementById('agreeTerms').checked) { atg.classList.add('checkbox-group', 'error'); em.classList.add('show'); return false; }
        atg.classList.remove('checkbox-group', 'error'); em.classList.remove('show'); return true;
    }

    function submitProfile() {
        if (!validateStep3()) return;
        document.getElementById('signup-form').submit();
    }

    /* init upload zone clicks after DOM ready */
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('idUploadZone').addEventListener('click', () => document.getElementById('idFile').click());
        document.getElementById('educationUploadZone').addEventListener('click', () => document.getElementById('educationFile').click());
        document.getElementById('cvUploadZone').addEventListener('click', () => document.getElementById('cvFile').click());

        document.getElementById('agreeTerms').addEventListener('change', function () {
            if (this.checked) { document.getElementById('agreeTermsGroup').classList.remove('checkbox-group', 'error'); document.getElementById('agreeTerms-error').classList.remove('show'); }
        });

        document.getElementById('fullName').addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
            if (this.value.trim() && /^[a-zA-Z\s]+$/.test(this.value)) { this.classList.remove('error'); document.getElementById('fullName-error').classList.remove('show'); }
        });
        document.getElementById('dateOfBirth').addEventListener('input', function () {
            let v = this.value.replace(/[^0-9/]/g, '');
            if (v.length === 2 && !v.includes('/')) v += '/';
            else if (v.length === 5 && v.split('/').length === 2) v += '/';
            this.value = v;
            if (/^\d{2}\/\d{2}\/\d{4}$/.test(v)) { this.classList.remove('error'); document.getElementById('dateOfBirth-error').classList.remove('show'); }
        });
        document.getElementById('phoneNumber').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9+]/g, '');
            if (/^(\+|00)[0-9]{1,15}$/.test(this.value)) { this.classList.remove('error'); document.getElementById('phoneNumber-error').classList.remove('show'); }
        });
        document.getElementById('primaryNiche').addEventListener('change', function () {
            if (this.value) { this.classList.remove('error'); document.getElementById('primaryNiche-error').classList.remove('show'); }
        });
    });

      function validateSignupForm() {
        const form    = document.getElementById('signup-form');
        const fname   = form.querySelector('[name="fname"]').value.trim();
        const email   = form.querySelector('[name="email"]').value.trim();
        const password = form.querySelector('[name="password"]').value;
        const confirm  = form.querySelector('[name="confirm_password"]').value;
        const terms    = document.getElementById('terms').checked;

        document.querySelectorAll('.auth-field-error').forEach(e => e.remove());
        document.querySelectorAll('.form-control').forEach(e => e.classList.remove('error'));

        let ok = true;

        function markError(input, msg) {
            input.classList.add('error');
            const p = document.createElement('p');
            p.className = 'error-msg auth-field-error';
            p.textContent = msg;
            input.closest('.form-group').appendChild(p);
            ok = false;
        }

        if (!fname)
            markError(form.querySelector('[name="fname"]'), 'First name is required.');

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email))
            markError(form.querySelector('[name="email"]'), 'A valid email is required.');

        if (!password || password.length < 10)
            markError(form.querySelector('[name="password"]'), 'Password must be at least 10 characters.');

        if (password !== confirm)
            markError(form.querySelector('[name="confirm_password"]'), 'Passwords do not match.');

        if (!terms) {
            const p = document.createElement('p');
            p.className = 'error-msg auth-field-error';
            p.textContent = 'You must agree to the Terms of Service.';
            document.querySelector('label[for="terms"]').parentElement.appendChild(p);
            ok = false;
        }

        return ok;
    }

    function handleSignup() {
        if (!validateSignupForm()) return;   // ← stop here if invalid

        const role = document.getElementById('role-input').value;
        if (role === 'Freelancer') {
            showWizard();
        } else {
            document.getElementById('signup-form').submit();
        }
    }
</script>
</body>
</html>
