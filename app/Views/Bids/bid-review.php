<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/Bids/bid-review.php
    Template: Bid Review — Client View
    Role:     client (authenticated)
    Route:    /bid-review?job_id={job_id}
              POST /bid-review  action=accept|decline
    ============================================================
    PHP Data contract (from BidController::index2 / store2):
      $job            — full job record (Project row)
      $bids           — array of BidRecord[] with milestones, slots
      $activeBid      — first bid or ?bid= selected (derived below)
      $client         — $_SESSION data
      $canAccept      — bool: no active contract on this job yet
      $interviewSlots — specialist's stated availability slots
    ============================================================
-->
<?php
// ── Derived helpers ────────────────────────────────────────────
$activeBid    = $bids[0] ?? null;
$activeBidId  = (int) ($activeBid['id'] ?? 0);
$bidCount     = count($bids);
$jobTitle     = htmlspecialchars($job['project_title'] ?? $job['title'] ?? 'Project');
$jobBudget    = (float) ($job['total_budget'] ?? $job['budget'] ?? 0);
$canAccept    = $canAccept ?? true;

// Helper: display star rating as filled/empty stars
function starString(float $rating, int $max = 5): string {
    $full  = min((int) round($rating), $max);
    $empty = $max - $full;
    return str_repeat('★', $full) . str_repeat('☆', $empty);
}

// Helper: days-ago label
function daysAgo(?string $dateStr): string {
    if (!$dateStr) return '?d';
    $diff = (int) round((time() - strtotime($dateStr)) / 86400);
    return $diff . 'd';
}
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Proposals — <?= $jobTitle ?> · Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/bid-review.css">
</head>
<body>

<!-- ══════════ TOPNAV ══════════ -->
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
        </svg>
        <span class="notif-count" style="position:absolute;top:2px;right:2px;">4</span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge">
            <div class="avatar avatar-sm"><?= strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? ''), 0, 2)) ?: 'ME' ?></div>
          </div>
          <span style="font-size:.875rem;font-weight:700;"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Me') ?></span>
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
        <?= $jobTitle ?>
      </div>
      <div class="flex items-center gap-14 flex-wrap">
        <h2 style="font-family:var(--font-display);font-size:1.4rem;font-weight:500;">Review Proposals</h2>
        <div class="flex items-center gap-8">
          <span class="badge badge-default font-mono" style="font-size:.75rem;"><?= $bidCount ?> total</span>
          <?php
          $interviewCount = count(array_filter($bids, fn($b) => ($b['status'] ?? '') === 'interview'));
          if ($interviewCount): ?>
          <span class="badge badge-verified badge-dot" style="font-size:.75rem;"><?= $interviewCount ?> interview<?= $interviewCount !== 1 ? 's' : '' ?></span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="flex gap-10 items-center">
      <button class="btn btn-outline btn-sm" onclick="document.getElementById('filters-modal').classList.remove('hidden')">⚙ Filter &amp; Sort</button>
      <?php if (!empty($job['deadline'])): ?>
      <span class="badge badge-pending font-mono" style="font-size:.75rem;">Deadline: <?= htmlspecialchars(date('M j, Y', strtotime($job['deadline']))) ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ══════════ 3-COLUMN REVIEW SHELL ══════════ -->
<div class="review-shell">

  <!-- ─── LEFT: BID LIST ─── -->
  <div class="bid-list-panel">
    <div class="bid-list-header">
      <div style="font-size:.8rem;color:var(--ink-muted);">
        <strong style="color:var(--ink);"><?= $bidCount ?> proposal<?= $bidCount !== 1 ? 's' : '' ?></strong> · Sorted by:
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
      <?php
        $bidAmt      = (float) ($b['total_bid_amount'] ?? 0);
        $overBudget  = $jobBudget > 0 && $bidAmt > $jobBudget;
        $initials    = strtoupper(substr($b['specialist_name'] ?? '?', 0, 2));
        $rating      = (float) ($b['rating'] ?? 5);
        $projects    = (int) ($b['completed_projects'] ?? 0);
        $statusLabel = ucfirst($b['status'] ?? 'pending');
        $submittedAt = $b['created_at'] ?? $b['submitted_at'] ?? null;
      ?>
      <div class="bid-card-item <?= $index === 0 ? 'active' : '' ?>" onclick="selectBid(this, <?= $index ?>)">
        <div class="flex justify-between items-start mb-4">
          <div class="flex items-center gap-8">
            <div class="avatar avatar-sm" style="font-size:.65rem;"><?= $initials ?></div>
            <div class="bid-item-name"><?= htmlspecialchars($b['specialist_name'] ?? 'Specialist') ?></div>
          </div>
          <span class="bid-item-amount <?= $overBudget ? 'over-budget' : 'at-budget' ?>">
            $<?= number_format($bidAmt, 0) ?>
          </span>
        </div>
        <div class="flex items-center gap-6 mb-4">
          <div class="stars" style="font-size:.75rem;"><?= starString($rating) ?></div>
          <span style="font-size:.75rem;color:var(--ink-muted);"><?= number_format($rating, 1) ?> · <?= $projects ?> projects</span>
        </div>
        <div class="flex gap-6 flex-wrap">
          <span class="badge badge-default" style="font-size:.6rem;"><?= htmlspecialchars($statusLabel) ?></span>
          <?php if (($b['status'] ?? '') === 'interview'): ?>
          <span class="badge badge-verified" style="font-size:.6rem;">🎙 Interview</span>
          <?php endif; ?>
        </div>
        <div class="bid-item-meta mt-4">
          <?= $submittedAt ? daysAgo($submittedAt) . ' ago' : '' ?>
          <?= $submittedAt ? ' · Submitted ' . date('M j', strtotime($submittedAt)) : '' ?>
        </div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div style="padding:40px 20px;text-align:center;color:var(--ink-muted);">
        <div style="font-size:2rem;margin-bottom:12px;">📭</div>
        No proposals received yet.
      </div>
    <?php endif; ?>
  </div>

  <!-- ─── CENTRE: BID DETAIL ─── -->
  <div class="bid-detail-panel" id="bid-detail">

    <?php if (!$activeBid): ?>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:300px;color:var(--ink-muted);">
      <div style="font-size:2.5rem;margin-bottom:16px;">📋</div>
      <p>Select a proposal from the left to review it.</p>
    </div>
    <?php else: ?>

    <?php
      // Active bid values
      $ab           = $activeBid;
      $abId         = (int) $ab['id'];
      $abName       = htmlspecialchars($ab['specialist_name'] ?? 'Specialist');
      $abInitials   = strtoupper(substr($ab['specialist_name'] ?? '?', 0, 2));
      $abAmount     = (float) ($ab['total_bid_amount'] ?? 0);
      $abDuration   = (int) ($ab['total_duration'] ?? 0);
      $abDayRate    = $abDuration > 0 ? round($abAmount / $abDuration) : 0;
      $abStatus     = $ab['status'] ?? 'pending';
      $abSubmitted  = $ab['created_at'] ?? $ab['submitted_at'] ?? null;
      $abRef        = 'BID-NX-' . str_pad($abId, 4, '0', STR_PAD_LEFT);
      $abStartDate  = $ab['start_date'] ?? null;
      $abSlots      = is_array($ab['availability_slots']) ? $ab['availability_slots'] : json_decode($ab['availability_slots'] ?? '[]', true);
      $abMilestones = $ab['milestones'] ?? [];
      $abCover      = $ab['proposal_message'] ?? '';
      $abDiff       = $ab['key_differentiators'] ?? '';
      $abPast       = $ab['relevant_work'] ?? '';
      $abRating     = (float) ($ab['rating'] ?? 5);
      $abProjects   = (int) ($ab['completed_projects'] ?? 0);
      $abLocation   = htmlspecialchars($ab['location'] ?? 'Location N/A');
      $abTitle      = htmlspecialchars($ab['specialist_title'] ?? 'Specialist');
      $abMsRate     = (float) ($ab['milestone_rate'] ?? 92);
      $abDelivered  = (float) ($ab['total_delivered'] ?? 0);
      $abFreeRev    = (int) ($ab['free_reviews'] ?? 0);
      $abRevPrice   = (float) ($ab['review_price'] ?? 0);
      $abReviewNote = '';

      $statusMap    = ['pending' => 'new', 'interview' => 'interview', 'accepted' => 'accepted', 'declined' => 'declined', 'rejected' => 'declined', 'shortlisted' => 'interview'];
      $statusClass  = $statusMap[$abStatus] ?? 'new';
      $statusPill   = match($abStatus) {
        'interview', 'shortlisted' => '🎙 Interview Set',
        'accepted'                 => '✓ Accepted',
        'declined', 'rejected'     => 'Declined',
        default                    => '● New',
      };

      // Budget delta
      $budgetDelta  = $abAmount - $jobBudget;
      $deltaLabel   = $budgetDelta > 0 ? '+$' . number_format($budgetDelta) . ' over'
                    : ($budgetDelta < 0 ? '-$' . number_format(abs($budgetDelta)) . ' under'
                    : '= Client Budget');
      $deltaClass   = $budgetDelta > 0 ? 'over' : ($budgetDelta < 0 ? 'under' : 'eq');

      // Current bid index (first = 0)
      $currentIndex = 0;
    ?>

    <!-- BID HEADER STATUS BAR -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
      <div class="flex items-center gap-16">
        <span class="text-xs text-muted font-mono">Ref: <?= $abRef ?></span>
        <?php if ($abSubmitted): ?>
        <span class="text-xs text-muted font-mono">Submitted <?= date('M j', strtotime($abSubmitted)) ?></span>
        <?php endif; ?>
      </div>
      <div class="flex gap-8">
        <button class="btn btn-ghost btn-sm" onclick="prevBid()">← Prev</button>
        <span class="text-xs text-muted" style="padding:6px 0;"><?= $currentIndex + 1 ?> of <?= $bidCount ?></span>
        <button class="btn btn-ghost btn-sm" onclick="nextBid()">Next →</button>
      </div>
    </div>

    <!-- SPECIALIST HERO CARD -->
    <div class="specialist-hero">
      <div class="avatar-badge"><div class="avatar avatar-lg" id="detail-initials"><?= $abInitials ?></div></div>
      <div style="flex:1;min-width:0;">
        <div class="flex items-center gap-16 flex-wrap mb-4">
          <h3 id="bid-specialist-name" style="font-family:var(--font-display);font-size:1.3rem;font-weight:600;"><?= $abName ?></h3>
          <span id="detail-status" class="bid-status-pill <?= $statusClass ?>"><?= $statusPill ?></span>
          <?php if (!empty($ab['verified'])): ?>
          <span class="badge badge-verified badge-dot" style="font-size:.7rem;">Verified</span>
          <?php endif; ?>
        </div>
        <div style="font-size:.875rem;color:var(--ink-muted);margin-bottom:8px;" id="detail-subtitle"><?= $abTitle ?> · <?= $abLocation ?></div>
        <div class="flex gap-16 flex-wrap" style="font-size:.8125rem;">
          <div>
            <div class="stars" style="font-size:.8rem;"><?= starString($abRating) ?></div>
            <div class="text-xs text-muted mt-2"><?= number_format($abRating, 2) ?> · <?= $abProjects ?> projects</div>
          </div>
          <div>
            <div style="font-family:var(--font-mono);font-weight:600;" id="detail-ms-rate"><?= $abMsRate ?>%</div>
            <div class="text-xs text-muted mt-2">Milestone Rate</div>
          </div>
          <?php if ($abDelivered > 0): ?>
          <div>
            <div style="font-family:var(--font-mono);font-weight:600;" id="detail-delivered">$<?= $abDelivered >= 1000 ? round($abDelivered / 1000) . 'K' : number_format($abDelivered) ?></div>
            <div class="text-xs text-muted mt-2">Delivered</div>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <div style="text-align:right;flex-shrink:0;">
        <a href="/profile/<?= (int) ($ab['user_id'] ?? 0) ?>" class="btn btn-outline btn-sm" target="_blank">View Full Profile →</a>
      </div>
    </div>

    <!-- SECTION A: COVER LETTER -->
    <div class="bid-section">
      <div class="bid-section-label">A — Cover Letter</div>
      <div id="bid-cover-letter" style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:22px;font-size:.9375rem;line-height:1.75;color:var(--ink-mid);">
        <?= $abCover ? nl2br(htmlspecialchars($abCover)) : '<em style="color:var(--ink-muted)">No cover letter provided.</em>' ?>
      </div>
    </div>

    <!-- SECTION B: KEY DIFFERENTIATORS -->
    <?php if ($abDiff): ?>
    <div class="bid-section">
      <div class="bid-section-label">B — Key Differentiators</div>
      <div id="bid-differentiators" style="background:var(--gold-pale);border:1px solid var(--gold-light);border-radius:var(--radius-md);padding:18px 20px;font-size:.875rem;color:var(--ink-mid);line-height:1.7;">
        <?= nl2br(htmlspecialchars($abDiff)) ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- SECTION C: RELEVANT PAST WORK -->
    <?php if ($abPast): ?>
    <div class="bid-section">
      <div class="bid-section-label">C — Relevant Past Work</div>
      <div id="bid-past-work" style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:18px 20px;font-size:.875rem;color:var(--ink-mid);line-height:1.7;">
        <?= nl2br(htmlspecialchars($abPast)) ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- SECTION D: BUDGET PROPOSAL -->
    <div class="bid-section">
      <div class="bid-section-label">D — Budget &amp; Financials</div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px;text-align:center;">
          <div id="bid-total-amount" style="font-family:var(--font-display);font-size:1.8rem;font-weight:300;">$<?= number_format($abAmount, 0) ?></div>
          <div class="text-xs text-muted mt-4">Proposed Total</div>
          <span class="ms-delta <?= $deltaClass ?>" style="margin-top:6px;display:inline-flex;"><?= $deltaLabel ?></span>
        </div>
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px;text-align:center;">
          <div id="bid-day-rate" style="font-family:var(--font-display);font-size:1.8rem;font-weight:300;">$<?= number_format($abDayRate) ?></div>
          <div class="text-xs text-muted mt-4">Effective Day Rate</div>
        </div>
        <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px;text-align:center;">
          <div id="bid-duration" style="font-family:var(--font-display);font-size:1.8rem;font-weight:300;"><?= $abDuration ?>d</div>
          <div class="text-xs text-muted mt-4">Proposed Duration</div>
        </div>
      </div>
      <?php if (!empty($ab['bid_rationale'])): ?>
      <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;font-size:.8125rem;color:var(--ink-mid);">
        <strong>Budget Rationale:</strong> <?= nl2br(htmlspecialchars($ab['bid_rationale'])) ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- SECTION E: MILESTONE COMPARISON -->
    <?php if (!empty($abMilestones)): ?>
    <div class="bid-section">
      <div class="bid-section-label">E — Milestone Comparison</div>
      <div style="border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;">
        <div class="ms-compare-row header">
          <span>Phase</span>
          <span class="ms-cell">Specialist Bid</span>
          <span class="ms-cell">Amount</span>
          <span class="ms-cell">Duration</span>
        </div>
        <?php
        $msGrandTotal = 0;
        $msTotalDays  = 0;
        foreach ($abMilestones as $i => $ms):
          $msAmt  = (float) ($ms['amount'] ?? 0);
          $msDays = (int) ($ms['duration_days'] ?? 0);
          $msGrandTotal += $msAmt;
          $msTotalDays  += $msDays;
        ?>
        <div class="ms-compare-row">
          <span class="ms-cell name"><?= ($i + 1) ?> · <?= htmlspecialchars($ms['milestone_name'] ?? 'Milestone') ?></span>
          <span class="ms-cell" style="font-size:.78rem;color:var(--ink-muted);"><?= htmlspecialchars($ms['deliverables'] ?? '—') ?></span>
          <span class="ms-cell" style="font-weight:700;">$<?= number_format($msAmt) ?></span>
          <span class="ms-cell"><span class="ms-delta eq"><?= $msDays ?>d</span></span>
        </div>
        <?php endforeach; ?>
        <div class="ms-compare-row" style="background:var(--ivory-deep);font-weight:700;">
          <span class="ms-cell name">Total</span>
          <span class="ms-cell"></span>
          <span class="ms-cell" style="color:var(--sage);">$<?= number_format($msGrandTotal) ?></span>
          <span class="ms-cell"><span class="ms-delta eq"><?= $msTotalDays ?>d</span></span>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- SECTION G: AVAILABILITY -->
    <div class="bid-section">
      <div class="bid-section-label">G — Availability &amp; Start Date</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
          <div class="text-xs text-muted mb-4">Proposed Start Date</div>
          <div id="bid-start-date" style="font-weight:700;font-family:var(--font-mono);">
            <?= $abStartDate ? date('M j, Y', strtotime($abStartDate)) : 'TBD' ?>
          </div>
          <?php if ($abStartDate): ?>
          <div class="text-xs text-muted mt-2">
            <?= round((strtotime($abStartDate) - time()) / 86400) ?> days from today
          </div>
          <?php endif; ?>
        </div>
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
          <div class="text-xs text-muted mb-4">Current Availability</div>
          <div style="font-weight:700;color:var(--sage);">● Available Now</div>
          <div class="text-xs text-muted mt-2"><?= $abLocation ?></div>
        </div>
      </div>
      <?php if (!empty($abSlots)): ?>
      <div>
        <div class="text-xs text-muted mb-6">Available for meetings &amp; check-ins:</div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <?php foreach ($abSlots as $slot): ?>
          <span class="badge badge-verified" style="font-size:.65rem;"><?= htmlspecialchars($slot) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- SECTION H: REVIEW PRICING -->
    <?php if ($abFreeRev > 0 || $abRevPrice > 0): ?>
    <div class="bid-section">
      <div class="bid-section-label">H — Review Pricing</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <?php if ($abRevPrice > 0): ?>
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
          <div class="text-xs text-muted mb-4">Per-Review Charge</div>
          <div style="font-weight:700;font-family:var(--font-mono);font-size:1.3rem;">$<?= number_format($abRevPrice) ?></div>
          <div class="text-xs text-muted mt-2">After included reviews</div>
        </div>
        <?php endif; ?>
        <?php if ($abFreeRev > 0): ?>
        <div style="padding:14px 16px;background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-sm);">
          <div class="text-xs text-muted mb-4">Free Reviews Included</div>
          <div style="font-weight:700;font-family:var(--font-mono);font-size:1.3rem;"><?= $abFreeRev ?></div>
          <div class="text-xs text-muted mt-2">Complimentary reviews</div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- SECTION I: PROFILE SCORE -->
    <?php
    $matchScore = (int) ($ab['match_score'] ?? 0);
    if ($matchScore > 0):
    ?>
    <div class="bid-section">
      <div class="bid-section-label">I — Profile &amp; Match Score</div>
      <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:18px 20px;">
        <div class="flex items-center gap-16 mb-16">
          <div style="text-align:center;">
            <div style="font-family:var(--font-display);font-size:2.5rem;font-weight:300;line-height:1;color:var(--gold);"><?= $matchScore ?>%</div>
            <div class="text-xs text-muted mt-4">Match Score</div>
          </div>
          <div style="flex:1;">
            <?php
            $dims = [
              'Niche alignment'    => (int) ($ab['score_niche'] ?? $matchScore),
              'Credential match'   => (int) ($ab['score_credentials'] ?? min($matchScore + 3, 100)),
              'Reputation score'   => (int) ($ab['score_reputation'] ?? min(round($abRating * 20), 100)),
              'Milestone adherence'=> (int) ($ab['score_milestone'] ?? (int) $abMsRate),
              'Budget alignment'   => (int) ($ab['score_budget'] ?? ($budgetDelta === 0.0 ? 100 : max(0, 100 - (int) abs($budgetDelta / $jobBudget * 100)))),
            ];
            foreach ($dims as $label => $score): ?>
            <div class="score-dimension">
              <span class="score-dim-label"><?= htmlspecialchars($label) ?></span>
              <div class="score-dim-bar"><div class="score-dim-fill" style="width:<?= $score ?>%;"></div></div>
              <span class="score-dim-val"><?= $score ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- SECTION J: ATTACHMENTS -->
    <?php
    $attachments = $ab['attachments'] ?? [];
    if (!empty($attachments)):
    ?>
    <div class="bid-section">
      <div class="bid-section-label">J — Attachments</div>
      <?php foreach ($attachments as $att): ?>
      <?php
        $ext = strtolower(pathinfo($att['file_name'] ?? '', PATHINFO_EXTENSION));
        $icon = match($ext) { 'pdf' => '📄', 'docx', 'doc' => '📝', 'zip' => '📦', default => '📁' };
        $size = (int) ($att['file_size'] ?? 0);
        $sizeLabel = $size > 1048576 ? round($size / 1048576, 1) . ' MB' : round($size / 1024) . ' KB';
      ?>
      <div style="display:flex;align-items:center;gap:12px;padding:11px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-bottom:6px;font-size:.875rem;">
        <span style="font-size:1rem;"><?= $icon ?></span>
        <span style="flex:1;font-weight:600;"><?= htmlspecialchars($att['file_name'] ?? 'File') ?></span>
        <span style="font-family:var(--font-mono);font-size:.75rem;color:var(--ink-muted);"><?= $sizeLabel ?></span>
        <a href="/uploads/<?= htmlspecialchars($att['file_path'] ?? '#') ?>" class="btn btn-outline btn-sm" download>Download</a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php endif; // end $activeBid ?>
  </div><!-- end bid-detail-panel -->

  <!-- ─── RIGHT: ACTION PANEL ─── -->
  <div class="action-panel">

    <!-- DECISION ACTIONS -->
    <div class="action-section-title">Decision</div>

    <?php if ($canAccept && ($activeBid['status'] ?? '') !== 'accepted'): ?>
    <button class="decision-btn accept" onclick="document.getElementById('accept-modal').classList.remove('hidden')">
      ✦ Accept &amp; Issue Contract
    </button>
    <?php endif; ?>
    <button class="decision-btn interview" onclick="document.getElementById('interview-modal').classList.remove('hidden')">
      🎙 Schedule Interview
    </button>
    <button class="decision-btn message" onclick="document.getElementById('message-modal').classList.remove('hidden')">
      💬 Send Message
    </button>
    <?php if (($activeBid['status'] ?? '') !== 'declined' && ($activeBid['status'] ?? '') !== 'rejected'): ?>
    <button class="decision-btn decline" onclick="document.getElementById('decline-modal').classList.remove('hidden')">
      Decline Proposal
    </button>
    <?php endif; ?>

    <hr class="divider" style="margin:16px 0;">

    <!-- PRIVATE NOTES -->
    <div class="action-section-title">Private Notes</div>
    <p style="font-size:.75rem;color:var(--ink-muted);margin-bottom:8px;">Only visible to you. Not shared with the specialist.</p>
    <textarea class="private-notes-area" id="private-notes"
      placeholder="Add private notes about this candidate…"
      oninput="autoSaveNotes()"><?= htmlspecialchars($activeBid['client_notes'] ?? '') ?></textarea>
    <div class="flex justify-between mt-6">
      <span class="text-xs text-muted font-mono" id="notes-save-status">Auto-saved</span>
      <span class="text-xs text-muted font-mono" id="notes-charcount">0 chars</span>
    </div>

    <hr class="divider" style="margin:16px 0;">

    <!-- QUICK STATS -->
    <div class="action-section-title">Bid At a Glance</div>
    <?php if ($activeBid): ?>
    <div style="display:flex;flex-direction:column;gap:0;">
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">Proposed Budget</span>
        <span class="font-mono font-bold">$<?= number_format($abAmount) ?></span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">vs Client Budget</span>
        <span class="font-mono" style="color:<?= $budgetDelta === 0.0 ? 'var(--sage)' : 'var(--rust)' ?>;"><?= $deltaLabel ?></span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">Duration</span>
        <span class="font-mono font-bold"><?= $abDuration ?> days</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">Milestones</span>
        <span class="font-mono font-bold"><?= count($abMilestones) ?></span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:.8125rem;">
        <span class="text-muted">Attachments</span>
        <span class="font-mono font-bold"><?= count($attachments ?? []) ?> files</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:7px 0;font-size:.8125rem;">
        <span class="text-muted">Status</span>
        <span class="font-mono font-bold"><?= ucfirst($abStatus) ?></span>
      </div>
    </div>
    <?php endif; ?>

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
    <?php if ($activeBid): ?>
    <div class="modal-body">
      <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-md);padding:18px 20px;margin-bottom:20px;">
        <div style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Contract Summary</div>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:.875rem;">
          <div class="flex justify-between"><span class="text-muted">Specialist</span><span class="font-bold"><?= $abName ?></span></div>
          <div class="flex justify-between"><span class="text-muted">Total Value</span><span class="font-mono font-bold">$<?= number_format($abAmount) ?></span></div>
          <?php if (!empty($abMilestones[0])): ?>
          <div class="flex justify-between"><span class="text-muted">First Escrow (Phase 1)</span><span class="font-mono font-bold">$<?= number_format($abMilestones[0]['amount'] ?? 0) ?> — charged now</span></div>
          <?php endif; ?>
          <div class="flex justify-between"><span class="text-muted">Duration</span><span class="font-mono"><?= $abDuration ?> days</span></div>
          <div class="flex justify-between"><span class="text-muted">NDA</span><span>Standard Nexus · 2yr · $10K damages</span></div>
          <?php if ($abFreeRev > 0): ?>
          <div class="flex justify-between"><span class="text-muted">Free Revisions</span><span><?= $abFreeRev ?> per milestone</span></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Contract Start Date</label>
        <input type="date" class="form-control" id="contract-start" value="<?= $abStartDate ? htmlspecialchars($abStartDate) : '' ?>">
        <p class="form-hint mt-4">Specialist proposed: <?= $abStartDate ? date('M j, Y', strtotime($abStartDate)) : 'TBD' ?>. Leave blank to use today's date.</p>
      </div>
      <div class="form-group">
        <label class="form-label">Message to Specialist <span class="text-muted font-mono" style="font-size:.7rem;font-weight:400;text-transform:none;letter-spacing:0;">Optional — sent with the contract</span></label>
        <textarea class="form-control" rows="3" placeholder="e.g. We're pleased to accept your proposal. Looking forward to working with you…"></textarea>
      </div>
      <?php if (!empty($abMilestones[0])): ?>
      <div class="verify-band">
        <span>💳</span>
        <div style="font-size:.8125rem;"><strong>$<?= number_format($abMilestones[0]['amount'] ?? 0) ?></strong> will be immediately locked from your payment method. The specialist will be notified and an NDA sent for signature before project access is granted.</div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('accept-modal').classList.add('hidden')">Cancel</button>
      <form method="post" action="/bid-review" style="margin:0;">
        <input type="hidden" name="action" value="accept">
        <input type="hidden" name="job_id" value="<?= (int) ($job['id'] ?? 0) ?>">
        <input type="hidden" name="bid_id" value="<?= $activeBidId ?>">
        <button class="btn btn-primary" type="submit">Accept &amp; Issue Contract</button>
      </form>
    </div>
  </div>
</div>

<!-- SCHEDULE INTERVIEW MODAL -->
<div id="interview-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Schedule Technical Interview</h3>
        <p class="text-sm text-muted mt-4">Select a slot from <?= $abName ?>'s stated availability. A calendar invite will be sent to both parties.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('interview-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <?php if (!empty($abSlots)): ?>
      <div style="margin-bottom:20px;">
        <label class="form-label">Specialist's Available Slots</label>
        <p class="form-hint mb-10"><?= $abName ?> marked the following as available for meetings.</p>
        <div class="slot-grid" id="slot-grid">
          <?php foreach ($abSlots as $slot): ?>
          <button type="button" class="slot-btn" onclick="selectSlot(this)"><?= htmlspecialchars($slot) ?></button>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
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
        <textarea class="form-control" rows="4" placeholder="e.g. 1. Verify scope depth&#10;2. Discuss Phase 1 methodology&#10;3. Review availability"></textarea>
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
        <h3>Message <?= $abName ?></h3>
        <p class="text-sm text-muted mt-4">Pre-contract messages are recorded and encrypted. The specialist will be notified immediately.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('message-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
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
        <textarea class="form-control" rows="6" id="message-body" placeholder="Write your message…"></textarea>
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
        <textarea class="form-control" rows="3" placeholder="Keep it professional. Providing feedback helps specialists improve."></textarea>
      </div>
      <div style="display:flex;align-items:center;gap:8px;font-size:.875rem;">
        <input type="checkbox" id="decline-keep-open" checked style="accent-color:var(--gold);">
        <label for="decline-keep-open">Keep this specialist in mind for future projects</label>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('decline-modal').classList.add('hidden')">Cancel</button>
      <form method="post" action="/bid-review" style="margin:0;">
        <input type="hidden" name="action" value="decline">
        <input type="hidden" name="job_id" value="<?= (int) ($job['id'] ?? 0) ?>">
        <input type="hidden" name="bid_id" value="<?= $activeBidId ?>">
        <button class="btn btn-danger" type="submit">Decline Proposal</button>
      </form>
    </div>
  </div>
</div>

<!-- CONTRACT SUCCESS MODAL -->
<div id="contract-success-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm" style="text-align:center;">
    <div class="modal-body" style="padding:48px 32px;">
      <div style="font-size:3rem;margin-bottom:20px;">✦</div>
      <h3 style="margin-bottom:10px;">Contract Issued</h3>
      <p class="text-sm text-muted mb-6">A contract and NDA have been sent to <strong><?= $abName ?></strong>. Work begins once the NDA is signed and Phase 1 escrow is confirmed.</p>
      <div style="display:flex;flex-direction:column;gap:10px;">
        <a href="/dashboard" class="btn btn-primary" style="justify-content:center;">Go to Dashboard</a>
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
      <div class="modal-footer" style="padding:0;margin-top:20px;">
        <button class="btn btn-outline" onclick="document.getElementById('filters-modal').classList.add('hidden')">Cancel</button>
        <button class="btn btn-primary" onclick="document.getElementById('filters-modal').classList.add('hidden')">Apply Filters</button>
      </div>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast-stack" id="toast-stack"></div>

<script>
// PHP bids data passed to JS for client-side switching
const bidsData = <?= json_encode(array_values($bids), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) ?>;
const jobBudget = <?= json_encode($jobBudget) ?>;

/* ── Utilities ── */
function escapeHtml(v) {
  const d = document.createElement('div');
  d.textContent = v || '';
  return d.innerHTML;
}
function textWithBreaks(v) {
  return escapeHtml(v).replace(/\n/g, '<br>');
}
function fmtMoney(n) {
  return '$' + Number(n || 0).toLocaleString();
}
function starString(rating, max = 5) {
  const full = Math.min(Math.round(rating), max);
  return '★'.repeat(full) + '☆'.repeat(max - full);
}

/* ── Dropdown ── */
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
  if (!bid) return;

  // Update hidden form inputs
  document.querySelectorAll('input[name="bid_id"]').forEach(i => i.value = bid.id || '');

  // Specialist name & initials
  const name = bid.specialist_name || 'Specialist';
  const initials = name.substring(0, 2).toUpperCase();
  const nameEl = document.getElementById('bid-specialist-name');
  if (nameEl) nameEl.textContent = name;
  const initialsEl = document.getElementById('detail-initials');
  if (initialsEl) initialsEl.textContent = initials;

  // Subtitle
  const subtitleEl = document.getElementById('detail-subtitle');
  if (subtitleEl) subtitleEl.textContent = (bid.specialist_title || 'Specialist') + ' · ' + (bid.location || '');

  // Status pill
  const statusMap = { pending: 'new', interview: 'interview', shortlisted: 'interview', accepted: 'accepted', declined: 'declined', rejected: 'declined' };
  const statusPillMap = { interview: '🎙 Interview Set', shortlisted: '🎙 Interview Set', accepted: '✓ Accepted', declined: 'Declined', rejected: 'Declined' };
  const sc = statusMap[bid.status] || 'new';
  const sp = statusPillMap[bid.status] || '● New';
  const statusEl = document.getElementById('detail-status');
  if (statusEl) { statusEl.className = 'bid-status-pill ' + sc; statusEl.textContent = sp; }

  // Budget
  const amt = parseFloat(bid.total_bid_amount) || 0;
  const amtEl = document.getElementById('bid-total-amount');
  if (amtEl) amtEl.textContent = fmtMoney(amt);

  // Day rate & duration
  const dur = parseInt(bid.total_duration) || 0;
  const dayRate = dur > 0 ? Math.round(amt / dur) : 0;
  const drEl = document.getElementById('bid-day-rate');
  if (drEl) drEl.textContent = fmtMoney(dayRate);
  const durEl = document.getElementById('bid-duration');
  if (durEl) durEl.textContent = dur + 'd';

  // Cover letter
  const clEl = document.getElementById('bid-cover-letter');
  if (clEl) clEl.innerHTML = bid.proposal_message ? textWithBreaks(bid.proposal_message) : '<em style="color:var(--ink-muted)">No cover letter provided.</em>';

  // Differentiators
  const diffEl = document.getElementById('bid-differentiators');
  if (diffEl) diffEl.innerHTML = bid.key_differentiators ? textWithBreaks(bid.key_differentiators) : '';

  // Past work
  const pwEl = document.getElementById('bid-past-work');
  if (pwEl) pwEl.innerHTML = bid.relevant_work ? textWithBreaks(bid.relevant_work) : '';

  // Start date
  const sdEl = document.getElementById('bid-start-date');
  if (sdEl && bid.start_date) {
    sdEl.textContent = new Date(bid.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
  } else if (sdEl) {
    sdEl.textContent = 'TBD';
  }

  // Notes
  const notesEl = document.getElementById('private-notes');
  if (notesEl) { notesEl.value = bid.client_notes || ''; autoSaveNotes(); }
}

/* ── SORT ── */
function setSort(el, key) {
  document.querySelectorAll('.bid-sort-bar .sort-chip').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  // Server-side: reload with ?sort={key}&job_id=...
}

/* ── NAV ── */
function prevBid() { showToast('Previous proposal loaded.', 'info'); }
function nextBid() { showToast('Next proposal loaded.', 'info'); }

/* ── INTERVIEW ── */
function selectSlot(el) {
  document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
  el.classList.add('selected');
}
function scheduleInterview() {
  const selected = document.querySelector('.slot-btn.selected');
  if (!selected) { showToast('Please select a time slot.', 'warn'); return; }
  document.getElementById('interview-modal').classList.add('hidden');
  showToast('Interview invitation sent. Calendar invite generated.');
}

/* ── MESSAGE ── */
const clientName = <?= json_encode(htmlspecialchars($_SESSION['user_name'] ?? 'The Client')) ?>;
const specialistFirstName = <?= json_encode(explode(' ', $abName ?? 'Specialist')[0]) ?>;
const templates = {
  'request-info': `Dear ${specialistFirstName},\n\nThank you for your proposal. Before we proceed, could you please provide more detail on [specific area]?\n\nKind regards,\n${clientName}`,
  'clarify-scope': `Dear ${specialistFirstName},\n\nWe would like to clarify one aspect of the project scope before making a decision. Could you confirm your approach to [specific area]?\n\nKind regards,\n${clientName}`,
  'nda-prompt': `Dear ${specialistFirstName},\n\nWe are progressing your proposal to the next review stage. Please check your notifications — an NDA has been generated and is awaiting your digital signature.\n\nKind regards,\n${clientName}`,
  'budget-discuss': `Dear ${specialistFirstName},\n\nWe are interested in your proposal but would like to discuss the budget before confirming. Would you be open to a short call?\n\nKind regards,\n${clientName}`,
};
function applyTemplate(key) {
  const el = document.getElementById('message-body');
  if (el && templates[key]) el.value = templates[key];
}
function sendMessage() {
  const msg = document.getElementById('message-body')?.value?.trim();
  if (!msg || msg.length < 10) { showToast('Please write a message before sending.', 'warn'); return; }
  document.getElementById('message-modal').classList.add('hidden');
  showToast('Message sent to specialist.');
}

/* ── NOTES AUTO-SAVE ── */
let notesSaveTimer;
function autoSaveNotes() {
  const el = document.getElementById('notes-save-status');
  const cc = document.getElementById('notes-charcount');
  const val = document.getElementById('private-notes')?.value || '';
  if (el) el.textContent = 'Unsaved…';
  if (cc) cc.textContent = val.length + ' chars';
  clearTimeout(notesSaveTimer);
  notesSaveTimer = setTimeout(() => {
    // AJAX POST /projects/{job_id}/proposals/{bid_id}/notes
    if (el) el.textContent = 'Auto-saved';
  }, 1200);
}

/* ── TOAST ── */
function showToast(msg, type = 'success') {
  const s = document.getElementById('toast-stack');
  const icons = { success: '✓', warn: '⚠', info: 'ℹ' };
  const cls = { success: 'success', warn: 'warning', info: '' };
  s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type === 'warn' ? 'Required' : type === 'info' ? 'Notice' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML = '', 4500);
}

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', () => {
  // Init notes char count for pre-filled notes
  const n = document.getElementById('private-notes');
  const cc = document.getElementById('notes-charcount');
  if (n && cc) cc.textContent = n.value.length + ' chars';
});
</script>
</body>
</html>