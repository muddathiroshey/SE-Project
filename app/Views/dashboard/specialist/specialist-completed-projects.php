<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/specialist/completed-projects.php
    Template: Completed Projects — Specialist View
    Role:     specialist (authenticated)
    Route:    /specialist/projects/completed
    ============================================================
    PHP Data contract (from SpecialistProjectController::completed()):
      $projects     — CompletedProject[] for $specialist
      $stats        — [ total, total_earned, avg_rating_received,
                        on_time_rate, dispute_rate, repeat_hired ]
      $earnings_chart  — monthly earnings breakdown array
      $specialist   — authenticated specialist record
    Each CompletedProject:
      $p['id'], $p['title'], $p['niche'], $p['contract_ref'],
      $p['client'],         — { name, initials, rating, verified }
      $p['total_value'],
      $p['completed_at'],   $p['duration_actual'],
      $p['milestones_count'],
      $p['on_time'],        — bool
      $p['rating_received'],— int 1–5 (null = not yet rated)
      $p['review_received'],— string|null
      $p['dispute'],        — null | { outcome, amount_recovered }
      $p['client_saved'],   — bool: in trusted list
      $p['certificate_url'],
      $p['nda_ref']

      Logos for different niches:
        Legal Consulting -> ⚖️
        Data Science and Machine Learning -> 🧠
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
<title>Completed Projects — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/specialist-completed-projects.css">
</head>
<body>

<!-- TOPNAV -->
<nav class="topnav">
  <?php require __DIR__ . '/../../partials/topnav.php'; ?>

<div class="main-layout">

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
        <span class="notif-count" style="margin-left:auto;">2</span>
      </a>
      <a class="sidebar-link" href="/dashboard/my-bids">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M3 2h10a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 2v1h8V4H4zm0 2v1h8V6H4z"/></svg>
        My Proposals
        <span class="notif-count" style="margin-left:auto;">5</span>
      </a>
      <a class="sidebar-link active" href="/dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M6 1h4a1 1 0 0 1 1 1v2H5V2a1 1 0 0 1 1-1z"/><path d="M3 4h10v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z"/></svg>
        Completed Work
      </a>
    </div>
    <div class="sidebar-section">
      <div class="sidebar-label">Marketplace</div>
      <a class="sidebar-link" href="/browse-jobs">
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
        <svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1zm0 3a.75.75 0 0 0 0 1.5.75.75 0 0 0 0-1.5zm-.25 3v4.5h1.5V7h-1.5z"/></svg>
        Disputes
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
        <div class="breadcrumb">Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Projects</div>
        <h2>Completed Projects</h2>
        <p class="mt-4">
          <!-- PHP: count($projects).' completed · $'.number_format($stats['total_earned']).' total earned' -->
          12 completed projects · <strong>$36,700 total earned</strong> · 4.8 average rating received.
        </p>
      </div>
      <div class="flex gap-10">
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
        <div class="strip-lbl">Total Earned</div>
      </div>
      <div class="strip-cell">
        <div class="strip-val">4.8</div>
        <div class="strip-lbl">Avg. Rating Received</div>
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
      <span class="fchip" onclick="filterCards(this,'legal')">⚖️ Legal Consulting</span>
      <span class="fchip" onclick="filterCards(this,'repeat')">🔄 Repeat Client</span>
      <span class="fchip" onclick="filterCards(this,'unrated')">⭐ Awaiting Rating</span>
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
    <div class="comp-card top-rated" id="cc-0" data-niche="legal" data-unrated="true">

      <!-- UNRATED BANNER -->
      <div class="unrated-banner">
        <span>⭐</span>
        <div style="flex:1;"><strong>You haven't rated Amira Tawfik yet.</strong> Leaving a review helps other specialists and improves the platform.</div>
        <button class="btn btn-ghost btn-sm" onclick="event.stopPropagation();openRatingModal(0)">Leave Review</button>
      </div>

      <div class="cc-header" onclick="toggleCard(0)">
        <div class="cc-niche-icon ni-legal">⚖️</div>
        <div style="flex:1;min-width:0;">
          <!-- PHP: htmlspecialchars($p['title']) -->
          <div class="cc-title">Predictive Churn Model — FinCorp Q1 2025</div>
          <div class="cc-meta">
            <!-- PHP: date('M j, Y', $p['completed_at']) -->
            <span>Completed Apr 2, 2025</span>
            <span>·</span>
            <span class="font-mono">CON-NX-3812 · Legal Consulting</span>
            <span>·</span>
            <span>5 milestones · 49 days</span>
          </div>
          <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap;">
            <!-- PHP: $p['rating_received'] ? stars : 'Not yet rated' -->
            <div style="color:var(--ink-faint);font-size:.8125rem;font-style:italic;">Awaiting rating</div>
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
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Client's Rating</div>
              <div class="review-display">
                "Exceptional work on the churn prediction model. The methodology was sound and the deliverables were production-ready. Outstanding communication and professionalism throughout the engagement."
              </div>
              <div class="flex items-center gap-10">
                <div class="avatar avatar-sm">AT</div>
                <div>
                  <div style="font-weight:700;font-size:.875rem;">Amira Tawfik</div>
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
          <a href="/profile" class="btn btn-ghost btn-sm">View Client Profile</a>
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
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Client's Rating</div>
              <div class="review-display">
                "James delivered a comprehensive cross-border legal framework that addressed every jurisdiction we operate in. His structured approach — submitting a gap analysis before drafting — saved us significant revision time."
              </div>
              <div class="flex items-center gap-10">
                <div class="avatar avatar-sm">AT</div>
                <div>
                  <div style="font-weight:700;font-size:.875rem;">Amira Tawfik</div>
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
          <a href="/profile" class="btn btn-ghost btn-sm">View Client Profile</a>
        </div>
      </div>
    </div>

    <!-- CARD 3: DISPUTE RESOLVED -->
    <div class="comp-card had-dispute" id="cc-2" data-niche="legal">
      <div class="cc-header" onclick="toggleCard(2)">
        <div class="cc-niche-icon ni-legal" style="background:#FBEAE7;border-color:#F0C4BC;">⚖️</div>
        <div style="flex:1;min-width:0;">
          <div class="cc-title">Annual Report — DE/EN Technical Translation (2024)</div>
          <div class="cc-meta">
            <span>Completed Mar 20, 2025</span>
            <span>·</span>
            <span class="font-mono">CON-NX-3801 · Legal Consulting</span>
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
                <p style="color:var(--ink-mid);font-size:.875rem;line-height:1.65;">Arbiter found your ISO-standard terminology was defensible but deviation from the agreed glossary was not communicated per the contract's amendment clause. <strong>$2,870 released to you, $1,230 retained by client.</strong></p>
              </div>
              <div style="margin-top:14px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 14px;font-size:.8125rem;color:var(--ink-mid);">
                💡 <strong>Lesson for future projects:</strong> Always communicate glossary amendments to clients before implementing them. Consider amendment clauses in your contract template.
              </div>
            </div>
            <div>
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Client's Review</div>
              <div class="review-display">
                "The translation quality was technically sound but the deviation from our agreed glossary without prior notice caused significant internal disruption. The dispute process was handled fairly."
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-top:14px;">
                <div style="padding:10px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Paid</div><div class="font-mono font-bold">$2,870</div></div>
                <div style="padding:10px;background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Recovered</div><div class="font-mono font-bold" style="color:var(--sage);">$1,230</div></div>
                <div style="padding:10px;background:#FBEAE7;border:1px solid #F0C4BC;border-radius:var(--radius-sm);text-align:center;"><div class="text-xs text-muted mb-4">Outcome</div><div class="font-mono font-bold" style="color:var(--rust);">Partial Refund</div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="cc-footer">
          <a href="/dispute" class="btn btn-ghost btn-sm">View Full Dispute Record</a>
        </div>
      </div>
    </div>

    <!-- CARD 4: NORMAL COMPLETED / OLDER -->
    <div class="comp-card on-time" id="cc-3" data-niche="legal" data-repeat="true">
      <div class="cc-header" onclick="toggleCard(3)">
        <div class="cc-niche-icon ni-legal">⚖️</div>
        <div style="flex:1;min-width:0;">
          <div class="cc-title">Customer Segmentation — Retail Banking Dashboard</div>
          <div class="cc-meta">
            <span>Completed Jan 28, 2025</span>
            <span>·</span>
            <span class="font-mono">CON-NX-3344 · Legal Consulting</span>
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
              <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:10px;font-family:var(--font-body);">Client's Rating</div>
              <div class="review-display">
                "Your segmentation model identified three customer cohorts we had completely missed. Delivered 2 days ahead of schedule. The Tableau dashboard was immediately usable by our business team."
              </div>
              <div class="flex items-center gap-10">
                <div class="avatar avatar-sm">ST</div>
                <div>
                  <div style="font-weight:700;font-size:.875rem;">Sarah Thornton</div>
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
          <a href="/profile" class="btn btn-ghost btn-sm">View Client Profile</a>
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
        <h3 id="rating-modal-title">Leave Feedback</h3>
        <p class="text-sm text-muted mt-4">Client feedback is shown on your profile and helps build your reputation.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('rating-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">

      <!-- OVERALL RATING -->
      <div class="form-group">
        <label class="form-label">Overall Rating</label>
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="star-input" id="star-input">
            <span class="star-btn">★</span>
            <span class="star-btn">★</span>
            <span class="star-btn">★</span>
            <span class="star-btn">★</span>
            <span class="star-btn">★</span>
          </div>
          <div id="rating-average" class="rating-average">5.0</div>
        </div>
      </div>

      <!-- DIMENSION RATINGS -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
        <div class="form-group" style="margin:0;">
          <label class="form-label text-xs">Brief Quality &amp; Clarity</label>
          <select class="form-control" style="font-size:.875rem;" onchange="updateRatingPreview()">
            <option>5 — Exceptional</option><option>4 — Good</option><option>3 — Satisfactory</option><option>2 — Poor</option><option>1 — Unacceptable</option>
          </select>
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label text-xs">Communication</label>
          <select class="form-control" style="font-size:.875rem;" onchange="updateRatingPreview()">
            <option>5 — Exceptional</option><option>4 — Good</option><option>3 — Satisfactory</option><option>2 — Poor</option><option>1 — Unacceptable</option>
          </select>
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label text-xs">Deliverable Quality</label>
          <select class="form-control" style="font-size:.875rem;" onchange="updateRatingPreview()">
            <option>5 — Exceptional</option><option>4 — Good</option><option>3 — Satisfactory</option><option>2 — Poor</option><option>1 — Unacceptable</option>
          </select>
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label text-xs">Milestone Adherence</label>
          <select class="form-control" style="font-size:.875rem;" onchange="updateRatingPreview()">
            <option>5 — Exceptional</option><option>4 — Good</option><option>3 — Satisfactory</option><option>2 — Poor</option><option>1 — Unacceptable</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Written Review</label>
        <textarea class="form-control" rows="5" id="review-text"
          placeholder="Describe your experience working with this client. Be specific — your review helps other specialists and the client's reputation on Nexus."
          oninput="countRChars(this)"></textarea>
        <div class="flex justify-between mt-4">
          <p class="form-hint">Min. 50 characters. Specific reviews are more helpful than short ones.</p>
          <span class="char-counter" id="rchc">0 / 800</span>
        </div>
      </div>

      <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:12px;">
        <input type="checkbox" id="review-rehire" style="accent-color:var(--gold);" checked>
        <label for="review-rehire" style="font-size:.875rem;color:var(--ink-mid);">I would work with this client again</label>
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
          <label style="display:flex;gap:8px;font-size:.875rem;cursor:pointer;"><input type="checkbox" checked style="accent-color:var(--gold);"> Earnings breakdown per project</label>
          <label style="display:flex;gap:8px;font-size:.875rem;cursor:pointer;"><input type="checkbox" checked style="accent-color:var(--gold);"> Client names &amp; ratings</label>
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
  const titles = ['Amira Tawfik', 'James Moreau', 'Lena Bergmann', 'Sarah Thornton'];
  document.getElementById('rating-modal-title').textContent = 'Rate ' + (titles[cardIdx] || 'Client');
  document.getElementById('rating-modal').classList.remove('hidden');
  updateRatingPreview();
}

function setStars(n) {
  document.querySelectorAll('.star-btn').forEach((btn, i) => {
    btn.classList.toggle('active', i < n);
  });
}

function updateRatingPreview() {
  const selects = Array.from(document.querySelectorAll('#rating-modal select'));
  const values = selects.map(select => parseInt(select.value, 10)).filter(v => !isNaN(v));
  const avg = values.length ? values.reduce((sum, v) => sum + v, 0) / values.length : 0;
  const rounded = Math.round(avg * 10) / 10;
  setStars(Math.round(avg));
  const avgEl = document.getElementById('rating-average');
  if (avgEl) avgEl.textContent = rounded.toFixed(1);
}

function countRChars(el) {
  const n = el.value.length;
  const c = document.getElementById('rchc');
  if(c) {
    c.textContent = n + ' / 800';
    c.className = 'char-counter' + (n > 720 ? 'warn' : '');
  }
}

function submitReview() {
  const text = document.getElementById('review-text')?.value?.trim();
  if(!text || text.length < 50) {
    showToast('Please write a review of at least 50 characters.', 'warn');
    return;
  }
  document.getElementById('rating-modal').classList.add('hidden');
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
