<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/specialist/my-proposals.php
    Template: My Proposals — Specialist View
    Role:     specialist (authenticated)
    Route:    /my-proposals
              /my-proposals?status=active
              /my-proposals?status=withdrawn
    ============================================================
    PHP Data contract (from ProposalController::index()):
      $proposals      — paginated ProposalRecord[] for $specialist
      $stats          — [ total, active, accepted,
                          withdrawn, declined, acceptance_rate ]
      $specialist     — authenticated specialist
      $filters        — current active filter/sort state
    Each ProposalRecord includes:
      $p['id'], $p['job'], $p['client'], $p['bid_total'],
      $p['status'],       — draft|submitted|interview|accepted|declined|withdrawn
                             interview|accepted|declined|withdrawn
      $p['submitted_at'], $p['can_withdraw'],
      $p['withdraw_deadline'],   — submitted_at + 48h
      $p['hours_remaining'],     — int
      $p['milestones'],  $p['cover_letter'], $p['attachments'],
      $p['client_notes']         — feedback if declined

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
<title>My Proposals — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/my-bids.css">
</head>
<body>

<!-- ══════════ TOPNAV
     PHP: include 'partials/topnav.php'; ['role'=>'specialist','user'=>$specialist]
-->
<nav class="topnav">
  <?php require __DIR__ . '/../../partials/topnav.php'; ?>

<div class="proposals-shell">

  <!-- ── SIDEBAR ── -->
  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-label">Overview</div>
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
        Dashboard
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Work</div>
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4a1 1 0 0 1 1-1h3l1 1h6a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4z"/></svg>
        Active Projects
        <span class="notif-count" style="margin-left:auto;display:inline-flex;align-items:center;justify-content:center;background:transparent;color:var(--gold);border:2px solid var(--gold);font-size:0.75rem;font-weight:700;padding:4px 10px;border-radius:12px;min-width:24px;text-align:center;">2</span>
      </a>
      <a class="sidebar-link active" href="/dashboard/my-bids">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M3 2h10a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v1h8V4H4zm0 2v1h8V6H4z"/></svg>
        My Proposals
        <span class="notif-count" style="margin-left:auto;display:inline-flex;align-items:center;justify-content:center;background:transparent;color:var(--gold);border:2px solid var(--gold);font-size:0.75rem;font-weight:700;padding:4px 10px;border-radius:12px;min-width:24px;text-align:center;">5</span>
      </a>
      <a class="sidebar-link" href="/dashboard">
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
      <a class="sidebar-link" href="/profile">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M2 14s1-1.5 6-1.5S14 14 14 14v1H2v-1z"/></svg>
        My Profile
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Finance</div>
      <a class="sidebar-link" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12v8H2V4zm1 1v6h10V5H3zm2 2h2v2H5V7z"/></svg>
        Earnings &amp; Payouts
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Support</div>
      <a class="sidebar-link" href="/dispute">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1zm0 4a.75.75 0 0 0 0 1.5.75.75 0 0 0 0-1.5zm-.25 3v4.5h1.5V7h-1.5z"/></svg>
        Disputes
      </a>
      <a class="sidebar-link" href="/chat">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M2 1h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-3l-4 3v-3H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>
        Messages
      </a>
    </div>
  </aside>

  <!-- ── MAIN ── -->
  <main class="proposals-main">

    <!-- PAGE HEADER -->
    <div class="page-header flex justify-between items-center">
      <div>
        <div class="breadcrumb">Specialist Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> My Proposals</div>
        <h2>My Proposals</h2>
        <p class="mt-4">
          <!-- PHP: count($proposals).' proposals across '.count(unique niches).' niches' -->
          12 proposals across 1 niche. <strong style="color:var(--gold);">1 withdrawal window open</strong> — act within 48h.
        </p>
      </div>
    </div>

    <!-- STATS STRIP -->
    <div class="stat-strip">
      <div class="strip-stat static">
        <div class="strip-stat-val"><?= $stats['total'] ?></div>
        <div class="strip-stat-lbl">Submitted</div>
      </div>
      <div class="strip-stat static">
        <div class="strip-stat-val" style="color:var(--gold);"><?= $stats['active'] ?></div>
        <div class="strip-stat-lbl">Active</div>
      </div>
      <div class="strip-stat static">
        <div class="strip-stat-val" style="color:var(--ink);"><?= $stats['accepted'] ?></div>
        <div class="strip-stat-lbl">Accepted</div>
      </div>
      <div class="strip-stat static">
        <div class="strip-stat-val" style="color:var(--ink-muted);">0</div>
        <div class="strip-stat-lbl">Withdrawn</div>
      </div>
      <div class="strip-stat static">
        <div class="strip-stat-val" style="color:var(--rust);"><?= $stats['acceptance_rate'] ?>%</div>
        <div class="strip-stat-lbl">Rate</div>
      </div>
    </div>

    <!-- FILTER ROW -->
    <div class="filter-row">
      <span style="font-size:.8rem;color:var(--ink-muted);">Filter:</span>
      <span class="filter-chip active" onclick="setChip(this,'all')">All</span>
      <span class="filter-chip" onclick="setChip(this,'withdraw')">⏱ Withdrawal Open</span>
      <span class="filter-chip" onclick="setChip(this,'interview')">🎙 Interview</span>
      <span class="filter-chip" onclick="setChip(this,'draft')">Draft</span>
      <div style="margin-left:auto;display:flex;gap:8px;">
        <select class="form-control" style="width:160px;padding:6px 10px;font-size:.8125rem;" onchange="sortProposals(this.value)">
          <option value="newest">Newest First</option>
          <option value="deadline">Deadline First</option>
          <option value="amount-high">Amount ↑</option>
          <option value="amount-low">Amount ↓</option>
          <option value="match">Match Score</option>
        </select>
      </div>
    </div>

    <!-- ══════════ PROPOSAL CARDS ══════════ -->

    <!-- PHP: foreach($proposals as $p): -->

    <!-- ── CARD 1: ACTIVE · WITHDRAWAL OPEN ── -->
    <div class="proposal-card can-withdraw" id="card-0" data-status="submitted">
      <div class="pc-header" onclick="toggleCard(0)">
        <div class="pc-niche-icon">🧠</div>
        <div style="flex:1;min-width:0;">
          <div class="pc-title">MENA Expansion — Cross-Border Contract Review</div>
          <div class="pc-meta">
            <span>FinCorp Egypt</span>
            <span class="font-mono">Data Science</span>
            <!-- PHP: date('M j, Y', $p['submitted_at']) -->
            <span>Submitted Apr 12, 2025 · 14:22 GMT+2</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap;">
            <span class="badge badge-pending badge-dot" style="font-size:.65rem;">Under Review</span>
            <span class="badge badge-gold" style="font-size:.65rem;">95% match</span>
            <span class="badge badge-default" style="font-size:.65rem;">Ref: BID-NX-4821-DR</span>
          </div>
        </div>
        <div class="pc-right">
          <div class="pc-amount">$12,000</div>
          <div class="pc-amount-lbl">3 milestones · 49 days</div>
          <div class="text-xs text-muted font-mono mt-6" style="color:var(--gold);">↩ Window open</div>
        </div>
      </div>

      <!-- WITHDRAWAL COUNTDOWN BAR -->
      <!-- PHP: if($p['can_withdraw']): -->
      <div class="withdraw-countdown" id="countdown-0">
        <span>↩</span>
        <div>
          <div style="font-weight:700;font-size:.8125rem;margin-bottom:1px;">Withdrawal window open</div>
          <div class="text-xs text-muted">Closes Apr 14, 2025 · 14:22 GMT+2</div>
        </div>
        <div class="countdown-bar-wrap">
          <div class="countdown-bar"><div class="countdown-fill" id="fill-0" style="width:99%;"></div></div>
          <div style="font-size:.7rem;font-family:var(--font-mono);color:var(--ink-muted);margin-top:3px;text-align:right;" id="pct-label-0">99% remaining</div>
        </div>
        <div class="countdown-timer" id="timer-0">47:58:12</div>
        <button class="btn btn-danger btn-sm" onclick="event.stopPropagation();showWithdrawConfirm(0)">
          Withdraw
        </button>
      </div>

      <!-- WITHDRAW CONFIRM BAR (inline, replaces button click) -->
      <div class="withdraw-confirm-bar" id="wconfirm-0">
        <span style="font-size:1rem;">⚠️</span>
        <div style="flex:1;">
          <strong>Are you sure?</strong> Withdrawing removes your proposal permanently. Your response-rate metric is unaffected within the 48h window.
        </div>
        <div style="display:flex;gap:8px;">
          <label class="form-label" style="margin:0;white-space:nowrap;font-size:.8rem;">Reason:</label>
          <select class="form-control" id="wr-reason-0" style="width:200px;padding:5px 8px;font-size:.8125rem;">
            <option value="">Optional reason…</option>
            <option>Accepted another project</option>
            <option>Availability changed</option>
            <option>Reconsidered my pricing</option>
            <option>Scope doesn't match expertise</option>
          </select>
        </div>
        <button class="btn btn-danger btn-sm" onclick="confirmWithdraw(0)">Confirm Withdrawal</button>
        <button class="btn btn-outline btn-sm" onclick="cancelWithdraw(0)">Cancel</button>
      </div>

      <!-- DRAWER -->
      <div class="pc-body" id="body-0">
        <div class="drawer-tabs" id="dtabs-0">
          <button class="drawer-tab active" onclick="switchDrawerTab(0,0,this)">Proposal</button>
          <button class="drawer-tab" onclick="switchDrawerTab(0,1,this)">Milestones</button>
          <button class="drawer-tab" onclick="switchDrawerTab(0,2,this)">Attachments</button>
        </div>
        <div class="drawer-content">

          <!-- DRAWER PANEL 0: PROPOSAL -->
          <div class="drawer-panel active" id="dpanel-0-0">
            <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Cover Letter Sent</div>
            <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px 18px;font-size:.9rem;color:var(--ink-mid);line-height:1.75;margin-bottom:14px;">
              Dear Amira, I have reviewed your project brief carefully and note your specific need for GDPR cross-border transfer analysis alongside Egyptian, UAE, and KSA compliance — this intersection is precisely the area I specialise in. Over the past six years I have advised four SaaS companies on MENA market entry…
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
              <div style="padding:12px 14px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
                <div class="text-xs text-muted mb-4">Your Bid</div>
                <div style="font-family:var(--font-mono);font-weight:700;font-size:1.1rem;">$12,000</div>
              </div>
              <div style="padding:12px 14px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
                <div class="text-xs text-muted mb-4">vs Client Budget</div>
                <div style="font-family:var(--font-mono);font-weight:700;font-size:1.1rem;color:var(--sage);">= No change</div>
              </div>
            </div>
          </div>

          <!-- DRAWER PANEL 1: MILESTONES -->
          <div class="drawer-panel" id="dpanel-0-1">
            <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:12px;font-family:var(--font-body);">Your Proposed Milestones</div>
            <div class="ms-drawer-row" style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-muted);padding:6px 0;border-bottom:1.5px solid var(--border);">
              <div></div><div>Phase</div><div style="text-align:right;">Duration</div><div style="text-align:right;">Amount</div>
            </div>
            <!-- PHP: foreach($p['milestones'] as $i=>$ms): -->
            <div class="ms-drawer-row">
              <div class="ms-drawer-num">1</div>
              <div style="color:var(--ink-mid);">Initial Document Review &amp; Gap Analysis</div>
              <div style="text-align:right;font-family:var(--font-mono);font-size:.875rem;">14d</div>
              <div style="text-align:right;font-family:var(--font-mono);font-weight:600;">$3,000</div>
            </div>
            <div class="ms-drawer-row">
              <div class="ms-drawer-num">2</div>
              <div style="color:var(--ink-mid);">Jurisdiction-Specific Legal Analysis</div>
              <div style="text-align:right;font-family:var(--font-mono);font-size:.875rem;">21d</div>
              <div style="text-align:right;font-family:var(--font-mono);font-weight:600;">$4,500</div>
            </div>
            <div class="ms-drawer-row">
              <div class="ms-drawer-num">3</div>
              <div style="color:var(--ink-mid);">Revised Contracts &amp; Final Advisory Report</div>
              <div style="text-align:right;font-family:var(--font-mono);font-size:.875rem;">14d</div>
              <div style="text-align:right;font-family:var(--font-mono);font-weight:600;">$4,500</div>
            </div>
            <div style="display:flex;justify-content:space-between;padding:12px 0;border-top:1.5px solid var(--border);margin-top:4px;font-weight:700;font-size:.9rem;">
              <span>Total</span>
              <span class="font-mono">$12,000 · 49 days</span>
            </div>
            <div style="background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);padding:10px 14px;font-size:.8125rem;color:var(--sage);">
              ✓ Milestones accepted as proposed by client — no changes made.
            </div>
          </div>

          <!-- DRAWER PANEL 2: ATTACHMENTS -->
          <div class="drawer-panel" id="dpanel-0-2">
            <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:12px;font-family:var(--font-body);">Files Sent with Proposal</div>
            <!-- PHP: foreach($p['attachments'] as $a): -->
            <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-bottom:8px;font-size:.875rem;">
              <span>📄</span>
              <span style="flex:1;font-weight:600;">MENA_Legal_Portfolio_Sample.pdf</span>
              <span class="font-mono text-xs text-muted">2.4 MB</span>
              <button class="btn btn-ghost btn-sm">Download</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-bottom:8px;font-size:.875rem;">
              <span>📝</span>
              <span style="flex:1;font-weight:600;">GDPR_SCC_Approach_Note.pdf</span>
              <span class="font-mono text-xs text-muted">840 KB</span>
              <button class="btn btn-ghost btn-sm">Download</button>
            </div>
          </div>

        </div>
        <!-- CARD ACTIONS -->
        <div class="pc-actions">
          <a href="/chat" class="btn btn-outline btn-sm">💬 Message Client</a>
          <a href="/job-view" class="btn btn-ghost btn-sm">View Job Posting</a>
          <a href="/profile" class="btn btn-ghost btn-sm">View Client Profile</a>
        </div>
      </div>
    </div>

    <!-- ── CARD 3: INTERVIEW SCHEDULED ── -->
    <div class="proposal-card interview" id="card-2" data-status="interview">
      <div class="pc-header" onclick="toggleCard(2)">
        <div class="pc-niche-icon">🧠</div>
        <div style="flex:1;min-width:0;">
          <div class="pc-title">Real-Time Anomaly Detection Pipeline</div>
          <div class="pc-meta">
            <span>Gulf Digital</span>
            <span class="font-mono">Data Science</span>
            <span>Submitted Apr 8, 2025</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap;">
            <span class="bid-status-pill interview" style="font-size:.65rem;padding:3px 8px;">🎙 Interview Scheduled</span>
            <span class="badge badge-default font-mono" style="font-size:.65rem;">Apr 16 · 10:00 GMT+2</span>
          </div>
        </div>
        <div class="pc-right">
          <div class="pc-amount">$14,200</div>
          <div class="pc-amount-lbl">6 milestones · 55 days</div>
        </div>
      </div>

      <div style="padding:12px 22px;background:#EBF0F8;border-top:1px solid #B8D0F0;font-size:.8125rem;color:#1A4A8A;display:flex;align-items:center;gap:10px;">
        <span>📅</span>
        <div style="flex:1;"><strong>Interview confirmed</strong> — Apr 16, 2025 · 10:00–10:45 AM GMT+2 · Google Meet. Agenda sent. Review it before the call.</div>
        <button class="btn btn-sm" style="background:#1A4A8A;color:#fff;border:none;" onclick="event.stopPropagation();openAgendaModal()">View Agenda</button>
      </div>

      <div class="pc-body" id="body-2">
        <div class="drawer-tabs">
          <button class="drawer-tab active" onclick="switchDrawerTab(2,0,this)">Proposal</button>
          <button class="drawer-tab" onclick="switchDrawerTab(2,1,this)">Milestones</button>
          <button class="drawer-tab" onclick="switchDrawerTab(2,2,this)">Attachments</button>
          <button class="drawer-tab" onclick="switchDrawerTab(2,3,this)">Interview</button>
        </div>
        <div class="drawer-content">
          <div class="drawer-panel active" id="dpanel-2-0">
            <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Cover Letter Sent</div>
            <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px 18px;font-size:.9rem;color:var(--ink-mid);line-height:1.75;margin-bottom:14px;">
              Dear Amira, I have reviewed your project brief carefully and note your specific need for GDPR cross-border transfer analysis alongside Egyptian, UAE, and KSA compliance — this intersection is precisely the area I specialise in. Over the past six years I have advised four SaaS companies on MENA market entry…
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div style="padding:12px 14px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);"><div class="text-xs text-muted mb-4">Your Bid</div><div class="font-mono font-bold">$14,200</div></div>
              <div style="padding:12px 14px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);"><div class="text-xs text-muted mb-4">Client Budget</div><div class="font-mono" style="color:var(--ink-muted);">$12,000</div></div>
            </div>
          </div>
          <div class="drawer-panel" id="dpanel-2-1">
            <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:12px;font-family:var(--font-body);">Your Proposed Milestones</div>
            <div class="ms-drawer-row" style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-muted);padding:6px 0;border-bottom:1.5px solid var(--border);">
              <div></div><div>Phase</div><div style="text-align:right;">Duration</div><div style="text-align:right;">Amount</div>
            </div>
            <div class="ms-drawer-row">
              <div class="ms-drawer-num">1</div>
              <div style="color:var(--ink-mid);">Initial Document Review &amp; Gap Analysis</div>
              <div style="text-align:right;font-family:var(--font-mono);font-size:.875rem;">14d</div>
              <div style="text-align:right;font-family:var(--font-mono);font-weight:600;">$3,000</div>
            </div>
            <div class="ms-drawer-row">
              <div class="ms-drawer-num">2</div>
              <div style="color:var(--ink-mid);">Jurisdiction-Specific Legal Analysis</div>
              <div style="text-align:right;font-family:var(--font-mono);font-size:.875rem;">21d</div>
              <div style="text-align:right;font-family:var(--font-mono);font-weight:600;">$4,500</div>
            </div>
            <div class="ms-drawer-row">
              <div class="ms-drawer-num">3</div>
              <div style="color:var(--ink-mid);">Revised Contracts &amp; Final Advisory Report</div>
              <div style="text-align:right;font-family:var(--font-mono);font-size:.875rem;">14d</div>
              <div style="text-align:right;font-family:var(--font-mono);font-weight:600;">$4,500</div>
            </div>
            <div style="display:flex;justify-content:space-between;padding:12px 0;border-top:1.5px solid var(--border);margin-top:4px;font-weight:700;font-size:.9rem;">
              <span>Total</span>
              <span class="font-mono">$12,000 · 55 days</span>
            </div>
            <div style="background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);padding:10px 14px;font-size:.8125rem;color:var(--sage);">
              ✓ Milestones edited by +6 days compared to the original timeline.
            </div>
          </div>
          <div class="drawer-panel" id="dpanel-2-2">
            <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:12px;font-family:var(--font-body);">Files Sent with Proposal</div>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-bottom:8px;font-size:.875rem;">
              <span>📄</span>
              <span style="flex:1;font-weight:600;">MENA_Legal_Portfolio_Sample.pdf</span>
              <span class="font-mono text-xs text-muted">2.4 MB</span>
              <button class="btn btn-ghost btn-sm">Download</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-bottom:8px;font-size:.875rem;">
              <span>📝</span>
              <span style="flex:1;font-weight:600;">GDPR_SCC_Approach_Note.pdf</span>
              <span class="font-mono text-xs text-muted">840 KB</span>
              <button class="btn btn-ghost btn-sm">Download</button>
            </div>
          </div>
          <div class="drawer-panel" id="dpanel-2-3">
            <div style="display:grid;gap:14px;">
              <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;">
                <div style="font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:8px;">Interview Details</div>
                <div style="font-size:.9rem;color:var(--ink);font-weight:700;margin-bottom:6px;">Apr 16, 2025 · 10:00–10:45 AM GMT+2</div>
                <div style="font-size:.85rem;color:var(--ink-muted);margin-bottom:10px;">Google Meet • 45 minutes</div>
                <div style="font-size:.85rem;color:var(--ink);font-weight:700;margin-bottom:6px;">Agenda</div>
                <ol style="padding-left:18px;margin:0;font-size:.85rem;line-height:1.7;color:var(--ink-mid);">
                  <li>Methodology review for Phase 1 gap analysis.</li>
                  <li>KSA commercial law depth and compliance scope.</li>
                  <li>GDPR SCC approach for Egypt to EU transfer.</li>
                  <li>Arabic drafting quality and review cadence.</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="pc-actions">
          <a href="/chat" class="btn btn-outline btn-sm">💬 Message Client</a>
          <a href="/job-view" class="btn btn-ghost btn-sm">View Job Posting</a>
          <a href="/profile" class="btn btn-ghost btn-sm">View Client Profile</a>
        </div>
      </div>
    </div>

    <!-- ── CARD 4: DECLINED WITH FEEDBACK ── -->
    <div class="proposal-card declined" id="card-3" data-status="declined">
      <div class="pc-header" onclick="toggleCard(3)">
        <div class="pc-niche-icon" style="background:#FBEAE7;border-color:#F0C4BC;">🧠</div>
        <div style="flex:1;min-width:0;">
          <div class="pc-title" style="color:var(--ink-mid);">Supply Chain Optimization Model</div>
          <div class="pc-meta">
            <span>LogiX Corp</span>
            <span class="font-mono">Data Science</span>
            <span>Submitted Apr 5, 2025</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap;">
            <span class="bid-status-pill declined" style="font-size:.65rem;padding:3px 8px;">Declined</span>
            <span class="text-xs text-muted font-mono">Feedback available</span>
          </div>
        </div>
        <div class="pc-right">
          <div class="pc-amount" style="color:var(--ink-muted);">$14,000</div>
          <div class="pc-amount-lbl">6 milestones · 60 days</div>
        </div>
      </div>

      <div class="pc-body" id="body-3">
        <div class="drawer-content">
          <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--rust);margin-bottom:10px;font-family:var(--font-body);">Client Feedback</div>
          <div class="feedback-card negative" style="margin-bottom:14px;">
            <strong>Reason:</strong> Budget too high<br>
            <strong>Client note:</strong> "We valued the proposal's methodology but the budget was 17% above our range and the justification didn't fully address the scope difference. We would consider Dr. Khalil for future engagements."
          </div>
        </div>
        <div class="pc-actions">
        </div>
      </div>
    </div>

    <!-- ── CARD 5: WITHDRAWN ── -->
    <div class="proposal-card withdrawn" id="card-4" data-status="withdrawn">
      <div class="pc-header" onclick="toggleCard(4)">
        <div class="pc-niche-icon" style="opacity:.5;">🧠</div>
        <div style="flex:1;min-width:0;">
          <div class="pc-title" style="color:var(--ink-faint);">Energy Demand Forecasting</div>
          <div class="pc-meta">
            <span>PowerGrid SA</span>
            <span class="font-mono">Data Science</span>
            <span>Submitted Apr 1, 2025 · Withdrawn Apr 2, 2025</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;">
            <span class="bid-status-pill declined" style="font-size:.65rem;padding:3px 8px;color:var(--ink-faint);background:var(--ivory-deep);">Withdrawn</span>
          </div>
        </div>
        <div class="pc-right">
          <div class="pc-amount" style="color:var(--ink-faint);">$4,400</div>
          <div class="pc-amount-lbl">3 milestones · 28 days</div>
        </div>
      </div>

      <div class="pc-body" id="body-4">
        <div class="drawer-content">
          <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:10px;font-family:var(--font-body);">Withdrawal Record</div>
          <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;font-size:.875rem;color:var(--ink-mid);">
            <div class="flex justify-between mb-6"><span class="text-muted">Withdrawn</span><span class="font-mono">Apr 2, 2025 · 08:14 GMT+2</span></div>
            <div class="flex justify-between mb-6"><span class="text-muted">Within Window</span><span style="color:var(--sage);font-weight:700;">Yes — 6h 08m after submission</span></div>
            <div class="flex justify-between mb-6"><span class="text-muted">Reason</span><span>Accepted another project</span></div>
            <div class="flex justify-between"><span class="text-muted">Response Rate Impact</span><span style="color:var(--sage);font-weight:700;">None — within window</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── CARD 6: DRAFT ── -->
    <div class="proposal-card draft" id="card-5" data-status="draft">
      <div class="pc-header" onclick="toggleCard(5)">
        <div class="pc-niche-icon">🧠</div>
        <div style="flex:1;min-width:0;">
          <div class="pc-title">NLP Sentiment Analysis — Arabic/English</div>
          <div class="pc-meta">
            <span>Digital Hub KSA</span>
            <span class="font-mono">Data Science</span>
            <span>Draft started Apr 13, 2025</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;">
            <span class="bid-status-pill new" style="font-size:.65rem;padding:3px 8px;background:var(--ivory-deep);color:var(--ink-muted);border-color:var(--border);">Draft</span>
            <span class="text-xs text-muted">Not submitted — posting closes in 18 days</span>
          </div>
        </div>
        <div class="pc-right">
          <div class="pc-amount" style="color:var(--ink-muted);">$7,500</div>
          <div class="pc-amount-lbl">Draft — not submitted</div>
        </div>
      </div>

      <div class="draft-banner">
        <span>💾</span>
        <span style="flex:1;">This proposal is saved as a draft. Complete and submit before the job closes.</span>
        <a href="/bid" class="btn btn-primary btn-sm">Continue Editing →</a>
        <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="event.stopPropagation();showToast('Draft deleted.','warn')">Delete Draft</button>
      </div>
    </div>

    <!-- LOAD MORE -->
    <div style="text-align:center;padding:24px 0;border-top:1px solid var(--border);margin-top:4px;">
      <!-- PHP: if($proposals->hasMorePages()): -->
      <button class="btn btn-outline" onclick="showToast('Loading older proposals…','info')">
        Load Older Proposals
      </button>
    </div>

  </main>
</div>

<!-- ══════════ TOAST ══════════ -->
<div class="toast-stack" id="toast-stack"></div>

<!-- ══════════ LATE WITHDRAWAL MODAL (after window closes) ══════════ -->
<div id="late-withdraw-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Request Late Withdrawal</h3>
        <p class="text-sm text-muted mt-4">Your 48-hour no-penalty window has closed. A formal withdrawal request will be reviewed by Nexus support and may affect your response-rate score.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('late-withdraw-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div style="background:#FBE9E7;border:1px solid #F0C4BC;border-radius:var(--radius-sm);padding:12px 14px;margin-bottom:16px;font-size:.875rem;color:var(--rust);">
        ⚠ Late withdrawals impact your response-rate score and are visible to clients reviewing your profile.
      </div>
      <div class="form-group">
        <label class="form-label">Reason for Late Withdrawal <span style="color:var(--rust);">Required</span></label>
        <select class="form-control">
          <option value="">— Select a reason —</option>
          <option>Emergency personal circumstances</option>
          <option>Conflict of interest discovered</option>
          <option>Medical or health-related reason</option>
          <option>Accepted a full-time role</option>
          <option>Other (explain below)</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Explanation</label>
        <textarea class="form-control" rows="3" placeholder="Provide context for your request. Nexus support reviews all late withdrawal requests within 24h."></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('late-withdraw-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-danger" onclick="document.getElementById('late-withdraw-modal').classList.add('hidden');showToast('Late withdrawal request submitted. Nexus support will respond within 24h.','warn')">Submit Request</button>
    </div>
  </div>
</div>

<div id="agenda-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Interview Agenda</h3>
        <p class="text-sm text-muted mt-4">Review the interview details and accept or decline the invitation.</p>
      </div>
      <button class="modal-close" onclick="closeAgendaModal()">✕</button>
    </div>
    <div class="modal-body">
      <div style="margin-bottom:20px;">
        <div style="font-size:.75rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:10px;font-family:var(--font-body);">Interview Time (GMT+2)</div>
        <div style="padding:18px 20px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;font-weight:700;">
          Tue 09:00–09:45
        </div>
      </div>
      <div style="margin-bottom:20px;">
        <div style="font-size:.75rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:10px;font-family:var(--font-body);">Interview Duration</div>
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">45 minutes</div>
      </div>
      <div style="margin-bottom:20px;">
        <div style="font-size:.75rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:10px;font-family:var(--font-body);">Meeting Platform</div>
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">Google Meet (link auto-generated)</div>
      </div>
      <div style="margin-bottom:20px;">
        <div style="font-size:.75rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:10px;font-family:var(--font-body);">Interview Agenda / Topics</div>
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);line-height:1.7;font-size:.9rem;color:var(--ink-mid);">
          <ol style="padding-left:18px;margin:0;">
            <li>Verify depth of KSA commercial law experience.</li>
            <li>Walk through Phase 1 methodology for gap analysis.</li>
            <li>Discuss GDPR SCC approach for Egypt → EU data transfers.</li>
            <li>Confirm Arabic drafting quality and review cadence.</li>
          </ol>
        </div>
      </div>
      <div style="padding:12px 14px;background:#FBF6E2;border:1px solid #E8D88C;border-radius:var(--radius-sm);font-size:.875rem;color:#6B4800;display:flex;gap:10px;align-items:flex-start;">
        <span>📄</span>
        <div>An NDA is ready for Dr. Khalil. The pre-interview brief will be shared after NDA signature. Interview confirmation includes a reminder of NDA terms.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="declineInterview()">Decline</button>
      <button class="btn btn-primary" onclick="acceptInterview()">Accept Interview</button>
    </div>
  </div>
</div>

<script>

/* ══ CARD TOGGLE ══ */
function toggleCard(i) {
  const body = document.getElementById('body-' + i);
  if (!body) return;
  body.classList.toggle('open');
}

function toggleDD() {
  const menu = document.getElementById('user-dd');
  if (menu) menu.classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) {
    document.getElementById('user-dd')?.classList.add('hidden');
  }
});

/* ══ DRAWER TABS ══ */
function switchDrawerTab(cardIdx, tabIdx, btn) {
  // Deactivate all tabs in this card
  const allTabs = btn.closest('.drawer-tabs').querySelectorAll('.drawer-tab');
  allTabs.forEach(t => t.classList.remove('active'));
  btn.classList.add('active');

  // Find all panels for this card
  let panelIdx = 0;
  let panel;
  while ((panel = document.getElementById(`dpanel-${cardIdx}-${panelIdx}`)) !== null) {
    panel.classList.toggle('active', panelIdx === tabIdx);
    panelIdx++;
  }
}

/* ══ FILTER CHIPS ══ */
function setChip(el, key) {
  document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
  el.classList.add('active');

  // PHP: reload with ?filter={key}
  document.querySelectorAll('.proposal-card').forEach(card => {
    if (key === 'all') {
      card.style.display = '';
    } else if (key === 'withdraw') {
      card.style.display = card.classList.contains('can-withdraw') ? '' : 'none';
    } else if (key === 'interview') {
      card.style.display = card.classList.contains('interview') ? '' : 'none';
    } else if (key === 'draft') {
      card.style.display = card.classList.contains('draft') ? '' : 'none';
    }
  });
}

/* ══ STAT STRIP FILTER ══ */
function filterByStatus(status, el) {
  document.querySelectorAll('.strip-stat').forEach(s => s.classList.remove('active'));
  el.classList.add('active');

  document.querySelectorAll('.proposal-card').forEach(card => {
    const statusVal = card.dataset.status || '';
    if (status === 'all') {
      card.style.display = '';
    } else {
      card.style.display = statusVal === status ? '' : 'none';
    }
  });
}

/* ══ SORT ══ */
function sortProposals(key) {
  // PHP: reload with ?sort={key}
  showToast('Proposals sorted by ' + key.replace('-', ' ') + '.', 'info');
}

/* ══ WITHDRAWAL ══ */
function showWithdrawConfirm(i) {
  document.getElementById('wconfirm-' + i)?.classList.add('show');
  document.getElementById('countdown-' + i)?.style && (document.getElementById('countdown-' + i).style.display = 'none');
}

function cancelWithdraw(i) {
  document.getElementById('wconfirm-' + i)?.classList.remove('show');
  document.getElementById('countdown-' + i) && (document.getElementById('countdown-' + i).style.display = '');
}

function confirmWithdraw(i) {
  const reason = document.getElementById('wr-reason-' + i)?.value || '';
  // PHP: AJAX DELETE /proposals/{id}/withdraw with reason
  document.getElementById('wconfirm-' + i)?.classList.remove('show');
  const card = document.getElementById('card-' + i);
  if (card) {
    card.classList.remove('can-withdraw');
    card.classList.add('withdrawn');
    card.style.opacity = '.6';
    const countdown = document.getElementById('countdown-' + i);
    if (countdown) countdown.remove();
    // Update status badge
    const statusBadge = card.querySelector('.badge-pending');
    if (statusBadge) {
      statusBadge.className = 'bid-status-pill';
      statusBadge.style.cssText = 'font-size:.65rem;padding:3px 8px;background:var(--ivory-deep);color:var(--ink-faint);border:1px solid var(--border);';
      statusBadge.textContent = 'Withdrawn';
    }
  }
  showToast('Proposal withdrawn successfully. Your response-rate score is unaffected.', 'info');
}

/* ══ COVER LETTER EXPAND ══ */
function toggleFullCover(i) {
  showToast('Full cover letter view — in production this expands the text.', 'info');
}

/* ══ COUNTDOWN TIMER ══ */
function startCountdown(elementId, fillId, pctLabelId, totalSeconds) {
  let remaining = totalSeconds;
  const total = 48 * 3600;

  const tick = () => {
    if (remaining <= 0) {
      const el = document.getElementById(elementId);
      if (el) el.textContent = 'Window closed';
      return;
    }

    const h = Math.floor(remaining / 3600);
    const m = Math.floor((remaining % 3600) / 60);
    const s = remaining % 60;
    const timeStr = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;

    const timerEl = document.getElementById(elementId);
    const fillEl  = document.getElementById(fillId);
    const pctEl   = document.getElementById(pctLabelId);
    const pct = (remaining / total) * 100;

    if (timerEl) {
      timerEl.textContent = timeStr;
      timerEl.classList.toggle('urgent', pct < 25);
    }
    if (fillEl) {
      fillEl.style.width = pct + '%';
      fillEl.classList.toggle('urgent', pct < 25);
    }
    if (pctEl) {
      pctEl.textContent = Math.round(pct) + '% remaining';
    }

    // Escalate container to urgent styling
    const container = document.getElementById('countdown-0');
    if (container && pct < 25) container.classList.add('urgent');

    remaining--;
    setTimeout(tick, 1000);
  };

  tick();
}

/* ══ TOAST ══ */
function showToast(msg, type = 'success') {
  const s = document.getElementById('toast-stack');
  const icons = { success: '✓', warn: '⚠', info: 'ℹ' };
  const cls   = { success: 'success', warn: 'warning', info: '' };
  s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type === 'warn' ? 'Notice' : type === 'info' ? 'Info' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML = '', 4500);
}

function openAgendaModal() {
  document.getElementById('agenda-modal').classList.remove('hidden');
}

function closeAgendaModal() {
  document.getElementById('agenda-modal').classList.add('hidden');
}

function acceptInterview() {
  closeAgendaModal();
  showToast('Interview accepted. The client will be notified.', 'success');
}

function declineInterview() {
  closeAgendaModal();
  showToast('Interview declined. The client has been notified.', 'warn');
}

/* ══ INIT ══ */
// PHP: $p['hours_remaining'] * 3600 + $p['minutes_remaining'] * 60
startCountdown('timer-0', 'fill-0', 'pct-label-0', 47 * 3600 + 58 * 60 + 12);

// Add hover styles to add-row-btn
document.querySelectorAll('.add-row-btn').forEach(btn => {
  btn.addEventListener('mouseenter', () => {
    btn.style.borderColor = 'var(--gold)';
    btn.style.color = 'var(--ink)';
    btn.style.background = 'var(--gold-pale)';
  });
  btn.addEventListener('mouseleave', () => {
    btn.style.borderColor = '';
    btn.style.color = '';
    btn.style.background = '';
  });
});

</script>
</body>
</html>
