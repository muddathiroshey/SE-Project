<?php
// Expected variables from AdminController::kycDetail():
// $submission — [
//   user: [ name, initials, email, phone, country, country_flag, niche, registered_at, completed_contracts ],
//   verified_docs: [ { id, icon, title, meta, filename, filesize, filetype, uploaded_at,
//                      reviewer_name, reviewed_at, review_note } ],
//   pending_docs:  [ { id, icon, title, meta, filename, filesize, filetype, uploaded_at } ],
// ]
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KYC Review — <?= htmlspecialchars($submission['user']['name'] ?? '') ?> — Nexus Admin</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin-kyc-detail.css">
</head>
<body>

  <nav class="topnav" style="background:var(--ink);border-bottom:1px solid rgba(247,244,239,.1);">
    <div class="container" style="max-width:100%;padding:0 32px;">
      <a class="topnav-logo" href="/admin" style="color:var(--ivory);">Nexus<span style="color:var(--gold);">.</span></a>
      <div class="topnav-links"><a href="/admin" style="color:rgba(247,244,239,.6);">Dashboard</a></div>
      <div class="topnav-actions">
        <div class="flex items-center gap-8">
          <div class="avatar avatar-sm" style="background:var(--gold);color:var(--ink);font-size:.75rem;font-weight:700;"><?= strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? ''), 0, 2)) ?: 'ME' ?></div>
          <span style="font-size:.875rem;font-weight:700;color:var(--ivory);"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Me') ?></span>
          <span class="role-badge rb-super" style="font-size:.6rem;"><?= htmlspecialchars(($_SESSION['role'] ?? 'Account') . ' Account') ?></span>
        </div>
      </div>
    </div>
  </nav>

  <div class="admin-shell">
    <aside class="admin-sidebar">
      <div class="admin-sidebar-section">Overview</div>
      <a class="admin-sidebar-link" href="/admin">📊 Health Dashboard</a>
      <div class="admin-sidebar-section">Marketplace</div>
      <a class="admin-sidebar-link" href="/admin/users">👤 Users</a>
      <div class="admin-sidebar-section">Disputes</div>
      <a class="admin-sidebar-link" href="/admin/disputes">⚖️ Active Disputes
        <span class="notif-count" style="margin-left:auto;background:var(--rust);"><?= (int)($stats['open_disputes'] ?? 0) ?></span>
      </a>
      <div class="admin-sidebar-section">Verifications</div>
      <a class="admin-sidebar-link active" href="/admin/kyc">🛡 KYC Queue</a>
      <div class="admin-sidebar-section">Sanctions</div>
      <a class="admin-sidebar-link" href="/admin/sanctions">⚠️ User Sanctions</a>
      <div class="admin-sidebar-section">Support</div>
      <a class="admin-sidebar-link" href="/admin/support">💬 Chat Support</a>
    </aside>

    <main class="admin-main">

      <div style="margin-bottom:24px;">
        <a href="/admin/kyc" style="font-size:.8125rem;color:var(--gold);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">← Back to KYC Queue</a>
        <div class="breadcrumb" style="font-family:var(--font-mono);font-size:.72rem;color:var(--ink-muted);margin-top:8px;">
          Admin Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Verifications <span style="margin:0 6px;color:var(--ink-faint);">›</span> KYC Queue <span style="margin:0 6px;color:var(--ink-faint);">›</span>
          <strong><?= htmlspecialchars($submission['user']['name'] ?? '') ?></strong>
        </div>
      </div>

      <!-- USER PROFILE CARD -->
      <div class="profile-card">
        <div class="flex items-center gap-16">
          <div class="avatar" style="width:56px;height:56px;font-size:1.1rem;flex-shrink:0;"><?= htmlspecialchars($submission['user']['initials'] ?? '') ?></div>
          <div style="flex:1;">
            <h2 style="font-family:var(--font-display);font-size:1.4rem;font-weight:600;margin-bottom:2px;"><?= htmlspecialchars($submission['user']['name'] ?? '') ?></h2>
            <div class="flex items-center gap-10" style="flex-wrap:wrap;">
              <span class="text-sm text-muted font-mono"><?= htmlspecialchars($submission['user']['email'] ?? '') ?></span>
              <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:2px;font-size:.6875rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;background:#EBF3EA;color:var(--sage);border:1px solid #C5DBC2;">Specialist</span>
              <span class="status-chip pending">⏳ Pending Verification</span>
            </div>
          </div>
          <a href="/admin/users/<?= (int)($submission['user']['id'] ?? 0) ?>" class="btn btn-ghost btn-sm" style="font-size:.75rem;">View Full Profile →</a>
        </div>

        <div class="profile-grid">
          <div>
            <div class="profile-field-label">Full Name</div>
            <div class="profile-field-value"><?= htmlspecialchars($submission['user']['name'] ?? '') ?></div>
          </div>
          <div>
            <div class="profile-field-label">Country</div>
            <div class="profile-field-value"><?= htmlspecialchars($submission['user']['country_flag'] ?? '') ?> <?= htmlspecialchars($submission['user']['country'] ?? '') ?></div>
          </div>
          <div>
            <div class="profile-field-label">Phone Number</div>
            <div class="profile-field-value"><?= htmlspecialchars($submission['user']['phone'] ?? '—') ?></div>
          </div>
          <div>
            <div class="profile-field-label">Niche / Specialization</div>
            <div class="profile-field-value"><?= htmlspecialchars($submission['user']['niche'] ?? '') ?></div>
          </div>
          <div>
            <div class="profile-field-label">Registration Date</div>
            <div class="profile-field-value font-mono" style="font-size:.8125rem;"><?= date('M j, Y', strtotime($submission['user']['registered_at'] ?? 'now')) ?></div>
          </div>
          <div>
            <div class="profile-field-label">Contracts Completed</div>
            <div class="profile-field-value"><?= (int)($submission['user']['completed_contracts'] ?? 0) ?></div>
          </div>
        </div>
      </div>

      <!-- VERIFIED CREDENTIALS -->
      <?php if (!empty($submission['verified_docs'])): ?>
      <div class="cred-section-title" style="color:var(--sage);">✓ Verified Credentials
        <span style="font-weight:400;color:var(--ink-muted);font-size:.75rem;letter-spacing:0;text-transform:none;">(<?= count($submission['verified_docs']) ?> document<?= count($submission['verified_docs']) !== 1 ? 's' : '' ?>)</span>
      </div>

      <?php foreach ($submission['verified_docs'] as $doc): ?>
      <div class="cred-card verified">
        <div class="cred-header">
          <div>
            <div class="cred-title"><?= htmlspecialchars($doc['icon'] ?? '📄') ?> <?= htmlspecialchars($doc['title']) ?></div>
            <div class="cred-meta"><?= htmlspecialchars($doc['meta'] ?? '') ?></div>
          </div>
          <span class="status-chip verified">✓ Verified</span>
        </div>
        <div class="cred-file-preview">
          <span class="cred-file-icon">📄</span>
          <div style="flex:1;">
            <div style="font-weight:600;"><?= htmlspecialchars($doc['filename']) ?></div>
            <div class="text-xs text-muted"><?= strtoupper(htmlspecialchars($doc['filetype'])) ?> · <?= htmlspecialchars($doc['filesize']) ?> · Uploaded <?= date('M j, Y', strtotime($doc['uploaded_at'])) ?></div>
          </div>
          <a href="/admin/kyc/documents/<?= (int)$doc['id'] ?>/download" class="btn btn-ghost btn-sm" style="font-size:.75rem;">📥 Download</a>
        </div>
        <div class="review-note-card">
          <div class="flex justify-between items-center" style="margin-bottom:4px;">
            <span class="reviewer">Reviewed by <?= htmlspecialchars($doc['reviewer_name']) ?></span>
            <span class="date"><?= date('M j, Y', strtotime($doc['reviewed_at'])) ?> · <?= date('H:i', strtotime($doc['reviewed_at'])) ?></span>
          </div>
          <div style="color:var(--ink-mid);"><?= htmlspecialchars($doc['review_note'] ?? '') ?></div>
        </div>
      </div>
      <?php endforeach ?>
      <?php endif ?>

      <!-- PENDING CREDENTIALS -->
      <?php if (!empty($submission['pending_docs'])): ?>
      <div class="cred-section-title" style="color:var(--gold);margin-top:32px;">⏳ Awaiting Review
        <span style="font-weight:400;color:var(--ink-muted);font-size:.75rem;letter-spacing:0;text-transform:none;">(<?= count($submission['pending_docs']) ?> document<?= count($submission['pending_docs']) !== 1 ? 's' : '' ?>)</span>
      </div>

      <?php foreach ($submission['pending_docs'] as $doc): ?>
      <div class="cred-card pending" id="cred-<?= (int)$doc['id'] ?>">
        <div class="cred-header">
          <div>
            <div class="cred-title"><?= htmlspecialchars($doc['icon'] ?? '📄') ?> <?= htmlspecialchars($doc['title']) ?></div>
            <div class="cred-meta"><?= htmlspecialchars($doc['meta'] ?? '') ?></div>
          </div>
          <span class="status-chip pending" id="cred-<?= (int)$doc['id'] ?>-status">⏳ Awaiting Review</span>
        </div>
        <div class="cred-file-preview">
          <span class="cred-file-icon">📄</span>
          <div style="flex:1;">
            <div style="font-weight:600;"><?= htmlspecialchars($doc['filename']) ?></div>
            <div class="text-xs text-muted"><?= strtoupper(htmlspecialchars($doc['filetype'])) ?> · <?= htmlspecialchars($doc['filesize']) ?> · Uploaded <?= date('M j, Y', strtotime($doc['uploaded_at'])) ?></div>
          </div>
          <a href="/admin/kyc/documents/<?= (int)$doc['id'] ?>/download" class="btn btn-ghost btn-sm" style="font-size:.75rem;">📥 Download</a>
        </div>
        <div class="form-group" style="margin-top:14px;margin-bottom:0;">
          <label class="form-label">Review Notes <span class="text-muted" style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.75rem;">— Required for rejection, optional for approval</span></label>
          <textarea class="form-control" rows="2" id="cred-<?= (int)$doc['id'] ?>-notes" placeholder="e.g. Verified credential with issuing body. Valid and unexpired."></textarea>
        </div>
        <div class="cred-actions">
          <button class="btn btn-primary btn-sm" onclick="verifyCred(<?= (int)$doc['id'] ?>, '<?= htmlspecialchars($doc['title'], ENT_QUOTES) ?>')">✓ Verify &amp; Approve</button>
          <button class="btn btn-outline btn-sm" style="color:var(--rust);border-color:var(--rust);" onclick="rejectCred(<?= (int)$doc['id'] ?>, '<?= htmlspecialchars($doc['title'], ENT_QUOTES) ?>')">✕ Reject</button>
          <span class="text-xs text-muted" style="margin-left:auto;">Verification is logged and visible to the user.</span>
        </div>
      </div>
      <?php endforeach ?>
      <?php endif ?>

      <!-- FULL VERIFY BUTTON -->
      <div style="background:var(--ivory-card);border:1.5px solid var(--gold);border-radius:var(--radius-md);padding:20px 24px;margin-top:28px;display:flex;align-items:center;justify-content:space-between;" id="full-verify-bar">
        <div>
          <div style="font-weight:700;font-size:.9375rem;">Complete KYC Verification</div>
          <div class="text-sm text-muted" style="margin-top:2px;">All documents must be reviewed before the user's account can be fully verified.</div>
        </div>
        <button class="btn btn-primary btn-lg" id="verify-all-btn" <?= !empty($submission['pending_docs']) ? 'disabled' : '' ?> onclick="verifyAll()">
          🛡 Verify User Account
        </button>
      </div>

    </main>
  </div>

  <div class="toast-stack" id="toast-stack"></div>

  <script>
    let pendingCount = <?= count($submission['pending_docs'] ?? []) ?>;
    const adminName = <?= json_encode(htmlspecialchars($_SESSION['user_name'] ?? 'You')) ?>;
    const userName  = <?= json_encode(htmlspecialchars($submission['user']['name'] ?? '')) ?>;

    function verifyCred(id, name) {
      const notes = document.getElementById('cred-' + id + '-notes')?.value;
      const card   = document.getElementById('cred-' + id);
      const status = document.getElementById('cred-' + id + '-status');
      // POST /admin/kyc/documents/{id}/approve { notes }
      card.classList.replace('pending', 'verified');
      status.className = 'status-chip verified';
      status.textContent = '✓ Verified';
      card.querySelector('.cred-actions')?.remove();
      card.querySelector('.form-group')?.remove();
      const noteCard = document.createElement('div');
      noteCard.className = 'review-note-card';
      noteCard.innerHTML = `<div class="flex justify-between items-center" style="margin-bottom:4px;"><span class="reviewer">Reviewed by ${adminName}</span><span class="date">Just now</span></div><div style="color:var(--ink-mid);">${notes || 'Approved — no additional notes.'}</div>`;
      card.querySelector('.cred-file-preview').after(noteCard);
      pendingCount--;
      if (pendingCount <= 0) document.getElementById('verify-all-btn').disabled = false;
      showToast(name + ' has been verified and approved.');
    }

    function rejectCred(id, name) {
      const notes = document.getElementById('cred-' + id + '-notes')?.value;
      if (!notes || notes.trim().length < 5) { showToast('Please provide a reason for rejection.', 'warn'); return; }
      const card   = document.getElementById('cred-' + id);
      const status = document.getElementById('cred-' + id + '-status');
      // POST /admin/kyc/documents/{id}/reject { notes }
      card.classList.replace('pending', 'rejected');
      status.className = 'status-chip rejected';
      status.textContent = '✕ Rejected';
      card.querySelector('.cred-actions')?.remove();
      card.querySelector('.form-group')?.remove();
      const noteCard = document.createElement('div');
      noteCard.className = 'review-note-card';
      noteCard.innerHTML = `<div class="flex justify-between items-center" style="margin-bottom:4px;"><span class="reviewer" style="color:var(--rust);">Rejected by ${adminName}</span><span class="date">Just now</span></div><div style="color:var(--rust);">${notes}</div>`;
      card.querySelector('.cred-file-preview').after(noteCard);
      pendingCount--;
      showToast(name + ' has been rejected. The user will be notified.', 'warn');
    }

    function verifyAll() {
      // POST /admin/kyc/users/{user_id}/verify-all
      const bar = document.getElementById('full-verify-bar');
      bar.style.borderColor = 'var(--sage)';
      bar.innerHTML = `<div style="display:flex;align-items:center;gap:12px;"><span style="font-size:1.4rem;">✅</span><div><div style="font-weight:700;font-size:.9375rem;color:var(--sage);">User Account Fully Verified</div><div class="text-sm text-muted">All KYC documents have been reviewed. ${userName}'s account is now active.</div></div></div>`;
      showToast(userName + "'s account has been fully verified.");
    }

    function showToast(msg, type = 'success') {
      const s = document.getElementById('toast-stack');
      const icons = { success: '✓', warn: '⚠' };
      const cls   = { success: 'success', warn: 'warning' };
      s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type === 'warn' ? 'Notice' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
      setTimeout(() => s.innerHTML = '', 4500);
    }
  </script>
</body>
</html>