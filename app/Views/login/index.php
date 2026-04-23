<?php
$errors      = $errors ?? ['login' => '', 'signup' => ''];
$active_form = $active_form ?? 'login';
function showError($e){ return !empty($e) ? '<p class="error-msg">'.$e.'</p>' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — Nexus</title>
<link rel="stylesheet" href="assets/style.css">
<style>
.auth-shell {
  min-height: 100vh;
  display: grid;
  grid-template-columns: 1fr 1fr;
}
.auth-panel-left {
  background: var(--ink);
  padding: 60px 64px;
  display: flex; flex-direction: column; justify-content: space-between;
  position: relative; overflow: hidden;
}
.auth-panel-left::before {
  content: '';
  position: absolute; inset: 0;
  background: repeating-linear-gradient(-45deg, transparent, transparent 60px, rgba(201,168,76,0.04) 60px, rgba(201,168,76,0.04) 61px);
  pointer-events: none;
}
.auth-logo { font-family: var(--font-display); font-size: 2rem; color: var(--ivory); }
.auth-logo span { color: var(--gold); }
.auth-left-body { position: relative; z-index: 1; }
.auth-headline {
  font-family: var(--font-display); font-size: 2.8rem; font-weight: 300;
  color: var(--ivory); line-height: 1.15; margin-bottom: 20px;
}
.auth-headline em { color: var(--gold); font-style: italic; }
.auth-sub { color: rgba(247,244,239,.6); font-size: .9375rem; margin-bottom: 40px; line-height: 1.75; }
.auth-feature {
  display: flex; align-items: flex-start; gap: 14px;
  margin-bottom: 20px;
}
.auth-feature-icon {
  width: 36px; height: 36px; border-radius: 50%;
  border: 1px solid rgba(201,168,76,.4);
  display: flex; align-items: center; justify-content: center;
  color: var(--gold); flex-shrink: 0;
}
.auth-feature-text { color: rgba(247,244,239,.75); font-size: .875rem; line-height: 1.6; }
.auth-feature-text strong { color: var(--ivory); }
.auth-quote {
  border-top: 1px solid rgba(247,244,239,.1);
  padding-top: 24px; margin-top: 40px;
}
.auth-quote p { font-family: var(--font-display); font-style: italic; font-size: 1rem; color: rgba(247,244,239,.7); margin-bottom: 10px; }
.auth-quote cite { font-size: .8rem; color: var(--gold); font-style: normal; font-family: var(--font-mono); }
.auth-panel-right {
  background: var(--ivory);
  display: flex; align-items: center; justify-content: center;
  padding: 60px 64px;
}
.auth-form-box { width: 100%; max-width: 420px; }
.auth-tabs { display: flex; border-bottom: 1.5px solid var(--border); margin-bottom: 32px; }
.auth-tab {
  flex: 1; padding: 12px; text-align: center;
  font-size: .8125rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
  color: var(--ink-muted); border-bottom: 2px solid transparent; margin-bottom: -1.5px;
  cursor: pointer; transition: all .15s; background: none; border-top: none; border-left: none; border-right: none;
  font-family: var(--font-body);
}
.auth-tab.active { color: var(--ink); border-bottom-color: var(--gold); }
.role-selector { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px; }
.role-card {
  border: 1.5px solid var(--border);
  border-radius: var(--radius-md);
  padding: 16px;
  cursor: pointer; transition: all .15s;
  text-align: center;
}
.role-card:hover { border-color: var(--gold); background: var(--gold-pale); }
.role-card.selected { border-color: var(--gold); background: var(--gold-pale); }
.role-card-icon { margin-bottom: 8px; display: flex; justify-content: center; }
.role-card-label { font-size: .8rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--ink-mid); }
.role-card.selected .role-card-label { color: var(--ink); }
.input-icon-wrap { position: relative; }
.input-icon-wrap .form-control { padding-left: 40px; }
.input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--ink-faint); pointer-events: none; display: flex; align-items: center; }
.forgot-link { font-size: .8125rem; color: var(--ink-muted); float: right; }
.forgot-link:hover { color: var(--gold); }
.auth-bottom-note { font-size: .8rem; color: var(--ink-muted); text-align: center; margin-top: 24px; }
.auth-bottom-note a { color: var(--gold); }
.verify-badge {
  background: #EBF3EA; border: 1px solid #C5DBC2;
  border-radius: var(--radius-md); padding: 12px 16px;
  display: flex; gap: 10px; align-items: flex-start;
  margin-bottom: 20px; font-size: .8125rem; color: var(--sage);
}
.error-msg {
  background: #fff5f5; border: 1px solid #fcc;
  border-radius: var(--radius-md); padding: 10px 14px;
  color: #e53935; font-size: .8125rem; margin-bottom: 16px; text-align: center;
}
@media (max-width: 768px) {
  .auth-shell { grid-template-columns: 1fr; }
  .auth-panel-left { display: none; }
  .auth-panel-right { padding: 40px 24px; }
}
</style>
</head>
<body>

<div class="auth-shell">

  <!-- LEFT PANEL -->
  <div class="auth-panel-left">
    <a class="auth-logo" href="index.html">Nexus<span>.</span></a>
    <div class="auth-left-body">
      <h2 class="auth-headline">Professional Work,<br><em>Properly</em> Structured.</h2>
      <p class="auth-sub">Join the marketplace designed for engagements where credentials, milestones, and trust are not optional — they are foundational.</p>
      <div class="auth-feature">
        <div class="auth-feature-icon">
          <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>
        </div>
        <div class="auth-feature-text"><strong>Verified Credentials</strong><br>Every specialist undergoes multi-stage license and certification validation.</div>
      </div>
      <div class="auth-feature">
        <div class="auth-feature-icon">
          <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M240-160q-66 0-113-47t-47-113v-480h640v480q0 66-47 113t-113 47H240Zm0-80h480q33 0 56.5-23.5T800-320v-400H160v400q0 33 23.5 56.5T240-240Zm120-120h240v-80H360v80Zm0-160h240v-80H360v80ZM240-240v-480 480Z"/></svg>
        </div>
        <div class="auth-feature-text"><strong>Milestone-Secured Escrow</strong><br>Funds release only on bilateral approval. Auto-approval protects specialists from non-responsive clients.</div>
      </div>
      <div class="auth-feature">
        <div class="auth-feature-icon">
          <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="m438-452-57-57q-11-11-27.5-11T325-509q-12 12-12 28.5t12 28.5l86 86q11 11 27 11t27-11l170-170q12-12 11.5-28.5T634-593q-12-12-28.5-12T577-593L438-452ZM480-80q-83 0-141.5-58.5T280-280v-320q0-66 32-121.5T400-810l200-110 200 110q56 32 88 87.5T920-600v320q0 83-58.5 141.5T720-80H480Z"/></svg>
        </div>
        <div class="auth-feature-text"><strong>Arbitration &amp; Audit Trail</strong><br>Every action is logged. Disputes are resolved with assembled evidence, not memory.</div>
      </div>
      <div class="auth-quote">
        <p>"Nexus is the only platform where my legal credentials were treated as non-negotiable requirements, not optional additions."</p>
        <cite>— J. Moreau, International Trade Lawyer</cite>
      </div>
    </div>
    <div style="position:relative;z-index:1;">
      <p style="font-size:.75rem;font-family:var(--font-mono);color:rgba(247,244,239,.3);letter-spacing:.1em;">SECURED BY 256-BIT ENCRYPTION · KYC VERIFIED · ISO 27001</p>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="auth-panel-right">
    <div class="auth-form-box">
      <div style="margin-bottom:32px;">
        <h2 style="font-family:var(--font-display);font-size:1.8rem;font-weight:500;margin-bottom:6px;">Welcome back</h2>
        <p style="font-size:.9rem;color:var(--ink-muted);">Sign in to your Nexus account or create one below.</p>
      </div>

      <div class="auth-tabs">
        <button class="auth-tab <?= $active_form === 'login'  ? 'active' : '' ?>" onclick="showTab('login')">Sign In</button>
        <button class="auth-tab <?= $active_form === 'signup' ? 'active' : '' ?>" onclick="showTab('register')">Create Account</button>
      </div>

      <!-- LOGIN FORM -->
      <div id="tab-login" <?= $active_form !== 'login' ? 'class="hidden"' : '' ?>>
        <form action="/login" method="post">
          <?= showError($errors['login']) ?>

          <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-icon-wrap">
              <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg>
              </span>
              <input type="email" name="email" class="form-control" placeholder="you@organization.com" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" style="display:flex;justify-content:space-between;">
              Password <a href="#" class="forgot-link">Forgot password?</a>
            </label>
            <div class="input-icon-wrap">
              <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm296.5-143.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg>
              </span>
              <input type="password" name="password" class="form-control" placeholder="••••••••••" required>
            </div>
          </div>

          <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
            <input type="checkbox" id="remember" name="remember" style="accent-color:var(--gold);">
            <label for="remember" style="font-size:.875rem;color:var(--ink-mid);">Remember me</label>
          </div>

          <button type="submit" name="login" class="btn btn-primary w-full" style="justify-content:center;">Sign In to Nexus</button>
        </form>
      </div>

      <!-- REGISTER FORM -->
      <div id="tab-register" <?= $active_form !== 'signup' ? 'class="hidden"' : '' ?>>
        <form action="/signup" method="post">
          <?= showError($errors['signup']) ?>

          <div class="verify-badge">
            <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor" style="flex-shrink:0;margin-top:1px"><path d="M480-80q-83 0-141.5-58.5T280-280v-320q0-66 32-121.5T400-810l200-110 200 110q56 32 88 87.5T920-600v320q0 83-58.5 141.5T720-80H480Z"/></svg>
            <div>All accounts undergo identity verification before accessing the platform marketplace.</div>
          </div>

          <div class="form-group">
            <label class="form-label">I am joining as</label>
            <div class="role-selector">
              <div class="role-card selected" onclick="selectRole(this,'Client')">
                <div class="role-card-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M120-120v-560h160v-160h400v320h160v400H520v-160h-80v160H120Zm80-80h80v-80h-80v80Zm0-160h80v-80h-80v80Zm0-160h80v-80h-80v80Zm160 160h80v-80h-80v80Zm0-160h80v-80h-80v80Zm0-160h80v-80h-80v80Zm160 320h80v-80h-80v80Zm0-160h80v-80h-80v80Zm0-160h80v-80h-80v80Zm160 480h80v-80h-80v80Zm0-160h80v-80h-80v80Z"/></svg>
                </div>
                <div class="role-card-label">Client / Org</div>
              </div>
              <div class="role-card" onclick="selectRole(this,'Freelancer')">
                <div class="role-card-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
                </div>
                <div class="role-card-label">Specialist</div>
              </div>
            </div>
            <input type="hidden" name="role" id="role-input" value="Client" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">First Name</label>
              <input type="text" name="fname" class="form-control" placeholder="Amira" required>
            </div>
            <div class="form-group">
              <label class="form-label">Last Name</label>
              <input type="text" name="lname" class="form-control" placeholder="Tawfik">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Professional Email</label>
            <div class="input-icon-wrap">
              <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg>
              </span>
              <input type="email" name="email" class="form-control" placeholder="amira@company.com" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-icon-wrap">
              <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm296.5-143.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg>
              </span>
              <input type="password" name="password" class="form-control" placeholder="Min. 10 characters" required>
            </div>
            <p class="form-hint">Must include uppercase, number, and symbol.</p>
          </div>

          <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <div class="input-icon-wrap">
              <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm296.5-143.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg>
              </span>
              <input type="password" name="confirm_password" class="form-control" placeholder="••••••••••" required>
            </div>
          </div>

          <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:24px;">
            <input type="checkbox" id="terms" name="terms" style="accent-color:var(--gold);margin-top:3px;" required>
            <label for="terms" style="font-size:.8125rem;color:var(--ink-mid);">I agree to Nexus's <a href="platform-guide.html#terms" style="color:var(--gold);">Terms of Service</a> and acknowledge the <a href="platform-guide.html#kyc" style="color:var(--gold);">KYC Verification Policy</a>.</label>
          </div>

          <button type="submit" name="signup" class="btn btn-primary w-full" style="justify-content:center;">Create Account &amp; Begin Verification</button>
        </form>
      </div>

      <p class="auth-bottom-note">Need help? <a href="platform-guide.html#support">Contact support</a></p>
    </div>
  </div>

</div>

<script>
function showTab(t) {
  document.getElementById('tab-login').classList.toggle('hidden', t !== 'login');
  document.getElementById('tab-register').classList.toggle('hidden', t !== 'register');
  document.querySelectorAll('.auth-tab').forEach((el,i) => el.classList.toggle('active', (i===0&&t==='login')||(i===1&&t==='register')));
}
function selectRole(el, role) {
  document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  document.getElementById('role-input').value = role;
}
</script>
</body>
</html>