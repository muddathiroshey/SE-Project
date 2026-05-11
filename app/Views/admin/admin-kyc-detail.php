<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KYC Review — Dr. Rania Khalil — Nexus Admin</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin-kyc-detail.css">
</head>

<body>

  <!-- ══════════ TOPNAV ══════════ -->
  <nav class="topnav" style="background:var(--ink);border-bottom:1px solid rgba(247,244,239,.1);">
    <div class="container" style="max-width:100%;padding:0 32px;">
      <a class="topnav-logo" href="admin-dashboard.html" style="color:var(--ivory);">Nexus<span
          style="color:var(--gold);">.</span></a>
      <div class="topnav-links"><a href="admin-dashboard.html" style="color:rgba(247,244,239,.6);">Dashboard</a></div>
      <div class="topnav-actions">
        <div class="flex items-center gap-8">
          <div class="avatar avatar-sm"
            style="background:var(--gold);color:var(--ink);font-size:.75rem;font-weight:700;"><?php echo strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? ''), 0, 2)) ?: 'ME'; ?></div>
          <span style="font-size:.875rem;font-weight:700;color:var(--ivory);"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Me'); ?></span>
          <span class="role-badge rb-super" style="font-size:.6rem;"><?php echo htmlspecialchars(($_SESSION['role'] ?? 'Account') . ' Account'); ?></span>
        </div>
      </div>
    </div>
  </nav>

  <div class="admin-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="admin-sidebar">
      <div class="admin-sidebar-section">Overview</div>
      <a class="admin-sidebar-link" href="admin-dashboard.html">📊 Health Dashboard</a>
      <div class="admin-sidebar-section">Marketplace</div>
      <a class="admin-sidebar-link" href="admin-team.html">👤 Users</a>
      <div class="admin-sidebar-section">Disputes</div>
      <a class="admin-sidebar-link" href="open-disputes.html">⚖️ Active Disputes <span class="notif-count"
          style="margin-left:auto;background:var(--rust);">4</span></a>
      <div class="admin-sidebar-section">Verifications</div>
      <a class="admin-sidebar-link active" href="admin-kyc.html">🛡 KYC Queue</a>
      <div class="admin-sidebar-section">Sanctions</div>
      <a class="admin-sidebar-link" href="sanctions.html">⚠️ User Sanctions</a>
      <div class="admin-sidebar-section">Support</div>
      <a class="admin-sidebar-link" href="admin-support.html">💬 Chat Support</a>
    </aside>

    <!-- ── MAIN ── -->
    <main class="admin-main">

      <!-- BACK + BREADCRUMB -->
      <div style="margin-bottom:24px;">
        <a href="admin-kyc.html"
          style="font-size:.8125rem;color:var(--gold);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">←
          Back to KYC Queue</a>
        <div class="breadcrumb"
          style="font-family:var(--font-mono);font-size:.72rem;color:var(--ink-muted);margin-top:8px;">
          Admin Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Verifications <span
            style="margin:0 6px;color:var(--ink-faint);">›</span> KYC Queue <span
            style="margin:0 6px;color:var(--ink-faint);">›</span> <strong>Dr. Rania Khalil</strong>
        </div>
      </div>

      <!-- ══════════ USER PROFILE CARD ══════════ -->
      <!-- PHP: $user = User::with('kycDocuments')->findOrFail($user_id) -->
      <div class="profile-card">
        <div class="flex items-center gap-16">
          <div class="avatar" style="width:56px;height:56px;font-size:1.1rem;flex-shrink:0;">RK</div>
          <div style="flex:1;">
            <h2 style="font-family:var(--font-display);font-size:1.4rem;font-weight:600;margin-bottom:2px;">Dr. Rania
              Khalil</h2>
            <div class="flex items-center gap-10" style="flex-wrap:wrap;">
              <span class="text-sm text-muted font-mono">rania@nexus.io</span>
              <span
                style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:2px;font-size:.6875rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;font-family:var(--font-body);background:#EBF3EA;color:var(--sage);border:1px solid #C5DBC2;">Specialist</span>
              <span class="status-chip pending">⏳ Pending Verification</span>
            </div>
          </div>
          <!-- PHP: Link to user's full profile page -->
          <button class="btn btn-ghost btn-sm" style="font-size:.75rem;">View Full Profile →</button>
        </div>

        <!-- PHP: Populate from $user object -->
        <div class="profile-grid">
          <div>
            <div class="profile-field-label">Full Name</div>
            <div class="profile-field-value">Dr. Rania Khalil</div>
          </div>
          <div>
            <div class="profile-field-label">Country</div>
            <div class="profile-field-value">🇪🇬 Egypt</div>
          </div>
          <div>
            <div class="profile-field-label">Phone Number</div>
            <!-- PHP: echo $user->phone -->
            <div class="profile-field-value">+20 10 1234 5678</div>
          </div>
          <div>
            <div class="profile-field-label">Niche / Specialization</div>
            <div class="profile-field-value">Data Science · ML Engineering</div>
          </div>
          <div>
            <div class="profile-field-label">Registration Date</div>
            <div class="profile-field-value font-mono" style="font-size:.8125rem;">Mar 22, 2025</div>
          </div>
          <div>
            <div class="profile-field-label">Contracts Completed</div>
            <!-- PHP: echo $user->completed_contracts_count -->
            <div class="profile-field-value">7</div>
          </div>
        </div>
      </div>

      <!-- ══════════ VERIFIED CREDENTIALS ══════════ -->
      <div class="cred-section-title" style="color:var(--sage);">✓ Verified Credentials <span
          style="font-weight:400;color:var(--ink-muted);font-size:.75rem;letter-spacing:0;text-transform:none;">(2
          documents)</span></div>

      <!-- PHP: foreach($user->kycDocuments->where('status','verified') as $doc): -->

      <!-- VERIFIED DOC 1 — National ID -->
      <div class="cred-card verified">
        <div class="cred-header">
          <div>
            <div class="cred-title">🪪 National ID Card</div>
            <div class="cred-meta">Government-issued identification · Front & back scans</div>
          </div>
          <span class="status-chip verified">✓ Verified</span>
        </div>
        <div class="cred-file-preview">
          <span class="cred-file-icon">📄</span>
          <!-- PHP: echo $doc->original_filename -->
          <div style="flex:1;">
            <div style="font-weight:600;">national_id_rania_khalil.pdf</div>
            <div class="text-xs text-muted">PDF · 1.2 MB · Uploaded Apr 2, 2025</div>
          </div>
          <!-- PHP: href="/admin/kyc/documents/{$doc->id}/download" -->
          <button class="btn btn-ghost btn-sm" style="font-size:.75rem;">📥 Download</button>
        </div>
        <div class="review-note-card">
          <div class="flex justify-between items-center" style="margin-bottom:4px;">
            <span class="reviewer">Reviewed by Sara Eissa</span>
            <span class="date">Apr 5, 2025 · 14:22</span>
          </div>
          <div style="color:var(--ink-mid);">ID verified against government registry. Name, photo, and DOB match
            registration data. Document is valid and not expired.</div>
        </div>
      </div>

      <!-- VERIFIED DOC 2 — University Degree -->
      <div class="cred-card verified">
        <div class="cred-header">
          <div>
            <div class="cred-title">🎓 Ph.D. in Computer Science</div>
            <div class="cred-meta">Cairo University · Issued 2019</div>
          </div>
          <span class="status-chip verified">✓ Verified</span>
        </div>
        <div class="cred-file-preview">
          <span class="cred-file-icon">📄</span>
          <div style="flex:1;">
            <div style="font-weight:600;">phd_certificate_cairo_uni.pdf</div>
            <div class="text-xs text-muted">PDF · 3.4 MB · Uploaded Apr 2, 2025</div>
          </div>
          <button class="btn btn-ghost btn-sm" style="font-size:.75rem;">📥 Download</button>
        </div>
        <div class="review-note-card">
          <div class="flex justify-between items-center" style="margin-bottom:4px;">
            <span class="reviewer">Reviewed by Ahmed Galal</span>
            <span class="date">Apr 6, 2025 · 09:10</span>
          </div>
          <div style="color:var(--ink-mid);">Degree verified with Cairo University registrar. Matches specialist profile
            claims. Valid credential.</div>
        </div>
      </div>
      <!-- PHP: endforeach -->

      <!-- ══════════ AWAITING REVIEW ══════════ -->
      <div class="cred-section-title" style="color:var(--gold);margin-top:32px;">⏳ Awaiting Review <span
          style="font-weight:400;color:var(--ink-muted);font-size:.75rem;letter-spacing:0;text-transform:none;">(2
          documents)</span></div>

      <!-- PHP: foreach($user->kycDocuments->where('status','pending') as $doc): -->

      <!-- PENDING DOC 1 — Professional Certification -->
      <div class="cred-card pending" id="cred-1">
        <div class="cred-header">
          <div>
            <div class="cred-title">📜 AWS Machine Learning — Specialty Certification</div>
            <div class="cred-meta">Amazon Web Services · Claimed issue date: Jan 2024 · Credential ID:
              AWS-ML-2024-RK-4821</div>
          </div>
          <span class="status-chip pending" id="cred-1-status">⏳ Awaiting Review</span>
        </div>
        <div class="cred-file-preview">
          <span class="cred-file-icon">📄</span>
          <div style="flex:1;">
            <div style="font-weight:600;">aws_ml_specialty_cert.pdf</div>
            <div class="text-xs text-muted">PDF · 890 KB · Uploaded Apr 14, 2025</div>
          </div>
          <button class="btn btn-ghost btn-sm" style="font-size:.75rem;">📥 Download</button>
        </div>
        <!-- REVIEW FORM -->
        <!-- PHP: <form method="POST" action="/admin/kyc/documents/{$doc->id}/review"> -->
        <div class="form-group" style="margin-top:14px;margin-bottom:0;">
          <label class="form-label">Review Notes <span class="text-muted"
              style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.75rem;">— Required for rejection,
              optional for approval</span></label>
          <textarea class="form-control" rows="2" id="cred-1-notes"
            placeholder="e.g. Verified credential ID with AWS certification portal. Valid until Jan 2027."></textarea>
        </div>
        <div class="cred-actions">
          <!-- PHP: action="/admin/kyc/documents/{$doc->id}/approve" -->
          <button class="btn btn-primary btn-sm" onclick="verifyCred('cred-1','AWS ML Specialty Certification')">✓
            Verify & Approve</button>
          <!-- PHP: action="/admin/kyc/documents/{$doc->id}/reject" -->
          <button class="btn btn-outline btn-sm" style="color:var(--rust);border-color:var(--rust);"
            onclick="rejectCred('cred-1','AWS ML Specialty Certification')">✕ Reject</button>
          <span class="text-xs text-muted" style="margin-left:auto;">Verification is logged and visible to the
            user.</span>
        </div>
        <!-- PHP: </form> -->
      </div>

      <!-- PENDING DOC 2 — Proof of Address -->
      <div class="cred-card pending" id="cred-2">
        <div class="cred-header">
          <div>
            <div class="cred-title">🏠 Proof of Address — Utility Bill</div>
            <div class="cred-meta">Electricity bill · Cairo, Egypt · Dated March 2025</div>
          </div>
          <span class="status-chip pending" id="cred-2-status">⏳ Awaiting Review</span>
        </div>
        <div class="cred-file-preview">
          <span class="cred-file-icon">🖼️</span>
          <div style="flex:1;">
            <div style="font-weight:600;">utility_bill_mar2025.jpg</div>
            <div class="text-xs text-muted">JPEG · 1.8 MB · Uploaded Apr 14, 2025</div>
          </div>
          <button class="btn btn-ghost btn-sm" style="font-size:.75rem;">📥 Download</button>
        </div>
        <div class="form-group" style="margin-top:14px;margin-bottom:0;">
          <label class="form-label">Review Notes</label>
          <textarea class="form-control" rows="2" id="cred-2-notes"
            placeholder="e.g. Address matches registration. Bill is dated within the last 3 months."></textarea>
        </div>
        <div class="cred-actions">
          <button class="btn btn-primary btn-sm" onclick="verifyCred('cred-2','Proof of Address')">✓ Verify &
            Approve</button>
          <button class="btn btn-outline btn-sm" style="color:var(--rust);border-color:var(--rust);"
            onclick="rejectCred('cred-2','Proof of Address')">✕ Reject</button>
          <span class="text-xs text-muted" style="margin-left:auto;">Verification is logged and visible to the
            user.</span>
        </div>
      </div>
      <!-- PHP: endforeach -->

      <!-- ══════════ FULL VERIFY BUTTON ══════════ -->
      <!-- PHP: Show only when all documents are verified -->
      <div
        style="background:var(--ivory-card);border:1.5px solid var(--gold);border-radius:var(--radius-md);padding:20px 24px;margin-top:28px;display:flex;align-items:center;justify-content:space-between;"
        id="full-verify-bar">
        <div>
          <div style="font-weight:700;font-size:.9375rem;">Complete KYC Verification</div>
          <div class="text-sm text-muted" style="margin-top:2px;">All documents must be reviewed before the user's
            account can be fully verified.</div>
        </div>
        <!-- PHP: POST /admin/kyc/users/{$user->id}/verify-all -->
        <button class="btn btn-primary btn-lg" id="verify-all-btn" disabled onclick="verifyAll()">🛡 Verify User
          Account</button>
      </div>

    </main>
  </div>

  <div class="toast-stack" id="toast-stack"></div>

  <script>
    let pendingCount = 2;

    /* ── VERIFY SINGLE CREDENTIAL ── */
    function verifyCred(id, name) {
      const notes = document.getElementById(id + '-notes')?.value;
      const card = document.getElementById(id);
      const status = document.getElementById(id + '-status');
      // PHP: AJAX POST /admin/kyc/documents/{id}/approve { notes }
      card.classList.remove('pending');
      card.classList.add('verified');
      status.className = 'status-chip verified';
      status.textContent = '✓ Verified';
      // Hide the actions
      const actions = card.querySelector('.cred-actions');
      const form = card.querySelector('.form-group');
      if (actions) actions.style.display = 'none';
      if (form) form.style.display = 'none';
      // Add review note
      const noteCard = document.createElement('div');
      noteCard.className = 'review-note-card';
      noteCard.innerHTML = `<div class="flex justify-between items-center" style="margin-bottom:4px;"><span class="reviewer">Reviewed by Omar Hassan (You)</span><span class="date">Just now</span></div><div style="color:var(--ink-mid);">${notes || 'Approved — no additional notes.'}</div>`;
      card.querySelector('.cred-file-preview').after(noteCard);
      pendingCount--;
      checkAllVerified();
      showToast(name + ' has been verified and approved.');
    }

    /* ── REJECT SINGLE CREDENTIAL ── */
    function rejectCred(id, name) {
      const notes = document.getElementById(id + '-notes')?.value;
      if (!notes || notes.trim().length < 5) { showToast('Please provide a reason for rejection.', 'warn'); return; }
      const card = document.getElementById(id);
      const status = document.getElementById(id + '-status');
      // PHP: AJAX POST /admin/kyc/documents/{id}/reject { notes }
      card.classList.remove('pending');
      card.classList.add('rejected');
      status.className = 'status-chip rejected';
      status.textContent = '✕ Rejected';
      const actions = card.querySelector('.cred-actions');
      const form = card.querySelector('.form-group');
      if (actions) actions.style.display = 'none';
      if (form) form.style.display = 'none';
      const noteCard = document.createElement('div');
      noteCard.className = 'review-note-card';
      noteCard.innerHTML = `<div class="flex justify-between items-center" style="margin-bottom:4px;"><span class="reviewer" style="color:var(--rust);">Rejected by Omar Hassan (You)</span><span class="date">Just now</span></div><div style="color:var(--rust);">${notes}</div>`;
      card.querySelector('.cred-file-preview').after(noteCard);
      pendingCount--;
      showToast(name + ' has been rejected. The user will be notified.', 'warn');
    }

    /* ── CHECK ALL VERIFIED ── */
    function checkAllVerified() {
      const btn = document.getElementById('verify-all-btn');
      if (pendingCount <= 0) { btn.disabled = false; }
    }

    /* ── VERIFY ALL ── */
    function verifyAll() {
      // PHP: AJAX POST /admin/kyc/users/{user_id}/verify-all
      const bar = document.getElementById('full-verify-bar');
      bar.style.borderColor = 'var(--sage)';
      bar.innerHTML = '<div style="display:flex;align-items:center;gap:12px;"><span style="font-size:1.4rem;">✅</span><div><div style="font-weight:700;font-size:.9375rem;color:var(--sage);">User Account Fully Verified</div><div class="text-sm text-muted">All KYC documents have been reviewed. Dr. Rania Khalil\'s account is now active.</div></div></div>';
      showToast('Dr. Rania Khalil\'s account has been fully verified.');
    }

    /* ── TOAST ── */
    function showToast(msg, type = 'success') {
      const s = document.getElementById('toast-stack');
      const icons = { success: '✓', warn: '⚠', info: 'ℹ' };
      const cls = { success: 'success', warn: 'warning', info: '' };
      s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type === 'warn' ? 'Notice' : type === 'info' ? 'Info' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
      setTimeout(() => s.innerHTML = '', 4500);
    }
  </script>
</body>

</html>