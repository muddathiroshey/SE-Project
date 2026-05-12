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
        <a href="/notifications" class="btn btn-ghost btn-icon" style="position:relative;">
          <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="currentColor">
            <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/>
          </svg>
          <span class="notif-count" style="position:absolute;top:2px;right:2px;"><?= htmlspecialchars($unread_count ?? 0) ?></span>
        </a>
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
            <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Specialist Account</div>
            <hr class="dropdown-divider">
            <a class="dropdown-item" href="/profile">My Profile</a>
            <a class="dropdown-item" href="/dashboard">Wallet &amp; Escrow</a>
            <a class="dropdown-item" href="profile/edit">Account Settings</a>
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

      <?php foreach ($threads as $t): ?>
      <a href="/chat?with=<?= (int) $t['partner_id'] ?>"
         class="chat-item <?= $t['partner_id'] == $with_user_id ? 'active' : '' ?>">
        <div class="chat-item-top">
          <div class="flex items-center gap-8">
            <div class="avatar-badge">
              <div class="avatar avatar-sm"><?= strtoupper(substr(htmlspecialchars($t['partner_name']), 0, 2)) ?></div>
            </div>
            <span class="chat-item-name"><?= htmlspecialchars($t['partner_name']) ?>
              <?php if ($t['unread_count'] > 0): ?>
              <span class="badge badge-unread" style="font-size:.625rem;"><?= (int) $t['unread_count'] ?></span>
              <?php endif; ?>
            </span>
          </div>
          <span class="chat-item-time"><?= date('H:i', strtotime($t['last_at'] ?? 'now')) ?></span>
        </div>
        <div class="chat-item-preview"><?= htmlspecialchars($t['preview'] ?? '') ?></div>
        <?php if (!empty($t['project_ref'])): ?>
        <div class="chat-item-meta">
          <span class="chat-item-project"><?= htmlspecialchars($t['project_ref']) ?></span>
        </div>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- CHAT MAIN -->
    <div class="chat-main">

      <!-- TOPBAR -->
      <div class="chat-topbar">
        <div class="chat-topbar-left">
          <div class="avatar-badge">
            <div class="avatar avatar-md"><?= strtoupper(substr(htmlspecialchars($partner['user_name'] ?? ($with_user_id ? 'U' : 'NM')), 0, 2)) ?></div>
          </div>
          <div>
            <div class="chat-topbar-name">
              <?php if (!empty($partner)): ?>
                <?= htmlspecialchars($partner['user_name']) ?>
              <?php else: ?>
                Select a conversation
              <?php endif; ?>
            </div>
            <div class="chat-topbar-sub">
              <?php if (!empty($partner)): ?>
                <?= htmlspecialchars($partner['user_role'] ?? '') ?>
                <?php if ($project_id): ?> · Project #<?= (int) $project_id ?><?php endif; ?>
              <?php else: ?>
                <span style="color:var(--ink-faint);">No active conversation</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="chat-topbar-actions">
          <a href="/project-detail<?= $project_id ? '?project=' . (int) $project_id : '' ?>" class="btn btn-outline btn-sm">Project Info</a>
        </div>
      </div>

      <!-- MESSAGES AREA -->
      <div class="chat-messages" id="chat-messages">
        <?php if (empty($messages) && $with_user_id): ?>
          <p class="text-muted" style="text-align:center;margin-top:40px;">Start the conversation.</p>
        <?php else: ?>
          <?php foreach ($messages as $m): ?>
          <div class="msg-row <?= $m['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received' ?>"
               data-msg-id="<?= (int) $m['id'] ?>">
            <div class="msg-bubble">
              <?= nl2br(htmlspecialchars($m['body'])) ?>
              <?php if (!empty($m['attachment_path'])): ?>
              <div class="chat-attachment">
                <span class="attachment-icon">📎</span>
                <div>
                  <div class="attachment-name"><?= htmlspecialchars($m['attachment_name'] ?? 'File') ?></div>
                </div>
                <a href="<?= htmlspecialchars($m['attachment_path']) ?>" target="_blank" rel="noopener"
                   class="btn btn-ghost btn-sm">Open</a>
              </div>
              <?php endif; ?>
            </div>
            <div class="msg-time text-xs text-muted"><?= date('H:i', strtotime($m['created_at'])) ?></div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- INPUT BAR -->
      <?php if ($with_user_id): ?>
      <div class="chat-input-bar">
        <div class="chat-input-toolbar">
          <button class="chat-input-tool" type="button" onclick="handleAttachClick()">📎 Attach File</button>
        </div>
        <div id="file-preview"></div>
        <input type="file" id="file-input" style="display:none;"
               accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip"
               onchange="handleFileInputChange(event)">
        <div class="chat-input-row">
          <div class="chat-input-area">
            <textarea class="chat-textarea" rows="1" placeholder="Type a message…" id="msg-input"
              onkeydown="handleEnter(event)"></textarea>
          </div>
          <button class="chat-send-btn" type="button" id="send-btn" onclick="sendMessage()">➤</button>
        </div>
      </div>
      <?php endif; ?>

    </div>

  </div>

  <script>
    // ── Config injected from PHP ──────────────────────────────
    const CHAT_CONFIG = {
      receiverId:  <?= (int) $with_user_id ?>,
      projectId:   <?= $project_id ? (int) $project_id : 'null' ?>,
      currentUser: <?= (int) $_SESSION['user_id'] ?>,
      csrfToken:   <?= json_encode($csrf_token) ?>,
    };

    // ── State ─────────────────────────────────────────────────
    let pendingFile   = null;
    let lastMessageId = <?= !empty($messages) ? (int) end($messages)['id'] : 0 ?>;
    let pollTimer     = null;

    // ── DOM helpers ───────────────────────────────────────────
    const msgArea   = () => document.getElementById('chat-messages');
    const msgInput  = () => document.getElementById('msg-input');
    const sendBtn   = () => document.getElementById('send-btn');

    function scrollToBottom() {
      const area = msgArea();
      area.scrollTop = area.scrollHeight;
    }

    function formatTime(dateStr) {
      const d = new Date(dateStr);
      return d.getHours() + ':' + String(d.getMinutes()).padStart(2, '0');
    }

    function appendMessage(msg) {
      // Remove placeholder if present
      const placeholder = msgArea().querySelector('.text-muted');
      if (placeholder) placeholder.remove();

      const isSent = (msg.sender_id == CHAT_CONFIG.currentUser);
      const row    = document.createElement('div');
      row.className = 'msg-row ' + (isSent ? 'sent' : 'received');
      row.dataset.msgId = msg.id;

      let bubbleInner = escapeHtml(msg.body).replace(/\n/g, '<br>');

      if (msg.attachment_path) {
        bubbleInner += `
          <div class="chat-attachment">
            <span class="attachment-icon">📎</span>
            <div><div class="attachment-name">${escapeHtml(msg.attachment_name || 'File')}</div></div>
            <a href="${escapeHtml(msg.attachment_path)}" target="_blank" rel="noopener"
               class="btn btn-ghost btn-sm">Open</a>
          </div>`;
      }

      row.innerHTML = `
        <div class="msg-bubble">${bubbleInner}</div>
        <div class="msg-time text-xs text-muted">${formatTime(msg.created_at)}</div>`;

      msgArea().appendChild(row);
      if (msg.id > lastMessageId) lastMessageId = msg.id;
      scrollToBottom();
    }

    function escapeHtml(str) {
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    // ── Send ──────────────────────────────────────────────────
    async function sendMessage() {
      const input = msgInput();
      const text  = input.value.trim();
      if (!text && !pendingFile) return;
      if (!CHAT_CONFIG.receiverId) return;

      const btn = sendBtn();
      btn.disabled = true;

      const fd = new FormData();
      fd.append('csrf_token',   CHAT_CONFIG.csrfToken);
      fd.append('receiver_id',  CHAT_CONFIG.receiverId);
      fd.append('body',         text);
      if (CHAT_CONFIG.projectId) fd.append('project_id', CHAT_CONFIG.projectId);
      if (pendingFile)           fd.append('attachment', pendingFile);

      input.value = '';
      removeAttachment();

      try {
        const res = await fetch('/chat/send', {
          method:  'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          body:    fd,
        });

        if (!res.ok) throw new Error('Send failed: ' + res.status);
        const msg = await res.json();
        appendMessage(msg);
      } catch (err) {
        console.error(err);
        // Restore input so user doesn't lose their message
        input.value = text;
        alert('Message failed to send. Please try again.');
      } finally {
        btn.disabled = false;
        input.focus();
      }
    }

    // ── Poll for new messages ─────────────────────────────────
    async function pollMessages() {
      if (!CHAT_CONFIG.receiverId) return;

      try {
        const url = `/chat/poll?with=${CHAT_CONFIG.receiverId}&since=${lastMessageId}`
          + (CHAT_CONFIG.projectId ? `&project=${CHAT_CONFIG.projectId}` : '');
        const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return;

        const msgs = await res.json();
        msgs.forEach(m => {
          // Skip messages we already rendered (our own sends come back from poll too)
          if (document.querySelector(`[data-msg-id="${m.id}"]`)) return;
          appendMessage(m);
        });
      } catch (err) {
        // Silently ignore poll errors (network blip etc.)
      }
    }

    // ── Keyboard ──────────────────────────────────────────────
    function handleEnter(e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    }

    // ── File attachment ───────────────────────────────────────
    function handleAttachClick() {
      document.getElementById('file-input').click();
    }

    function handleFileInputChange(event) {
      const file = event.target.files?.[0];
      if (!file) return;
      pendingFile = file;

      const preview  = document.getElementById('file-preview');
      const sizeMB   = (file.size / 1024 / 1024).toFixed(1);
      const typeLabel = file.type ? file.type.split('/').pop().toUpperCase() : 'File';

      preview.innerHTML = `
        <div class="chat-file-preview">
          <div class="attachment-icon">📎</div>
          <div class="chat-file-preview-info">
            <div class="chat-file-preview-name">${escapeHtml(file.name)}</div>
            <div class="chat-file-preview-meta">${sizeMB} MB · ${typeLabel}</div>
          </div>
          <button class="chat-file-remove" type="button" onclick="removeAttachment()">Remove</button>
        </div>`;

      msgInput().focus();
    }

    function removeAttachment() {
      pendingFile = null;
      document.getElementById('file-preview').innerHTML = '';
      document.getElementById('file-input').value = '';
    }

    // ── Dropdown ──────────────────────────────────────────────
    function toggleDD() {
      document.getElementById('user-dd').classList.toggle('hidden');
    }
    document.addEventListener('click', e => {
      if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
    });

    // ── Init ──────────────────────────────────────────────────
    scrollToBottom();

    if (CHAT_CONFIG.receiverId) {
      // Poll every 3 seconds
      pollTimer = setInterval(pollMessages, 3000);
    }
  </script>

</body>
</html>