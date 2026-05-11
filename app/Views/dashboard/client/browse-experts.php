<?php
/**
 * Views/dashboard/client/browse-experts.php
 * Injected by JobController::browseExperts()
 *
 * $experts  — specialistProfiles rows (with user_name, is_verified, skills_csv)
 * $total    — int
 * $pages    — int
 * $page     — int
 * $filters  — ['niche','q','verified_only']
 * $niches   — [['niche'=>'…','cnt'=>n], …]
 * $client   — clientProfile row
 */

$initials = strtoupper(substr($_SESSION['user_name'] ?? 'CL', 0, 2));
$userName = htmlspecialchars($_SESSION['user_name'] ?? 'Client');
$unread   = (int) ($_SESSION['notif_unread'] ?? 0);

$queryBase  = array_filter([
    'q'            => $filters['q']            ?? '',
    'niche'        => $filters['niche']        ?? '',
    'verified'     => $filters['verified_only'] ?? '',
]);
$buildPage  = fn($p) => '/browse-experts?' . http_build_query(array_merge($queryBase, ['page' => $p]));

// Star rating helper (★ display)
$stars = function(float $r): string {
    $full  = floor($r);
    $half  = ($r - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    return str_repeat('★', $full) . str_repeat('½', $half) . str_repeat('☆', $empty);
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Browse Experts — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/browse-experts.css">
</head>
<body>

<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="/">Nexus<span>.</span></a>
    <div class="topnav-links"><a href="/dashboard">Dashboard</a></div>
    <div class="topnav-actions">
      <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;"><svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg>
        <?php if ($unread): ?><span class="notif-count" style="position:absolute;top:2px;right:2px;"><?= $unread ?></span><?php endif; ?>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm"><?= $initials ?></div></div>
          <span style="font-size:.875rem;font-weight:700;"><?= $userName ?></span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Client Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/profile">My Profile</a>
          <a class="dropdown-item" href="/wallet">Wallet &amp; Escrow</a>
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

<div style="padding:40px 0;" class="container">

  <!-- SEARCH HERO -->
  <div class="mb-32">
    <h2 style="font-family:var(--font-display);font-size:2rem;font-weight:300;margin-bottom:16px;">Find a Verified Specialist</h2>
    <form method="GET" action="/browse-experts" style="display:flex;gap:10px;">
      <?php if (!empty($filters['niche'])): ?>
      <input type="hidden" name="niche" value="<?= htmlspecialchars($filters['niche']) ?>">
      <?php endif; ?>
      <div style="flex:1;position:relative;">
        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--ink-faint);">🔍</span>
        <input type="text" name="q" class="form-control" style="padding-left:40px;font-size:1rem;height:48px;"
          placeholder="e.g. &quot;Machine Learning Engineer with NLP experience&quot;"
          value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
      </div>
      <button type="submit" class="btn btn-primary" style="height:48px;padding:0 28px;">Search</button>
    </form>
  </div>

  <div style="display:flex;">

    <!-- FILTER SIDEBAR -->
    <aside class="filter-sidebar">
      <div class="filter-group">
        <div class="filter-group-title">Niche Category</div>
        <?php foreach ($niches as $n): ?>
        <label class="filter-check">
          <a href="<?= '/browse-experts?' . http_build_query(array_merge($queryBase, ['niche' => $n['niche'], 'page' => 1])) ?>"
             style="color:<?= ($filters['niche'] ?? '') === $n['niche'] ? 'var(--gold)' : 'inherit' ?>;text-decoration:none;">
            <?= htmlspecialchars($n['niche']) ?> (<?= number_format((int)$n['cnt']) ?>)
          </a>
        </label>
        <?php endforeach; ?>
        <?php if (!empty($filters['niche'])): ?>
        <a href="/browse-experts" class="text-xs" style="color:var(--rust);">✕ Clear</a>
        <?php endif; ?>
      </div>

      <div class="filter-group">
        <div class="filter-group-title">Verification</div>
        <label class="filter-check">
          <a href="<?= '/browse-experts?' . http_build_query(array_merge($queryBase, ['verified' => 1, 'page' => 1])) ?>"
             style="color:<?= !empty($filters['verified_only']) ? 'var(--gold)' : 'inherit' ?>;text-decoration:none;">
            Verified only
          </a>
        </label>
      </div>

      <a href="/browse-experts" class="btn btn-outline btn-sm w-full" style="justify-content:center;">Reset Filters</a>
    </aside>

    <!-- EXPERT RESULTS -->
    <div class="expert-grid">
      <div class="sort-bar">
        <div class="expert-list-count">
          Showing <strong><?= number_format($total) ?></strong> specialist<?= $total != 1 ? 's' : '' ?>
          <?php if (!empty($filters['niche'])): ?>
          in <strong><?= htmlspecialchars($filters['niche']) ?></strong>
          <?php endif; ?>
        </div>
      </div>

      <div style="display:grid;gap:16px;">

        <?php if (empty($experts)): ?>
        <div style="text-align:center;padding:60px 0;color:var(--ink-muted);">
          <div style="font-size:2.5rem;margin-bottom:16px;">🔍</div>
          <div style="font-weight:600;margin-bottom:8px;">No specialists found</div>
          <div class="text-sm"><a href="/browse-experts">Clear filters</a> to see all specialists.</div>
        </div>
        <?php else: foreach ($experts as $e):
          $eInit  = strtoupper(substr($e['user_name'], 0, 2));
          $skills = array_filter(array_map('trim', explode(',', $e['skills_csv'] ?? '')));
          $rating = (float)($e['rating_avg'] ?? 0);
          $projects = (int)($e['project_number'] ?? 0);
        ?>
        <div class="expert-card">
          <div class="expert-meta">
            <div class="avatar-badge">
              <?php if (!empty($e['avatar_path'])): ?>
              <img src="<?= htmlspecialchars($e['avatar_path']) ?>" class="avatar avatar-lg" alt="<?= htmlspecialchars($e['user_name']) ?>">
              <?php else: ?>
              <div class="avatar avatar-lg"><?= $eInit ?></div>
              <?php endif; ?>
            </div>
            <div style="flex:1;">
              <div class="expert-name">
                <?= htmlspecialchars($e['user_name']) ?>
                <?php if ($e['is_verified']): ?>
                <span class="badge badge-verified badge-dot" style="font-size:.65rem;margin-left:6px;">Verified</span>
                <?php endif; ?>
              </div>
              <div class="expert-title"><?= htmlspecialchars($e['primary_niche']) ?><?php if (!empty($e['country'])): ?> · <?= htmlspecialchars($e['country']) ?><?php endif; ?></div>
              <?php if ($rating > 0): ?>
              <div class="flex items-center gap-8 flex-wrap">
                <div class="stars"><?= $stars($rating) ?></div>
                <span class="text-xs text-muted"><?= number_format($rating, 2) ?><?php if ($projects > 0): ?> · <?= $projects ?> project<?= $projects != 1 ? 's' : '' ?><?php endif; ?></span>
              </div>
              <?php endif; ?>
            </div>
            <?php if (!empty($e['hourly_rate'])): ?>
            <div style="text-align:right;flex-shrink:0;">
              <div style="font-family:var(--font-mono);font-size:1rem;font-weight:500;">$<?= number_format((float)$e['hourly_rate'], 0) ?>/hr</div>
            </div>
            <?php endif; ?>
          </div>

          <?php if (!empty($e['summary'])): ?>
          <p class="expert-bio"><?= htmlspecialchars(substr($e['summary'], 0, 280)) ?><?= strlen($e['summary']) > 280 ? '…' : '' ?></p>
          <?php endif; ?>

          <?php if ($skills): ?>
          <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <?php foreach (array_slice($skills, 0, 6) as $skill): ?>
            <span class="tag"><?= htmlspecialchars($skill) ?></span>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <div class="expert-footer">
            <div style="display:flex;gap:8px;">
              <?php if ($projects > 0): ?>
              <span class="text-xs text-muted font-mono"><?= $projects ?> completed project<?= $projects != 1 ? 's' : '' ?></span>
              <?php endif; ?>
            </div>
            <div style="display:flex;gap:8px;">
              <a href="/profile?user_id=<?= (int)$e['user_id'] ?>" class="btn btn-outline btn-sm">View Profile</a>
              <button class="btn btn-primary btn-sm" type="button"
                onclick="openInviteModal(<?= (int)$e['user_id'] ?>, '<?= addslashes(htmlspecialchars($e['user_name'])) ?>')">
                Invite to Bid
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; endif; ?>

        <!-- PAGINATION -->
        <?php if ($pages > 1): ?>
        <div style="display:flex;justify-content:center;gap:8px;padding:16px 0;">
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
        <?php endif; ?>

      </div><!-- /grid -->
    </div><!-- /expert-grid -->
  </div>
</div>

<!-- INVITE MODAL -->
<div class="modal-overlay" id="invite-modal">
  <div class="modal-content">
    <div class="modal-header">Invite Specialist to Bid</div>
    <form method="POST" action="/invite" style="display:contents;">
      <input type="hidden" name="specialist_user_id" id="invite-user-id">
      <div class="modal-form-group">
        <label class="modal-label">Specialist</label>
        <input type="text" id="specialist-name"
          style="width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);font-family:var(--font-body);font-size:.9375rem;color:var(--ink);background:var(--ivory-deep);" readonly>
      </div>
      <div class="modal-form-group">
        <label class="modal-label">Select Project</label>
        <select name="project_id" id="project-select" class="modal-select" required>
          <option value="">Choose a project…</option>
          <?php
            // $client_projects injected by controller if available
            if (!empty($client_projects)):
              foreach ($client_projects as $cp):
          ?>
          <option value="<?= (int)$cp['id'] ?>"><?= htmlspecialchars($cp['project_title']) ?></option>
          <?php     endforeach;
            endif;
          ?>
        </select>
      </div>
      <div class="modal-form-group">
        <label class="modal-label">Invitation Message</label>
        <textarea name="message" id="invite-message" class="modal-textarea"
          placeholder="Write a brief message explaining why you'd like to invite this specialist…"></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-outline btn-sm" onclick="closeInviteModal()">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm">Send Invite</button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});
function openInviteModal(userId, name) {
  document.getElementById('invite-user-id').value = userId;
  document.getElementById('specialist-name').value = name;
  document.getElementById('project-select').value  = '';
  document.getElementById('invite-message').value  = '';
  document.getElementById('invite-modal').classList.add('show');
}
function closeInviteModal() {
  document.getElementById('invite-modal').classList.remove('show');
}
document.getElementById('invite-modal').addEventListener('click', e => {
  if (e.target.id === 'invite-modal') closeInviteModal();
});
function toggleCustomRepScore(show) {
  document.getElementById('custom-rep-score').style.display = show ? 'block' : 'none';
}
</script>
</body>
</html>