<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/client/active-projects.php
    Template: Active Projects — Client View
    Role:     client (authenticated)
    Route:    /my-projects/active
    ============================================================
    PHP Data contract (from ClientProjectController::active()):
      $projects     — ActiveProject[] for $client
      $stats        — [ total, total_escrowed, due_soon,
                        pending_reviews, overdue ]
      $client       — authenticated client record
    Each ActiveProject:
      $p['id'], $p['title'], $p['niche'], $p['contract_ref'],
      $p['specialist'],       — { name, initials, rating, verified }
      $p['total_value'],      $p['paid_to_date'],
      $p['current_milestone'],
      $p['milestones_total'], $p['milestones_done'],
      $p['next_deadline'],    $p['days_remaining'],
      $p['escrowed_next'],
      $p['status'],  — active|pending_review|revision|overdue|dispute
      $p['unread_messages'],
      $p['started_at'],       $p['progress_pct']
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Active Projects — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/client-active-projects.css">
</head>
<body>

<!-- ══════════ TOPNAV ══════════ -->
<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="dashboard-client.php">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="dashboard-client.html">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔 <span class="notif-count" style="position:absolute;top:2px;right:2px;">4</span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">AT</div></div>
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

<div class="main-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-label">Overview</div>
      <a class="sidebar-link" href="dashboard-client.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
        Dashboard
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Projects</div>
      <a class="sidebar-link" href="post-job.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/></svg>
        Post New Project
      </a>
      <a class="sidebar-link active" href="client-active-projects.php">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12v12H2V2zm1 1v10h10V3H3z"/></svg>
        Active Projects
        <span class="notif-count" style="margin-left:auto;">3</span>
      </a>
      <a class="sidebar-link" href="client-completed-projects.php">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M4 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 2v1h6V3H5zm0 3v1h6V6H5zm0 3v1h4V9H5z"/></svg>
        Completed
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Bids</div>
      <a class="sidebar-link" href="incoming-bids.php">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v7h10V4H3zm1 1h2v2H4V5zm4 0h2v2H8V5zm4 0h2v2h-2V5z"/></svg>
        My Bids
        <span class="notif-count" style="margin-left:auto;">12</span>
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Marketplace</div>
      <a class="sidebar-link" href="browse-experts.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a4 4 0 1 1 0 8A4 4 0 0 1 8 1zm0 9c-3.3 0-6 1.6-6 3v1h12v-1c0-1.4-2.7-3-6-3z"/></svg>
        Browse Experts
      </a>
      <a class="sidebar-link" href="#">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M1 2h14v2H1V2zm0 4h14v2H1V6zm0 4h14v2H1v-2z"/></svg>
        Saved Experts
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Finance</div>
      <a class="sidebar-link" href="escrow-wallet.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm0 2v6h12V6H2zm9 1h2v2h-2V7z"/></svg>
        Escrow &amp; Wallet
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Support</div>
      <a class="sidebar-link" href="dispute.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1zm0 3a.75.75 0 0 0 0 1.5.75.75 0 0 0 0-1.5zm-.25 3v4.5h1.5V7h-1.5z"/></svg>
        Disputes
        <span class="notif-count" style="margin-left:auto;background:transparent;border-color:var(--rust);color:var(--rust);">1</span>
      </a>
      <a class="sidebar-link" href="messages.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 1h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-3l-4 3v-3H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>
        Messages
      </a>
    </div>
  </aside>

  <!-- CONTENT AREA -->
  <div class="content-area">

    <!-- PAGE HEADER -->
    <div class="page-header flex justify-between items-center">
      <div>
        <div class="breadcrumb">Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Projects</div>
        <h2>Active Projects</h2>
        <p class="mt-4">
          <!-- PHP: count($projects).' active contracts' -->
          3 active contracts · <strong style="color:var(--gold);">$24,500 in escrow</strong> · 1 milestone awaiting your review.
        </p>
      </div>
      <a href="post-job.html" class="btn btn-primary">+ Post New Project</a>
    </div>

    <!-- STAT STRIP -->
    <div class="stat-strip">
      <div class="strip-cell">
        <div class="strip-val">3</div>
        <div class="strip-lbl">All Active</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:#1A4A8A;">1</div>
        <div class="strip-lbl">Awaiting Review</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:var(--gold);">0</div>
        <div class="strip-lbl">In Revision</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:var(--rust);">1</div>
        <div class="strip-lbl">In Dispute</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:var(--gold);">$24,500</div>
        <div class="strip-lbl">Total Escrowed</div>
      </div>
    </div>

    <!-- ══════════ PROJECT CARDS ══════════ -->
    <!-- PHP: foreach($projects as $p): -->

    <!-- PROJECT 1: ACTIVE · PENDING SPECIALIST DELIVERY -->
    <a href="project-detail.html" class="proj-card status-active" style="text-decoration:none;display:block;" id="pc-0">
      <div class="proj-card-body">
        <div class="proj-niche-icon ni-data">🧠</div>
        <div style="min-width:0;">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap;">
            <!-- PHP: htmlspecialchars($p['title']) -->
            <div style="font-family:var(--font-display);font-size:1.05rem;font-weight:600;color:var(--ink);">Predictive Churn Model — FinCorp Q2</div>
            <span class="status-pill sp-active">● Active</span>
          </div>
          <div style="display:flex;gap:14px;font-size:.8125rem;color:var(--ink-muted);flex-wrap:wrap;margin-bottom:10px;">
            <!-- PHP: $p['specialist']['name'] -->
            <span class="flex items-center gap-6">
              <div class="avatar avatar-sm" style="width:20px;height:20px;font-size:.6rem;flex-shrink:0;">DR</div>
              Dr. Rania Khalil
              <span class="badge badge-verified badge-dot" style="font-size:.575rem;">Verified</span>
            </span>
            <span>·</span>
            <span class="font-mono">CON-NX-3812</span>
            <span>·</span>
            <span>Data Science</span>
            <span>·</span>
            <span>Started Apr 3, 2025</span>
            <!-- PHP: if($p['unread_messages'] > 0): -->
            <span class="unread-msg">💬 2 unread</span>
          </div>
          <div style="font-size:.75rem;color:var(--ink-muted);margin-top:5px;">
            <!-- PHP: $p['milestones_done'].' of '.$p['milestones_total'].' milestones complete · Phase '.$p['current_milestone']['number'].' in progress' -->
            1 of 5 milestones complete · Phase 2 in progress (68%)
          </div>
        </div>
        <div class="proj-right">
          <div class="proj-value">$8,400</div>
          <div class="proj-value-sub">$1,680 paid · $3,360 escrowed</div>
          <div style="margin-top:10px;">
            <div class="dl-chip soon">⏱ Phase 2 due Apr 19</div>
          </div>
        </div>
      </div>
      <div class="proj-progress-row">
        <div class="progress-bar" style="flex:1;height:6px;">
          <!-- PHP: width = $p['progress_pct'].'%' -->
          <div class="progress-fill" style="width:20%;"></div>
        </div>
        <span style="font-size:.75rem;font-family:var(--font-mono);color:var(--ink-muted);white-space:nowrap;">20% complete</span>
        <span style="font-size:.75rem;color:var(--ink-muted);">·</span>
        <span style="font-size:.75rem;color:var(--ink-muted);">Next deadline in 4 days</span>
        <span style="margin-left:auto;" class="flex gap-8">
          <span class="btn btn-ghost btn-sm" style="font-size:.75rem;pointer-events:none;">View Details →</span>
        </span>
      </div>
    </a>

    <!-- PROJECT 2: PENDING CLIENT REVIEW -->
    <a href="project-detail.html" class="proj-card status-pending-review" style="text-decoration:none;display:block;" id="pc-1">
      <div class="proj-card-body">
        <div class="proj-niche-icon ni-legal">⚖️</div>
        <div style="min-width:0;">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap;">
            <div style="font-family:var(--font-display);font-size:1.05rem;font-weight:600;color:var(--ink);">MENA Expansion — Cross-Border Contract Review</div>
            <span class="status-pill sp-review">⏳ Awaiting Your Review</span>
          </div>
          <div style="display:flex;gap:14px;font-size:.8125rem;color:var(--ink-muted);flex-wrap:wrap;margin-bottom:10px;">
            <span class="flex items-center gap-6">
              <div class="avatar avatar-sm" style="width:20px;height:20px;font-size:.6rem;flex-shrink:0;">JM</div>
              James Moreau
              <span class="badge badge-verified badge-dot" style="font-size:.575rem;">Verified</span>
            </span>
            <span>·</span>
            <span class="font-mono">CON-NX-4821</span>
            <span>·</span>
            <span>Legal Consulting</span>
            <span>·</span>
            <span>Started Apr 3, 2025</span>
          </div>
          <div style="font-size:.75rem;color:#1A4A8A;margin-top:5px;font-weight:700;">
            Phase 1 submitted Apr 15 — awaiting your review · Auto-approves Apr 18 if no action
          </div>
        </div>
        <div class="proj-right">
          <div class="proj-value">$12,000</div>
          <div class="proj-value-sub">$0 paid · $3,000 escrowed</div>
          <div style="margin-top:10px;">
            <div class="dl-chip review">⏳ Review by Apr 18</div>
          </div>
        </div>
      </div>
      <div class="proj-progress-row" style="background:#EBF0F8;">
        <div class="progress-bar" style="flex:1;height:6px;background:rgba(26,74,138,.15);">
          <div class="progress-fill" style="width:33%;background:#1A4A8A;"></div>
        </div>
        <span style="font-size:.75rem;font-family:var(--font-mono);color:#1A4A8A;">Phase 1 of 3 delivered</span>
        <span style="font-size:.75rem;color:#1A4A8A;">·</span>
        <span style="font-size:.75rem;color:#1A4A8A;font-weight:700;">Your action needed — review &amp; approve Phase 1</span>
        <span style="margin-left:auto;" class="flex gap-8">
          <span class="btn btn-ghost btn-sm" style="font-size:.75rem;pointer-events:none;color:#1A4A8A;">Review Now →</span>
        </span>
      </div>
    </a>

    <!-- PROJECT 3: DISPUTE ACTIVE -->
    <a href="project-detail.html" class="proj-card status-dispute" style="text-decoration:none;display:block;" id="pc-2">
      <div class="proj-card-body">
        <div class="proj-niche-icon ni-trans" style="background:#FBEAE7;border-color:#F0C4BC;">🌐</div>
        <div style="min-width:0;">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap;">
            <div style="font-family:var(--font-display);font-size:1.05rem;font-weight:600;color:var(--ink);">Annual Report — DE/EN Technical Translation</div>
            <span class="status-pill sp-dispute">⚖ Dispute Active</span>
          </div>
          <div style="display:flex;gap:14px;font-size:.8125rem;color:var(--ink-muted);flex-wrap:wrap;margin-bottom:10px;">
            <span class="flex items-center gap-6">
              <div class="avatar avatar-sm" style="width:20px;height:20px;font-size:.6rem;flex-shrink:0;">LB</div>
              Lena Bergmann
            </span>
            <span>·</span>
            <span class="font-mono">CON-NX-3801</span>
            <span>·</span>
            <span>Technical Translation</span>
            <span>·</span>
            <span>Started Apr 1, 2025</span>
          </div>
          <div style="font-size:.75rem;color:var(--rust);margin-top:5px;font-weight:700;">
            Dispute DSP-NX-3801 · Phase 3 delivery challenged · Arbiter assigned · Verdict expected Apr 16
          </div>
        </div>
        <div class="proj-right">
          <div class="proj-value">$4,100</div>
          <div class="proj-value-sub">$2,700 paid · $1,400 frozen</div>
          <div style="margin-top:10px;">
            <div class="dl-chip overdue">⚖ Dispute Active</div>
          </div>
        </div>
      </div>
      <!-- DISPUTE STRIP -->
      <div class="dispute-strip">
        <span>⚖️</span>
        <div style="flex:1;">Arbitration in progress — $1,400 escrowed and frozen. Verdict expected within 60 hours. <strong>Evidence package assembled automatically.</strong></div>
        <span style="font-size:.75rem;font-weight:700;color:var(--rust);pointer-events:none;">View Dispute →</span>
      </div>
    </a>

    <!-- EMPTY STATE (shown when filter returns nothing) -->
    <div class="empty-state hidden" id="empty-state">
      <div class="empty-icon">📋</div>
      <h4 style="font-family:var(--font-display);font-size:1.3rem;font-weight:500;margin-bottom:8px;">No projects match this filter</h4>
      <p class="text-sm text-muted">Try clearing your filter or <a href="post-job.html" style="color:var(--gold);">post a new project</a>.</p>
    </div>

  </div>
</div>

<!-- TOAST -->
<div class="toast-stack" id="toast-stack"></div>

<script>
function setChip(el, key) {
  document.querySelectorAll('.fchip').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  const cards = document.querySelectorAll('.proj-card');
  let any = false;
  cards.forEach(card => {
    let show = true;
    if(key === 'review')   show = card.classList.contains('status-pending-review');
    if(key === 'dispute')  show = card.classList.contains('status-dispute');
    if(key === 'deadline') show = card.classList.contains('status-active') || card.classList.contains('status-overdue');
    card.style.display = show ? '' : 'none';
    if(show) any = true;
  });
  document.getElementById('empty-state').classList.toggle('hidden', any);
}
function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  s.innerHTML = `<div class="toast ${type==='info'?'':'success'}"><span class="toast-icon">${type==='info'?'ℹ':'✓'}</span><div><div class="toast-title">${type==='info'?'Info':'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(()=>s.innerHTML='',4000);
}
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});
</script>
</body>
</html>
