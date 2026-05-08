<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notifications — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/notifications.css">
</head>
<body>

<nav class="topnav center-nav">
  <div class="container">
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="dashboard-client.html">Dashboard</a>
    </div>
  </div>
</nav>

<div class="notif-shell">

  <div class="notif-page-header">
    <div>
      <h2 style="font-family:var(--font-display);font-size:1.8rem;font-weight:500;margin-bottom:4px;">Notifications</h2>
      <p class="text-sm text-muted">4 unread — last updated 11:42 GMT+2</p>
    </div>
    <div style="display:flex;gap:10px;">
      <button class="btn btn-ghost btn-sm" onclick="markAllRead()">Mark all read</button>
      <button class="btn btn-outline btn-sm" onclick="document.getElementById('pref-modal').classList.remove('hidden')">⚙ Preferences</button>
    </div>
  </div>


  <!-- TODAY -->
  <div class="notif-group-label">Today — Apr 15, 2025</div>

  <div class="notif-item unread">
    <div class="notif-icon message">💬</div>
    <div class="notif-body">
      <div class="notif-title">New message from Dr. Rania Khalil</div>
      <div class="notif-desc">"I'll have the model comparison ready by tonight — the cross-validation report will follow tomorrow morning. Full Phase 2 submission by end of Apr 17…"</div>
      <div class="notif-meta">
        <span class="notif-time">11:42</span>
        <span class="notif-project">NX-2025-3812</span>
      </div>
    </div>
    <div class="notif-actions">
      <a href="messages.html" class="btn btn-primary btn-sm">Reply</a>
      <button class="btn btn-ghost btn-sm">✕</button>
    </div>
  </div>

  <div class="notif-item unread">
    <div class="notif-icon dispute">⚖️</div>
    <div class="notif-body">
      <div class="notif-title">Dispute update — Arbiter reviewing evidence</div>
      <div class="notif-desc">Arbiter Mohammed Hassan has reviewed 55% of the submitted evidence for dispute DSP-NX-3801. A verdict is expected within 60 hours.</div>
      <div class="notif-meta">
        <span class="notif-time">10:05</span>
        <span class="notif-project">DSP-NX-3801</span>
      </div>
    </div>
    <div class="notif-actions">
      <a href="dispute.html" class="btn btn-outline btn-sm">View Dispute</a>
      <button class="btn btn-ghost btn-sm">✕</button>
    </div>
  </div>

  <div class="notif-item unread">
    <div class="notif-icon bid">📨</div>
    <div class="notif-body">
      <div class="notif-title">New bid received — Marcus Fernandez</div>
      <div class="notif-desc">Marcus Fernandez has submitted a proposal for the Annual Report Translation project. Budget: $7,800 · 4 milestones · Delivery: 35 days.</div>
      <div class="notif-meta">
        <span class="notif-time">08:14</span>
        <span class="notif-project">NX-2025-3801</span>
        <span class="badge badge-pending" style="font-size:.625rem;">Pending Review</span>
      </div>
    </div>
    <div class="notif-actions">
      <button class="btn btn-primary btn-sm">Review Bid</button>
      <button class="btn btn-ghost btn-sm">✕</button>
    </div>
  </div>

  <!-- YESTERDAY -->
  <div class="notif-group-label">Yesterday — Apr 14, 2025</div>

  <div class="notif-item">
    <div class="notif-icon escrow">💰</div>
    <div class="notif-body">
      <div class="notif-title">Escrow locked — Phase 2 funded</div>
      <div class="notif-desc">$3,360 has been locked in escrow for Phase 2 (Feature Engineering &amp; Model Training) of the Predictive Churn Model project. Dr. Rania Khalil has been notified to proceed.</div>
      <div class="notif-meta">
        <span class="notif-time">Apr 14 · 09:00</span>
        <span class="notif-project">NX-2025-3812</span>
      </div>
    </div>
    <div class="notif-actions">
      <a href="escrow-wallet.html" class="btn btn-outline btn-sm">View Wallet</a>
    </div>
  </div>

  <div class="notif-item">
    <div class="notif-icon milestone">📋</div>
    <div class="notif-body">
      <div class="notif-title">NDA signed — James Moreau</div>
      <div class="notif-desc">James Moreau has signed the auto-generated NDA for the MENA Expansion Contract Review project. He may now access the full project brief and begin preparation.</div>
      <div class="notif-meta">
        <span class="notif-time">Apr 14 · 15:22</span>
        <span class="notif-project">NX-2025-4821</span>
      </div>
    </div>
    <div class="notif-actions">
      <a href="project-detail.html" class="btn btn-outline btn-sm">View Project</a>
    </div>
  </div>

  <div class="notif-item">
    <div class="notif-icon dispute">🔒</div>
    <div class="notif-body">
      <div class="notif-title">Dispute filed — Annual Report Translation</div>
      <div class="notif-desc">A quality dispute was filed against Phase 3 delivery by specialist Lena Bergmann. Evidence package has been automatically assembled (23 items). An arbiter will be assigned shortly.</div>
      <div class="notif-meta">
        <span class="notif-time">Apr 13 · 14:22</span>
        <span class="notif-project">DSP-NX-3801</span>
      </div>
    </div>
    <div class="notif-actions">
      <a href="dispute.html" class="btn btn-danger btn-sm">View Dispute</a>
    </div>
  </div>

  <!-- OLDER -->
  <div class="notif-group-label">Earlier This Week</div>

  <div class="notif-item">
    <div class="notif-icon system" style="font-size:.9rem;">🔒</div>
    <div class="notif-body">
      <div class="notif-title">New login detected from Cairo, EG</div>
      <div class="notif-desc">A new session was created for your account from Cairo, Egypt using Chrome on Windows. If this wasn't you, please secure your account immediately.</div>
      <div class="notif-meta">
        <span class="notif-time">Apr 12 · 08:44</span>
        <span class="badge badge-verified" style="font-size:.625rem;">Verified</span>
      </div>
    </div>
  </div>

  <div class="notif-item">
    <div class="notif-icon escrow">✅</div>
    <div class="notif-body">
      <div class="notif-title">Milestone 1 approved — $1,680 released</div>
      <div class="notif-desc">You approved Phase 1 (EDA &amp; Baseline) of the Predictive Churn Model project. $1,680 has been released to Dr. Rania Khalil. Phase 2 escrow is now active.</div>
      <div class="notif-meta">
        <span class="notif-time">Apr 12 · 08:15</span>
        <span class="notif-project">NX-2025-3812</span>
      </div>
    </div>
  </div>

  <div style="text-align:center;padding:32px 0;">
    <button class="btn btn-outline">Load Older Notifications</button>
  </div>

</div>

<!-- PREFERENCES MODAL -->
<div id="pref-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Notification Preferences</h3>
        <p class="text-sm text-muted mt-4">Choose how and when you receive notifications.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('pref-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">In-App Notifications</div>
      <div class="pref-row"><span>Milestone updates &amp; approvals</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>New messages</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>New bids received</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>Dispute updates</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>Escrow &amp; payment events</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>Security alerts (logins, etc.)</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>

    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('pref-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('pref-modal').classList.add('hidden')">Save Preferences</button>
    </div>
  </div>
</div>

<script>
function markAllRead() {
  document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
  document.querySelector('.notif-count')?.remove();
}
document.querySelectorAll('.notif-filter-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.notif-filter-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
  });
});
</script>
</body>
</html>
