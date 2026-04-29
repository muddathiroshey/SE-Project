<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/client-profile-edit.php
    Template: Client Profile — Self-Edit View
    Role:     client (authenticated)
    Bound to: /views/client/profile-edit.php
    Data:     $client, $org, $verificationStatus, $paymentMethods,
              $projectHistory, $trustedSpecialists, $blockedUsers
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- PHP: <title><?= htmlspecialchars($client['org_name']) ?> — Client Profile · Nexus</title> -->
<title>FinCorp Egypt — Client Profile · Nexus</title>
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/client-profile-edit.css">
</head>
<body>

<!-- ══════════════════ TOPNAV ══════════════════
     PHP: include 'partials/topnav.php';
     Pass: ['role'=>'client','active'=>'profile','user'=>$client]
-->
<nav class="topnav">
  <div class="container" style="max-width:100%;padding:0 32px;">
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="dashboard-client.html">Dashboard</a>
      <!-- PHP: <a href="/client/profile/<?= $client['slug'] ?>" target="_blank">Preview Public Profile ↗</a> -->
      <a href="client-profile-public.html" target="_blank">Preview Public Profile ↗</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔<span class="notif-count" style="position:absolute;top:2px;right:2px;">4</span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">AT</div></div>
          <!-- PHP: <span style="font-size:.875rem;font-weight:700;"><?= htmlspecialchars($client['first_name']) ?></span> -->
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

<!-- ══════════════════ 3-COLUMN SHELL ══════════════════ -->
<div class="edit-shell">

  <!-- ── LEFT SIDEBAR NAV ── -->
  <aside class="sidebar" style="padding-top:24px;">

    <div class="sidebar-label">Profile Sections</div>
    <a class="edit-nav-link active" href="#sec-identity" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a4 4 0 1 1 0 8A4 4 0 0 1 8 1zm0 9c-3.3 0-6 1.6-6 3v1h12v-1c0-1.4-2.7-3-6-3z"/></svg>
      Identity &amp; Contact
    </a>
    <a class="edit-nav-link" href="#sec-org" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="5" width="14" height="9" rx="1"/><path d="M5 5V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
      Organization
    </a>
    <a class="edit-nav-link" href="#sec-about" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M4 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 2v1h6V3H5zm0 3v1h6V6H5zm0 3v1h4V9H5z"/></svg>
      About &amp; Niche Needs
    </a>
    <a class="edit-nav-link" href="#sec-kyc" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1L2 4v4c0 3.3 2.5 6.4 6 7 3.5-.6 6-3.7 6-7V4L8 1z"/></svg>
      KYC &amp; Verification
    </a>
    <a class="edit-nav-link" href="#sec-billing" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm0 2v6h12V6H2zm9 1h2v2h-2V7z"/></svg>
      Billing &amp; Tax
    </a>
    <a class="edit-nav-link" href="#sec-privacy" onclick="setActive(this)">
      <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor"><circle cx="8" cy="8" r="3"/><path d="M8 1v2M8 13v2M1 8h2M13 8h2"/></svg>
      Privacy &amp; Visibility
    </a>

    <div class="sidebar-label" style="margin-top:16px;">Account</div>
    <a class="edit-nav-link" href="client-profile-public.html" target="_blank">👁 Preview Profile</a>
  </aside>

  <!-- ── MAIN EDIT AREA ── -->
  <main class="edit-main">

    <!-- ════ SECTION 1: IDENTITY & CONTACT ════ -->
    <div class="edit-section" id="sec-identity">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 01</div>
          <div class="edit-section-heading">Identity &amp; Contact</div>
          <!-- PHP: shown as "Last updated <?= date('M j, Y', $client['updated_at']) ?>" -->
          <div class="edit-section-desc">Your personal contact information and primary account credentials. Your name and photo represent you in all specialist-facing communications.</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
          <span class="badge badge-verified badge-dot">Verified</span>
          <span id="identity-unverified-badge" class="badge badge-pending badge-dot" style="display:none;">Unverified</span>
        </div>
      </div>

      <!-- PERSONAL PHOTO -->
      <div class="avatar-upload-zone">
        <div class="avatar-upload-target" onclick="document.getElementById('avatar-input').click()">
          <!-- PHP: <div class="avatar avatar-xl"><?= $client['initials'] ?></div> -->
          <div class="avatar avatar-xl">AT</div>
          <div class="avatar-overlay"><span style="font-size:1.2rem;">📷</span><span>Change</span></div>
          <input type="file" id="avatar-input" accept="image/*" style="display:none;" onchange="markUnsaved()">
        </div>
        <div class="logo-upload-info">
          <strong>Contact Photo</strong>
          Shown alongside your name in all specialist conversations and bid notifications.
          <span>JPG or PNG · Min 200×200px · Max 5MB</span>
          <div style="display:flex;gap:8px;margin-top:10px;">
            <button class="btn btn-outline btn-sm" onclick="document.getElementById('avatar-input').click()">Upload</button>
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
          <input id="full-name-field" type="text" class="form-control" value="Amira Tawfik" readonly aria-readonly="true" style="flex:1;min-width:260px;background:var(--ivory-deep);cursor:not-allowed;">
          <button type="button" class="btn btn-outline btn-sm" onclick="openNameChangeModal()">Request Name Change</button>
        </div>
        <p class="form-hint mt-4">Submit a request with supporting ID verification if your legal name has changed.</p>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Job Title</label>
          <input type="text" class="form-control" value="Head of Analytics" oninput="markUnsaved()">
          <p class="form-hint">Shown to specialists on your profile and in bid notifications.</p>
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
          <label class="form-label">Phone Number</label>
          <input id="phone-number-field" type="tel" class="form-control" value="+201001234567" oninput="markUnsaved();validatePhoneNumber()" placeholder="e.g. +201001234567 or 00201001234567">
          <p class="form-hint" id="phone-error" style="color:var(--rust);display:none;">Phone number must begin with a plus sign (+) or 00.</p>
        </div>
      </div>

    <!-- ════ SECTION 2: ORGANIZATION ════ -->
    <div class="edit-section" id="sec-org">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 02</div>
          <div class="edit-section-heading">Organization (Optional)</div>
          <div class="edit-section-desc">Your company details, registration, and branding. Verified organizations receive a trust badge visible to all specialists.</div>
        </div>
        <!-- PHP: <?php if($org['verified']): ?><span class="badge badge-verified badge-dot">Org Verified</span><?php endif; ?> -->
        <div style="display:flex;gap:8px;align-items:center;">
          <span class="badge badge-verified badge-dot">Org Verified</span>
          <span id="org-unverified-badge" class="badge badge-pending badge-dot" style="display:none;">Unverified</span>
        </div>
      </div>

      <!-- LOGO UPLOAD -->
      <div class="logo-upload-zone">
        <div class="logo-upload-target" onclick="document.getElementById('logo-input').click()">
          <!-- PHP: if($org['logo']): <img src="<?= $org['logo_url'] ?>"> else: show initials -->
          <div class="logo-letters">FC</div>
          <div class="logo-overlay"><span style="font-size:1.2rem;">🖼</span><span>Change Logo</span></div>
          <input type="file" id="logo-input" accept="image/png,image/jpeg,image/svg+xml" style="display:none;" onchange="markUnsaved()">
        </div>
        <div class="logo-upload-info">
          <strong>Organization Logo</strong>
          Displayed on your public client profile and alongside project postings.
          <span>PNG, JPG, or SVG · Min 200×200px · Transparent background preferred · Max 5MB</span>
          <div style="display:flex;gap:8px;margin-top:10px;">
            <button class="btn btn-outline btn-sm" onclick="document.getElementById('logo-input').click()">Upload Logo</button>
            <button class="btn btn-ghost btn-sm" style="color:var(--rust);">Remove</button>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Organization Name</label>
          <input type="text" class="form-control" value="FinCorp Egypt" oninput="markUnsaved()">
        </div>
     
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Organization Type</label>
          <select class="form-control" onchange="markUnsaved()">
            <option selected>Corporate — Private Sector</option>
            <option>Corporate — Public / Listed</option>
            <option>Government / Public Body</option>
            <option>NGO / Non-Profit</option>
            <option>SME / Startup</option>
            <option>Academic Institution</option>
            <option>Law Firm</option>
            <option>Individual / Sole Proprietor</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Industry</label>
          <select id="industry-select" class="form-control" onchange="toggleIndustryOtherField(this);markUnsaved()">
            <option selected>Financial Services &amp; Banking</option>
            <option>Legal &amp; Professional Services</option>
            <option>Technology &amp; Software</option>
            <option>Healthcare &amp; Pharma</option>
            <option>Energy &amp; Utilities</option>
            <option>Retail &amp; E-Commerce</option>
            <option>Manufacturing</option>
            <option>Logistics &amp; Supply Chain</option>
            <option>Government &amp; Public Sector</option>
            <option>Other</option>
          </select>
          <div id="industry-other-group" style="display:none;margin-top:10px;">
            <input id="industry-other-input" type="text" class="form-control" placeholder="Enter your industry" oninput="markUnsaved()">
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Registration Country</label>
          <select id="registration-country-select" class="form-control" onchange="markUnsaved()"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Commercial Registration No.</label>
          <input type="text" class="form-control" value="EGY-CR-48821-FC" oninput="markUnsaved()">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Organization Website</label>
          <input type="url" class="form-control" value="https://fincorp.eg" placeholder="https://" oninput="markUnsaved()">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Organization Bio</label>
        <textarea class="form-control" rows="4" oninput="markUnsaved();countChars(this,500,'org-bio-counter')">FinCorp Egypt is a mid-market financial services company providing corporate banking, investment advisory, and digital payment infrastructure across North Africa and the GCC. We engage specialized consultants for data science, legal, and research projects that require high credential standards and structured delivery.</textarea>
        <div class="flex justify-between mt-4">
          <p class="form-hint">Shown to specialists on your public client profile.</p>
          <span class="char-counter" id="org-bio-counter">342 / 500</span>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Headquarters Address</label>
        <input type="text" class="form-control" value="12 Hassan Sabry St, Zamalek, Cairo 11211, Egypt" oninput="markUnsaved()">
      </div>
    </div>

    <!-- ════ SECTION 3: ABOUT & NICHE NEEDS ════ -->
    <div class="edit-section" id="sec-about">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 03</div>
          <div class="edit-section-heading">About &amp; Niche Needs</div>
          <div class="edit-section-desc">Describe the kinds of projects you typically run. This helps Nexus surface the right specialists and improves your bid quality.</div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">What We Typically Hire For</label>
        <textarea class="form-control" rows="4" oninput="markUnsaved();countChars(this,600,'hiring-for-counter')">We primarily engage specialists for high-stakes data science projects (predictive modelling, ML pipeline deployment) and cross-jurisdictional legal consulting related to our North African and GCC expansion. All engagements involve regulated deliverables where credential verification and milestone accountability are mandatory.</textarea>
        <div class="flex justify-between mt-4">
          <p class="form-hint">Specialists read this before deciding to bid. Be specific about standards and expectations.</p>
          <span class="char-counter" id="hiring-for-counter">352 / 600</span>
        </div>
      </div>

      <hr class="divider">
      <label class="form-label mb-12">Niche Disciplines We Engage <span class="text-muted font-mono" style="font-size:.7rem;text-transform:none;letter-spacing:0;font-weight:400;margin-left:6px;">Tick all that apply</span></label>
      <div class="niche-pref-grid" id="niche-prefs">
        <div class="niche-pref-card selected" onclick="toggleNiche(this)"><div class="niche-pref-card-icon">🧠</div><div class="niche-pref-card-name">Data Science &amp; ML</div></div>
        <div class="niche-pref-card selected" onclick="toggleNiche(this)"><div class="niche-pref-card-icon">⚖️</div><div class="niche-pref-card-name">Legal Consulting</div></div>
        <div class="niche-pref-card" onclick="toggleNiche(this)"><div class="niche-pref-card-icon">🌐</div><div class="niche-pref-card-name">Tech Translation</div></div>
        <div class="niche-pref-card selected" onclick="toggleNiche(this)"><div class="niche-pref-card-icon">📈</div><div class="niche-pref-card-name">Financial Modelling</div></div>
        <div class="niche-pref-card" onclick="toggleNiche(this)"><div class="niche-pref-card-icon">🔐</div><div class="niche-pref-card-name">Cybersecurity</div></div>
        <div class="niche-pref-card" onclick="toggleNiche(this)"><div class="niche-pref-card-icon">🔬</div><div class="niche-pref-card-name">Biomedical Research</div></div>
      </div>



      <div class="form-group">
        <label class="form-label">Keywords Specialists Should Know About You</label>
        <div class="tag-input-wrap" id="kw-wrap" onclick="document.getElementById('kw-input').focus()">
          <span class="tag-pill">Regulated Industry <button class="tag-pill-remove" onclick="removeTag(this)">×</button></span>
          <span class="tag-pill">GCC Expansion <button class="tag-pill-remove" onclick="removeTag(this)">×</button></span>
          <span class="tag-pill">Arabic Markets <button class="tag-pill-remove" onclick="removeTag(this)">×</button></span>
          <span class="tag-pill">Explainability Required <button class="tag-pill-remove" onclick="removeTag(this)">×</button></span>
          <span class="tag-pill">NDA-Sensitive <button class="tag-pill-remove" onclick="removeTag(this)">×</button></span>
          <input type="text" id="kw-input" class="tag-input-field" placeholder="Add keyword…" onkeydown="handleTagInput(event,'kw-wrap','kw-input')">
        </div>
        <p class="form-hint mt-4">Press Enter or comma to add. These improve your match score with specialists in search.</p>
      </div>
    </div>

    <!-- ════ SECTION 4: KYC & VERIFICATION ════ -->
    <div class="edit-section" id="sec-kyc">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 04</div>
          <div class="edit-section-heading">KYC &amp; Verification</div>
          <div class="edit-section-desc">Verified client organizations receive a trust badge on their profile, increasing specialist response rates by up to 40%.</div>
        </div>
        <!-- PHP: badge driven by $org['kyc_status'] — 'verified' | 'pending' | 'incomplete' -->
        <span class="badge badge-verified badge-dot">KYC Verified</span>
      </div>

      <div class="verify-band mb-20" style="background:#EBF3EA;border-color:#C5DBC2;">
        <span>🛡</span>
        <div style="font-size:.8125rem;">
          <strong>Identity verified</strong> — confirmed Apr 3, 2025.
          <span style="color:var(--sage);font-weight:700;margin-left:6px;">✓ Complete</span>
        </div>
      </div>

      <div class="kyc-doc-row">
        <div class="kyc-doc-icon">📋</div>
        <div style="flex:1;">
          <div style="font-weight:700;font-size:.875rem;">Commercial Registration Certificate</div>
          <div class="text-xs text-muted">EGY-CR-48821-FC · Uploaded Apr 1, 2025 · Verified Apr 3</div>
        </div>
        <span class="badge badge-verified" style="font-size:.625rem;">Verified</span>
        <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.parentNode.remove();markUnsaved()">✕</button>
      </div>

      <div class="kyc-doc-row">
        <div class="kyc-doc-icon">🏦</div>
        <div style="flex:1;">
          <div style="font-weight:700;font-size:.875rem;">VAT Registration Certificate</div>
          <div class="text-xs text-muted">EG-VAT-28841-C · Uploaded Apr 1, 2025 · Verified Apr 3</div>
        </div>
        <span class="badge badge-verified" style="font-size:.625rem;">Verified</span>
        <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.parentNode.remove();markUnsaved()">✕</button>
      </div>

      <div class="kyc-doc-row">
        <div class="kyc-doc-icon">🪪</div>
        <div style="flex:1;">
          <div style="font-weight:700;font-size:.875rem;">Authorized Signatory ID</div>
          <div class="text-xs text-muted">Amira Tawfik · National ID · Uploaded Apr 1, 2025 · Verified Apr 3</div>
        </div>
        <span class="badge badge-verified" style="font-size:.625rem;">Verified</span>
      </div>
      <div id="kyc-extra-files" style="margin-bottom:8px;"></div>
      <hr class="divider">
      <h4 style="font-size:.9rem;margin-bottom:8px;">Add Additional Documents</h4>
      <p class="form-hint mb-12">Optional documents that further establish trust with specialist — e.g. audited financials, ISO certifications, or regulatory licenses.</p>
      <button type="button" class="btn btn-outline btn-sm" onclick="openAddDocumentModal()">+ Add Document</button>
    </div>
  
    <!-- ════ SECTION 5: BILLING & TAX ════ -->
    <div class="edit-section" id="sec-billing">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 05</div>
          <div class="edit-section-heading">Billing &amp; Tax</div>
          <div class="edit-section-desc">Manage payment methods, invoicing preferences, and tax registration details used for VAT calculation.</div>
        </div>
        <a href="escrow-wallet.html" class="btn btn-outline btn-sm">View Full Wallet →</a>
      </div>

      <h4 style="font-size:.9rem;margin-bottom:14px;">Payment Methods</h4>
      <!-- PHP: foreach($paymentMethods as $pm): -->
      <div id="payment-methods-list">
        <div class="payment-card default" data-payment-id="pm-001">
          <div class="card-chip">MC</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;">Mastercard ···· 4821</div>
            <div class="text-xs text-muted">Expires 09/27</div>
          </div>
          <span class="badge badge-verified" style="font-size:.625rem;">Default</span>
          <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="removePaymentMethod(this)">Remove</button>
        </div>
        <div class="payment-card" data-payment-id="pm-002">
          <div class="card-chip visa">VISA</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;">Visa ···· 2201</div>
            <div class="text-xs text-muted">Expires 03/26</div>
          </div>
          <button class="btn btn-ghost btn-sm">Set Default</button>
          <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="removePaymentMethod(this)">Remove</button>
        </div>
      </div>
      <div id="payment-methods-empty-message" class="text-muted" style="display:none;margin-top:8px;padding:16px;border:1px dashed var(--border);border-radius:var(--radius-md);background:var(--ivory-deep);">
        You currently have no payment methods. Add one now to pay for projects and invoices.
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
        <textarea class="form-control" rows="2" oninput="markUnsaved()">FinCorp Egypt, 12 Hassan Sabry St, Zamalek, Cairo 11211, Egypt</textarea>
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

    <!-- ════ SECTION 6: PRIVACY & VISIBILITY ════ -->
    <div class="edit-section" id="sec-privacy">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 06</div>
          <div class="edit-section-heading">Privacy &amp; Visibility</div>
          <div class="edit-section-desc">Control what information specialists can see on your public client profile and across the platform.</div>
        </div>
      </div>

      <div class="visibility-row">
        <div class="visibility-row-label"><strong>Public Client Profile Active</strong>Your profile is visible to verified specialists. Disable to go dark — projects will still be postable but your profile page returns a 404.</div>
        <label class="toggle"><input type="checkbox" checked onchange="markUnsaved()"><span class="toggle-slider"></span></label>
      </div>
      <div class="visibility-row">
        <div class="visibility-row-label"><strong>Show Project History Count</strong>Allow specialists to see how many projects you've completed on Nexus.</div>
        <label class="toggle"><input type="checkbox" checked onchange="markUnsaved()"><span class="toggle-slider"></span></label>
      </div>
      <div class="visibility-row">
        <div class="visibility-row-label"><strong>Show Total Spend Band</strong>Display a spend tier indicator (e.g. "$10K–$50K total") to reassure specialists of your seriousness as a client.</div>
        <label class="toggle"><input type="checkbox" onchange="markUnsaved()"><span class="toggle-slider"></span></label>
      </div>
      <div class="visibility-row">
        <div class="visibility-row-label"><strong>Allow Specialists to Send Messages</strong>Verified specialists who match your stated niche needs can send you a message without a project open.</div>
        <label class="toggle"><input type="checkbox" onchange="markUnsaved()"><span class="toggle-slider"></span></label>
      </div>
      <hr class="divider">
      <h4 style="font-size:.9rem;margin-bottom:16px;">Password &amp; Security</h4>
      <div class="form-row">
        <div class="form-group" style="width:100%;">
          <p class="form-hint" style="margin-bottom:12px;">Use the button below to update your password securely.</p>
          <button type="button" class="btn btn-outline" onclick="openChangePasswordModal()">Change Password</button>
        </div>
      </div>

      <!-- SAVE ROW -->
      <div class="flex justify-between items-center mt-32">
        <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="document.getElementById('delete-modal').classList.remove('hidden')">Delete Account</button>
        <div style="display:flex;gap:10px;">
          <button class="btn btn-outline" onclick="discardChanges()">Discard Changes</button>
          <button class="btn btn-primary btn-lg" onclick="saveProfile()">Save All Changes</button>
        </div>
      </div>
    </div>

  </main>
</div><!-- end .edit-shell -->

<!-- TOAST -->
<div class="toast-stack" id="toast-stack"></div>



<!-- ── ADD CARD MODAL ── -->
<div id="add-card-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div><h3>Add Payment Method</h3><p class="text-sm text-muted mt-4">Card details are handled via secure PCI-compliant tokenization.</p></div>
      <button class="modal-close" onclick="document.getElementById('add-card-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group"><label class="form-label">Card Number</label><input id="card-number-input" type="text" class="form-control" placeholder="•••• •••• •••• ••••" maxlength="19" oninput="formatCardNumber(this);detectCardType()"></div>
      <div id="card-type-display" style="font-size:.75rem;color:var(--ink-muted);margin-top:-8px;margin-bottom:12px;"></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Expiry Date</label><input id="expiry-date-input" type="text" class="form-control" placeholder="MM / YY" maxlength="7" oninput="formatExpiryDate(this)"></div>
        <div class="form-group"><label class="form-label">CVV</label><input id="cvv-input" type="text" class="form-control" placeholder="•••" maxlength="4" oninput="this.value=this.value.replace(/[^0-9]/g,'')"></div>
      </div>
      <div class="form-group"><label class="form-label">Cardholder Name</label><input id="cardholder-name-input" type="text" class="form-control" placeholder="As printed on the card"></div>
      <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
        <input type="checkbox" id="set-default-card" style="accent-color:var(--gold);">
        <label for="set-default-card" style="font-size:.875rem;color:var(--ink-mid);">Set as default payment method</label>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeAddCardModal()">Cancel</button>
      <button class="btn btn-primary" onclick="submitAddCard()">Add Card</button>
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

<!-- ── CHANGE PASSWORD MODAL ── -->
<div id="password-change-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Change Password</h3>
        <p class="text-sm text-muted mt-4">Enter your current password and choose a new secure password.</p>
      </div>
      <button class="modal-close" onclick="closeChangePasswordModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Current Password</label>
        <input id="current-password-field" type="password" class="form-control" placeholder="Enter current password">
      </div>
      <div class="form-group">
        <label class="form-label">New Password</label>
        <input id="new-password-field" type="password" class="form-control" placeholder="Min. 8 characters">
      </div>
      <div class="form-group">
        <label class="form-label">Confirm New Password</label>
        <input id="confirm-password-field" type="password" class="form-control" placeholder="Repeat new password">
      </div>
      <p class="form-hint" id="password-change-error" style="color:var(--rust);display:none;margin-top:0;">Passwords must match and be at least 8 characters long.</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeChangePasswordModal()">Cancel</button>
      <button class="btn btn-primary" onclick="submitChangePasswordRequest()">Submit</button>
    </div>
  </div>
</div>

<!-- ── DELETE ACCOUNT MODAL ── -->
<div id="delete-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3 style="color:var(--rust);">Delete Account</h3>
      <button class="modal-close" onclick="document.getElementById('delete-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div style="background:#FBEAE7;border:1px solid #F0C4BC;border-radius:var(--radius-sm);padding:14px;margin-bottom:16px;font-size:.875rem;color:var(--rust);">All active projects must be completed or cancelled before deletion. Escrowed funds will be returned after a 30-day review period.</div>
      <div class="form-group"><label class="form-label">Type your organization name to confirm</label><input type="text" class="form-control" placeholder="FinCorp Egypt"></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('delete-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-danger">Permanently Delete Account</button>
    </div>
  </div>
</div>

<!-- ── ADD DOCUMENT MODAL ── -->
<div id="add-document-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Add Document</h3>
        <p class="text-sm text-muted mt-4">Give the document a title and upload the supporting file.</p>
      </div>
      <button class="modal-close" onclick="closeAddDocumentModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Document Name</label>
        <input id="additional-document-name" type="text" class="form-control" placeholder="e.g. Annual Audit Report 2025">
      </div>
      <div class="form-group">
        <label class="form-label">Upload Document</label>
        <div class="upload-zone file-dropzone" id="additional-document-upload-zone" onclick="document.getElementById('additional-document-file').click()" ondragover="event.preventDefault(); this.classList.add('drag-over')" ondragleave="this.classList.remove('drag-over')" ondrop="handleAdditionalDocumentDrop(event)">
          <div class="file-dropzone-icon">📄</div>
          <div class="file-dropzone-label"><strong>Click to upload</strong> or drag &amp; drop</div>
          <div class="file-dropzone-hint">PDF, JPG, JPEG, PNG, DOCX · Max 20MB</div>
          <input type="file" id="additional-document-file" accept=".pdf,.jpg,.jpeg,.png,.docx" style="display:none;" onchange="previewAdditionalDocumentFile(this)">
        </div>
        <div id="additional-document-file-preview" style="margin-top:12px;display:none;"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeAddDocumentModal()">Cancel</button>
      <button class="btn btn-primary" onclick="submitAdditionalDocument()">Upload Document</button>
    </div>
  </div>
</div>

<script>
/* ── TAX RATE MAPPING ── */
const TAX_VAT_RATES = {
  'USA — US Dollar (USD)': '0% (No VAT)',
  'Germany — Euro (EUR)': '19% (Germany Standard)',
  'United Kingdom — Pound Sterling (GBP)': '20% (UK Standard)',
  'Egypt — Egyptian Pound (EGP)': '14% (Egypt Standard)',
  'Saudi Arabia — Riyal (SAR)': '15% (KSA Standard)',
  'Kuwait — Kuwaiti Dinar (KWD)': '0% (No VAT)'
};

function updateVatRate() {
  const select = document.getElementById('tax-jurisdiction-select');
  const vatInput = document.getElementById('vat-rate-input');
  if (!select || !vatInput) return;
  vatInput.value = TAX_VAT_RATES[select.value] || 'N/A';
}

window.addEventListener('DOMContentLoaded', () => {
  updateVatRate();
  updatePaymentMethodsEmptyState();
});

/* ── UNSAVED ── */
let unsaved = false;
function markUnsaved() { unsaved=true; document.getElementById('unsaved-banner').classList.add('visible'); }
function discardChanges() { unsaved=false; document.getElementById('unsaved-banner').classList.remove('visible'); showToast('Changes discarded.','info'); }
function saveProfile() { unsaved=false; document.getElementById('unsaved-banner').classList.remove('visible'); showToast('Profile saved. Changes are live on your public profile.'); }
window.addEventListener('beforeunload', e => { if(unsaved){e.preventDefault();e.returnValue='';} });

function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}

document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});

/* ── TOAST ── */
function showToast(msg, type) {
  const s = document.getElementById('toast-stack');
  s.innerHTML = `<div class="toast ${type==='info'?'':'success'}"><span class="toast-icon">${type==='info'?'ℹ':'✓'}</span><div><div class="toast-title">${type==='info'?'Notice':'Saved'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(()=>s.innerHTML='',4000);
}

function openNameChangeModal() {
  const modal = document.getElementById('name-change-modal');
  const sourceField = document.getElementById('full-name-field');
  const input = document.getElementById('name-change-full-name');
  if (sourceField && input) input.value = sourceField.value;
  if (modal) modal.classList.remove('hidden');
}

function closeNameChangeModal() {
  const modal = document.getElementById('name-change-modal');
  const input = document.getElementById('name-change-full-name');
  const fileInput = document.getElementById('name-change-id-input');
  const preview = document.getElementById('name-change-id-preview');
  if (modal) modal.classList.add('hidden');
  if (input) input.value = '';
  if (fileInput) fileInput.value = '';
  if (preview) preview.innerHTML = '';
}

function handleNameChangeIdUpload(input) {
  const preview = document.getElementById('name-change-id-preview');
  if (!preview || !input.files || !input.files[0]) return;

  const f = input.files[0];
  if (!f.type.startsWith('image/')) {
    showToast('Please upload an image file for your ID.', 'info');
    input.value = '';
    preview.innerHTML = '';
    return;
  }

  const size = f.size > 1048576 ? (f.size / 1048576).toFixed(1) + ' MB' : (f.size / 1024).toFixed(0) + ' KB';
  preview.innerHTML = `<div class="uploaded-file-row"><div class="uploaded-file-icon">🪪</div><span class="uploaded-file-name">${f.name}</span><span class="uploaded-file-size">${size}</span></div>`;
}

function submitNameChangeRequest() {
  const fullNameInput = document.getElementById('name-change-full-name');
  const fileInput = document.getElementById('name-change-id-input');
  const name = fullNameInput ? fullNameInput.value.trim() : '';

  if (name.length < 3) {
    showToast('Please enter your full legal name.', 'info');
    if (fullNameInput) fullNameInput.focus();
    return;
  }

  if (!fileInput || !fileInput.files || !fileInput.files[0]) {
    showToast('Please upload an image of your ID.', 'info');
    return;
  }

  closeNameChangeModal();
  showToast('Name change request submitted. Verification is usually completed in 1–2 business days.');
}

function openChangePasswordModal() {
  const modal = document.getElementById('password-change-modal');
  if (modal) modal.classList.remove('hidden');
}

function closeChangePasswordModal() {
  const modal = document.getElementById('password-change-modal');
  const current = document.getElementById('current-password-field');
  const next = document.getElementById('new-password-field');
  const confirm = document.getElementById('confirm-password-field');
  const error = document.getElementById('password-change-error');
  if (modal) modal.classList.add('hidden');
  if (current) current.value = '';
  if (next) next.value = '';
  if (confirm) confirm.value = '';
  if (error) error.style.display = 'none';
}

function submitChangePasswordRequest() {
  const current = document.getElementById('current-password-field');
  const next = document.getElementById('new-password-field');
  const confirm = document.getElementById('confirm-password-field');
  const error = document.getElementById('password-change-error');
  if (!current || !next || !confirm || !error) return;

  const currentValue = current.value.trim();
  const nextValue = next.value.trim();
  const confirmValue = confirm.value.trim();
  const valid = currentValue && nextValue.length >= 8 && nextValue === confirmValue;

  if (!valid) {
    error.style.display = 'block';
    return;
  }

  error.style.display = 'none';
  closeChangePasswordModal();
  showToast('Password change submitted. Your account will be updated after verification.');
}

function formatCardNumber(input) {
  let val = input.value.replace(/\s/g, '');
  val = val.replace(/[^0-9]/g, '');
  let formatted = '';
  for (let i = 0; i < val.length; i += 4) {
    if (i > 0) formatted += ' ';
    formatted += val.substring(i, i + 4);
  }
  input.value = formatted;
}

function formatExpiryDate(input) {
  let val = input.value.replace(/\D/g, '');
  if (val.length >= 2) {
    val = val.substring(0, 2) + ' / ' + val.substring(2, 4);
  }
  input.value = val;
}

function detectCardType() {
  const cardNumber = document.getElementById('card-number-input');
  const typeDisplay = document.getElementById('card-type-display');
  if (!cardNumber || !typeDisplay) return;
  
  const num = cardNumber.value.replace(/\s/g, '');
  let type = '';
  
  if (/^4/.test(num)) {
    type = 'Visa';
  } else if (/^5[1-5]/.test(num)) {
    type = 'Mastercard';
  }
  
  typeDisplay.textContent = type ? `Detected: ${type}` : '';
}

function closeAddCardModal() {
  const modal = document.getElementById('add-card-modal');
  const cardNum = document.getElementById('card-number-input');
  const expiry = document.getElementById('expiry-date-input');
  const cvv = document.getElementById('cvv-input');
  const holder = document.getElementById('cardholder-name-input');
  const typeDisplay = document.getElementById('card-type-display');
  const checkbox = document.getElementById('set-default-card');
  
  if (modal) modal.classList.add('hidden');
  if (cardNum) cardNum.value = '';
  if (expiry) expiry.value = '';
  if (cvv) cvv.value = '';
  if (holder) holder.value = '';
  if (typeDisplay) typeDisplay.textContent = '';
  if (checkbox) checkbox.checked = false;
}

function submitAddCard() {
  const cardNum = document.getElementById('card-number-input');
  const expiry = document.getElementById('expiry-date-input');
  const cvv = document.getElementById('cvv-input');
  const holder = document.getElementById('cardholder-name-input');
  const checkbox = document.getElementById('set-default-card');
  
  if (!cardNum || !expiry || !cvv || !holder) return;
  
  const num = cardNum.value.replace(/\s/g, '').trim();
  const exp = expiry.value.trim();
  const cvc = cvv.value.trim();
  const name = holder.value.trim();
  
  if (!num || num.length < 13) {
    showToast('Please enter a valid card number.', 'info');
    return;
  }
  
  if (!exp || !/^\d{2} \/ \d{2}$/.test(exp)) {
    showToast('Please enter expiry date in MM / YY format.', 'info');
    return;
  }
  
  if (!cvc || cvc.length < 3) {
    showToast('Please enter a valid CVV.', 'info');
    return;
  }
  
  if (!name) {
    showToast('Please enter cardholder name.', 'info');
    return;
  }
  
  let cardType = '';
  if (/^4/.test(num)) {
    cardType = 'visa';
  } else if (/^5[1-5]/.test(num)) {
    cardType = 'mastercard';
  } else {
    showToast('Card type not supported. Please use Visa or Mastercard.', 'info');
    return;
  }
  
  const lastFour = num.slice(-4);
  const isDefault = checkbox.checked;
  
  if (isDefault) {
    document.querySelectorAll('#payment-methods-list .payment-card.default').forEach(card => card.classList.remove('default'));
    document.querySelectorAll('#payment-methods-list .badge-verified').forEach(badge => badge.remove());
  }
  
  const cardHtml = `<div class="payment-card${isDefault ? ' default' : ''}" data-payment-id="">
    <div class="card-chip${cardType === 'visa' ? ' visa' : ''}">${cardType === 'visa' ? 'VISA' : 'MC'}</div>
    <div style="flex:1;">
      <div style="font-weight:700;font-size:.875rem;">${cardType === 'visa' ? 'Visa' : 'Mastercard'} ···· ${lastFour}</div>
      <div class="text-xs text-muted">Expires ${exp}</div>
    </div>
    ${isDefault ? '<span class="badge badge-verified" style="font-size:.625rem;">Default</span>' : '<button class="btn btn-ghost btn-sm">Set Default</button>'}
    <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="removePaymentMethod(this)">Remove</button>
  </div>`;
  
  const paymentList = document.getElementById('payment-methods-list');
  if (paymentList) {
    const div = document.createElement('div');
    div.innerHTML = cardHtml;
    paymentList.appendChild(div.firstElementChild);
    updatePaymentMethodsEmptyState();
  }
  
  closeAddCardModal();
  showToast('Card added successfully.');
}

/* ── NAV ACTIVE ── */
function setActive(el) { document.querySelectorAll('.edit-nav-link').forEach(a=>a.classList.remove('active')); el.classList.add('active'); }

/* ── CHAR COUNTER ── */
function countChars(el,max,id) {
  const n=el.value.length, c=document.getElementById(id);
  if(!c)return;
  c.textContent=`${n} / ${max}`;
  c.className='char-counter'+(n>max?' over':n>max*.9?' warn':'');
}

/* ── TAG INPUT ── */
function handleTagInput(e, wrapId, inputId) {
  if(e.key==='Enter'||e.key===','){
    e.preventDefault();
    const val=e.target.value.trim().replace(/,$/,'');
    if(!val)return;
    const pill=document.createElement('span');
    pill.className='tag-pill';
    pill.innerHTML=`${val} <button class="tag-pill-remove" onclick="removeTag(this)">×</button>`;
    e.target.parentNode.insertBefore(pill,e.target);
    e.target.value='';
    markUnsaved();
  }
  if(e.key==='Backspace'&&e.target.value===''){
    const pills=document.querySelectorAll(`#${wrapId} .tag-pill`);
    if(pills.length){pills[pills.length-1].remove();markUnsaved();}
  }
}
function removeTag(btn){btn.parentNode.remove();markUnsaved();}

/* ── NICHE TOGGLE ── */
function toggleNiche(el){el.classList.toggle('selected');markUnsaved();}

/* ── FILE UPLOAD ── */
function handleFileUpload(input,targetId){
  const target=document.getElementById(targetId);
  if(!target)return;
  Array.from(input.files).forEach(f=>{
    const size=f.size>1048576?(f.size/1048576).toFixed(1)+' MB':(f.size/1024).toFixed(0)+' KB';
    const row=document.createElement('div');
    row.className='uploaded-file-row';
    row.innerHTML=`<div class="uploaded-file-icon">📄</div><span style="flex:1;font-size:.875rem;font-weight:600;">${f.name}</span><span style="font-size:.75rem;font-family:var(--font-mono);color:var(--ink-muted);">${size}</span><span class="badge badge-pending" style="font-size:.625rem;">Staged</span><button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.parentNode.remove();markUnsaved()">✕</button>`;
    target.appendChild(row);
    markUnsaved();
  });
}

function openAddDocumentModal(){
  document.getElementById('add-document-modal').classList.remove('hidden');
}

function closeAddDocumentModal(){
  const modal=document.getElementById('add-document-modal');
  const nameInput=document.getElementById('additional-document-name');
  const fileInput=document.getElementById('additional-document-file');
  const preview=document.getElementById('additional-document-file-preview');
  const uploadZone=document.getElementById('additional-document-upload-zone');
  if(modal) modal.classList.add('hidden');
  if(nameInput) nameInput.value='';
  if(fileInput) fileInput.value='';
  if(preview){ preview.style.display='none'; preview.innerHTML=''; }
  if(uploadZone) uploadZone.style.display='flex';
}

function previewAdditionalDocumentFile(input){
  const preview=document.getElementById('additional-document-file-preview');
  const uploadZone=document.getElementById('additional-document-upload-zone');
  if(!preview||!uploadZone||!input.files||!input.files[0])return;
  const file=input.files[0];
  if(file.size>20971520){ showToast('File must be 20MB or less.','info'); input.value=''; return; }
  const size=file.size>1048576?(file.size/1048576).toFixed(1)+' MB':(file.size/1024).toFixed(0)+' KB';
  uploadZone.style.display='none';
  preview.style.display='block';
  preview.innerHTML=`<div class="uploaded-file-row"><div class="uploaded-file-icon">📄</div><span style="flex:1;font-size:.875rem;font-weight:600;">${file.name}</span><span style="font-size:.75rem;font-family:var(--font-mono);color:var(--ink-muted);">${size}</span><button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="removeAdditionalDocumentFile(this)">✕</button></div>`;
}

function removeAdditionalDocumentFile(button){
  const preview=document.getElementById('additional-document-file-preview');
  const uploadZone=document.getElementById('additional-document-upload-zone');
  const fileInput=document.getElementById('additional-document-file');
  if(preview) { preview.style.display='none'; preview.innerHTML=''; }
  if(uploadZone) uploadZone.style.display='flex';
  if(fileInput) fileInput.value='';
}

function handleAdditionalDocumentDrop(event){
  event.preventDefault();
  event.stopPropagation();
  const zone=event.currentTarget;
  if(zone) zone.classList.remove('drag-over');
  const files=event.dataTransfer?.files;
  if(!files||!files.length) return;
  const input=document.getElementById('additional-document-file');
  if(!input) return;
  const dt=new DataTransfer();
  dt.items.add(files[0]);
  input.files=dt.files;
  input.dispatchEvent(new Event('change',{bubbles:true}));
}

function submitAdditionalDocument(){
  const nameInput=document.getElementById('additional-document-name');
  const fileInput=document.getElementById('additional-document-file');
  const target=document.getElementById('kyc-extra-files');
  if(!nameInput||!fileInput||!target) return;
  const name=nameInput.value.trim();
  if(!name){ showToast('Enter a document name before uploading.','info'); return; }
  if(!fileInput.files||!fileInput.files[0]){ showToast('Please choose a PDF file to upload.','info'); return; }
  const file=fileInput.files[0];
  const size=file.size>1048576?(file.size/1048576).toFixed(1)+' MB':(file.size/1024).toFixed(0)+' KB';
  const row=document.createElement('div');
  row.className='kyc-doc-row';
  row.innerHTML=`<div class="kyc-doc-icon">📄</div><div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">${name}</div><div class="text-xs text-muted">${file.name} · ${size}</div></div><span class="badge badge-pending" style="font-size:.625rem;">Under review</span><button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.parentNode.remove();markUnsaved()">✕</button>`;
  target.appendChild(row);
  markUnsaved();
  closeAddDocumentModal();
  showToast('Document added successfully.');
}

function removePaymentMethod(button){
  const card = button.closest('.payment-card');
  if (!card) return;
  const paymentId = card.dataset.paymentId || null;
  // TODO: send paymentId to backend and delete the payment method from the database.
  card.remove();
  updatePaymentMethodsEmptyState();
  markUnsaved();
}

function updatePaymentMethodsEmptyState(){
  const paymentList = document.getElementById('payment-methods-list');
  const emptyMessage = document.getElementById('payment-methods-empty-message');
  if(!paymentList||!emptyMessage) return;
  emptyMessage.style.display = paymentList.querySelector('.payment-card') ? 'none' : 'block';
}

const COUNTRY_TIMEZONES = {
  'Algeria': ['Africa/Algiers'],
  'Argentina': ['America/Argentina/Buenos_Aires'],
  'Australia': ['Australia/Sydney', 'Australia/Adelaide', 'Australia/Perth'],
  'Austria': ['Europe/Vienna'],
  'Bahrain': ['Asia/Bahrain'],
  'Bangladesh': ['Asia/Dhaka'],
  'Belgium': ['Europe/Brussels'],
  'Brazil': ['America/Sao_Paulo', 'America/Manaus', 'America/Rio_Branco', 'America/Noronha'],
  'Bulgaria': ['Europe/Sofia'],
  'Canada': ['America/Toronto', 'America/Winnipeg', 'America/Edmonton', 'America/Vancouver', 'America/Halifax', 'America/St_Johns'],
  'Chile': ['America/Santiago', 'Pacific/Easter'],
  'China': ['Asia/Shanghai'],
  'Colombia': ['America/Bogota'],
  'Croatia': ['Europe/Zagreb'],
  'Czechia': ['Europe/Prague'],
  'Denmark': ['Europe/Copenhagen'],
  'Egypt': ['Africa/Cairo'],
  'Estonia': ['Europe/Tallinn'],
  'Ethiopia': ['Africa/Addis_Ababa'],
  'Finland': ['Europe/Helsinki'],
  'France': ['Europe/Paris'],
  'Germany': ['Europe/Berlin'],
  'Ghana': ['Africa/Accra'],
  'Greece': ['Europe/Athens'],
  'Hong Kong': ['Asia/Hong_Kong'],
  'Hungary': ['Europe/Budapest'],
  'India': ['Asia/Kolkata'],
  'Indonesia': ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura'],
  'Ireland': ['Europe/Dublin'],
  'Israel': ['Asia/Jerusalem'],
  'Italy': ['Europe/Rome'],
  'Japan': ['Asia/Tokyo'],
  'Jordan': ['Asia/Amman'],
  'Kazakhstan': ['Asia/Almaty', 'Asia/Aqtobe'],
  'Kenya': ['Africa/Nairobi'],
  'Kuwait': ['Asia/Kuwait'],
  'Lebanon': ['Asia/Beirut'],
  'Malaysia': ['Asia/Kuala_Lumpur'],
  'Mexico': ['America/Mexico_City', 'America/Cancun', 'America/Chihuahua', 'America/Tijuana'],
  'Morocco': ['Africa/Casablanca'],
  'Netherlands': ['Europe/Amsterdam'],
  'New Zealand': ['Pacific/Auckland', 'Pacific/Chatham'],
  'Nigeria': ['Africa/Lagos'],
  'Norway': ['Europe/Oslo'],
  'Oman': ['Asia/Muscat'],
  'Pakistan': ['Asia/Karachi'],
  'Peru': ['America/Lima'],
  'Philippines': ['Asia/Manila'],
  'Poland': ['Europe/Warsaw'],
  'Portugal': ['Europe/Lisbon', 'Atlantic/Azores', 'Atlantic/Madeira'],
  'Qatar': ['Asia/Qatar'],
  'Romania': ['Europe/Bucharest'],
  'Russia': ['Europe/Kaliningrad', 'Europe/Moscow', 'Europe/Samara', 'Asia/Yekaterinburg', 'Asia/Omsk', 'Asia/Krasnoyarsk', 'Asia/Irkutsk', 'Asia/Yakutsk', 'Asia/Vladivostok', 'Asia/Magadan', 'Asia/Kamchatka'],
  'Saudi Arabia': ['Asia/Riyadh'],
  'Serbia': ['Europe/Belgrade'],
  'Singapore': ['Asia/Singapore'],
  'South Africa': ['Africa/Johannesburg'],
  'South Korea': ['Asia/Seoul'],
  'Spain': ['Europe/Madrid', 'Atlantic/Canary'],
  'Sri Lanka': ['Asia/Colombo'],
  'Sweden': ['Europe/Stockholm'],
  'Switzerland': ['Europe/Zurich'],
  'Taiwan': ['Asia/Taipei'],
  'Thailand': ['Asia/Bangkok'],
  'Tunisia': ['Africa/Tunis'],
  'Turkey': ['Europe/Istanbul'],
  'Ukraine': ['Europe/Kyiv'],
  'United Arab Emirates': ['Asia/Dubai'],
  'United Kingdom': ['Europe/London'],
  'United States': ['America/New_York', 'America/Chicago', 'America/Denver', 'America/Phoenix', 'America/Los_Angeles', 'America/Anchorage', 'Pacific/Honolulu'],
  'Uruguay': ['America/Montevideo'],
  'Vietnam': ['Asia/Ho_Chi_Minh']
};

const ALL_COUNTRIES = [
  'Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda','Argentina','Armenia','Aruba','Australia','Austria','Azerbaijan',
  'Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria','Burkina Faso','Burundi',
  'Cabo Verde','Cambodia','Cameroon','Canada','Central African Republic','Chad','Chile','China','Colombia','Comoros','Costa Rica','Côte d’Ivoire','Croatia','Cuba','Cyprus','Czechia',
  'Democratic Republic of the Congo','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Eswatini','Ethiopia',
  'Fiji','Finland','France','Gabon','Gambia','Georgia','Germany','Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hungary',
  'Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Kosovo','Kuwait','Kyrgyzstan',
  'Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Mauritania','Mauritius','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Morocco','Mozambique','Myanmar',
  'Namibia','Nauru','Nepal','Netherlands','New Zealand','Nicaragua','Niger','Nigeria','North Korea','North Macedonia','Norway','Oman','Pakistan','Palau','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda',
  'Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Sweden','Switzerland','Syria',
  'Taiwan','Tajikistan','Tanzania','Thailand','Timor-Leste','Togo','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Yemen','Zambia','Zimbabwe'
];

function formatTimezoneLabel(zone){
  try{
    const formatter=new Intl.DateTimeFormat('en-US',{timeZone:zone,timeZoneName:'shortOffset'});
    const offsetPart=formatter.formatToParts(new Date()).find(part=>part.type==='timeZoneName');
    const offset=offsetPart?offsetPart.value:'';
    return offset?`${offset} - ${zone}`:zone;
  }catch(e){return zone;}
}

function populateCountrySelect(){
  const countrySelect=document.getElementById('country-select');
  if(!countrySelect)return;
  const current=COUNTRY_TIMEZONES[countrySelect.value]?countrySelect.value:'Egypt';
  countrySelect.innerHTML='';
  Object.keys(COUNTRY_TIMEZONES).sort().forEach(country=>{
    const option=document.createElement('option');
    option.value=country;
    option.textContent=country;
    option.selected=country===current;
    countrySelect.appendChild(option);
  });
  syncTimezoneForCountry(false);
}

function populateRegistrationCountrySelect(){
  const regSelect=document.getElementById('registration-country-select');
  if(!regSelect)return;
  const current=regSelect.value || 'Egypt';
  regSelect.innerHTML='';
  ALL_COUNTRIES.slice().sort().forEach(country=>{
    const option=document.createElement('option');
    option.value=country;
    option.textContent=country;
    option.selected=country===current;
    regSelect.appendChild(option);
  });
}

function syncTimezoneForCountry(shouldMark){
  const countrySelect=document.getElementById('country-select');
  const timezoneSelect=document.getElementById('timezone-select');
  if(!countrySelect||!timezoneSelect)return;
  const zones=COUNTRY_TIMEZONES[countrySelect.value]||COUNTRY_TIMEZONES.Egypt;
  timezoneSelect.innerHTML='';
  zones.forEach(zone=>{const option=document.createElement('option');option.value=zone;option.textContent=formatTimezoneLabel(zone);timezoneSelect.appendChild(option);});
  timezoneSelect.disabled=zones.length===1;
  timezoneSelect.title=zones.length===1?'Timezone is set by country':'';
  if(shouldMark)markUnsaved();
}

function handleTimezoneChange(){markUnsaved();}

function toggleIndustryOtherField(selectEl){
  const group = document.getElementById('industry-other-group');
  const input = document.getElementById('industry-other-input');
  if(!group || !input) return;
  const show = selectEl && selectEl.value === 'Other';
  group.style.display = show ? 'block' : 'none';
  if(!show) input.value = '';
}

function validatePhoneNumber() {
  const field = document.getElementById('phone-number-field');
  const error = document.getElementById('phone-error');
  if (!field || !error) return;
  const value = field.value.trim();
  const valid = /^((\+)|(00))\d+$/.test(value);
  error.style.display = valid || value === '' ? 'none' : 'block';
  if (!valid && value !== '') {
    field.style.borderColor = '#D32F2F';
    field.style.boxShadow = '0 0 0 1px rgba(211, 47, 47, 0.25)';
  } else {
    field.style.borderColor = '';
    field.style.boxShadow = '';
  }
  return valid;
}

populateCountrySelect();
populateRegistrationCountrySelect();
toggleIndustryOtherField(document.getElementById('industry-select'));
const sections=['sec-identity','sec-org','sec-about','sec-kyc','sec-team','sec-preferences','sec-billing','sec-privacy','sec-notifications'];
const navLinks=document.querySelectorAll('.edit-nav-link[href^="#"]');
window.addEventListener('scroll',()=>{
  let cur=sections[0];
  sections.forEach(id=>{const el=document.getElementById(id);if(el&&el.getBoundingClientRect().top<130)cur=id;});
  navLinks.forEach(l=>l.classList.toggle('active',l.getAttribute('href')==='#'+cur));
});
</script>
</body>
</html>
