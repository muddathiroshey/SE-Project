<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile — Nexus</title>
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/css/specialist-profile-edit.css">
<script src="assets/js/specialist-profile-edit.js"></script>
</head>
<body>

<!-- ══════════════════ TOPNAV ══════════════════ -->
<nav class="topnav">
  <div class="container" style="max-width:100%;padding:0 32px;">
    <a class="topnav-logo" href="index.php">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="dashboard-freelancer.php">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.php" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔<span class="notif-count" style="position:absolute;top:2px;right:2px;">7</span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">DR</div></div>
          <span style="font-size:.875rem;font-weight:700;">Dr. Rania K.</span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">My Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="profile-edit.php">My Profile</a>
          <a class="dropdown-item" href="escrow-wallet.html">Wallet &amp; Escrow</a>
          <a class="dropdown-item" href="#">Account Settings</a>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="login.html" style="color:var(--rust);">Sign Out</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- ══════════════════ 3-COLUMN SHELL ══════════════════ -->
<div class="edit-shell">

  <!-- ── LEFT NAV ── -->
  <aside class="sidebar" style="padding-top:24px;">
    <div class="sidebar-label">Profile Sections</div>
    <a class="edit-nav-link active" href="#sec-identity" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a4 4 0 1 1 0 8A4 4 0 0 1 8 1zm0 9c-3.3 0-6 1.6-6 3v1h12v-1c0-1.4-2.7-3-6-3z"/></svg>
      Identity &amp; Headline
    </a>
    <a class="edit-nav-link" href="#sec-about" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M4 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 2v1h6V3H5zm0 3v1h6V6H5zm0 3v1h4V9H5z"/></svg>
      About &amp; Specializations
    </a>
    <a class="edit-nav-link" href="#sec-skills" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12v2H2V2zm0 4h12v2H2V6zm0 4h8v2H2v-2z"/></svg>
      Skills &amp; Languages
    </a>
    <a class="edit-nav-link" href="#sec-credentials" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><circle cx="8" cy="5" r="3"/><path d="M8 9l-3 6h6L8 9z"/></svg>
      Credentials &amp; Certs
    </a>
    <a class="edit-nav-link" href="#sec-portfolio" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="3" width="14" height="11" rx="1"/><path d="M5 3V2h6v1"/></svg>
      Portfolio
    </a>
    <a class="edit-nav-link" href="#sec-billing" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm0 2v6h12V6H2zm9 1h2v2h-2V7z"/></svg>
      Billing &amp; Tax
    </a>
    <a class="edit-nav-link" href="#sec-rates" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1v14M4 5h6a2 2 0 0 1 0 4H6a2 2 0 0 0 0 4h6"/></svg>
      Rates
    </a>
    <a class="edit-nav-link" href="#sec-privacy" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1L2 4v4c0 3.3 2.5 6.4 6 7 3.5-.6 6-3.7 6-7V4L8 1z"/></svg>
      Privacy &amp; Visibility
    </a>

    <div class="sidebar-label" style="margin-top:16px;">Actions</div>
    <a class="edit-nav-link" href="expert-profile.php" onclick="savePreviewStateAndGo(event)">
      👁 Preview as Client
    </a>
  </aside>

  <!-- ── MAIN EDIT AREA ── -->
  <main class="edit-main">

    <!-- ════ SECTION 1: IDENTITY ════ -->
    <div class="edit-section" id="sec-identity">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 01</div>
          <div class="edit-section-heading">Identity &amp; Headline</div>
          <div class="edit-section-desc">This is the first thing clients see. Your name, photo, title, and location form their initial impression.</div>
        </div>
      </div>

      <!-- AVATAR UPLOAD -->
      <div class="avatar-upload-zone">
        <div class="avatar-upload-target" onclick="document.getElementById('avatar-input').click()">
          <div class="avatar avatar-xl">DR</div>
          <div class="avatar-overlay">
            <span style="font-size:1.2rem;">📷</span>
            <span>Change</span>
          </div>
          <input type="file" id="avatar-input" accept="image/*" style="display:none;" onchange="handleAvatar(this)">
        </div>
        <div class="avatar-upload-info">
          <strong>Profile Photo</strong>
          Upload a professional, high-resolution portrait. Specialists with photos receive 2.8× more invitations.
          <span>JPG or PNG · Min. 400×400px · Max 5MB</span>
          <div style="display:flex;gap:8px;margin-top:10px;">
            <button class="btn btn-outline btn-sm" onclick="document.getElementById('avatar-input').click()">Upload Photo</button>
            <button class="btn btn-ghost btn-sm" style="color:var(--rust);">Remove</button>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">
          Full Name
          <span class="text-muted font-mono" style="font-size:.7rem;text-transform:none;letter-spacing:0;font-weight:400;margin-left:8px;">Locked for security</span>
        </label>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <input id="full-name-field" type="text" class="form-control" value="Rania Khalil" readonly aria-readonly="true" style="flex:1;min-width:260px;background:var(--ivory-deep);cursor:not-allowed;">
          <button type="button" class="btn btn-outline btn-sm" onclick="openNameChangeModal()">Request Name Change</button>
        </div>
        <p class="form-hint mt-4">To update your legal name, submit a request with an ID image for verification.</p>
      </div>

      <div class="form-group">
        <label class="form-label">
          Professional Title
          <span class="text-muted font-mono" style="font-size:.7rem;text-transform:none;letter-spacing:0;font-weight:400;margin-left:8px;">Shown below your name on your public profile</span>
        </label>
        <input id="professional-title-field" type="text" class="form-control" value="Senior Data Scientist · Machine Learning &amp; NLP Research" oninput="markUnsaved();countChars(this,80,'title-counter')">
        <div class="flex justify-between mt-4">
          <p class="form-hint">Be specific. "Machine Learning Engineer — Arabic NLP" outperforms "Data Scientist".</p>
          <span class="char-counter" id="title-counter">57 / 80</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Country</label>
          <select id="country-select" class="form-control" onchange="syncTimezoneForCountry(true)">
            <option selected>Egypt</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Timezone</label>
          <select id="timezone-select" class="form-control" onchange="handleTimezoneChange()" disabled>
            <option selected>Africa/Cairo</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Years of Experience</label>
          <input id="experience-years-field" type="number" class="form-control" value="9" min="0" max="50" oninput="markUnsaved()">
        </div>
        <div class="form-group">
          <label class="form-label">Primary Niche</label>
          <select id="primary-niche-field" class="form-control" onchange="markUnsaved()">
            <option selected>Data Science &amp; Machine Learning</option>
            <option>Legal Consulting</option>
            <option>Technical Translation</option>
            <option>Financial Modelling</option>
            <option>Cybersecurity Audit</option>
            <option>Biomedical Research</option>
          </select>
        </div>
      </div>
    </div>

    <!-- ════ SECTION 2: ABOUT ════ -->
    <div class="edit-section" id="sec-about">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 02</div>
          <div class="edit-section-heading">About &amp; Specializations</div>
          <div class="edit-section-desc">Your bio and specialization cards form the core of your public overview tab.</div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Professional Bio</label>
        <textarea id="bio-field" class="form-control" rows="7" oninput="markUnsaved();countChars(this,900,'bio-counter')">With a PhD in Computer Science and nine years of applied research and consulting experience, I specialize in high-stakes machine learning projects where interpretability, reliability, and domain alignment are non-negotiable. My work spans predictive modelling for financial services, NLP systems for Arabic-English corpora, and production ML pipelines for regulated industries.

I approach every engagement with a structured, milestone-driven methodology: clear deliverables, versioned notebooks, documented assumptions, and stakeholder-ready presentations at each phase. I do not accept projects outside my verified areas of expertise.

I am particularly experienced in working within the constraints of regulated industries where model explainability (SHAP, LIME) and audit trails are required deliverables, not afterthoughts.</textarea>
        <div class="flex justify-between mt-4">
          <p class="form-hint">Write in first person. Clients read this before deciding to invite you.</p>
          <span class="char-counter" id="bio-counter">672 / 900</span>
        </div>
      </div>

      <hr class="divider">
      <div class="flex justify-between items-center mb-16">
        <label class="form-label" style="margin:0;">Specialization Cards <span class="text-muted font-mono" style="font-size:.7rem;text-transform:none;letter-spacing:0;font-weight:400;margin-left:6px;">Displayed as 2×2 grid on public profile · max 4</span></label>
        <button class="btn btn-outline btn-sm" onclick="addSpecCard()">+ Add Card</button>
      </div>

      <div id="spec-cards">
        <div class="spec-edit-card">
          <div class="form-group" style="margin:0;">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" value="Predictive Modelling" oninput="markUnsaved()">
          </div>
          <div class="form-group" style="margin:0;">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" value="Churn, risk scoring, demand forecasting for FS/retail" oninput="markUnsaved()">
          </div>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);margin-top:22px;" onclick="this.closest('.spec-edit-card').remove();markUnsaved()">🗑</button>
        </div>
        <div class="spec-edit-card">
          <div class="form-group" style="margin:0;">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" value="Arabic NLP" oninput="markUnsaved()">
          </div>
          <div class="form-group" style="margin:0;">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" value="Sentiment analysis, named entity recognition, summarization" oninput="markUnsaved()">
          </div>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);margin-top:22px;" onclick="this.closest('.spec-edit-card').remove();markUnsaved()">🗑</button>
        </div>
        <div class="spec-edit-card">
          <div class="form-group" style="margin:0;">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" value="Explainable AI" oninput="markUnsaved()">
          </div>
          <div class="form-group" style="margin:0;">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" value="SHAP, LIME, regulatory reporting for banking/healthcare" oninput="markUnsaved()">
          </div>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);margin-top:22px;" onclick="this.closest('.spec-edit-card').remove();markUnsaved()">🗑</button>
        </div>
        <div class="spec-edit-card">
          <div class="form-group" style="margin:0;">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" value="ML Engineering" oninput="markUnsaved()">
          </div>
          <div class="form-group" style="margin:0;">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" value="Pipeline architecture, MLFlow, Docker, AWS Sagemaker" oninput="markUnsaved()">
          </div>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);margin-top:22px;" onclick="this.closest('.spec-edit-card').remove();markUnsaved()">🗑</button>
        </div>
      </div>
    </div>

    <!-- ════ SECTION 3: SKILLS & LANGUAGES ════ -->
    <div class="edit-section" id="sec-skills">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 03</div>
          <div class="edit-section-heading">Skills &amp; Languages</div>
          <div class="edit-section-desc">Skills appear as searchable tags and as scored bars on your profile's Skills tab.</div>
        </div>
      </div>

      <hr class="divider">
      <div class="mb-16">
        <label class="form-label" style="margin:0;">Skill Proficiency Scores <span class="text-muted font-mono" style="font-size:.7rem;text-transform:none;letter-spacing:0;font-weight:400;margin-left:6px;">Shown as progress bars on Skills tab</span></label>
      </div>

      <div id="skill-rows">
        <div class="skill-edit-row">
          <input type="text" class="form-control" value="Python (ML Ecosystem)" oninput="markUnsaved()">
          <select class="form-control skill-level-select" onchange="markUnsaved()">
            <option>Beginner</option><option>Intermediate</option><option>Advanced</option><option selected>Expert</option>
          </select>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.skill-edit-row').remove();markUnsaved()">🗑</button>
        </div>
        <div class="skill-edit-row">
          <input type="text" class="form-control" value="PyTorch / TensorFlow" oninput="markUnsaved()">
          <select class="form-control skill-level-select" onchange="markUnsaved()">
            <option>Beginner</option><option>Intermediate</option><option>Advanced</option><option selected>Expert</option>
          </select>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.skill-edit-row').remove();markUnsaved()">🗑</button>
        </div>
        <div class="skill-edit-row">
          <input type="text" class="form-control" value="Arabic NLP" oninput="markUnsaved()">
          <select class="form-control skill-level-select" onchange="markUnsaved()">
            <option>Beginner</option><option>Intermediate</option><option>Advanced</option><option selected>Expert</option>
          </select>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.skill-edit-row').remove();markUnsaved()">🗑</button>
        </div>
        <div class="skill-edit-row">
          <input type="text" class="form-control" value="Statistical Modelling" oninput="markUnsaved()">
          <select class="form-control skill-level-select" onchange="markUnsaved()">
            <option>Beginner</option><option>Intermediate</option><option selected>Advanced</option><option>Expert</option>
          </select>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.skill-edit-row').remove();markUnsaved()">🗑</button>
        </div>
        <div class="skill-edit-row">
          <input type="text" class="form-control" value="AWS / GCP ML Services" oninput="markUnsaved()">
          <select class="form-control skill-level-select" onchange="markUnsaved()">
            <option>Beginner</option><option>Intermediate</option><option selected>Advanced</option><option>Expert</option>
          </select>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.skill-edit-row').remove();markUnsaved()">🗑</button>
        </div>
      </div>
      <button class="add-row-btn w-full mt-8" onclick="addSkillRow()">+ Add another skill</button>

      <hr class="divider">
      <div class="flex justify-between items-center mb-12">
        <label class="form-label" style="margin:0;">Languages</label>
        <button class="add-row-btn" style="width:auto;padding:6px 14px;font-size:.8125rem;" onclick="addLangRow()">+ Add Language</button>
      </div>
      <div id="lang-rows">
        <div class="language-row">
          <input type="text" class="form-control" value="Arabic" oninput="markUnsaved()">
          <select class="form-control" onchange="markUnsaved()"><option selected>Native</option><option>Fluent</option><option>Conversational</option><option>Basic</option></select>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.language-row').remove();markUnsaved()">🗑</button>
        </div>
        <div class="language-row">
          <input type="text" class="form-control" value="English" oninput="markUnsaved()">
          <select class="form-control" onchange="markUnsaved()"><option>Native</option><option selected>Fluent</option><option>Conversational</option><option>Basic</option></select>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.language-row').remove();markUnsaved()">🗑</button>
        </div>
        <div class="language-row">
          <input type="text" class="form-control" value="French" oninput="markUnsaved()">
          <select class="form-control" onchange="markUnsaved()"><option>Native</option><option>Fluent</option><option selected>Conversational</option><option>Basic</option></select>
          <button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.language-row').remove();markUnsaved()">🗑</button>
        </div>
      </div>
    </div>

    <!-- ════ SECTION 4: CREDENTIALS ════ -->
    <div class="edit-section" id="sec-credentials">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 04</div>
          <div class="edit-section-heading">Credentials &amp; Certifications</div>
          <div class="edit-section-desc">Each credential you add goes through Nexus's verification workflow. Verified credentials unlock Top Tier status.</div>
        </div>
        <button class="btn btn-gold btn-sm" onclick="addCredential()">+ Add Credential</button>
      </div>

      <!-- KYC STATUS BAND -->
      <div class="verify-band mb-20">
        <span>🛡</span>
        <div style="flex:1;font-size:.8125rem;">
          <strong>Identity &amp; KYC verified</strong> — National ID and residential address confirmed Apr 2, 2025.
          <span style="color:var(--sage);font-weight:700;margin-left:6px;">✓ Complete</span>
        </div>
      </div>

      <div id="credential-list">

        <!-- CREDENTIAL 1 — VERIFIED, COLLAPSED -->
        <div class="credential-edit-item" id="cred-0">
          <div class="credential-edit-header" onclick="toggleCred(0)">
            <span class="credential-drag-handle">⠿</span>
            <div class="credential-type-icon">🎓</div>
            <div class="credential-edit-name">
              <div style="font-weight:700;">Doctor of Philosophy — Computer Science</div>
              <div style="font-size:.8125rem;color:var(--ink-muted);font-weight:400;">American University in Cairo · 2016</div>
            </div>
            <span class="badge badge-verified badge-dot" style="flex-shrink:0;">Verified</span>
            <span class="credential-chevron">▾</span>
          </div>
          <div class="credential-edit-body">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Degree / Credential Type</label>
                <select class="form-control" onchange="toggleOtherCredentialType(this)"><option selected>Doctoral Degree (PhD)</option><option>Master's Degree</option><option>Bachelor's Degree</option><option>Professional Certification</option><option>Award / Recognition</option><option>Other</option></select>
              </div>
              <div class="form-group other-credential-type-group" style="display:none;">
                <label class="form-label">Specify Credential Type</label>
                <input type="text" class="form-control" placeholder="Describe the credential type" oninput="markUnsaved()">
              </div>
              <div class="form-group">
                <label class="form-label">Field / Discipline</label>
                <input type="text" class="form-control" value="Computer Science" oninput="markUnsaved()">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Issuing Institution</label>
                <input type="text" class="form-control" value="American University in Cairo" oninput="markUnsaved()">
              </div>
              <div class="form-group">
                <label class="form-label">Year Awarded</label>
                <input type="number" class="form-control" value="2016" oninput="markUnsaved()">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Supporting Document</label>
              <div class="uploaded-file-row">
                <div class="uploaded-file-icon">📄</div>
                <span class="uploaded-file-name">phd_certificate_AUC_2016.pdf</span>
                <span class="uploaded-file-size">1.2 MB</span>
                <span class="badge badge-verified" style="font-size:.625rem;flex-shrink:0;">Verified by Nexus</span>
                <button class="btn btn-ghost btn-sm" style="color:var(--rust);">Replace</button>
              </div>
            </div>
          </div>
        </div>

        <!-- CREDENTIAL 2 — VERIFIED -->
        <div class="credential-edit-item" id="cred-1">
          <div class="credential-edit-header" onclick="toggleCred(1)">
            <span class="credential-drag-handle">⠿</span>
            <div class="credential-type-icon">🏅</div>
            <div class="credential-edit-name">
              <div style="font-weight:700;">Professional ML Engineer (PMLE)</div>
              <div style="font-size:.8125rem;color:var(--ink-muted);font-weight:400;">Google Cloud · Expires Dec 2025</div>
            </div>
            <span class="badge badge-verified badge-dot" style="flex-shrink:0;">Verified</span>
            <span class="credential-chevron">▾</span>
          </div>
          <div class="credential-edit-body">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Credential Type</label>
                <select class="form-control" onchange="toggleOtherCredentialType(this)"><option>Doctoral Degree (PhD)</option><option>Master's Degree</option><option>Bachelor's Degree</option><option selected>Professional Certification</option><option>Award / Recognition</option><option>Other</option></select>
              </div>
              <div class="form-group other-credential-type-group" style="display:none;">
                <label class="form-label">Specify Credential Type</label>
                <input type="text" class="form-control" placeholder="Describe the credential type" oninput="markUnsaved()">
              </div>
              <div class="form-group">
                <label class="form-label">Issuing Organization</label>
                <input type="text" class="form-control" value="Google Cloud" oninput="markUnsaved()">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Issue Date</label>
                <input type="month" class="form-control" value="2023-12">
              </div>
              <div class="form-group">
                <label class="form-label">Expiry Date</label>
                <input type="month" class="form-control" value="2025-12">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Credential ID / URL</label>
              <input type="text" class="form-control" value="https://www.credential.net/abc123" placeholder="Verification link or ID number" oninput="markUnsaved()">
            </div>
            <div class="form-group">
              <label class="form-label">Certificate Document</label>
              <div class="uploaded-file-row">
                <div class="uploaded-file-icon">📄</div>
                <span class="uploaded-file-name">google_pmle_cert_2023.pdf</span>
                <span class="uploaded-file-size">840 KB</span>
                <span class="badge badge-verified" style="font-size:.625rem;flex-shrink:0;">Verified</span>
                <button class="btn btn-ghost btn-sm" style="color:var(--rust);">Replace</button>
              </div>
            </div>
          </div>
        </div>

        <!-- CREDENTIAL 3 — PENDING -->
        <div class="credential-edit-item" id="cred-2">
          <div class="credential-edit-header" onclick="toggleCred(2)">
            <span class="credential-drag-handle">⠿</span>
            <div class="credential-type-icon">🏅</div>
            <div class="credential-edit-name">
              <div style="font-weight:700;">AWS Certified ML — Specialty</div>
              <div style="font-size:.8125rem;color:var(--ink-muted);font-weight:400;">Amazon Web Services · Expires Jun 2025</div>
            </div>
            <span class="badge badge-pending badge-dot" style="flex-shrink:0;">Under Review</span>
            <span class="credential-chevron">▾</span>
          </div>
          <div class="credential-edit-body">
            <div class="verify-band" style="margin-bottom:16px;background:#FDF3E0;border-color:#F0D899;">
              <span>⏳</span>
              <div style="font-size:.8125rem;color:#9A6800;">Submitted 3 days ago · Nexus verification team is reviewing your uploaded certificate. Typical review: 2–5 business days.</div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Credential Type</label>
                <select class="form-control" disabled><option selected>Professional Certification</option></select>
              </div>
              <div class="form-group">
                <label class="form-label">Issuing Organization</label>
                <input type="text" class="form-control" value="Amazon Web Services" readonly style="background:var(--ivory-deep);">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Uploaded Document</label>
              <div class="uploaded-file-row">
                <div class="uploaded-file-icon">📄</div>
                <span class="uploaded-file-name">aws_ml_specialty_2023.pdf</span>
                <span class="uploaded-file-size">1.1 MB</span>
                <span class="badge badge-pending" style="font-size:.625rem;flex-shrink:0;">Reviewing</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ════ SECTION 5: PORTFOLIO ════ -->
    <div class="edit-section" id="sec-portfolio">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 05</div>
          <div class="edit-section-heading">Portfolio</div>
          <div class="edit-section-desc">Upload notebooks, case studies, anonymized reports, or research papers. Sensitive items can be marked private.</div>
        </div>
        <button class="btn btn-outline btn-sm" onclick="addPortfolioItem()">+ Add Item</button>
      </div>

      <div id="portfolio-list">

        <div class="portfolio-edit-item">
          <div class="portfolio-edit-header">
            <div class="portfolio-type">📓</div>
            <div style="flex:1;min-width:0;">
              <div style="font-weight:700;font-size:.9rem;">Credit Risk Scoring Model — GCC Banking</div>
              <div class="text-xs text-muted font-mono">Jupyter Notebook · 84MB · Apr 2024</div>
            </div>
            <div style="display:flex;gap:6px;flex-shrink:0;">
              <span class="badge badge-gold" style="font-size:.625rem;">Public</span>
              <button class="btn btn-ghost btn-sm" onclick="togglePortfolioEdit(this)">Edit</button>
              <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.closest('.portfolio-edit-item').remove();markUnsaved()">🗑</button>
            </div>
          </div>
          <div class="portfolio-edit-body" style="display:none;">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" value="Credit Risk Scoring Model — GCC Banking" oninput="markUnsaved()">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea class="form-control" rows="2" placeholder="Briefly describe what this project demonstrates…">End-to-end credit risk pipeline for a regional GCC bank — feature engineering, XGBoost model, SHAP explainability, and regulatory report.</textarea>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Visibility</label>
                <select class="form-control" onchange="markUnsaved()">
                  <option selected>Public (visible to all)</option>
                  <option>Hidden (not displayed)</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Client Name</label>
                <input type="text" class="form-control" placeholder="Shown only if public · leave blank to hide" oninput="markUnsaved()">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Replace File</label>
              <div class="uploaded-file-row">
                <div class="uploaded-file-icon">📓</div>
                <span class="uploaded-file-name">credit_risk_model_anon.ipynb</span>
                <span class="uploaded-file-size">84 MB</span>
                <button class="btn btn-ghost btn-sm" style="margin-left:auto;">Replace</button>
              </div>
            </div>
          </div>
        </div>

        <div class="portfolio-edit-item">
          <div class="portfolio-edit-header">
            <div class="portfolio-type">📊</div>
            <div style="flex:1;min-width:0;">
              <div style="font-weight:700;font-size:.9rem;">Churn Prediction Pipeline — Telecom (MENA)</div>
              <div class="text-xs text-muted font-mono">Python Project · Anonymized · Jan 2024</div>
            </div>
            <div style="display:flex;gap:6px;flex-shrink:0;">
              <span class="badge badge-gold" style="font-size:.625rem;">Public</span>
              <button class="btn btn-ghost btn-sm" onclick="togglePortfolioEdit(this)">Edit</button>
              <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.closest('.portfolio-edit-item').remove();markUnsaved()">🗑</button>
            </div>
          </div>
          <div class="portfolio-edit-body" style="display:none;"></div>
        </div>

        <div class="portfolio-edit-item">
          <div class="portfolio-edit-header">
            <div class="portfolio-type">📄</div>
            <div style="flex:1;min-width:0;">
              <div style="font-weight:700;font-size:.9rem;">Arabic Sentiment Analysis — EMNLP 2023</div>
              <div class="text-xs text-muted font-mono">Research Paper · Public Publication · 2023</div>
            </div>
            <div style="display:flex;gap:6px;flex-shrink:0;">
              <span class="badge badge-default" style="font-size:.625rem;">Private</span>
              <button class="btn btn-ghost btn-sm" onclick="togglePortfolioEdit(this)">Edit</button>
              <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.closest('.portfolio-edit-item').remove();markUnsaved()">🗑</button>
            </div>
          </div>
          <div class="portfolio-edit-body" style="display:none;"></div>
        </div>

      </div>

      <div class="form-group" style="margin-top:20px;">
        <div class="text-xs text-muted">Add a new portfolio item via the button above. The item will appear immediately after submission.</div>
      </div>
    </div>

    <!-- ════ SECTION 6: BILLING & TAX ════ -->
    <div class="edit-section" id="sec-billing">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 06</div>
          <div class="edit-section-heading">Billing &amp; Tax</div>
          <div class="edit-section-desc">Manage payment methods, invoicing preferences, and tax registration details used for VAT calculation.</div>
        </div>
        <a href="escrow-wallet.html" class="btn btn-outline btn-sm">View Full Wallet →</a>
      </div>

      <h4 style="font-size:.9rem;margin-bottom:14px;">Payment Methods</h4>
      <!-- PHP: foreach($paymentMethods as $pm): -->
      <div id="payment-methods-list">
        <div class="payment-card default">
          <div class="card-chip">MC</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;">Mastercard ···· 4821</div>
            <div class="text-xs text-muted">Expires 09/27</div>
          </div>
          <span class="badge badge-verified" style="font-size:.625rem;">Default</span>
          <button class="btn btn-ghost btn-sm" style="color:var(--rust);">Remove</button>
        </div>
        <div class="payment-card">
          <div class="card-chip visa">VISA</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;">Visa ···· 2201</div>
            <div class="text-xs text-muted">Expires 03/26</div>
          </div>
          <button class="btn btn-ghost btn-sm">Set Default</button>
          <button class="btn btn-ghost btn-sm" style="color:var(--rust);">Remove</button>
        </div>
      </div>
      <button class="btn btn-outline btn-sm mt-8" onclick="document.getElementById('add-card-modal').classList.remove('hidden')">+ Add Payment Method</button>

      <hr class="divider">
      <h4 style="font-size:.9rem;margin-bottom:14px;">Tax Information</h4>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tax Jurisdiction</label>
          <select id="tax-jurisdiction-select" class="form-control" onchange="updateVatRate(); markUnsaved()">
            <option selected>USA — US Dollar (USD)</option>
            <option>Germany — Euro (EUR)</option>
            <option>United Kingdom — Pound Sterling (GBP)</option>
            <option>Egypt — Egyptian Pound (EGP)</option>
            <option>Saudi Arabia — Riyal (SAR)</option>
            <option>Kuwait — Kuwaiti Dinar (KWD)</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Applicable VAT Rate</label>
          <input id="vat-rate-input" type="text" class="form-control" value="0% (No VAT)" readonly style="background:var(--ivory-deep);">
          <p class="form-hint">Auto-calculated from jurisdiction.</p>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">VAT Registration Number</label>
          <input type="text" class="form-control" value="EG-VAT-28841-C" oninput="markUnsaved()">
        </div>
        <div class="form-group">
          <label class="form-label">Tax ID / TIN</label>
          <input type="text" class="form-control" value="EGY-TIN-774821" oninput="markUnsaved()">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Billing Address for Invoices</label>
        <textarea class="form-control" rows="2" oninput="markUnsaved()">Cairo, Egypt</textarea>
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);">
        <div><div style="font-weight:700;font-size:.875rem;">Currency</div><div class="text-xs text-muted">All invoices and receipts issued in</div></div>
        <select class="form-control" style="width:160px;" onchange="markUnsaved()">
          <option selected>USD — US Dollar</option>
          <option>EUR — Euro</option>
          <option>GBP — Pound Sterling</option>
          <option>EGP — Egyptian Pound</option>
          <option>SAR — Saudi Riyal</option>
          <option>KWD — Kuwaiti Dinar</option>
        </select>
      </div>
    </div>

    <!-- ════ SECTION 7: RATES ════ -->
    <div class="edit-section" id="sec-rates">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 07</div>
          <div class="edit-section-heading">Rates</div>
          <div class="edit-section-desc">Set your minimum fixed-project floor.</div>
        </div>
      </div>

      <div class="rate-card mb-24">
        <div class="rate-card-header">
          <label class="form-label" style="margin:0;">Fixed-Project Minimum</label>
          <span class="badge badge-default">Floor only</span>
        </div>
        <div class="rate-input-wrap">
          <span class="currency-symbol">$</span>
          <input type="number" class="form-control" value="3000" min="500" step="100" oninput="markUnsaved()">
        </div>
        <p class="form-hint mt-8">Bids below this floor will require your manual override.</p>
      </div>
    </div>

    <!-- ════ SECTION 8: PRIVACY ════ -->
    <div class="edit-section" id="sec-privacy">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 08</div>
          <div class="edit-section-heading">Privacy &amp; Visibility</div>
          <div class="edit-section-desc">Control what information is visible to different audiences. Sensitive fields can be masked while metrics remain public.</div>
        </div>
      </div>

      <div class="visibility-row">
        <div class="visibility-row-label">
          <strong>Public Profile Active</strong>
          Your profile appears in search results and can be found by clients.
        </div>
        <label class="toggle"><input type="checkbox" checked onchange="markUnsaved()"><span class="toggle-slider"></span></label>
      </div>
      <div class="visibility-row">
        <div class="visibility-row-label">
          <strong>Show Full Name</strong>
          Display full name publicly. If off, only first name is shown.
        </div>
        <label class="toggle"><input type="checkbox" checked onchange="markUnsaved()"><span class="toggle-slider"></span></label>
      </div>
      <div class="visibility-row">
        <div class="visibility-row-label">
          <strong>Show Earnings Metrics</strong>
          Display total value delivered ($740K) on your public profile.
        </div>
        <label class="toggle"><input type="checkbox" checked onchange="markUnsaved()"><span class="toggle-slider"></span></label>
      </div>
      <div class="visibility-row">
        <div class="visibility-row-label">
          <strong>Allow Direct Messages Before Invitation</strong>
          Clients can message you without first posting a project or sending an invite.
        </div>
        <label class="toggle"><input type="checkbox" onchange="markUnsaved()"><span class="toggle-slider"></span></label>
      </div>
      <!-- SAVE BUTTON AT BOTTOM OF LAST SECTION -->
      <div class="flex justify-between items-center mt-32">
        <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="document.getElementById('delete-modal').classList.remove('hidden')">Delete Account</button>
        <div style="display:flex;gap:10px;">
          <button class="btn btn-outline" onclick="discardChanges()">Discard Changes</button>
          <button class="btn btn-primary btn-lg" onclick="saveProfile()">Save All Changes</button>
        </div>
      </div>
    </div>

  </main>

</div>

<!-- ── SAVE SUCCESS TOAST ── -->
<div class="toast-stack" id="toast-stack"></div>

<!-- ── DELETE ACCOUNT MODAL ── -->
<div id="delete-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3 style="color:var(--rust);">Delete Account</h3>
      <button class="modal-close" onclick="document.getElementById('delete-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <p style="margin-bottom:16px;">This will permanently delete your profile, portfolio, credentials, and all associated data. Active contracts must be completed or cancelled first.</p>
      <div class="form-group">
        <label class="form-label">Type your email to confirm</label>
        <input type="email" class="form-control" placeholder="rania.khalil@example.com">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('delete-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-danger">Permanently Delete Account</button>
    </div>
  </div>
</div>

<!-- ── NAME CHANGE REQUEST MODAL ── -->
<div id="name-change-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Request Name Change</h3>
        <p class="text-sm text-muted mt-4">Enter your updated full name and upload a clear image of your government ID.</p>
      </div>
      <button class="modal-close" onclick="closeNameChangeModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">New Full Name</label>
        <input id="name-change-full-name" type="text" class="form-control" placeholder="Enter your legal full name">
      </div>
      <div class="form-group">
        <label class="form-label">Government ID Image</label>
        <div class="upload-zone file-dropzone" onclick="document.getElementById('name-change-id-input').click()">
          <div class="file-dropzone-icon">🪪</div>
          <div class="file-dropzone-label"><strong>Click to upload</strong> ID image</div>
          <div class="file-dropzone-hint">PNG / JPG / JPEG · Max 10MB</div>
          <input type="file" id="name-change-id-input" accept="image/png,image/jpeg,image/jpg" style="display:none;" onchange="handleNameChangeIdUpload(this)">
        </div>
        <div id="name-change-id-preview" style="margin-top:8px;"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeNameChangeModal()">Cancel</button>
      <button class="btn btn-primary" onclick="submitNameChangeRequest()">Submit Request</button>
    </div>
  </div>
</div>

<!-- ── ADD CREDENTIAL MODAL ── -->
<div id="add-cred-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Add New Credential</h3>
        <p class="text-sm text-muted mt-4">All credentials go through Nexus's verification workflow (2–5 business days).</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('add-cred-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Credential Type</label>
        <select id="new-cred-type-select" class="form-control" onchange="toggleOtherCredentialType(this)">
          <option value="">— Select type —</option>
          <option value="Doctoral Degree (PhD)">Doctoral Degree (PhD)</option>
          <option value="Master's Degree">Master's Degree</option>
          <option value="Bachelor's Degree">Bachelor's Degree</option>
          <option value="Professional Certification">Professional Certification</option>
          <option value="Award / Recognition">Award / Recognition</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div class="form-group" id="new-cred-type-other-group" style="display:none;">
        <label class="form-label">Specify Credential Type</label>
        <input id="new-cred-type-other" type="text" class="form-control" placeholder="Describe the credential type" oninput="markUnsaved()">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Credential Name</label>
          <input id="new-cred-name" type="text" class="form-control" placeholder="e.g. Google Professional ML Engineer">
        </div>
        <div class="form-group">
          <label class="form-label">Issuing Organization</label>
          <input id="new-cred-org" type="text" class="form-control" placeholder="e.g. Google Cloud">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Issue Date</label>
          <input id="new-cred-issue-date" type="month" class="form-control">
        </div>
        <div class="form-group">
          <label class="form-label">Expiry Date <span class="text-muted font-mono" style="font-weight:400;font-size:.7rem;">If applicable</span></label>
          <input id="new-cred-expiry-date" type="month" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Verification URL / Credential ID</label>
        <input id="new-cred-verification" type="text" class="form-control" placeholder="https://www.credential.net/… or certificate ID">
        <p class="form-hint">A direct verification link significantly speeds up the review process.</p>
      </div>
      <div class="form-group">
        <label class="form-label">Upload Certificate Document</label>
        <div class="upload-zone file-dropzone" id="new-cred-upload-zone" onclick="document.getElementById('new-cred-file').click()" ondragover="event.preventDefault(); this.classList.add('drag-over')" ondragleave="this.classList.remove('drag-over')" ondrop="handleCredentialDrop(event)">
          <div class="file-dropzone-icon">📄</div>
          <div class="file-dropzone-label"><strong>Click to upload</strong> or drag &amp; drop</div>
          <div class="file-dropzone-hint">PDF · Max 20MB</div>
          <input type="file" id="new-cred-file" accept=".pdf,.jpg,.png" style="display:none;" onchange="handleCredentialFileUpload(this)">
        </div>
        <div id="new-cred-file-preview" style="margin-top:12px;display:none;"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('add-cred-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="submitNewCredential()">Submit for Verification</button>
    </div>
  </div>
</div>

<!-- ── ADD PORTFOLIO ITEM MODAL ── -->
<div id="add-portfolio-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Add Portfolio Item</h3>
        <p class="text-sm text-muted mt-4">Enter a title and upload the file you want to showcase in your portfolio.</p>
      </div>
      <button class="modal-close" onclick="closeAddPortfolioModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Item Title</label>
        <input id="new-pf-title" type="text" class="form-control" placeholder="Project or paper title">
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea id="new-pf-description" class="form-control" rows="3" placeholder="Briefly describe what this work demonstrates…"></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Client Name</label>
        <input id="new-pf-client" type="text" class="form-control" placeholder="Optional — shown only if public">
      </div>
      <div class="form-group">
        <label class="form-label">Upload File</label>
        <div class="upload-zone file-dropzone" id="new-pf-upload-zone" onclick="document.getElementById('new-pf-file').click()" ondragover="event.preventDefault(); this.classList.add('drag-over')" ondragleave="this.classList.remove('drag-over')" ondrop="handlePortfolioDrop(event)">
          <div class="file-dropzone-icon">📁</div>
          <div class="file-dropzone-label"><strong>Click to upload</strong> or drag &amp; drop</div>
          <div class="file-dropzone-hint">Notebooks (.ipynb) · PDFs · ZIP · Max 100MB</div>
          <input type="file" id="new-pf-file" style="display:none;" onchange="handlePortfolioFileUpload(this)">
        </div>
        <div id="new-pf-file-preview" style="margin-top:12px;display:none;"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeAddPortfolioModal()">Cancel</button>
      <button class="btn btn-primary" onclick="submitNewPortfolioItem()">Add Item</button>
    </div>
  </div>
</div>

</body>
</html>
