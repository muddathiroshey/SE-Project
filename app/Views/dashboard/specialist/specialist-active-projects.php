<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/specialist/active-projects.php
    Template: Active Projects — Specialist View
    Role:     specialist (authenticated)
    Route:    /my-projects/active
    ============================================================
    PHP Data contract (from SpecialistProjectController::active()):
      $projects     — ActiveProject[] for $specialist
      $stats        — [ total, total_escrowed, due_soon,
                        pending_reviews, overdue ]
      $specialist   — authenticated specialist record
    Each ActiveProject:
      $p['id'], $p['title'], $p['niche'], $p['contract_ref'],
      $p['client'],           — { name, initials, rating, verified }
      $p['total_value'],      $p['paid_to_date'],
      $p['current_milestone'],
      $p['milestones_total'], $p['milestones_done'],
      $p['next_deadline'],    $p['days_remaining'],
      $p['escrowed_next'],
      $p['status'],  — active|pending_review|revision|overdue|dispute
      $p['unread_messages'],
      $p['started_at'],       $p['progress_pct']

      Logos for different niches:
        Legal -> ⚖️
        Data scince and machine learning -> 🧠
        Technical Translation -> 🌐
        Financial Modeling -> 📈
        Biomedical Research -> 🔬
        Cybersecurity Audit -> 🔐
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Specialist Active Projects — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/specialist-active-projects.css">
</head>
<body>

<!-- ══════════ TOPNAV ══════════ -->
<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="dashboard-freelancer.html">Nexus<span>.</span></a>
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
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Specialist Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="expert-profile.html">My Profile</a>
          <a class="dropdown-item" href="escrow-wallet.html">Wallet &amp; Escrow</a>
          <a class="dropdown-item" href="#">Account Settings</a>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="login.html" style="color:var(--rust);">Sign Out</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="app-shell">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-label">Overview</div>
      <a class="sidebar-link" href="dashboard-freelancer.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
        Dashboard
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Work</div>
      <a class="sidebar-link active" href="project-detail.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4a1 1 0 0 1 1-1h3l1 1h6a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4z"/></svg>
        Active Projects
        <span class="notif-count" style="margin-left:auto;">2</span>
      </a>
      <a class="sidebar-link" href="my-bids.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M3 2h10a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v1h8V4H4zm0 2v1h8V6H4z"/></svg>
        My Proposals
        <span class="notif-count" style="margin-left:auto;">5</span>
      </a>
      <a class="sidebar-link" href="specialist-completed-projects.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M6 1h4a1 1 0 0 1 1 1v2H5V2a1 1 0 0 1 1-1z"/><path d="M3 4h10v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z"/></svg>
        Completed Work
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Marketplace</div>
      <a class="sidebar-link" href="#">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M11 11l3 3-1 1-3-3v-1.4A5.5 5.5 0 1 1 11 11zM6.5 11A4.5 4.5 0 1 0 6.5 2a4.5 4.5 0 0 0 0 9z"/></svg>
        Browse Jobs
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Profile</div>
      <a class="sidebar-link" href="expert-profile.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M2 14s1-1.5 6-1.5S14 14 14 14v1H2v-1z"/></svg>
        My Profile
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Finance</div>
      <a class="sidebar-link" href="escrow-wallet.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12v8H2V4zm1 1v6h10V5H3zm2 2h2v2H5V7z"/></svg>
        Earnings &amp; Payouts
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Support</div>
      <a class="sidebar-link" href="dispute.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1zm0 4a.75.75 0 0 0 0 1.5.75.75 0 0 0 0-1.5zm-.25 3v4.5h1.5V7h-1.5z"/></svg>
        Disputes
      </a>
      <a class="sidebar-link" href="messages.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 1h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-3l-4 3v-3H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>
        Messages
      </a>
    </div>
  </aside>

  <!-- CONTENT AREA -->
  <main class="main-content">

    <!-- PAGE HEADER -->
    <div class="page-header flex justify-between items-center">
      <div>
        <div class="breadcrumb">Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Projects</div>
        <h2>Active Projects</h2>
        <p class="mt-4">
          <!-- PHP: count($projects).' active contracts' -->
          3 active contracts · <strong style="color:var(--gold);">$4,500 Pending Funds
          </strong>
        </p>
      </div>
    </div>

    <!-- STAT STRIP -->
    <div class="stat-strip">
      <div class="strip-cell">
        <div class="strip-val">3</div>
        <div class="strip-lbl">All Active</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:#1A4A8A;">1</div>
        <div class="strip-lbl">Review Requested</div>
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
        <div class="strip-val" style="color:var(--gold);">$4,500</div>
        <div class="strip-lbl">Pending Funds</div>
      </div>
    </div>

    <!-- ══════════ PROJECT CARDS ══════════ -->
    <!-- PHP: foreach($projects as $p): -->


    <!-- PROJECT 1: DEADLINE APPROACHING -->
    <a href="project-detail.html" class="proj-card status-pending-review" style="text-decoration:none;display:block;" id="pc-1">
      <div class="proj-card-body">
        <div class="proj-niche-icon ni-data">🧠</div>
        <div style="min-width:0;">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap;">
            <div style="font-family:var(--font-display);font-size:1.05rem;font-weight:600;color:var(--ink);">MENA Expansion — Cross-Border Contract Review</div>
            <span class="status-pill sp-overdue">⚠ Deadline Approaching</span>
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
            <span>Data Science</span>
            <span>·</span>
            <span>Started Apr 3, 2025</span>
          </div>
          <div style="font-size:.75rem;color:var(--rust);margin-top:5px;font-weight:700;">
            Phase 1 submitted Apr 15 — deadline approaching Apr 18 if no action
          </div>
        </div>
        <div class="proj-right">
          <div class="proj-value">$12,000</div>
          <div class="proj-value-sub">$0 paid · $3,000 escrowed</div>
          <div style="margin-top:10px;">
            <div class="dl-chip overdue">⚠ Deadline Apr 18</div>
          </div>
        </div>
      </div>
      <div class="proj-progress-row">
        <div class="progress-bar" style="flex:1;height:6px;">
          <div class="progress-fill" style="width:33%;"></div>
        </div>
        <span style="font-size:.75rem;font-family:var(--font-mono);color:var(--ink-muted);">Phase 1 of 3 delivered</span>
        <span style="font-size:.75rem;color:var(--rust);">·</span>
        <span style="font-size:.75rem;color:var(--rust);">Deadline approaching — action required</span>
        <span style="margin-left:auto;" class="flex gap-8">
          <span class="btn btn-ghost btn-sm" style="font-size:.75rem;pointer-events:none;color:var(--rust);">Review Now →</span>
        </span>
      </div>
    </a>

    <!-- PROJECT 2: ACTIVE · PENDING SPECIALIST DELIVERY -->
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

    <!-- PROJECT 3: DISPUTE ACTIVE -->
    <a href="project-detail.html" class="proj-card status-dispute" style="text-decoration:none;display:block;" id="pc-2">
      <div class="proj-card-body">
        <div class="proj-niche-icon ni-data">🧠</div>
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
            <span>Data Science</span>
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