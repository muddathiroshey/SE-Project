<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — Nexus</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .admin-topbar {
      background: var(--ink);
      color: var(--ivory);
      padding: 6px 0;
      font-size: .75rem;
      font-family: var(--font-mono);
      letter-spacing: .06em;
    }

    .admin-topbar .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .admin-topbar span {
      color: rgba(247, 244, 239, .5);
    }

    .admin-topbar strong {
      color: var(--gold);
    }

    .admin-shell {
      display: flex;
      min-height: calc(100vh - var(--nav-h) - 30px);
    }

    .admin-sidebar {
      width: 220px;
      background: var(--ink);
      flex-shrink: 0;
      padding: 24px 0;
    }

    .admin-sidebar-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 20px;
      font-size: .8125rem;
      color: rgba(247, 244, 239, .5);
      cursor: pointer;
      transition: all .12s;
      border-left: 2px solid transparent;
    }

    .admin-sidebar-link:hover,
    .admin-sidebar-link.active {
      color: var(--ivory);
      background: rgba(247, 244, 239, .06);
      border-left-color: var(--gold);
    }

    .admin-sidebar-link.active {
      color: var(--gold);
    }

    .admin-sidebar-section {
      font-size: .6rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: rgba(247, 244, 239, .25);
      font-weight: 700;
      padding: 16px 20px 6px;
    }

    .admin-main {
      flex: 1;
      padding: 32px 40px;
      min-width: 0;
      background: var(--ivory);
    }

    .role-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 10px;
      border-radius: 2px;
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .07em;
      text-transform: uppercase;
      font-family: var(--font-body);
      white-space: nowrap;
    }

    .rb-super {
      background: var(--ink);
      color: var(--gold);
      border: 1px solid rgba(201, 168, 76, .3);
    }

    .health-metric {
      background: var(--ivory-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 20px 24px;
    }

    .health-metric::before {
      display: none;
    }

    .health-metric.green::before,
    .health-metric.red::before {
      display: none;
    }

    .sparkline {
      display: none;
    }

    .spark-bar {
      display: none;
    }

    .spark-bar.highlight {
      display: none;
    }

    .niche-row {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
    }

    .niche-row:last-child {
      border-bottom: none;
    }

    .niche-bar-track {
      flex: 1;
      background: var(--ivory-deep);
      border-radius: 2px;
      height: 6px;
      overflow: hidden;
    }

    .niche-bar-fill {
      height: 100%;
      border-radius: 2px;
      background: var(--gold);
    }

    .niche-bar-fill.top {
      background: var(--ink);
    }

    .alert-item {
      display: flex;
      gap: 14px;
      align-items: flex-start;
      padding: 12px 16px;
      border-radius: var(--radius-sm);
      margin-bottom: 8px;
      font-size: .875rem;
    }

    .alert-item.warn {
      background: #FDF3E0;
      border: 1px solid #F0D899;
    }

    .alert-item.danger {
      background: #FBEAE7;
      border: 1px solid #F0C4BC;
    }

    .alert-item.info {
      background: #EBF3FA;
      border: 1px solid #B8D8F0;
    }

    .alert-icon {
      font-size: 1rem;
      flex-shrink: 0;
    }

    .user-flag-row {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
      font-size: .875rem;
    }

    .user-flag-row:last-child {
      border-bottom: none;
    }

    .rbac-row {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
      font-size: .875rem;
    }

    .rbac-row:last-child {
      border-bottom: none;
    }

    .rbac-perms {
      display: flex;
      gap: 4px;
      flex-wrap: wrap;
    }

    .perm-chip {
      padding: 2px 8px;
      border-radius: 2px;
      font-size: .6rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .06em;
      background: var(--ivory-deep);
      border: 1px solid var(--border);
      color: var(--ink-mid);
    }

    .perm-chip.on {
      background: var(--gold-pale);
      border-color: var(--gold-light);
      color: #7A5C10;
    }

    .digest-preview {
      background: var(--ivory-deep);
      border: 1px dashed var(--border-dark);
      border-radius: var(--radius-md);
      padding: 20px;
      font-size: .8125rem;
    }

    .digest-header {
      background: var(--ink);
      color: var(--ivory);
      border-radius: var(--radius-sm);
      padding: 10px 16px;
      font-family: var(--font-display);
      font-size: 1rem;
      margin-bottom: 12px;
    }
  </style>
</head>

<body>

  <nav class="topnav" style="background:var(--ink);border-bottom:1px solid rgba(247,244,239,.1);">
    <div class="container" style="max-width:100%;padding:0 32px;">
      <a class="topnav-logo" href="/admin" style="color:var(--ivory);">Nexus<span
          style="color:var(--gold);">.</span></a>
      <div class="topnav-links">
        <a href="/admin" style="color:rgba(247,244,239,.6);">Dashboard</a>
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
      <a class="admin-sidebar-link active" href="/admin">📊 Health Dashboard</a>

      <div class="admin-sidebar-section">Marketplace</div>
      <a class="admin-sidebar-link" href="#">👤 Users</a>

      <div class="admin-sidebar-section">Disputes</div>
      <a class="admin-sidebar-link" href="/dispute">⚖️ Active Disputes <span class="notif-count"
          style="margin-left:auto;background:var(--rust);">4</span></a>

      <div class="admin-sidebar-section">Verifications</div>
      <a class="admin-sidebar-link" href="#">🛡 KYC Queue</a>

      <div class="admin-sidebar-section">Sanctions</div>
      <a class="admin-sidebar-link" href="#">⚠️ User Sanctions</a>

      <div class="admin-sidebar-section">Support</div>
      <a class="admin-sidebar-link" href="/chat">💬 Chat Support</a>
    </aside>

    <!-- MAIN -->
    <main class="admin-main">

      <div class="flex justify-between items-start mb-28">
        <div>
          <div class="breadcrumb"
            style="font-family:var(--font-mono);font-size:.72rem;color:var(--ink-muted);margin-bottom:8px;">
            Admin Console <span style="margin:0 6px;color:var(--ink-faint);">›</span> System <span
              style="margin:0 6px;color:var(--ink-faint);">›</span> Admin Team
          </div>
          <h2 style="font-family:var(--font-display);font-size:1.6rem;font-weight:500;margin-bottom:6px;">Admin Team
            Management</h2>
          <p style="font-size:.875rem;color:var(--ink-muted);">Create, configure, and manage administrator accounts.
            Only Super Admins can access this page.</p>
        </div>
        <button class="btn btn-primary" onclick="scrollToCreate()">+ Create Admin Account</button>
      </div>

      <!-- HEALTH METRICS -->
      <div class="grid-4 mb-32">
        <div class="health-metric green">
          <div class="stat-value" style="font-size:1.8rem;">847</div>
          <div class="stat-label">Active Contracts</div>
          <div class="stat-delta up mt-4">↑ +23 today</div>
          <div class="sparkline">
            <div class="spark-bar" style="height:40%;"></div>
            <div class="spark-bar" style="height:55%;"></div>
            <div class="spark-bar" style="height:48%;"></div>
            <div class="spark-bar" style="height:70%;"></div>
            <div class="spark-bar" style="height:62%;"></div>
            <div class="spark-bar" style="height:80%;"></div>
            <div class="spark-bar highlight" style="height:90%;"></div>
          </div>
        </div>
        <div class="health-metric">
          <div class="stat-value" style="font-size:1.8rem;">$2.4M</div>
          <div class="stat-label">Total Escrowed Value</div>
          <div class="stat-delta up mt-4">↑ $84K since yesterday</div>
          <div class="sparkline">
            <div class="spark-bar" style="height:50%;"></div>
            <div class="spark-bar" style="height:60%;"></div>
            <div class="spark-bar" style="height:55%;"></div>
            <div class="spark-bar" style="height:72%;"></div>
            <div class="spark-bar" style="height:80%;"></div>
            <div class="spark-bar" style="height:85%;"></div>
            <div class="spark-bar highlight" style="height:95%;"></div>
          </div>
        </div>
        <div class="health-metric red">
          <div class="stat-value" style="font-size:1.8rem;">4</div>
          <div class="stat-label">Active Disputes</div>
          <div class="stat-delta down mt-4">↑ +1 today · 2.1% of active</div>
          <div class="sparkline">
            <div class="spark-bar" style="height:30%;background:var(--rust);opacity:.5;"></div>
            <div class="spark-bar" style="height:20%;background:var(--rust);opacity:.5;"></div>
            <div class="spark-bar" style="height:40%;background:var(--rust);opacity:.5;"></div>
            <div class="spark-bar" style="height:25%;background:var(--rust);opacity:.5;"></div>
            <div class="spark-bar" style="height:35%;background:var(--rust);opacity:.5;"></div>
            <div class="spark-bar" style="height:20%;background:var(--rust);opacity:.5;"></div>
            <div class="spark-bar highlight" style="height:45%;background:var(--rust);"></div>
          </div>
        </div>
        <div class="health-metric green">
          <div class="stat-value" style="font-size:1.8rem;">98.1%</div>
          <div class="stat-label">Dispute Resolution Rate</div>
          <div class="stat-delta up mt-4">↑ Above 95% SLA target</div>
          <div class="sparkline">
            <div class="spark-bar" style="height:88%;background:#C5DBC2;"></div>
            <div class="spark-bar" style="height:92%;background:#C5DBC2;"></div>
            <div class="spark-bar" style="height:90%;background:#C5DBC2;"></div>
            <div class="spark-bar" style="height:95%;background:#C5DBC2;"></div>
            <div class="spark-bar" style="height:93%;background:#C5DBC2;"></div>
            <div class="spark-bar" style="height:97%;background:#C5DBC2;"></div>
            <div class="spark-bar highlight" style="height:98%;background:var(--sage);"></div>
          </div>
        </div>
      </div>

      <!-- SECONDARY STATS -->
      <div class="grid-4 mb-32">
        <div class="stat-card">
          <div class="stat-value" style="font-size:1.4rem;">12,420</div>
          <div class="stat-label">Verified Specialists</div>
          <div class="stat-delta up mt-4">↑ 42 new this week</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" style="font-size:1.4rem;">214</div>
          <div class="stat-label">KYC Queue</div>
          <div class="stat-delta mt-4" style="color:var(--ink-muted);">Avg. 2.4 day process</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" style="font-size:1.4rem;">$740K</div>
          <div class="stat-label">Released This Month</div>
          <div class="stat-delta up mt-4">↑ 18% vs March</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" style="font-size:1.4rem;">4.92</div>
          <div class="stat-label">Avg. Platform Rating</div>
          <div class="stat-delta up mt-4">↑ 0.02 this quarter</div>
        </div>
      </div>

      <!-- ALERTS -->
      <div class="mb-32">
        <h3 class="mb-12">System Alerts</h3>
        <div class="alert-item danger"><span class="alert-icon">🔴</span>
          <div><strong>Dispute SLA at risk:</strong> DSP-NX-3799 has been unresolved for 69h. Arbiter K. Farouk is
            approaching 72h verdict deadline. Escalation required.</div><button class="btn btn-sm btn-danger"
            style="margin-left:auto;white-space:nowrap;">Escalate</button>
        </div>
        <div class="alert-item warn"><span class="alert-icon">🟡</span>
          <div><strong>KYC backlog:</strong> 214 pending verifications exceeds 200-item threshold. Consider assigning
            additional verification staff.</div><button class="btn btn-sm btn-outline"
            style="margin-left:auto;white-space:nowrap;">Review Queue</button>
        </div>
        <div class="alert-item info"><span class="alert-icon">🔵</span>
          <div><strong>Weekly digest scheduled:</strong> Next send is Apr 17, 08:00 GMT. 6,240 specialists will receive
            personalized job recommendations.</div>
        </div>
      </div>

      <div class="grid-2 mb-32">

        <!-- NICHE PERFORMANCE -->
        <div class="card">
          <h3 class="mb-4">Niche Performance</h3>
          <p class="mb-16 text-sm text-muted">Active contracts and growth rate by discipline.</p>
          <div class="niche-row">
            <div style="width:140px;font-size:.875rem;font-weight:700;">Data Science</div>
            <div class="niche-bar-track">
              <div class="niche-bar-fill top" style="width:88%;"></div>
            </div>
            <div style="font-family:var(--font-mono);font-size:.8125rem;width:40px;text-align:right;">312</div>
            <span class="stat-delta up" style="width:40px;text-align:right;">+22%</span>
          </div>
          <div class="niche-row">
            <div style="width:140px;font-size:.875rem;font-weight:700;">Legal Consulting</div>
            <div class="niche-bar-track">
              <div class="niche-bar-fill" style="width:65%;"></div>
            </div>
            <div style="font-family:var(--font-mono);font-size:.8125rem;width:40px;text-align:right;">228</div>
            <span class="stat-delta up" style="width:40px;text-align:right;">+14%</span>
          </div>
          <div class="niche-row">
            <div style="width:140px;font-size:.875rem;font-weight:700;">Financial Modelling</div>
            <div class="niche-bar-track">
              <div class="niche-bar-fill" style="width:52%;"></div>
            </div>
            <div style="font-family:var(--font-mono);font-size:.8125rem;width:40px;text-align:right;">184</div>
            <span class="stat-delta up" style="width:40px;text-align:right;">+9%</span>
          </div>
          <div class="niche-row">
            <div style="width:140px;font-size:.875rem;font-weight:700;">Tech Translation</div>
            <div class="niche-bar-track">
              <div class="niche-bar-fill" style="width:38%;"></div>
            </div>
            <div style="font-family:var(--font-mono);font-size:.8125rem;width:40px;text-align:right;">134</div>
            <span class="stat-delta up" style="width:40px;text-align:right;">+6%</span>
          </div>
          <div class="niche-row">
            <div style="width:140px;font-size:.875rem;font-weight:700;">Cybersecurity</div>
            <div class="niche-bar-track">
              <div class="niche-bar-fill" style="width:25%;"></div>
            </div>
            <div style="font-family:var(--font-mono);font-size:.8125rem;width:40px;text-align:right;">88</div>
            <span class="stat-delta up" style="width:40px;text-align:right;">+18%</span>
          </div>
          <div class="niche-row">
            <div style="width:140px;font-size:.875rem;font-weight:700;">Biomedical</div>
            <div class="niche-bar-track">
              <div class="niche-bar-fill" style="width:18%;"></div>
            </div>
            <div style="font-family:var(--font-mono);font-size:.8125rem;width:40px;text-align:right;">62</div>
            <span class="stat-delta up" style="width:40px;text-align:right;">+31%</span>
          </div>
        </div>

        <!-- USER SANCTIONS -->
        <div class="card">
          <h3 class="mb-4">Active User Sanctions</h3>
          <p class="mb-16 text-sm text-muted">Users currently under penalty or review.</p>
          <div class="user-flag-row">
            <div class="avatar avatar-sm">LB</div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.875rem;">Lena Bergmann</div>
              <div class="text-xs text-muted">Technical Translation</div>
            </div>
            <span class="sanction-pill sanction-warn">⚠ Warning</span>
            <button class="btn btn-ghost btn-sm">Review</button>
          </div>
          <div class="user-flag-row">
            <div class="avatar avatar-sm">TM</div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.875rem;">Thomas Müller</div>
              <div class="text-xs text-muted">Data Science</div>
            </div>
            <span class="sanction-pill sanction-limit">⛔ Limited Ban</span>
            <button class="btn btn-ghost btn-sm">Review</button>
          </div>
          <div class="user-flag-row">
            <div class="avatar avatar-sm">RK</div>
            <div style="flex:1;">
              <div style="font-weight:700;font-size:.875rem;">Rashid Khalil</div>
              <div class="text-xs text-muted">Financial Modelling</div>
            </div>
            <span class="sanction-pill sanction-ban">⛔ Permanent Ban</span>
            <button class="btn btn-ghost btn-sm">Review</button>
          </div>
        </div>

      </div>
    </main>
  </div>

</body>

</html>