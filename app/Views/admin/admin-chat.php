<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages — Nexus</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin-chat.css">
</head>

<body>

  <?php require __DIR__ . '/../partials/topnav.php'; ?>

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