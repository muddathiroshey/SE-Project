<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/specialist/earnings.php
    Template: Earnings & Payouts — Specialist View
    Role:     specialist (authenticated)
    Route:    /earnings
    ============================================================
    PHP Data contract (from EarningsController::index()):
      $specialist       — authenticated specialist record
      $summary          — [ cleared, pending, on_hold, lifetime,
                            this_month, last_month, ytd,
                            fee_rate, fee_tier, next_tier_at,
                            lifetime_toward_tier ]
      $payouts          — paginated PayoutRecord[]
      $escrow_active    — EscrowRecord[] (per active project/milestone)
      $tax              — [ jurisdiction, vat_rate, vat_number,
                            ytd_gross, ytd_fees, ytd_net,
                            ytd_vat_collected ]
      $payment_methods  — SpecialistPayoutMethod[]
      $monthly_chart    — array(12) of monthly totals
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Earnings &amp; Payouts — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/specialist-wallet.css">
</head>
<body>

<!-- ══════════ TOPNAV ══════════ -->
<nav class="topnav">
  <div class="container" style="max-width:100%;padding:0 32px;">
    <a class="topnav-logo" href="/">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="/dashboard">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="#" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔 <span class="notif-count" style="position:absolute;top:2px;right:2px;">7</span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">DR</div></div>
          <!-- PHP: <?= htmlspecialchars($specialist['display_name']) ?> -->
          <span style="font-size:.875rem;font-weight:700;">Dr. Rania K.</span>
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

<!-- ══════════ EARNINGS HERO ══════════ -->
<div class="earnings-hero">
  <div class="container" style="max-width:1200px;">
    <!-- PHP: $specialist['display_name'].' · Earnings Overview' -->
    <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:rgba(247,244,239,.35);font-family:var(--font-body);margin-bottom:20px;position:relative;z-index:1;">Dr. Rania Khalil · Earnings Overview · Silver Tier</div>
    <div class="earnings-hero-grid">
      <div class="hero-stat">
        <div class="hero-stat-val">$<?= number_format($summary['cleared'], 2) ?></div>
        <div class="hero-stat-lbl">Cleared Balance</div>
        <div class="hero-stat-sub" style="color:var(--sage);">Available to withdraw</div>
      </div>
      <div class="hero-divider"></div>
      <div class="hero-stat">
        <div class="hero-stat-val" style="color:var(--gold);">$<?= number_format($summary['pending'], 2) ?></div>
        <div class="hero-stat-lbl">Pending Release</div>
        <div class="hero-stat-sub" style="color:rgba(201,168,76,.7);">In cooling period</div>
      </div>
      <div class="hero-divider"></div>
      <div class="hero-stat">
        <div class="hero-stat-val">$<?= number_format($summary['ytd'], 2) ?></div>
        <div class="hero-stat-lbl">Earned YTD</div>
        <div class="hero-stat-sub" style="color:rgba(247,244,239,.4);">↑ 22% vs last year</div>
      </div>
      <div class="hero-divider"></div>
      <div class="hero-stat">
        <div class="hero-stat-val">$<?= number_format($summary['lifetime'], 2) ?></div>
        <div class="hero-stat-lbl">Lifetime Earned</div>
        <div class="hero-stat-sub" style="color:rgba(247,244,239,.4);">Since Jan 2021</div>
      </div>
    </div>

    <!-- WITHDRAW BUTTON -->
    <div style="text-align:center;margin-top:28px;position:relative;z-index:1;">
      <button class="btn btn-gold btn-lg" onclick="document.getElementById('withdraw-modal').classList.remove('hidden')">
        ↑ Withdraw Cleared Balance ($28,400)
      </button>
    </div>
  </div>
</div>

<!-- ══════════ TABS ══════════ -->
<div class="earnings-tabs">
  <div style="max-width:1200px;margin:0 auto;">
    <div class="tabs" style="border:none;">
      <button class="tab-item active" onclick="switchTab(0)">Overview</button>
      <button class="tab-item" onclick="switchTab(1)">Payout History</button>
      <button class="tab-item" onclick="switchTab(2)">Active Escrow</button>
      <button class="tab-item" onclick="switchTab(3)">Tax &amp; Fees</button>
      <button class="tab-item" onclick="switchTab(4)">Payout Methods</button>
      <button class="tab-item" onclick="switchTab(5)">Preferences</button>
    </div>
  </div>
</div>

<!-- ══════════ BODY ══════════ -->
<div class="earnings-body">

  <!-- ══ TAB 0: OVERVIEW ══ -->
  <div id="tab-0">

    <!-- BALANCE CARDS -->
    <div class="balance-grid">
      <div class="balance-card cleared">
        <div class="balance-lbl">Cleared Balance</div>
        <div class="balance-val" style="color:var(--sage);">$28,400</div>
        <div class="balance-sub">Ready to withdraw · No holds</div>
        <button class="btn btn-sm" style="margin-top:12px;background:var(--sage);color:#fff;border:none;" onclick="document.getElementById('withdraw-modal').classList.remove('hidden')">Withdraw Now</button>
      </div>
      <div class="balance-card pending">
        <div class="balance-lbl">Pending</div>
        <div class="balance-val" style="color:var(--gold);">$6,800</div>
        <div class="balance-sub">In 48h cooling period · Clears Apr 17</div>
        <div class="cooling-indicator mt-12">
          <div>
            <div style="font-weight:700;font-size:.8125rem;margin-bottom:2px;">Cooling period</div>
            <div style="font-size:.75rem;">Clears Apr 17 · 08:00 GMT+2</div>
          </div>
          <div class="cooling-bar-wrap">
            <div class="cooling-bar"><div class="cooling-fill" style="width:55%;"></div></div>
            <div style="font-size:.7rem;font-family:var(--font-mono);margin-top:3px;color:#1A4A8A;">~29h remaining</div>
          </div>
        </div>
      </div>
      <div class="balance-card on-hold">
        <div class="balance-lbl">On Hold</div>
        <div class="balance-val" style="color:var(--rust);">$1,000</div>
        <div class="balance-sub">Dispute DSP-NX-3801 · Awaiting verdict</div>
        <a href="/dispute" class="btn btn-sm btn-outline" style="margin-top:12px;font-size:.75rem;">View Dispute</a>
      </div>
    </div>

    <!-- FEE TIER -->
    <div class="card mb-28">
      <div class="earn-section-label">Platform Fee Tier</div>
      <div class="flex items-center gap-20 mb-16">
        <div>
          <div style="font-family:var(--font-display);font-size:2.5rem;font-weight:300;line-height:1;color:var(--ink);">6.5%</div>
          <div style="font-size:.75rem;color:var(--ink-muted);margin-top:4px;">Current Rate — Silver Tier</div>
        </div>
        <div style="flex:1;">
          <div style="font-size:.8125rem;color:var(--ink-mid);margin-bottom:12px;">You need <strong class="font-mono">$10,200</strong> more lifetime earnings to reach <strong>Gold Tier (5.5%)</strong>. At your current pace, you'll reach it in approximately <strong>2–3 months</strong>.</div>
          <div class="tier-track">
            <div class="tier-line"><div class="tier-line-fill" style="width:59%;"></div></div>
            <div class="tier-node reached" title="Bronze — 8%">8%</div>
            <div class="tier-node current" title="Silver — 6.5% (current)">6.5</div>
            <div class="tier-node" title="Gold — 5.5%">5.5</div>
            <div class="tier-node" title="Platinum — 4%">4%</div>
          </div>
          <div class="tier-labels">
            <span>Bronze<br>$0</span>
            <span class="active-tier">Silver ★<br>$10K</span>
            <span>Gold<br>$25K</span>
            <span>Platinum<br>$100K</span>
          </div>
        </div>
      </div>
      <!-- PHP: '$'.number_format($summary['lifetime_toward_tier']).' / $25,000 lifetime threshold' -->
      <div class="progress-bar" style="height:8px;"><div class="progress-fill" style="width:59%;"></div></div>
      <div class="flex justify-between mt-6 text-xs text-muted font-mono">
        <span>$14,800 lifetime toward Gold</span>
        <span>$25,000 threshold</span>
      </div>
    </div>

    <!-- QUICK STATS ROW -->
    <div class="grid-4" style="margin-top:24px;">
      <div class="stat-card">
        <div class="stat-value" style="font-size:1.4rem;">$5,050</div>
        <div class="stat-label">This Month</div>
        <div class="stat-delta up mt-4">↑ 9.8% vs March</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" style="font-size:1.4rem;">$4,600</div>
        <div class="stat-label">Last Month</div>
        <div class="stat-delta up mt-4">↑ Best month ever</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" style="font-size:1.4rem;">$3,370</div>
        <div class="stat-label">Monthly Average</div>
        <div class="stat-delta mt-4" style="color:var(--ink-muted);">12-month rolling</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" style="font-size:1.4rem;">$2,356</div>
        <div class="stat-label">Fees Paid (YTD)</div>
        <div class="stat-delta mt-4" style="color:var(--ink-muted);">6.5% rate · Silver</div>
      </div>
    </div>

  </div>

  <!-- ══ TAB 1: PAYOUT HISTORY ══ -->
  <div id="tab-1" class="hidden">

    <div class="flex justify-between items-center mb-20">
      <div>
        <div class="earn-section-label">Payout History</div>
        <!-- PHP: count($payouts).' payouts' -->
        <div style="font-size:.8125rem;color:var(--ink-muted);">24 payouts · All time</div>
      </div>
      <div class="flex gap-8">
        <select class="form-control" style="width:150px;padding:5px 10px;font-size:.8125rem;">
          <option>All Statuses</option>
          <option>Cleared</option>
          <option>Pending</option>
          <option>On Hold</option>
        </select>
        <button class="btn btn-outline btn-sm" onclick="document.getElementById('export-modal').classList.remove('hidden')">⬇ Export CSV</button>
      </div>
    </div>

    <div style="border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;">
      <div class="payout-row header">
        <span>Date</span>
        <span>Description</span>
        <span style="text-align:right;">Gross</span>
        <span style="text-align:right;">Fee (6.5%)</span>
        <span style="text-align:right;">Net</span>
        <span style="text-align:center;">Status</span>
      </div>
      <!-- PHP: foreach($payouts as $p): -->
      <div class="payout-row">
        <span class="font-mono" style="font-size:.8rem;">Apr 12, 2025</span>
        <div>
          <div style="font-weight:700;">Predictive Churn Model — Phase 1</div>
          <div class="text-xs text-muted">FinCorp Egypt · CON-NX-3812 · Milestone approved</div>
        </div>
        <span style="text-align:right;font-family:var(--font-mono);">$1,680</span>
        <span style="text-align:right;font-family:var(--font-mono);color:var(--rust);">-$109</span>
        <span style="text-align:right;font-family:var(--font-mono);font-weight:700;color:var(--sage);">$1,571</span>
        <span style="text-align:center;"><span class="payout-status ps-cleared">Cleared</span></span>
      </div>
      <div class="payout-row">
        <span class="font-mono" style="font-size:.8rem;">Apr 10, 2025</span>
        <div>
          <div style="font-weight:700;">Fraud Detection Model — Phase 4</div>
          <div class="text-xs text-muted">Gulf Digital · CON-NX-3799 · Final milestone</div>
        </div>
        <span style="text-align:right;font-family:var(--font-mono);">$1,600</span>
        <span style="text-align:right;font-family:var(--font-mono);color:var(--rust);">-$104</span>
        <span style="text-align:right;font-family:var(--font-mono);font-weight:700;color:var(--sage);">$1,496</span>
        <span style="text-align:center;"><span class="payout-status ps-cleared">Cleared</span></span>
      </div>
      <div class="payout-row">
        <span class="font-mono" style="font-size:.8rem;">Apr 15, 2025</span>
        <div>
          <div style="font-weight:700;">MENA Contract Review — Phase 1 (Awaiting approval)</div>
          <div class="text-xs text-muted">FinCorp Egypt · CON-NX-4821 · Client reviewing</div>
        </div>
        <span style="text-align:right;font-family:var(--font-mono);">$3,000</span>
        <span style="text-align:right;font-family:var(--font-mono);color:var(--rust);">-$195</span>
        <span style="text-align:right;font-family:var(--font-mono);font-weight:700;">$2,805</span>
        <span style="text-align:center;"><span class="payout-status ps-pending">Pending</span></span>
      </div>
      <div class="payout-row">
        <span class="font-mono" style="font-size:.8rem;">Apr 15, 2025</span>
        <div>
          <div style="font-weight:700;">Fraud Detection — Phase 3</div>
          <div class="text-xs text-muted">Gulf Digital · CON-NX-3799 · 48h cooling</div>
        </div>
        <span style="text-align:right;font-family:var(--font-mono);">$3,800</span>
        <span style="text-align:right;font-family:var(--font-mono);color:var(--rust);">-$247</span>
        <span style="text-align:right;font-family:var(--font-mono);font-weight:700;">$3,553</span>
        <span style="text-align:center;"><span class="payout-status ps-cooling">Cooling</span></span>
      </div>
      <div class="payout-row">
        <span class="font-mono" style="font-size:.8rem;">Apr 13, 2025</span>
        <div>
          <div style="font-weight:700;">Annual Report Translation — Phase 3 (Dispute)</div>
          <div class="text-xs text-muted">FinCorp Egypt · CON-NX-3801 · 70/30 verdict</div>
        </div>
        <span style="text-align:right;font-family:var(--font-mono);">$1,400</span>
        <span style="text-align:right;font-family:var(--font-mono);color:var(--rust);">-$91</span>
        <span style="text-align:right;font-family:var(--font-mono);font-weight:700;color:var(--rust);">$1,000 hold</span>
        <span style="text-align:center;"><span class="payout-status ps-on-hold">On Hold</span></span>
      </div>
      <div class="payout-row">
        <span class="font-mono" style="font-size:.8rem;">Mar 28, 2025</span>
        <div>
          <div style="font-weight:700;">Customer Segmentation — Phase 4 (Final)</div>
          <div class="text-xs text-muted">FinCorp Egypt · CON-NX-3344</div>
        </div>
        <span style="text-align:right;font-family:var(--font-mono);">$1,900</span>
        <span style="text-align:right;font-family:var(--font-mono);color:var(--rust);">-$124</span>
        <span style="text-align:right;font-family:var(--font-mono);font-weight:700;color:var(--sage);">$1,776</span>
        <span style="text-align:center;"><span class="payout-status ps-cleared">Cleared</span></span>
      </div>

      <div style="padding:14px 16px;background:var(--ivory-deep);border-top:1.5px solid var(--border);display:flex;justify-content:space-between;font-size:.875rem;">
        <button class="btn btn-ghost btn-sm" onclick="showToast('Loading older payouts…','info')">Load 18 Older Payouts →</button>
        <div style="display:flex;gap:24px;">
          <div><span class="text-muted">Total Gross (shown):</span> <span class="font-mono font-bold">$13,380</span></div>
          <div><span class="text-muted">Total Fees:</span> <span class="font-mono font-bold" style="color:var(--rust);">-$870</span></div>
          <div><span class="text-muted">Total Net:</span> <span class="font-mono font-bold" style="color:var(--sage);">$12,510</span></div>
        </div>
      </div>
    </div>

  </div>

  <!-- ══ TAB 2: ACTIVE ESCROW ══ -->
  <div id="tab-2" class="hidden">

    <div class="earn-section-label">Funds Currently in Escrow</div>
    <p style="font-size:.8125rem;color:var(--ink-muted);margin-bottom:20px;">These funds are locked by clients for your active milestones. They release within 24h of milestone approval. Auto-approval triggers if the client does not review within 72h.</p>

    <div style="border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;margin-bottom:24px;">
      <!-- PHP: foreach($escrow_active as $e): -->
      <div class="escrow-row">
        <div class="escrow-project-dot" style="background:var(--gold);"></div>
        <div style="flex:1;">
          <div style="font-weight:700;font-size:.9rem;">Predictive Churn Model — Phase 2</div>
          <div class="text-xs text-muted">FinCorp Egypt · CON-NX-3812 · Deadline Apr 19</div>
        </div>
        <span class="badge badge-pending badge-dot" style="font-size:.65rem;">In Progress</span>
        <span class="escrow-amount" style="color:var(--gold);">$3,360</span>
        <div style="font-size:.75rem;color:var(--ink-muted);font-family:var(--font-mono);margin-left:12px;">Releases on approval</div>
      </div>
      <div class="escrow-row">
        <div class="escrow-project-dot" style="background:#1A4A8A;"></div>
        <div style="flex:1;">
          <div style="font-weight:700;font-size:.9rem;">MENA Contract Review — Phase 1</div>
          <div class="text-xs text-muted">FinCorp Egypt · CON-NX-4821 · Submitted Apr 15 · Auto-approves Apr 18</div>
        </div>
        <span class="badge badge-dot" style="font-size:.65rem;background:#EBF0F8;color:#1A4A8A;border:1px solid #B8D0F0;">Awaiting Review</span>
        <span class="escrow-amount">$3,000</span>
        <div style="font-size:.75rem;color:#1A4A8A;font-family:var(--font-mono);margin-left:12px;">~67h to auto-approve</div>
      </div>
      <div class="escrow-row">
        <div class="escrow-project-dot" style="background:var(--rust);"></div>
        <div style="flex:1;">
          <div style="font-weight:700;font-size:.9rem;">Annual Report Translation — Phase 3 (Disputed)</div>
          <div class="text-xs text-muted">FinCorp Egypt · DSP-NX-3801 · Frozen pending verdict</div>
        </div>
        <span class="badge badge-danger badge-dot" style="font-size:.65rem;">Frozen</span>
        <span class="escrow-amount" style="color:var(--rust);">$1,400</span>
        <div style="font-size:.75rem;color:var(--rust);font-family:var(--font-mono);margin-left:12px;">Frozen — dispute active</div>
      </div>
      <div style="padding:14px 16px;background:var(--ivory-deep);border-top:1.5px solid var(--border);display:flex;justify-content:space-between;font-size:.875rem;font-weight:700;">
        <span>Total in Escrow</span>
        <span class="font-mono">$7,760</span>
      </div>
    </div>

    <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-md);padding:18px 20px;font-size:.8125rem;color:var(--ink-mid);">
      <strong>How Escrow Works:</strong> When you submit milestone deliverables, the client has 72 hours to review. If they approve, funds clear in 24h. If they do not respond within 72h, the milestone auto-approves and funds release automatically. Disputed milestones are frozen until the arbiter issues a verdict.
    </div>

  </div>

  <!-- ══ TAB 3: TAX & FEES ══ -->
  <div id="tab-3" class="hidden">

    <div class="grid-2">

      <div>
        <div class="earn-section-label">Tax Summary — 2025 YTD</div>
        <!-- PHP: $tax object -->
        <div class="card card-sm mb-20">
          <div class="tax-row"><span class="tax-lbl">Jurisdiction</span><span class="tax-val">Egypt (EGY)</span></div>
          <div class="tax-row"><span class="tax-lbl">Gross Earnings YTD</span><span class="tax-val">$36,200</span></div>
          <div class="tax-row"><span class="tax-lbl">Platform Fees Paid</span><span class="tax-val" style="color:var(--rust);">-$2,356</span></div>
          <div class="tax-row"><span class="tax-lbl">Net Earnings YTD</span><span class="tax-val" style="color:var(--sage);">$33,844</span></div>
          <div class="tax-row"><span class="tax-lbl">VAT Registration No.</span><span class="tax-val font-mono">EG-VAT-98821-K</span></div>
          <div class="tax-row"><span class="tax-lbl">Applicable VAT Rate</span><span class="tax-val">14% (Egypt)</span></div>
          <div class="tax-row"><span class="tax-lbl">VAT on Fees (YTD)</span><span class="tax-val font-mono">$330</span></div>
          <div class="tax-row" style="font-weight:700;font-size:.9rem;">
            <span>Estimated Tax Obligation (EGY)</span>
            <span class="tax-val font-mono">$2,686</span>
          </div>
        </div>
        <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;font-size:.8125rem;color:var(--ink-mid);margin-bottom:16px;">
          ℹ Nexus is not a tax advisor. These figures are estimations to help your planning. Consult a qualified accountant for your jurisdiction's specific obligations.
        </div>
      </div>

      <div>
        <div class="earn-section-label">Platform Fee Breakdown — YTD</div>
        <div class="card card-sm mb-20">
          <div class="tax-row"><span class="tax-lbl">Current Tier</span><span class="tax-val">Silver — 6.5%</span></div>
          <div class="tax-row"><span class="tax-lbl">Gross Billed to Clients</span><span class="tax-val font-mono">$36,200</span></div>
          <div class="tax-row"><span class="tax-lbl">Platform Fees (6.5%)</span><span class="tax-val font-mono" style="color:var(--rust);">-$2,356</span></div>
          <div class="tax-row"><span class="tax-lbl">Net After Fees</span><span class="tax-val font-mono" style="color:var(--sage);">$33,844</span></div>

          <div style="height:1px;background:var(--border);margin:12px 0;"></div>
          <div style="font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:8px;font-family:var(--font-body);">Fee Savings at Higher Tiers (YTD)</div>
          <div class="tax-row"><span class="tax-lbl">If at Gold (5.5%)</span><span class="tax-val font-mono" style="color:var(--sage);">+$363 saved</span></div>
          <div class="tax-row"><span class="tax-lbl">If at Platinum (4%)</span><span class="tax-val font-mono" style="color:var(--sage);">+$906 saved</span></div>
          <div style="font-size:.75rem;color:var(--ink-muted);margin-top:8px;">You're $10,200 from Gold tier. Reach it and save ~$730/year at current pace.</div>
        </div>

        <div class="earn-section-label">Update Tax Profile</div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tax Jurisdiction</label>
            <select class="form-control" style="font-size:.875rem;">
              <option selected>Egypt (EGY)</option>
              <option>UAE</option>
              <option>Saudi Arabia</option>
              <option>United Kingdom</option>
              <option>Germany</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">VAT / Tax ID</label>
            <input type="text" class="form-control" style="font-size:.875rem;" value="EG-VAT-98821-K">
          </div>
        </div>
        <button class="btn btn-primary btn-sm" onclick="showToast('Tax profile updated.')">Save Tax Profile</button>
      </div>

    </div>

  </div>

  <!-- ══ TAB 4: PAYOUT METHODS ══ -->
  <div id="tab-4" class="hidden">

    <div class="earn-section-label">Payout Methods</div>
    <p style="font-size:.8125rem;color:var(--ink-muted);margin-bottom:20px;">Nexus releases cleared funds to your primary payout method every business day. You can also trigger manual withdrawals at any time.</p>

    <!-- PHP: foreach($payment_methods as $m): -->
    <div class="payment-card default" style="cursor:default;">
      <div class="card-chip visa">VISA</div>
      <div style="flex:1; display:flex; flex-direction:column; justify-content:center; gap:4px;">
        <div style="font-weight:700;font-size:.9375rem;color:var(--ink);">Visa — Commercial International Bank</div>
        <div class="text-xs text-muted">Account ending ···· 4812 · Cairo, Egypt · EGP</div>
        <div class="text-xs text-muted font-mono">IBAN: EG38····················4812</div>
      </div>
      <div style="display:flex;align-items:center;gap:10px;">
        <span class="badge badge-verified" style="font-size:.65rem;">Default</span>
        <button class="btn btn-ghost btn-sm" style="color:var(--rust);">Remove</button>
      </div>
    </div>

    <div class="payment-card" style="cursor:default;">
      <div class="card-chip mastercard">MC</div>
      <div style="flex:1; display:flex; flex-direction:column; justify-content:center; gap:4px;">
        <div style="font-weight:700;font-size:.9375rem;color:var(--ink);">Mastercard — TransferWise</div>
        <div class="text-xs text-muted">rania.khalil@example.com · Multi-currency</div>
      </div>
      <div style="display:flex;align-items:center;gap:8px;">
        <button class="btn btn-outline btn-sm" onclick="showToast('Mastercard set as default payout method.')">Set Default</button>
        <button class="btn btn-ghost btn-sm" style="color:var(--rust);">Remove</button>
      </div>
    </div>

    <button class="btn btn-outline btn-sm mt-12" onclick="document.getElementById('add-method-modal').classList.remove('hidden')">+ Add Payout Method</button>

    <hr class="divider" style="margin:28px 0;">

    <div class="earn-section-label">Manual Withdrawal</div>
    <div class="card card-sm">
      <div class="flex justify-between items-center mb-16">
        <div>
          <div style="font-weight:700;font-size:.9375rem;">Available to Withdraw</div>
          <div style="font-family:var(--font-display);font-size:2rem;font-weight:300;color:var(--sage);line-height:1.1;margin-top:4px;">$28,400</div>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('withdraw-modal').classList.remove('hidden')">↑ Withdraw Now</button>
      </div>
      <div style="font-size:.8125rem;color:var(--ink-muted);line-height:1.8;">
        <div>• Transfers to your default payout method (CIB ···· 4812)</div>
        <div>• Standard bank processing: 1–3 business days</div>
        <div>• No withdrawal fee for amounts above $100</div>
        <div>• Minimum withdrawal: $50</div>
      </div>
    </div>

  </div>

  <!-- ══ TAB 5: PREFERENCES ══ -->
  <div id="tab-5" class="hidden">

    <div class="earn-section-label">Payout Preferences</div>
    <div class="card card-sm mb-24">
      <div class="pref-row">
        <div class="pref-row-label">
          <strong>Automatic Weekly Payouts</strong>
          Nexus automatically withdraws your cleared balance every Monday.
        </div>
        <label class="toggle"><input type="checkbox" checked onchange="showToast('Auto-payout setting saved.')"><span class="toggle-slider"></span></label>
      </div>
      <div class="pref-row">
        <div class="pref-row-label">
          <strong>Multi-Currency Holding</strong>
          Hold earnings in their original currency (USD, EUR, GBP) instead of converting to EGP.
        </div>
        <label class="toggle"><input type="checkbox" checked onchange="showToast('Currency preference saved.')"><span class="toggle-slider"></span></label>
      </div>
      <div class="pref-row">
        <div class="pref-row-label">
          <strong>Email Receipt for Every Payout</strong>
          Receive a PDF receipt when each milestone payment clears.
        </div>
        <label class="toggle"><input type="checkbox" checked onchange="showToast('Email receipt preference saved.')"><span class="toggle-slider"></span></label>
      </div>
      <div class="pref-row">
        <div class="pref-row-label">
          <strong>Monthly Earnings Summary Email</strong>
          A full breakdown of the previous month's earnings, fees, and tax estimate.
        </div>
        <label class="toggle"><input type="checkbox" checked onchange="showToast('Monthly summary preference saved.')"><span class="toggle-slider"></span></label>
      </div>
    </div>

    <div class="earn-section-label">Minimum Auto-Payout Threshold</div>
    <div class="card card-sm">
      <p style="font-size:.8125rem;color:var(--ink-muted);margin-bottom:14px;">Auto-payouts will only trigger if your cleared balance exceeds this amount.</p>
      <div style="display:flex;gap:12px;align-items:center;">
        <div style="position:relative;width:160px;">
          <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-family:var(--font-mono);color:var(--ink-muted);">$</span>
          <input type="number" class="form-control" style="padding-left:28px;font-family:var(--font-mono);" value="500" min="50" step="50">
        </div>
        <span style="font-size:.875rem;color:var(--ink-muted);">minimum balance before auto-payout triggers</span>
        <button class="btn btn-primary btn-sm" onclick="showToast('Threshold saved.')">Save</button>
      </div>
    </div>

  </div>

</div>

<!-- ══════════ MODALS ══════════ -->

<!-- WITHDRAW MODAL -->
<div id="withdraw-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Withdraw Cleared Balance</h3>
        <p class="text-sm text-muted mt-4">Funds will be transferred to your default payout method.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('withdraw-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div style="background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px;font-size:.875rem;">
        <div class="flex justify-between"><span class="text-muted">Available to Withdraw</span><span class="font-mono font-bold" style="color:var(--sage);">$28,400</span></div>
      </div>
      <div class="form-group">
        <label class="form-label">Amount to Withdraw</label>
        <div style="position:relative;">
          <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-family:var(--font-mono);color:var(--ink-muted);">$</span>
          <input type="number" id="withdraw-amount" class="form-control" style="padding-left:28px;font-family:var(--font-mono);font-size:1rem;" value="28400" max="28400" min="50" oninput="updateWithdrawalSummary()" data-available="28400">
        </div>
        <p class="form-hint mt-4">Minimum $50. No fee for amounts above $100.</p>
        <div id="withdrawal-error" style="display:none;color:var(--rust);font-size:.8125rem;margin-top:8px;padding:8px 12px;background:#FFE5E5;border:1px solid #FFB3B3;border-radius:var(--radius-sm);"></div>
      </div>
      <div class="form-group">
        <label class="form-label">To Payout Method</label>
        <div class="payment-card default" style="cursor:pointer;" onclick="selectPayoutMethod(this,'visa-4812')" data-payment-id="visa-4812">
          <div class="card-chip visa">VISA</div>
          <div style="flex:1; display: flex; flex-direction: column; justify-content: center;">
            <div style="font-weight:700;font-size:.9375rem;color:var(--ink);line-height:1.2;">Visa ···· 4812</div>
            <div style="font-size:.8125rem;color:var(--ink-muted);margin-top:4px;">Default</div>
          </div>
          <input type="radio" name="payout-method" value="visa-4812" checked>
        </div>
        <div class="payment-card" style="cursor:pointer;" onclick="selectPayoutMethod(this,'mastercard-rania')" data-payment-id="mastercard-rania">
          <div class="card-chip mastercard">MC</div>
          <div style="flex:1; display: flex; flex-direction: column; justify-content: center;">
            <div style="font-weight:700;font-size:.9375rem;color:var(--ink);line-height:1.2;">Mastercard ···· 5829</div>
            <div style="font-size:.8125rem;color:var(--ink-muted);margin-top:4px;">rania.khalil@example.com</div>
          </div>
          <input type="radio" name="payout-method" value="mastercard-rania">
        </div>
      </div>
      <div style="background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 14px;font-size:.8125rem;color:var(--ink-mid);">
        <div class="flex justify-between mb-4"><span>Withdrawal Amount</span><span class="font-mono" id="summary-amount">$28,400</span></div>
        <div class="flex justify-between mb-4"><span>Processing Fee</span><span class="font-mono" id="summary-fee">$0 (above $100)</span></div>
        <div class="flex justify-between font-bold"><span>You Receive</span><span class="font-mono" id="summary-receive" style="color:var(--sage);">$28,400</span></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('withdraw-modal').classList.add('hidden')">Cancel</button>
      <button id="confirm-withdrawal-btn" class="btn btn-primary" onclick="submitWithdrawal()">Confirm Withdrawal</button>
    </div>
  </div>
</div>

<!-- ADD PAYOUT METHOD MODAL -->
<div id="add-method-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3>Add Payout Method</h3>
      <button class="modal-close" onclick="document.getElementById('add-method-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Method Type</label>
        <select class="form-control" id="method-type" onchange="updateMethodForm(this.value)">
          <option value="bank">Bank Transfer (IBAN/SWIFT)</option>
          <option value="wise">Wise</option>
          <option value="paypal">PayPal</option>
        </select>
      </div>
      <div id="method-bank-fields">
        <div class="form-group">
          <label class="form-label">Bank Name</label>
          <input type="text" class="form-control" placeholder="e.g. CIB, NBE, HSBC">
        </div>
        <div class="form-group">
          <label class="form-label">IBAN</label>
          <input type="text" class="form-control" placeholder="EG38 0020 0001 0000 2112 3456 7891 3">
        </div>
        <div class="form-group">
          <label class="form-label">Account Currency</label>
          <select class="form-control">
            <option>USD</option>
            <option>EGP</option>
            <option>EUR</option>
            <option>GBP</option>
          </select>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
        <input type="checkbox" id="set-default-method" style="accent-color:var(--gold);">
        <label for="set-default-method" style="font-size:.875rem;color:var(--ink-mid);">Set as default payout method</label>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('add-method-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('add-method-modal').classList.add('hidden');showToast('Payout method added. Verification may take 1 business day.')">Add Method</button>
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

/* ── TABS ── */
/* ── PAYOUT METHOD SELECTION ── */
function selectPayoutMethod(el, methodValue) {
  const cards = el.parentElement.querySelectorAll('.payment-card');
  cards.forEach(card => card.classList.remove('default'));
  el.classList.add('default');
  el.querySelector('input[type="radio"]').checked = true;
}

/* ── WITHDRAWAL SUMMARY ── */
function updateWithdrawalSummary() {
  const amountInput = document.getElementById('withdraw-amount');
  const summaryAmount = document.getElementById('summary-amount');
  const summaryFee = document.getElementById('summary-fee');
  const summaryReceive = document.getElementById('summary-receive');
  const errorDiv = document.getElementById('withdrawal-error');
  const confirmBtn = document.getElementById('confirm-withdrawal-btn');
  
  if (!amountInput || !summaryAmount || !summaryFee || !summaryReceive) return;
  
  const amount = parseFloat(amountInput.value) || 0;
  const available = parseFloat(amountInput.dataset.available) || 28400;
  let fee = 0;
  let feeText = '$0 (above $100)';
  let isValid = true;
  let errorMsg = '';
  
  // Validation checks
  if (amount < 50) {
    isValid = false;
    errorMsg = 'Minimum withdrawal is $50.';
  } else if (amount > available) {
    isValid = false;
    errorMsg = `Cannot exceed available balance of $${available.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}.`;
  }
  
  // Calculate fee and receive amount only if valid
  if (isValid) {
    if (amount > 0 && amount <= 100) {
      fee = amount * 0.025;
      feeText = `$${fee.toFixed(2)} (2.5% for amounts ≤$100)`;
    } else if (amount > 100) {
      feeText = '$0 (above $100)';
    }
  }
  
  const receive = amount - fee;
  
  // Update summary display
  summaryAmount.textContent = `$${amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
  summaryFee.textContent = feeText;
  summaryReceive.textContent = `$${receive.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
  
  // Show/hide error and update button state
  if (isValid) {
    errorDiv.style.display = 'none';
    errorDiv.textContent = '';
    confirmBtn.disabled = false;
    confirmBtn.style.opacity = '1';
    confirmBtn.style.cursor = 'pointer';
  } else {
    errorDiv.style.display = 'block';
    errorDiv.textContent = errorMsg;
    confirmBtn.disabled = true;
    confirmBtn.style.opacity = '0.6';
    confirmBtn.style.cursor = 'not-allowed';
  }
}

/* ── SUBMIT WITHDRAWAL ── */
function submitWithdrawal() {
  const amountInput = document.getElementById('withdraw-amount');
  const amount = parseFloat(amountInput.value) || 0;
  
  // Frontend validation (already disabled button if invalid, but check again for safety)
  const available = parseFloat(amountInput.dataset.available) || 28400;
  if (amount < 50 || amount > available) {
    showToast('Please enter a valid withdrawal amount.', 'warn');
    return;
  }
  
  // TODO: Backend validation (PHP/Laravel)
  // Before processing withdrawal in your controller, validate:
  // 1. Ensure authenticated user is the owner
  // 2. Fetch current cleared_balance from database (do NOT trust client value)
  // 3. Verify: $withdrawalAmount <= $clearedBalance
  // 4. Verify: $withdrawalAmount >= 50 (minimum)
  // 5. If valid, create WithdrawalRequest record with status='pending'
  // 6. Return success/failure response
  // 7. Store withdrawal ID and payout method selection securely on server
  
  const selectedMethod = document.querySelector('input[name="payout-method"]:checked')?.value;
  
  document.getElementById('withdraw-modal').classList.add('hidden');
  showToast(`Withdrawal of $${amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} initiated. Funds arrive within 1–3 business days.`);
  
  // In production, send to backend:
  // POST /api/withdrawals
  // { amount: amount, payoutMethodId: selectedMethod }
}

function switchTab(i) {
  document.querySelectorAll('.tabs .tab-item').forEach((t,j) => t.classList.toggle('active', i===j));
  ['tab-0','tab-1','tab-2','tab-3','tab-4','tab-5'].forEach((id,j) => {
    const el = document.getElementById(id);
    if(el) el.classList.toggle('hidden', i!==j);
  });
}

/* ── PAYOUT METHOD FORM ── */
function updateMethodForm(val) {
  const bankFields = document.getElementById('method-bank-fields');
  if(val === 'bank') {
    bankFields.innerHTML = `<div class="form-group"><label class="form-label">Bank Name</label><input type="text" class="form-control" placeholder="e.g. CIB, NBE, HSBC"></div><div class="form-group"><label class="form-label">IBAN</label><input type="text" class="form-control" placeholder="EG38 0020 0001 0000 2112 3456 7891 3"></div><div class="form-group"><label class="form-label">Account Currency</label><select class="form-control"><option>USD</option><option>EGP</option><option>EUR</option><option>GBP</option></select></div>`;
  } else if(val === 'wise') {
    bankFields.innerHTML = `<div class="form-group"><label class="form-label">Wise Email Address</label><input type="email" class="form-control" placeholder="your@email.com"></div>`;
  } else if(val === 'paypal') {
    bankFields.innerHTML = `<div class="form-group"><label class="form-label">PayPal Email Address</label><input type="email" class="form-control" placeholder="your@paypal.com"></div>`;
  }
}

/* ── TOAST ── */
function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  const icons = {success:'✓', warn:'⚠', info:'ℹ'};
  const cls   = {success:'success', warn:'warning', info:''};
  s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type==='warn'?'Notice':type==='info'?'Info':'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(() => s.innerHTML = '', 4500);
}

/* ── INITIALIZE ── */
document.addEventListener('DOMContentLoaded', () => {
  updateWithdrawalSummary();
});
</script>
</body>
</html>
