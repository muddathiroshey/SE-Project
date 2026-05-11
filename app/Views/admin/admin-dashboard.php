<?php
// Expected variables from AdminController::dashboard():
// $stats           — [ active_contracts, total_escrowed, resolution_rate, verified_specialists,
//                      kyc_queue, released_this_month, avg_rating, delta_* ]
// $alerts          — array of [ type (danger|warn|info), title, body, action_label?, action_url? ]
// $niches          — array of [ name, count, pct_width, growth ]
// $recentSanctions — array of [ name, initials, niche, tier (warn|limit|ban) ]
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — Nexus</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin-dashboard.css">
</head>
<body>

  <nav class="topnav" style="background:var(--ink);border-bottom:1px solid rgba(247,244,239,.1);">
    <div class="container" style="max-width:100%;padding:0 32px;">
      <a class="topnav-logo" href="/admin" style="color:var(--ivory);">Nexus<span style="color:var(--gold);">.</span></a>
      <div class="topnav-links">
        <a href="/admin" style="color:rgba(247,244,239,.6);">Dashboard</a>
      </div>
      <div class="topnav-actions">
        <div class="flex items-center gap-8">
          <div class="avatar avatar-sm" style="background:var(--gold);color:var(--ink);font-size:.75rem;font-weight:700;"><?= strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? ''), 0, 2)) ?: 'ME' ?></div>
          <span style="font-size:.875rem;font-weight:700;color:var(--ivory);"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Me') ?></span>
          <span class="role-badge rb-super" style="font-size:.6rem;"><?= htmlspecialchars(($_SESSION['role'] ?? 'Account') . ' Account') ?></span>
        </div>
      </div>
    </div>
  </nav>

  <div class="admin-shell">
    <aside class="admin-sidebar">
      <div class="admin-sidebar-section">Overview</div>
      <a class="admin-sidebar-link active" href="/admin">📊 Health Dashboard</a>
      <div class="admin-sidebar-section">Marketplace</div>
      <a class="admin-sidebar-link" href="/admin/users">👤 Users</a>
      <div class="admin-sidebar-section">Disputes</div>
      <a class="admin-sidebar-link" href="/admin/disputes">⚖️ Active Disputes
        <span class="notif-count" style="margin-left:auto;background:var(--rust);"><?= (int)($stats['open_disputes'] ?? 0) ?></span>
      </a>
      <div class="admin-sidebar-section">Verifications</div>
      <a class="admin-sidebar-link" href="/admin/kyc">🛡 KYC Queue</a>
      <div class="admin-sidebar-section">Sanctions</div>
      <a class="admin-sidebar-link" href="/admin/sanctions">⚠️ User Sanctions</a>
      <div class="admin-sidebar-section">Support</div>
      <a class="admin-sidebar-link" href="/admin/support">💬 Chat Support</a>
    </aside>

    <main class="admin-main">

      <div class="flex justify-between items-start mb-28">
        <div>
          <div class="breadcrumb" style="font-family:var(--font-mono);font-size:.72rem;color:var(--ink-muted);margin-bottom:8px;">
            Admin Console <span style="margin:0 6px;color:var(--ink-faint);">›</span> System <span style="margin:0 6px;color:var(--ink-faint);">›</span> Dashboard
          </div>
          <h2 style="font-family:var(--font-display);font-size:1.6rem;font-weight:500;margin-bottom:6px;">Health Dashboard</h2>
          <p style="font-size:.875rem;color:var(--ink-muted);">Platform overview and system alerts.</p>
        </div>
      </div>

      <!-- HEALTH METRICS -->
      <div class="grid-4 mb-32">

        <div class="health-metric green">
          <div class="stat-value" style="font-size:1.8rem;"><?= number_format($stats['active_contracts'] ?? 0) ?></div>
          <div class="stat-label">Active Contracts</div>
          <div class="stat-delta up mt-4">↑ +<?= (int)($stats['contracts_today'] ?? 0) ?> today</div>
          <div class="sparkline">
            <?php foreach ($stats['contracts_sparkline'] ?? [] as $i => $h): ?>
              <div class="spark-bar <?= $i === array_key_last($stats['contracts_sparkline']) ? 'highlight' : '' ?>" style="height:<?= (int)$h ?>%;"></div>
            <?php endforeach ?>
          </div>
        </div>

        <div class="health-metric">
          <div class="stat-value" style="font-size:1.8rem;">$<?= number_format(($stats['total_escrowed'] ?? 0) / 1000000, 1) ?>M</div>
          <div class="stat-label">Total Escrowed Value</div>
          <div class="sparkline">
            <?php foreach ($stats['escrow_sparkline'] ?? [] as $i => $h): ?>
              <div class="spark-bar <?= $i === array_key_last($stats['escrow_sparkline']) ? 'highlight' : '' ?>" style="height:<?= (int)$h ?>%;background:var(--rust);<?= $i !== array_key_last($stats['escrow_sparkline']) ? 'opacity:.5;' : '' ?>"></div>
            <?php endforeach ?>
          </div>
        </div>

        <div class="health-metric green">
          <div class="stat-value" style="font-size:1.8rem;"><?= number_format($stats['resolution_rate'] ?? 0, 1) ?>%</div>
          <div class="stat-label">Dispute Resolution Rate</div>
          <div class="stat-delta up mt-4">↑ Above <?= (int)($stats['resolution_sla'] ?? 95) ?>% SLA target</div>
          <div class="sparkline">
            <?php foreach ($stats['resolution_sparkline'] ?? [] as $i => $h): ?>
              <div class="spark-bar <?= $i === array_key_last($stats['resolution_sparkline']) ? 'highlight' : '' ?>" style="height:<?= (int)$h ?>%;background:<?= $i === array_key_last($stats['resolution_sparkline']) ? 'var(--sage)' : '#C5DBC2' ?>;"></div>
            <?php endforeach ?>
          </div>
        </div>

      </div>

      <!-- SECONDARY STATS -->
      <div class="grid-4 mb-32">
        <div class="stat-card">
          <div class="stat-value" style="font-size:1.4rem;"><?= number_format($stats['verified_specialists'] ?? 0) ?></div>
          <div class="stat-label">Verified Specialists</div>
          <div class="stat-delta up mt-4">↑ <?= (int)($stats['specialists_this_week'] ?? 0) ?> new this week</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" style="font-size:1.4rem;"><?= number_format($stats['kyc_queue'] ?? 0) ?></div>
          <div class="stat-label">KYC Queue</div>
          <div class="stat-delta mt-4" style="color:var(--ink-muted);">Avg. <?= number_format($stats['kyc_avg_days'] ?? 0, 1) ?> day process</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" style="font-size:1.4rem;">$<?= number_format(($stats['released_this_month'] ?? 0) / 1000) ?>K</div>
          <div class="stat-label">Released This Month</div>
          <div class="stat-delta up mt-4">↑ <?= (int)($stats['released_vs_last_month'] ?? 0) ?>% vs last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" style="font-size:1.4rem;"><?= number_format($stats['avg_rating'] ?? 0, 2) ?></div>
          <div class="stat-label">Avg. Platform Rating</div>
          <div class="stat-delta up mt-4">↑ <?= number_format($stats['rating_delta'] ?? 0, 2) ?> this quarter</div>
        </div>
      </div>

      <!-- ALERTS -->
      <div class="mb-32">
        <h3 class="mb-12">System Alerts</h3>
        <?php foreach ($alerts ?? [] as $alert): ?>
          <div class="alert-item <?= htmlspecialchars($alert['type']) ?>">
            <span class="alert-icon">
              <?= $alert['type'] === 'danger' ? '🔴' : ($alert['type'] === 'warn' ? '🟡' : '🔵') ?>
            </span>
            <div><strong><?= htmlspecialchars($alert['title']) ?>:</strong> <?= htmlspecialchars($alert['body']) ?></div>
            <?php if (!empty($alert['action_label'])): ?>
              <a href="<?= htmlspecialchars($alert['action_url'] ?? '#') ?>"
                 class="btn btn-sm <?= $alert['type'] === 'danger' ? 'btn-danger' : 'btn-outline' ?>"
                 style="margin-left:auto;white-space:nowrap;">
                <?= htmlspecialchars($alert['action_label']) ?>
              </a>
            <?php endif ?>
          </div>
        <?php endforeach ?>
      </div>

      <div class="grid-2 mb-32">

        <!-- NICHE PERFORMANCE -->
        <div class="card">
          <h3 class="mb-4">Niche Performance</h3>
          <p class="mb-16 text-sm text-muted">Active contracts and growth rate by discipline.</p>
          <?php foreach ($niches ?? [] as $i => $niche): ?>
            <div class="niche-row">
              <div style="width:140px;font-size:.875rem;font-weight:700;"><?= htmlspecialchars($niche['name']) ?></div>
              <div class="niche-bar-track">
                <div class="niche-bar-fill <?= $i === 0 ? 'top' : '' ?>" style="width:<?= (int)$niche['pct_width'] ?>%;"></div>
              </div>
              <div style="font-family:var(--font-mono);font-size:.8125rem;width:40px;text-align:right;"><?= (int)$niche['count'] ?></div>
              <span class="stat-delta up" style="width:40px;text-align:right;">+<?= (int)$niche['growth'] ?>%</span>
            </div>
          <?php endforeach ?>
        </div>

        <!-- USER SANCTIONS -->
        <div class="card">
          <h3 class="mb-4">Active User Sanctions</h3>
          <p class="mb-16 text-sm text-muted">Users currently under penalty or review.</p>
          <?php
          $tierClasses = ['warn' => 'sanction-warn', 'limit' => 'sanction-limit', 'ban' => 'sanction-ban'];
          $tierLabels  = ['warn' => '⚠ Warning', 'limit' => '⛔ Limited Ban', 'ban' => '⛔ Permanent Ban'];
          foreach ($recentSanctions ?? [] as $s):
          ?>
            <div class="user-flag-row">
              <div class="avatar avatar-sm"><?= htmlspecialchars($s['initials']) ?></div>
              <div style="flex:1;">
                <div style="font-weight:700;font-size:.875rem;"><?= htmlspecialchars($s['name']) ?></div>
                <div class="text-xs text-muted"><?= htmlspecialchars($s['niche']) ?></div>
              </div>
              <span class="sanction-pill <?= $tierClasses[$s['tier']] ?? '' ?>"><?= $tierLabels[$s['tier']] ?? '' ?></span>
              <a href="/admin/sanctions/<?= (int)$s['id'] ?>" class="btn btn-ghost btn-sm">Review</a>
            </div>
          <?php endforeach ?>
        </div>

      </div>
    </main>
  </div>

</body>
</html>