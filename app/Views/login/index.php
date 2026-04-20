<?php
$errors      = $errors ?? ['login' => '', 'signup' => ''];
$active_form = $active_form ?? 'login';
function showError($e){ return !empty($e) ? '<p class="error-msg">'.$e.'</p>' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
:root {
    --green: #4CAF50;
    --green-dark: #43a047;
    --green-pale: rgba(76,175,80,0.07);
    --green-ring: rgba(76,175,80,0.18);
    --ink: #1a1a2e;
    --ivory: #f0f0e8;
    --ivory-muted: rgba(240,240,232,0.6);
    --ivory-faint: rgba(240,240,232,0.25);
    --border: #d8d8d0;
    --bg: #f7f7f3;
    --text: #1c1c1c;
    --text-mid: #555;
    --text-faint: #888;
    --red: #e53935;
    --radius: 9px;
    --radius-lg: 14px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}
.shell {
    display: grid;
    grid-template-columns: 1fr 1fr;
    width: 100%;
    max-width: 960px;
    min-height: 620px;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,0.13);
}

/* ── LEFT PANEL ── */
.left {
    background: var(--ink);
    padding: 44px 48px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}
.left::before {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(-45deg, transparent, transparent 55px, rgba(76,175,80,0.035) 55px, rgba(76,175,80,0.035) 56px);
    pointer-events: none;
}
.logo {
    font-size: 1.4rem;
    font-weight: 500;
    color: var(--ivory);
    letter-spacing: -0.02em;
    text-decoration: none;
    position: relative;
    z-index: 1;
}
.logo span { color: var(--green); }
.left-body { position: relative; z-index: 1; }
.headline {
    font-size: 1.8rem;
    font-weight: 300;
    color: var(--ivory);
    line-height: 1.2;
    margin-bottom: 12px;
}
.headline em { color: var(--green); font-style: italic; }
.sub {
    color: var(--ivory-muted);
    font-size: 0.85rem;
    line-height: 1.75;
    margin-bottom: 28px;
}
.feat { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
.feat-icon {
    width: 30px; height: 30px;
    border-radius: 50%;
    border: 1px solid rgba(76,175,80,0.4);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.feat-text { font-size: 0.8rem; color: rgba(240,240,232,0.7); line-height: 1.55; }
.feat-text strong { color: var(--ivory); font-weight: 500; display: block; margin-bottom: 1px; }
.quote-block { border-top: 1px solid rgba(240,240,232,0.1); padding-top: 18px; margin-top: 28px; }
.quote-block p { font-style: italic; font-size: 0.82rem; color: rgba(240,240,232,0.6); margin-bottom: 8px; line-height: 1.6; }
.quote-block cite { font-size: 0.73rem; color: var(--green); font-style: normal; }
.left-footer { position: relative; z-index: 1; font-size: 0.62rem; color: var(--ivory-faint); letter-spacing: 0.09em; text-transform: uppercase; }

/* ── RIGHT PANEL ── */
.right {
    background: #ffffff;
    padding: 44px 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.form-box { width: 100%; max-width: 340px; }
.form-header { margin-bottom: 24px; }
.form-header h2 { font-size: 1.4rem; font-weight: 500; color: var(--text); margin-bottom: 4px; }
.form-header p { font-size: 0.82rem; color: var(--text-faint); }

/* ── TABS ── */
.tabs { display: flex; border-bottom: 1px solid var(--border); margin-bottom: 24px; }
.tab-btn {
    flex: 1; padding: 10px; text-align: center;
    font-size: 0.72rem; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase;
    color: var(--text-faint); border: none; background: none; cursor: pointer;
    border-bottom: 2px solid transparent; margin-bottom: -1px;
    transition: color 0.15s, border-color 0.15s;
    font-family: inherit;
}
.tab-btn.active { color: var(--text); border-bottom-color: var(--green); }
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* ── FIELDS ── */
.field { margin-bottom: 13px; }
.field > label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.73rem;
    font-weight: 500;
    color: var(--text-mid);
    margin-bottom: 5px;
    letter-spacing: 0.03em;
}
.iw { position: relative; }
.iw svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); pointer-events: none; }
.iw input { padding-left: 36px !important; }
input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    height: 40px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 0 12px;
    font-size: 0.875rem;
    font-family: inherit;
    background: #fff;
    color: var(--text);
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    border-color: var(--green);
    box-shadow: 0 0 0 3px var(--green-ring);
}
input[type="text"]:hover,
input[type="email"]:hover,
input[type="password"]:hover { border-color: var(--green); }
.forgot { font-size: 0.72rem; color: var(--text-faint); text-decoration: none; font-weight: 400; }
.forgot:hover { color: var(--green); }
.hint { font-size: 0.7rem; color: var(--text-faint); margin-top: 4px; }

/* ── ROLE CARDS ── */
.role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 13px; }
.role-card {
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 13px 10px;
    cursor: pointer;
    text-align: center;
    transition: border-color 0.15s, background 0.15s;
}
.role-card:hover { border-color: var(--green); background: var(--green-pale); }
.role-card.sel { border-color: var(--green); background: var(--green-pale); }
.role-card svg { display: block; margin: 0 auto 6px; }
.role-label { font-size: 0.7rem; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; color: var(--text-faint); }
.role-card.sel .role-label { color: var(--text); }

/* ── BUTTONS ── */
.btn-primary {
    width: 100%;
    height: 40px;
    background: var(--green);
    color: #fff;
    border: none;
    border-radius: var(--radius);
    font-size: 0.875rem;
    font-weight: 500;
    font-family: inherit;
    cursor: pointer;
    transition: background 0.15s;
    margin-top: 4px;
}
.btn-primary:hover { background: var(--green-dark); }

/* ── FORM ROW ── */
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

/* ── MISC ── */
.check-row { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 16px; font-size: 0.78rem; color: var(--text-faint); }
.check-row input { margin-top: 2px; accent-color: var(--green); }
.check-row a { color: var(--green); text-decoration: none; }
.note { font-size: 0.75rem; color: var(--text-faint); text-align: center; margin-top: 16px; }
.note a { color: var(--green); text-decoration: none; }
.note a:hover { text-decoration: underline; }
.badge {
    background: rgba(76,175,80,0.07);
    border: 1px solid rgba(76,175,80,0.25);
    border-radius: var(--radius);
    padding: 10px 12px;
    display: flex;
    gap: 8px;
    align-items: flex-start;
    font-size: 0.77rem;
    color: var(--text-mid);
    margin-bottom: 14px;
    line-height: 1.5;
}
.error-msg {
    background: #fff5f5;
    border: 1px solid #fcc;
    border-radius: var(--radius);
    color: var(--red);
    font-size: 0.8rem;
    padding: 9px 12px;
    margin-bottom: 14px;
    text-align: center;
}

/* ── RESPONSIVE ── */
@media (max-width: 700px) {
    body { padding: 0; }
    .shell { grid-template-columns: 1fr; border-radius: 0; min-height: 100vh; }
    .left { display: none; }
    .right { padding: 40px 28px; align-items: flex-start; padding-top: 60px; }
}
</style>
</head>
<body>
<div class="shell">

  <!-- ── LEFT PANEL ── -->
  <div class="left">
    <a class="logo" href="#">Free<span>lance</span></a>
    <div class="left-body">
      <h2 class="headline">Work done right,<br><em>every</em> milestone.</h2>
      <p class="sub">The platform built around milestones, contracts, and verified professionals — because trust is not optional.</p>

      <div class="feat">
        <div class="feat-icon">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="feat-text"><strong>Milestone-secured escrow</strong>Funds release only on bilateral approval, protecting both sides.</div>
      </div>
      <div class="feat">
        <div class="feat-icon">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="feat-text"><strong>Verified credentials</strong>Every specialist undergoes multi-stage validation before listing.</div>
      </div>
      <div class="feat">
        <div class="feat-icon">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="feat-text"><strong>Full audit trail</strong>Every action is logged. Disputes resolved with evidence, not memory.</div>
      </div>

      <div class="quote-block">
        <p>"Finally a platform that treats contracts as non-negotiable, not an afterthought."</p>
        <cite>— A. Tawfik, Senior Consultant</cite>
      </div>
    </div>
    <p class="left-footer">Secured · KYC Verified · Escrow Protected</p>
  </div>

  <!-- ── RIGHT PANEL ── -->
  <div class="right">
    <div class="form-box">
      <div class="form-header">
        <h2>Welcome back</h2>
        <p>Sign in to your account or create one below.</p>
      </div>

      <div class="tabs">
        <button class="tab-btn <?= $active_form === 'login'  ? 'active' : '' ?>" onclick="switchTab('login')">Sign in</button>
        <button class="tab-btn <?= $active_form === 'signup' ? 'active' : '' ?>" onclick="switchTab('signup')">Create account</button>
      </div>

      <!-- LOGIN TAB -->
      <div id="tab-login" class="tab-panel <?= $active_form === 'login' ? 'active' : '' ?>">
        <form action="/login" method="post">
          <?= showError($errors['login']) ?>

          <div class="field">
            <label>Email address</label>
            <div class="iw">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
              <input type="email" name="email" placeholder="you@example.com" required>
            </div>
          </div>

          <div class="field">
            <label>
              Password
              <a href="#" class="forgot">Forgot password?</a>
            </label>
            <div class="iw">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" name="password" placeholder="••••••••" required>
            </div>
          </div>

          <div class="check-row">
            <input type="checkbox" id="rem" name="remember">
            <label for="rem">Remember me on this device</label>
          </div>

          <button class="btn-primary" type="submit" name="login">Sign in</button>
        </form>
        <p class="note">Don't have an account? <a href="#" onclick="switchTab('signup');return false;">Create one</a></p>
      </div>

      <!-- SIGNUP TAB -->
      <div id="tab-signup" class="tab-panel <?= $active_form === 'signup' ? 'active' : '' ?>">
        <form action="/signup" method="post">
          <?= showError($errors['signup']) ?>

          <div class="badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            All accounts undergo identity verification before accessing the marketplace.
          </div>

          <div class="field">
            <label>I am joining as</label>
            <div class="role-grid">
              <div class="role-card sel" onclick="selRole(this,'Client')" data-role="Client">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
                <div class="role-label">Client</div>
              </div>
              <div class="role-card" onclick="selRole(this,'Freelancer')" data-role="Freelancer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
                <div class="role-label">Freelancer</div>
              </div>
            </div>
            <input type="hidden" name="role" id="role-input" value="Client" required>
          </div>

          <div class="form-row">
            <div class="field">
              <label>First name</label>
              <input type="text" name="fname" placeholder="Amira" required>
            </div>
            <div class="field">
              <label>Last name</label>
              <input type="text" name="lname" placeholder="Tawfik">
            </div>
          </div>

          <div class="field">
            <label>Email address</label>
            <div class="iw">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
              <input type="email" name="email" placeholder="you@example.com" required>
            </div>
          </div>

          <div class="field">
            <label>Password</label>
            <div class="iw">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" name="password" placeholder="Min. 8 characters" required>
            </div>
            <p class="hint">Must include uppercase, number, and symbol.</p>
          </div>

          <div class="field">
            <label>Confirm password</label>
            <div class="iw">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><polyline points="9,16 11,18 15,14"/></svg>
              <input type="password" name="confirm_password" placeholder="Repeat password" required>
            </div>
          </div>

          <div class="check-row">
            <input type="checkbox" id="terms" required>
            <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</label>
          </div>

          <button class="btn-primary" type="submit" name="signup">Create account</button>
        </form>
        <p class="note">Already have an account? <a href="#" onclick="switchTab('login');return false;">Sign in</a></p>
      </div>

    </div>
  </div>
</div>

<script>
function switchTab(name) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    const idx = name === 'login' ? 0 : 1;
    document.querySelectorAll('.tab-btn')[idx].classList.add('active');
}
function selRole(el, role) {
    document.querySelectorAll('.role-card').forEach(c => {
        c.classList.remove('sel');
        c.querySelector('svg').setAttribute('stroke', '#aaa');
        c.querySelector('.role-label').style.color = '';
    });
    el.classList.add('sel');
    el.querySelector('svg').setAttribute('stroke', '#4CAF50');
    document.getElementById('role-input').value = role;
}
</script>
</body>
</html>