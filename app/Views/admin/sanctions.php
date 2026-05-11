<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Sanctions — Nexus Admin</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/sanctions.css">
</head>

<body>

  <?php require __DIR__ . '/../partials/topnav.php'; ?>

  <div class="admin-shell">
    <aside class="admin-sidebar">
      <div class="admin-sidebar-section">Overview</div>
      <a class="admin-sidebar-link" href="admin-dashboard.html">📊 Health Dashboard</a>
      <div class="admin-sidebar-section">Marketplace</div>
      <a class="admin-sidebar-link" href="admin-team.html">👤 Users</a>
      <div class="admin-sidebar-section">Disputes</div>
      <a class="admin-sidebar-link" href="open-disputes.html">⚖️ Active Disputes <span class="notif-count"
          style="margin-left:auto;background:var(--rust);">4</span></a>
      <div class="admin-sidebar-section">Verifications</div>
      <a class="admin-sidebar-link" href="admin-kyc.html">🛡 KYC Queue</a>
      <div class="admin-sidebar-section">Sanctions</div>
      <a class="admin-sidebar-link active" href="sanctions.html">⚠️ User Sanctions</a>
      <div class="admin-sidebar-section">Support</div>
      <a class="admin-sidebar-link" href="admin-support.html">💬 Chat Support</a>
    </aside>

    <main class="admin-main">

      <!-- HEADER -->
      <div class="flex justify-between items-start mb-28">
        <div>
          <div class="breadcrumb"
            style="font-family:var(--font-mono);font-size:.72rem;color:var(--ink-muted);margin-bottom:8px;">Admin
            Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Sanctions</div>
          <h2 style="font-family:var(--font-display);font-size:1.6rem;font-weight:500;margin-bottom:6px;">User Sanctions
          </h2>
          <p style="font-size:.875rem;color:var(--ink-muted);">Manage warnings, limited bans, and permanent bans.
            Withdraw sanctions with an apology or apply new ones.</p>
        </div>
        <button class="btn btn-primary"
          onclick="document.getElementById('new-sanction').scrollIntoView({behavior:'smooth'})">+ New Sanction</button>
      </div>

      <!-- SEARCH -->
      <div class="flex justify-between items-center mb-14">
        <div
          style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);font-family:var(--font-body);">
          Active Sanctions · 5 Users</div>
        <div class="search-wrap"><span class="icon">🔍</span><input type="text" class="search-input"
            placeholder="Search sanctioned users…" oninput="searchSanctions(this.value)"></div>
      </div>

      <!-- ═══ TIER 1: WARNINGS ═══ -->
      <!-- PHP: $warnings = Sanction::where('tier','warning')->with('user')->get() -->
      <div class="sanction-group" id="group-warn">
        <div class="sanction-group-head"><span class="s-pill warn">⚠ Warning</span><span
            style="color:var(--ink-faint);">2 users</span></div>

        <div class="sanction-row" data-name="lena bergmann" data-email="lena.b@nexus.io" id="s-1">
          <div class="avatar avatar-sm" style="flex-shrink:0;">LB</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;">Lena Bergmann</div>
            <div class="text-xs text-muted font-mono">lena.b@nexus.io · Technical Translation</div>
          </div>
          <div style="text-align:right;min-width:160px;">
            <div class="text-xs text-muted">Reason</div>
            <div style="font-size:.8125rem;font-weight:600;">Late deliverable (2nd offense)</div>
          </div>
          <div style="text-align:right;min-width:100px;">
            <div class="text-xs text-muted">Since</div>
            <div class="font-mono" style="font-size:.8125rem;">Apr 8, 2025</div>
          </div>
          <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
            onclick="openWithdraw('s-1','Lena Bergmann','warning')">↺ Withdraw</button>
        </div>

        <div class="sanction-row" data-name="omar youssef" data-email="omar.y@nexus.io" id="s-2">
          <div class="avatar avatar-sm" style="flex-shrink:0;">OY</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;">Omar Youssef</div>
            <div class="text-xs text-muted font-mono">omar.y@nexus.io · Legal Consulting</div>
          </div>
          <div style="text-align:right;min-width:160px;">
            <div class="text-xs text-muted">Reason</div>
            <div style="font-size:.8125rem;font-weight:600;">Incomplete milestone submission</div>
          </div>
          <div style="text-align:right;min-width:100px;">
            <div class="text-xs text-muted">Since</div>
            <div class="font-mono" style="font-size:.8125rem;">Apr 2, 2025</div>
          </div>
          <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
            onclick="openWithdraw('s-2','Omar Youssef','warning')">↺ Withdraw</button>
        </div>
      </div>

      <!-- ═══ TIER 2: LIMITED BAN ═══ -->
      <!-- PHP: $limited = Sanction::where('tier','limited_ban')->with('user')->get() -->
      <div class="sanction-group" id="group-limit">
        <div class="sanction-group-head"><span class="s-pill limit">⛔ Limited Ban</span><span
            style="color:var(--ink-faint);">2 users</span></div>

        <div class="sanction-row" data-name="thomas müller" data-email="thomas.m@nexus.io" id="s-3">
          <div class="avatar avatar-sm" style="flex-shrink:0;">TM</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;">Thomas Müller</div>
            <div class="text-xs text-muted font-mono">thomas.m@nexus.io · Data Science</div>
          </div>
          <div style="text-align:right;min-width:160px;">
            <div class="text-xs text-muted">Reason</div>
            <div style="font-size:.8125rem;font-weight:600;">Plagiarized deliverable</div>
          </div>
          <div style="text-align:right;min-width:100px;">
            <div class="text-xs text-muted">Since</div>
            <div class="font-mono" style="font-size:.8125rem;">Mar 28, 2025</div>
          </div>
          <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
            onclick="openWithdraw('s-3','Thomas Müller','limited_ban')">↺ Withdraw</button>
        </div>

        <div class="sanction-row" data-name="fatima al-harbi" data-email="fatima.h@nexus.io" id="s-4">
          <div class="avatar avatar-sm" style="flex-shrink:0;">FA</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;">Fatima Al-Harbi</div>
            <div class="text-xs text-muted font-mono">fatima.h@nexus.io · Financial Modelling</div>
          </div>
          <div style="text-align:right;min-width:160px;">
            <div class="text-xs text-muted">Reason</div>
            <div style="font-size:.8125rem;font-weight:600;">Client harassment report</div>
          </div>
          <div style="text-align:right;min-width:100px;">
            <div class="text-xs text-muted">Since</div>
            <div class="font-mono" style="font-size:.8125rem;">Mar 15, 2025</div>
          </div>
          <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
            onclick="openWithdraw('s-4','Fatima Al-Harbi','limited_ban')">↺ Withdraw</button>
        </div>
      </div>

      <!-- ═══ TIER 3: PERMANENT BAN ═══ -->
      <!-- PHP: $banned = Sanction::where('tier','permanent_ban')->with('user')->get() -->
      <div class="sanction-group" id="group-ban">
        <div class="sanction-group-head"><span class="s-pill ban">⛔ Permanent Ban</span><span
            style="color:var(--ink-faint);">1 user</span></div>

        <div class="sanction-row" data-name="rashid khalil" data-email="rashid.k@nexus.io" id="s-5">
          <div class="avatar avatar-sm" style="flex-shrink:0;opacity:.5;">RK</div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;color:var(--ink-muted);">Rashid Khalil</div>
            <div class="text-xs text-muted font-mono">rashid.k@nexus.io · Financial Modelling</div>
          </div>
          <div style="text-align:right;min-width:160px;">
            <div class="text-xs text-muted">Reason</div>
            <div style="font-size:.8125rem;font-weight:600;color:var(--rust);">Fraud — fabricated credentials</div>
          </div>
          <div style="text-align:right;min-width:100px;">
            <div class="text-xs text-muted">Since</div>
            <div class="font-mono" style="font-size:.8125rem;">Feb 20, 2025</div>
          </div>
          <button class="btn btn-outline btn-sm" style="font-size:.75rem;"
            onclick="openWithdraw('s-5','Rashid Khalil','permanent_ban')">↺ Withdraw</button>
        </div>
      </div>

      <!-- ═══ WITHDRAW MODAL ═══ -->
      <div id="withdraw-modal" class="modal-backdrop hidden">
        <div class="modal" style="max-width:520px;">
          <div class="modal-header">
            <div>
              <h3 id="withdraw-title">Withdraw Sanction</h3>
              <p class="text-sm text-muted mt-4">The user will receive your apology message via notification.</p>
            </div>
            <button class="modal-close"
              onclick="document.getElementById('withdraw-modal').classList.add('hidden')">✕</button>
          </div>
          <div class="modal-body">
            <div
              style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px;">
              <div style="font-weight:700;" id="withdraw-user-name">—</div>
              <div class="text-xs text-muted" id="withdraw-tier-label">—</div>
            </div>
            <div class="form-group">
              <label class="form-label">Apology / Withdrawal Message <span
                  style="color:var(--rust);">Required</span></label>
              <!-- PHP: This message is sent to the user as a platform notification -->
              <textarea class="form-control" rows="3" id="withdraw-message"
                placeholder="e.g. After further review, we have determined that the sanction was issued in error. We sincerely apologize for the inconvenience…"></textarea>
              <p class="form-hint mt-4">This message will be sent to the user as an official platform notification.</p>
            </div>
            <div class="form-group">
              <label class="form-label">Internal Reason <span class="text-muted"
                  style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.75rem;">Admin-only</span></label>
              <input type="text" class="form-control" id="withdraw-reason"
                placeholder="e.g. Dispute resolved in user's favor">
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-outline"
              onclick="document.getElementById('withdraw-modal').classList.add('hidden')">Cancel</button>
            <!-- PHP: POST /admin/sanctions/{id}/withdraw { message, reason } -->
            <button class="btn btn-primary" onclick="confirmWithdraw()">Withdraw & Send Apology</button>
          </div>
        </div>
      </div>

      <!-- ═══ NEW SANCTION FORM ═══ -->
      <div class="new-sanction-card" id="new-sanction" style="margin-top:36px;">
        <div
          style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);font-family:var(--font-body);margin-bottom:5px;">
          Issue New Sanction</div>
        <h3 style="font-family:var(--font-display);font-size:1.2rem;font-weight:600;margin-bottom:4px;">Sanction a User
        </h3>
        <p style="font-size:.8125rem;color:var(--ink-muted);margin-bottom:20px;">Search for a user by name, username, or
          email. Select a sanction tier and provide a reason.</p>

        <!-- PHP: AJAX GET /admin/users/search?q={query} -->
        <div class="form-group">
          <label class="form-label">Find User</label>
          <input type="text" class="form-control" id="user-search" placeholder="Search by name, username, or email…"
            oninput="searchUsers(this.value)">
        </div>
        <div id="user-results" style="margin-bottom:16px;max-height:200px;overflow-y:auto;display:none;">
          <!-- JS-populated search results -->
        </div>
        <div id="selected-user"
          style="display:none;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px;">
          <div class="flex items-center gap-12">
            <div class="avatar avatar-sm" id="sel-avatar">—</div>
            <div style="flex:1;">
              <div style="font-weight:700;" id="sel-name">—</div>
              <div class="text-xs text-muted font-mono" id="sel-email">—</div>
            </div><button class="btn btn-ghost btn-sm" style="font-size:.75rem;" onclick="clearSelection()">✕
              Clear</button>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Sanction Tier</label>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;" id="tier-selector">
            <label id="t-warning"
              style="border:1.5px solid var(--border);border-radius:var(--radius-md);padding:14px 16px;cursor:pointer;transition:all .15s;display:block;"
              onclick="selectTier('warning')">
              <input type="radio" name="tier" value="warning" style="display:none;">
              <div style="margin-bottom:6px;"><span class="s-pill warn">⚠ Warning</span></div>
              <p style="font-size:.75rem;color:var(--ink-muted);line-height:1.5;margin:0;">User is notified. No access
                restrictions.</p>
            </label>
            <label id="t-limited_ban"
              style="border:1.5px solid var(--border);border-radius:var(--radius-md);padding:14px 16px;cursor:pointer;transition:all .15s;display:block;"
              onclick="selectTier('limited_ban')">
              <input type="radio" name="tier" value="limited_ban" style="display:none;">
              <div style="margin-bottom:6px;"><span class="s-pill limit">⛔ Limited Ban</span></div>
              <p style="font-size:.75rem;color:var(--ink-muted);line-height:1.5;margin:0;">Cannot accept new contracts.
                Existing ones continue.</p>
            </label>
            <label id="t-permanent_ban"
              style="border:1.5px solid var(--border);border-radius:var(--radius-md);padding:14px 16px;cursor:pointer;transition:all .15s;display:block;"
              onclick="selectTier('permanent_ban')">
              <input type="radio" name="tier" value="permanent_ban" style="display:none;">
              <div style="margin-bottom:6px;"><span class="s-pill ban">⛔ Permanent Ban</span></div>
              <p style="font-size:.75rem;color:var(--ink-muted);line-height:1.5;margin:0;">Full account suspension.
                Login disabled.</p>
            </label>
          </div>
        </div>

        <!-- BAN DURATION (visible only when Limited Ban is selected) -->
        <!-- PHP: Store as $sanction->duration_days -->
        <div class="form-group" id="ban-duration-group" style="display:none;margin-top:0;">
          <label class="form-label">Ban Duration <span style="color:var(--rust);">Required</span></label>
          <div class="flex items-center gap-10">
            <input type="number" class="form-control" id="ban-days" min="1" max="365" placeholder="e.g. 14"
              style="width:120px;">
            <span style="font-size:.875rem;color:var(--ink-muted);">days</span>
            <div style="display:flex;gap:6px;margin-left:12px;">
              <button type="button" class="btn btn-ghost btn-sm" style="font-size:.75rem;"
                onclick="document.getElementById('ban-days').value=7">7d</button>
              <button type="button" class="btn btn-ghost btn-sm" style="font-size:.75rem;"
                onclick="document.getElementById('ban-days').value=14">14d</button>
              <button type="button" class="btn btn-ghost btn-sm" style="font-size:.75rem;"
                onclick="document.getElementById('ban-days').value=30">30d</button>
              <button type="button" class="btn btn-ghost btn-sm" style="font-size:.75rem;"
                onclick="document.getElementById('ban-days').value=90">90d</button>
            </div>
          </div>
          <p class="form-hint mt-4">The user will be unable to accept new contracts for this duration. Existing
            contracts remain active.</p>
        </div>

        <div class="form-group">
          <label class="form-label">Reason / Message to User <span style="color:var(--rust);">Required</span></label>
          <!-- PHP: This message is stored and sent to the user -->
          <textarea class="form-control" rows="3" id="sanction-message"
            placeholder="e.g. Your account has been sanctioned due to repeated policy violations regarding deliverable quality…"></textarea>
          <p class="form-hint mt-4">This message will be sent to the user as an official notification explaining the
            sanction.</p>
        </div>

        <div class="flex gap-12 items-center">
          <!-- PHP: POST /admin/sanctions/create { user_id, tier, message } -->
          <button class="btn btn-primary btn-lg" onclick="applySanction()">⚠️ Apply Sanction</button>
          <button class="btn btn-outline" onclick="resetForm()">Clear</button>
        </div>
      </div>

    </main>
  </div>

  <div class="toast-stack" id="toast-stack"></div>

  <script>
    /* ── SEARCH SANCTIONED USERS ── */
    function searchSanctions(q) {
      q = q.toLowerCase();
      document.querySelectorAll('.sanction-row').forEach(r => {
        const n = r.dataset.name || '', e = r.dataset.email || '';
        r.style.display = (n.includes(q) || e.includes(q)) ? '' : 'none';
      });
    }

    /* ── WITHDRAW ── */
    let withdrawId = '', withdrawName = '';
    function openWithdraw(id, name, tier) {
      withdrawId = id; withdrawName = name;
      const labels = { warning: 'Warning', limited_ban: 'Limited Ban', permanent_ban: 'Permanent Ban' };
      document.getElementById('withdraw-title').textContent = 'Withdraw Sanction — ' + name;
      document.getElementById('withdraw-user-name').textContent = name;
      document.getElementById('withdraw-tier-label').textContent = 'Current tier: ' + labels[tier];
      document.getElementById('withdraw-message').value = '';
      document.getElementById('withdraw-reason').value = '';
      document.getElementById('withdraw-modal').classList.remove('hidden');
    }
    function confirmWithdraw() {
      const msg = document.getElementById('withdraw-message').value.trim();
      if (!msg) { showToast('Please write an apology message.', 'warn'); return; }
      // PHP: AJAX POST /admin/sanctions/{withdrawId}/withdraw
      document.getElementById('withdraw-modal').classList.add('hidden');
      const row = document.getElementById(withdrawId);
      if (row) { row.style.transition = 'opacity .4s'; row.style.opacity = '0'; setTimeout(() => row.remove(), 400); }
      showToast('Sanction withdrawn for ' + withdrawName + '. Apology sent.');
    }

    /* ── USER SEARCH FOR NEW SANCTION ── */
    const mockUsers = [
      { name: 'Hassan Ali', email: 'hassan@nexus.io', initials: 'HA', niche: 'Cybersecurity' },
      { name: 'Dr. Rania Khalil', email: 'rania@nexus.io', initials: 'RK', niche: 'Data Science' },
      { name: 'Karim Al-Azzawi', email: 'karim.a@nexus.io', initials: 'KA', niche: 'Cybersecurity' },
      { name: 'Sara Eissa', email: 'sara@nexus.io', initials: 'SE', niche: 'Credentials Review' },
      { name: 'Ahmed Galal', email: 'ahmed.g@nexus.io', initials: 'AG', niche: 'Financial Modelling' },
    ];
    let selectedUser = null, selectedTier = '';

    function searchUsers(q) {
      const box = document.getElementById('user-results');
      if (!q || q.length < 2) { box.style.display = 'none'; return; }
      q = q.toLowerCase();
      const matches = mockUsers.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q));
      if (!matches.length) { box.innerHTML = '<div style="padding:10px;font-size:.8125rem;color:var(--ink-muted);">No users found.</div>'; box.style.display = 'block'; return; }
      box.innerHTML = matches.map(u => `<div class="user-result" onclick="pickUser('${u.name}','${u.email}','${u.initials}')"><div class="avatar avatar-sm" style="flex-shrink:0;width:28px;height:28px;font-size:.6rem;">${u.initials}</div><div><div style="font-weight:700;font-size:.8125rem;">${u.name}</div><div class="text-xs text-muted font-mono">${u.email} · ${u.niche}</div></div></div>`).join('');
      box.style.display = 'block';
    }
    function pickUser(name, email, initials) {
      selectedUser = { name, email, initials };
      document.getElementById('user-results').style.display = 'none';
      document.getElementById('user-search').value = '';
      document.getElementById('selected-user').style.display = 'block';
      document.getElementById('sel-avatar').textContent = initials;
      document.getElementById('sel-name').textContent = name;
      document.getElementById('sel-email').textContent = email;
    }
    function clearSelection() { selectedUser = null; document.getElementById('selected-user').style.display = 'none'; }

    /* ── TIER SELECT ── */
    function selectTier(tier) {
      selectedTier = tier;
      document.querySelectorAll('#tier-selector > label').forEach(l => { l.style.borderColor = 'var(--border)'; l.style.background = 'var(--ivory-card)'; });
      const el = document.getElementById('t-' + tier);
      if (el) { el.style.borderColor = 'var(--gold)'; el.style.background = 'var(--gold-pale)'; }
      // Show/hide ban duration input for limited_ban
      const durGroup = document.getElementById('ban-duration-group');
      durGroup.style.display = (tier === 'limited_ban') ? '' : 'none';
      if (tier !== 'limited_ban') document.getElementById('ban-days').value = '';
    }

    /* ── APPLY SANCTION ── */
    function applySanction() {
      if (!selectedUser) { showToast('Please select a user.', 'warn'); return; }
      if (!selectedTier) { showToast('Please select a sanction tier.', 'warn'); return; }
      if (selectedTier === 'limited_ban') {
        const days = parseInt(document.getElementById('ban-days').value);
        if (!days || days < 1) { showToast('Please specify the number of ban days.', 'warn'); return; }
      }
      const msg = document.getElementById('sanction-message').value.trim();
      if (!msg) { showToast('Please provide a reason/message.', 'warn'); return; }
      // PHP: AJAX POST /admin/sanctions/create { user_id, tier, duration_days, message }
      const daysText = selectedTier === 'limited_ban' ? ' for ' + document.getElementById('ban-days').value + ' days' : '';
      showToast('Sanction applied to ' + selectedUser.name + daysText + '. Notification sent.');
      resetForm();
    }
    function resetForm() {
      selectedUser = null; selectedTier = '';
      document.getElementById('selected-user').style.display = 'none';
      document.getElementById('user-search').value = '';
      document.getElementById('sanction-message').value = '';
      document.getElementById('ban-days').value = '';
      document.getElementById('ban-duration-group').style.display = 'none';
      document.querySelectorAll('#tier-selector > label').forEach(l => { l.style.borderColor = 'var(--border)'; l.style.background = 'var(--ivory-card)'; });
    }

    /* ── TOAST ── */
    function showToast(msg, type = 'success') { const s = document.getElementById('toast-stack'); const icons = { success: '✓', warn: '⚠', info: 'ℹ' }; const cls = { success: 'success', warn: 'warning', info: '' }; s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type === 'warn' ? 'Notice' : type === 'info' ? 'Info' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`; setTimeout(() => s.innerHTML = '', 4500); }
  </script>
</body>

</html>