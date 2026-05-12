<!DOCTYPE html>

<?php

$jobId       = (int) ($job['id'] ?? 0);
$jobTitle    = htmlspecialchars($job['title'] ?? $job['project_title'] ?? 'Untitled Project');
$jobRef      = htmlspecialchars($job['ref'] ?? ('NX-' . date('Y') . '-' . str_pad($jobId, 4, '0', STR_PAD_LEFT)));
$jobBrief    = $job['brief'] ?? $job['project_brief'] ?? '';
$jobReqs     = $job['full_requirements'] ?? $job['project_full_requirements'] ?? '';
$jobIdeal    = $job['ideal_candidate'] ?? '';
$jobBudget   = (float) ($job['total_budget'] ?? 0);
$jobDuration = (int) ($job['total_duration_days'] ?? array_sum(array_column($milestones, 'duration_days')));
$jobNiche    = htmlspecialchars($job['niche'] ?? 'Consulting');
$jobEngType  = htmlspecialchars($job['engagement_type'] ?? '');
$jobPostedAt = $job['posted_at'] ?? $job['created_at'] ?? null;
$firstEscrow = (float) ($job['first_escrow'] ?? ($milestones[0]['amount'] ?? 0));
$isPublic    = ($job['visibility'] ?? 'public') === 'public';
$ndaRequired = $ndaRequired ?? !empty($job['nda_required']);
$bidCount    = (int) ($bidCount ?? $job['bid_count'] ?? 0);
$canBid      = $canBid ?? true;
$blockReason = $blockReason ?? null;
$myBid       = $myBid ?? null;


$clientOrg       = htmlspecialchars($client['org_name'] ?? $client['user_name'] ?? 'Client');
$clientInitials  = strtoupper(substr(str_replace(' ', '', $clientOrg), 0, 2));
$clientIndustry  = htmlspecialchars($client['industry'] ?? 'Corporate');
$clientCity      = htmlspecialchars($client['city'] ?? $client['location'] ?? '');
$clientVerified  = !empty($client['verified']);
$clientSlug      = $client['slug'] ?? 'profile/' . ($client['id'] ?? '');


$nicheIcons = [
    'Legal Consulting'      => '⚖️',
    'Data Science'          => '🧠',
    'Data Science & ML'     => '🧠',
    'Technical Translation' => '🌐',
    'Financial Modelling'   => '📈',
    'Biomedical Research'   => '🔬',
    'Cybersecurity Audit'   => '🔐',
    'Default'               => '📋',
];
$nicheIcon = $nicheIcons[$job['niche'] ?? ''] ?? $nicheIcons['Default'];

$nicheFields = [];
if (!empty($job['niche_answers_json'])) {
    $nicheFields = json_decode($job['niche_answers_json'], true) ?: [];
}

foreach (['jurisdictions', 'governing_law', 'bar_admissions', 'document_languages',
          'industry_context', 'engagement_type'] as $col) {
    if (!empty($job[$col]) && !isset($nicheFields[$col])) {
        $nicheFields[$col] = $job[$col];
    }
}

$ndaType     = htmlspecialchars($job['nda_type'] ?? 'standard');
$ndaDuration = htmlspecialchars($job['nda_duration'] ?? '2 years');
$ndaDamages  = $job['nda_damages'] ?? '10000';
$ndaDmgLabel = $ndaDamages === 'none' ? 'None' : '$' . number_format((float) $ndaDamages) . ' per breach';
$ndaGovLaw   = htmlspecialchars($job['nda_governing_law'] ?? 'Egyptian Civil Law');


$freeRevisions = (int) ($job['free_revisions_per_milestone'] ?? $job['free_revisions'] ?? 2);


$clientStats = [
    'completed_projects' => (int) ($client['completed_projects'] ?? 0),
    'dispute_rate'       => htmlspecialchars($client['dispute_rate'] ?? '—'),
    'payment_reliability'=> htmlspecialchars($client['payment_reliability'] ?? '—'),
    'repeat_hire_rate'   => htmlspecialchars($client['repeat_hire_rate'] ?? '—'),
    'avg_approval_h'     => htmlspecialchars($client['avg_approval_hours'] ?? '72'),
];
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $jobTitle ?> — Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/job-view.css">
</head>
<body>

<?php require __DIR__ . '/../partials/topnav.php'; ?>

<div class="job-hero">
  <div class="container">
    <div class="job-hero-inner">


      <div class="job-niche-icon"><?= $nicheIcon ?></div>

      <div style="flex:1;">


        <div style="font-size:.75rem;font-family:var(--font-mono);color:var(--ink-muted);margin-bottom:8px;">
          <?= $jobNiche ?><?= $jobEngType ? ' &nbsp;·&nbsp; ' . $jobEngType : '' ?>
        </div>


        <h1 style="font-family:var(--font-display);font-size:1.8rem;font-weight:500;margin-bottom:10px;line-height:1.2;">
          <?= $jobTitle ?>
        </h1>


        <div class="job-meta-row">
          <?php if (!empty($nicheFields['jurisdictions'])): ?>
          <div class="job-meta-item">
            <span>📍</span>
            <?= htmlspecialchars(is_array($nicheFields['jurisdictions'])
                ? implode(' · ', $nicheFields['jurisdictions'])
                : $nicheFields['jurisdictions']) ?>
          </div>
          <?php endif; ?>
          <?php if (!empty($nicheFields['governing_law'])): ?>
          <div class="job-meta-item">
            <span>⚖️</span>
            <?= htmlspecialchars(is_array($nicheFields['governing_law'])
                ? implode(', ', $nicheFields['governing_law'])
                : $nicheFields['governing_law']) ?>
          </div>
          <?php endif; ?>
          <?php if ($clientCity): ?>
          <div class="job-meta-item"><span>🌍</span><?= $clientCity ?></div>
          <?php endif; ?>
        </div>

        <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
          <?php if ($isPublic): ?>
          <span class="badge badge-default">🌐 Public Listing</span>
          <?php else: ?>
          <span class="badge badge-pending">🔒 Invitation-Only</span>
          <?php endif; ?>
          <?php if ($ndaRequired): ?>
          <span class="badge badge-pending">🔏 NDA Required on Shortlist</span>
          <?php endif; ?>
          <?php if (!empty($job['interview_required'])): ?>
          <span class="badge badge-default">🎙 Technical Interview</span>
          <?php endif; ?>
          <span class="badge badge-default font-mono" style="font-size:.625rem;">Ref: <?= $jobRef ?></span>
          <?php if ($myBid): ?>
          <span class="badge badge-verified badge-dot" style="font-size:.7rem;">✓ Proposal Submitted</span>
          <?php endif; ?>
        </div>

      </div>

      <div style="flex-shrink:0;text-align:right;min-width:160px;">
        <div style="font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);font-weight:700;margin-bottom:8px;font-family:var(--font-body);">Posted by</div>
        <a href="/<?= htmlspecialchars($clientSlug) ?>" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;color:var(--ink);">
          <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:var(--ink);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:.875rem;font-weight:600;color:var(--gold);">
            <?= $clientInitials ?>
          </div>
          <div style="text-align:left;">
            <div style="font-weight:700;font-size:.875rem;"><?= $clientOrg ?></div>
            <div class="text-xs text-muted"><?= $clientIndustry ?><?= $clientCity ? ' · ' . $clientCity : '' ?></div>
          </div>
        </a>
        <?php if ($clientVerified): ?>
        <div style="margin-top:10px;">
          <span class="badge badge-verified badge-dot" style="font-size:.625rem;">Verified</span>
        </div>
        <?php endif; ?>
        <?php if ($jobPostedAt): ?>
        <div class="text-xs text-muted font-mono mt-8">Posted <?= date('M j, Y', strtotime($jobPostedAt)) ?></div>
        <?php endif; ?>
      </div>

    </div>


    <div class="job-stats-bar">
      <div class="job-stat">
        <div class="val">$<?= number_format($jobBudget) ?></div>
        <div class="lbl">Total Budget</div>
      </div>
      <div class="job-stat">
        <div class="val"><?= count($milestones) ?></div>
        <div class="lbl">Milestone<?= count($milestones) !== 1 ? 's' : '' ?></div>
      </div>
      <div class="job-stat">
        <div class="val"><?= $jobDuration ?>d</div>
        <div class="lbl">Duration</div>
      </div>
      <div class="job-stat">
        <div class="val">$<?= number_format($firstEscrow) ?></div>
        <div class="lbl">First Escrow</div>
      </div>
      <div class="job-stat">
        <div class="val"><?= $bidCount ?></div>
        <div class="lbl">Proposal<?= $bidCount !== 1 ? 's' : '' ?> So Far</div>
      </div>
    </div>

  </div>
</div>

<div class="container">
  <div class="job-body">


    <div>


      <div class="tabs mt-24 mb-28">
        <button class="tab-item active" onclick="switchTab(0)">Project Brief</button>
        <button class="tab-item" onclick="switchTab(1)">Milestones</button>
        <button class="tab-item" onclick="switchTab(2)">NDA &amp; Privacy</button>
        <button class="tab-item" onclick="switchTab(3)">Client Profile</button>
      </div>


      <div id="tab-0">

        <?php if ($jobBrief): ?>
        <div class="job-section">
          <div class="job-section-title">Project Brief</div>
          <div style="font-size:.9375rem;line-height:1.75;color:var(--ink-mid);">
            <?= nl2br(htmlspecialchars($jobBrief)) ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($nicheFields)): ?>
        <div class="job-section">
          <div class="job-section-title">Niche-Specific Details</div>
          <?php

          $fieldLabels = [
              'engagement_type'   => 'Engagement Type',
              'jurisdictions'     => 'Jurisdictions',
              'governing_law'     => 'Governing Law',
              'bar_admissions'    => 'Required Bar Admissions',
              'document_languages'=> 'Document Languages',
              'industry_context'  => 'Industry Context',
              'dataset_size'      => 'Dataset Size',
              'ml_framework'      => 'ML Framework',
              'source_language'   => 'Source Language',
              'target_language'   => 'Target Language',
              'subject_domain'    => 'Subject Domain',
              'model_type'        => 'Model Type',
              'audit_scope'       => 'Audit Scope',
              'compliance_standard'=>'Compliance Standard',
          ];
          foreach ($nicheFields as $key => $val):
              if (!$val || $key === 'niche') continue;
              $label = $fieldLabels[$key] ?? ucwords(str_replace('_', ' ', $key));
              $display = is_array($val) ? implode(' · ', $val) : $val;
          ?>
          <div class="niche-field-row">
            <div class="niche-field-label"><?= htmlspecialchars($label) ?></div>
            <div class="niche-field-value"><?= htmlspecialchars($display) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($jobReqs): ?>
        <div class="job-section">
          <div class="job-section-title">Full Project Requirements</div>
          <div style="font-size:.9375rem;line-height:1.75;color:var(--ink-mid);">
            <?= nl2br(htmlspecialchars($jobReqs)) ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($jobIdeal): ?>
        <div class="job-section">
          <div class="job-section-title">Ideal Specialist Profile</div>
          <div style="font-size:.9375rem;line-height:1.75;color:var(--ink-mid);">
            <?= nl2br(htmlspecialchars($jobIdeal)) ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="job-section">
          <div class="job-section-title">Timeline &amp; Delivery</div>
          <div style="display:flex;gap:24px;flex-wrap:wrap;">
            <div>
              <div class="text-xs text-muted mb-4">Estimated Duration</div>
              <div style="font-weight:700;"><?= $jobDuration ?> days (<?= count($milestones) ?> phase<?= count($milestones) !== 1 ? 's' : '' ?>)</div>
            </div>
            <?php if (!empty($job['expected_start'])): ?>
            <div>
              <div class="text-xs text-muted mb-4">Expected Start</div>
              <div style="font-weight:700;"><?= htmlspecialchars($job['expected_start']) ?></div>
            </div>
            <?php endif; ?>
            <div>
              <div class="text-xs text-muted mb-4">Free Revisions / Phase</div>
              <div style="font-weight:700;"><?= $freeRevisions ?> revision<?= $freeRevisions !== 1 ? 's' : '' ?> included</div>
            </div>
            <div>
              <div class="text-xs text-muted mb-4">Proposal Visibility</div>
              <div style="font-weight:700;"><?= $isPublic ? 'Public' : 'Invitation-Only' ?></div>
            </div>
          </div>
        </div>

      </div>
      <div id="tab-1" class="hidden">

        <p class="text-sm text-muted mb-20">Funds are locked in escrow per milestone. You begin each phase only after the client confirms the previous phase escrow and you both sign off. Payments release on bilateral milestone approval.</p>

        <?php
        $msTotal     = 0;
        $msTotalDays = 0;
        foreach ($milestones as $i => $m):
          $msAmt  = (float) ($m['amount'] ?? 0);
          $msDays = (int) ($m['duration_days'] ?? $m['duration'] ?? 0);
          $msName = $m['name'] ?? $m['milestone_name'] ?? 'Milestone ' . ($i + 1);
          $msDels = $m['deliverables'] ?? '';
          $msTotal     += $msAmt;
          $msTotalDays += $msDays;
        ?>
        <div class="milestone-display-item">
          <div class="milestone-display-num"><?= $i + 1 ?></div>
          <div class="milestone-display-body">
            <div class="milestone-display-name"><?= htmlspecialchars($msName) ?></div>
            <div class="milestone-display-meta">
              <span>⏱ <?= $msDays ?> day<?= $msDays !== 1 ? 's' : '' ?></span>
              <?php if ($msDels): ?>
              <span style="color:var(--ink-muted);font-size:.8rem;">· <?= htmlspecialchars($msDels) ?></span>
              <?php endif; ?>
            </div>
          </div>
          <div class="milestone-display-amount">
            <div style="font-family:var(--font-mono);font-weight:600;font-size:1rem;">$<?= number_format($msAmt) ?></div>
            <div class="text-xs text-muted">on approval</div>
          </div>
        </div>
        <?php endforeach; ?>


        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);margin-top:16px;">
          <span style="font-weight:700;">Total Project Value</span>
          <span style="font-family:var(--font-mono);font-size:1.2rem;font-weight:600;">$<?= number_format($msTotal) ?></span>
        </div>

        <div class="verify-band mt-16">
          <span>🔒</span>
          <div style="font-size:.8125rem;">
            <strong>First escrow ($<?= number_format($firstEscrow) ?>) is locked at contract signing.</strong>
            You will not begin Phase 1 work until this is confirmed.
            Each subsequent milestone's escrow is locked before you start that phase.
            Auto-approval triggers after 72h if the client does not review.
          </div>
        </div>

        <hr class="divider">
        <h4 class="mb-8" style="font-size:.9rem;">Revision Policy</h4>
        <p class="text-sm text-muted">
          <?= $freeRevisions ?> free revision<?= $freeRevisions !== 1 ? 's are' : ' is' ?> included per milestone.
          Additional revisions are billed at a separately agreed rate, logged and tracked by the platform.
          Revision requests must be submitted within the client's inspection window.
        </p>

      </div>
      <div id="tab-2" class="hidden">

        <div class="job-section">
          <div class="job-section-title">NDA Terms</div>
          <?php if ($ndaRequired): ?>
          <div class="verify-band mb-16">
            <span>🔏</span>
            <div style="font-size:.8125rem;">
              <strong>NDA is required for this engagement.</strong>
              It will be auto-generated and sent to you via the platform if the client shortlists your proposal.
              You must sign before accessing the full project brief and any attached materials.
            </div>
          </div>
          <?php if ($ndaType === 'custom'): ?>
          <div class="niche-field-row"><div class="niche-field-label">NDA Type</div><div class="niche-field-value">Custom NDA (provided by client)</div></div>
          <?php else: ?>
          <div class="niche-field-row"><div class="niche-field-label">NDA Type</div><div class="niche-field-value">Standard Nexus NDA</div></div>
          <div class="niche-field-row"><div class="niche-field-label">Duration</div><div class="niche-field-value"><?= $ndaDuration ?> from engagement end</div></div>
          <div class="niche-field-row"><div class="niche-field-label">Liquidated Damages</div><div class="niche-field-value"><?= $ndaDmgLabel ?></div></div>
          <div class="niche-field-row"><div class="niche-field-label">Governing Law (NDA)</div><div class="niche-field-value"><?= $ndaGovLaw ?></div></div>
          <div class="niche-field-row"><div class="niche-field-label">Applies To</div><div class="niche-field-value">All project materials, communications, client identity, and deliverables</div></div>
          <?php endif; ?>
          <?php else: ?>
          <p class="text-sm text-muted">No NDA is required for this engagement.</p>
          <?php endif; ?>
        </div>

        <div class="job-section">
          <div class="job-section-title">Your Obligations</div>
          <p class="text-sm text-muted">
            By submitting a proposal you acknowledge that you have read and agree to the project scope
            <?= $ndaRequired ? 'and the NDA terms outlined above. Accepting a contract on this project constitutes a binding signature of the auto-generated NDA.' : 'and Nexus platform terms.' ?>
          </p>
        </div>

      </div>

      <div id="tab-3" class="hidden">

        <div style="display:flex;gap:20px;align-items:flex-start;margin-bottom:24px;">
          <div style="width:72px;height:72px;border-radius:var(--radius-md);background:var(--ink);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.5rem;font-weight:600;color:var(--gold);flex-shrink:0;">
            <?= $clientInitials ?>
          </div>
          <div>
            <h3 style="font-size:1.2rem;margin-bottom:4px;"><?= $clientOrg ?></h3>
            <div class="text-sm text-muted mb-8"><?= $clientIndustry ?><?= $clientCity ? ' · ' . $clientCity : '' ?></div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
              <?php if ($clientVerified): ?>
              <span class="badge badge-verified badge-dot" style="font-size:.625rem;">Verified</span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <?php if (!empty($client['bio'])): ?>
        <p class="mb-16" style="font-size:.9rem;"><?= nl2br(htmlspecialchars($client['bio'])) ?></p>
        <?php endif; ?>

        <div class="client-mini mb-20">
          <div class="client-mini-stat">
            <span class="text-muted">Projects Completed</span>
            <span class="font-mono font-bold"><?= $clientStats['completed_projects'] ?></span>
          </div>
          <div class="client-mini-stat">
            <span class="text-muted">Dispute Rate</span>
            <span class="font-mono font-bold" style="color:var(--sage);"><?= $clientStats['dispute_rate'] ?></span>
          </div>
          <div class="client-mini-stat">
            <span class="text-muted">Payment Reliability</span>
            <span class="font-mono font-bold" style="color:var(--sage);"><?= $clientStats['payment_reliability'] ?></span>
          </div>
          <div class="client-mini-stat">
            <span class="text-muted">Repeat Hire Rate</span>
            <span class="font-mono font-bold"><?= $clientStats['repeat_hire_rate'] ?></span>
          </div>
          <div class="client-mini-stat">
            <span class="text-muted">Auto-Approval Window</span>
            <span class="font-mono"><?= $clientStats['avg_approval_h'] ?>h</span>
          </div>
        </div>

        <a href="/<?= htmlspecialchars($clientSlug) ?>" class="btn btn-outline btn-sm">View Full Client Profile →</a>

      </div>

    </div>


    <div>

      <?php if ($myBid): ?>
      <!-- EXISTING BID PANEL -->
      <div style="background:var(--ivory-card);border:1.5px solid var(--gold);border-radius:var(--radius-md);padding:24px;margin-bottom:20px;">
        <div style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--gold);margin-bottom:12px;">Your Active Proposal</div>
        <div style="display:flex;flex-direction:column;gap:8px;font-size:.875rem;margin-bottom:16px;">
          <div style="display:flex;justify-content:space-between;">
            <span class="text-muted">Bid Amount</span>
            <span class="font-mono font-bold">$<?= number_format((float) $myBid['total_bid_amount']) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span class="text-muted">Status</span>
            <span class="font-bold"><?= ucfirst($myBid['status'] ?? 'pending') ?></span>
          </div>
          <?php if (!empty($myBid['submitted_at'])): ?>
          <div style="display:flex;justify-content:space-between;">
            <span class="text-muted">Submitted</span>
            <span class="font-mono"><?= date('M j, Y', strtotime($myBid['submitted_at'])) ?></span>
          </div>
          <?php endif; ?>
        </div>
        <a href="/jobs/<?= $jobId ?>/bid/<?= (int) $myBid['id'] ?>" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;">Edit / View Proposal →</a>
      </div>
      <?php elseif ($canBid): ?>
      <!-- PLACE BID PANEL -->
      <div style="background:var(--ivory-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;margin-bottom:20px;">
        <div style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:16px;">Submit Your Proposal</div>
        <p style="font-size:.8125rem;color:var(--ink-muted);margin-bottom:16px;">
          <?= $bidCount ?> proposal<?= $bidCount !== 1 ? 's' : '' ?> submitted so far.
          <?php if ($ndaRequired): ?>
          NDA auto-generated on shortlisting.
          <?php endif; ?>
        </p>
        <a href="/jobs/<?= $jobId ?>/bid" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:10px;">
          ✦ Submit a Proposal
        </a>
        <button class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;" onclick="copyLink()">
          🔗 <span id="copied-msg">Share This Job</span>
        </button>
      </div>
      <?php else: ?>
      <!-- BLOCKED PANEL -->
      <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;margin-bottom:20px;text-align:center;">
        <div style="font-size:1.5rem;margin-bottom:10px;">🔒</div>
        <div style="font-weight:700;margin-bottom:6px;font-size:.9375rem;">Cannot Submit Proposal</div>
        <?php if ($blockReason): ?>
        <p class="text-sm text-muted"><?= htmlspecialchars($blockReason) ?></p>
        <?php endif; ?>
      </div>
      <?php endif; ?>


      <div class="client-mini mt-16">
        <div style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;font-family:var(--font-body);">Client Quick Stats</div>
        <div class="client-mini-stat">
          <span class="text-muted">Completed Projects</span>
          <span class="font-mono font-bold"><?= $clientStats['completed_projects'] ?></span>
        </div>
        <div class="client-mini-stat">
          <span class="text-muted">Payment Reliability</span>
          <span class="font-mono font-bold" style="color:var(--sage);"><?= $clientStats['payment_reliability'] ?></span>
        </div>
        <div class="client-mini-stat">
          <span class="text-muted">Avg. Approval Time</span>
          <span class="font-mono font-bold" style="color:var(--sage);"><?= $clientStats['avg_approval_h'] ?>h</span>
        </div>
        <div class="client-mini-stat">
          <span class="text-muted">Dispute Rate</span>
          <span class="font-mono"><?= $clientStats['dispute_rate'] ?></span>
        </div>
        <a href="/<?= htmlspecialchars($clientSlug) ?>" style="display:block;text-align:center;margin-top:12px;font-size:.8125rem;color:var(--gold);">View full client profile →</a>
      </div>

      <?php if (!empty($similarJobs)): ?>
      <!-- SIMILAR JOBS -->
      <div style="margin-top:24px;">
        <div style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Similar Projects</div>
        <?php foreach ($similarJobs as $sj): ?>
        <a href="/jobs/<?= (int) $sj['id'] ?>" style="display:block;padding:12px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-card);margin-bottom:8px;text-decoration:none;color:var(--ink);">
          <div style="font-weight:600;font-size:.875rem;margin-bottom:4px;"><?= htmlspecialchars($sj['title'] ?? '') ?></div>
          <div style="display:flex;gap:10px;font-size:.75rem;color:var(--ink-muted);">
            <span class="font-mono">$<?= number_format((float) ($sj['total_budget'] ?? 0)) ?></span>
            <span>·</span>
            <span><?= htmlspecialchars($sj['niche'] ?? '') ?></span>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    </div>

  </div>
</div>


<?php if (!empty($bidJustSubmitted)): ?>
<div id="success-modal" class="modal-backdrop">
<?php else: ?>
<div id="success-modal" class="modal-backdrop hidden">
<?php endif; ?>
  <div class="modal modal-sm" style="text-align:center;">
    <div class="modal-body" style="padding:48px 32px;">
      <div style="font-size:3rem;margin-bottom:20px;">✦</div>
      <h3 style="margin-bottom:10px;">Proposal Submitted</h3>
      <p class="text-sm text-muted mb-8">Your proposal for <strong><?= $jobTitle ?></strong> has been sent to <?= $clientOrg ?>.</p>
      <p class="text-sm text-muted mb-24">You'll be notified when they review it.
        <?php if ($ndaRequired): ?>
        If shortlisted, an NDA will be sent for your signature before the full brief is shared.
        <?php endif; ?>
      </p>
      <?php if ($myBid && !empty($myBid['id'])): ?>
      <span class="font-mono text-xs text-muted" id="bid-ref">
        Proposal Ref: BID-NX-<?= str_pad((int) $myBid['id'], 4, '0', STR_PAD_LEFT) ?>
      </span>
      <?php endif; ?>
      <div style="display:flex;flex-direction:column;gap:10px;margin-top:24px;">
        <a href="/dashboard" class="btn btn-primary" style="justify-content:center;">Back to Dashboard</a>
        <button class="btn btn-outline" style="justify-content:center;"
          onclick="document.getElementById('success-modal').classList.add('hidden')">View Proposal Details</button>
      </div>
    </div>
  </div>
</div>

<div class="toast-stack" id="toast-stack"></div>

<script>

const JOB_ID    = <?= json_encode($jobId) ?>;
const JOB_TITLE = <?= json_encode($jobTitle) ?>;


function toggleDD() { document.getElementById('user-dd').classList.toggle('hidden'); }
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});


function switchTab(i) {
  document.querySelectorAll('.tabs .tab-item').forEach((t, j) => t.classList.toggle('active', i === j));
  ['tab-0', 'tab-1', 'tab-2', 'tab-3'].forEach((id, j) => {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('hidden', i !== j);
  });
}


function copyLink() {
  navigator.clipboard?.writeText(window.location.href).then(() => {
    const el = document.getElementById('copied-msg');
    if (el) {
      el.textContent = 'Link copied!';
      setTimeout(() => el.textContent = 'Share This Job', 2500);
    }
  });
}

function showToast(msg, type = 'success') {
  const s = document.getElementById('toast-stack');
  const isWarn = type === 'warn';
  s.innerHTML = `<div class="toast ${isWarn ? 'warning' : 'success'}"><span class="toast-icon">${isWarn ? '⚠' : '✓'}</span><div><div class="toast-title">${isWarn ? 'Required' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML = '', 4000);
}
</script>
</body>
</html>