<?php
// Expected variables from controller:
// $conversations  — array of conversation objects (name, initials, preview, project, time, unread)
// $active         — the currently open conversation object
// $messages       — array of message objects (sender, initials, time, text, attachments[], direction)
// $unread_count   — int, for nav badge
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages — Nexus</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin-chat.css">
</head>
<body>

  <nav class="topnav">
    <div class="container">
      <a class="topnav-logo" href="/dashboard">Nexus<span>.</span></a>
      <div class="topnav-links">
        <a href="/dashboard">Dashboard</a>
      </div>
      <div class="topnav-actions">
        <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;">
          <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
            <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
          </svg>
          <span class="notif-count" style="position:absolute;top:2px;right:2px;"><?= (int)($unread_count ?? 0) ?></span>
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
            <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Specialist Account</div>
            <hr class="dropdown-divider">
            <a class="dropdown-item" href="/profile">My Profile</a>
            <a class="dropdown-item" href="/wallet">Wallet &amp; Escrow</a>
            <a class="dropdown-item" href="/settings">Account Settings</a>
            <hr class="dropdown-divider">
            <a class="dropdown-item" href="/logout" style="color:var(--rust);">Sign Out</a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="chat-shell">

    <!-- CONVERSATION LIST -->
    <div class="chat-list">
      <div class="chat-list-header">
        <h3>Messages</h3>
        <div class="chat-search">
          <span class="chat-search-icon">🔍</span>
          <input type="text" placeholder="Search conversations…">
        </div>
      </div>

      <?php foreach ($conversations ?? [] as $i => $conv): ?>
      <div class="chat-item <?= $i === 0 ? 'active' : '' ?>">
        <div class="chat-item-top">
          <div class="flex items-center gap-8">
            <div class="avatar-badge">
              <div class="avatar avatar-sm"><?= htmlspecialchars($conv['initials']) ?></div>
            </div>
            <span class="chat-item-name"><?= htmlspecialchars($conv['name']) ?></span>
          </div>
          <span class="chat-item-time"><?= htmlspecialchars($conv['time']) ?></span>
        </div>
        <div class="chat-item-preview"><?= htmlspecialchars($conv['preview']) ?></div>
        <div class="chat-item-meta">
          <span class="chat-item-project"><?= htmlspecialchars($conv['project']) ?></span>
          <?php if (!empty($conv['unread'])): ?>
            <span class="unread-count"><?= (int)$conv['unread'] ?></span>
          <?php endif ?>
        </div>
      </div>
      <?php endforeach ?>

    </div>

    <!-- CHAT MAIN -->
    <div class="chat-main">

      <div class="chat-topbar">
        <div class="chat-topbar-left">
          <div class="avatar-badge">
            <div class="avatar avatar-md"><?= htmlspecialchars($active['initials'] ?? '') ?></div>
          </div>
          <div>
            <div class="chat-topbar-name"><?= htmlspecialchars($active['name'] ?? '') ?></div>
            <div class="chat-topbar-sub">
              <?= htmlspecialchars($active['project_title'] ?? '') ?> ·
              <?= htmlspecialchars($active['project_ref'] ?? '') ?> ·
              <span style="color:var(--sage);">● <?= htmlspecialchars($active['status'] ?? 'Online') ?></span>
            </div>
          </div>
        </div>
        <div class="chat-topbar-actions">
          <a href="/projects/<?= (int)($active['project_id'] ?? 0) ?>" class="btn btn-outline btn-sm">Project Info</a>
        </div>
      </div>

      <!-- MESSAGES AREA -->
      <div class="chat-messages" id="chat-messages">

        <?php
        $lastDate = null;
        foreach ($messages ?? [] as $msg):
          $msgDate = date('Y-m-d', strtotime($msg['created_at']));
        ?>

          <?php if ($msgDate !== $lastDate): $lastDate = $msgDate; ?>
            <div class="date-separator">
              <?= date('M j, Y', strtotime($msg['created_at'])) ?>
              <?= $msgDate === date('Y-m-d') ? ' — Today' : '' ?>
            </div>
          <?php endif ?>

          <?php if ($msg['type'] === 'system'): ?>
            <div style="text-align:center;">
              <div class="chat-bubble system"><?= htmlspecialchars($msg['text']) ?></div>
            </div>

          <?php else: $dir = $msg['direction'] === 'out' ? 'out' : 'in'; ?>
            <div class="chat-bubble-wrap <?= $dir ?>">
              <div class="chat-sender-info">
                <?= htmlspecialchars($msg['sender_name']) ?> · <?= date('H:i', strtotime($msg['created_at'])) ?>
              </div>
              <div class="chat-bubble <?= $dir ?>">
                <?= htmlspecialchars($msg['text']) ?>

                <?php foreach ($msg['attachments'] ?? [] as $att): ?>
                  <div class="chat-attachment in-att">
                    <span class="attachment-icon">📄</span>
                    <div>
                      <div class="attachment-name"><?= htmlspecialchars($att['name']) ?></div>
                      <div class="attachment-size"><?= htmlspecialchars($att['size']) ?> · <?= htmlspecialchars($att['type']) ?></div>
                    </div>
                    <a href="/attachments/<?= (int)$att['id'] ?>/download" class="btn btn-ghost btn-sm" style="margin-left:auto;">Download</a>
                  </div>
                <?php endforeach ?>
              </div>
            </div>
          <?php endif ?>

        <?php endforeach ?>

      </div>

      <!-- INPUT BAR -->
      <div class="chat-input-bar">
        <div class="chat-input-toolbar">
          <button class="chat-input-tool" type="button" onclick="handleAttachClick()">📎 Attach File</button>
        </div>
        <div id="file-preview"></div>
        <input type="file" id="file-input" style="display:none;" onchange="handleFileInputChange(event)">
        <div class="chat-input-row">
          <div class="chat-input-area">
            <textarea class="chat-textarea" rows="1" placeholder="Type a message…" id="msg-input" onkeydown="handleEnter(event)"></textarea>
          </div>
          <button class="chat-send-btn" type="button" onclick="sendMessage()" style="margin-left:auto;">➤</button>
        </div>
      </div>

    </div>
  </div>

  <script>
    let pendingFile = null;
    function handleEnter(e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } }
    function handleAttachClick() { document.getElementById('file-input').click(); }
    function handleFileInputChange(event) {
      const file = event.target.files?.[0];
      if (!file) return;
      pendingFile = file;
      const sizeMB = (file.size / 1024 / 1024).toFixed(1);
      const typeLabel = file.type ? file.type.split('/').pop().toUpperCase() : 'File';
      document.getElementById('file-preview').innerHTML = `<div class="chat-file-preview"><div class="attachment-icon">📎</div><div class="chat-file-preview-info"><div class="chat-file-preview-name">${file.name}</div><div class="chat-file-preview-meta">${sizeMB} MB · ${typeLabel}</div></div><button class="chat-file-remove" type="button" onclick="removeAttachment()">Remove</button></div>`;
      document.getElementById('msg-input').focus();
    }
    function removeAttachment() { pendingFile = null; document.getElementById('file-preview').innerHTML = ''; document.getElementById('file-input').value = ''; }
    function sendMessage() {
      const input = document.getElementById('msg-input');
      const text = input.value.trim();
      if (!text && !pendingFile) return;
      const msgs = document.getElementById('chat-messages');
      const wrap = document.createElement('div'); wrap.className = 'chat-bubble-wrap out';
      const info = document.createElement('div'); info.className = 'chat-sender-info';
      const now = new Date(); info.textContent = `You · ${now.getHours()}:${String(now.getMinutes()).padStart(2,'0')}`;
      const bubble = document.createElement('div'); bubble.className = 'chat-bubble out';
      if (text) bubble.appendChild(document.createTextNode(text));
      if (pendingFile) {
        const ad = document.createElement('div'); ad.className = 'chat-attachment in-att';
        ad.innerHTML = `<span class="attachment-icon">📎</span><div><div class="attachment-name">${pendingFile.name}</div><div class="attachment-size">${(pendingFile.size/1024/1024).toFixed(1)} MB · ${(pendingFile.type||'').split('/').pop().toUpperCase()||'File'}</div></div><button class="btn btn-ghost btn-sm">Open</button>`;
        bubble.appendChild(ad);
      }
      wrap.appendChild(info); wrap.appendChild(bubble); msgs.appendChild(wrap);
      msgs.scrollTop = msgs.scrollHeight; input.value = ''; removeAttachment();
    }
    function toggleDD() { document.getElementById('user-dd').classList.toggle('hidden'); }
    document.addEventListener('click', e => { if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden'); });
    document.getElementById('chat-messages').scrollTop = 9999;
  </script>
</body>
</html>