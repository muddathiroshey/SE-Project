<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages — Nexus</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .chat-shell {
      display: flex;
      height: calc(100vh - var(--nav-h));
      overflow: hidden;
    }

    .chat-list {
      width: 300px;
      border-right: 1px solid var(--border);
      overflow-y: auto;
      flex-shrink: 0;
      background: var(--ivory-card);
    }

    .chat-list-header {
      padding: 20px 20px 14px;
      border-bottom: 1px solid var(--border);
    }

    .chat-list-header h3 {
      font-size: 1rem;
      margin-bottom: 10px;
    }

    .chat-search {
      position: relative;
    }

    .chat-search input {
      width: 100%;
      padding: 8px 12px 8px 32px;
      background: var(--ivory-deep);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-family: var(--font-body);
      font-size: .8125rem;
      color: var(--ink);
      outline: none;
    }

    .chat-search input:focus {
      border-color: var(--gold);
    }

    .chat-search-icon {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--ink-faint);
      font-size: .75rem;
    }

    .chat-item {
      padding: 14px 20px;
      border-bottom: 1px solid var(--border);
      cursor: pointer;
      transition: background .12s;
    }

    .chat-item:hover {
      background: var(--ivory-deep);
    }

    .chat-item.active {
      background: var(--ivory-deep);
      border-left: 3px solid var(--gold);
    }

    .chat-item-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 4px;
    }

    .chat-item-name {
      font-weight: 700;
      font-size: .875rem;
    }

    .chat-item-time {
      font-size: .6875rem;
      color: var(--ink-faint);
      font-family: var(--font-mono);
    }

    .chat-item-preview {
      font-size: .8125rem;
      color: var(--ink-muted);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .chat-item-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 4px;
    }

    .chat-item-project {
      font-size: .625rem;
      font-family: var(--font-mono);
      color: var(--ink-faint);
      text-transform: uppercase;
      letter-spacing: .06em;
    }

    .unread-count {
      background: var(--gold);
      color: var(--ink);
      border-radius: 10px;
      font-size: .625rem;
      font-weight: 700;
      padding: 1px 6px;
      font-family: var(--font-mono);
    }

    .chat-safroom-badge {
      background: #FBEAE7;
      color: var(--rust);
      border: 1px solid #F0C4BC;
      border-radius: 2px;
      font-size: .625rem;
      font-weight: 700;
      padding: 2px 6px;
      text-transform: uppercase;
      letter-spacing: .06em;
    }

    .chat-main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .chat-topbar {
      padding: 16px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--ivory-card);
      flex-shrink: 0;
    }

    .chat-topbar-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .chat-topbar-name {
      font-weight: 700;
      font-size: .9375rem;
    }

    .chat-topbar-sub {
      font-size: .75rem;
      color: var(--ink-muted);
    }

    .chat-topbar-actions {
      display: flex;
      gap: 8px;
    }

    .chat-messages {
      flex: 1;
      overflow-y: auto;
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      background: var(--ivory);
    }

    .chat-bubble-wrap {
      display: flex;
      flex-direction: column;
    }

    .chat-bubble-wrap.out {
      align-items: flex-end;
    }

    .chat-bubble-wrap.in {
      align-items: flex-start;
    }

    .chat-sender-info {
      font-size: .6875rem;
      color: var(--ink-faint);
      margin-bottom: 4px;
      font-family: var(--font-mono);
    }

    .chat-bubble {
      max-width: 68%;
      padding: 12px 16px;
      border-radius: 12px;
      font-size: .9rem;
      line-height: 1.6;
      position: relative;
    }

    .chat-bubble.out {
      background: var(--ink);
      color: var(--ivory);
      border-radius: 12px 12px 2px 12px;
    }

    .chat-bubble.in {
      background: var(--ivory-card);
      color: var(--ink);
      border: 1px solid var(--border);
      border-radius: 12px 12px 12px 2px;
    }

    .chat-bubble.system {
      background: var(--gold-pale);
      color: var(--ink-mid);
      border: 1px solid var(--gold-light);
      border-radius: var(--radius-md);
      max-width: 85%;
      text-align: center;
      font-size: .8125rem;
      padding: 10px 18px;
    }

    .chat-bubble.saferoom {
      background: #FDF3E0;
      color: #6B4800;
      border: 1px solid #F0D899;
      border-radius: var(--radius-md);
      max-width: 85%;
      font-size: .8125rem;
      padding: 12px 18px;
    }

    .chat-bubble-time {
      font-size: .6875rem;
      color: var(--ink-faint);
      margin-top: 4px;
      font-family: var(--font-mono);
    }

    .chat-bubble.out+.chat-bubble-time {
      text-align: right;
    }

    .chat-attachment {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(247, 244, 239, .2);
      border: 1px solid rgba(247, 244, 239, .2);
      border-radius: 10px;
      padding: 10px 12px;
      margin-top: 10px;
      font-size: .8125rem;
      width: 100%;
      box-sizing: border-box;
      flex-wrap: wrap;
    }

    .chat-bubble.out .chat-attachment {
      background: rgba(255, 255, 255, .08);
      border-color: rgba(255, 255, 255, .12);
    }

    .chat-attachment.in-att {
      background: var(--ivory-deep);
      border-color: var(--border);
    }

    .attachment-icon {
      font-size: 1rem;
    }

    .attachment-name {
      font-weight: 600;
    }

    .attachment-size {
      color: var(--ink-faint);
      font-size: .75rem;
    }

    .chat-input-bar {
      padding: 16px 24px;
      border-top: 1px solid var(--border);
      background: var(--ivory-card);
      flex-shrink: 0;
    }

    .chat-input-toolbar {
      display: flex;
      gap: 6px;
      margin-bottom: 10px;
    }

    .chat-input-tool {
      padding: 8px 16px;
      font-size: .8125rem;
      font-weight: 700;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: var(--ivory-deep);
      color: var(--ink-mid);
      cursor: pointer;
      font-family: var(--font-body);
      transition: all .12s;
    }

    .chat-input-tool:hover {
      border-color: var(--gold);
      color: var(--ink);
    }

    .chat-file-preview {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: var(--ivory-deep);
      margin-bottom: 10px;
    }

    .chat-file-preview-info {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .chat-file-preview-name {
      font-weight: 700;
    }

    .chat-file-preview-meta {
      font-size: .75rem;
      color: var(--ink-faint);
    }

    .chat-file-remove {
      border: none;
      background: transparent;
      color: var(--rust);
      cursor: pointer;
      font-size: .85rem;
      padding: 0;
    }

    .chat-input-row {
      display: flex;
      gap: 10px;
      align-items: flex-end;
      width: 100%;
    }

    .chat-input-area {
      flex: 1;
    }

    .chat-textarea {
      width: 100%;
      padding: 12px 18px;
      background: var(--ivory-deep);
      border: 1.5px solid var(--border);
      border-radius: 20px;
      font-family: var(--font-body);
      font-size: .9375rem;
      color: var(--ink);
      resize: none;
      outline: none;
      min-height: 48px;
      max-height: 240px;
    }

    .chat-textarea:focus {
      border-color: var(--gold);
      background: var(--ivory-card);
    }

    .chat-send-btn {
      width: 44px;
      height: 44px;
      background: var(--ink);
      color: var(--ivory);
      border: none;
      border-radius: var(--radius-sm);
      cursor: pointer;
      font-size: 1rem;
      transition: background .15s;
      flex-shrink: 0;
    }

    .chat-send-btn:hover {
      background: var(--ink-mid);
    }

    .chat-detail-panel {
      width: 260px;
      border-left: 1px solid var(--border);
      background: var(--ivory-card);
      overflow-y: auto;
      flex-shrink: 0;
    }

    .chat-detail-section {
      padding: 20px;
      border-bottom: 1px solid var(--border);
    }

    .chat-detail-label {
      font-size: .65rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      font-weight: 700;
      color: var(--ink-muted);
      margin-bottom: 10px;
    }

    .saferoom-banner {
      background: #FBE9E7;
      border-bottom: 2px solid var(--rust);
      padding: 10px 20px;
      display: flex;
      gap: 10px;
      align-items: center;
      font-size: .8125rem;
      color: var(--rust);
    }

    .saferoom-icon {
      font-size: 1rem;
    }

    .milestone-quick {
      padding: 8px 12px;
      background: var(--ivory-deep);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      margin-bottom: 6px;
      font-size: .8125rem;
    }

    .milestone-quick-name {
      font-weight: 700;
      margin-bottom: 2px;
    }

    .milestone-quick-info {
      font-size: .75rem;
      color: var(--ink-muted);
      display: flex;
      gap: 8px;
    }

    .date-separator {
      text-align: center;
      font-size: .6875rem;
      font-family: var(--font-mono);
      color: var(--ink-faint);
      margin: 8px 0;
      position: relative;
    }

    .date-separator::before,
    .date-separator::after {
      content: '';
      position: absolute;
      top: 50%;
      width: 40%;
      height: 1px;
      background: var(--border);
    }

    .date-separator::before {
      left: 0;
    }

    .date-separator::after {
      right: 0;
    }
  </style>
</head>

<body>

  <nav class="topnav">
    <div class="container">
      <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
      <div class="topnav-links">
        <a href="dashboard-client.html">Dashboard</a>
      </div>
      <div class="topnav-actions">
        <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">🔔 <span
            class="notif-count" style="position:absolute;top:2px;right:2px;">4</span></a>
        <div class="dropdown">
          <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
            <div class="avatar-badge">
              <div class="avatar avatar-sm">AT</div>
            </div>
            <span style="font-size:.875rem;font-weight:700;">Amira T.</span>
            <span style="color:var(--ink-faint);">▾</span>
          </div>
          <div class="dropdown-menu hidden" id="user-dd">
            <div class="dropdown-item"
              style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">
              Specialist Account</div>
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

      <div class="chat-item active">
        <div class="chat-item-top">
          <div class="flex items-center gap-8">
            <div class="avatar-badge">
              <div class="avatar avatar-sm">DR</div>
            </div>
            <span class="chat-item-name">Dr. Rania Khalil</span>
          </div>
          <span class="chat-item-time">11:42</span>
        </div>
        <div class="chat-item-preview">I'll have the model comparison ready by tonight.</div>
        <div class="chat-item-meta">
          <span class="chat-item-project">NX-2025-3812</span>
          <span class="unread-count">2</span>
        </div>
      </div>

      <div class="chat-item">
        <div class="chat-item-top">
          <div class="flex items-center gap-8">
            <div class="avatar avatar-sm">JM</div>
            <span class="chat-item-name">James Moreau</span>
          </div>
          <span class="chat-item-time">Yesterday</span>
        </div>
        <div class="chat-item-preview">NDA has been signed. I'll begin the review Monday.</div>
        <div class="chat-item-meta">
          <span class="chat-item-project">NX-2025-4821</span>
        </div>
      </div>

      <div class="chat-item">
        <div class="chat-item-top">
          <div class="flex items-center gap-8">
            <div class="avatar avatar-sm">KA</div>
            <span class="chat-item-name">Karim Al-Azzawi</span>
          </div>
          <span class="chat-item-time">Apr 11</span>
        </div>
        <div class="chat-item-preview">Thank you for the invitation. I've reviewed the requirements…</div>
        <div class="chat-item-meta">
          <span class="chat-item-project">NX-2025-5102</span>
        </div>
      </div>

      <div class="chat-item">
        <div class="chat-item-top">
          <div class="flex items-center gap-8">
            <div class="avatar avatar-sm" style="background:var(--ink);color:var(--ivory);">NX</div>
            <span class="chat-item-name">Nexus Support</span>
          </div>
          <span class="chat-item-time">Apr 9</span>
        </div>
        <div class="chat-item-preview">Your escrow of $3,360 has been locked for Phase 2.</div>
        <div class="chat-item-meta">
          <span class="chat-item-project">System</span>
        </div>
      </div>
    </div>

    <!-- CHAT MAIN -->
    <div class="chat-main">

      <!-- TOPBAR -->
      <div class="chat-topbar">
        <div class="chat-topbar-left">
          <div class="avatar-badge">
            <div class="avatar avatar-md">DR</div>
          </div>
          <div>
            <div class="chat-topbar-name">Dr. Rania Khalil</div>
            <div class="chat-topbar-sub">Predictive Churn Model · NX-2025-3812 · <span style="color:var(--sage);">●
                Online</span></div>
          </div>
        </div>
        <div class="chat-topbar-actions">
          <a href="project-detail.html" class="btn btn-outline btn-sm">Project Info</a>
        </div>
      </div>

      <!-- MESSAGES AREA -->
      <div class="chat-messages" id="chat-messages">

        <div class="date-separator">Apr 14, 2025</div>

        <div style="text-align:center;">
          <div class="chat-bubble system">
            🛡 This conversation is encrypted and archived per your project NDA. All messages are admissible as evidence
            in any dispute proceedings.
          </div>
        </div>

        <div style="text-align:center;">
          <div class="chat-bubble system">
            ✦ NDA signed by both parties — Apr 3, 2025 · Ref: NDA-NX-3812-2025
          </div>
        </div>

        <div class="chat-bubble-wrap in">
          <div class="chat-sender-info">Dr. Rania Khalil · 09:14</div>
          <div class="chat-bubble in">Good morning Amira. I've completed the SHAP analysis for the first deliverable of
            Phase 2. I'm attaching the report — please let me know if you'd like the full Jupyter Notebook as well.
            <div class="chat-attachment in-att">
              <span class="attachment-icon">📄</span>
              <div>
                <div class="attachment-name">SHAP_Feature_Report_v1.pdf</div>
                <div class="attachment-size">2.4 MB · PDF</div>
              </div>
              <button class="btn btn-ghost btn-sm" style="margin-left:auto;">Download</button>
            </div>
          </div>
        </div>

        <div class="chat-bubble-wrap out">
          <div class="chat-sender-info">You · 10:02</div>
          <div class="chat-bubble out">Thank you Rania — I've reviewed it quickly and the feature importance ranking
            makes a lot of sense with what we know about our customer behaviour. Could you walk me through the top 3
            features during our call tomorrow?</div>
        </div>

        <div class="chat-bubble-wrap in">
          <div class="chat-sender-info">Dr. Rania Khalil · 10:18</div>
          <div class="chat-bubble in">Absolutely. I'll prepare a brief slide to make it easy to share with your
            stakeholders. Also — I noticed the dataset has some missing values in the "contract_duration" column. I'd
            like to propose an imputation approach. Happy to discuss.</div>
        </div>

        <div class="chat-bubble-wrap out">
          <div class="chat-sender-info">You · 10:45</div>
          <div class="chat-bubble out">That makes sense. Yes, let's discuss it tomorrow. What time works for you? I'm
            thinking 10 AM Cairo time.</div>
        </div>

        <div class="chat-bubble-wrap in">
          <div class="chat-sender-info">Dr. Rania Khalil · 11:01</div>
          <div class="chat-bubble in">10 AM works perfectly. I'll send a calendar invite shortly.</div>
        </div>

        <div style="text-align:center;">
          <div class="chat-bubble system">
            📅 Technical interview scheduled — Apr 15, 10:00 AM GMT+2 · Added by Dr. Rania Khalil
          </div>
        </div>

        <div class="date-separator">Apr 15, 2025 — Today</div>

        <div class="chat-bubble-wrap in">
          <div class="chat-sender-info">Dr. Rania Khalil · 09:52</div>
          <div class="chat-bubble in">Good morning. Ready for the call. Sending the notebook link ahead of time so you
            can follow along.
            <div class="chat-attachment in-att">
              <span class="attachment-icon">📓</span>
              <div>
                <div class="attachment-name">phase2_model_comparison_draft.ipynb</div>
                <div class="attachment-size">8.4 MB · Jupyter Notebook</div>
              </div>
              <button class="btn btn-ghost btn-sm" style="margin-left:auto;">Open</button>
            </div>
          </div>
        </div>

        <div class="chat-bubble-wrap out">
          <div class="chat-sender-info">You · 11:38</div>
          <div class="chat-bubble out">Great call. The XGBoost performance is impressive — 0.87 AUC on the hold-out set.
            Let's go with that approach. When do you expect the full Phase 2 submission?</div>
        </div>

        <div class="chat-bubble-wrap in">
          <div class="chat-sender-info">Dr. Rania Khalil · 11:42</div>
          <div class="chat-bubble in">I'll have the model comparison ready by tonight — the cross-validation report will
            follow tomorrow morning. Full Phase 2 submission by end of Apr 17, well ahead of the Apr 19 deadline.</div>
        </div>

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
            <textarea class="chat-textarea" rows="1" placeholder="Type a message…" id="msg-input"
              onkeydown="handleEnter(event)"></textarea>
          </div>
          <button class="chat-send-btn" type="button" onclick="sendMessage()" style="margin-left:auto;">➤</button>
        </div>
      </div>

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