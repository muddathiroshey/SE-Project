<?php


$initials = strtoupper(substr($_SESSION['user_name'] ?? 'SP', 0, 2));
$userName = htmlspecialchars($_SESSION['user_name'] ?? 'Specialist');
$unread   = (int) ($_SESSION['notif_unread'] ?? 0);

// Niche → icon + CSS class map
$nicheIcon = [
    'Data Science'          => ['icon' => '🧠', 'cls' => 'ji-data'],
    'Legal'                 => ['icon' => '⚖️',  'cls' => 'ji-legal'],
    'Technical Translation' => ['icon' => '🌐', 'cls' => 'ji-trans'],
    'Financial Modeling'    => ['icon' => '📈', 'cls' => 'ji-fin'],
    'Biomedical Research'   => ['icon' => '🔬', 'cls' => 'ji-bio'],
    'Cybersecurity Audit'   => ['icon' => '🔐', 'cls' => 'ji-cyber'],
    'Software Development'  => ['icon' => '💻', 'cls' => 'ji-data'],
    'Engineering'           => ['icon' => '⚙️',  'cls' => 'ji-fin'],
];
$getNicheIcon = fn($n) => $nicheIcon[$n] ?? ['icon' => '📋', 'cls' => 'ji-data'];

// Build query-string helper for pagination links
$queryBase = array_filter([
    'q'          => $filters['q']          ?? '',
    'niche'      => $filters['niche']      ?? '',
    'budget_min' => $filters['budget_min'] ?? '',
    'budget_max' => $filters['budget_max'] ?? '',
]);
$buildPage = fn($p) => '/browse-jobs?' . http_build_query(array_merge($queryBase, ['page' => $p]));

// Time-since helper
$timeSince = function(string $dt): string {
    $diff = time() - strtotime($dt);
    if ($diff < 3600)  return floor($diff/60)  . ' minutes ago';
    if ($diff < 86400) return floor($diff/3600) . ' hours ago';
    return floor($diff/86400) . ' days ago';
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Browse Jobs — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/browse-jobs.css">
</head>
<body>

<!-- TOPNAV -->
<nav class="topnav">
  <div class="container" style="max-width:100%;padding:0 32px;">
    <a class="topnav-logo" href="/">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="/dashboard">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;">
        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg> <?php if ($unread): ?><span class="notif-count" style="position:absolute;top:2px;right:2px;"><?= $unread ?></span><?php endif; ?>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm"><?= $initials ?></div></div>
          <span style="font-size:.875rem;font-weight:700;"><?= $userName ?></span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Freelancer Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/profile">My Profile</a>
          <a class="dropdown-item" href="/wallet">Earnings &amp; Wallet</a>
          <a class="dropdown-item" href="/profile/edit">Account Settings</a>
          <hr class="dropdown-divider">
          <form method="POST" action="/logout" style="margin:0;">
            <button class="dropdown-item" style="color:var(--rust);background:none;border:none;width:100%;text-align:left;cursor:pointer;">Sign Out</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- SEARCH HERO -->
<div class="search-hero">
  <form method="GET" action="/browse-jobs">
    <div class="container" style="max-width:100%;padding:0 32px;">
      <div class="search-bar-wrap">
        <div class="search-input-wrap">
          <span class="search-icon">🔍</span>
          <input type="text" name="q" class="search-input" id="search-q"
            placeholder="Search by keyword, skill, jurisdiction, or technology…"
            value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
        </div>
        <!-- hidden fields to preserve other filters on search -->
        <?php if (!empty($filters['niche'])): ?>
        <input type="hidden" name="niche" value="<?= htmlspecialchars($filters['niche']) ?>">
        <?php endif; ?>
        <button type="submit" class="search-btn">Search</button>
      </div>
    </div>
  </form>
</div>

<!-- BROWSE SHELL -->
<div class="browse-shell">

  <!-- FILTER PANEL -->
  <aside class="filter-panel">
    <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:16px;font-family:var(--font-body);">Filters</div>

    <!-- Niche filter — dynamic from DB -->
    <div class="fg">
      <div class="fg-title">Niche</div>
      <?php foreach ($niches as $n): ?>
      <label class="fg-check">
        <a href="<?= '/browse-jobs?' . http_build_query(array_merge($queryBase, ['niche' => $n['niche'], 'page' => 1])) ?>"
           style="color:<?= ($filters['niche'] ?? '') === $n['niche'] ? 'var(--gold)' : 'inherit' ?>;text-decoration:none;">
          <?= htmlspecialchars($n['niche']) ?> (<?= number_format((int)$n['cnt']) ?>)
        </a>
      </label>
      <?php endforeach; ?>
      <?php if (!empty($filters['niche'])): ?>
      <a href="/browse-jobs" class="text-xs" style="color:var(--rust);">✕ Clear niche</a>
      <?php endif; ?>
    </div>

    <!-- Budget filter -->
    <form method="GET" action="/browse-jobs" id="filter-form">
      <?php foreach (['q','niche'] as $k): if (!empty($filters[$k])): ?>
      <input type="hidden" name="<?= $k ?>" value="<?= htmlspecialchars($filters[$k]) ?>">
      <?php endif; endforeach; ?>
      <div class="fg">
        <div class="fg-title">Budget Range</div>
        <div style="display:flex;gap:8px;margin-bottom:8px;">
          <div style="position:relative;flex:1;">
            <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-family:var(--font-mono);font-size:.8rem;color:var(--ink-muted);">$</span>
            <input type="number" name="budget_min" class="form-control" style="padding-left:22px;font-size:.8125rem;"
              placeholder="Min" id="f-min" value="<?= htmlspecialchars($filters['budget_min'] ?? '') ?>">
          </div>
          <div style="position:relative;flex:1;">
            <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-family:var(--font-mono);font-size:.8rem;color:var(--ink-muted);">$</span>
            <input type="number" name="budget_max" class="form-control" style="padding-left:22px;font-size:.8125rem;"
              placeholder="Max" id="f-max" value="<?= htmlspecialchars($filters['budget_max'] ?? '') ?>">
          </div>
        </div>
        <button type="submit" class="btn btn-outline btn-sm w-full" style="justify-content:center;">Apply</button>
      </div>
    </form>

    <a href="/browse-jobs" class="btn btn-outline btn-sm w-full" style="justify-content:center;margin-top:8px;">Reset All Filters</a>
  </aside>

  <!-- JOB FEED -->
  <?php require __DIR__ . '/../../partials/topnav.php'; ?>
      $milestones = is_array($j['milestones'] ?? null) ? count($j['milestones']) : 0;
      $firstEscrow = $milestones > 0 && is_array($j['milestones'])
          ? (float)($j['milestones'][0]['amount'] ?? 0)
          : round((float)$j['total_budget'] / max($milestones,1));
    ?>
    <div class="job-card" id="jc-<?= (int)$j['id'] ?>">
      <div class="job-card-top">
        <div class="job-niche-icon <?= $ni['cls'] ?>"><?= $ni['icon'] ?></div>
        <div style="flex:1;min-width:0;">
          <div class="job-title">
            <a href="/job-view?id=<?= (int)$j['id'] ?>"><?= htmlspecialchars($j['project_title']) ?></a>
          </div>
          <div class="job-meta-row">
            <div class="client-mini-inline">
              <div class="org-chip"><?= $orgInit ?></div>
              <span><?= htmlspecialchars($j['client_display_name']) ?></span>
              <?php if ($j['client_verified']): ?>
              <span class="badge badge-verified badge-dot" style="font-size:.6rem;">Verified</span>
              <?php endif; ?>
            </div>
            <span>·</span>
            <span class="font-mono"><?= htmlspecialchars($j['niche']) ?></span>
            <span>·</span>
            <span>Posted <?= $postedAgo ?></span>
          </div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <div class="job-budget" style="margin-top:8px;">$<?= number_format((float)$j['total_budget'], 0) ?></div>
          <?php if ($milestones > 0): ?>
          <div class="job-budget-lbl"><?= $milestones ?> milestone<?= $milestones != 1 ? 's' : '' ?></div>
          <?php endif; ?>
        </div>
      </div>

      <p class="job-excerpt"><?= htmlspecialchars(substr($j['project_brief'], 0, 240)) ?>…</p>

      <div class="job-tags">
        <span class="badge badge-gold" style="font-size:.65rem;"><?= htmlspecialchars($j['niche']) ?></span>
        <?php if ($j['nda_type'] !== 'standard' || $j['nda_file_path']): ?>
        <span class="badge badge-default" style="font-size:.65rem;">🔏 NDA Required</span>
        <?php endif; ?>
        <?php if ($j['visibility'] === 'invite_only'): ?>
        <span class="badge badge-default" style="font-size:.65rem;">✉ Invite Only</span>
        <?php endif; ?>
      </div>

      <div class="job-bottom">
        <div>
          <div style="display:flex;gap:14px;font-size:.8rem;color:var(--ink-muted);">
            <?php if ($firstEscrow > 0): ?>
            <span class="font-mono">$<?= number_format($firstEscrow, 0) ?> first escrow</span>
            <span>·</span>
            <?php endif; ?>
            <span><?= (int)$j['bid_count'] ?> proposal<?= $j['bid_count'] != 1 ? 's' : '' ?></span>
          </div>
        </div>
        <div class="job-actions">
          <a href="/job-view?id=<?= (int)$j['id'] ?>" class="btn btn-outline btn-sm">View Details</a>
          <a href="/bid?job_id=<?= (int)$j['id'] ?>" class="btn btn-primary btn-sm">Submit Proposal</a>
        </div>
      </div>
    </div>
    <?php endforeach; endif; ?>

    <!-- PAGINATION -->
    <?php if ($pages > 1): ?>
    <div style="display:flex;justify-content:center;gap:8px;padding:24px 0;border-top:1px solid var(--border);margin-top:8px;">
      <?php if ($page > 1): ?>
      <a href="<?= $buildPage($page - 1) ?>" class="btn btn-outline btn-sm">← Prev</a>
      <?php endif; ?>
      <?php for ($p = max(1, $page-2); $p <= min($pages, $page+2); $p++): ?>
      <a href="<?= $buildPage($p) ?>" class="btn <?= $p === $page ? 'btn-primary' : 'btn-outline' ?> btn-sm"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $pages): ?>
      <a href="<?= $buildPage($page + 1) ?>" class="btn btn-outline btn-sm">Next →</a>
      <?php endif; ?>
    </div>
    <p class="text-sm text-muted" style="text-align:center;margin-top:4px;">
      Showing page <?= $page ?> of <?= $pages ?> (<?= number_format($total) ?> total)
    </p>
    <?php endif; ?>

  </div><!-- /job-feed -->
</div><!-- /browse-shell -->

<div class="toast-stack" id="toast-stack"></div>

<script>
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});
function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  const icons = {success:'✓', warn:'⚠', info:'ℹ'};
  s.innerHTML = `<div class="toast ${type==='warn'?'warning':''}"><span class="toast-icon">${icons[type]||'ℹ'}</span><div><div class="toast-title">${type==='info'?'Info':'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(()=>s.innerHTML='',4000);
}
</script>
</body>
</html>