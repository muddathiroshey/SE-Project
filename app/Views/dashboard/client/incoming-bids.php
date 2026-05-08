<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/client/incoming-bids.php
    Template: Incoming Bids — Client Overview
    Role:     client (authenticated)
    Route:    /my-bids
              /my-bids?project={job_id}
              /my-bids?status=new
    ============================================================
    PHP Data contract (from BidOverviewController::index()):
      $projects_with_bids  — JobRecord[] that have at least one bid
      $all_bids            — BidRecord[] across all projects (paginated)
      $active_filters      — current filter state
      $stats               — [ total_bids, new_bids, shortlisted,
                               interviews, avg_match, projects_open ]
      $client              — authenticated client
    Each BidRecord:
      $b['id'], $b['job'], $b['specialist'],
      $b['bid_total'],     $b['job_budget'],
      $b['milestones'],    $b['duration_days'],
      $b['match_score'],
      $b['status'],        — new|reviewed|shortlisted|
                              interview|accepted|declined|withdrawn
      $b['submitted_at'],  $b['cover_letter_excerpt'],
      $b['attachments_count'],
      $b['milestone_changes'], — bool
      $b['can_withdraw'],
      $b['specialist']     — { name, initials, rating, projects,
                               verified, niche, response_time }
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Incoming Bids — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/incoming-bids.css">
</head>
<body>

<!-- TOPNAV -->
<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="dashboard-client.php">Nexus<span>.</span></a>
    <div class="topnav-actions" style="margin-left: auto;">
      <a href="#" class="btn btn-ghost btn-icon" style="position:relative;">
        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg> <span style="position:absolute;top:0;right:0;width:8px;height:8px;background:var(--rust);border-radius:50%;"></span>
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
          <a class="dropdown-item" href="/dashboard">Wallet &amp; Escrow</a>
          <a class="dropdown-item" href="#">Account Settings</a>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/login" style="color:var(--rust);">Sign Out</a>
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
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
        Dashboard
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Projects</div>
      <a class="sidebar-link" href="/post-job">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/></svg>
        Post New Project
      </a>
      <a class="sidebar-link" href="client-active-projects.php">
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
      <a class="sidebar-link active" href="/incoming-bids">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v7h10V4H3zm1 1h2v2H4V5zm4 0h2v2H8V5zm4 0h2v2h-2V5z"/></svg>
        My Bids
        <span class="notif-count" style="margin-left:auto;">12</span>
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Marketplace</div>
      <a class="sidebar-link" href="/browse-experts">
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
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm0 2v6h12V6H2zm9 1h2v2h-2V7z"/></svg>
        Escrow &amp; Wallet
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Support</div>
      <a class="sidebar-link" href="/dispute">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1zm0 3a.75.75 0 0 0 0 1.5.75.75 0 0 0 0-1.5zm-.25 3v4.5h1.5V7h-1.5z"/></svg>
        Disputes
        <span class="notif-count" style="margin-left:auto;background:transparent;border-color:var(--rust);color:var(--rust);">1</span>
      </a>
      <a class="sidebar-link" href="/chat">
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
        <div class="breadcrumb">Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Incoming Bids</div>
        <h2>Incoming Bids</h2>
        <!-- PHP: $stats object -->
        <p class="mt-4">
          <strong style="color:var(--gold);">12 new proposals</strong> across 2 open projects · 1 interview scheduled.
        </p>
      </div>
    </div>

    <!-- STAT STRIP -->
    <div class="stat-strip">
      <div class="strip-cell" onclick="filterByStatus('all',this)">
        <!-- PHP: $stats['total_bids'] -->
        <div class="strip-val">17</div>
        <div class="strip-lbl">Total Bids</div>
      </div>
      <div class="strip-cell" onclick="filterByStatus('new',this)">
        <!-- PHP: $stats['new_bids'] -->
        <div class="strip-val" style="color:var(--gold);">12</div>
        <div class="strip-lbl">Unreviewed</div>
      </div>
      <div class="strip-cell" onclick="filterByStatus('interview',this)">
        <div class="strip-val" style="color:#1A4A8A;">1</div>
        <div class="strip-lbl">Interview</div>
      </div>
      <div class="strip-cell" onclick="filterByStatus('declined',this)">
        <div class="strip-val" style="color:var(--ink-muted);">1</div>
        <div class="strip-lbl">Declined</div>
      </div>
      <div class="strip-cell">
        <!-- PHP: $stats['avg_match'] -->
        <div class="strip-val">84%</div>
        <div class="strip-lbl">Avg. Match</div>
      </div>
    </div>

    <!-- PROJECT FILTER PILLS -->
    <div class="proj-filter-row" id="proj-filter-row">
      <span style="font-size:.8rem;color:var(--ink-muted);align-self:center;">Project:</span>
      <div class="proj-pill active" onclick="filterByProject(this,'all')">
        All Projects
        <span class="proj-pill-count">17</span>
      </div>
      <!-- PHP: foreach($projects_with_bids as $proj): -->
      <div class="proj-pill" onclick="filterByProject(this,'4821')">
        ⚖️ MENA Expansion — Contract Review
        <span class="proj-pill-count">7</span>
      </div>
      <div class="proj-pill" onclick="filterByProject(this,'5102')">
        🧠 Anomaly Detection Pipeline
        <span class="proj-pill-count">10</span>
      </div>
    </div>

    <!-- STATUS FILTER CHIPS -->
    <div class="filter-row">
      <span style="font-size:.8rem;color:var(--ink-muted);">Status:</span>
      <span class="fchip active" onclick="filterChip(this,'all')">All</span>
      <span class="fchip" onclick="filterChip(this,'new')">● New</span>
      <span class="fchip" onclick="filterChip(this,'interview')">🎙 Interview</span>
      <span class="fchip" onclick="filterChip(this,'reviewed')">Reviewed</span>
      <span class="fchip" onclick="filterChip(this,'declined')">Declined</span>
      <div style="margin-left:auto;display:flex;gap:8px;align-items:center;">
        <span style="font-size:.8rem;color:var(--ink-muted);">Sort:</span>
        <select class="form-control" style="width:160px;padding:5px 10px;font-size:.8125rem;">
          <option>Best Match</option>
          <option>Newest First</option>
          <option>Price — Low to High</option>
          <option>Price — High to Low</option>
          <option>Highest Rated Specialist</option>
          <option>Shortest Duration</option>
        </select>
      </div>
    </div>

    <!-- ══════════ GROUPED VIEW ══════════ -->
    <div id="view-grouped-content">

      <!-- ── PROJECT 1: MENA CONTRACT REVIEW ── -->
      <div class="project-section" data-project="4821">
        <div class="project-section-header" onclick="toggleSection(this)">
          <div class="ps-niche-icon" style="background:#EBF3EA;border:1px solid #C5DBC2;">⚖️</div>
          <div style="flex:1;min-width:0;">
            <div class="ps-title">MENA Expansion — Cross-Border Contract Review</div>
            <div style="font-size:.75rem;color:var(--ink-muted);margin-top:2px;display:flex;gap:10px;flex-wrap:wrap;">
              <span class="ps-ref">NX-2025-4821</span>
              <span>Legal Consulting</span>
              <span>Budget: $12,000</span>
              <span style="color:var(--gold);font-weight:700;">⏱ Closes in 11 days</span>
            </div>
          </div>
          <div class="flex items-center gap-16" style="flex-shrink:0;">
            <span class="notif-count" style="background:var(--gold);color:var(--ink);font-size:.75rem;padding:3px 10px;border-radius:2px;">5 new</span>
            <span style="font-size:.8125rem;color:var(--ink-muted);">7 total bids</span>
            <a href="/bid-review" class="btn btn-primary btn-sm" onclick="event.stopPropagation()">Review All →</a>
            <span class="ps-chevron">▾</span>
          </div>
        </div>

        <div class="bids-list">
          <!-- COLUMN HEADER -->
          <div class="bid-row header-row">
            <div class="brc center"></div>
            <div class="brc specialist-col">Specialist</div>
            <div class="brc center">Match</div>
            <div class="brc center">Bid Amount</div>
            <div class="brc center">Duration</div>
            <div class="brc center">Status</div>
          </div>

          <!-- BID 1 — ACCEPTED -->
          <div class="bid-row is-new" id="br-0" data-status="new" data-project="4821">
            <div class="brc center">
              <div class="avatar avatar-sm">DR</div>
            </div>
            <div class="brc specialist-col">
              <div class="spec-mini">
                <div>
                  <div class="spec-mini-name">
                    Dr. Rania Khalil
                    <span class="badge badge-verified badge-dot" style="font-size:.575rem;margin-left:4px;">Verified</span>
                  </div>
                  <div class="spec-mini-sub">
                    <div class="stars" style="font-size:.7rem;display:inline;">★★★★★</div>
                    4.97 · 83 projects · &lt;2h response
                  </div>
                </div>
              </div>
              <div style="margin-top:5px;font-size:.75rem;color:var(--ink-muted);font-style:italic;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:280px;">
                "I have reviewed your brief carefully — the GDPR cross-border transfer requirement is precisely my specialisation…"
              </div>
            </div>
            <div class="brc center">
              <span class="match-pill mp-high">95%</span>
            </div>
            <div class="brc center">
              <div style="font-family:var(--font-mono);font-weight:700;">$12,000</div>
              <span class="amount-delta ad-equal">= budget</span>
            </div>
            <div class="brc center">
              <div style="font-family:var(--font-mono);">49d</div>
              <div style="font-size:.7rem;color:var(--ink-muted);">3 phases</div>
            </div>
            <div class="brc center">
              <span class="bid-status-chip bsc-new">● New</span>
            </div>
          </div>

          <!-- BID 2 — INTERVIEW -->
          <div class="bid-row is-interview" id="br-1" data-status="interview" data-project="4821">
            <div class="brc center"><div class="avatar avatar-sm">JM</div></div>
            <div class="brc specialist-col">
              <div class="spec-mini">
                <div>
                  <div class="spec-mini-name">James Moreau <span class="badge badge-verified badge-dot" style="font-size:.575rem;margin-left:4px;">Verified</span></div>
                  <div class="spec-mini-sub"><div class="stars" style="font-size:.7rem;display:inline;">★★★★★</div> 4.91 · 61 projects · &lt;4h response</div>
                </div>
              </div>
              <div style="margin-top:5px;font-size:.75rem;color:var(--ink-muted);font-style:italic;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                "I have 12 years of cross-border MENA commercial law experience, including extensive UAE and KSA market entry work…"
              </div>
            </div>
            <div class="brc center"><span class="match-pill mp-high">88%</span></div>
            <div class="brc center">
              <div style="font-family:var(--font-mono);font-weight:700;">$11,200</div>
              <span class="amount-delta ad-under">↓ $800 under</span>
            </div>
            <div class="brc center"><div style="font-family:var(--font-mono);">44d</div><div style="font-size:.7rem;color:var(--ink-muted);">3 phases</div></div>
            <div class="brc center"><span class="bid-status-chip bsc-interview">🎙 Interview Set</span></div>
          </div>

          <!-- BID 3 — NEW -->
          <div class="bid-row is-new" id="br-2" data-status="new" data-project="4821">
            <div class="brc center" style="position:relative;">
              <div class="avatar avatar-sm">NA</div>
              <div class="new-dot" style="position:absolute;top:10px;right:8px;"></div>
            </div>
            <div class="brc specialist-col">
              <div class="spec-mini">
                <div>
                  <div class="spec-mini-name">Nadia Al-Farsi</div>
                  <div class="spec-mini-sub"><div class="stars" style="font-size:.7rem;display:inline;">★★★★☆</div> 4.82 · 29 projects</div>
                </div>
              </div>
              <div style="margin-top:5px;font-size:.75rem;color:var(--ink-muted);font-style:italic;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                "My experience spans commercial law across North Africa and the GCC with a specific focus on technology sector…"
              </div>
            </div>
            <div class="brc center"><span class="match-pill mp-medium">81%</span></div>
            <div class="brc center">
              <div style="font-family:var(--font-mono);font-weight:700;">$14,500</div>
              <span class="amount-delta ad-over">↑ $2,500 over</span>
            </div>
            <div class="brc center"><div style="font-family:var(--font-mono);">52d</div><div style="font-size:.7rem;color:var(--ink-muted);">3 phases</div></div>
            <div class="brc center"><span class="bid-status-chip bsc-new">● New</span></div>
          </div>

          <!-- BID 4, 5, 6 (collapsed new bids) -->
          <div class="bid-row is-new" id="br-3" data-status="new" data-project="4821">
            <div class="brc center"><div class="avatar avatar-sm">YB</div></div>
            <div class="brc specialist-col">
              <div class="spec-mini"><div><div class="spec-mini-name">Youssef Benali</div><div class="spec-mini-sub">4.74 · 18 projects</div></div></div>
            </div>
            <div class="brc center"><span class="match-pill mp-medium">74%</span></div>
            <div class="brc center"><div style="font-family:var(--font-mono);font-weight:700;">$9,800</div><span class="amount-delta ad-under">↓ $2,200 under</span></div>
            <div class="brc center"><div style="font-family:var(--font-mono);">56d</div></div>
            <div class="brc center"><span class="bid-status-chip bsc-new">● New</span></div>
          </div>

          <!-- DECLINED BID -->
          <div class="bid-row is-declined" id="br-declined-0" data-status="declined" data-project="4821">
            <div class="brc center"><div class="avatar avatar-sm">MF</div></div>
            <div class="brc specialist-col"><div class="spec-mini"><div><div class="spec-mini-name" style="color:var(--ink-muted);">Marcus Fernandez</div><div class="spec-mini-sub">4.61 · 12 projects</div></div></div></div>
            <div class="brc center"><span class="match-pill mp-low">62%</span></div>
            <div class="brc center"><div style="font-family:var(--font-mono);font-weight:700;color:var(--ink-muted);">$16,200</div><span class="amount-delta ad-over">↑ $4,200 over</span></div>
            <div class="brc center"><div style="font-family:var(--font-mono);color:var(--ink-muted);">60d</div></div>
            <div class="brc center"><span class="bid-status-chip bsc-declined">Declined</span></div>
          </div>

          <!-- MORE BIDS BUTTON -->
          <div style="padding:12px 20px;background:var(--ivory-deep);border-top:1px solid var(--border);text-align:center;">
            <button class="btn btn-ghost btn-sm" style="font-size:.8125rem;" onclick="showToast('Showing all 7 bids for this project.','info')">
              View all 7 bids for this project →
            </button>
          </div>
        </div>
      </div>

      <!-- ── PROJECT 2: ANOMALY DETECTION ── -->
      <div class="project-section" data-project="5102">
        <div class="project-section-header" onclick="toggleSection(this)">
          <div class="ps-niche-icon" style="background:var(--gold-pale);border:1px solid var(--gold-light);">🧠</div>
          <div style="flex:1;min-width:0;">
            <div class="ps-title">Real-Time Anomaly Detection Pipeline — Banking Sector</div>
            <div style="font-size:.75rem;color:var(--ink-muted);margin-top:2px;display:flex;gap:10px;flex-wrap:wrap;">
              <span class="ps-ref">NX-2025-5102</span>
              <span>Data Science</span>
              <span>Budget: $12,000–$18,000</span>
              <span style="color:var(--ink-muted);">⏱ Closes in 19 days</span>
            </div>
          </div>
          <div class="flex items-center gap-16" style="flex-shrink:0;">
            <span class="notif-count" style="background:var(--gold);color:var(--ink);font-size:.75rem;padding:3px 10px;border-radius:2px;">7 new</span>
            <span style="font-size:.8125rem;color:var(--ink-muted);">10 total bids</span>
            <a href="/bid-review" class="btn btn-primary btn-sm" onclick="event.stopPropagation()">Review All →</a>
            <span class="ps-chevron">▾</span>
          </div>
        </div>

        <div class="bids-list">
          <div class="bid-row header-row">
            <div class="brc center"></div>
            <div class="brc specialist-col">Specialist</div>
            <div class="brc center">Match</div>
            <div class="brc center">Bid Amount</div>
            <div class="brc center">Duration</div>
            <div class="brc center">Status</div>
          </div>

          <!-- BID: DATA SCIENCE -->
          <div class="bid-row is-new" id="br-4" data-status="new" data-project="5102">
            <div class="brc center"><div class="avatar avatar-sm">KA</div></div>
            <div class="brc specialist-col">
              <div class="spec-mini"><div><div class="spec-mini-name">Karim Al-Azzawi <span class="badge badge-verified badge-dot" style="font-size:.575rem;margin-left:4px;">Verified</span></div><div class="spec-mini-sub"><div class="stars" style="font-size:.7rem;display:inline;">★★★★★</div> 4.91 · 61 projects</div></div></div>
              <div style="margin-top:5px;font-size:.75rem;color:var(--ink-muted);font-style:italic;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">"I have built real-time anomaly detection at scale for two GCC banking clients using Kafka + Flink…"</div>
            </div>
            <div class="brc center"><span class="match-pill mp-high">91%</span></div>
            <div class="brc center"><div style="font-family:var(--font-mono);font-weight:700;">$14,200</div><div style="font-size:.7rem;color:var(--ink-muted);">within range</div></div>
            <div class="brc center"><div style="font-family:var(--font-mono);">55d</div><div style="font-size:.7rem;color:var(--ink-muted);">Milestones edited</div></div>
            <div class="brc center"><span class="bid-status-chip bsc-new">● New</span></div>
          </div>

          <!-- NEW BIDS SUMMARY (collapsed) -->
          <div style="padding:14px 20px;display:flex;align-items:center;gap:14px;font-size:.875rem;color:var(--ink-muted);">
            <div class="new-dot"></div>
            <span>7 more unreviewed proposals — including 3 with budgets within range, 1 with milestone changes.</span>
            <a href="/bid-review" class="btn btn-outline btn-sm" style="margin-left:auto;">Review All 10 →</a>
          </div>
        </div>
      </div>

    </div><!-- end view-grouped-content -->

    <!-- ══════════ ALL BIDS VIEW (flat table) ══════════ -->


  </div>
</div>

<!-- ══════════ MODALS ══════════ -->

<!-- ACCEPT & ISSUE CONTRACT -->
<div id="accept-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Accept Proposal &amp; Issue Contract</h3>
        <p class="text-sm text-muted mt-4">This generates a binding contract, sends the NDA, and locks the first escrow.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('accept-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-md);padding:16px 18px;margin-bottom:18px;font-size:.875rem;">
        <div class="flex justify-between mb-6"><span class="text-muted">Specialist</span><span class="font-bold" id="accept-name">—</span></div>
        <div class="flex justify-between mb-6"><span class="text-muted">Contract Value</span><span class="font-mono font-bold" id="accept-amount">—</span></div>
        <div class="flex justify-between mb-6"><span class="text-muted">First Escrow Locked Now</span><span class="font-mono font-bold">$3,000</span></div>
        <div class="flex justify-between"><span class="text-muted">NDA</span><span>Standard Nexus · Auto-generated</span></div>
      </div>
      <div class="form-group">
        <label class="form-label">Message to Specialist (optional)</label>
        <textarea class="form-control" rows="3" placeholder="Welcome them to the project…"></textarea>
      </div>
      <div class="verify-band">
        <span>💳</span>
        <div style="font-size:.8125rem;">$3,000 will be locked from your Mastercard ···· 4821 on confirm.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('accept-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('accept-modal').classList.add('hidden');showToast('Contract issued. NDA sent. Escrow locked.')">✦ Confirm &amp; Issue Contract</button>
    </div>
  </div>
</div>

<!-- SCHEDULE INTERVIEW -->
<div id="interview-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3>Schedule Technical Interview</h3>
      <button class="modal-close" onclick="document.getElementById('interview-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-row">
        <div class="form-group"><label class="form-label">Date</label><input type="date" class="form-control"></div>
        <div class="form-group"><label class="form-label">Time (GMT+2)</label><input type="time" class="form-control" value="10:00"></div>
      </div>
      <div class="form-group">
        <label class="form-label">Duration</label>
        <select class="form-control"><option>30 minutes</option><option selected>45 minutes</option><option>60 minutes</option></select>
      </div>
      <div class="form-group">
        <label class="form-label">Platform</label>
        <select class="form-control"><option>Google Meet (auto-generated)</option><option>Zoom</option><option>Microsoft Teams</option></select>
      </div>
      <div class="form-group">
        <label class="form-label">Interview Agenda</label>
        <textarea class="form-control" rows="3" placeholder="Topics to cover in the interview…"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('interview-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('interview-modal').classList.add('hidden');showToast('Interview invitation sent. Calendar invite generated.')">Send Invitation</button>
    </div>
  </div>
</div>

<!-- SEND MESSAGE -->
<div id="message-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3 id="msg-modal-title">Message Specialist</h3>
      <button class="modal-close" onclick="document.getElementById('message-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Quick Templates</label>
        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px;">
          <button type="button" class="sort-chip" style="padding:4px 10px;border:1px solid var(--border);border-radius:2px;font-size:.75rem;font-family:var(--font-mono);cursor:pointer;background:var(--ivory-card);" onclick="document.getElementById('msg-body').value='Thank you for your proposal. Before we proceed, could you clarify your approach to [specific area]?'">Request Info</button>
          <button type="button" class="sort-chip" style="padding:4px 10px;border:1px solid var(--border);border-radius:2px;font-size:.75rem;font-family:var(--font-mono);cursor:pointer;background:var(--ivory-card);" onclick="document.getElementById('msg-body').value='We are interested in your proposal but would like to discuss the budget before confirming. Are you open to a short call?'">Budget Discussion</button>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Message</label>
        <textarea class="form-control" rows="5" id="msg-body" placeholder="Write your message…"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('message-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('message-modal').classList.add('hidden');showToast('Message sent.')">Send Message</button>
    </div>
  </div>
</div>

<!-- DECLINE -->
<div id="decline-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3 id="decline-modal-title">Decline Proposal</h3>
      <button class="modal-close" onclick="document.getElementById('decline-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Reason</label>
        <select class="form-control"><option>— Select reason —</option><option>Selected another specialist</option><option>Budget too high</option><option>Credentials insufficient</option><option>Timeline too long</option><option>Project cancelled</option></select>
      </div>
      <div class="form-group">
        <label class="form-label">Optional feedback <span class="text-muted font-mono" style="font-size:.7rem;font-weight:400;text-transform:none;letter-spacing:0;">Sent to specialist</span></label>
        <textarea class="form-control" rows="2" placeholder="Brief, professional feedback…"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('decline-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-danger" onclick="document.getElementById('decline-modal').classList.add('hidden');showToast('Proposal declined. Specialist notified.','info')">Decline Proposal</button>
    </div>
  </div>
</div>

<!-- FILTER MODAL -->


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


/* ── SECTION COLLAPSE ── */
function toggleSection(header) {
  header.classList.toggle('collapsed');
}

/* ── EXPAND ROW ── */
function toggleExpand(rowId) {
  const expId = 'exp-' + rowId;
  const el = document.getElementById(expId);
  if(el) el.classList.toggle('open');
}

/* ── STAT STRIP FILTER ── */
function filterByStatus(status, el) {
  document.querySelectorAll('.strip-cell').forEach(c => c.classList.remove('af'));
  el.classList.add('af');
  document.querySelectorAll('.bid-row:not(.header-row)').forEach(row => {
    if(status === 'all') { row.style.display = ''; return; }
    row.style.display = (row.dataset.status === status) ? '' : 'none';
  });
}

/* ── PROJECT PILL FILTER ── */
function filterByProject(el, projId) {
  document.querySelectorAll('.proj-pill').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  if(projId === 'all') {
    document.querySelectorAll('.project-section').forEach(s => s.style.display = '');
  } else {
    document.querySelectorAll('.project-section').forEach(s => {
      s.style.display = s.dataset.project === projId ? '' : 'none';
    });
  }
}

/* ── STATUS CHIP FILTER ── */
function filterChip(el, status) {
  document.querySelectorAll('.fchip').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.bid-row:not(.header-row)').forEach(row => {
    if(status === 'all') { row.style.display = ''; return; }
    row.style.display = (row.dataset.status === status) ? '' : 'none';
  });
}

/* ── SHORTLIST ── */
function shortlistBid(rowId, name) {
  const row = document.getElementById(rowId);
  if(row) {
    row.classList.remove('is-new');
    row.classList.add('is-shortlisted');
    row.dataset.status = 'shortlisted';
    const chip = row.querySelector('.bid-status-chip');
    if(chip) { chip.className='bid-status-chip bsc-shortlist'; chip.textContent='✓ Shortlisted'; }
    const dot = row.querySelector('.new-dot');
    if(dot) dot.remove();
  }
  showToast(name + ' shortlisted. NDA will be sent for signature.');
}

/* ── ACCEPT MODAL ── */
function openAccept(name, amount) {
  document.getElementById('accept-name').textContent = name;
  document.getElementById('accept-amount').textContent = amount;
  document.getElementById('accept-modal').classList.remove('hidden');
}

/* ── INTERVIEW MODAL ── */
function openInterview() {
  document.getElementById('interview-modal').classList.remove('hidden');
}

/* ── MESSAGE MODAL ── */
function openMessage(name) {
  document.getElementById('msg-modal-title').textContent = 'Message ' + name;
  document.getElementById('message-modal').classList.remove('hidden');
}

/* ── DECLINE MODAL ── */
function openDecline(name) {
  document.getElementById('decline-modal-title').textContent = 'Decline — ' + name;
  document.getElementById('decline-modal').classList.remove('hidden');
}

/* ── TOAST ── */
function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  const icons = {success:'✓', warn:'⚠', info:'ℹ'};
  const cls   = {success:'success', warn:'warning', info:''};
  s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type==='warn'?'Notice':type==='info'?'Info':'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML = '', 4500);
}

/* Bid row click opens the MVC bid review route. */
document.querySelectorAll('.bid-row:not(.header-row)').forEach(row => {
  row.addEventListener('click', function(e) {
    if (e.target.closest('button') || e.target.closest('a')) return;
    window.location.href = '/bid-review';
  });
});
</script>
</body>
</html>
