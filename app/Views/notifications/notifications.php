<?php

// Group by date
$grouped = [];
foreach ($notifications as $n) {
    $day = date('Y-m-d', strtotime($n['created_at']));
    $grouped[$day][] = $n;
}

// Icon / CSS-class per type
$notifMeta = [
    'message'            => ['icon' => '💬', 'cls' => 'message'],
    'dispute_opened'     => ['icon' => '⚖️',  'cls' => 'dispute'],
    'dispute_message'    => ['icon' => '⚖️',  'cls' => 'dispute'],
    'dispute_resolved'   => ['icon' => '⚖️',  'cls' => 'dispute'],
    'bid_received'       => ['icon' => '📨', 'cls' => 'bid'],
    'bid_accepted'       => ['icon' => '✅', 'cls' => 'bid'],
    'bid_rejected'       => ['icon' => '❌', 'cls' => 'bid'],
    'escrow_hold'        => ['icon' => '💰', 'cls' => 'escrow'],
    'escrow_release'     => ['icon' => '✅', 'cls' => 'escrow'],
    'milestone_approved' => ['icon' => '📋', 'cls' => 'milestone'],
    'milestone_submitted'=> ['icon' => '📋', 'cls' => 'milestone'],
    'security'           => ['icon' => '🔒', 'cls' => 'system'],
];
$getMeta = fn($type) => $notifMeta[$type] ?? ['icon' => '🔔', 'cls' => 'general'];

// Human-readable day label
$dayLabel = function(string $day): string {
    $today     = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    if ($day === $today)     return 'Today — ' . date('M j, Y');
    if ($day === $yesterday) return 'Yesterday — ' . date('M j, Y', strtotime($day));
    // Within last 7 days
    if (strtotime($day) >= strtotime('-7 days')) return 'Earlier This Week';
    return date('F j, Y', strtotime($day));
};

$userName = htmlspecialchars($_SESSION['user_name'] ?? 'Account');
$unread   = (int) $unread_count;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notifications — Nexus</title>
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/notifications.css">
</head>
<body>

<nav class="topnav center-nav">
  <div class="container">
    <a class="topnav-logo" href="/">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="/dashboard">Dashboard</a>
    </div>
  </div>
</nav>

<div class="notif-shell">

  <div class="notif-page-header">
    <div>
      <h2 style="font-family:var(--font-display);font-size:1.8rem;font-weight:500;margin-bottom:4px;">Notifications</h2>
      <p class="text-sm text-muted">
        <?= $unread ?> unread — last updated <?= date('H:i') ?> GMT+2
      </p>
    </div>
    <div style="display:flex;gap:10px;">
      <!-- Mark all read via POST (JS enhances to AJAX) -->
      <form method="POST" action="/notifications/read-all" id="mark-all-form">
        <button type="submit" class="btn btn-ghost btn-sm" id="mark-all-btn">Mark all read</button>
      </form>
      <button class="btn btn-outline btn-sm" onclick="document.getElementById('pref-modal').classList.remove('hidden')">⚙ Preferences</button>
    </div>
  </div>

  <?php if (empty($notifications)): ?>
  <div style="text-align:center;padding:60px 0;color:var(--ink-muted);">
    <div style="font-size:2.5rem;margin-bottom:16px;">🔔</div>
    <div style="font-weight:600;margin-bottom:8px;">All caught up</div>
    <div class="text-sm">No notifications yet.</div>
  </div>

  <?php else: ?>
  <?php foreach ($grouped as $day => $items): ?>
  <div class="notif-group-label"><?= $dayLabel($day) ?></div>

  <?php foreach ($items as $n):
    $meta    = $getMeta($n['type']);
    $isUnread = !(bool) $n['is_read'];
    $link    = htmlspecialchars($n['link'] ?? '');
    $time    = date('H:i', strtotime($n['created_at']));
    $fullTime = date('M d · H:i', strtotime($n['created_at']));
    // Decide action button label + href by type
    $btnLabel = 'View';
    $btnHref  = $link ?: '#';
    $btnCls   = 'btn-outline';
    if (str_starts_with($n['type'], 'message'))         { $btnLabel = 'Reply';         $btnCls = 'btn-primary'; }
    if (str_starts_with($n['type'], 'bid'))             { $btnLabel = 'Review Bid';    $btnCls = 'btn-primary'; }
    if (str_starts_with($n['type'], 'dispute'))         { $btnLabel = 'View Dispute';  $btnCls = 'btn-outline'; }
    if (str_starts_with($n['type'], 'escrow'))          { $btnLabel = 'View Wallet';   $btnCls = 'btn-outline'; }
    if (str_starts_with($n['type'], 'milestone'))       { $btnLabel = 'View Project';  $btnCls = 'btn-outline'; }
  ?>
  <div class="notif-item <?= $isUnread ? 'unread' : '' ?>" data-id="<?= (int)$n['id'] ?>">
    <div class="notif-icon <?= $meta['cls'] ?>"><?= $meta['icon'] ?></div>
    <div class="notif-body">
      <div class="notif-title"><?= htmlspecialchars($n['title']) ?></div>
      <?php if (!empty($n['body'])): ?>
      <div class="notif-desc"><?= htmlspecialchars($n['body']) ?></div>
      <?php endif; ?>
      <div class="notif-meta">
        <span class="notif-time"><?= $isUnread ? $time : $fullTime ?></span>
        <?php if ($isUnread): ?>
        <span class="badge badge-pending" style="font-size:.625rem;">Unread</span>
        <?php endif; ?>
      </div>
    </div>
    <div class="notif-actions">
      <?php if ($link): ?>
      <a href="<?= $btnHref ?>" class="btn <?= $btnCls ?> btn-sm notif-action-link"
         data-id="<?= (int)$n['id'] ?>"><?= $btnLabel ?></a>
      <?php endif; ?>
      <!-- Dismiss button posts to /notifications/dismiss -->
      <form method="POST" action="/notifications/dismiss" class="dismiss-form" style="display:inline;">
        <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
        <button type="submit" class="btn btn-ghost btn-sm">✕</button>
      </form>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endforeach; ?>

  <div style="text-align:center;padding:32px 0;">
    <button class="btn btn-outline" id="load-more-btn">Load Older Notifications</button>
  </div>
  <?php endif; ?>

</div>

<!-- ── PREFERENCES MODAL ── -->
<div id="pref-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Notification Preferences</h3>
        <p class="text-sm text-muted mt-4">Choose how and when you receive notifications.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('pref-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:12px;">In-App Notifications</div>
      <div class="pref-row"><span>Milestone updates &amp; approvals</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>New messages</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>New bids received</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>Dispute updates</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>Escrow &amp; payment events</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="pref-row"><span>Security alerts (logins, etc.)</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
      <div class="modal-footer">
        <button class="btn btn-outline" onclick="document.getElementById('pref-modal').classList.add('hidden')">Cancel</button>
        <button class="btn btn-primary" onclick="document.getElementById('pref-modal').classList.add('hidden')">Save Preferences</button>
      </div>
    </div>
  </div>
</div>

<script>
// ── Mark all read (AJAX) ──────────────────────────────────
document.getElementById('mark-all-form').addEventListener('submit', function(e) {
  e.preventDefault();
  fetch('/notifications/read-all', {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  }).then(() => {
    document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
    document.querySelectorAll('.badge-pending').forEach(el => el.remove());
    document.querySelector('.notif-count')?.remove();
    document.querySelector('.notif-page-header p').textContent = '0 unread — just now';
  });
});

// ── Mark single read on action link click ────────────────
document.querySelectorAll('.notif-action-link').forEach(link => {
  link.addEventListener('click', function() {
    const id = this.dataset.id;
    if (!id) return;
    fetch('/notifications/read', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'id=' + id
    });
    // Remove unread class immediately
    this.closest('.notif-item')?.classList.remove('unread');
  });
});

//Dismiss (AJAX) 
document.querySelectorAll('.dismiss-form').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const id   = this.querySelector('input[name=id]').value;
    const item = this.closest('.notif-item');
    fetch('/notifications/dismiss', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'id=' + id
    }).then(() => {
      item?.remove();
    });
  });
});

// Mark item read when clicked anywhere
document.querySelectorAll('.notif-item.unread').forEach(item => {
  item.addEventListener('click', function() {
    const id = this.dataset.id;
    if (!id || !this.classList.contains('unread')) return;
    this.classList.remove('unread');
    fetch('/notifications/read', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'id=' + id
    });
  });
});
</script>
</body>
</html>