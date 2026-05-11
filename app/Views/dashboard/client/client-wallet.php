<?php
/**
 * Views/dashboard/client/client-wallet.php
 * Injected by WalletController::index()
 *
 * Available variables:
 *   $client       — clientProfile row  (includes org_name, tax_jurisdiction, vat_number, etc.)
 *   $summary      — [ total_spent, ytd_released, in_escrow, pending_release, frozen ]
 *   $escrow       — EscrowRecord[] with project_milestones joined
 *   $transactions — wallet_transactions[] newest-first
 *   $wallet       — wallets row (balance, pending, currency)
 *   $error        — string|null
 *   $success      — string|null
 */

// ── helpers ──────────────────────────────────────────────────
$user_name_display = htmlspecialchars(
    ($client['org_name'] ?? '') ?: ($_SESSION['user_name'] ?? 'Client')
);

// Group escrow rows by project so we can render per-project milestone breakdown
$escrowByProject = [];
foreach ($escrow as $e) {
    $pid = $e['project_id'];
    if (!isset($escrowByProject[$pid])) {
        $escrowByProject[$pid] = [
            'project_id'    => $pid,
            'project_title' => $e['project_title'],
            'niche'         => $e['niche'] ?? '',
            'status'        => $e['status'],
            'total_budget'  => 0,
            'milestones'    => [],
        ];
    }
    $escrowByProject[$pid]['total_budget']  += (float) $e['amount'];
    $escrowByProject[$pid]['milestones'][]   = $e;
    // If any milestone is disputed, flag the whole project
    if ($e['status'] === 'disputed') {
        $escrowByProject[$pid]['status'] = 'disputed';
        $escrowByProject[$pid]['dispute_ref'] = 'DSP-NX-' . $pid;
    }
}

// Fee tier logic
$lifetime     = (float) ($summary['total_spent'] ?? 0);
$feeRate      = 8.0;
$feeTierLabel = 'Bronze';
$nextTierAt   = 10000;
$nextTierFee  = 6.5;
$nextTierName = 'Silver';
if ($lifetime >= 100000) { $feeRate = 4.0;  $feeTierLabel = 'Platinum'; $nextTierAt = null; }
elseif ($lifetime >= 25000) { $feeRate = 5.5; $feeTierLabel = 'Gold';    $nextTierAt = 100000; $nextTierFee = 4.0;  $nextTierName = 'Platinum'; }
elseif ($lifetime >= 10000) { $feeRate = 6.5; $feeTierLabel = 'Silver';  $nextTierAt = 25000;  $nextTierFee = 5.5;  $nextTierName = 'Gold'; }

$tierProgress = $nextTierAt ? min(100, round(($lifetime / $nextTierAt) * 100)) : 100;

// tx icon map
$txIcons = [
    'deposit'        => ['icon' => '💳', 'cls' => 'credit'],
    'escrow_hold'    => ['icon' => '🔒', 'cls' => 'lock'],
    'escrow_release' => ['icon' => '✓',  'cls' => 'credit'],
    'platform_fee'   => ['icon' => '💸', 'cls' => 'debit'],
    'refund'         => ['icon' => '↩',  'cls' => 'credit'],
    'withdrawal'     => ['icon' => '💸', 'cls' => 'debit'],
];
$isCredit = fn(string $t) => in_array($t, ['deposit', 'escrow_release', 'refund']);

// badge class per escrow status
$escrowBadge = [
    'held'     => 'badge-pending',
    'released' => 'badge-verified',
    'disputed' => 'badge-danger',
    'refunded' => 'badge-default',
];

// niche → badge colour
$nicheBadge = [
    'Data Science'           => 'badge-gold',
    'Legal'                  => 'badge-verified',
    'Technical Translation'  => 'badge-default',
    'Financial Modeling'     => 'badge-gold',
    'Software Development'   => 'badge-verified',
];
$getNicheBadge = fn($n) => $nicheBadge[$n] ?? 'badge-default';

// milestone status helpers
$msDot   = ['paid'=>'var(--sage)','approved'=>'var(--sage)','in_progress'=>'var(--gold)','submitted'=>'var(--gold)','held'=>'var(--gold)','pending'=>'var(--border-dark)','locked'=>'var(--border-dark)','disputed'=>'var(--rust)'];
$msBadge = ['paid'=>'badge-verified','approved'=>'badge-verified','in_progress'=>'badge-pending','submitted'=>'badge-pending','held'=>'badge-pending','pending'=>'badge-default','locked'=>'badge-default','disputed'=>'badge-danger'];
$msLabel = ['paid'=>'Released','approved'=>'Released','in_progress'=>'In Progress','submitted'=>'Submitted','held'=>'In Escrow','pending'=>'🔒 Locked','locked'=>'🔒 Locked','disputed'=>'Frozen'];
?>
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
  <?php require __DIR__ . '/../../partials/topnav.php'; ?>
          <hr class="dropdown-divider">
          <form method="POST" action="/logout" style="margin:0;">
            <button class="dropdown-item" style="color:var(--rust);background:none;border:none;width:100%;text-align:left;cursor:pointer;">Sign Out</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>

<?php if (!empty($error)): ?>
<div class="alert alert-danger" style="margin:0;padding:12px 24px;background:#FDF5F4;border-bottom:1px solid var(--rust);color:var(--rust);font-size:.875rem;">
  ⚠ <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>
<?php if (!empty($success)): ?>
<div class="alert alert-success" style="margin:0;padding:12px 24px;background:#F2F9F5;border-bottom:1px solid var(--sage);color:var(--sage);font-size:.875rem;">
  ✓ <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<!-- ══ WALLET HERO ══ -->
<div class="wallet-hero">
  <div class="container">
    <div style="color:rgba(247,244,239,.45);font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;margin-bottom:20px;font-family:var(--font-body);">
      <?= htmlspecialchars($client['user_name'] ?? $user_name_display) ?>
      · Client Wallet
      <?php if (!empty($client['org_name'])): ?> · <?= htmlspecialchars($client['org_name']) ?><?php endif; ?>
    </div>
    <div class="wallet-hero-inner">
      <div class="wallet-stat">
        <div class="wallet-val">$<?= number_format((float)($summary['total_spent'] ?? 0), 0) ?></div>
        <div class="wallet-lbl">Total Spent</div>
        <div class="wallet-delta">Total lifetime spendings</div>
      </div>
      <div class="wallet-stat">
        <div class="wallet-val">$<?= number_format((float)($summary['ytd_released'] ?? 0), 0) ?></div>
        <div class="wallet-lbl">Released (YTD)</div>
        <div class="wallet-delta"><?= date('Y') ?> year-to-date</div>
      </div>
      <div class="wallet-stat">
        <div class="wallet-val">$<?= number_format((float)($summary['in_escrow'] ?? 0), 0) ?></div>
        <div class="wallet-lbl">In Escrow</div>
        <div class="wallet-delta">Across <?= count($escrowByProject) ?> active project<?= count($escrowByProject) != 1 ? 's' : '' ?></div>
      </div>
      <div class="wallet-stat">
        <div class="wallet-val">$<?= number_format((float)($summary['pending_release'] ?? 0), 0) ?></div>
        <div class="wallet-lbl">Pending Release</div>
        <div class="wallet-delta">In cooling-off period</div>
      </div>
      <div class="wallet-stat">
        <div class="wallet-val"><?php
          $frozen = (float)($summary['frozen'] ?? 0);
          echo '$' . number_format($frozen, 0);
        ?></div>
        <div class="wallet-lbl">Frozen (Dispute)</div>
        <?php if ($frozen > 0): ?>
        <div class="wallet-delta" style="color:rgba(240,100,80,.7);">Active dispute — <a href="/dispute" style="color:inherit;text-decoration:underline;">view</a></div>
        <?php else: ?>
        <div class="wallet-delta">No active disputes</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="wallet-body">

    <!-- ══ LEFT ══ -->
    <div>

      <!-- TABS -->
      <div class="tabs mb-24">
        <button class="tab-item active" onclick="wTab(0)">Escrow by Project</button>
        <button class="tab-item" onclick="wTab(1)">Transaction History</button>
        <button class="tab-item" onclick="wTab(2)">Tax &amp; Compliance</button>
      </div>

      <!-- ── TAB 0: ESCROW BY PROJECT ── -->
      <div id="wt-0">
        <div class="flex justify-between items-center mb-16">
          <h3>Escrow Breakdown</h3>
          <button class="btn btn-primary btn-sm" onclick="document.getElementById('fund-modal').classList.remove('hidden')">+ Fund Next Milestone</button>
        </div>

        <?php if (empty($escrowByProject)): ?>
        <div style="padding:40px;text-align:center;color:var(--ink-muted);">
          No active escrow. <a href="/browse-experts">Find a specialist →</a>
        </div>
        <?php else: foreach ($escrowByProject as $proj):
          $isDisputed = ($proj['status'] === 'disputed');
          // Calculate progress: count released milestones
          $total_ms   = count($proj['milestones']);
          $released   = count(array_filter($proj['milestones'], fn($m) => in_array($m['status'], ['paid','released','approved'])));
          $progressPct = $total_ms > 0 ? round(($released / $total_ms) * 100) : 0;
        ?>
        <div class="escrow-project-card" <?= $isDisputed ? 'style="border-top:3px solid var(--rust);"' : '' ?>>
          <div class="escrow-project-header" <?= $isDisputed ? 'style="background:#FDF5F4;"' : '' ?>>
            <div>
              <?php if ($isDisputed): ?>
              <span class="badge badge-danger">Dispute Active</span>
              <?php else: ?>
              <span class="badge <?= $getNicheBadge($proj['niche']) ?>"><?= htmlspecialchars($proj['niche'] ?: 'General') ?></span>
              <?php endif; ?>
              <div style="font-weight:700;font-size:.9375rem;margin-top:6px;"><?= htmlspecialchars($proj['project_title']) ?></div>
              <div class="text-xs text-muted font-mono mt-2">NX-<?= date('Y') ?>-<?= $proj['project_id'] ?></div>
            </div>
            <div style="text-align:right;">
              <div style="font-family:var(--font-mono);font-size:1.1rem;font-weight:500;<?= $isDisputed ? 'color:var(--rust);' : '' ?>">
                $<?= number_format($proj['total_budget'], 0) ?>
              </div>
              <?php if ($isDisputed): ?>
              <div class="text-xs text-muted">Frozen · Dispute</div>
              <?php else: ?>
              <div class="text-xs text-muted">Total Budget</div>
              <?php endif; ?>
            </div>
          </div>
          <div class="escrow-project-body">
            <?php if ($isDisputed): ?>
            <p class="text-sm text-muted">Funds frozen pending dispute resolution. Funds will be released or refunded per the arbitrator verdict.</p>
            <a href="/dispute" class="btn btn-danger btn-sm mt-12">View Dispute →</a>
            <?php else: ?>
            <!-- progress bar -->
            <div class="progress-bar mb-12">
              <div class="progress-fill success" style="width:<?= $progressPct ?>%;"></div>
            </div>
            <!-- milestone rows -->
            <?php foreach ($proj['milestones'] as $idx => $ms):
              $st      = $ms['status'] ?? 'pending';
              $dotClr  = $msDot[$st]   ?? 'var(--border-dark)';
              $bdgCls  = $msBadge[$st] ?? 'badge-default';
              $bdgLbl  = $msLabel[$st] ?? ucfirst($st);
              $isFaded = in_array($st, ['pending','locked']);
              $amtClr  = match(true) {
                  in_array($st, ['paid','released','approved']) => 'color:var(--sage);',
                  in_array($st, ['held','in_progress','submitted']) => 'color:var(--gold);',
                  default => ''
              };
            ?>
            <div class="escrow-phase-row">
              <div class="phase-status-dot" style="background:<?= $dotClr ?>;"></div>
              <span style="flex:1;<?= $isFaded ? 'color:var(--ink-faint);' : '' ?>">
                Phase <?= $idx + 1 ?> — <?= htmlspecialchars($ms['milestone_name']) ?>
              </span>
              <span class="badge <?= $bdgCls ?>" style="font-size:.625rem;"><?= $bdgLbl ?></span>
              <span class="escrow-phase-amount <?= $isFaded ? 'text-muted' : '' ?>" style="<?= $amtClr ?>">
                $<?= number_format((float)$ms['amount'], 0) ?>
              </span>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; endif; ?>
      </div>

      <!-- ── TAB 1: TRANSACTION HISTORY ── -->
      <div id="wt-1" class="hidden">
        <div class="flex justify-between items-center mb-16">
          <h3>Transaction History</h3>
          <a href="/wallet/transactions?export=csv" class="btn btn-outline btn-sm">⬇ Export CSV</a>
        </div>

        <?php if (empty($transactions)): ?>
        <div style="padding:40px;text-align:center;color:var(--ink-muted);">No transactions yet.</div>
        <?php else: foreach ($transactions as $tx):
          $meta   = $txIcons[$tx['type']] ?? ['icon' => '💰', 'cls' => 'lock'];
          $credit = $isCredit($tx['type']);
          $sign   = $credit ? '+' : '-';
          $amtCls = $credit ? 'credit' : 'debit';
          $label  = $tx['description'] ?: ucwords(str_replace('_', ' ', $tx['type']));
          $proj   = $tx['project_title'] ? ' · ' . htmlspecialchars($tx['project_title']) : '';
        ?>
        <div class="tx-row">
          <div class="tx-icon <?= $meta['cls'] ?>"><?= $meta['icon'] ?></div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($label) ?></div>
            <div class="text-xs text-muted"><?= $proj ?><?= date('M d, Y', strtotime($tx['created_at'])) ?></div>
          </div>
          <span class="tx-amount <?= $amtCls ?>"><?= $sign ?>$<?= number_format(abs((float)$tx['amount']), 2) ?></span>
        </div>
        <?php endforeach; endif; ?>
      </div>

      <!-- ── TAB 2: TAX & COMPLIANCE ── -->
      <div id="wt-2" class="hidden">
        <h3 class="mb-4">Tax &amp; VAT Compliance</h3>
        <p class="text-sm text-muted mb-16">Nexus calculates applicable taxes based on client and specialist jurisdictions. You are responsible for filing in your jurisdiction.</p>

        <div class="card card-sm mb-16">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">Your Tax Profile</div>
          <div class="tax-row">
            <span class="text-muted">Entity Type</span>
            <span class="font-mono"><?= htmlspecialchars($client['org_type'] ?? '—') ?></span>
          </div>
          <div class="tax-row">
            <span class="text-muted">Jurisdiction</span>
            <span class="font-mono"><?= htmlspecialchars($client['tax_jurisdiction'] ?? $client['country'] ?? '—') ?></span>
          </div>
          <div class="tax-row">
            <span class="text-muted">VAT Number</span>
            <span class="font-mono"><?= htmlspecialchars($client['vat_number'] ?? '—') ?></span>
          </div>
          <div class="tax-row">
            <span class="text-muted">Tax ID</span>
            <span class="font-mono"><?= htmlspecialchars($client['tax_id'] ?? '—') ?></span>
          </div>
          <div class="tax-row">
            <span class="text-muted">Currency</span>
            <span class="font-mono"><?= htmlspecialchars($wallet['currency'] ?? 'USD') ?></span>
          </div>
        </div>

        <?php
          // Compute YTD figures from transactions
          $ytd_fees    = 0;
          $ytd_escrow  = 0;
          $ytd_release = 0;
          $currentYear = date('Y');
          foreach ($transactions as $tx) {
              if (substr($tx['created_at'], 0, 4) !== $currentYear) continue;
              $amt = abs((float)$tx['amount']);
              if ($tx['type'] === 'platform_fee')   $ytd_fees    += $amt;
              if ($tx['type'] === 'escrow_hold')     $ytd_escrow  += $amt;
              if ($tx['type'] === 'escrow_release')  $ytd_release += $amt;
          }
        ?>
        <div class="card card-sm">
          <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;"><?= $currentYear ?> Tax Summary (YTD)</div>
          <div class="tax-row">
            <span>Total Platform Fees Paid</span>
            <span class="font-mono">$<?= number_format($ytd_fees, 2) ?></span>
          </div>
          <div class="tax-row">
            <span>Total Escrow Locked</span>
            <span class="font-mono">$<?= number_format($ytd_escrow, 2) ?></span>
          </div>
          <div class="tax-row">
            <span>Total Released to Specialists</span>
            <span class="font-mono">$<?= number_format($ytd_release, 2) ?></span>
          </div>
          <hr class="divider" style="margin:8px 0;">
          <div class="tax-row" style="font-weight:700;">
            <span>Total Specialist Payments (YTD)</span>
            <span class="font-mono">$<?= number_format((float)($summary['ytd_released'] ?? 0), 2) ?></span>
          </div>
          <div class="text-xs text-muted mt-8" style="line-height:1.5;">
            Tax obligations vary by jurisdiction. Please consult your local tax authority or accountant.
            <?php if ($client['vat_number']): ?>
            VAT invoices are available per transaction in the export.
            <?php endif; ?>
          </div>
        </div>
      </div><!-- /wt-2 -->
    </div><!-- /left -->

    <!-- ══ RIGHT SIDEBAR ══ -->
    <div>

      <!-- PAYMENT METHODS -->
      <div class="card mb-16">
        <h4 style="font-size:.9rem;margin-bottom:14px;">Payment Methods</h4>
        <!-- Static cards — replace with dynamic payment_methods[] once that model exists -->
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
          <a href="/profile/edit" style="color:inherit;text-decoration:underline;">Manage payment methods →</a>
        </div>
      </div>

      <!-- WALLET BALANCE CARD (live from DB) -->
      <div class="card mb-16">
        <h4 style="font-size:.9rem;margin-bottom:10px;">Wallet Balance</h4>
        <div style="font-family:var(--font-display);font-size:2rem;font-weight:300;margin-bottom:4px;">
          $<?= number_format((float)($wallet['balance'] ?? 0), 2) ?>
        </div>
        <div class="text-xs text-muted mb-12"><?= htmlspecialchars($wallet['currency'] ?? 'USD') ?> available balance</div>
        <?php if ((float)($wallet['pending'] ?? 0) > 0): ?>
        <div class="text-xs" style="color:var(--gold);margin-bottom:12px;">
          + $<?= number_format((float)$wallet['pending'], 2) ?> pending release
        </div>
        <?php endif; ?>
        <button class="btn btn-outline btn-sm" style="width:100%;"
                onclick="document.getElementById('topup-modal').classList.remove('hidden')">
          + Top Up Wallet
        </button>
      </div>

      <!-- PLATFORM FEE TIER (dynamic) -->
      <div class="card">
        <h4 style="font-size:.9rem;margin-bottom:10px;">Platform Fee Tier</h4>
        <div style="font-family:var(--font-display);font-size:1.8rem;font-weight:300;margin-bottom:4px;"><?= $feeRate ?>%</div>
        <div class="text-xs text-muted mb-12">Current commission rate (<?= $feeTierLabel ?> Tier)</div>
        <?php if ($nextTierAt): ?>
        <div class="progress-bar mb-8"><div class="progress-fill" style="width:<?= $tierProgress ?>%;"></div></div>
        <div class="text-xs text-muted mb-12">
          $<?= number_format($lifetime, 0) ?> / $<?= number_format($nextTierAt, 0) ?> lifetime spend
          to <?= $nextTierName ?> Tier (<?= $nextTierFee ?>%)
        </div>
        <?php else: ?>
        <div class="text-xs text-muted mb-12">Maximum tier reached 🎉</div>
        <?php endif; ?>
        <div style="font-size:.8125rem;background:var(--ivory-deep);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 14px;">
          <?php
          $tiers = [
              ['label'=>'Bronze (New)',       'threshold'=>0,      'rate'=>'8%',   'current'=>($feeTierLabel==='Bronze')],
              ['label'=>'Silver ($10K+)',     'threshold'=>10000,  'rate'=>'6.5%', 'current'=>($feeTierLabel==='Silver')],
              ['label'=>'Gold ($25K+)',       'threshold'=>25000,  'rate'=>'5.5%', 'current'=>($feeTierLabel==='Gold')],
              ['label'=>'Platinum ($100K+)',  'threshold'=>100000, 'rate'=>'4%',   'current'=>($feeTierLabel==='Platinum')],
          ];
          foreach ($tiers as $i => $t): ?>
          <div class="flex justify-between <?= $i < count($tiers)-1 ? 'mb-4' : '' ?>"
               <?= $t['current'] ? 'style="font-weight:700;"' : '' ?>>
            <span <?= !$t['current'] ? 'class="text-muted"' : '' ?>><?= $t['label'] ?></span>
            <span class="font-mono <?= $t['current'] ? 'text-gold' : '' ?>"><?= $t['rate'] ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div><!-- /right -->
  </div><!-- /wallet-body -->
</div><!-- /container -->

<!-- ══ FUND MILESTONE MODAL ══ -->
<div id="fund-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Fund Next Milestone</h3>
        <?php
          // Find next pending/locked milestone across all projects
          $nextMs = null; $nextProj = null;
          foreach ($escrowByProject as $proj) {
              foreach ($proj['milestones'] as $ms) {
                  if (in_array($ms['status'] ?? '', ['pending','locked'])) {
                      $nextMs = $ms; $nextProj = $proj; break 2;
                  }
              }
          }
        ?>
        <p class="text-sm text-muted mt-4">
          <?php if ($nextMs): ?>
            Lock escrow for <strong><?= htmlspecialchars($nextMs['milestone_name']) ?></strong>
            — <?= htmlspecialchars($nextProj['project_title']) ?>
          <?php else: ?>
            All milestones are currently funded or no active projects.
          <?php endif; ?>
        </p>
      </div>
      <button class="modal-close" onclick="document.getElementById('fund-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <?php if ($nextMs): ?>
      <div class="card card-sm mb-16" style="background:var(--ivory-deep);">
        <div class="text-xs text-muted mb-4">Milestone to Fund</div>
        <div style="font-weight:700;"><?= htmlspecialchars($nextMs['milestone_name']) ?></div>
        <div style="font-family:var(--font-mono);font-size:1.2rem;font-weight:500;margin-top:8px;">
          $<?= number_format((float)$nextMs['amount'], 2) ?>
        </div>
      </div>
      <form method="POST" action="/wallet/fund">
        <input type="hidden" name="project_id"   value="<?= $nextMs['project_id'] ?>">
        <input type="hidden" name="milestone_id" value="<?= $nextMs['id'] ?>">
        <input type="hidden" name="amount"        value="<?= $nextMs['amount'] ?>">
        <div class="form-group">
          <label class="form-label">Payment Method</label>
          <select class="form-control" name="payment_method">
            <option value="card_default">Mastercard ···· 4821 (Default)</option>
            <option value="card_secondary">Visa ···· 2201</option>
            <option value="wallet">Wallet Balance ($<?= number_format((float)($wallet['balance'] ?? 0), 2) ?> available)</option>
          </select>
        </div>
        <div class="verify-band">
          <span>🔒</span>
          <div style="font-size:.8125rem;">
            $<?= number_format((float)$nextMs['amount'], 2) ?> will be charged and immediately locked in escrow.
            The specialist will be notified to begin work. Funds release only upon your approval.
          </div>
        </div>
        <div class="modal-footer" style="padding:0;margin-top:16px;">
          <button type="button" class="btn btn-outline" onclick="document.getElementById('fund-modal').classList.add('hidden')">Cancel</button>
          <button type="submit" class="btn btn-primary">Lock $<?= number_format((float)$nextMs['amount'], 2) ?> in Escrow</button>
        </div>
      </form>
      <?php else: ?>
      <p class="text-muted text-sm">No upcoming milestones to fund at this time.</p>
      <div class="modal-footer" style="padding:0;margin-top:16px;">
        <button class="btn btn-outline" onclick="document.getElementById('fund-modal').classList.add('hidden')">Close</button>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ══ TOP-UP MODAL ══ -->
<div id="topup-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <div>
        <h3>Top Up Wallet</h3>
        <p class="text-sm text-muted mt-4">Add funds to your Nexus wallet balance.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('topup-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <form method="POST" action="/wallet/fund">
        <input type="hidden" name="type" value="deposit">
        <input type="hidden" name="project_id" value="">
        <div class="form-group">
          <label class="form-label">Amount (<?= htmlspecialchars($wallet['currency'] ?? 'USD') ?>)</label>
          <input type="number" name="amount" class="form-control" min="10" step="0.01" placeholder="0.00" required>
        </div>
        <div class="form-group">
          <label class="form-label">Payment Method</label>
          <select class="form-control" name="payment_method">
            <option value="card_default">Mastercard ···· 4821 (Default)</option>
            <option value="card_secondary">Visa ···· 2201</option>
          </select>
        </div>
        <div class="modal-footer" style="padding:0;margin-top:16px;">
          <button type="button" class="btn btn-outline" onclick="document.getElementById('topup-modal').classList.add('hidden')">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Funds</button>
        </div>
      </form>
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
  for(let j=0;j<3;j++) { const el=document.getElementById('wt-'+j); if(el) el.classList.toggle('hidden',i!==j); }
}
</script>
</body>
</html>