<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Escrow &amp; Wallet — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/client-wallet.css">
</head>
<body>

<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="dashboard-client.html">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">🔔<span class="notif-count" style="position:absolute;top:2px;right:2px;">4</span></a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar avatar-sm">AT</div>
          <span style="font-size:.875rem;font-weight:700;color:var(--ink);">Amira T.</span>
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

<!-- WALLET HERO -->
<div class="wallet-hero">
  <div class="container">
    <div style="color:rgba(247,244,239,.45);font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;margin-bottom:20px;font-family:var(--font-body);">Amira Tawfik · Client Wallet · FinCorp Egypt</div>
    <div class="wallet-hero-inner">
      <div class="wallet-stat">
        <div class="wallet-val">$24,500</div>
        <div class="wallet-lbl">Total Spent</div>
        <div class="wallet-delta">Total lifetime spendings</div>
      </div>
      <div class="wallet-stat">
        <div class="wallet-val">$15,440</div>
        <div class="wallet-lbl">Released (YTD)</div>
        <div class="wallet-delta">↑ 18% vs last year</div>
      </div>
      <div class="wallet-stat">
        <div class="wallet-val">$6,960</div>
        <div class="wallet-lbl">In Escrow</div>
        <div class="wallet-delta">Across 3 active milestones</div>
      </div>
      <div class="wallet-stat">
        <div class="wallet-val">$2,100</div>
        <div class="wallet-lbl">Pending Release</div>
        <div class="wallet-delta">In cooling-off period</div>
      </div>
      <div class="wallet-stat">
        <div class="wallet-val">$1,400</div>
        <div class="wallet-lbl">Frozen (Dispute)</div>
        <div class="wallet-delta" style="color:rgba(240,100,80,.7);">DSP-NX-3801 · Active</div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="wallet-body">

    <!-- LEFT -->
    <div>

      <!-- TABS -->
      <div class="tabs mb-24">
        <button class="tab-item active" onclick="wTab(0)">Escrow by Project</button>
        <button class="tab-item" onclick="wTab(1)">Transaction History</button>
        <button class="tab-item" onclick="wTab(2)">Tax &amp; Compliance</button>
      </div>

      <!-- ESCROW BY PROJECT -->
      <div id="wt-0">
        <div class="flex justify-between items-center mb-16">
          <h3>Escrow Breakdown</h3>
          <button class="btn btn-primary btn-sm" onclick="document.getElementById('fund-modal').classList.remove('hidden')">+ Fund Next Milestone</button>
        </div>

        <div class="escrow-project-card">
          <div class="escrow-project-header">
            <div>
              <span class="badge badge-gold">Data Science</span>
              <div style="font-weight:700;font-size:.9375rem;margin-top:6px;">Predictive Churn Model — FinCorp</div>
              <div class="text-xs text-muted font-mono mt-2">NX-2025-3812</div>
            </div>
            <div style="text-align:right;">
              <div style="font-family:var(--font-mono);font-size:1.1rem;font-weight:500;">$8,400</div>
              <div class="text-xs text-muted">Total Budget</div>
            </div>
          </div>
          <div class="escrow-project-body">
            <div class="progress-bar mb-12"><div class="progress-fill success" style="width:20%;"></div></div>
            <div class="escrow-phase-row">
              <div class="phase-status-dot" style="background:var(--sage);"></div>
              <span style="flex:1;">Phase 1 — EDA &amp; Baseline</span>
              <span class="badge badge-verified" style="font-size:.625rem;">Released</span>
              <span class="escrow-phase-amount" style="color:var(--sage);">$1,680</span>
            </div>
            <div class="escrow-phase-row">
              <div class="phase-status-dot" style="background:var(--gold);"></div>
              <span style="flex:1;">Phase 2 — Feature Engineering</span>
              <span class="badge badge-pending" style="font-size:.625rem;">In Escrow</span>
              <span class="escrow-phase-amount" style="color:var(--gold);">$3,360</span>
            </div>
            <div class="escrow-phase-row">
              <div class="phase-status-dot" style="background:var(--border-dark);"></div>
              <span style="flex:1;color:var(--ink-faint);">Phase 3 — Model Evaluation</span>
              <span class="badge badge-default" style="font-size:.625rem;">🔒 Locked</span>
              <span class="escrow-phase-amount text-muted">$840</span>
            </div>
            <div class="escrow-phase-row">
              <div class="phase-status-dot" style="background:var(--border-dark);"></div>
              <span style="flex:1;color:var(--ink-faint);">Phase 4 — Production Pipeline</span>
              <span class="badge badge-default" style="font-size:.625rem;">🔒 Locked</span>
              <span class="escrow-phase-amount text-muted">$1,680</span>
            </div>
            <div class="escrow-phase-row">
              <div class="phase-status-dot" style="background:var(--border-dark);"></div>
              <span style="flex:1;color:var(--ink-faint);">Phase 5 — Final Handoff</span>
              <span class="badge badge-default" style="font-size:.625rem;">🔒 Locked</span>
              <span class="escrow-phase-amount text-muted">$840</span>
            </div>
          </div>
        </div>

        <div class="escrow-project-card">
          <div class="escrow-project-header">
            <div>
              <span class="badge badge-verified">Legal</span>
              <div style="font-weight:700;font-size:.9375rem;margin-top:6px;">MENA Expansion — Contract Review</div>
              <div class="text-xs text-muted font-mono mt-2">NX-2025-4821</div>
            </div>
            <div style="text-align:right;">
              <div style="font-family:var(--font-mono);font-size:1.1rem;font-weight:500;">$12,000</div>
              <div class="text-xs text-muted">Total Budget</div>
            </div>
          </div>
          <div class="escrow-project-body">
            <div class="escrow-phase-row">
              <div class="phase-status-dot" style="background:var(--gold);"></div>
              <span style="flex:1;">Phase 1 — Document Review</span>
              <span class="badge badge-pending" style="font-size:.625rem;">In Escrow</span>
              <span class="escrow-phase-amount" style="color:var(--gold);">$3,000</span>
            </div>
            <div class="escrow-phase-row">
              <div class="phase-status-dot" style="background:var(--border-dark);"></div>
              <span style="flex:1;color:var(--ink-faint);">Phase 2 — Jurisdiction Analysis</span>
              <span class="badge badge-default" style="font-size:.625rem;">🔒 Locked</span>
              <span class="escrow-phase-amount text-muted">$4,500</span>
            </div>
            <div class="escrow-phase-row">
              <div class="phase-status-dot" style="background:var(--border-dark);"></div>
              <span style="flex:1;color:var(--ink-faint);">Phase 3 — Final Report</span>
              <span class="badge badge-default" style="font-size:.625rem;">🔒 Locked</span>
              <span class="escrow-phase-amount text-muted">$4,500</span>
            </div>
          </div>
        </div>

        <div class="escrow-project-card" style="border-top:3px solid var(--rust);">
          <div class="escrow-project-header" style="background:#FDF5F4;">
            <div>
              <span class="badge badge-danger">Dispute Active</span>
              <div style="font-weight:700;font-size:.9375rem;margin-top:6px;">Annual Report — DE/EN Translation</div>
              <div class="text-xs text-muted font-mono mt-2">NX-2025-3801</div>
            </div>
            <div style="text-align:right;">
              <div style="font-family:var(--font-mono);font-size:1.1rem;font-weight:500;color:var(--rust);">$1,400</div>
              <div class="text-xs text-muted">Frozen · Phase 3</div>
            </div>
          </div>
          <div class="escrow-project-body">
            <p class="text-sm text-muted">Funds frozen pending dispute resolution. Arbiter verdict expected within 60 hours. Funds will be released or refunded per the verdict.</p>
            <a href="dispute.html" class="btn btn-danger btn-sm mt-12">View Dispute →</a>
          </div>
        </div>
      </div>

      <!-- TRANSACTION HISTORY -->
      <div id="wt-1" class="hidden">
        <div class="flex justify-between items-center mb-16">
          <h3>Transaction History</h3>
          <button class="btn btn-outline btn-sm">⬇ Export CSV</button>
        </div>
        <div class="tx-row">
          <div class="tx-icon lock">🔒</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Escrow Locked — Phase 2</div><div class="text-xs text-muted">Predictive Churn Model · Apr 14, 2025</div></div>
          <span class="tx-amount lock">-$3,360</span>
        </div>
        <div class="tx-row">
          <div class="tx-icon credit">✓</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Phase 1 Released to Specialist</div><div class="text-xs text-muted">Predictive Churn Model · Apr 12, 2025</div></div>
          <span class="tx-amount debit">-$1,680</span>
        </div>
        <div class="tx-row">
          <div class="tx-icon lock">🔒</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Escrow Locked — Phase 1 (Contract)</div><div class="text-xs text-muted">Predictive Churn Model · Apr 3, 2025</div></div>
          <span class="tx-amount lock">-$1,680</span>
        </div>
        <div class="tx-row">
          <div class="tx-icon lock">🔒</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875iem;">Escrow Locked — Phase 1 (Legal)</div><div class="text-xs text-muted">MENA Expansion Contract Review · Apr 3, 2025</div></div>
          <span class="tx-amount lock">-$3,000</span>
        </div>
        <div class="tx-row">
          <div class="tx-icon credit">💳</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Wallet Top-Up</div><div class="text-xs text-muted">Mastercard ···· 4821 · Apr 3, 2025</div></div>
          <span class="tx-amount credit">+$10,000</span>
        </div>
        <div class="tx-row">
          <div class="tx-icon debit">💸</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Platform Fee (6.5%) — Churn Model P1</div><div class="text-xs text-muted">Apr 12, 2025</div></div>
          <span class="tx-amount debit">-$109</span>
        </div>
      </div>

      <!-- TAX & COMPLIANCE -->
      <div id="wt-2" class="hidden">
        <h3 class="mb-4">Tax &amp; VAT Compliance</h3>
        <p class="text-sm text-muted mb-16">Nexus calculates applicable taxes based on client and specialist jurisdictions. You are responsible for filing in your jurisdiction.</p>
        <div class="card card-sm mb-16">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Your Tax Profile</div>
          <div class="tax-row"><span class="text-muted">Entity Type</span><span class="font-mono">Corporate (LLC)</span></div>
          <div class="tax-row"><span class="text-muted">Jurisdiction</span><span class="font-mono">Egypt (EGY)</span></div>
          <div class="tax-row"><span class="text-muted">VAT Registration</span><span class="font-mono">EG-VAT-28841-C</span></div>
          <div class="tax-row"><span class="text-muted">Applicable VAT Rate</span><span class="font-mono">14% (Egypt)</span></div>
          <div class="tax-row"><span class="text-muted">VAT Collected YTD</span><span class="font-mono">$2,162</span></div>
        </div>
        <div class="card card-sm">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">2025 Tax Summary (YTD)</div>
          <div class="tax-row"><span>Total Platform Fees Paid</span><span class="font-mono">$1,004</span></div>
          <div class="tax-row"><span>VAT on Platform Fees</span><span class="font-mono">$141</span></div>
          <div class="tax-row"><span>Total Specialist Payments</span><span class="font-mono">$15,440</span></div>
          <div class="tax-row"><span>Withholding Tax Applied (10%)</span><span class="font-mono">$0 (exempt under treaty)</span></div>
          <hr class="divider" style="margin:8px 0;">
          <div class="tax-row" style="font-weight:700;"><span>Total Tax Obligations (EGY)</span><span class="font-mono">$1,145</span></div>
        </div>
      </div>
    </div>

    <!-- RIGHT SIDEBAR -->
    <div>

      <!-- PAYMENT METHODS -->
      <div class="card mb-16">
        <h4 style="font-size:.9rem;margin-bottom:14px;">Payment Methods</h4>
        <div class="payment-method-card active">
          <div class="card-logo">MC</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Mastercard ···· 4821</div><div class="text-xs text-muted">Expires 09/27 · Primary</div></div>
          <span class="badge badge-verified" style="font-size:.625rem;">Default</span>
        </div>
        <div class="payment-method-card">
          <div class="card-logo" style="background:#1A3C87;">VISA</div>
          <div style="flex:1;"><div style="font-weight:700;font-size:.875rem;">Visa ···· 2201</div><div class="text-xs text-muted">Expires 03/26</div></div>
        </div>
        <div class="text-xs text-muted">
          To add or change the default payment method, go to your profile settings.
        </div>
      </div>

      <!-- PLATFORM FEE TIER -->
      <div class="card">
        <h4 style="font-size:.9rem;margin-bottom:10px;">Platform Fee Tier</h4>
        <div style="font-family:var(--font-display);font-size:1.8rem;font-weight:300;margin-bottom:4px;">6.5%</div>
        <div class="text-xs text-muted mb-12">Current commission rate (Silver Tier)</div>
        <div class="progress-bar mb-8"><div class="progress-fill" style="width:62%;"></div></div>
        <div class="text-xs text-muted mb-12">$15,440 / $25,000 lifetime spend to Gold Tier (5.5%)</div>
        <div style="font-size:.8125rem;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 14px;">
          <div class="flex justify-between mb-4"><span class="text-muted">Bronze (New)</span><span class="font-mono">8%</span></div>
          <div class="flex justify-between mb-4" style="font-weight:700;"><span>Silver (Current)</span><span class="font-mono text-gold">6.5%</span></div>
          <div class="flex justify-between mb-4"><span class="text-muted">Gold ($25K+)</span><span class="font-mono">5.5%</span></div>
          <div class="flex justify-between"><span class="text-muted">Platinum ($100K+)</span><span class="font-mono">4%</span></div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- FUND MILESTONE MODAL -->
<div id="fund-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Fund Next Milestone</h3>
        <p class="text-sm text-muted mt-4">Lock Phase 3 escrow for Predictive Churn Model.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('fund-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="card card-sm mb-16" style="background:var(--ivory-deep);">
        <div class="text-xs text-muted mb-4">Milestone to Fund</div>
        <div style="font-weight:700;">Phase 3 — Model Evaluation &amp; Bias Audit</div>
        <div style="font-family:var(--font-mono);font-size:1.2rem;font-weight:500;margin-top:8px;">$840</div>
      </div>
      <div class="form-group">
        <label class="form-label">Payment Method</label>
        <select class="form-control">
          <option>Mastercard ···· 4821 (Default)</option>
          <option>Visa ···· 2201</option>
          <option>Wallet Balance ($0 available)</option>
        </select>
      </div>
      <div class="verify-band">
        <span>🔒</span>
        <div style="font-size:.8125rem;">$840 will be charged and immediately locked in escrow. Dr. Rania Khalil will be notified to begin Phase 3. Funds will only release upon your approval.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('fund-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('fund-modal').classList.add('hidden')">Lock $840 in Escrow</button>
    </div>
  </div>
</div>

<script>
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}

document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});

function wTab(i) {
  document.querySelectorAll('.tabs .tab-item').forEach((t,j) => t.classList.toggle('active', i===j));
  for(let j=0;j<4;j++) { const el=document.getElementById('wt-'+j); if(el) el.classList.toggle('hidden',i!==j); }
}
</script>
</body>
</html>
