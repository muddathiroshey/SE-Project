<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/client/completed-projects.php
    Template: Completed Projects — Client View
    Role:     client (authenticated)
    Route:    /my-projects/completed
    ============================================================
    PHP Data contract (from ClientProjectController::completed()):
      $projects     — CompletedProject[] for $client
      $stats        — [ total, total_spent, avg_rating_given,
                        on_time_rate, dispute_rate, repeat_hired ]
      $spend_chart  — monthly spend breakdown array
      $client       — authenticated client record
    Each CompletedProject:
      $p['id'], $p['title'], $p['niche'], $p['contract_ref'],
      $p['specialist'],       — { name, initials, rating, verified }
      $p['total_value'],
      $p['completed_at'],     $p['duration_actual'],
      $p['milestones_count'],
      $p['on_time'],          — bool
      $p['rating_given'],     — int 1–5 (null = not yet rated)
      $p['review_given'],     — string|null
      $p['dispute'],          — null | { outcome, amount_recovered }
      $p['specialist_saved'], — bool: in trusted list
      $p['certificate_url'],
      $p['nda_ref']
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Completed Projects — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/client-completed-projects.css">
</head>
<body>

<!-- TOPNAV -->
<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="dashboard-client.html">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔 <span style="position:absolute;top:0;right:0;width:8px;height:8px;background:var(--rust);border-radius:50%;"></span>
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
      <a class="sidebar-link" href="client-active-projects.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12v12H2V2zm1 1v10h10V3H3z"/></svg>
        Active Projects
        <span class="notif-count" style="margin-left:auto;">3</span>
      </a>
      <a class="sidebar-link active" href="client-completed-projects.html">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M4 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 2v1h6V3H5zm0 3v1h6V6H5zm0 3v1h4V9H5z"/></svg>
        Completed
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Bids</div>
      <a class="sidebar-link" href="#">
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
        <h2>Completed Projects</h2>
        <p class="mt-4">
          <!-- PHP: count($projects).' completed · $'.number_format($stats['total_spent']).' total spent' -->
          12 completed projects · <strong>$36,700 total spent</strong> · 4.8 average specialist rating.
        </p>
      </div>
      <div class="flex gap-10">
        <a href="post-job.html" class="btn btn-primary">+ Post New Project</a>
      </div>
    </div>

    <!-- STAT STRIP -->
    <div class="stat-strip">
      <!-- PHP: $stats object -->
      <div class="strip-cell">
        <div class="strip-val">12</div>
        <div class="strip-lbl">Completed</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:var(--sage);">$36,700</div>
        <div class="strip-lbl">Total Spent</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val">4.8</div>
        <div class="strip-lbl">Avg. Rating Given</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val" style="color:var(--rust);">2.1%</div>
        <div class="strip-lbl">Dispute Rate</div>
      </div>
    </div>

    <!-- FILTER ROW -->
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;align-items:center;">
      <span style="font-size:.8rem;color:var(--ink-muted);">Filter:</span>
      <span class="fchip active" onclick="filterCards(this,'all')">All</span>
      <span class="fchip" onclick="filterCards(this,'data')">🧠 Data Science</span>
      <span class="fchip" onclick="filterCards(this,'legal')">⚖️ Legal</span>
      <span class="fchip" onclick="filterCards(this,'repeat')">🔄 Rehired Specialist</span>
      <span class="fchip" onclick="filterCards(this,'unrated')">⭐ Needs Rating</span>
      <div style="margin-left:auto;display:flex;gap:8px;">
        <select class="form-control" style="width:160px;padding:5px 10px;font-size:.8125rem;">
          <option>Newest First</option>
          <option>Oldest First</option>
          <option>Highest Value</option>
          <option>Highest Rated</option>
        </select>
      </div>
    </div>

    <!-- ══════════ COMPLETED CARDS ══════════ -->
    <!-- PHP: foreach($projects as $p): -->

    <!-- CARD 1: TOP RATED — NEEDS RATING (unrated) -->
    <div class="comp-card top-rated" id="cc-0" data-niche="data" data-unrated="true">

      <!-- UNRATED BANNER -->
      <div class="unrated-banner">
        <span>⭐</span>
        <div style="flex:1;"><strong>You haven't rated Dr. Rania Khalil yet.</strong> Leaving a review helps other clients and improves the platform.</div>
        <button class="btn btn-gold btn-sm" onclick="event.stopPropagation();openRatingModal(0)">Leave Review</button>
      </div>

      <div class="cc-header" onclick="toggleCard(0)">
        <div class="cc-niche-icon ni-data">🧠</div>
        <div style="flex:1;min-width:0;">
          <!-- PHP: htmlspecialchars($p['title']) -->
          <div class="cc-title">Predictive Churn Model — FinCorp Q1 2025</div>
          <div class="cc-meta">
            <span class="flex items-center gap-6">
              <div class="avatar avatar-sm" style="width:20px;height:20px;font-size:.6rem;flex-shrink:0;">DR</div>
              Dr. Rania Khalil
              <span class="badge badge-verified badge-dot" style="font-size:.575rem;">Verified</span>
            </span>
            <span>·</span>
            <!-- PHP: date('M j, Y', $p['completed_at']) -->
            <span>Completed Apr 2, 2025</span>
            <span>·</span>
            <span class="font-mono">CON-NX-3812 · Data Science</span>
            <span>·</span>
            <span>5 milestones · 49 days</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap;">
            <!-- PHP: $p['rating_given'] ? stars : 'Not yet rated' -->
            <div style="color:var(--ink-faint);font-size:.8125rem;font-style:italic;">Not yet rated by you</div>
            <span class="perf-badge pb-ontime">✓ On Time</span>
            <span class="perf-badge pb-budget">↓ Under Budget</span>
          </div>
        </div>
        <div class="cc-right">
          <div class="cc-value">$8,400</div>
          <div class="cc-value-sub">5 milestones · fully paid</div>
          <div style="margin-top:8px;">
            <span class="perf-badge pb-top" style="font-size:.65rem;">⭐ Recommended</span>
          </div>
        </div>
      </div>

      <div class="cc-body" id="cbody-0">
        <div class="cc-drawer-content">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <div>
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Your Review</div>
              <div class="review-display">
                "Dr. Rania delivered an exceptional churn prediction model that immediately improved our retention strategy. The code was clean, well-documented, and production-ready. Outstanding quality and communication throughout."
              </div>
              <div class="flex items-center gap-10">
                <div class="avatar avatar-sm">AT</div>
                <div>
                  <div style="font-weight:700;font-size:.875rem;">You (Amira Tawfik)</div>
                  <div class="stars-display">
                    <span class="star-filled">★</span><span class="star-filled">★</span>
                    <span class="star-filled">★</span><span class="star-filled">★</span>
                    <span class="star-filled">★</span>
                    <span style="font-size:.8125rem;color:var(--ink-muted);margin-left:4px;">5.0</span>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Project Summary</div>
              <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:14px;">
                <div style="padding:10px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Value</div><div class="font-mono font-bold">$8,400</div></div>
                <div style="padding:10px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Duration</div><div class="font-mono font-bold">49 days</div></div>
                <div style="padding:10px;background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Delivery</div><div class="font-mono font-bold" style="color:var(--sage);">On Time</div></div>
              </div>
              <div style="padding:12px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:12px;">
                <div style="font-weight:700;font-size:.875rem;margin-bottom:10px;">📦 Deliverables</div>
                <div style="font-size:.8rem;color:var(--ink-mid);line-height:1.6;">
                  • Trained model (model.pkl)<br>
                  • Feature engineering code<br>
                  • Performance report (PDF)<br>
                  • Documentation & handoff
                </div>
              </div>
              <button class="btn btn-outline btn-sm" style="width:100%;" onclick="showToast('Deliverables downloaded.')">⬇ Download All</button>
            </div>
          </div>
        </div>
        <div class="cc-footer">
          <button class="btn btn-outline btn-sm" onclick="showToast('James Moreau is already in your Trusted Specialists list.')">✦ Trusted Specialist</button>
          <a href="expert-profile.html" class="btn btn-ghost btn-sm">View Profile</a>
        </div>
      </div>
    </div>

    <!-- CARD 2: RATED — LEGAL — REPEAT HIRE -->
    <div class="comp-card on-time" id="cc-1" data-niche="legal" data-repeat="true">
      <div class="cc-header" onclick="toggleCard(1)">
        <div class="cc-niche-icon ni-legal">⚖️</div>
        <div style="flex:1;min-width:0;">
          <div class="cc-title">Initial Legal Framework — MENA Expansion 2024</div>
          <div class="cc-meta">
            <span class="flex items-center gap-6">
              <div class="avatar avatar-sm" style="width:20px;height:20px;font-size:.6rem;flex-shrink:0;">JM</div>
              James Moreau
              <span class="badge badge-verified badge-dot" style="font-size:.575rem;">Verified</span>
            </span>
            <span>·</span>
            <span>Completed Feb 14, 2025</span>
            <span>·</span>
            <span class="font-mono">CON-NX-4104 · Legal Consulting</span>
            <span>·</span>
            <span>3 milestones · 38 days</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap;">
            <div class="stars-display">
              <span class="star-filled">★</span><span class="star-filled">★</span>
              <span class="star-filled">★</span><span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span style="font-size:.8125rem;color:var(--ink-muted);margin-left:4px;">5.0</span>
            </div>
            <span class="perf-badge pb-ontime">✓ On Time</span>
            <span class="perf-badge pb-repeat">🔄 Rehired</span>
          </div>
        </div>
        <div class="cc-right">
          <div class="cc-value">$10,500</div>
          <div class="cc-value-sub">3 milestones · fully paid</div>
        </div>
      </div>

      <div class="cc-body" id="cbody-1">
        <div class="cc-drawer-content">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <div>
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Your Review</div>
              <div class="review-display">
                "James delivered a comprehensive cross-border legal framework that addressed every jurisdiction we operate in. His structured approach — submitting a gap analysis before drafting — saved us significant revision time."
              </div>
              <div class="flex items-center gap-10">
                <div class="avatar avatar-sm">AT</div>
                <div>
                  <div style="font-weight:700;font-size:.875rem;">You (Amira Tawfik)</div>
                  <div class="stars-display">
                    <span class="star-filled">★</span><span class="star-filled">★</span>
                    <span class="star-filled">★</span><span class="star-filled">★</span>
                    <span class="star-filled">★</span>
                    <span style="font-size:.8125rem;color:var(--ink-muted);margin-left:4px;">5.0</span>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Project Summary</div>
              <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:14px;">
                <div style="padding:10px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Value</div><div class="font-mono font-bold">$10,500</div></div>
                <div style="padding:10px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Duration</div><div class="font-mono font-bold">38 days</div></div>
                <div style="padding:10px;background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Delivery</div><div class="font-mono font-bold" style="color:var(--sage);">On Time</div></div>
              </div>
              <div style="padding:12px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:12px;">
                <div style="font-weight:700;font-size:.875rem;margin-bottom:10px;">📦 Deliverables</div>
                <div style="font-size:.8rem;color:var(--ink-mid);line-height:1.6;">
                  • Legal brief & compliance memo<br>
                  • Contract templates<br>
                  • Policy documentation<br>
                  • Executive summary
                </div>
              </div>
              <button class="btn btn-outline btn-sm" style="width:100%;" onclick="showToast('Deliverables downloaded.')">⬇ Download All</button>
            </div>
          </div>
        </div>
        <div class="cc-footer">
          <button class="btn btn-outline btn-sm" onclick="showToast('James Moreau is already in your Trusted Specialists list.')">✦ Trusted Specialist</button>
          <a href="expert-profile.html" class="btn btn-ghost btn-sm">View Profile</a>
        </div>
      </div>
    </div>

    <!-- CARD 3: DISPUTE RESOLVED -->
    <div class="comp-card had-dispute" id="cc-2" data-niche="trans">
      <div class="cc-header" onclick="toggleCard(2)">
        <div class="cc-niche-icon ni-trans" style="background:#FBEAE7;border-color:#F0C4BC;">🌐</div>
        <div style="flex:1;min-width:0;">
          <div class="cc-title">Annual Report — DE/EN Technical Translation (2024)</div>
          <div class="cc-meta">
            <span class="flex items-center gap-6">
              <div class="avatar avatar-sm" style="width:20px;height:20px;font-size:.6rem;flex-shrink:0;">LB</div>
              Lena Bergmann
            </span>
            <span>·</span>
            <span>Completed Mar 20, 2025</span>
            <span>·</span>
            <span class="font-mono">CON-NX-3801 · Translation</span>
            <span>·</span>
            <span>4 milestones · 45 days</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap;">
            <div class="stars-display">
              <span class="star-filled">★</span><span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-empty">★</span>
              <span class="star-empty">★</span>
              <span style="font-size:.8125rem;color:var(--ink-muted);margin-left:4px;">3.0</span>
            </div>
            <span class="perf-badge pb-disputed">⚖ Dispute Resolved</span>
          </div>
        </div>
        <div class="cc-right">
          <div class="cc-value">$2,870</div>
          <div class="cc-value-sub" style="color:var(--rust);">of $4,100 · 70/30 verdict</div>
        </div>
      </div>

      <div class="cc-body" id="cbody-2">
        <div class="cc-drawer-content">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <div>
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:10px;font-family:var(--font-body);">Dispute Outcome</div>
              <div class="dispute-outcome-card">
                <div class="flex items-center gap-8 mb-10">
                  <span class="do-verdict do-split">70/30 Split</span>
                  <span class="text-xs text-muted font-mono">DSP-NX-3801 · Resolved Apr 13, 2025</span>
                </div>
                <p style="color:var(--ink-mid);font-size:.875rem;line-height:1.65;">Arbiter found the specialist's ISO-standard terminology was defensible but deviation from the agreed glossary was not communicated per the contract's amendment clause. <strong>$2,870 released to specialist, $1,230 returned to you.</strong></p>
              </div>
              <div style="margin-top:14px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 14px;font-size:.8125rem;color:var(--ink-mid);">
                💡 <strong>Lesson for future projects:</strong> Add a glossary deviation clause to your NDA template and require a change-request form before any terminology amendments.
              </div>
            </div>
            <div>
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Your Review</div>
              <div class="review-display">
                "The translation quality was technically sound but the deviation from our agreed glossary without prior notice caused significant internal disruption. The dispute process was handled fairly."
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-top:14px;">
                <div style="padding:10px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Paid</div><div class="font-mono font-bold">$2,870</div></div>
                <div style="padding:10px;background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Recovered</div><div class="font-mono font-bold" style="color:var(--sage);">$1,230</div></div>
                <div style="padding:10px;background:#FBEAE7;border:1px solid #F0C4BC;border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Dispute</div><div class="font-mono font-bold" style="color:var(--rust);">Won Partial</div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="cc-footer">
          <a href="dispute.html" class="btn btn-ghost btn-sm">View Full Dispute Record</a>
        </div>
      </div>
    </div>

    <!-- CARD 4: NORMAL COMPLETED / OLDER -->
    <div class="comp-card on-time" id="cc-3" data-niche="data" data-repeat="true">
      <div class="cc-header" onclick="toggleCard(3)">
        <div class="cc-niche-icon ni-data">🧠</div>
        <div style="flex:1;min-width:0;">
          <div class="cc-title">Customer Segmentation — Retail Banking Dashboard</div>
          <div class="cc-meta">
            <span class="flex items-center gap-6">
              <div class="avatar avatar-sm" style="width:20px;height:20px;font-size:.6rem;flex-shrink:0;">DR</div>
              Dr. Rania Khalil
              <span class="badge badge-verified badge-dot" style="font-size:.575rem;">Verified</span>
            </span>
            <span>·</span>
            <span>Completed Jan 28, 2025</span>
            <span>·</span>
            <span class="font-mono">CON-NX-3344 · Data Science</span>
            <span>·</span>
            <span>4 milestones · 30 days</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap;">
            <div class="stars-display">
              <span class="star-filled">★</span><span class="star-filled">★</span>
              <span class="star-filled">★</span><span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span style="font-size:.8125rem;color:var(--ink-muted);margin-left:4px;">5.0</span>
            </div>
            <span class="perf-badge pb-ontime">✓ On Time</span>
            <span class="perf-badge pb-repeat">🔄 Rehired</span>
          </div>
        </div>
        <div class="cc-right">
          <div class="cc-value">$6,200</div>
          <div class="cc-value-sub">4 milestones · fully paid</div>
        </div>
      </div>

      <div class="cc-body" id="cbody-3">
        <div class="cc-drawer-content">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <div>
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Your Review</div>
              <div class="review-display">
                "Rania's segmentation model identified three customer cohorts our internal team had completely missed. Delivered 2 days ahead of schedule. The Tableau dashboard was immediately usable by our business team."
              </div>
              <div class="flex items-center gap-10">
                <div class="avatar avatar-sm">AT</div>
                <div>
                  <div style="font-weight:700;font-size:.875rem;">You (Amira Tawfik)</div>
                  <div class="stars-display">
                    <span class="star-filled">★</span><span class="star-filled">★</span>
                    <span class="star-filled">★</span><span class="star-filled">★</span>
                    <span class="star-filled">★</span>
                    <span style="font-size:.8125rem;color:var(--ink-muted);margin-left:4px;">5.0</span>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Project Summary</div>
              <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:14px;">
                <div style="padding:10px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Value</div><div class="font-mono font-bold">$6,200</div></div>
                <div style="padding:10px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Duration</div><div class="font-mono font-bold">30 days</div></div>
                <div style="padding:10px;background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Delivery</div><div class="font-mono font-bold" style="color:var(--sage);">On Time</div></div>
              </div>
              <div style="padding:12px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:12px;">
                <div style="font-weight:700;font-size:.875rem;margin-bottom:10px;">📦 Deliverables</div>
                <div style="font-size:.8rem;color:var(--ink-mid);line-height:1.6;">
                  • Segmentation model (ML)<br>
                  • Tableau dashboard<br>
                  • Cohort analysis report<br>
                  • Implementation guide
                </div>
              </div>
              <button class="btn btn-outline btn-sm" style="width:100%;" onclick="showToast('Deliverables downloaded.')">⬇ Download All</button>
            </div>
          </div>
        </div>
        <div class="cc-footer">
          <button class="btn btn-outline btn-sm" onclick="showToast('James Moreau is already in your Trusted Specialists list.')">✦ Trusted Specialist</button>
          <a href="expert-profile.html" class="btn btn-ghost btn-sm">View Profile</a>
        </div>
      </div>
    </div>

    <!-- LOAD MORE -->
    <div style="text-align:center;padding:24px 0;border-top:1px solid var(--border);margin-top:4px;">
      <p class="text-sm text-muted mb-12">Showing 4 of 12 completed projects</p>
      <button class="btn btn-outline" onclick="showToast('Loading 8 more projects…','info')">Load 8 More</button>
    </div>

  </div>
</div>

<!-- ══════════ RATING MODAL ══════════ -->
<div id="rating-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3 id="rating-modal-title">Rate Dr. Rania Khalil</h3>
        <p class="text-sm text-muted mt-4">Your review is public and helps other clients make informed hiring decisions.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('rating-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">

      <!-- OVERALL RATING -->
      <div class="form-group">
        <label class="form-label">Overall Rating</label>
        <div class="star-input" id="star-input">
          <button type="button" class="star-btn" onclick="setStars(1)">★</button>
          <button type="button" class="star-btn" onclick="setStars(2)">★</button>
          <button type="button" class="star-btn" onclick="setStars(3)">★</button>
          <button type="button" class="star-btn" onclick="setStars(4)">★</button>
          <button type="button" class="star-btn" onclick="setStars(5)">★</button>
        </div>
      </div>

      <!-- DIMENSION RATINGS -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
        <div class="form-group" style="margin:0;">
          <label class="form-label text-xs">Brief Quality &amp; Clarity</label>
          <select class="form-control" style="font-size:.875rem;">
            <option>5 — Exceptional</option><option>4 — Good</option><option>3 — Satisfactory</option><option>2 — Poor</option><option>1 — Unacceptable</option>
          </select>
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label text-xs">Communication</label>
          <select class="form-control" style="font-size:.875rem;">
            <option>5 — Exceptional</option><option>4 — Good</option><option>3 — Satisfactory</option><option>2 — Poor</option><option>1 — Unacceptable</option>
          </select>
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label text-xs">Deliverable Quality</label>
          <select class="form-control" style="font-size:.875rem;">
            <option>5 — Exceptional</option><option>4 — Good</option><option>3 — Satisfactory</option><option>2 — Poor</option><option>1 — Unacceptable</option>
          </select>
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label text-xs">Milestone Adherence</label>
          <select class="form-control" style="font-size:.875rem;">
            <option>5 — Exceptional</option><option>4 — Good</option><option>3 — Satisfactory</option><option>2 — Poor</option><option>1 — Unacceptable</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Written Review</label>
        <textarea class="form-control" rows="5" id="review-text"
          placeholder="Describe your experience working with this specialist. Be specific — your review helps other clients and the specialist's career on Nexus."
          oninput="countRChars(this)"></textarea>
        <div class="flex justify-between mt-4">
          <p class="form-hint">Min. 50 characters. Specific reviews are more helpful than short ones.</p>
          <span class="char-counter" id="rchc">0 / 800</span>
        </div>
      </div>

      <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:12px;">
        <input type="checkbox" id="review-rehire" style="accent-color:var(--gold);" checked>
        <label for="review-rehire" style="font-size:.875rem;color:var(--ink-mid);">I would hire this specialist again</label>
      </div>

      <div style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" id="review-add-trusted" style="accent-color:var(--gold);" checked>
        <label for="review-add-trusted" style="font-size:.875rem;color:var(--ink-mid);">Add to my Trusted Specialists list (auto-invite on future relevant projects)</label>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('rating-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="submitReview()">Submit Review</button>
    </div>
  </div>
</div>

<!-- EXPORT MODAL -->
<div id="export-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3>Export Project History</h3>
      <button class="modal-close" onclick="document.getElementById('export-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-row">
        <div class="form-group"><label class="form-label">From</label><input type="month" class="form-control" value="2024-01"></div>
        <div class="form-group"><label class="form-label">To</label><input type="month" class="form-control" value="2025-04"></div>
      </div>
      <div class="form-group">
        <label class="form-label">Format</label>
        <select class="form-control">
          <option>PDF — Project History Summary</option>
          <option>CSV — Raw Data</option>
          <option>PDF + CSV</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Include</label>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <label style="display:flex;gap:8px;font-size:.875rem;cursor:pointer;"><input type="checkbox" checked style="accent-color:var(--gold);"> Spend breakdown per project</label>
          <label style="display:flex;gap:8px;font-size:.875rem;cursor:pointer;"><input type="checkbox" checked style="accent-color:var(--gold);"> Specialist names &amp; ratings</label>
          <label style="display:flex;gap:8px;font-size:.875rem;cursor:pointer;"><input type="checkbox" style="accent-color:var(--gold);"> Dispute records</label>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('export-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('export-modal').classList.add('hidden');showToast('Export generated. Download starting shortly.')">Generate Export</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast-stack" id="toast-stack"></div>

<script>
/* ── DROPDOWN TOGGLE ── */
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});

/* ── CARD TOGGLE ── */
function toggleCard(i) {
  const body = document.getElementById('cbody-' + i);
  if(body) body.classList.toggle('open');
}

/* ── FILTER ── */
function filterCards(el, key) {
  document.querySelectorAll('.fchip').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.comp-card').forEach(card => {
    let show = true;
    if(key === 'data')    show = card.dataset.niche === 'data';
    if(key === 'legal')   show = card.dataset.niche === 'legal';
    if(key === 'repeat')  show = card.dataset.repeat === 'true';
    if(key === 'unrated') show = card.dataset.unrated === 'true';
    card.style.display = show ? '' : 'none';
  });
}

/* ── RATING MODAL ── */
let currentRatingCard = null;
function openRatingModal(cardIdx) {
  currentRatingCard = cardIdx;
  // PHP: update title with specialist name
  const titles = ['Dr. Rania Khalil', 'James Moreau', 'Lena Bergmann', 'Dr. Rania Khalil'];
  document.getElementById('rating-modal-title').textContent = 'Rate ' + (titles[cardIdx] || 'Specialist');
  document.getElementById('rating-modal').classList.remove('hidden');
  setStars(5); // default 5 stars
}

let selectedStars = 5;
function setStars(n) {
  selectedStars = n;
  document.querySelectorAll('.star-btn').forEach((btn, i) => {
    btn.classList.toggle('active', i < n);
  });
}

function countRChars(el) {
  const n = el.value.length;
  const c = document.getElementById('rchc');
  if(c) {
    c.textContent = n + ' / 800';
    c.className = 'char-counter' + (n > 720 ? ' warn' : '');
  }
}

function submitReview() {
  const text = document.getElementById('review-text')?.value?.trim();
  if(!text || text.length < 50) {
    showToast('Please write a review of at least 50 characters.', 'warn');
    return;
  }
  document.getElementById('rating-modal').classList.add('hidden');
  // Remove unrated banner if present
  if(currentRatingCard !== null) {
    const card = document.getElementById('cc-' + currentRatingCard);
    if(card) {
      const banner = card.querySelector('.unrated-banner');
      if(banner) banner.remove();
      card.removeAttribute('data-unrated');
    }
  }
  showToast('Review submitted. Thank you — your feedback helps the community.');
}

/* ── TOAST ── */
function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  const icons = {success:'✓', warn:'⚠', info:'ℹ'};
  const cls   = {success:'success', warn:'warning', info:''};
  s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type==='warn'?'Notice':type==='info'?'Info':'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML = '', 4500);
}
</script>
</body>
</html>
