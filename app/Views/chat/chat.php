<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages — Nexus</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/chat.css">
</head>

<body>

  <nav class="topnav">
    <div class="container">
      <a class="topnav-logo" href="/">Nexus<span>.</span></a>
      <div class="topnav-links">
        <a href="/dashboard">Dashboard</a>
      </div>
      <div class="topnav-actions">
        <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;"><svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
          <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
        </svg> <span
            class="notif-count" style="position:absolute;top:2px;right:2px;"><?= htmlspecialchars($unread_count ?? 0) ?></span></a>
        <div class="dropdown">
          <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
            <div class="avatar-badge">
              <div class="avatar avatar-sm"><?php
                echo strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? ''), 0, 2)) ?: 'ME';
              ?></div>
            </div>
            <span style="font-size:.875rem;font-weight:700;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Me'); ?></span>
            <span style="color:var(--ink-faint);">▾</span>
          </div>
          <div class="dropdown-menu hidden" id="user-dd">
            <div class="dropdown-item"
              style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">
              Specialist Account</div>
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

      <?php foreach ($threads as $t): ?>
      <a href="/chat?with=<?= $t['partner_id'] ?>"
         class="chat-item <?= $t['partner_id'] == $with_user_id ? 'active' : '' ?>">
        <div class="chat-item-top">
          <div class="flex items-center gap-8">
            <div class="avatar-badge">
              <div class="avatar avatar-sm"><?= strtoupper(substr($t['partner_name'], 0, 2)) ?></div>
            </div>
            <span class="chat-item-name"><?= htmlspecialchars($t['partner_name']) ?>
              <?php if ($t['unread_count'] > 0): ?>
              <span class="badge badge-unread" style="font-size:.625rem;"><?= $t['unread_count'] ?></span>
              <?php endif; ?>
            </span>
          </div>
          <span class="chat-item-time"><?= date('H:i', strtotime($t['last_at'] ?? ($t['last_message_at'] ?? 'now'))) ?></span>
        </div>
        <div class="chat-item-preview"><?= htmlspecialchars($t['preview']) ?></div>
        <div class="chat-item-meta">
          <?php if (!empty($t['project_ref'])): ?>
          <span class="chat-item-project"><?= htmlspecialchars($t['project_ref']) ?></span>
          <?php endif; ?>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- CHAT MAIN -->
    <div class="chat-main">

      <!-- TOPBAR -->
      <div class="chat-topbar">
          <div class="chat-topbar-left">
            <div class="avatar-badge">
              <div class="avatar avatar-md"><?= strtoupper(substr(htmlspecialchars($partner['user_name'] ?? ($with_user_id ? 'User' : 'NM')), 0, 2)) ?></div>
            </div>
            <div>
              <div class="chat-topbar-name"><?php if (!empty($partner)): ?><?= htmlspecialchars($partner['user_name']) ?><?php else: ?>Select a conversation<?php endif; ?></div>
              <div class="chat-topbar-sub"><?php if (!empty($partner)): ?><?= htmlspecialchars($partner['user_role'] ?? '') ?><?php if ($project_id): ?> · Project #<?= htmlspecialchars($project_id) ?><?php endif; ?><?php else: ?><span style="color:var(--ink-faint);">No active conversation</span><?php endif; ?></div>
            </div>
          </div>
          <div class="chat-topbar-actions">
            <a href="/project-detail<?= $project_id ? '?project=' . urlencode($project_id) : '' ?>" class="btn btn-outline btn-sm">Project Info</a>
          </div>
      </div>

      <!-- MESSAGES AREA -->
      <div class="chat-messages" id="chat-messages">
        <?php if (empty($messages) && $with_user_id): ?>
        <p class="text-muted" style="text-align:center;margin-top:40px;">Start the conversation.</p>
        <?php else: ?>
        <?php foreach ($messages as $m): ?>
        <div class="msg-row <?= $m['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received' ?>">
          <div class="msg-bubble"><?= nl2br(htmlspecialchars($m['body'])) ?></div>
          <div class="msg-time text-xs text-muted"><?= date('H:i', strtotime($m['created_at'])) ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

      </div>

      <!-- INPUT BAR -->
      <form method="POST" action="/chat/send" class="chat-input-bar">
        <input type="hidden" name="receiver_id" value="<?= $with_user_id ?>">
        <?php if ($project_id): ?>
        <input type="hidden" name="project_id" value="<?= $project_id ?>">
        <?php endif; ?>
        <div class="chat-input-toolbar">
          <button class="chat-input-tool" type="button" onclick="handleAttachClick()">📎 Attach File</button>
        </div>
        <div id="file-preview"></div>
        <input type="file" id="file-input" style="display:none;" onchange="handleFileInputChange(event)">
        <div class="chat-input-row">
          <div class="chat-input-area">
            <textarea name="body" class="chat-textarea" rows="1" placeholder="Type a message…" id="msg-input"
              onkeydown="handleEnter(event)" required></textarea>
          </div>
          <button class="chat-send-btn" type="submit" style="margin-left:auto;">➤</button>
        </div>
      </form>

    </div>

   

  </div>

  <script>
    let pendingFile = null;
    function handleEnter(e) {
      if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    }
    function handleAttachClick() {
      document.getElementById('file-input').click();
    }
    function handleFileInputChange(event) {
      const file = event.target.files?.[0];
      if (!file) return;
      pendingFile = file;

      const preview = document.getElementById('file-preview');
      const sizeMB = (file.size / 1024 / 1024).toFixed(1);
      const typeLabel = file.type ? file.type.split('/').pop().toUpperCase() : 'File';

      preview.innerHTML = `
    <div class="chat-file-preview">
      <div class="attachment-icon">📎</div>
      <div class="chat-file-preview-info">
        <div class="chat-file-preview-name">${file.name}</div>
        <div class="chat-file-preview-meta">${sizeMB} MB · ${typeLabel}</div>
      </div>
      <button class="chat-file-remove" type="button" onclick="removeAttachment()">Remove</button>
    </div>
  `;
      document.getElementById('msg-input').focus();
    }
    function removeAttachment() {
      pendingFile = null;
      document.getElementById('file-preview').innerHTML = '';
      document.getElementById('file-input').value = '';
    }
    function sendMessage() {
      const input = document.getElementById('msg-input');
      const text = input.value.trim();
      if (!text && !pendingFile) return;

      const msgs = document.getElementById('chat-messages');
      const wrap = document.createElement('div');
      wrap.className = 'chat-bubble-wrap out';

      const info = document.createElement('div');
      info.className = 'chat-sender-info';
      const now = new Date();
      info.textContent = `You · ${now.getHours()}:${String(now.getMinutes()).padStart(2, '0')}`;

      const bubble = document.createElement('div');
      bubble.className = 'chat-bubble out';

      if (text) {
        bubble.appendChild(document.createTextNode(text));
      }

      if (pendingFile) {
        const attachmentDiv = document.createElement('div');
        attachmentDiv.className = 'chat-attachment in-att';

        const icon = document.createElement('span');
        icon.className = 'attachment-icon';
        icon.textContent = '📎';

        const infoBlock = document.createElement('div');
        const name = document.createElement('div');
        name.className = 'attachment-name';
        name.textContent = pendingFile.name;
        const size = document.createElement('div');
        size.className = 'attachment-size';
        const typeLabel = pendingFile.type ? pendingFile.type.split('/').pop().toUpperCase() : 'File';
        size.textContent = `${(pendingFile.size / 1024 / 1024).toFixed(1)} MB · ${typeLabel}`;
        infoBlock.appendChild(name);
        infoBlock.appendChild(size);

        const action = document.createElement('button');
        action.className = 'btn btn-ghost btn-sm';
        action.type = 'button';
        action.textContent = 'Open';

        attachmentDiv.appendChild(icon);
        attachmentDiv.appendChild(infoBlock);
        attachmentDiv.appendChild(action);

        bubble.appendChild(attachmentDiv);
      }

      wrap.appendChild(info);
      wrap.appendChild(bubble);
      msgs.appendChild(wrap);
      msgs.scrollTop = msgs.scrollHeight;

      input.value = '';
      removeAttachment();
    }
    function toggleDD() {
      document.getElementById('user-dd').classList.toggle('hidden');
    }
    document.addEventListener('click', e => {
      if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
    });
    document.getElementById('chat-messages').scrollTop = 9999;
  </script>

</body>

</html>