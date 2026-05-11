<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KYC Queue — Nexus Admin</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin-kyc.css">
</head>

<body>

  <?php require __DIR__ . '/../partials/topnav.php'; ?>

  <!-- ══════════ ADMIN SHELL ══════════ -->
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

      <!-- PAGE HEADER -->
      <div class="flex justify-between items-start mb-28">
        <div>
          <div class="breadcrumb"
            style="font-family:var(--font-mono);font-size:.72rem;color:var(--ink-muted);margin-bottom:8px;">
            Admin Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Verifications <span
              style="margin:0 6px;color:var(--ink-faint);">›</span> KYC Queue
          </div>
          <h2 style="font-family:var(--font-display);font-size:1.6rem;font-weight:500;margin-bottom:6px;">KYC
            Verification Queue</h2>
          <p style="font-size:.875rem;color:var(--ink-muted);">Review and verify uploaded identity documents and
            credentials for clients and specialists.</p>
        </div>
      </div>

      <!-- ── STAT CARDS ── -->
      <!-- PHP: Populate stats from $kycStats = KycSubmission::getQueueStats() -->
      <div class="kyc-stats">
        <div class="kyc-stat-card urgent">
          <div class="stat-value" style="font-size:1.8rem;">214</div>
          <div class="stat-label">Total Pending</div>
          <div class="stat-delta down mt-4">↑ +12 today</div>
        </div>
        <div class="kyc-stat-card">
          <div class="stat-value" style="font-size:1.8rem;">87</div>
          <div class="stat-label">Clients Pending</div>
          <div class="stat-delta mt-4" style="color:var(--ink-muted);">ID + Proof of Address</div>
        </div>
        <div class="kyc-stat-card">
          <div class="stat-value" style="font-size:1.8rem;">127</div>
          <div class="stat-label">Specialists Pending</div>
          <div class="stat-delta mt-4" style="color:var(--ink-muted);">Credentials + Licenses</div>
        </div>
        <div class="kyc-stat-card ok">
          <div class="stat-value" style="font-size:1.8rem;">2.4d</div>
          <div class="stat-label">Avg. Processing Time</div>
          <div class="stat-delta up mt-4">↓ Improved from 3.1d</div>
        </div>
      </div>

      <!-- ── FILTER BAR ── -->
      <div class="flex justify-between items-center mb-14">
        <div class="filter-bar" style="margin-bottom:0;">
          <span
            style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);font-family:var(--font-body);">Filter:</span>
          <button class="filter-chip active" onclick="filterKYC('all', this)">All (214)</button>
          <button class="filter-chip" onclick="filterKYC('client', this)">Clients (87)</button>
          <button class="filter-chip" onclick="filterKYC('specialist', this)">Specialists (127)</button>
          <button class="filter-chip" onclick="filterKYC('high', this)">High Priority (18)</button>
        </div>
        <div class="search-wrap">
          <span class="icon">🔍</span>
          <input type="text" class="search-input" placeholder="Search by name or email…"
            oninput="searchKYC(this.value)">
        </div>
      </div>

      <!-- ── KYC TABLE ── -->
      <!-- PHP: foreach($pendingSubmissions as $submission): -->
      <!-- PHP: Each row links to admin-kyc-detail.html?user_id=$submission->user_id -->
      <div
        style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;">
        <table class="kyc-table">
          <thead>
            <tr>
              <th>Priority</th>
              <th>User</th>
              <th>Type</th>
              <th>Country</th>
              <th>Niche / Industry</th>
              <th>Documents</th>
              <th>Submitted</th>
              <th>Status</th>
              <th style="text-align:right;">Action</th>
            </tr>
          </thead>
          <tbody>

            <!-- ROW 1 — Specialist, High Priority -->
            <tr onclick="window.location='admin-kyc-detail.html'" data-type="specialist" data-priority="high"
              data-name="dr. rania khalil" data-email="rania@nexus.io">
              <td><span class="priority-dot high"></span></td>
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">RK</div>
                  <div>
                    <div style="font-weight:700;">Dr. Rania Khalil</div>
                    <div class="text-xs text-muted font-mono">rania@nexus.io</div>
                  </div>
                </div>
              </td>
              <td><span class="type-pill specialist">Specialist</span></td>
              <td>Egypt</td>
              <td style="font-size:.8125rem;">Data Science</td>
              <td>
                <div class="doc-count">4 files · <span class="pending">2 awaiting</span></div>
              </td>
              <td class="font-mono text-xs text-muted">Apr 14, 2025</td>
              <td><span class="type-pill" style="background:#FDF3E0;color:#9A6800;border:1px solid #F0D899;">Awaiting
                  Review</span></td>
              <td style="text-align:right;">
                <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                  onclick="event.stopPropagation();window.location='admin-kyc-detail.html'">Review →</button>
              </td>
            </tr>

            <!-- ROW 2 — Client, High Priority -->
            <tr onclick="window.location='admin-kyc-detail.html'" data-type="client" data-priority="high"
              data-name="fincorp egypt" data-email="admin@fincorp.eg">
              <td><span class="priority-dot high"></span></td>
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">FC</div>
                  <div>
                    <div style="font-weight:700;">FinCorp Egypt</div>
                    <div class="text-xs text-muted font-mono">admin@fincorp.eg</div>
                  </div>
                </div>
              </td>
              <td><span class="type-pill client">Client</span></td>
              <td>Egypt</td>
              <td style="font-size:.8125rem;">Financial Services</td>
              <td>
                <div class="doc-count">3 files · <span class="pending">3 awaiting</span></div>
              </td>
              <td class="font-mono text-xs text-muted">Apr 13, 2025</td>
              <td><span class="type-pill" style="background:#FDF3E0;color:#9A6800;border:1px solid #F0D899;">Awaiting
                  Review</span></td>
              <td style="text-align:right;">
                <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                  onclick="event.stopPropagation();window.location='admin-kyc-detail.html'">Review →</button>
              </td>
            </tr>

            <!-- ROW 3 — Specialist, Medium -->
            <tr onclick="window.location='admin-kyc-detail.html'" data-type="specialist" data-priority="medium"
              data-name="lena bergmann" data-email="lena.b@nexus.io">
              <td><span class="priority-dot medium"></span></td>
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">LB</div>
                  <div>
                    <div style="font-weight:700;">Lena Bergmann</div>
                    <div class="text-xs text-muted font-mono">lena.b@nexus.io</div>
                  </div>
                </div>
              </td>
              <td><span class="type-pill specialist">Specialist</span></td>
              <td>Germany</td>
              <td style="font-size:.8125rem;">Technical Translation</td>
              <td>
                <div class="doc-count">5 files · <span class="pending">1 awaiting</span></div>
              </td>
              <td class="font-mono text-xs text-muted">Apr 12, 2025</td>
              <td><span class="type-pill" style="background:#FDF3E0;color:#9A6800;border:1px solid #F0D899;">Awaiting
                  Review</span></td>
              <td style="text-align:right;">
                <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                  onclick="event.stopPropagation();window.location='admin-kyc-detail.html'">Review →</button>
              </td>
            </tr>

            <!-- ROW 4 — Client, Medium -->
            <tr onclick="window.location='admin-kyc-detail.html'" data-type="client" data-priority="medium"
              data-name="medgroup ksa" data-email="ops@medgroup.sa">
              <td><span class="priority-dot medium"></span></td>
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">MG</div>
                  <div>
                    <div style="font-weight:700;">MedGroup KSA</div>
                    <div class="text-xs text-muted font-mono">ops@medgroup.sa</div>
                  </div>
                </div>
              </td>
              <td><span class="type-pill client">Client</span></td>
              <td>Saudi Arabia</td>
              <td style="font-size:.8125rem;">Healthcare</td>
              <td>
                <div class="doc-count">2 files · <span class="pending">2 awaiting</span></div>
              </td>
              <td class="font-mono text-xs text-muted">Apr 11, 2025</td>
              <td><span class="type-pill" style="background:#FDF3E0;color:#9A6800;border:1px solid #F0D899;">Awaiting
                  Review</span></td>
              <td style="text-align:right;">
                <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                  onclick="event.stopPropagation();window.location='admin-kyc-detail.html'">Review →</button>
              </td>
            </tr>

            <!-- ROW 5 — Specialist, Low -->
            <tr onclick="window.location='admin-kyc-detail.html'" data-type="specialist" data-priority="low"
              data-name="karim al-azzawi" data-email="karim.a@nexus.io">
              <td><span class="priority-dot low"></span></td>
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">KA</div>
                  <div>
                    <div style="font-weight:700;">Karim Al-Azzawi</div>
                    <div class="text-xs text-muted font-mono">karim.a@nexus.io</div>
                  </div>
                </div>
              </td>
              <td><span class="type-pill specialist">Specialist</span></td>
              <td>Iraq</td>
              <td style="font-size:.8125rem;">Cybersecurity</td>
              <td>
                <div class="doc-count">3 files · <span class="pending">1 awaiting</span></div>
              </td>
              <td class="font-mono text-xs text-muted">Apr 10, 2025</td>
              <td><span class="type-pill" style="background:#FDF3E0;color:#9A6800;border:1px solid #F0D899;">Awaiting
                  Review</span></td>
              <td style="text-align:right;">
                <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                  onclick="event.stopPropagation();window.location='admin-kyc-detail.html'">Review →</button>
              </td>
            </tr>

            <!-- ROW 6 — Client, Low -->
            <tr onclick="window.location='admin-kyc-detail.html'" data-type="client" data-priority="low"
              data-name="techinfra uae" data-email="hr@techinfra.ae">
              <td><span class="priority-dot low"></span></td>
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">TI</div>
                  <div>
                    <div style="font-weight:700;">TechInfra UAE</div>
                    <div class="text-xs text-muted font-mono">hr@techinfra.ae</div>
                  </div>
                </div>
              </td>
              <td><span class="type-pill client">Client</span></td>
              <td>UAE</td>
              <td style="font-size:.8125rem;">IT Infrastructure</td>
              <td>
                <div class="doc-count">2 files · <span class="pending">1 awaiting</span></div>
              </td>
              <td class="font-mono text-xs text-muted">Apr 9, 2025</td>
              <td><span class="type-pill" style="background:#FDF3E0;color:#9A6800;border:1px solid #F0D899;">Awaiting
                  Review</span></td>
              <td style="text-align:right;">
                <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                  onclick="event.stopPropagation();window.location='admin-kyc-detail.html'">Review →</button>
              </td>
            </tr>
            <!-- PHP: endforeach -->

          </tbody>
        </table>
      </div>

      <!-- PAGINATION -->
      <!-- PHP: Render pagination from $pendingSubmissions->links() -->
      <div class="flex justify-between items-center" style="margin-top:20px;">
        <span class="text-xs text-muted">Showing 6 of 214 submissions</span>
        <div class="flex gap-6">
          <button class="btn btn-ghost btn-sm" disabled>← Previous</button>
          <button class="btn btn-outline btn-sm">Next →</button>
        </div>
      </div>

    </main>
  </div>

  <!-- TOAST -->
  <div class="toast-stack" id="toast-stack"></div>

  <script>
    /* ── FILTER ── */
    function filterKYC(type, el) {
      document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
      el.classList.add('active');
      document.querySelectorAll('.kyc-table tbody tr').forEach(row => {
        if (type === 'all') { row.style.display = ''; return; }
        if (type === 'high') { row.style.display = row.dataset.priority === 'high' ? '' : 'none'; return; }
        row.style.display = row.dataset.type === type ? '' : 'none';
      });
    }

    /* ── SEARCH ── */
    function searchKYC(q) {
      q = q.toLowerCase();
      document.querySelectorAll('.kyc-table tbody tr').forEach(row => {
        const name = row.dataset.name || '';
        const email = row.dataset.email || '';
        row.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
      });
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