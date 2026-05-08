<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Active Disputes — Nexus Admin</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/open-disputes.css">

</head>

<body>

  <nav class="topnav" style="background:var(--ink);border-bottom:1px solid rgba(247,244,239,.1);">
    <div class="container" style="max-width:100%;padding:0 32px;">
      <a class="topnav-logo" href="admin-dashboard.html" style="color:var(--ivory);">Nexus<span
          style="color:var(--gold);">.</span></a>
      <div class="topnav-links">
        <a href="admin-dashboard.html" style="color:rgba(247,244,239,.6);">Dashboard</a>
      </div>
      <div class="topnav-actions">
        <div class="flex items-center gap-8">
          <div class="avatar avatar-sm"
            style="background:var(--gold);color:var(--ink);font-size:.75rem;font-weight:700;">OH</div>
          <span style="font-size:.875rem;font-weight:700;color:var(--ivory);">Omar H.</span>
          <span class="role-badge rb-super" style="font-size:.6rem;">Super Admin</span>
        </div>
      </div>
    </div>
  </nav>

  <div class="admin-shell">
    <aside class="admin-sidebar">
      <div class="admin-sidebar-section">Overview</div>
      <a class="admin-sidebar-link" href="admin-dashboard.html">📊 Health Dashboard</a>

      <div class="admin-sidebar-section">Marketplace</div>
      <a class="admin-sidebar-link" href="admin-team.html">👤 Users</a>

      <div class="admin-sidebar-section">Disputes</div>
      <a class="admin-sidebar-link active" href="open-disputes.html">⚖️ Active Disputes <span class="notif-count"
          style="margin-left:auto;background:var(--rust);">4</span></a>

      <div class="admin-sidebar-section">Verifications</div>
      <a class="admin-sidebar-link" href="admin-kyc.html">🛡 KYC Queue</a>

      <div class="admin-sidebar-section">Sanctions</div>
      <a class="admin-sidebar-link" href="sanctions.html">⚠️ User Sanctions</a>

      <div class="admin-sidebar-section">Support</div>
      <a class="admin-sidebar-link" href="admin-support.html">💬 Chat Support</a>
    </aside>

    <main class="admin-main">

      <!-- PAGE HEADER -->
      <div class="flex justify-between items-start mb-28">
        <div>
          <div class="breadcrumb"
            style="font-family:var(--font-mono);font-size:.72rem;color:var(--ink-muted);margin-bottom:8px;">
            Admin Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Disputes
          </div>
          <h2 style="font-family:var(--font-display);font-size:1.6rem;font-weight:500;margin-bottom:6px;">Active
            Disputes</h2>
          <p style="font-size:.875rem;color:var(--ink-muted);">Monitor, review, and manage all open dispute cases across
            the platform.</p>
        </div>
      </div>

      <!-- STAT CARDS -->
      <div class="dispute-stats">
        <div class="dispute-stat-card urgent">
          <div class="stat-value" style="font-size:1.8rem;">4</div>
          <div class="stat-label">Open Disputes</div>
          <div class="stat-delta down mt-4">↑ +1 today</div>
        </div>
        <div class="dispute-stat-card">
          <div class="stat-value" style="font-size:1.8rem;">2</div>
          <div class="stat-label">Awaiting Verdict</div>
          <div class="stat-delta mt-4" style="color:var(--ink-muted);">Avg. 48h response</div>
        </div>
        <div class="dispute-stat-card">
          <div class="stat-value" style="font-size:1.8rem;">$18,400</div>
          <div class="stat-label">Disputed Value</div>
          <div class="stat-delta mt-4" style="color:var(--ink-muted);">Across 4 cases</div>
        </div>
        <div class="dispute-stat-card resolved">
          <div class="stat-value" style="font-size:1.8rem;">98.1%</div>
          <div class="stat-label">Resolution Rate</div>
          <div class="stat-delta up mt-4">↑ Above 95% SLA</div>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div class="filter-bar">
        <span
          style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);font-family:var(--font-body);">Filter:</span>
        <button class="filter-chip active" onclick="filterDisputes('all', this)">All (4)</button>
        <button class="filter-chip" onclick="filterDisputes('review', this)">Under Review (2)</button>
        <button class="filter-chip" onclick="filterDisputes('evidence', this)">Evidence Phase (1)</button>
        <button class="filter-chip" onclick="filterDisputes('escalated', this)">Escalated (1)</button>
      </div>

      <!-- DISPUTES TABLE -->
      <div
        style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;">
        <table class="dispute-table">
          <thead>
            <tr>
              <th>Priority</th>
              <th>Dispute Ref</th>
              <th>Project</th>
              <th>Claimant</th>
              <th>Respondent</th>
              <th>Disputed Amount</th>
              <th>Status</th>
              <th>Arbiter</th>
              <th>SLA</th>
              <th>Filed</th>
            </tr>
          </thead>
          <tbody>

            <!-- DISPUTE 1 — Critical / Escalated -->
            <tr onclick="window.location='admin-dispute.html'" data-status="escalated">
              <td><span class="priority-dot critical"></span></td>
              <td>
                <div style="font-weight:700;color:var(--ink);">DSP-NX-3799</div>
              </td>
              <td>
                <div style="font-weight:600;font-size:.8125rem;">SaaS Compliance Audit</div>
                <div class="text-xs text-muted font-mono">NX-2025-3799</div>
              </td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm" style="width:24px;height:24px;font-size:.6rem;">FC</div>
                  <span style="font-size:.8125rem;">FinCorp Egypt</span>
                </div>
              </td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm" style="width:24px;height:24px;font-size:.6rem;">KA</div>
                  <span style="font-size:.8125rem;">Karim Al-Azzawi</span>
                </div>
              </td>
              <td><span class="font-mono font-bold">$5,200</span></td>
              <td><span class="status-pill escalated">⚠ Escalated</span></td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm"
                    style="width:24px;height:24px;font-size:.6rem;background:var(--ink);color:var(--gold);">KF</div>
                  <span style="font-size:.8125rem;">K. Farouk</span>
                </div>
              </td>
              <td>
                <div class="sla-bar">
                  <div class="sla-bar-fill red" style="width:96%;"></div>
                </div>
                <span class="font-mono text-xs" style="color:var(--rust);">69h/72h</span>
              </td>
              <td class="font-mono text-xs text-muted">Apr 10</td>
            </tr>

            <!-- DISPUTE 2 — Under Review -->
            <tr onclick="window.location='admin-dispute.html'" data-status="review">
              <td><span class="priority-dot high"></span></td>
              <td>
                <div style="font-weight:700;color:var(--ink);">DSP-NX-3801</div>
              </td>
              <td>
                <div style="font-weight:600;font-size:.8125rem;">Annual Report — DE/EN Translation</div>
                <div class="text-xs text-muted font-mono">NX-2025-3801</div>
              </td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm" style="width:24px;height:24px;font-size:.6rem;">FC</div>
                  <span style="font-size:.8125rem;">FinCorp Egypt</span>
                </div>
              </td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm" style="width:24px;height:24px;font-size:.6rem;">LB</div>
                  <span style="font-size:.8125rem;">Lena Bergmann</span>
                </div>
              </td>
              <td><span class="font-mono font-bold">$4,800</span></td>
              <td><span class="status-pill review">Under Review</span></td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm"
                    style="width:24px;height:24px;font-size:.6rem;background:var(--ink);color:var(--gold);">MH</div>
                  <span style="font-size:.8125rem;">M. Hassan</span>
                </div>
              </td>
              <td>
                <div class="sla-bar">
                  <div class="sla-bar-fill yellow" style="width:58%;"></div>
                </div>
                <span class="font-mono text-xs">42h/72h</span>
              </td>
              <td class="font-mono text-xs text-muted">Apr 13</td>
            </tr>

            <!-- DISPUTE 3 — Evidence Phase -->
            <tr onclick="window.location='admin-dispute.html'" data-status="evidence">
              <td><span class="priority-dot medium"></span></td>
              <td>
                <div style="font-weight:700;color:var(--ink);">DSP-NX-3845</div>
              </td>
              <td>
                <div style="font-weight:600;font-size:.8125rem;">Predictive Model — Healthcare</div>
                <div class="text-xs text-muted font-mono">NX-2025-3845</div>
              </td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm" style="width:24px;height:24px;font-size:.6rem;">MG</div>
                  <span style="font-size:.8125rem;">MedGroup KSA</span>
                </div>
              </td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm" style="width:24px;height:24px;font-size:.6rem;">DR</div>
                  <span style="font-size:.8125rem;">Dr. Rania Khalil</span>
                </div>
              </td>
              <td><span class="font-mono font-bold">$6,200</span></td>
              <td><span class="status-pill evidence">Evidence Phase</span></td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm"
                    style="width:24px;height:24px;font-size:.6rem;background:var(--ink);color:var(--gold);">SA</div>
                  <span style="font-size:.8125rem;">S. Al-Rashid</span>
                </div>
              </td>
              <td>
                <div class="sla-bar">
                  <div class="sla-bar-fill green" style="width:25%;"></div>
                </div>
                <span class="font-mono text-xs" style="color:var(--sage);">18h/72h</span>
              </td>
              <td class="font-mono text-xs text-muted">Apr 15</td>
            </tr>

            <!-- DISPUTE 4 — Under Review -->
            <tr onclick="window.location='admin-dispute.html'" data-status="review">
              <td><span class="priority-dot low"></span></td>
              <td>
                <div style="font-weight:700;color:var(--ink);">DSP-NX-3852</div>
              </td>
              <td>
                <div style="font-weight:600;font-size:.8125rem;">Cybersecurity Penetration Test</div>
                <div class="text-xs text-muted font-mono">NX-2025-3852</div>
              </td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm" style="width:24px;height:24px;font-size:.6rem;">TI</div>
                  <span style="font-size:.8125rem;">TechInfra UAE</span>
                </div>
              </td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm" style="width:24px;height:24px;font-size:.6rem;">HA</div>
                  <span style="font-size:.8125rem;">Hassan Ali</span>
                </div>
              </td>
              <td><span class="font-mono font-bold">$2,200</span></td>
              <td><span class="status-pill review">Under Review</span></td>
              <td>
                <div class="arbiter-tag">
                  <div class="avatar avatar-sm"
                    style="width:24px;height:24px;font-size:.6rem;background:var(--ink);color:var(--gold);">KF</div>
                  <span style="font-size:.8125rem;">K. Farouk</span>
                </div>
              </td>
              <td>
                <div class="sla-bar">
                  <div class="sla-bar-fill green" style="width:12%;"></div>
                </div>
                <span class="font-mono text-xs" style="color:var(--sage);">8h/72h</span>
              </td>
              <td class="font-mono text-xs text-muted">Apr 16</td>
            </tr>

          </tbody>
        </table>
      </div>

    </main>
  </div>

  <script>
    function filterDisputes(status, el) {
      // Update active chip
      document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
      el.classList.add('active');

      // Filter rows
      const rows = document.querySelectorAll('.dispute-table tbody tr');
      rows.forEach(row => {
        if (status === 'all') {
          row.style.display = '';
        } else {
          row.style.display = row.dataset.status === status ? '' : 'none';
        }
      });
    }
  </script>

</body>

</html>