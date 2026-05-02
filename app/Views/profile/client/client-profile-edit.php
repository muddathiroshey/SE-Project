<?php
// views/profile/client/client-profile-edit.php

$client      = $client      ?? [];
$kyc_docs    = $kyc_docs    ?? [];
$niche_prefs = $niche_prefs ?? [];
$keywords    = $keywords    ?? [];
$errors      = $errors      ?? [];

$saved = isset($_GET['saved']);

// Helper: check if a niche is selected
$hasNiche = fn(string $n): bool => in_array($n, $niche_prefs, true);

// Helper: selected option
$sel = fn($a, $b): string => $a === $b ? 'selected' : '';

$kycBadge = match($client['kyc_status'] ?? 'incomplete') {
    'verified'   => '<span class="badge badge-verified badge-dot">KYC Verified</span>',
    'pending'    => '<span class="badge badge-pending badge-dot">Under Review</span>',
    default      => '<span class="badge badge-danger badge-dot">Incomplete</span>',
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($client['org_name'] ?? 'My Profile') ?> — Client Profile · Nexus</title>
<link rel="stylesheet" href="/assets/style.css">
<link rel="stylesheet" href="/assets/client-profile-edit.css">
</head>
<body>

<!-- ══════════════════ TOPNAV ══════════════════ -->
<nav class="topnav">
  <div class="container" style="max-width:100%;padding:0 32px;">
    <a class="topnav-logo" href="/dashboard">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="/dashboard">Dashboard</a>
      <a href="/client/<?= htmlspecialchars($client['slug'] ?? '') ?>" target="_blank">Preview Public Profile ↗</a>
    </div>
    <div class="topnav-actions">
      <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge">
            <div class="avatar avatar-sm">
              <?= htmlspecialchars(mb_strtoupper(mb_substr($client['user_name'] ?? 'U', 0, 2))) ?>
            </div>
          </div>
          <span style="font-size:.875rem;font-weight:700;">
            <?= htmlspecialchars(explode(' ', $client['user_name'] ?? '')[0]) ?>
          </span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Client Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/profile/edit">My Profile</a>
          <a class="dropdown-item" href="/wallet">Wallet &amp; Escrow</a>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/logout" style="color:var(--rust);">Sign Out</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- ══════════════════ UNSAVED BANNER ══════════════════ -->
<div class="unsaved-banner" id="unsaved-banner">
  <span>You have unsaved changes.</span>
  <div style="display:flex;gap:8px;">
    <button class="btn btn-outline btn-sm" onclick="discardChanges()">Discard</button>
    <button class="btn btn-primary btn-sm" onclick="document.getElementById('profile-form').requestSubmit()">Save Now</button>
  </div>
</div>

<?php if ($saved): ?>
<div class="toast-stack" id="toast-stack" style="display:block;">
  <div class="toast success">
    <span class="toast-icon">✓</span>
    <div>
      <div class="toast-title">Saved</div>
      <div class="toast-body">Profile saved. Changes are live on your public profile.</div>
    </div>
  </div>
</div>
<script>setTimeout(() => { const s = document.getElementById('toast-stack'); if(s) s.innerHTML=''; }, 4000);</script>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="error-banner" style="background:#FBEAE7;border-left:3px solid var(--rust);padding:14px 24px;font-size:.875rem;color:var(--rust);">
  <ul style="margin:0;padding-left:18px;">
    <?php foreach ($errors as $e): ?>
      <li><?= htmlspecialchars($e) ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

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
    <a class="edit-nav-link" href="/client/<?= htmlspecialchars($client['slug'] ?? '') ?>" target="_blank">👁 Preview Profile</a>
  </aside>

  <!-- ── MAIN EDIT AREA ── -->
  <main class="edit-main">

    <form
      id="profile-form"
      method="POST"
      action="/profile/update"
      enctype="multipart/form-data"
      novalidate
    >
    <!-- hidden fields for multi-value JS-driven inputs -->
    <input type="hidden" name="niche_prefs" id="niche-prefs-input">
    <input type="hidden" name="keywords"    id="keywords-input">

    <!-- ════ SECTION 1: IDENTITY & CONTACT ════ -->
    <div class="edit-section" id="sec-identity">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 01</div>
          <div class="edit-section-heading">Identity &amp; Contact</div>
          <div class="edit-section-desc">Your personal contact information and primary account credentials.</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
          <?php if ($client['is_verified'] ?? false): ?>
            <span class="badge badge-verified badge-dot">Verified</span>
          <?php else: ?>
            <span class="badge badge-pending badge-dot">Unverified</span>
          <?php endif; ?>
        </div>
      </div>

      <!-- AVATAR -->
      <div class="avatar-upload-zone">
        <div class="avatar-upload-target" onclick="document.getElementById('avatar-input').click()">
          <?php if (!empty($client['avatar_path'])): ?>
            <img src="/<?= htmlspecialchars($client['avatar_path']) ?>" class="avatar avatar-xl" alt="Avatar">
          <?php else: ?>
            <div class="avatar avatar-xl">
              <?= htmlspecialchars(mb_strtoupper(mb_substr($client['user_name'] ?? 'U', 0, 2))) ?>
            </div>
          <?php endif; ?>
          <div class="avatar-overlay"><span style="font-size:1.2rem;">📷</span><span>Change</span></div>
          <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display:none;" onchange="markUnsaved()">
        </div>
        <div class="logo-upload-info">
          <strong>Contact Photo</strong>
          Shown alongside your name in all specialist conversations and bid notifications.
          <span>JPG or PNG · Min 200×200px · Max 5MB</span>
          <div style="display:flex;gap:8px;margin-top:10px;">
            <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('avatar-input').click()">Upload</button>
          </div>
        </div>
      </div>

      <!-- Full Name — locked -->
      <div class="form-group">
        <label class="form-label">
          Full Name
          <span class="text-muted font-mono" style="font-size:.7rem;text-transform:none;letter-spacing:0;font-weight:400;margin-left:8px;">Locked for security</span>
        </label>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <input
            id="full-name-field"
            type="text"
            class="form-control"
            value="<?= htmlspecialchars($client['user_name'] ?? '') ?>"
            readonly
            aria-readonly="true"
            style="flex:1;min-width:260px;background:var(--ivory-deep);cursor:not-allowed;"
          >
          <button type="button" class="btn btn-outline btn-sm" onclick="openNameChangeModal()">Request Name Change</button>
        </div>
        <p class="form-hint mt-4">Submit a request with supporting ID verification if your legal name has changed.</p>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Job Title</label>
          <input type="text" name="job_title" class="form-control"
            value="<?= htmlspecialchars($client['job_title'] ?? '') ?>"
            oninput="markUnsaved()">
          <p class="form-hint">Shown to specialists on your profile and in bid notifications.</p>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Country</label>
          <select id="country-select" name="country" class="form-control" onchange="syncTimezoneForCountry(true)">
            <option value="<?= htmlspecialchars($client['country'] ?? '') ?>" selected>
              <?= htmlspecialchars($client['country'] ?? 'Select country') ?>
            </option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Timezone</label>
          <select id="timezone-select" name="timezone" class="form-control" onchange="handleTimezoneChange()" disabled>
            <option value="<?= htmlspecialchars($client['timezone'] ?? '') ?>" selected>
              <?= htmlspecialchars($client['timezone'] ?? '') ?>
            </option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <input
            id="phone-number-field"
            type="tel"
            name="phone_number"
            class="form-control"
            value="<?= htmlspecialchars($client['phone_number'] ?? '') ?>"
            oninput="markUnsaved();validatePhoneNumber()"
            placeholder="e.g. +201001234567"
          >
          <p class="form-hint" id="phone-error" style="color:var(--rust);display:none;">Phone number must begin with + or 00.</p>
        </div>
      </div>
    </div>

    <!-- ════ SECTION 2: ORGANIZATION ════ -->
    <div class="edit-section" id="sec-org">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 02</div>
          <div class="edit-section-heading">Organization (Optional)</div>
          <div class="edit-section-desc">Your company details, registration, and branding.</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
          <?php if (($client['kyc_status'] ?? '') === 'verified'): ?>
            <span class="badge badge-verified badge-dot">Org Verified</span>
          <?php else: ?>
            <span class="badge badge-pending badge-dot">Unverified</span>
          <?php endif; ?>
        </div>
      </div>

      <!-- LOGO -->
      <div class="logo-upload-zone">
        <div class="logo-upload-target" onclick="document.getElementById('logo-input').click()">
          <?php if (!empty($client['logo_path'])): ?>
            <img src="/<?= htmlspecialchars($client['logo_path']) ?>" alt="Logo" style="width:64px;height:64px;object-fit:contain;">
          <?php else: ?>
            <div class="logo-letters">
              <?= htmlspecialchars(mb_strtoupper(mb_substr($client['org_name'] ?? 'O', 0, 2))) ?>
            </div>
          <?php endif; ?>
          <div class="logo-overlay"><span style="font-size:1.2rem;">🖼</span><span>Change Logo</span></div>
          <input type="file" id="logo-input" name="logo" accept="image/png,image/jpeg,image/svg+xml" style="display:none;" onchange="markUnsaved()">
        </div>
        <div class="logo-upload-info">
          <strong>Organization Logo</strong>
          Displayed on your public client profile and alongside project postings.
          <span>PNG, JPG, or SVG · Min 200×200px · Max 5MB</span>
          <div style="display:flex;gap:8px;margin-top:10px;">
            <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('logo-input').click()">Upload Logo</button>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Organization Name</label>
          <input type="text" name="org_name" class="form-control"
            value="<?= htmlspecialchars($client['org_name'] ?? '') ?>"
            oninput="markUnsaved()">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Organization Type</label>
          <select name="org_type" class="form-control" onchange="markUnsaved()">
            <?php foreach ([
              'Corporate — Private Sector',
              'Corporate — Public / Listed',
              'Government / Public Body',
              'NGO / Non-Profit',
              'SME / Startup',
              'Academic Institution',
              'Law Firm',
              'Individual / Sole Proprietor',
            ] as $type): ?>
              <option <?= $sel($client['org_type'] ?? '', $type) ?>>
                <?= htmlspecialchars($type) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Industry</label>
          <select id="industry-select" name="org_industry" class="form-control"
            onchange="toggleIndustryOtherField(this);markUnsaved()">
            <?php foreach ([
              'Financial Services & Banking',
              'Legal & Professional Services',
              'Technology & Software',
              'Healthcare & Pharma',
              'Energy & Utilities',
              'Retail & E-Commerce',
              'Manufacturing',
              'Logistics & Supply Chain',
              'Government & Public Sector',
              'Other',
            ] as $ind): ?>
              <option <?= $sel($client['org_industry'] ?? '', $ind) ?>>
                <?= htmlspecialchars($ind) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div id="industry-other-group" style="display:none;margin-top:10px;">
            <input id="industry-other-input" type="text" name="org_industry_other" class="form-control"
              placeholder="Enter your industry"
              value="<?= htmlspecialchars($client['org_industry_other'] ?? '') ?>"
              oninput="markUnsaved()">
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Registration Country</label>
          <select id="registration-country-select" name="org_reg_country" class="form-control" onchange="markUnsaved()">
            <option value="<?= htmlspecialchars($client['org_reg_country'] ?? '') ?>" selected>
              <?= htmlspecialchars($client['org_reg_country'] ?? 'Select country') ?>
            </option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Commercial Registration No.</label>
          <input type="text" name="org_reg_number" class="form-control"
            value="<?= htmlspecialchars($client['org_reg_number'] ?? '') ?>"
            oninput="markUnsaved()">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Organization Website</label>
          <input type="url" name="org_website" class="form-control"
            value="<?= htmlspecialchars($client['org_website'] ?? '') ?>"
            placeholder="https://"
            oninput="markUnsaved()">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Organization Bio</label>
        <textarea name="org_bio" class="form-control" rows="4"
          oninput="markUnsaved();countChars(this,500,'org-bio-counter')"
        ><?= htmlspecialchars($client['org_bio'] ?? '') ?></textarea>
        <div class="flex justify-between mt-4">
          <p class="form-hint">Shown to specialists on your public client profile.</p>
          <span class="char-counter" id="org-bio-counter">
            <?= mb_strlen($client['org_bio'] ?? '') ?> / 500
          </span>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Headquarters Address</label>
        <input type="text" name="org_address" class="form-control"
          value="<?= htmlspecialchars($client['org_address'] ?? '') ?>"
          oninput="markUnsaved()">
      </div>
    </div>

    <!-- ════ SECTION 3: ABOUT & NICHE NEEDS ════ -->
    <div class="edit-section" id="sec-about">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 03</div>
          <div class="edit-section-heading">About &amp; Niche Needs</div>
          <div class="edit-section-desc">Describe the kinds of projects you typically run.</div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">What We Typically Hire For</label>
        <textarea name="hiring_description" class="form-control" rows="4"
          oninput="markUnsaved();countChars(this,600,'hiring-for-counter')"
        ><?= htmlspecialchars($client['hiring_description'] ?? '') ?></textarea>
        <div class="flex justify-between mt-4">
          <p class="form-hint">Specialists read this before deciding to bid.</p>
          <span class="char-counter" id="hiring-for-counter">
            <?= mb_strlen($client['hiring_description'] ?? '') ?> / 600
          </span>
        </div>
      </div>

      <hr class="divider">
      <label class="form-label mb-12">
        Niche Disciplines We Engage
        <span class="text-muted font-mono" style="font-size:.7rem;text-transform:none;letter-spacing:0;font-weight:400;margin-left:6px;">Tick all that apply</span>
      </label>
      <div class="niche-pref-grid" id="niche-prefs">
        <?php
        $niches = [
          ['icon' => '🧠', 'name' => 'Data Science & ML'],
          ['icon' => '⚖️', 'name' => 'Legal Consulting'],
          ['icon' => '🌐', 'name' => 'Tech Translation'],
          ['icon' => '📈', 'name' => 'Financial Modelling'],
          ['icon' => '🔐', 'name' => 'Cybersecurity'],
          ['icon' => '🔬', 'name' => 'Biomedical Research'],
        ];
        foreach ($niches as $n):
          $selected = $hasNiche($n['name']) ? 'selected' : '';
        ?>
          <div class="niche-pref-card <?= $selected ?>" onclick="toggleNiche(this)">
            <div class="niche-pref-card-icon"><?= $n['icon'] ?></div>
            <div class="niche-pref-card-name"><?= htmlspecialchars($n['name']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="form-group" style="margin-top:20px;">
        <label class="form-label">Keywords Specialists Should Know About You</label>
        <div class="tag-input-wrap" id="kw-wrap" onclick="document.getElementById('kw-input').focus()">
          <?php foreach ($keywords as $kw): ?>
            <span class="tag-pill">
              <?= htmlspecialchars($kw) ?>
              <button type="button" class="tag-pill-remove" onclick="removeTag(this)">×</button>
            </span>
          <?php endforeach; ?>
          <input type="text" id="kw-input" class="tag-input-field" placeholder="Add keyword…"
            onkeydown="handleTagInput(event,'kw-wrap','kw-input')">
        </div>
        <p class="form-hint mt-4">Press Enter or comma to add.</p>
      </div>
    </div>

    <!-- ════ SECTION 4: KYC & VERIFICATION ════ -->
    <div class="edit-section" id="sec-kyc">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 04</div>
          <div class="edit-section-heading">KYC &amp; Verification</div>
          <div class="edit-section-desc">Verified client organizations receive a trust badge on their profile.</div>
        </div>
        <?= $kycBadge ?>
      </div>

      <?php if (($client['kyc_status'] ?? '') === 'verified'): ?>
        <div class="verify-band mb-20" style="background:#EBF3EA;border-color:#C5DBC2;">
          <span>🛡</span>
          <div style="font-size:.8125rem;">
            <strong>Identity verified</strong>
            <?php if (!empty($client['kyc_verified_at'])): ?>
              — confirmed <?= date('M j, Y', strtotime($client['kyc_verified_at'])) ?>.
            <?php endif; ?>
            <span style="color:var(--sage);font-weight:700;margin-left:6px;">✓ Complete</span>
          </div>
        </div>
      <?php endif; ?>

      <?php foreach ($kyc_docs as $doc): ?>
        <div class="kyc-doc-row">
          <div class="kyc-doc-icon">📋</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($doc['doc_title'] ?? $doc['doc_type']) ?></div>
            <div class="text-xs text-muted">
              <?= htmlspecialchars($doc['file_name']) ?> ·
              Uploaded <?= date('M j, Y', strtotime($doc['uploaded_at'])) ?>
            </div>
          </div>
          <?php if ($doc['review_status'] === 'approved'): ?>
            <span class="badge badge-verified" style="font-size:.625rem;">Verified</span>
          <?php elseif ($doc['review_status'] === 'rejected'): ?>
            <span class="badge badge-danger" style="font-size:.625rem;">Rejected</span>
          <?php else: ?>
            <span class="badge badge-pending" style="font-size:.625rem;">Under review</span>
          <?php endif; ?>
          <form method="POST" action="/profile/kyc/delete" style="display:inline;">
            <input type="hidden" name="doc_id" value="<?= (int) $doc['id'] ?>">
            <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--rust);">✕</button>
          </form>
        </div>
      <?php endforeach; ?>

      <div id="kyc-extra-files" style="margin-bottom:8px;"></div>
      <hr class="divider">
      <h4 style="font-size:.9rem;margin-bottom:8px;">Add Additional Documents</h4>
      <p class="form-hint mb-12">Optional documents that further establish trust — e.g. audited financials, ISO certifications.</p>
      <button type="button" class="btn btn-outline btn-sm" onclick="openAddDocumentModal()">+ Add Document</button>
    </div>

    <!-- ════ SECTION 5: BILLING & TAX ════ -->
    <div class="edit-section" id="sec-billing">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 05</div>
          <div class="edit-section-heading">Billing &amp; Tax</div>
          <div class="edit-section-desc">Manage invoicing preferences and tax registration details.</div>
        </div>
        <a href="/wallet" class="btn btn-outline btn-sm">View Full Wallet →</a>
      </div>

      <hr class="divider">
      <h4 style="font-size:.9rem;margin-bottom:14px;">Tax Information</h4>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tax Jurisdiction</label>
          <select id="tax-jurisdiction-select" name="tax_jurisdiction" class="form-control"
            onchange="updateVatRate();markUnsaved()">
            <?php foreach ([
              'USA — US Dollar (USD)',
              'Germany — Euro (EUR)',
              'United Kingdom — Pound Sterling (GBP)',
              'Egypt — Egyptian Pound (EGP)',
              'Saudi Arabia — Riyal (SAR)',
              'Kuwait — Kuwaiti Dinar (KWD)',
            ] as $jur): ?>
              <option <?= $sel($client['tax_jurisdiction'] ?? '', $jur) ?>>
                <?= htmlspecialchars($jur) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Applicable VAT Rate</label>
          <input id="vat-rate-input" type="text" class="form-control" readonly
            style="background:var(--ivory-deep);">
          <p class="form-hint">Auto-calculated from jurisdiction.</p>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">VAT Registration Number</label>
          <input type="text" name="vat_number" class="form-control"
            value="<?= htmlspecialchars($client['vat_number'] ?? '') ?>"
            oninput="markUnsaved()">
        </div>
        <div class="form-group">
          <label class="form-label">Tax ID / TIN</label>
          <input type="text" name="tax_id" class="form-control"
            value="<?= htmlspecialchars($client['tax_id'] ?? '') ?>"
            oninput="markUnsaved()">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Billing Address for Invoices</label>
        <textarea name="billing_address" class="form-control" rows="2"
          oninput="markUnsaved()"><?= htmlspecialchars($client['billing_address'] ?? '') ?></textarea>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);">
        <div>
          <div style="font-weight:700;font-size:.875rem;">Currency</div>
          <div class="text-xs text-muted">All invoices and receipts issued in</div>
        </div>
        <select name="currency" class="form-control" style="width:160px;" onchange="markUnsaved()">
          <?php foreach (['USD — US Dollar','EUR — Euro','GBP — Pound Sterling','EGP — Egyptian Pound','SAR — Saudi Riyal','KWD — Kuwaiti Dinar'] as $cur): ?>
            <option <?= $sel($client['currency'] ?? 'USD', explode(' ', $cur)[0]) ?>>
              <?= htmlspecialchars($cur) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!-- ════ SECTION 6: PRIVACY & VISIBILITY ════ -->
    <div class="edit-section" id="sec-privacy">
      <div class="edit-section-title">
        <div>
          <div class="edit-section-label">Section 06</div>
          <div class="edit-section-heading">Privacy &amp; Visibility</div>
          <div class="edit-section-desc">Control what specialists can see on your public client profile.</div>
        </div>
      </div>

      <div class="visibility-row">
        <div class="visibility-row-label"><strong>Public Client Profile Active</strong>Your profile is visible to verified specialists.</div>
        <label class="toggle">
          <input type="checkbox" name="profile_active" value="1"
            <?= ($client['profile_active'] ?? 1) ? 'checked' : '' ?>
            onchange="markUnsaved()">
          <span class="toggle-slider"></span>
        </label>
      </div>
      <div class="visibility-row">
        <div class="visibility-row-label"><strong>Show Project History Count</strong>Allow specialists to see how many projects you've completed.</div>
        <label class="toggle">
          <input type="checkbox" name="show_project_count" value="1"
            <?= ($client['show_project_count'] ?? 1) ? 'checked' : '' ?>
            onchange="markUnsaved()">
          <span class="toggle-slider"></span>
        </label>
      </div>
      <div class="visibility-row">
        <div class="visibility-row-label"><strong>Show Total Spend Band</strong>Display a spend tier indicator to reassure specialists.</div>
        <label class="toggle">
          <input type="checkbox" name="show_spend_band" value="1"
            <?= ($client['show_spend_band'] ?? 0) ? 'checked' : '' ?>
            onchange="markUnsaved()">
          <span class="toggle-slider"></span>
        </label>
      </div>
      <div class="visibility-row">
        <div class="visibility-row-label"><strong>Allow Specialists to Send Messages</strong>Verified specialists can message you without an open project.</div>
        <label class="toggle">
          <input type="checkbox" name="allow_messages" value="1"
            <?= ($client['allow_messages'] ?? 0) ? 'checked' : '' ?>
            onchange="markUnsaved()">
          <span class="toggle-slider"></span>
        </label>
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
        <button type="button" class="btn btn-ghost btn-sm" style="color:var(--rust);"
          onclick="document.getElementById('delete-modal').classList.remove('hidden')">
          Delete Account
        </button>
        <div style="display:flex;gap:10px;">
          <button type="button" class="btn btn-outline" onclick="discardChanges()">Discard Changes</button>
          <button type="submit" class="btn btn-primary btn-lg">Save All Changes</button>
        </div>
      </div>
    </div>

    </form><!-- end #profile-form -->
  </main>
</div>

<!-- TOAST -->
<div class="toast-stack" id="toast-stack"></div>

<!-- ── ADD DOCUMENT MODAL ── -->
<div id="add-document-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Add Document</h3>
        <p class="text-sm text-muted mt-4">Give the document a title and upload the supporting file.</p>
      </div>
      <button type="button" class="modal-close" onclick="closeAddDocumentModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Document Name</label>
        <input id="additional-document-name" type="text" class="form-control" placeholder="e.g. Annual Audit Report 2025">
      </div>
      <div class="form-group">
        <label class="form-label">Upload Document</label>
        <div class="upload-zone file-dropzone" id="additional-document-upload-zone"
          onclick="document.getElementById('additional-document-file').click()"
          ondragover="event.preventDefault();this.classList.add('drag-over')"
          ondragleave="this.classList.remove('drag-over')"
          ondrop="handleAdditionalDocumentDrop(event)">
          <div class="file-dropzone-icon">📄</div>
          <div class="file-dropzone-label"><strong>Click to upload</strong> or drag &amp; drop</div>
          <div class="file-dropzone-hint">PDF, JPG, JPEG, PNG, DOCX · Max 20MB</div>
          <input type="file" id="additional-document-file"
            accept=".pdf,.jpg,.jpeg,.png,.docx" style="display:none;"
            onchange="previewAdditionalDocumentFile(this)">
        </div>
        <div id="additional-document-file-preview" style="margin-top:12px;display:none;"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="closeAddDocumentModal()">Cancel</button>
      <button type="button" class="btn btn-primary" onclick="submitAdditionalDocument()">Upload Document</button>
    </div>
  </div>
</div>

<!-- ── NAME CHANGE MODAL ── -->
<div id="name-change-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Request Name Change</h3>
        <p class="text-sm text-muted mt-4">Enter your updated full name and upload a clear image of your government ID.</p>
      </div>
      <button type="button" class="modal-close" onclick="closeNameChangeModal()">✕</button>
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
          <div class="file-dropzone-hint">PNG / JPG · Max 10MB</div>
          <input type="file" id="name-change-id-input" accept="image/png,image/jpeg" style="display:none;" onchange="handleNameChangeIdUpload(this)">
        </div>
        <div id="name-change-id-preview" style="margin-top:8px;"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="closeNameChangeModal()">Cancel</button>
      <button type="button" class="btn btn-primary" onclick="submitNameChangeRequest()">Submit Request</button>
    </div>
  </div>
</div>

<!-- ── CHANGE PASSWORD MODAL ── -->
<div id="password-change-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Change Password</h3>
        <p class="text-sm text-muted mt-4">Enter your current password and choose a new one.</p>
      </div>
      <button type="button" class="modal-close" onclick="closeChangePasswordModal()">✕</button>
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
      <p class="form-hint" id="password-change-error" style="color:var(--rust);display:none;margin-top:0;">
        Passwords must match and be at least 8 characters long.
      </p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="closeChangePasswordModal()">Cancel</button>
      <button type="button" class="btn btn-primary" onclick="submitChangePasswordRequest()">Submit</button>
    </div>
  </div>
</div>

<!-- ── DELETE ACCOUNT MODAL ── -->
<div id="delete-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3 style="color:var(--rust);">Delete Account</h3>
      <button type="button" class="modal-close" onclick="document.getElementById('delete-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div style="background:#FBEAE7;border:1px solid #F0C4BC;border-radius:var(--radius-sm);padding:14px;margin-bottom:16px;font-size:.875rem;color:var(--rust);">
        All active projects must be completed or cancelled before deletion. Escrowed funds will be returned after a 30-day review period.
      </div>
      <div class="form-group">
        <label class="form-label">Type your organization name to confirm</label>
        <input type="text" class="form-control" placeholder="<?= htmlspecialchars($client['org_name'] ?? '') ?>">
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="document.getElementById('delete-modal').classList.add('hidden')">Cancel</button>
      <button type="button" class="btn btn-danger">Permanently Delete Account</button>
    </div>
  </div>
</div>

<script>
const TAX_VAT_RATES = {
  'USA — US Dollar (USD)':                '0% (No VAT)',
  'Germany — Euro (EUR)':                 '19% (Germany Standard)',
  'United Kingdom — Pound Sterling (GBP)':'20% (UK Standard)',
  'Egypt — Egyptian Pound (EGP)':         '14% (Egypt Standard)',
  'Saudi Arabia — Riyal (SAR)':           '15% (KSA Standard)',
  'Kuwait — Kuwaiti Dinar (KWD)':         '0% (No VAT)'
};

function updateVatRate() {
  const sel = document.getElementById('tax-jurisdiction-select');
  const inp = document.getElementById('vat-rate-input');
  if (sel && inp) inp.value = TAX_VAT_RATES[sel.value] || 'N/A';
}

window.addEventListener('DOMContentLoaded', () => {
  updateVatRate();
  populateCountrySelect();
  populateRegistrationCountrySelect();
  toggleIndustryOtherField(document.getElementById('industry-select'));
});

// Serialize multi-value fields before submit
document.getElementById('profile-form').addEventListener('submit', () => {
  const niches = [...document.querySelectorAll('#niche-prefs .niche-pref-card.selected')]
    .map(el => el.querySelector('.niche-pref-card-name').textContent.trim());
  const kws = [...document.querySelectorAll('#kw-wrap .tag-pill')]
    .map(el => el.childNodes[0].textContent.trim());
  document.getElementById('niche-prefs-input').value = niches.join(',');
  document.getElementById('keywords-input').value    = kws.join(',');
});

let unsaved = false;
function markUnsaved() {
  unsaved = true;
  document.getElementById('unsaved-banner').classList.add('visible');
}
function discardChanges() {
  unsaved = false;
  document.getElementById('unsaved-banner').classList.remove('visible');
  showToast('Changes discarded.', 'info');
}
window.addEventListener('beforeunload', e => { if (unsaved) { e.preventDefault(); e.returnValue = ''; } });

function toggleDD() { document.getElementById('user-dd').classList.toggle('hidden'); }
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});

function showToast(msg, type) {
  const s = document.getElementById('toast-stack');
  s.innerHTML = `<div class="toast ${type === 'info' ? '' : 'success'}">
    <span class="toast-icon">${type === 'info' ? 'ℹ' : '✓'}</span>
    <div><div class="toast-title">${type === 'info' ? 'Notice' : 'Saved'}</div>
    <div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML = '', 4000);
}

function setActive(el) {
  document.querySelectorAll('.edit-nav-link').forEach(a => a.classList.remove('active'));
  el.classList.add('active');
}

function countChars(el, max, id) {
  const n = el.value.length, c = document.getElementById(id);
  if (!c) return;
  c.textContent = `${n} / ${max}`;
  c.className = 'char-counter' + (n > max ? ' over' : n > max * .9 ? ' warn' : '');
}

function handleTagInput(e, wrapId, inputId) {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    const val = e.target.value.trim().replace(/,$/, '');
    if (!val) return;
    const pill = document.createElement('span');
    pill.className = 'tag-pill';
    pill.innerHTML = `${val} <button type="button" class="tag-pill-remove" onclick="removeTag(this)">×</button>`;
    e.target.parentNode.insertBefore(pill, e.target);
    e.target.value = '';
    markUnsaved();
  }
  if (e.key === 'Backspace' && e.target.value === '') {
    const pills = document.querySelectorAll(`#${wrapId} .tag-pill`);
    if (pills.length) { pills[pills.length - 1].remove(); markUnsaved(); }
  }
}
function removeTag(btn) { btn.parentNode.remove(); markUnsaved(); }
function toggleNiche(el) { el.classList.toggle('selected'); markUnsaved(); }

function validatePhoneNumber() {
  const field = document.getElementById('phone-number-field');
  const error = document.getElementById('phone-error');
  if (!field || !error) return;
  const value = field.value.trim();
  const valid = /^((\+)|(00))\d+$/.test(value);
  error.style.display = valid || value === '' ? 'none' : 'block';
  field.style.borderColor = (!valid && value) ? '#D32F2F' : '';
  field.style.boxShadow   = (!valid && value) ? '0 0 0 1px rgba(211,47,47,.25)' : '';
  return valid;
}

function toggleIndustryOtherField(sel) {
  const g = document.getElementById('industry-other-group');
  const i = document.getElementById('industry-other-input');
  if (!g || !i) return;
  const show = sel && sel.value === 'Other';
  g.style.display = show ? 'block' : 'none';
  if (!show) i.value = '';
}

// ── COUNTRY / TIMEZONE (same maps as original) ──
const COUNTRY_TIMEZONES = {
  'Algeria':['Africa/Algiers'],'Argentina':['America/Argentina/Buenos_Aires'],
  'Australia':['Australia/Sydney','Australia/Adelaide','Australia/Perth'],
  'Austria':['Europe/Vienna'],'Bahrain':['Asia/Bahrain'],'Bangladesh':['Asia/Dhaka'],
  'Belgium':['Europe/Brussels'],'Brazil':['America/Sao_Paulo','America/Manaus','America/Rio_Branco','America/Noronha'],
  'Bulgaria':['Europe/Sofia'],'Canada':['America/Toronto','America/Winnipeg','America/Edmonton','America/Vancouver','America/Halifax','America/St_Johns'],
  'Chile':['America/Santiago','Pacific/Easter'],'China':['Asia/Shanghai'],'Colombia':['America/Bogota'],
  'Croatia':['Europe/Zagreb'],'Czechia':['Europe/Prague'],'Denmark':['Europe/Copenhagen'],
  'Egypt':['Africa/Cairo'],'Estonia':['Europe/Tallinn'],'Ethiopia':['Africa/Addis_Ababa'],
  'Finland':['Europe/Helsinki'],'France':['Europe/Paris'],'Germany':['Europe/Berlin'],
  'Ghana':['Africa/Accra'],'Greece':['Europe/Athens'],'Hong Kong':['Asia/Hong_Kong'],
  'Hungary':['Europe/Budapest'],'India':['Asia/Kolkata'],'Indonesia':['Asia/Jakarta','Asia/Makassar','Asia/Jayapura'],
  'Ireland':['Europe/Dublin'],'Israel':['Asia/Jerusalem'],'Italy':['Europe/Rome'],
  'Japan':['Asia/Tokyo'],'Jordan':['Asia/Amman'],'Kazakhstan':['Asia/Almaty','Asia/Aqtobe'],
  'Kenya':['Africa/Nairobi'],'Kuwait':['Asia/Kuwait'],'Lebanon':['Asia/Beirut'],
  'Malaysia':['Asia/Kuala_Lumpur'],'Mexico':['America/Mexico_City','America/Cancun','America/Chihuahua','America/Tijuana'],
  'Morocco':['Africa/Casablanca'],'Netherlands':['Europe/Amsterdam'],'New Zealand':['Pacific/Auckland','Pacific/Chatham'],
  'Nigeria':['Africa/Lagos'],'Norway':['Europe/Oslo'],'Oman':['Asia/Muscat'],
  'Pakistan':['Asia/Karachi'],'Peru':['America/Lima'],'Philippines':['Asia/Manila'],
  'Poland':['Europe/Warsaw'],'Portugal':['Europe/Lisbon','Atlantic/Azores','Atlantic/Madeira'],
  'Qatar':['Asia/Qatar'],'Romania':['Europe/Bucharest'],
  'Russia':['Europe/Kaliningrad','Europe/Moscow','Europe/Samara','Asia/Yekaterinburg','Asia/Omsk','Asia/Krasnoyarsk','Asia/Irkutsk','Asia/Yakutsk','Asia/Vladivostok','Asia/Magadan','Asia/Kamchatka'],
  'Saudi Arabia':['Asia/Riyadh'],'Serbia':['Europe/Belgrade'],'Singapore':['Asia/Singapore'],
  'South Africa':['Africa/Johannesburg'],'South Korea':['Asia/Seoul'],'Spain':['Europe/Madrid','Atlantic/Canary'],
  'Sri Lanka':['Asia/Colombo'],'Sweden':['Europe/Stockholm'],'Switzerland':['Europe/Zurich'],
  'Taiwan':['Asia/Taipei'],'Thailand':['Asia/Bangkok'],'Tunisia':['Africa/Tunis'],
  'Turkey':['Europe/Istanbul'],'Ukraine':['Europe/Kyiv'],'United Arab Emirates':['Asia/Dubai'],
  'United Kingdom':['Europe/London'],
  'United States':['America/New_York','America/Chicago','America/Denver','America/Phoenix','America/Los_Angeles','America/Anchorage','Pacific/Honolulu'],
  'Uruguay':['America/Montevideo'],'Vietnam':['Asia/Ho_Chi_Minh']
};

const ALL_COUNTRIES = [
  'Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda','Argentina','Armenia','Aruba','Australia','Austria','Azerbaijan',
  'Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria','Burkina Faso','Burundi',
  'Cabo Verde','Cambodia','Cameroon','Canada','Central African Republic','Chad','Chile','China','Colombia','Comoros','Costa Rica','Côte d\'Ivoire','Croatia','Cuba','Cyprus','Czechia',
  'Democratic Republic of the Congo','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Eswatini','Ethiopia',
  'Fiji','Finland','France','Gabon','Gambia','Georgia','Germany','Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hungary',
  'Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Kosovo','Kuwait','Kyrgyzstan',
  'Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Mauritania','Mauritius','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Morocco','Mozambique','Myanmar',
  'Namibia','Nauru','Nepal','Netherlands','New Zealand','Nicaragua','Niger','Nigeria','North Korea','North Macedonia','Norway','Oman','Pakistan','Palau','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda',
  'Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Sweden','Switzerland','Syria',
  'Taiwan','Tajikistan','Tanzania','Thailand','Timor-Leste','Togo','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Yemen','Zambia','Zimbabwe'
];

function formatTimezoneLabel(zone) {
  try {
    const f = new Intl.DateTimeFormat('en-US', { timeZone: zone, timeZoneName: 'shortOffset' });
    const p = f.formatToParts(new Date()).find(x => x.type === 'timeZoneName');
    return p ? `${p.value} - ${zone}` : zone;
  } catch { return zone; }
}

function populateCountrySelect() {
  const sel = document.getElementById('country-select');
  if (!sel) return;
  const current = '<?= addslashes($client['country'] ?? 'Egypt') ?>';
  sel.innerHTML = '';
  Object.keys(COUNTRY_TIMEZONES).sort().forEach(c => {
    const o = document.createElement('option');
    o.value = c; o.textContent = c; o.selected = c === current;
    sel.appendChild(o);
  });
  syncTimezoneForCountry(false);
}

function populateRegistrationCountrySelect() {
  const sel = document.getElementById('registration-country-select');
  if (!sel) return;
  const current = '<?= addslashes($client['org_reg_country'] ?? 'Egypt') ?>';
  sel.innerHTML = '';
  ALL_COUNTRIES.slice().sort().forEach(c => {
    const o = document.createElement('option');
    o.value = c; o.textContent = c; o.selected = c === current;
    sel.appendChild(o);
  });
}

function syncTimezoneForCountry(shouldMark) {
  const cSel = document.getElementById('country-select');
  const tSel = document.getElementById('timezone-select');
  if (!cSel || !tSel) return;
  const zones = COUNTRY_TIMEZONES[cSel.value] || COUNTRY_TIMEZONES['Egypt'];
  const current = '<?= addslashes($client['timezone'] ?? '') ?>';
  tSel.innerHTML = '';
  zones.forEach(z => {
    const o = document.createElement('option');
    o.value = z; o.textContent = formatTimezoneLabel(z); o.selected = z === current;
    tSel.appendChild(o);
  });
  tSel.disabled = zones.length === 1;
  if (shouldMark) markUnsaved();
}

function handleTimezoneChange() { markUnsaved(); }

// ── MODALS ──
function openNameChangeModal() {
  const src = document.getElementById('full-name-field');
  const inp = document.getElementById('name-change-full-name');
  if (src && inp) inp.value = src.value;
  document.getElementById('name-change-modal').classList.remove('hidden');
}
function closeNameChangeModal() {
  document.getElementById('name-change-modal').classList.add('hidden');
  document.getElementById('name-change-full-name').value = '';
  document.getElementById('name-change-id-input').value  = '';
  document.getElementById('name-change-id-preview').innerHTML = '';
}
function handleNameChangeIdUpload(input) {
  const preview = document.getElementById('name-change-id-preview');
  if (!input.files?.[0]) return;
  const f = input.files[0];
  if (!f.type.startsWith('image/')) { showToast('Please upload an image file.', 'info'); input.value = ''; return; }
  const size = f.size > 1048576 ? (f.size / 1048576).toFixed(1) + ' MB' : (f.size / 1024).toFixed(0) + ' KB';
  preview.innerHTML = `<div class="uploaded-file-row"><div class="uploaded-file-icon">🪪</div><span>${f.name}</span><span>${size}</span></div>`;
}
function submitNameChangeRequest() {
  const name = document.getElementById('name-change-full-name').value.trim();
  const file = document.getElementById('name-change-id-input');
  if (name.length < 3) { showToast('Please enter your full legal name.', 'info'); return; }
  if (!file?.files?.[0]) { showToast('Please upload an image of your ID.', 'info'); return; }
  closeNameChangeModal();
  showToast('Name change request submitted. Verification takes 1–2 business days.');
}

function openChangePasswordModal() { document.getElementById('password-change-modal').classList.remove('hidden'); }
function closeChangePasswordModal() {
  document.getElementById('password-change-modal').classList.add('hidden');
  ['current-password-field','new-password-field','confirm-password-field'].forEach(id => {
    const el = document.getElementById(id); if (el) el.value = '';
  });
  document.getElementById('password-change-error').style.display = 'none';
}
function submitChangePasswordRequest() {
  const cur  = document.getElementById('current-password-field').value.trim();
  const next = document.getElementById('new-password-field').value.trim();
  const conf = document.getElementById('confirm-password-field').value.trim();
  const err  = document.getElementById('password-change-error');
  if (!cur || next.length < 8 || next !== conf) { err.style.display = 'block'; return; }
  err.style.display = 'none';
  closeChangePasswordModal();
  showToast('Password change submitted.');
}

function openAddDocumentModal()  { document.getElementById('add-document-modal').classList.remove('hidden'); }
function closeAddDocumentModal() {
  document.getElementById('add-document-modal').classList.add('hidden');
  document.getElementById('additional-document-name').value = '';
  document.getElementById('additional-document-file').value = '';
  const prev = document.getElementById('additional-document-file-preview');
  const zone = document.getElementById('additional-document-upload-zone');
  prev.style.display = 'none'; prev.innerHTML = '';
  zone.style.display = 'flex';
}
function previewAdditionalDocumentFile(input) {
  const prev = document.getElementById('additional-document-file-preview');
  const zone = document.getElementById('additional-document-upload-zone');
  if (!input.files?.[0]) return;
  const file = input.files[0];
  if (file.size > 20971520) { showToast('File must be 20MB or less.', 'info'); input.value = ''; return; }
  const size = file.size > 1048576 ? (file.size / 1048576).toFixed(1) + ' MB' : (file.size / 1024).toFixed(0) + ' KB';
  zone.style.display = 'none';
  prev.style.display = 'block';
  prev.innerHTML = `<div class="uploaded-file-row"><div class="uploaded-file-icon">📄</div><span style="flex:1;">${file.name}</span><span>${size}</span><button type="button" class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="removeAdditionalDocumentFile(this)">✕</button></div>`;
}
function removeAdditionalDocumentFile() {
  document.getElementById('additional-document-file').value = '';
  document.getElementById('additional-document-file-preview').style.display = 'none';
  document.getElementById('additional-document-upload-zone').style.display  = 'flex';
}
function handleAdditionalDocumentDrop(e) {
  e.preventDefault(); e.stopPropagation();
  e.currentTarget.classList.remove('drag-over');
  const files = e.dataTransfer?.files;
  if (!files?.length) return;
  const input = document.getElementById('additional-document-file');
  const dt = new DataTransfer(); dt.items.add(files[0]); input.files = dt.files;
  input.dispatchEvent(new Event('change', { bubbles: true }));
}
function submitAdditionalDocument() {
  const name = document.getElementById('additional-document-name').value.trim();
  const file = document.getElementById('additional-document-file');
  const target = document.getElementById('kyc-extra-files');
  if (!name) { showToast('Enter a document name before uploading.', 'info'); return; }
  if (!file?.files?.[0]) { showToast('Please choose a file to upload.', 'info'); return; }
  const f = file.files[0];
  const size = f.size > 1048576 ? (f.size / 1048576).toFixed(1) + ' MB' : (f.size / 1024).toFixed(0) + ' KB';
  const row = document.createElement('div');
  row.className = 'kyc-doc-row';
  row.innerHTML = `<div class="kyc-doc-icon">📄</div><div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">${name}</div><div class="text-xs text-muted">${f.name} · ${size}</div></div><span class="badge badge-pending" style="font-size:.625rem;">Under review</span><button type="button" class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.parentNode.remove();markUnsaved()">✕</button>`;
  target.appendChild(row);
  markUnsaved();
  closeAddDocumentModal();
  showToast('Document added successfully.');
}

// ── SCROLL SPY ──
const sections = ['sec-identity','sec-org','sec-about','sec-kyc','sec-billing','sec-privacy'];
const navLinks = document.querySelectorAll('.edit-nav-link[href^="#"]');
window.addEventListener('scroll', () => {
  let cur = sections[0];
  sections.forEach(id => { const el = document.getElementById(id); if (el && el.getBoundingClientRect().top < 130) cur = id; });
  navLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + cur));
});
</script>
</body>
</html>