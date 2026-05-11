<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/client/bid-review.php
    Template: Bid Review — Client View
    Role:     client (authenticated)
    Route:    /projects/{job_id}/proposals
              /projects/{job_id}/proposals/{bid_id}
    ============================================================
    PHP Data contract (from BidReviewController):
      $job            — full job record
      $bids           — paginated collection of BidRecord[]
      $activeBid      — currently-selected BidRecord (from ?bid= param)
      $bidCount       — int total proposals
      $client         — authenticated client
      $canAccept      — bool (no active contract yet on this job)
      $interviewSlots — specialist's stated availability slots
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- PHP: <title>Proposals — <?= htmlspecialchars($job['project_title'] ?? 'Project') ?> · Nexus</title> -->
<title>Proposals — <?php echo htmlspecialchars($job['project_title'] ?? 'Project'); ?> · Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/bid-review.css">
</head>
<body>
<?php $activeBidId = (int) ($bids[0]['id'] ?? 0); ?>

<!-- ══════════ TOPNAV
     PHP: include 'partials/topnav.php'; ['role'=>'client','user'=>$client]
-->
<nav class="topnav">
  <div class="container" style="max-width:100%;padding:0 32px;">
    <a class="topnav-logo" href="/">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="/bid-review">← Proposals</a>
      <a href="/dashboard">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="#" class="btn btn-ghost btn-icon" style="position:relative;">
        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg> <span class="notif-count" style="position:absolute;top:2px;right:2px;">4</span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge">
            <div class="avatar avatar-sm"><?php echo strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? ''), 0, 2)) ?: 'ME'; ?></div>
          </div>
          <span style="font-size:.875rem;font-weight:700;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Me'); ?></span>
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

<!-- PAGE HEADER -->
<div class="page-header-bar">
  <div style="max-width:100%;padding:0 32px;" class="flex justify-between items-center">
    <div>
      <div class="breadcrumb" style="font-family:var(--font-mono);font-size:.75rem;color:var(--ink-muted);margin-bottom:6px;">
        Proposals <span style="margin:0 6px;color:var(--ink-faint);">›</span>
        <?php echo htmlspecialchars($job['project_title'] ?? 'Project'); ?>
      </div>
      <div class="flex items-center gap-14 flex-wrap">
        <h2 style="font-family:var(--font-display);font-size:1.4rem;font-weight:500;">Review Proposals</h2>
        <div class="flex items-center gap-8">
          <span class="badge badge-default font-mono" style="font-size:.75rem;"><?php echo count($bids); ?> total</span>
          <span class="badge badge-verified badge-dot" style="font-size:.75rem;">1 interview</span>
        </div>
      </div>
    </div>
    <div class="flex gap-10 items-center">
      <button class="btn btn-outline btn-sm" onclick="document.getElementById('filters-modal').classList.remove('hidden')">⚙ Filter &amp; Sort</button>
      <!-- PHP: deadline chip -->
    </div>
  </div>
</div>

<!-- ══════════ 3-COLUMN REVIEW SHELL ══════════ -->
<div class="review-shell">

  <!-- ─── LEFT: BID LIST ─── -->
  <div class="bid-list-panel">
    <div class="bid-list-header">
      <div style="font-size:.8rem;color:var(--ink-muted);">
        <!-- PHP: $bidCount.' proposals' -->
        <strong style="color:var(--ink);">7 proposals</strong> . Sorted by:
      </div>
      <div class="bid-sort-bar">
        <span class="sort-chip active" onclick="setSort(this,'match')">Best Match</span>
        <span class="sort-chip" onclick="setSort(this,'rating')">Highest Rated</span>
        <span class="sort-chip" onclick="setSort(this,'low')">Price ↑</span>
        <span class="sort-chip" onclick="setSort(this,'high')">Price ↓</span>
        <span class="sort-chip" onclick="setSort(this,'new')">Newest</span>
      </div>
    </div>

    <?php if (!empty($bids)): ?>
      <?php foreach ($bids as $index => $b): ?>
      <div class="bid-card-item <?php echo $index === 0 ? 'active' : ''; ?>" onclick="selectBid(this, <?php echo $index; ?>)">
        <div class="flex justify-between items-start mb-4">
          <div class="bid-item-name"><?php echo htmlspecialchars($b['specialist_name'] ?? 'Specialist'); ?></div>
          <span class="bid-item-amount <?php echo ($b['total_bid_amount'] ?? 0) > ($job['total_budget'] ?? 0) ? 'over-budget' : 'at-budget'; ?>">$<?php echo number_format($b['total_bid_amount'] ?? 0, 2); ?></span>
        </div>
        <div class="flex items-center gap-6 mb-4">
          <div class="stars" style="font-size:.75rem;">★★★★★</div>
          <span style="font-size:.75rem;color:var(--ink-muted);"><?php echo htmlspecialchars($b['specialist_name'] ? 'Top rated' : 'New'); ?> · <?php echo intval($b['user_id'] ?? 0); ?> projects</span>
        </div>
        <div class="flex gap-16 flex-wrap">
          <span class="badge badge-default" style="font-size:.6rem;"><?php echo htmlspecialchars(ucfirst($b['status'] ?? 'pending')); ?></span>
        </div>
        <div class="bid-item-meta mt-4"><?php echo isset($b['start_date']) ? intval((strtotime('now') - strtotime($b['created_at'])) / 86400) . 'd' : 'TBD'; ?> · Submitted <?php echo isset($b['created_at']) ? date('M j', strtotime($b['created_at'])) : 'N/A'; ?></div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div style="padding:20px;text-align:center;color:var(--ink-muted);">
        No proposals received yet.
      </div>
    <?php endif; ?>

    <div style="padding:20px;text-align:center;">
      <button class="btn btn-ghost btn-sm" style="font-size:.75rem;">Load more proposals</button>
    </div>
  </div>

  <!-- ─── CENTRE: BID DETAIL ─── -->
  <div class="bid-detail-panel" id="bid-detail">

    <!-- PHP: if(!$activeBid): show empty state -->
    <!-- SHOWN WHEN A BID IS SELECTED — default show first bid -->

    <!-- BID HEADER STATUS BAR -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
      <div class="flex items-center gap-16">
        <!-- PHP: 'Ref: BID-'.$bid['id'] -->
        <span class="text-xs text-muted font-mono">Ref: BID-NX-4821-DR</span>
        <span class="text-xs text-muted font-mono">Submitted Apr 12</span>
      </div>
      <div class="flex gap-8">
        <button class="btn btn-ghost btn-sm" onclick="prevBid()">← Prev</button>
        <!-- PHP: $currentIndex + 1 . ' of ' . $bidCount -->
        <span class="text-xs text-muted" style="padding:6px 0;">1 of 7</span>
        <button class="btn btn-ghost btn-sm" onclick="nextBid()">Next →</button>
      </div>
    </div>

    <!-- SPECIALIST HERO CARD -->
    <div class="specialist-hero">
      <div class="avatar-badge"><div class="avatar avatar-lg">DR</div></div>
      <div style="flex:1;min-width:0;">
        <div class="flex items-center gap-16 flex-wrap mb-4">
          <!-- PHP: htmlspecialchars($bid['specialist']['display_name']) -->
          <h3 id="bid-specialist-name" style="font-family:var(--font-display);font-size:1.3rem;font-weight:600;">Dr. Rania Khalil</h3>
          <span id="detail-status" class="bid-status-pill new">● New</span>
          <span class="badge badge-verified badge-dot" style="font-size:.7rem;">Verified</span>
        </div>
        <div style="font-size:.875rem;color:var(--ink-muted);margin-bottom:8px;">Senior Data Scientist · Cairo, Egypt</div>
        <div class="flex gap-16 flex-wrap" style="font-size:.8125rem;">
          <div><div class="stars" style="font-size:.8rem;">★★★★★</div><div class="text-xs text-muted mt-2">4.97 · 83 projects</div></div>
          <div><div style="font-family:var(--font-mono);font-weight:600;">92%</div><div class="text-xs text-muted mt-2">Milestone Rate</div></div>
          <div><div style="font-family:var(--font-mono);font-weight:600;">$740K</div><div class="text-xs text-muted mt-2">Delivered</div></div>
        </div>
      </div>
      <div style="text-align:right;flex-shrink:0;">
        <a href="/profile" class="btn btn-outline btn-sm" target="_blank">View Full Profile →</a>
      </div>
    </div>

    <!-- SECTION A: COVER LETTER -->
    <div class="bid-section">
      <div class="bid-section-label">A — Cover Letter</div>
      <!-- PHP: nl2br(htmlspecialchars($bid['cover_letter'])) -->
      <div id="bid-cover-letter" style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:22px;font-size:.9375rem;line-height:1.75;color:var(--ink-mid);">
        <p style="margin-bottom:12px;">Dear Amira,</p>
        <p style="margin-bottom:12px;">I have reviewed your project brief carefully and note your specific need for GDPR cross-border transfer analysis alongside Egyptian, UAE, and KSA compliance — this intersection is precisely the area I specialise in.</p>
        <p style="margin-bottom:12px;">Over the past six years I have advised four SaaS companies on MENA market entry contracts, including two with DIFC Courts arbitration clauses and one requiring full GDPR Standard Contractual Clause integration. I am admitted to the Cairo Bar, hold an LLM in International Commercial Law from UCL, and am fluent in Arabic, English, and French.</p>
        <p>My Phase 1 approach will begin with a complete document inventory and a gap analysis matrix comparing your existing contracts against each jurisdiction's current requirements — delivered as a structured PDF with prioritised findings, so your internal team can immediately see what needs to change before I begin drafting.</p>
      </div>
    </div>

    <!-- SECTION B: KEY DIFFERENTIATORS -->
    <div class="bid-section">
      <div class="bid-section-label">B — Key Differentiators</div>
      <div id="bid-differentiators" style="background:var(--gold-pale);border:1px solid var(--gold-light);border-radius:var(--radius-md);padding:18px 20px;font-size:.875rem;color:var(--ink-mid);line-height:1.7;">
        <!-- PHP: nl2br(htmlspecialchars($bid['differentiators'])) -->
        Cairo Bar admission (2014) · DIFC Courts practitioner (listed) · LLM International Commercial Law, UCL · 4 prior SaaS market-entry engagements · Native Arabic, fluent English and French · GDPR SCC specialist · ISO 27001-aware legal practice.
      </div>
    </div>

    <!-- SECTION C: RELEVANT PAST WORK -->
    <div class="bid-section">
      <div class="bid-section-label">C — Relevant Past Work</div>
      <div id="bid-past-work" style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:18px 20px;font-size:.875rem;color:var(--ink-mid);line-height:1.7;">
        <!-- PHP: nl2br(htmlspecialchars($bid['past_work'])) -->
        Advised a Cairo-based fintech on a UAE market entry in 2023 — delivered a full contract suite (distributor, SaaS subscription, employment) across EGY/UAE jurisdictions. Secondly, assisted a logistics technology firm entering KSA on a Vision 2030 procurement framework engagement — negotiated cross-border data residency clauses with their Saudi counsel. Both engagements completed within milestone budget and on time.
      </div>
    </div>

    <!-- SECTION D: BUDGET PROPOSAL -->
    <div class="bid-section">
      <div class="bid-section-label">D — Budget &amp; Financials</div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
        <!-- PHP: $bid['bid_total'] -->
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px;text-align:center;">
          <div id="bid-total-amount" style="font-family:var(--font-display);font-size:1.8rem;font-weight:300;">$12,000</div>
          <div class="text-xs text-muted mt-4">Proposed Total</div>
          <span class="ms-delta eq" style="margin-top:6px;display:inline-flex;">= Client Budget</span>
        </div>
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px;text-align:center;">
          <div id="bid-day-rate" style="font-family:var(--font-display);font-size:1.8rem;font-weight:300;">$245</div>
          <div class="text-xs text-muted mt-4">Effective Day Rate</div>
        </div>
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px;text-align:center;">
          <div id="bid-duration" style="font-family:var(--font-display);font-size:1.8rem;font-weight:300;">49d</div>
          <div class="text-xs text-muted mt-4">Proposed Duration</div>
        </div>
      </div>
      <!-- PHP: if($bid['budget_rationale']): -->
    </div>

    <!-- SECTION E: MILESTONE COMPARISON -->
    <div class="bid-section">
      <div class="bid-section-label">E — Milestone Comparison</div>
      <div style="border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;">
        <div class="ms-compare-row header">
          <span>Phase</span>
          <span class="ms-cell">Client Proposed</span>
          <span class="ms-cell">Specialist Bid</span>
          <span class="ms-cell">Delta</span>
        </div>
        <!-- PHP: foreach($bid['milestones'] as $i=>$ms): -->
        <div class="ms-compare-row">
          <span class="ms-cell name">1 · Initial Review &amp; Gap Analysis</span>
          <span class="ms-cell" style="color:var(--ink-muted);">14d · $3,000</span>
          <span class="ms-cell" style="font-weight:700;">14d · $3,000</span>
          <span class="ms-cell"><span class="ms-delta eq">No change</span></span>
        </div>
        <div class="ms-compare-row">
          <span class="ms-cell name">2 · Jurisdiction Analysis</span>
          <span class="ms-cell" style="color:var(--ink-muted);">21d · $4,500</span>
          <span class="ms-cell" style="font-weight:700;">21d · $4,500</span>
          <span class="ms-cell"><span class="ms-delta eq">No change</span></span>
        </div>
        <div class="ms-compare-row">
          <span class="ms-cell name">3 · Final Contracts &amp; Report</span>
          <span class="ms-cell" style="color:var(--ink-muted);">14d · $4,500</span>
          <span class="ms-cell" style="font-weight:700;">14d · $4,500</span>
          <span class="ms-cell"><span class="ms-delta eq">No change</span></span>
        </div>
        <div class="ms-compare-row" style="background:var(--ivory-deep);font-weight:700;">
          <span class="ms-cell name">Total</span>
          <span class="ms-cell" style="color:var(--ink-muted);">49d · $12,000</span>
          <span class="ms-cell" style="color:var(--sage);">49d · $12,000</span>
          <span class="ms-cell"><span class="ms-delta eq">= Budget</span></span>
        </div>
      </div>
    </div>

    <!-- SECTION G: AVAILABILITY -->
    <div class="bid-section">
      <div class="bid-section-label">G — Availability &amp; Start Date</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
          <div class="text-xs text-muted mb-4">Proposed Start Date</div>
          <!-- PHP: date('M j, Y', $bid['start_date']) -->
          <div id="bid-start-date" style="font-weight:700;font-family:var(--font-mono);">Apr 22, 2025</div>
          <div class="text-xs text-muted mt-2">Within 12 days of contract</div>
        </div>
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
          <div class="text-xs text-muted mb-4">Current Availability</div>
          <div style="font-weight:700;color:var(--sage);">● Available Now</div>
          <div class="text-xs text-muted mt-2">GMT+2 · Cairo</div>
        </div>
      </div>
      <!-- PHP: $bid['availability_slots'] -->
      <div>
        <div class="text-xs text-muted mb-6">Available for meetings &amp; check-ins (GMT+2):</div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <span class="badge badge-verified" style="font-size:.65rem;">Mon 14–16</span>
          <span class="badge badge-verified" style="font-size:.65rem;">Tue 09–11</span>
          <span class="badge badge-verified" style="font-size:.65rem;">Wed 09–11</span>
          <span class="badge badge-verified" style="font-size:.65rem;">Thu 14–16</span>
        </div>
      </div>
    </div>

    <!-- SECTION H: REVIEW PRICING -->
    <div class="bid-section">
      <div class="bid-section-label">H — Review Pricing</div>
      <!-- PHP: if($bid['review_price'] || $bid['free_reviews']): -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <!-- PHP: if($bid['review_price']): -->
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
          <div class="text-xs text-muted mb-4">Per-Review Charge</div>
          <!-- PHP: $bid['review_price'] -->
          <div style="font-weight:700;font-family:var(--font-mono);font-size:1.3rem;">$450</div>
          <div class="text-xs text-muted mt-2">After included reviews</div>
        </div>
        <!-- PHP: endif; -->
        <!-- PHP: if($bid['free_reviews']): -->
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
          <div class="text-xs text-muted mb-4">Free Reviews Included</div>
          <!-- PHP: $bid['free_reviews'] -->
          <div style="font-weight:700;font-family:var(--font-mono);font-size:1.3rem;">2</div>
          <div class="text-xs text-muted mt-2">Complimentary reviews</div>
        </div>
        <!-- PHP: endif; -->
      </div>
    </div>

    <!-- SECTION I: PROFILE SCORE BREAKDOWN -->
    <div class="bid-section">
      <div class="bid-section-label">I — Profile &amp; Match Score</div>
      <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:18px 20px;">
        <div class="flex items-center gap-16 mb-16">
          <div style="text-align:center;">
            <div style="font-family:var(--font-display);font-size:2.5rem;font-weight:300;line-height:1;color:var(--gold);">95%</div>
            <div class="text-xs text-muted mt-4">Match Score</div>
          </div>
          <div style="flex:1;">
            <!-- PHP: foreach($matchDimensions as $d): -->
            <div class="score-dimension">
              <span class="score-dim-label">Niche alignment</span>
              <div class="score-dim-bar"><div class="score-dim-fill" style="width:100%;"></div></div>
              <span class="score-dim-val">100</span>
            </div>
            <div class="score-dimension">
              <span class="score-dim-label">Credential match</span>
              <div class="score-dim-bar"><div class="score-dim-fill" style="width:100%;"></div></div>
              <span class="score-dim-val">100</span>
            </div>
            <div class="score-dimension">
              <span class="score-dim-label">Reputation score</span>
              <div class="score-dim-bar"><div class="score-dim-fill" style="width:99%;"></div></div>
              <span class="score-dim-val">99</span>
            </div>
            <div class="score-dimension">
              <span class="score-dim-label">Milestone adherence</span>
              <div class="score-dim-bar"><div class="score-dim-fill" style="width:92%;"></div></div>
              <span class="score-dim-val">92</span>
            </div>
            <div class="score-dimension">
              <span class="score-dim-label">Budget alignment</span>
              <div class="score-dim-bar"><div class="score-dim-fill" style="width:100%;"></div></div>
              <span class="score-dim-val">100</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION J: ATTACHMENTS -->
    <div class="bid-section">
      <div class="bid-section-label">J — Attachments</div>
      <!-- PHP: foreach($bid['attachments'] as $a): -->
      <div style="display:flex;align-items:center;gap:12px;padding:11px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-bottom:6px;font-size:.875rem;">
        <span style="font-size:1rem;">📄</span>
        <span style="flex:1;font-weight:600;">MENA_Legal_Portfolio_Sample.pdf</span>
        <span style="font-family:var(--font-mono);font-size:.75rem;color:var(--ink-muted);">2.4 MB</span>
        <button class="btn btn-outline btn-sm">Download</button>
      </div>
      <div style="display:flex;align-items:center;gap:12px;padding:11px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-bottom:6px;font-size:.875rem;">
        <span style="font-size:1rem;">📝</span>
        <span style="flex:1;font-weight:600;">GDPR_SCC_Approach_Note.pdf</span>
        <span style="font-family:var(--font-mono);font-size:.75rem;color:var(--ink-muted);">840 KB</span>
        <button class="btn btn-outline btn-sm">Download</button>
      </div>
    </div>

  </div><!-- end bid-detail-panel -->

  <!-- ─── RIGHT: ACTION PANEL ─── -->
  <div class="action-panel">

    <!-- DECISION ACTIONS -->
    <div class="action-section-title">Decision</div>

    <!-- PHP: if($canAccept && $bid['status'] !== 'accepted'): -->
    <button class="decision-btn accept" onclick="document.getElementById('accept-modal').classList.remove('hidden')">
      ✦ Accept &amp; Issue Contract
    </button>
    <button class="decision-btn interview" onclick="document.getElementById('interview-modal').classList.remove('hidden')">
      🎙 Schedule Interview
    </button>
    <button class="decision-btn message" onclick="document.getElementById('message-modal').classList.remove('hidden')">
      💬 Send Message
    </button>
    <button class="decision-btn decline" onclick="document.getElementById('decline-modal').classList.remove('hidden')">
      Decline Proposal
    </button>

    <hr class="divider" style="margin:16px 0;">


    <!-- PRIVATE NOTES -->
    <div class="action-section-title">Private Notes</div>
    <p style="font-size:.75rem;color:var(--ink-muted);margin-bottom:8px;">Only visible to you. Not shared with the specialist.</p>
    <!-- PHP: value="<?= htmlspecialchars($bid['client_notes'] ?? '') ?>" -->
    <textarea class="private-notes-area" id="private-notes"
      placeholder="Add private notes about this candidate…"
      oninput="autoSaveNotes()">Strong candidate — Cairo Bar confirmed. GDPR SCC experience is a differentiator. Arrange 30min call to check their KSA law depth before committing.</textarea>
    <div class="flex justify-between mt-6">
      <span class="text-xs text-muted font-mono" id="notes-save-status">Auto-saved</span>
      <span class="text-xs text-muted font-mono" id="notes-charcount">234 chars</span>
    </div>

    <hr class="divider" style="margin:16px 0;">

    <!-- QUICK STATS -->
    <div class="action-section-title">Bid At a Glance</div>
    <div style="display:flex;flex-direction:column;gap:0;">
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">Proposed Budget</span>
        <span class="font-mono font-bold">$12,000</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">vs Client Budget</span>
        <span class="font-mono" style="color:var(--sage);">= No change</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">Duration</span>
        <span class="font-mono font-bold">49 days</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">Milestone Changes</span>
        <span class="font-mono" style="color:var(--sage);">None</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">Attachments</span>
        <span class="font-mono font-bold">2 files</span>
      </div>
    </div>

  </div><!-- end action-panel -->

</div><!-- end review-shell -->

<!-- ══════════ MODALS ══════════ -->

<!-- ACCEPT & ISSUE CONTRACT MODAL -->
<div id="accept-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Accept Proposal &amp; Issue Contract</h3>
        <p class="text-sm text-muted mt-4">This will generate a binding contract, trigger NDA delivery, and lock the first milestone escrow from your payment method.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('accept-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">

      <!-- CONTRACT SUMMARY -->
      <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-md);padding:18px 20px;margin-bottom:20px;">
        <div style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;font-family:var(--font-body);">Contract Summary</div>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:.875rem;">
          <div class="flex justify-between"><span class="text-muted">Specialist</span><span class="font-bold">Dr. Rania Khalil</span></div>
          <div class="flex justify-between"><span class="text-muted">Total Value</span><span class="font-mono font-bold">$12,000</span></div>
          <div class="flex justify-between"><span class="text-muted">First Escrow (Phase 1)</span><span class="font-mono font-bold">$3,000 — charged now</span></div>
          <div class="flex justify-between"><span class="text-muted">Duration</span><span class="font-mono">49 days</span></div>
          <div class="flex justify-between"><span class="text-muted">NDA</span><span>Standard Nexus · 2yr · $10K damages</span></div>
          <div class="flex justify-between"><span class="text-muted">Free Revisions</span><span>2 per milestone</span></div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Contract Start Date</label>
        <input type="date" class="form-control" id="contract-start">
        <p class="form-hint mt-4">Specialist proposed: Apr 22, 2025. Leave blank to use today's date.</p>
      </div>

      <div class="form-group">
        <label class="form-label">Message to Specialist <span class="text-muted font-mono" style="font-size:.7rem;font-weight:400;text-transform:none;letter-spacing:0;">Optional — sent with the contract</span></label>
        <textarea class="form-control" rows="3" placeholder="e.g. Dr. Khalil — we're pleased to accept your proposal. Looking forward to working with you on this…"></textarea>
      </div>

      <div class="verify-band">
        <span>💳</span>
        <div style="font-size:.8125rem;"><strong>$3,000</strong> will be immediately locked from your Mastercard ···· 4821. The specialist will be notified and an NDA sent for signature before project access is granted.</div>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('accept-modal').classList.add('hidden')">Cancel</button>
      <form method="post" action="/bid-review" style="margin:0;"><input type="hidden" name="action" value="accept"><input type="hidden" name="job_id" value="<?php echo (int) ($job['id'] ?? 0); ?>"><input type="hidden" class="selected-bid-id" name="bid_id" value="<?php echo $activeBidId; ?>"><button class="btn btn-primary" type="submit">Accept &amp; Issue Contract</button></form>
    </div>
  </div>
</div>

<!-- SCHEDULE INTERVIEW MODAL -->
<div id="interview-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Schedule Technical Interview</h3>
        <p class="text-sm text-muted mt-4">Select a slot from Dr. Rania Khalil's stated availability. A calendar invite will be sent to both parties.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('interview-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">

      <!-- SPECIALIST SLOTS -->
      <div style="margin-bottom:20px;">
        <label class="form-label">Specialist's Available Slots (GMT+2)</label>
        <p class="form-hint mb-10">Dr. Rania Khalil marked the following as available for meetings.</p>
        <div class="slot-grid" id="slot-grid">
          <!-- PHP: foreach($interviewSlots as $slot): -->
          <button type="button" class="slot-btn" onclick="selectSlot(this)">Mon 14:00–16:00</button>
          <button type="button" class="slot-btn" onclick="selectSlot(this)">Tue 09:00–11:00</button>
          <button type="button" class="slot-btn" onclick="selectSlot(this)">Wed 09:00–11:00</button>
          <button type="button" class="slot-btn" onclick="selectSlot(this)">Thu 14:00–16:00</button>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Interview Duration</label>
        <select class="form-control">
          <option>30 minutes</option>
          <option selected>45 minutes</option>
          <option>60 minutes</option>
          <option>90 minutes</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Meeting Platform</label>
        <select class="form-control">
          <option>Google Meet (link auto-generated)</option>
          <option>Zoom (link auto-generated)</option>
          <option>Microsoft Teams</option>
          <option>In-person (specify address in notes)</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Interview Agenda / Topics <span class="text-muted font-mono" style="font-size:.7rem;font-weight:400;text-transform:none;letter-spacing:0;">Sent to specialist in advance</span></label>
        <textarea class="form-control" rows="4"
          placeholder="e.g. 1. Verify KSA commercial law depth&#10;2. Discuss Phase 1 gap-analysis methodology&#10;3. Clarify GDPR SCC approach for non-adequacy jurisdictions&#10;4. Review availability and stakeholder Q&A cadence">1. Verify depth of KSA commercial law experience
2. Walk through Phase 1 methodology for gap analysis
3. Discuss GDPR SCC approach for Egypt→EU data transfers
4. Confirm Arabic drafting quality and review cadence</textarea>
      </div>

      <div style="background:var(--gold-pale);border:1px solid var(--gold-light);border-radius:var(--radius-sm);padding:12px 14px;font-size:.8125rem;display:flex;gap:10px;">
        <span>📋</span>
        <div>An NDA is ready for Dr. Khalil. The pre-interview brief will be shared after NDA signature. Interview confirmation includes a reminder of NDA terms.</div>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('interview-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="scheduleInterview()">Send Interview Invitation</button>
    </div>
  </div>
</div>

<!-- SEND MESSAGE MODAL -->
<div id="message-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Message Dr. Rania Khalil</h3>
        <p class="text-sm text-muted mt-4">Pre-contract messages are recorded and encrypted. The specialist will be notified immediately.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('message-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">

      <!-- QUICK TEMPLATES -->
      <div style="margin-bottom:16px;">
        <label class="form-label">Quick Templates</label>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <button type="button" class="sort-chip" onclick="applyTemplate('request-info')">Request More Info</button>
          <button type="button" class="sort-chip" onclick="applyTemplate('clarify-scope')">Clarify Scope</button>
          <button type="button" class="sort-chip" onclick="applyTemplate('nda-prompt')">NDA Reminder</button>
          <button type="button" class="sort-chip" onclick="applyTemplate('budget-discuss')">Discuss Budget</button>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Message</label>
        <textarea class="form-control" rows="6" id="message-body"
          placeholder="Write your message to Dr. Khalil…"></textarea>
      </div>

      <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);font-size:.8125rem;">
        <input type="checkbox" id="msg-attach-proposal" style="accent-color:var(--gold);">
        <label for="msg-attach-proposal">Attach a link to the proposal summary for reference</label>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('message-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="sendMessage()">Send Message</button>
    </div>
  </div>
</div>

<!-- DECLINE MODAL -->
<div id="decline-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Decline This Proposal</h3>
        <p class="text-sm text-muted mt-4">The specialist will receive a notification. You can optionally include feedback.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('decline-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Reason for Decline</label>
        <select class="form-control" id="decline-reason">
          <option value="">— Select a reason —</option>
          <option>Selected another specialist</option>
          <option>Budget too high</option>
          <option>Credentials did not fully match</option>
          <option>Proposed timeline too long</option>
          <option>Proposal lacked specificity</option>
          <option>Project cancelled or postponed</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Optional Feedback <span class="text-muted font-mono" style="font-size:.7rem;font-weight:400;text-transform:none;letter-spacing:0;">Sent to specialist</span></label>
        <textarea class="form-control" rows="3"
          placeholder="Providing feedback helps specialists improve their proposals. Keep it professional."></textarea>
      </div>
      <div style="display:flex;align-items:center;gap:8px;font-size:.875rem;">
        <input type="checkbox" id="decline-keep-open" checked style="accent-color:var(--gold);">
        <label for="decline-keep-open">Keep this specialist in mind for future projects</label>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('decline-modal').classList.add('hidden')">Cancel</button>
      <form method="post" action="/bid-review" style="margin:0;"><input type="hidden" name="action" value="decline"><input type="hidden" name="job_id" value="<?php echo (int) ($job['id'] ?? 0); ?>"><input type="hidden" class="selected-bid-id" name="bid_id" value="<?php echo $activeBidId; ?>"><button class="btn btn-danger" type="submit">Decline Proposal</button></form>
    </div>
  </div>
</div>

<!-- CONTRACT ISSUED SUCCESS MODAL -->
<div id="contract-success-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm" style="text-align:center;">
    <div class="modal-body" style="padding:48px 32px;">
      <div style="font-size:3rem;margin-bottom:20px;">✦</div>
      <h3 style="margin-bottom:10px;">Contract Issued</h3>
      <p class="text-sm text-muted mb-6">A contract and NDA have been sent to <strong>Dr. Rania Khalil</strong>. Work begins once the NDA is signed and Phase 1 escrow is confirmed.</p>
      <div class="font-mono text-xs text-muted mb-24">Contract Ref: CON-NX-4821-2025</div>
      <div style="display:flex;flex-direction:column;gap:10px;">
        <a href="/project-detail" class="btn btn-primary" style="justify-content:center;">Go to Project Page</a>
        <a href="/chat" class="btn btn-outline" style="justify-content:center;">Open Project Messages</a>
      </div>
    </div>
  </div>
</div>

<!-- FILTERS MODAL -->
<div id="filters-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3>Filter &amp; Sort Proposals</h3>
      <button class="modal-close" onclick="document.getElementById('filters-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Sort By</label>
        <select class="form-control">
          <option>Best Match Score</option>
          <option>Highest Reputation</option>
          <option>Price — Lowest First</option>
          <option>Price — Highest First</option>
          <option>Submitted — Newest First</option>
          <option>Shortest Duration</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Status Filter</label>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <label style="display:flex;gap:8px;font-size:.875rem;cursor:pointer;"><input type="checkbox" checked style="accent-color:var(--gold);"> New &amp; Unreviewed</label>
          <label style="display:flex;gap:8px;font-size:.875rem;cursor:pointer;"><input type="checkbox" checked style="accent-color:var(--gold);"> Interview Scheduled</label>
          <label style="display:flex;gap:8px;font-size:.875rem;cursor:pointer;"><input type="checkbox" style="accent-color:var(--gold);"> Declined</label>
        </div>
      </div>
      <?php require __DIR__ . '/../partials/topnav.php'; ?>

<!-- TOAST -->
<div class="toast-stack" id="toast-stack"></div>

<script>
// PHP: Pass bids data to JS
const bidsData = <?php echo json_encode($bids); ?>;

function escapeHtml(value) {
  const div = document.createElement('div');
  div.textContent = value || '';
  return div.innerHTML;
}

function textWithBreaks(value) {
  return escapeHtml(value).replace(/\n/g, '<br>');
}

function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});

/* ── BID SELECTION ── */
function selectBid(el, idx) {
  document.querySelectorAll('.bid-card-item').forEach(c => c.classList.remove('active'));
  el.classList.add('active');

  const bid = bidsData[idx];
  if (bid) {
    document.querySelectorAll('.selected-bid-id').forEach(input => {
      input.value = bid.id || '';
    });
    document.getElementById('bid-specialist-name').textContent = bid.specialist_name || 'Specialist';
    document.getElementById('bid-total-amount').textContent = '$' + (bid.total_bid_amount || 0).toLocaleString();

    const coverLetterEl = document.getElementById('bid-cover-letter');
    coverLetterEl.innerHTML = bid.proposal_message ? textWithBreaks(bid.proposal_message) : 'No cover letter provided.';

    const differentiatorsEl = document.getElementById('bid-differentiators');
    differentiatorsEl.innerHTML = bid.key_differentiators ? textWithBreaks(bid.key_differentiators) : 'No key differentiators provided.';

    const pastWorkEl = document.getElementById('bid-past-work');
    pastWorkEl.innerHTML = bid.relevant_work ? textWithBreaks(bid.relevant_work) : 'No relevant past work provided.';

    const dayRateEl = document.getElementById('bid-day-rate');
    const duration = bid.total_duration || 0;
    const dayRate = duration > 0 ? Math.round(bid.total_bid_amount / duration) : 0;
    dayRateEl.textContent = '$' + dayRate.toLocaleString();

    const durationEl = document.getElementById('bid-duration');
    durationEl.textContent = duration + 'd';

    const startDateEl = document.getElementById('bid-start-date');
    startDateEl.textContent = bid.start_date ? new Date(bid.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'TBD';

    // Update status
    const statusMap = {
      'pending': 'new',
      'interview': 'interview',
      'accepted': 'accepted',
      'declined': 'declined'
    };
    const statusClass = statusMap[bid.status] || 'new';
    const statusLabel = bid.status === 'interview' ? '🎙 Interview Set' : bid.status === 'declined' ? 'Declined' : '● New';
    const statusEl = document.getElementById('detail-status');
    statusEl.className = 'bid-status-pill ' + statusClass;
    statusEl.textContent = statusLabel;
  }
}

/* ── SORT ── */
function setSort(el, key) {
  document.querySelectorAll('.sort-chip').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  // PHP: reloads page with ?sort={key}
}

/* ── NAV ── */
function prevBid() { showToast('Previous proposal loaded.','info'); }
function nextBid() { showToast('Next proposal loaded.','info'); }

/* ── ACCEPT ── */
function acceptBid() {
  document.getElementById('accept-modal').classList.add('hidden');
  document.getElementById('contract-success-modal').classList.remove('hidden');
}

/* ── INTERVIEW ── */
function selectSlot(el) {
  document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
  el.classList.add('selected');
}
function scheduleInterview() {
  const selected = document.querySelector('.slot-btn.selected');
  const custom = document.getElementById('interview-date')?.value;
  if(!selected && !custom) { showToast('Please select a time slot or propose a custom date.','warn'); return; }
  document.getElementById('interview-modal').classList.add('hidden');
  showToast('Interview invitation sent to Dr. Rania Khalil. Calendar invite generated.');
}

/* ── MESSAGE ── */
const templates = {
  'request-info': 'Dear Dr. Khalil,\n\nThank you for your proposal. Before we proceed, could you please provide more detail on [specific area]?\n\nKind regards,\nAmira',
  'clarify-scope': 'Dear Dr. Khalil,\n\nWe would like to clarify one aspect of the project scope before making a decision. Could you confirm your approach to [specific area]?\n\nKind regards,\nAmira',
  'nda-prompt': 'Dear Dr. Khalil,\n\nWe are progressing your proposal to the next review stage. Please check your notifications — an NDA has been generated and is awaiting your digital signature before we share the full project materials.\n\nKind regards,\nAmira',
  'budget-discuss': 'Dear Dr. Khalil,\n\nWe are interested in your proposal but would like to discuss the budget for Phase 2 before confirming. Would you be open to a short call?\n\nKind regards,\nAmira'
};
function applyTemplate(key) {
  const el = document.getElementById('message-body');
  if(el && templates[key]) { el.value = templates[key]; }
}
function sendMessage() {
  const msg = document.getElementById('message-body')?.value?.trim();
  if(!msg || msg.length < 10) { showToast('Please write a message before sending.','warn'); return; }
  document.getElementById('message-modal').classList.add('hidden');
  showToast('Message sent to Dr. Rania Khalil.');
}

/* ── DECLINE ── */
function declineBid() {
  document.getElementById('decline-modal').classList.add('hidden');
  showToast('Proposal declined. Dr. Khalil has been notified.','info');
}

/* ── NOTES AUTO-SAVE ── */
let notesSaveTimer;
function autoSaveNotes() {
  const el = document.getElementById('notes-save-status');
  const cc = document.getElementById('notes-charcount');
  const val = document.getElementById('private-notes')?.value || '';
  if(el) el.textContent = 'Unsaved…';
  if(cc) cc.textContent = val.length + ' chars';
  clearTimeout(notesSaveTimer);
  notesSaveTimer = setTimeout(()=>{
    // PHP: AJAX POST /projects/{job_id}/proposals/{bid_id}/notes
    if(el) el.textContent = 'Auto-saved';
  }, 1200);
}

/* ── COMPARE TOGGLE ── */
function toggleCompare(el) {
  showToast(el.checked ? 'Comparison view enabled — scroll milestone section.' : 'Comparison view hidden.','info');
}

/* ── TOAST ── */
function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  const icons = {success:'✓', warn:'⚠', info:'ℹ'};
  const cls = {success:'success', warn:'warning', info:''};
  s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type==='warn'?'Required':type==='info'?'Notice':'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(()=>s.innerHTML='', 4500);
}

// Init notes char count
document.addEventListener('DOMContentLoaded', () => {
  const n = document.getElementById('private-notes');
  const cc = document.getElementById('notes-charcount');
  if(n && cc) cc.textContent = n.value.length + ' chars';

  // Select first bid by default
  const firstBid = document.querySelector('.bid-card-item');
  if (firstBid) {
    selectBid(firstBid, 0);
  }
});
</script>
</body>
</html>
