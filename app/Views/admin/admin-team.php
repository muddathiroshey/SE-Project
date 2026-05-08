<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/admin/team.php
    Template: Admin Team Management
    Role:     super_admin (only super admins can access this page)
    Route:    /admin/team
    ============================================================
    PHP Data contract (from AdminTeamController::index()):
      $admins         — AdminUser[] (all non-deleted admin accounts)
      $currentAdmin   — authenticated super admin
      $roleDefinitions— RoleDefinition[] with permissions per role
      $auditLog       — recent AdminAuditLog[] (last 20 entries)
      $stats          — [ total, by_role[], active_now, created_this_month ]
    Each AdminUser:
      $a['id'], $a['name'], $a['email'], $a['role'],
      $a['status'],    — active | suspended | pending_setup
      $a['created_at'], $a['last_active'],
      $a['created_by'], $a['permissions_override'],
      $a['avatar_initials']
    Roles:
      super_admin      — Full system access, can manage other admins
      dispute_mediator — Dispute arbitration, evidence review, verdict issuing
      credentials_review— Credential verification queue, approve/reject credentials
      tech_support     — User account issues, bug reports, flagged content
      (future: finance_auditor, content_moderator)
    ============================================================
-->
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Team Management — Nexus</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin-team.css">
</head>

<body>


  <!-- ══════════ TOPNAV ══════════ -->
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

  <!-- ══════════ ADMIN SHELL ══════════ -->
  <div class="admin-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="admin-sidebar">
      <div class="admin-sidebar-section">Overview</div>
      <a class="admin-sidebar-link" href="admin-dashboard.html">📊 Health Dashboard</a>

      <div class="admin-sidebar-section">Marketplace</div>
      <a class="admin-sidebar-link active" href="admin-team.html">👤 Users</a>

      <div class="admin-sidebar-section">Disputes</div>
      <a class="admin-sidebar-link" href="open-disputes.html">⚖️ Active Disputes <span class="notif-count"
          style="margin-left:auto;background:var(--rust);">4</span></a>

      <div class="admin-sidebar-section">Verifications</div>
      <a class="admin-sidebar-link" href="admin-kyc.html">🛡 KYC Queue</a>

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

      <!-- STAT STRIP -->
      <div class="stat-strip">
        <!-- PHP: $stats object -->
        <div class="sc">
          <div class="sc-val">8</div>
          <div class="sc-lbl">Total Admins</div>
        </div>
        <div class="sc">
          <div class="sc-val" style="color:var(--sage);">6</div>
          <div class="sc-lbl">Active</div>
        </div>
        <div class="sc">
          <div class="sc-val" style="color:var(--gold);">1</div>
          <div class="sc-lbl">Pending Setup</div>
        </div>
        <div class="sc">
          <div class="sc-val" style="color:var(--rust);">1</div>
          <div class="sc-lbl">Suspended</div>
        </div>
      </div>

      <!-- ROLE DEFINITIONS -->
      <div class="flex justify-between items-center mb-14">
        <div
          style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);font-family:var(--font-body);">
          Role Definitions &amp; Permission Sets</div>
        <button class="btn btn-ghost btn-sm" style="font-size:.75rem;" onclick="toggleRoleDefs()">Show / Hide</button>
      </div>
      <div class="role-def-grid" id="role-def-grid">

        <div class="role-def-card super-admin-card">
          <div class="rdc-name"><span class="role-badge rb-super">Super Admin</span></div>
          <p class="rdc-desc">Full platform access. Can create, edit, and delete all admin accounts and roles. Has
            access to all system settings, financial data, and audit logs. Only one account should hold this role at a
            time in production.</p>
          <div class="perm-grid">
            <span class="pchip high">All Permissions</span>
            <span class="pchip on">Create Admins</span>
            <span class="pchip on">Delete Accounts</span>
            <span class="pchip on">Platform Config</span>
            <span class="pchip on">Finance Access</span>
            <span class="pchip on">Audit Logs</span>
            <span class="pchip on">Override Verdicts</span>
          </div>
        </div>

        <div class="role-def-card">
          <div class="rdc-name"><span class="role-badge rb-mediator">Dispute Mediator</span></div>
          <p class="rdc-desc">Reviews dispute cases, examines evidence packages, communicates with parties, and issues
            binding verdicts. Can apply sanctions and escalate to Super Admin. Cannot access financial dashboards or
            admin user management.</p>
          <div class="perm-grid">
            <span class="pchip on">View Disputes</span>
            <span class="pchip on">Issue Verdicts</span>
            <span class="pchip on">Evidence Access</span>
            <span class="pchip on">Apply Sanctions</span>
            <span class="pchip on">Message Parties</span>
            <span class="pchip on">Safe Room Access</span>
            <span class="pchip">Finance Access</span>
            <span class="pchip">Admin Mgmt</span>
          </div>
        </div>

        <div class="role-def-card">
          <div class="rdc-name"><span class="role-badge rb-creds">Credentials Review</span></div>
          <p class="rdc-desc">Processes the credential verification queue — reviews submitted documents, validates
            against issuing institutions, approves or rejects credentials, and flags anomalies for Super Admin review.
            Cannot issue verdicts or access billing.</p>
          <div class="perm-grid">
            <span class="pchip on">Credentials Queue</span>
            <span class="pchip on">Approve Creds</span>
            <span class="pchip on">Reject Creds</span>
            <span class="pchip on">KYC Review</span>
            <span class="pchip on">Specialist Profiles</span>
            <span class="pchip on">Flag for Review</span>
            <span class="pchip">Verdicts</span>
            <span class="pchip">Finance Access</span>
          </div>
        </div>

        <div class="role-def-card">
          <div class="rdc-name"><span class="role-badge rb-support">Tech Support</span></div>
          <p class="rdc-desc">Handles user-reported issues, bug escalations, and account recovery requests. Can view
            user accounts and impersonate for debugging (logged). Cannot modify contracts, issue verdicts, or access the
            credentials queue.</p>
          <div class="perm-grid">
            <span class="pchip on">View User Accounts</span>
            <span class="pchip on">Account Recovery</span>
            <span class="pchip on">View Bug Reports</span>
            <span class="pchip on">Impersonate (logged)</span>

            <span class="pchip">Modify Contracts</span>
            <span class="pchip">Credentials Queue</span>
            <span class="pchip">Verdicts</span>
          </div>
        </div>

      </div>

      <!-- ══════════ ADMIN TABLE ══════════ -->
      <div class="flex justify-between items-center mb-14">
        <div
          style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);font-family:var(--font-body);">
          Current Admin Accounts</div>
        <div class="flex gap-10 items-center">
          <div class="admin-search-wrap">
            <span class="admin-search-icon">🔍</span>
            <input type="text" class="admin-search" placeholder="Search by name or email…"
              oninput="filterAdmins(this.value)">
          </div>
          <select class="form-control" style="width:160px;padding:6px 10px;font-size:.8125rem;"
            onchange="filterRole(this.value)">
            <option value="">All Roles</option>
            <option value="super_admin">Super Admin</option>
            <option value="dispute_mediator">Dispute Mediator</option>
            <option value="credentials_review">Credentials Review</option>
            <option value="tech_support">Tech Support</option>
          </select>
          <select class="form-control" style="width:130px;padding:6px 10px;font-size:.8125rem;">
            <option>All Statuses</option>
            <option>Active</option>
            <option>Suspended</option>
            <option>Pending Setup</option>
          </select>
        </div>
      </div>

      <!-- ── GROUP: SUPER ADMIN ── -->
      <div class="role-group">
        <div class="role-section-head">
          <span class="role-badge rb-super" style="font-size:.6rem;">Super Admin</span>
          <!-- PHP: count filter -->
          <span style="color:rgba(247,244,239,.4);">1 account</span>
        </div>
        <table class="admin-table role-group-table">
          <thead>
            <tr>
              <th>Admin</th>
              <th>Status</th>
              <th>Last Active</th>
              <th>Created</th>
              <th>Created By</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- PHP: foreach($admins->where('role','super_admin') as $a): -->
            <tr class="self" data-role="super_admin" data-name="omar hassan" data-email="omar@nexus.io">
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm"
                    style="background:var(--gold);color:var(--ink);font-size:.75rem;font-weight:700;flex-shrink:0;">OH
                  </div>
                  <div>
                    <div style="font-weight:700;">Omar Hassan <span
                        style="font-size:.7rem;background:var(--ivory-deep);border:1px solid var(--border);border-radius:2px;padding:1px 6px;color:var(--ink-muted);font-family:var(--font-mono);margin-left:4px;">You</span>
                    </div>
                    <div class="text-xs text-muted">omar@nexus.io</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-6">
                  <div class="status-dot sd-active"></div> Active
                </div>
              </td>
              <td class="font-mono text-xs">Now</td>
              <td class="font-mono text-xs text-muted">Jan 12, 2024</td>
              <td class="text-xs text-muted">System</td>
              <td style="text-align:right;">
                <button class="btn btn-ghost btn-sm" style="font-size:.75rem;color:var(--ink-muted);cursor:not-allowed;"
                  disabled title="Cannot edit your own account from this panel">Edit</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ── GROUP: DISPUTE MEDIATOR ── -->
      <div class="role-group">
        <div class="role-section-head">
          <span class="role-badge rb-mediator" style="font-size:.6rem;">Dispute Mediator</span>
          <span style="color:rgba(247,244,239,.4);">2 accounts</span>
        </div>
        <table class="admin-table role-group-table">
          <thead>
            <tr>
              <th>Admin</th>
              <th>Status</th>
              <th>Last Active</th>
              <th>Created</th>
              <th>Created By</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr data-role="dispute_mediator" data-name="layla ibrahim" data-email="layla@nexus.io">
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">LI</div>
                  <div>
                    <div style="font-weight:700;">Layla Ibrahim</div>
                    <div class="text-xs text-muted">layla@nexus.io</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-6">
                  <div class="status-dot sd-active"></div> Active
                </div>
              </td>
              <td class="font-mono text-xs">2h ago</td>
              <td class="font-mono text-xs text-muted">Mar 4, 2025</td>
              <td class="text-xs text-muted">Omar H.</td>
              <td style="text-align:right;">
                <div class="row-actions" style="justify-content:flex-end;">
                  <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                    onclick="openEditModal({id:2,name:'Layla Ibrahim',email:'layla@nexus.io',role:'dispute_mediator',status:'active',twofa:true})">✎
                    Edit Role</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;"
                    onclick="toggleSuspend(this,'Layla Ibrahim')">⏸ Suspend</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;color:var(--rust);"
                    onclick="openDeleteModal({id:2,name:'Layla Ibrahim'})">🗑 Delete</button>
                </div>
              </td>
            </tr>
            <tr data-role="dispute_mediator" data-name="khalid mansour" data-email="khalid@nexus.io">
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">KM</div>
                  <div>
                    <div style="font-weight:700;">Khalid Mansour</div>
                    <div class="text-xs text-muted">khalid@nexus.io</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-6">
                  <div class="status-dot sd-active"></div> Active
                </div>
              </td>
              <td class="font-mono text-xs">1d ago</td>
              <td class="font-mono text-xs text-muted">Mar 4, 2025</td>
              <td class="text-xs text-muted">Omar H.</td>
              <td style="text-align:right;">
                <div class="row-actions" style="justify-content:flex-end;">
                  <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                    onclick="openEditModal({id:3,name:'Khalid Mansour',email:'khalid@nexus.io',role:'dispute_mediator',status:'active',twofa:true})">✎
                    Edit Role</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;"
                    onclick="toggleSuspend(this,'Khalid Mansour')">⏸ Suspend</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;color:var(--rust);"
                    onclick="openDeleteModal({id:3,name:'Khalid Mansour'})">🗑 Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ── GROUP: CREDENTIALS REVIEW ── -->
      <div class="role-group">
        <div class="role-section-head">
          <span class="role-badge rb-creds" style="font-size:.6rem;">Credentials Review</span>
          <span style="color:rgba(247,244,239,.4);">3 accounts · 1 pending</span>
        </div>
        <table class="admin-table role-group-table">
          <thead>
            <tr>
              <th>Admin</th>
              <th>Status</th>
              <th>Last Active</th>
              <th>Created</th>
              <th>Created By</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr data-role="credentials_review" data-name="sara eissa" data-email="sara@nexus.io">
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">SE</div>
                  <div>
                    <div style="font-weight:700;">Sara Eissa</div>
                    <div class="text-xs text-muted">sara@nexus.io</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-6">
                  <div class="status-dot sd-active"></div> Active
                </div>
              </td>
              <td class="font-mono text-xs">4h ago</td>
              <td class="font-mono text-xs text-muted">Feb 10, 2025</td>
              <td class="text-xs text-muted">Omar H.</td>
              <td style="text-align:right;">
                <div class="row-actions" style="justify-content:flex-end;">
                  <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                    onclick="openEditModal({id:4,name:'Sara Eissa',email:'sara@nexus.io',role:'credentials_review',status:'active',twofa:true})">✎
                    Edit Role</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;"
                    onclick="toggleSuspend(this,'Sara Eissa')">⏸ Suspend</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;color:var(--rust);"
                    onclick="openDeleteModal({id:4,name:'Sara Eissa'})">🗑 Delete</button>
                </div>
              </td>
            </tr>
            <tr data-role="credentials_review" data-name="ahmed galal" data-email="ahmed.g@nexus.io">
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">AG</div>
                  <div>
                    <div style="font-weight:700;">Ahmed Galal</div>
                    <div class="text-xs text-muted">ahmed.g@nexus.io</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-6">
                  <div class="status-dot sd-active"></div> Active
                </div>
              </td>
              <td class="font-mono text-xs">6h ago</td>
              <td class="font-mono text-xs text-muted">Feb 10, 2025</td>
              <td class="text-xs text-muted">Omar H.</td>
              <td style="text-align:right;">
                <div class="row-actions" style="justify-content:flex-end;">
                  <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                    onclick="openEditModal({id:5,name:'Ahmed Galal',email:'ahmed.g@nexus.io',role:'credentials_review',status:'active',twofa:true})">✎
                    Edit Role</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;"
                    onclick="toggleSuspend(this,'Ahmed Galal')">⏸ Suspend</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;color:var(--rust);"
                    onclick="openDeleteModal({id:5,name:'Ahmed Galal'})">🗑 Delete</button>
                </div>
              </td>
            </tr>
            <!-- PENDING SETUP -->
            <tr data-role="credentials_review" data-name="mona farouk" data-email="mona@nexus.io">
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm"
                    style="background:var(--ivory-deep);border:1.5px dashed var(--border-dark);color:var(--ink-faint);flex-shrink:0;">
                    MF</div>
                  <div>
                    <div style="font-weight:700;color:var(--ink-muted);">Mona Farouk</div>
                    <div class="text-xs text-muted">mona@nexus.io</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-6">
                  <div class="status-dot sd-pending"></div>
                  <span style="color:var(--gold);font-weight:600;">Pending Setup</span>
                </div>
                <div class="text-xs text-muted mt-2">Invite sent Apr 14 · Awaiting first login</div>
              </td>

              <td class="font-mono text-xs text-muted">Never</td>
              <td class="font-mono text-xs text-muted">Apr 14, 2025</td>
              <td class="text-xs text-muted">Omar H.</td>
              <td style="text-align:right;">
                <div class="row-actions" style="justify-content:flex-end;">
                  <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                    onclick="resendInvite('Mona Farouk')">↺ Resend Invite</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;color:var(--rust);"
                    onclick="openDeleteModal({id:6,name:'Mona Farouk'})">🗑 Cancel</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ── GROUP: TECH SUPPORT ── -->
      <div class="role-group">
        <div class="role-section-head">
          <span class="role-badge rb-support" style="font-size:.6rem;">Tech Support</span>
          <span style="color:rgba(247,244,239,.4);">2 accounts · 1 suspended</span>
        </div>
        <table class="admin-table role-group-table">
          <thead>
            <tr>
              <th>Admin</th>
              <th>Status</th>
              <th>Last Active</th>
              <th>Created</th>
              <th>Created By</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr data-role="tech_support" data-name="hassan ali" data-email="hassan@nexus.io">
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;">HA</div>
                  <div>
                    <div style="font-weight:700;">Hassan Ali</div>
                    <div class="text-xs text-muted">hassan@nexus.io</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-6">
                  <div class="status-dot sd-active"></div> Active
                </div>
              </td>
              <td class="font-mono text-xs">3h ago</td>
              <td class="font-mono text-xs text-muted">Jan 20, 2025</td>
              <td class="text-xs text-muted">Omar H.</td>
              <td style="text-align:right;">
                <div class="row-actions" style="justify-content:flex-end;">
                  <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                    onclick="openEditModal({id:7,name:'Hassan Ali',email:'hassan@nexus.io',role:'tech_support',status:'active',twofa:true})">✎
                    Edit Role</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;"
                    onclick="toggleSuspend(this,'Hassan Ali')">⏸ Suspend</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;color:var(--rust);"
                    onclick="openDeleteModal({id:7,name:'Hassan Ali'})">🗑 Delete</button>
                </div>
              </td>
            </tr>
            <!-- SUSPENDED -->
            <tr class="suspended" data-role="tech_support" data-name="nour abdelaziz" data-email="nour@nexus.io">
              <td>
                <div class="flex items-center gap-10">
                  <div class="avatar avatar-sm" style="flex-shrink:0;opacity:.5;">NA</div>
                  <div>
                    <div style="font-weight:700;color:var(--ink-muted);">Nour Abdelaziz</div>
                    <div class="text-xs text-muted">nour@nexus.io</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-6">
                  <div class="status-dot sd-suspended"></div>
                  <span style="color:var(--rust);font-weight:600;">Suspended</span>
                </div>
                <div class="text-xs text-muted mt-2">Since Apr 10 · Reason: Policy breach</div>
              </td>

              <td class="font-mono text-xs text-muted">Apr 10, 2025</td>
              <td class="font-mono text-xs text-muted">Nov 4, 2024</td>
              <td class="text-xs text-muted">Omar H.</td>
              <td style="text-align:right;">
                <div class="row-actions" style="justify-content:flex-end;">
                  <button class="btn btn-gold btn-sm" style="font-size:.75rem;"
                    onclick="reinstateAccount('Nour Abdelaziz',this)">↺ Reinstate</button>
                  <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
                    onclick="openEditModal({id:8,name:'Nour Abdelaziz',email:'nour@nexus.io',role:'tech_support',status:'suspended',twofa:false})">✎
                    Edit</button>
                  <button class="btn btn-ghost btn-sm" style="font-size:.75rem;color:var(--rust);"
                    onclick="openDeleteModal({id:8,name:'Nour Abdelaziz'})">🗑 Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ══════════ CREATE ADMIN FORM ══════════ -->
      <div class="create-card" id="create-form" style="margin-top:36px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:22px;">
          <div>
            <div
              style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);font-family:var(--font-body);margin-bottom:5px;">
              Create New Admin</div>
            <h3 style="font-family:var(--font-display);font-size:1.2rem;font-weight:600;margin-bottom:4px;">Add a Team
              Member</h3>
            <p style="font-size:.8125rem;color:var(--ink-muted);">An invite email will be sent immediately. The account
              activates only after first login.</p>
          </div>
          <span class="badge badge-danger badge-dot" style="font-size:.7rem;flex-shrink:0;">Super Admin Only</span>
        </div>

        <!-- PHP: <form method="POST" action="/admin/team/create" id="create-admin-form"> -->
        <!-- PHP: csrf_field() -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
          <div class="form-group" style="margin:0;">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" id="new-name" placeholder="e.g. Yasmine Mostafa"
              oninput="updatePreview()">
          </div>
          <div class="form-group" style="margin:0;">
            <label class="form-label">Work Email Address</label>
            <input type="email" class="form-control" name="email" id="new-email" placeholder="yasmine@nexus.io"
              oninput="updatePreview()">
            <p class="form-hint mt-4">Must be an <strong>@nexus.io</strong> address. External emails are not permitted.
            </p>
          </div>
        </div>

        <!-- ROLE SELECTOR -->
        <div class="form-group" style="margin-bottom:20px;">
          <label class="form-label">Role Assignment</label>
          <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;" id="role-selector">
            <!-- PHP: foreach($roleDefinitions as $role): -->
            <label class="role-option-card" id="ro-super_admin"
              style="border:1.5px solid var(--border);border-radius:var(--radius-md);padding:14px 16px;cursor:pointer;transition:all .15s;display:block;"
              onclick="selectRole('super_admin')">
              <input type="radio" name="role" value="super_admin" style="display:none;">
              <div style="margin-bottom:6px;"><span class="role-badge rb-super" style="font-size:.65rem;">Super
                  Admin</span></div>
              <p style="font-size:.75rem;color:var(--ink-muted);line-height:1.5;margin:0;">Full system access. Use with
                extreme caution.</p>
            </label>
            <label class="role-option-card" id="ro-dispute_mediator"
              style="border:1.5px solid var(--border);border-radius:var(--radius-md);padding:14px 16px;cursor:pointer;transition:all .15s;display:block;"
              onclick="selectRole('dispute_mediator')">
              <input type="radio" name="role" value="dispute_mediator" style="display:none;">
              <div style="margin-bottom:6px;"><span class="role-badge rb-mediator" style="font-size:.65rem;">Dispute
                  Mediator</span></div>
              <p style="font-size:.75rem;color:var(--ink-muted);line-height:1.5;margin:0;">Arbitration, evidence review,
                verdict issuing.</p>
            </label>
            <label class="role-option-card" id="ro-credentials_review"
              style="border:1.5px solid var(--border);border-radius:var(--radius-md);padding:14px 16px;cursor:pointer;transition:all .15s;display:block;"
              onclick="selectRole('credentials_review')">
              <input type="radio" name="role" value="credentials_review" style="display:none;">
              <div style="margin-bottom:6px;"><span class="role-badge rb-creds" style="font-size:.65rem;">Credentials
                  Review</span></div>
              <p style="font-size:.75rem;color:var(--ink-muted);line-height:1.5;margin:0;">Verify and approve specialist
                credentials &amp; KYC.</p>
            </label>
            <label class="role-option-card" id="ro-tech_support"
              style="border:1.5px solid var(--border);border-radius:var(--radius-md);padding:14px 16px;cursor:pointer;transition:all .15s;display:block;"
              onclick="selectRole('tech_support')">
              <input type="radio" name="role" value="tech_support" style="display:none;">
              <div style="margin-bottom:6px;"><span class="role-badge rb-support" style="font-size:.65rem;">Tech
                  Support</span></div>
              <p style="font-size:.75rem;color:var(--ink-muted);line-height:1.5;margin:0;">User account issues, bug
                reports, recovery.</p>
            </label>
          </div>
          <span class="field-error show" id="err-role"
            style="display:none;color:var(--rust);font-size:.8rem;margin-top:8px;">Please select a role before creating
            the account.</span>
        </div>

        <!-- SUPER ADMIN WARNING -->
        <div id="super-admin-warning"
          style="display:none;background:#FBEAE7;border:1px solid #F0C4BC;border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:20px;font-size:.8125rem;color:var(--rust);">
          ⚠ <strong>You are about to create a Super Admin account.</strong> This role has unrestricted access to all
          platform functions, financial data, and the ability to delete other admin accounts. Only proceed if this is
          intentional and authorized.
          <div style="margin-top:8px;display:flex;align-items:center;gap:8px;">
            <input type="checkbox" id="super-confirm" style="accent-color:var(--rust);">
            <label for="super-confirm" style="cursor:pointer;">I confirm this account needs Super Admin access</label>
          </div>
        </div>

        <!-- ACCESS NOTES -->
        <div class="form-group" style="margin-bottom:20px;">
          <label class="form-label">Access Notes <span class="text-muted font-mono"
              style="font-size:.7rem;font-weight:400;text-transform:none;letter-spacing:0;margin-left:6px;">Optional —
              internal only</span></label>
          <textarea class="form-control" name="notes" rows="2" id="new-notes"
            placeholder="e.g. Hired for dispute backlog — handle MENA region cases only"></textarea>
        </div>

        <div class="flex gap-12 items-center">
          <button type="submit" class="btn btn-primary btn-lg" id="create-btn" onclick="createAdmin(event)">
            ✦ Create Account
          </button>
          <button type="button" class="btn btn-outline" onclick="resetCreateForm()">Clear Form</button>
          <span class="text-xs text-muted" style="margin-left:auto;">Account inactive until first login.</span>
        </div>
        <!-- PHP: </form> -->
      </div>

      <!-- ══════════ AUDIT LOG ══════════ -->
      <div style="margin-top:40px;">
        <div class="flex justify-between items-center mb-14">
          <div
            style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);font-family:var(--font-body);">
            Recent Admin Team Activity</div>
        </div>
        <div
          style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:16px 20px;">
          <!-- PHP: foreach($auditLog as $entry): -->
          <div class="audit-row">
            <div class="audit-dot" style="background:var(--sage);"></div>
            <div style="flex:1;">
              <div class="audit-action">Admin account created — Mona Farouk (Credentials Review)</div>
              <div class="text-xs text-muted">By Omar Hassan · Invite sent to mona@nexus.io</div>
            </div>
            <span class="audit-time">Apr 14 · 11:22</span>
          </div>
          <div class="audit-row">
            <div class="audit-dot" style="background:var(--rust);"></div>
            <div style="flex:1;">
              <div class="audit-action">Admin account suspended — Nour Abdelaziz (Tech Support)</div>
              <div class="text-xs text-muted">By Omar Hassan · Reason: Policy breach · Access revoked</div>
            </div>
            <span class="audit-time">Apr 10 · 09:15</span>
          </div>
          <div class="audit-row">
            <div class="audit-dot" style="background:var(--gold);"></div>
            <div style="flex:1;">
              <div class="audit-action">Role changed — Hassan Ali: Tech Support → Tech Support (permissions reset)</div>
              <div class="text-xs text-muted">By Omar Hassan</div>
            </div>
            <span class="audit-time">Apr 8 · 14:40</span>
          </div>
          <div class="audit-row">
            <div class="audit-dot" style="background:var(--sage);"></div>
            <div style="flex:1;">
              <div class="audit-action">Admin account created — Ahmed Galal (Credentials Review)</div>
              <div class="text-xs text-muted">By Omar Hassan</div>
            </div>
            <span class="audit-time">Feb 10 · 16:02</span>
          </div>
          <div class="audit-row">
            <div class="audit-dot" style="background:var(--sage);"></div>
            <div style="flex:1;">
              <div class="audit-action">Admin account created — Sara Eissa (Credentials Review)</div>
              <div class="text-xs text-muted">By Omar Hassan</div>
            </div>
            <span class="audit-time">Feb 10 · 15:58</span>
          </div>
          <div class="audit-row">
            <div class="audit-dot" style="background:var(--rust);"></div>
            <div style="flex:1;">
              <div class="audit-action">Admin account deleted — Farid Nabil (Tech Support)</div>
              <div class="text-xs text-muted">By Omar Hassan · Account permanently removed · Audit record retained</div>
            </div>
            <span class="audit-time">Jan 28 · 10:11</span>
          </div>
        </div>
      </div>

    </main>
  </div>

  <!-- ══════════ EDIT ROLE MODAL ══════════ -->
  <div id="edit-modal" class="modal-backdrop hidden">
    <div class="modal" style="max-width:600px;">
      <div class="modal-header">
        <div>
          <h3 id="edit-modal-title">Edit Admin — Layla Ibrahim</h3>
          <p class="text-sm text-muted mt-4">Change role, update account status. All changes are logged.</p>
        </div>
        <button class="modal-close" onclick="document.getElementById('edit-modal').classList.add('hidden')">✕</button>
      </div>
      <div class="modal-body">

        <!-- ACCOUNT INFO (read-only) -->
        <div
          style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:20px;display:flex;gap:16px;align-items:center;">
          <div class="avatar avatar-sm" id="edit-avatar">LI</div>
          <div>
            <div style="font-weight:700;font-size:.9375rem;" id="edit-name-display">Layla Ibrahim</div>
            <div class="text-xs text-muted font-mono" id="edit-email-display">layla@nexus.io</div>
          </div>
          <div style="margin-left:auto;">
            <div class="text-xs text-muted">Current Role</div>
            <span class="role-badge rb-mediator mt-2" id="edit-current-role-badge"
              style="margin-top:6px;display:inline-flex;">Dispute Mediator</span>
          </div>
        </div>

        <!-- ROLE CHANGE -->
        <div class="form-group">
          <label class="form-label">Change Role To</label>
          <select class="form-control" id="edit-role-select" onchange="onEditRoleChange(this.value)">
            <option value="super_admin">Super Admin — Full Access</option>
            <option value="dispute_mediator" selected>Dispute Mediator — Arbitration &amp; Verdicts</option>
            <option value="credentials_review">Credentials Review — Verification Queue</option>
            <option value="tech_support">Tech Support — User Account Issues</option>
          </select>
          <div id="edit-super-warning"
            style="display:none;margin-top:10px;background:#FBEAE7;border:1px solid #F0C4BC;border-radius:var(--radius-sm);padding:12px 14px;font-size:.8125rem;color:var(--rust);">
            ⚠ Promoting to Super Admin grants unrestricted platform access. This action is logged and irreversible
            without another Super Admin.
          </div>
        </div>

        <!-- PERMISSIONS INHERITED PREVIEW -->
        <div
          style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px;">
          <div
            style="font-size:.65rem;text-transform:uppercase;letter-spacing:.1em;font-weight:700;color:var(--ink-muted);margin-bottom:10px;font-family:var(--font-body);">
            Inherited Permissions for Selected Role</div>
          <div class="perm-grid" id="edit-perms-preview">
            <span class="pchip on">View Disputes</span>
            <span class="pchip on">Issue Verdicts</span>
            <span class="pchip on">Evidence Access</span>
            <span class="pchip on">Apply Sanctions</span>
            <span class="pchip on">Message Parties</span>
            <span class="pchip on">Safe Room Access</span>
            <span class="pchip">Finance Access</span>
            <span class="pchip">Admin Mgmt</span>
          </div>
        </div>

        <!-- ACCOUNT STATUS -->
        <div class="form-group">
          <label class="form-label">Account Status</label>
          <select class="form-control" id="edit-status-select">
            <option value="active">Active — Full access</option>
            <option value="suspended">Suspended — Login disabled, no access</option>
          </select>
        </div>

        <!-- SUSPENSION REASON (conditional) -->
        <div id="suspension-reason-wrap" style="display:none;">
          <div class="form-group">
            <label class="form-label">Suspension Reason <span style="color:var(--rust);">Required</span></label>
            <select class="form-control" id="suspension-reason">
              <option>— Select reason —</option>
              <option>Policy breach</option>
              <option>Security incident</option>
              <option>Role redundancy</option>
              <option>Temporary leave</option>
              <option>Other (explain in notes)</option>
            </select>
          </div>
        </div>
        <div id="edit-status-select-handler" style="display:none;"></div>

        <!-- SECURITY ACTIONS -->
        <div style="display:flex;flex-direction:column;gap:8px;">
          <div
            style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);font-size:.875rem;">
            <div>
              <div style="font-weight:700;">Revoke All Active Sessions</div>
              <div class="text-xs text-muted">Immediately log out this admin from all devices.</div>
            </div>
            <button type="button" class="btn btn-outline btn-sm"
              onclick="showToast('All sessions for this admin have been revoked.','info')">Revoke Sessions</button>
          </div>
        </div>

        <!-- INTERNAL NOTES -->
        <div class="form-group mt-16">
          <label class="form-label">Internal Notes <span class="text-muted font-mono"
              style="font-size:.7rem;font-weight:400;text-transform:none;letter-spacing:0;margin-left:6px;">Visible to
              Super Admins only</span></label>
          <textarea class="form-control" id="edit-notes" rows="2"
            placeholder="Reason for change, context, etc."></textarea>
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-outline"
          onclick="document.getElementById('edit-modal').classList.add('hidden')">Cancel</button>
        <!-- PHP: PATCH /admin/team/{id} -->
        <button class="btn btn-primary" onclick="saveEdit()">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- ══════════ DELETE CONFIRMATION MODAL ══════════ -->
  <div id="delete-modal" class="modal-backdrop hidden">
    <div class="modal modal-sm">
      <div class="modal-header">
        <div>
          <h3 style="color:var(--rust);">Delete Admin Account</h3>
          <p class="text-sm text-muted mt-4">This action is permanent and cannot be undone. An audit record is retained.
          </p>
        </div>
        <button class="modal-close" onclick="document.getElementById('delete-modal').classList.add('hidden')">✕</button>
      </div>
      <div class="modal-body">
        <div
          style="background:#FBEAE7;border:1px solid #F0C4BC;border-radius:var(--radius-md);padding:16px 18px;margin-bottom:18px;font-size:.875rem;">
          <div style="font-weight:700;color:var(--rust);margin-bottom:6px;">⚠ You are about to permanently delete:</div>
          <div style="font-size:1rem;font-family:var(--font-display);color:var(--ink);" id="delete-name-display">Layla
            Ibrahim</div>
          <div class="text-xs text-muted font-mono mt-2" id="delete-email-display">layla@nexus.io</div>
        </div>

        <div
          style="display:flex;flex-direction:column;gap:6px;font-size:.875rem;margin-bottom:18px;color:var(--ink-mid);">
          <div>✓ All active sessions will be immediately revoked</div>
          <div>✓ Login access will be permanently removed</div>
          <div>✓ Audit record of this deletion will be retained</div>
          <div style="color:var(--rust);">✕ Any in-progress dispute cases must be reassigned first</div>
        </div>

        <div class="form-group">
          <label class="form-label">Reason for Deletion <span style="color:var(--rust);">Required</span></label>
          <select class="form-control" id="delete-reason">
            <option value="">— Select a reason —</option>
            <option>Role eliminated / team restructure</option>
            <option>Employee departure</option>
            <option>Security incident — immediate removal</option>
            <option>Duplicate / test account</option>
            <option>Other</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Type the admin's name to confirm</label>
          <input type="text" class="form-control" id="delete-confirm-input" placeholder="Type exact name…">
          <span class="field-error" id="delete-name-error"
            style="display:none;color:var(--rust);font-size:.8rem;margin-top:6px;">Name does not match. Please type the
            admin's exact full name.</span>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline"
          onclick="document.getElementById('delete-modal').classList.add('hidden')">Cancel</button>
        <!-- PHP: DELETE /admin/team/{id} -->
        <button class="btn btn-danger" id="delete-confirm-btn" onclick="confirmDelete()">Permanently Delete
          Account</button>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast-stack" id="toast-stack"></div>

  <script>
    /* ── ROLE SELECTOR ── */
    let selectedRole = '';
    function selectRole(role) {
      selectedRole = role;
      document.querySelectorAll('.role-option-card').forEach(c => {
        c.style.borderColor = 'var(--border)';
        c.style.background = 'var(--ivory-card)';
      });
      const card = document.getElementById('ro-' + role);
      if (card) {
        card.style.borderColor = 'var(--gold)';
        card.style.background = 'var(--gold-pale)';
      }
      document.getElementById('err-role').style.display = 'none';
      document.getElementById('super-admin-warning').style.display = role === 'super_admin' ? 'block' : 'none';
      updatePreview();
    }

    /* ── PREVIEW ── */
    const roleLabels = {
      super_admin: 'Super Admin',
      dispute_mediator: 'Dispute Mediator',
      credentials_review: 'Credentials Review Admin',
      tech_support: 'Tech Support Admin'
    };
    function updatePreview() {
      const name = document.getElementById('new-name')?.value || '—';
      const email = document.getElementById('new-email')?.value || '—';
      document.getElementById('prev-name').textContent = name;
      document.getElementById('prev-email').textContent = email;
      document.getElementById('prev-role').textContent = roleLabels[selectedRole] || '—';
    }

    /* ── CREATE ADMIN ── */
    function createAdmin(e) {
      e.preventDefault();
      const name = document.getElementById('new-name')?.value?.trim();
      const email = document.getElementById('new-email')?.value?.trim();

      if (!name) { showToast('Please enter a full name.', 'warn'); return; }
      if (!email || !email.endsWith('@nexus.io')) {
        showToast('Email must be an @nexus.io address.', 'warn'); return;
      }
      if (!selectedRole) {
        document.getElementById('err-role').style.display = 'block';
        return;
      }
      if (selectedRole === 'super_admin' && !document.getElementById('super-confirm')?.checked) {
        showToast('Please confirm Super Admin access before proceeding.', 'warn'); return;
      }

      // PHP: AJAX POST /admin/team/create
      document.getElementById('create-form').scrollIntoView({ behavior: 'smooth' });
      showToast('Admin account created for ' + name + '. Invite sent to ' + email + '.');
      resetCreateForm();
    }

    function resetCreateForm() {
      document.getElementById('new-name').value = '';
      document.getElementById('new-email').value = '';
      document.getElementById('new-notes').value = '';
      selectedRole = '';
      document.querySelectorAll('.role-option-card').forEach(c => {
        c.style.borderColor = 'var(--border)';
        c.style.background = 'var(--ivory-card)';
      });
      document.getElementById('super-admin-warning').style.display = 'none';
      document.getElementById('err-role').style.display = 'none';
      updatePreview();
    }

    /* ── EDIT MODAL ── */
    let deleteTargetName = '';
    function openEditModal(admin) {
      document.getElementById('edit-modal-title').textContent = 'Edit Admin — ' + admin.name;
      document.getElementById('edit-avatar').textContent = admin.name.split(' ').map(n => n[0]).join('').toUpperCase();
      document.getElementById('edit-name-display').textContent = admin.name;
      document.getElementById('edit-email-display').textContent = admin.email;

      const roleSelect = document.getElementById('edit-role-select');
      if (roleSelect) roleSelect.value = admin.role;

      const statusSelect = document.getElementById('edit-status-select');
      if (statusSelect) statusSelect.value = admin.status;

      const badge = document.getElementById('edit-current-role-badge');
      if (badge) {
        const cls = { super_admin: 'rb-super', dispute_mediator: 'rb-mediator', credentials_review: 'rb-creds', tech_support: 'rb-support' };
        badge.className = 'role-badge ' + (cls[admin.role] || 'rb-support');
        badge.textContent = roleLabels[admin.role] || admin.role;
      }

      document.getElementById('edit-modal').classList.remove('hidden');

      // Status change listener
      statusSelect?.addEventListener('change', function () {
        document.getElementById('suspension-reason-wrap').style.display = this.value === 'suspended' ? 'block' : 'none';
      });
    }

    function onEditRoleChange(role) {
      document.getElementById('edit-super-warning').style.display = role === 'super_admin' ? 'block' : 'none';
      // Update permissions preview
      const permsMap = {
        super_admin: ['All Permissions', 'Create Admins', 'Delete Accounts', 'Platform Config', 'Finance Access', 'Audit Logs', 'Override Verdicts'],
        dispute_mediator: ['View Disputes', 'Issue Verdicts', 'Evidence Access', 'Apply Sanctions', 'Message Parties', 'Safe Room Access'],
        credentials_review: ['Credentials Queue', 'Approve Creds', 'Reject Creds', 'KYC Review', 'Specialist Profiles', 'Flag for Review'],
        tech_support: ['View User Accounts', 'Account Recovery', 'View Bug Reports', 'Impersonate (logged)']
      };
      const preview = document.getElementById('edit-perms-preview');
      if (!preview) return;
      const all_perms = ['All Permissions', 'Create Admins', 'Delete Accounts', 'Platform Config', 'Finance Access', 'Audit Logs', 'Override Verdicts', 'View Disputes', 'Issue Verdicts', 'Evidence Access', 'Apply Sanctions', 'Message Parties', 'Safe Room Access', 'Credentials Queue', 'Approve Creds', 'Reject Creds', 'KYC Review', 'Specialist Profiles', 'Flag for Review', 'View User Accounts', 'Account Recovery', 'View Bug Reports', 'Impersonate (logged)'];
      const active = permsMap[role] || [];
      preview.innerHTML = all_perms.slice(0, 10).map(p =>
        `<span class="pchip ${active.includes(p) ? 'on' : ''}">${p}</span>`
      ).join('');
    }

    function saveEdit() {
      document.getElementById('edit-modal').classList.add('hidden');
      const name = document.getElementById('edit-name-display')?.textContent;
      const role = document.getElementById('edit-role-select')?.value;
      showToast(name + '\'s account updated to ' + roleLabels[role] + '.');
    }

    /* ── DELETE MODAL ── */
    let deleteTargetId = null;
    function openDeleteModal(admin) {
      deleteTargetId = admin.id;
      deleteTargetName = admin.name;
      document.getElementById('delete-name-display').textContent = admin.name;
      document.getElementById('delete-email-display').textContent = admin.email || '';
      document.getElementById('delete-confirm-input').value = '';
      document.getElementById('delete-name-error').style.display = 'none';
      document.getElementById('delete-modal').classList.remove('hidden');
    }

    function confirmDelete() {
      const input = document.getElementById('delete-confirm-input')?.value?.trim();
      const reason = document.getElementById('delete-reason')?.value;
      if (!reason) { showToast('Please select a reason for deletion.', 'warn'); return; }
      if (input.toLowerCase() !== deleteTargetName.toLowerCase()) {
        document.getElementById('delete-name-error').style.display = 'block';
        return;
      }
      document.getElementById('delete-modal').classList.add('hidden');
      // PHP: AJAX DELETE /admin/team/{deleteTargetId}
      // Remove row from DOM
      const rows = document.querySelectorAll('[data-name="' + deleteTargetName.toLowerCase() + '"]');
      rows.forEach(r => {
        r.style.transition = 'opacity .4s';
        r.style.opacity = '0';
        setTimeout(() => r.remove(), 400);
      });
      showToast(deleteTargetName + '\'s admin account has been permanently deleted. Audit record retained.');
    }

    /* ── SUSPEND / REINSTATE ── */
    function toggleSuspend(btn, name) {
      const row = btn.closest('tr');
      const statusCell = row.querySelector('td:nth-child(2)');
      const dot = statusCell?.querySelector('.status-dot');
      const isSuspended = btn.textContent.includes('Reinstate');
      if (!isSuspended) {
        btn.textContent = '↺ Reinstate';
        btn.className = 'btn btn-gold btn-sm';
        if (dot) { dot.className = 'status-dot sd-suspended'; }
        statusCell.innerHTML = '<div class="flex items-center gap-6"><div class="status-dot sd-suspended"></div> <span style="color:var(--rust);font-weight:600;">Suspended</span></div>';
        row.classList.add('suspended');
        showToast(name + ' has been suspended. Access revoked immediately.', 'warn');
      } else {
        reinstateAccount(name, btn);
      }
    }
    function reinstateAccount(name, btn) {
      const row = btn.closest('tr');
      const statusCell = row.querySelector('td:nth-child(2)');
      btn.textContent = '⏸ Suspend';
      btn.className = 'btn btn-ghost btn-sm';
      btn.style.fontSize = '.75rem';
      statusCell.innerHTML = '<div class="flex items-center gap-6"><div class="status-dot sd-active"></div> Active</div>';
      row.classList.remove('suspended');
      row.style.opacity = '1';
      showToast(name + '\'s account has been reinstated. Access restored.');
    }

    /* ── FILTER ── */
    function filterAdmins(query) {
      const q = query.toLowerCase();
      document.querySelectorAll('.admin-table tbody tr').forEach(r => {
        const name = r.dataset.name || '';
        const email = r.dataset.email || '';
        r.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
      });
    }
    function filterRole(role) {
      document.querySelectorAll('.admin-table tbody tr').forEach(r => {
        r.style.display = (!role || r.dataset.role === role) ? '' : 'none';
      });
    }

    /* ── MISC ── */
    function scrollToCreate() {
      document.getElementById('create-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
      document.getElementById('new-name').focus();
    }
    function toggleRoleDefs() {
      const el = document.getElementById('role-def-grid');
      el.style.display = el.style.display === 'none' ? '' : 'none';
    }
    function resendInvite(name) {
      showToast('Invite re-sent to ' + name + '.', 'info');
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