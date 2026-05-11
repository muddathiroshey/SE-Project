<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat Support — Nexus Admin</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin-support.css">
</head>

<body>

  <nav class="topnav" style="background:var(--ink);border-bottom:1px solid rgba(247,244,239,.1);">
    <div class="container" style="max-width:100%;padding:0 32px;">
      <a class="topnav-logo" href="admin-dashboard.html" style="color:var(--ivory);">Nexus<span
          style="color:var(--gold);">.</span></a>
      <div class="topnav-links"><a href="admin-dashboard.html" style="color:rgba(247,244,239,.6);">Dashboard</a></div>
      <div class="topnav-actions">
        <div class="flex items-center gap-8">
          <div class="avatar avatar-sm"
            style="background:var(--gold);color:var(--ink);font-size:.75rem;font-weight:700;">OH</div>
          <span style="font-size:.875rem;font-weight:700;color:var(--ivory);">Omar H.</span>
          <span class="role-badge rb-super" style="font-size:.6rem;">Super Admin</span>
        </div>
      </div>
    </div>
  </nav>

  <div class="admin-shell">
    <aside class="admin-sidebar">
      <div class="admin-sidebar-section">Overview</div>
      <a class="admin-sidebar-link" href="admin-dashboard.html">📊 Health Dashboard</a>
      <div class="admin-sidebar-section">Marketplace</div>
      <a class="admin-sidebar-link" href="admin-team.html">👤 Users</a>
      <div class="admin-sidebar-section">Disputes</div>
      <a class="admin-sidebar-link" href="open-disputes.html">⚖️ Active Disputes <span class="notif-count"
          style="margin-left:auto;background:var(--rust);">4</span></a>
      <div class="admin-sidebar-section">Verifications</div>
      <a class="admin-sidebar-link" href="admin-kyc.html">🛡 KYC Queue</a>
      <div class="admin-sidebar-section">Sanctions</div>
      <a class="admin-sidebar-link" href="sanctions.html">⚠️ User Sanctions</a>
      <div class="admin-sidebar-section">Support</div>
      <a class="admin-sidebar-link active" href="admin-support.html">💬 Chat Support</a>
    </aside>

    <main class="admin-main">
      <div class="support-wrap">

        <!-- ═══ TICKET LIST ═══ -->
        <!-- PHP: $tickets = SupportTicket::where('status','open')->with('user','lastMessage')->orderByDesc('updated_at')->get() -->
        <div class="ticket-list" id="ticket-list">
          <div class="ticket-list-header">
            <div class="breadcrumb"
              style="font-family:var(--font-mono);font-size:.72rem;color:var(--ink-muted);margin-bottom:8px;">Admin
              Dashboard <span style="margin:0 6px;color:var(--ink-faint);">›</span> Support</div>
            <h2>Chat Support</h2>
            <p style="font-size:.8125rem;color:var(--ink-muted);">Users awaiting support. Click to open conversation.
            </p>
            <div class="ticket-stats">
            </div>
            <input type="text" class="ticket-search" placeholder="🔍 Search by name or topic…"
              oninput="filterTickets(this.value)">
          </div>
          <div class="ticket-items" id="ticket-items">

            <!-- PHP: foreach($tickets as $ticket): -->
            <div class="ticket-item" data-id="1" data-name="dr. rania khalil" onclick="openChat(1)">
              <div class="avatar avatar-sm" style="flex-shrink:0;">RK</div>
              <div style="flex:1;min-width:0;">
                <div class="t-name"><a href="admin-kyc-detail.html" onclick="event.stopPropagation();">Dr. Rania
                    Khalil</a><span class="t-type specialist">Specialist</span><span class="t-niche">· Data
                    Science</span></div>
                <div class="t-preview">I can't upload my certification document…</div>
              </div>
              <div style="text-align:right;flex-shrink:0;">
                <div class="t-time">2 min ago</div>
                <div class="t-unread">2</div>
              </div>
            </div>

            <div class="ticket-item" data-id="2" data-name="fincorp egypt" onclick="openChat(2)">
              <div class="avatar avatar-sm" style="flex-shrink:0;">FC</div>
              <div style="flex:1;min-width:0;">
                <div class="t-name"><a href="#" onclick="event.stopPropagation();">FinCorp Egypt</a><span
                    class="t-type client">Client</span><span class="t-niche">· Financial Services</span></div>
                <div class="t-preview">Payment not reflecting in our account balance</div>
              </div>
              <div style="text-align:right;flex-shrink:0;">
                <div class="t-time">8 min ago</div>
                <div class="t-unread">1</div>
              </div>
            </div>

            <div class="ticket-item" data-id="3" data-name="lena bergmann" onclick="openChat(3)">
              <div class="avatar avatar-sm" style="flex-shrink:0;">LB</div>
              <div style="flex:1;min-width:0;">
                <div class="t-name"><a href="#" onclick="event.stopPropagation();">Lena Bergmann</a><span
                    class="t-type specialist">Specialist</span><span class="t-niche">· Translation</span></div>
                <div class="t-preview">How do I update my portfolio samples?</div>
              </div>
              <div style="text-align:right;flex-shrink:0;">
                <div class="t-time">15 min ago</div>
              </div>
            </div>

            <div class="ticket-item" data-id="4" data-name="karim al-azzawi" onclick="openChat(4)">
              <div class="avatar avatar-sm" style="flex-shrink:0;">KA</div>
              <div style="flex:1;min-width:0;">
                <div class="t-name"><a href="#" onclick="event.stopPropagation();">Karim Al-Azzawi</a><span
                    class="t-type specialist">Specialist</span><span class="t-niche">· Cybersecurity</span></div>
                <div class="t-preview">My account was sanctioned but I didn't receive a reason</div>
              </div>
              <div style="text-align:right;flex-shrink:0;">
                <div class="t-time">22 min ago</div>
                <div class="t-unread">3</div>
              </div>
            </div>

            <div class="ticket-item" data-id="5" data-name="medgroup ksa" onclick="openChat(5)">
              <div class="avatar avatar-sm" style="flex-shrink:0;">MG</div>
              <div style="flex:1;min-width:0;">
                <div class="t-name"><a href="#" onclick="event.stopPropagation();">MedGroup KSA</a><span
                    class="t-type client">Client</span><span class="t-niche">· Healthcare</span></div>
                <div class="t-preview">Need to change the milestone structure for contract #4821</div>
              </div>
              <div style="text-align:right;flex-shrink:0;">
                <div class="t-time">35 min ago</div>
              </div>
            </div>

            <div class="ticket-item" data-id="6" data-name="thomas müller" onclick="openChat(6)">
              <div class="avatar avatar-sm" style="flex-shrink:0;">TM</div>
              <div style="flex:1;min-width:0;">
                <div class="t-name"><a href="#" onclick="event.stopPropagation();">Thomas Müller</a><span
                    class="t-type specialist">Specialist</span><span class="t-niche">· Data Science</span></div>
                <div class="t-preview">Requesting appeal for my limited ban sanction</div>
              </div>
              <div style="text-align:right;flex-shrink:0;">
                <div class="t-time">1h ago</div>
              </div>
            </div>

            <div class="ticket-item" data-id="7" data-name="sara eissa" onclick="openChat(7)">
              <div class="avatar avatar-sm" style="flex-shrink:0;">SE</div>
              <div style="flex:1;min-width:0;">
                <div class="t-name"><a href="#" onclick="event.stopPropagation();">Sara Eissa</a><span
                    class="t-type specialist">Specialist</span><span class="t-niche">· QA Review</span></div>
                <div class="t-preview">Two-factor authentication not sending SMS</div>
              </div>
              <div style="text-align:right;flex-shrink:0;">
                <div class="t-time">1h ago</div>
              </div>
            </div>

            <div class="ticket-item" data-id="8" data-name="ahmed galal" onclick="openChat(8)">
              <div class="avatar avatar-sm" style="flex-shrink:0;">AG</div>
              <div style="flex:1;min-width:0;">
                <div class="t-name"><a href="#" onclick="event.stopPropagation();">Ahmed Galal</a><span
                    class="t-type specialist">Specialist</span><span class="t-niche">· Finance</span></div>
                <div class="t-preview">Contract payment stuck in escrow for 5 days</div>
              </div>
              <div style="text-align:right;flex-shrink:0;">
                <div class="t-time">2h ago</div>
              </div>
            </div>
            <!-- PHP: endforeach -->

          </div>
        </div>

        <!-- ═══ CHAT PANEL ═══ -->
        <!-- PHP: Load messages via AJAX GET /admin/support/tickets/{id}/messages -->
        <div class="chat-panel" id="chat-panel">
          <div class="chat-panel-header">
            <div class="avatar avatar-sm" id="chat-avatar" style="flex-shrink:0;">—</div>
            <div>
              <div style="font-weight:700;" id="chat-name"><a href="#" id="chat-name-link"
                  style="color:inherit;text-decoration:none;">—</a><span class="t-type" id="chat-type"
                  style="margin-left:8px;">—</span><span class="t-niche" id="chat-niche"></span></div>
              <div class="text-xs text-muted font-mono" id="chat-topic">—</div>
            </div>
            <button class="close-chat" onclick="closeChat()">✕ Close Chat</button>
          </div>
          <div class="chat-messages" id="chat-messages">
            <!-- JS-populated -->
          </div>
          <div class="chat-input-wrap">
            <!-- PHP: POST /admin/support/tickets/{id}/reply -->
            <textarea rows="2" placeholder="Type your reply…" id="chat-reply"
              onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendReply();}"></textarea>
            <button class="btn btn-primary" onclick="sendReply()">Send</button>
          </div>
        </div>

      </div>
    </main>
  </div>

  <div class="toast-stack" id="toast-stack"></div>

  <script>
    /* ── MOCK CHAT DATA ── */
    const chats = {
      1: {
        name: 'Dr. Rania Khalil', initials: 'RK', topic: 'Document Upload Issue', type: 'specialist', niche: 'Data Science', profileUrl: 'admin-kyc-detail.html', messages: [
          { from: 'user', text: "Hi, I'm trying to upload my AWS ML certification but the system keeps rejecting it. It says file too large but it's only 890 KB.", time: '10:32' },
          { from: 'user', text: "I've tried both PDF and JPEG formats. Neither works.", time: '10:33' },
          { from: 'admin', text: "Hello Dr. Khalil, thank you for reaching out. Let me look into this for you. Can you confirm which browser you're using?", time: '10:35' },
          { from: 'user', text: "Chrome, latest version on macOS.", time: '10:36' },
        ]
      },
      2: {
        name: 'FinCorp Egypt', initials: 'FC', topic: 'Payment Issue', type: 'client', niche: 'Financial Services', profileUrl: '#', messages: [
          { from: 'user', text: "We completed contract #3847 two days ago but the payment hasn't reflected in our balance yet.", time: '09:45' },
          { from: 'admin', text: "Hi FinCorp, I can see the payment is in the escrow release queue. It should process within 24 hours.", time: '09:50' },
          { from: 'user', text: "It's been 48 hours already. This is urgent — we have payroll commitments.", time: '10:22' },
        ]
      },
      3: {
        name: 'Lena Bergmann', initials: 'LB', topic: 'Portfolio Update', type: 'specialist', niche: 'Translation', profileUrl: '#', messages: [
          { from: 'user', text: "Hello, I'd like to update my portfolio with recent translation samples. Where can I do this?", time: '10:15' },
        ]
      },
      4: {
        name: 'Karim Al-Azzawi', initials: 'KA', topic: 'Sanction Inquiry', type: 'specialist', niche: 'Cybersecurity', profileUrl: '#', messages: [
          { from: 'user', text: "I received a notification that my account has been sanctioned but there's no explanation.", time: '10:08' },
          { from: 'user', text: "I haven't violated any policies. Please review this.", time: '10:08' },
          { from: 'user', text: "This is affecting my active contracts. Please respond urgently.", time: '10:10' },
        ]
      },
      5: {
        name: 'MedGroup KSA', initials: 'MG', topic: 'Contract Modification', type: 'client', niche: 'Healthcare', profileUrl: '#', messages: [
          { from: 'user', text: "We need to restructure the milestones for contract #4821. The specialist and I both agree on the new structure.", time: '09:55' },
        ]
      },
      6: {
        name: 'Thomas Müller', initials: 'TM', topic: 'Ban Appeal', type: 'specialist', niche: 'Data Science', profileUrl: '#', messages: [
          { from: 'user', text: "I'd like to formally appeal my limited ban. The plagiarism claim was a misunderstanding — I can provide evidence that the work was original.", time: '09:30' },
        ]
      },
      7: {
        name: 'Sara Eissa', initials: 'SE', topic: '2FA Issue', type: 'specialist', niche: 'QA Review', profileUrl: '#', messages: [
          { from: 'user', text: "I can't log in because the 2FA SMS isn't arriving. I've tried multiple times.", time: '09:20' },
        ]
      },
      8: {
        name: 'Ahmed Galal', initials: 'AG', topic: 'Escrow Payment', type: 'specialist', niche: 'Finance', profileUrl: '#', messages: [
          { from: 'user', text: "Contract #5102 was marked complete 5 days ago but payment is still in escrow. The client confirmed satisfaction.", time: '08:45' },
        ]
      },
    };

    let activeChat = null;

    /* ── OPEN CHAT ── */
    function openChat(id) {
      activeChat = id;
      const data = chats[id];
      if (!data) return;
      // Collapse list
      document.getElementById('ticket-list').classList.add('collapsed');
      // Show chat
      document.getElementById('chat-panel').classList.add('open');
      document.getElementById('chat-avatar').textContent = data.initials;
      // Set name as profile link
      const nameLink = document.getElementById('chat-name-link');
      nameLink.textContent = data.name;
      nameLink.href = data.profileUrl || '#';
      // Set type tag
      const typeEl = document.getElementById('chat-type');
      typeEl.textContent = data.type === 'client' ? 'Client' : 'Specialist';
      typeEl.className = 't-type ' + data.type;
      // Set niche
      document.getElementById('chat-niche').textContent = '· ' + data.niche;
      document.getElementById('chat-topic').textContent = data.topic;
      // Mark active
      document.querySelectorAll('.ticket-item').forEach(t => t.classList.remove('active'));
      document.querySelector(`.ticket-item[data-id="${id}"]`)?.classList.add('active');
      // Remove unread badge
      const badge = document.querySelector(`.ticket-item[data-id="${id}"] .t-unread`);
      if (badge) badge.remove();
      renderMessages(data.messages);
    }

    /* ── RENDER MESSAGES ── */
    function renderMessages(msgs) {
      const box = document.getElementById('chat-messages');
      box.innerHTML = msgs.map(m => `<div class="chat-msg ${m.from === 'admin' ? 'admin' : 'user'}"><div>${m.text}</div><div class="msg-meta">${m.from === 'admin' ? '⚙ Admin · ' : ''}${m.time}</div></div>`).join('');
      box.scrollTop = box.scrollHeight;
    }

    /* ── CLOSE CHAT ── */
    function closeChat() {
      activeChat = null;
      document.getElementById('ticket-list').classList.remove('collapsed');
      document.getElementById('chat-panel').classList.remove('open');
      document.querySelectorAll('.ticket-item').forEach(t => t.classList.remove('active'));
    }

    /* ── SEND REPLY ── */
    function sendReply() {
      const input = document.getElementById('chat-reply');
      const text = input.value.trim();
      if (!text || !activeChat) return;
      // PHP: AJAX POST /admin/support/tickets/{activeChat}/reply { message: text }
      chats[activeChat].messages.push({ from: 'admin', text, time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) });
      renderMessages(chats[activeChat].messages);
      input.value = '';
      showToast('Reply sent to ' + chats[activeChat].name);
    }

    /* ── FILTER ── */
    function filterTickets(q) {
      q = q.toLowerCase();
      document.querySelectorAll('.ticket-item').forEach(t => {
        t.style.display = (t.dataset.name || '').includes(q) ? '' : 'none';
      });
    }

    /* ── TOAST ── */
    function showToast(msg, type = 'success') { const s = document.getElementById('toast-stack'); const icons = { success: '✓', warn: '⚠' }; const cls = { success: 'success', warn: 'warning' }; s.innerHTML = `<div class="toast ${cls[type] || ''}"><span class="toast-icon">${icons[type] || 'ℹ'}</span><div><div class="toast-title">${type === 'warn' ? 'Notice' : 'Done'}</div><div class="toast-body">${msg}</div></div></div>`; setTimeout(() => s.innerHTML = '', 4500); }
  </script>
</body>

</html>