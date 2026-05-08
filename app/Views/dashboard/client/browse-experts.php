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
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="dashboard-client.html">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.html" class="btn btn-ghost btn-icon">🔔</a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">AT</div></div>
          <span style="font-size:.875rem;font-weight:700;">Amira T.</span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Client Account</div>
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

<div style="padding:40px 0;" class="container">

  <!-- SEARCH HERO -->
  <div class="mb-32">
    <h2 style="font-family:var(--font-display);font-size:2rem;font-weight:300;margin-bottom:16px;">Find a Verified Specialist</h2>
    <div style="display:flex;gap:10px;">
      <div style="flex:1;position:relative;">
        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--ink-faint);">🔍</span>
        <input type="text" class="form-control" style="padding-left:40px;font-size:1rem;height:48px;" placeholder="e.g. &quot;Machine Learning Engineer with NLP experience&quot;">
      </div>   
      <button class="btn btn-primary" style="height:48px;padding:0 28px;">Search</button>
    </div>
  </div>

  <div style="display:flex;">

    <!-- FILTER SIDEBAR -->
    <aside class="filter-sidebar">

      <div class="filter-group">
        <div class="filter-group-title">Niche Category</div>
        <label class="filter-check"><input type="checkbox" checked> <label>Data Science (3,420)</label></label>
        <label class="filter-check"><input type="checkbox"> <label>Legal Consulting (1,830)</label></label>
        <label class="filter-check"><input type="checkbox"> <label>Technical Translation (980)</label></label>
        <label class="filter-check"><input type="checkbox"> <label>Financial Modelling (1,150)</label></label>
        <label class="filter-check"><input type="checkbox"> <label>Cybersecurity (740)</label></label>
        <label class="filter-check"><input type="checkbox"> <label>Biomedical (520)</label></label>
      </div>

      <div class="filter-group">
        <div class="filter-group-title">Reputation Score</div>
        <label class="filter-check"><input type="radio" name="rep" value="any" checked onchange="toggleCustomRepScore(false)"> <label>Any score</label></label>
        <label class="filter-check"><input type="radio" name="rep" value="4.5" onchange="toggleCustomRepScore(false)"> <label>4.5 and above</label></label>
        <label class="filter-check"><input type="radio" name="rep" value="4.8" onchange="toggleCustomRepScore(false)"> <label>4.8 and above (Elite)</label></label>
        <label class="filter-check"><input type="radio" name="rep" value="custom" onchange="toggleCustomRepScore(true)"> <label>Custom</label></label>
        <div id="custom-rep-score" style="display:none;margin-top:10px;">
          <input type="number" id="rep-score-input" min="0" max="5" step="0.1" placeholder="e.g. 4.6" style="width:100%;padding:8px 10px;border:1px solid var(--border);border-radius:var(--radius-sm);font-size:.8125rem;">
        </div>
      </div>

      <div class="filter-group">
        <div class="filter-group-title">Project Success Rate</div>
        <label class="filter-check"><input type="radio" name="psr" value="any" checked onchange="toggleCustomPSR(false)"> <label>Any</label></label>
        <label class="filter-check"><input type="radio" name="psr" value="80" onchange="toggleCustomPSR(false)"> <label>80%+</label></label>
        <label class="filter-check"><input type="radio" name="psr" value="90" onchange="toggleCustomPSR(false)"> <label>90%+ (Top Tier)</label></label>
        <label class="filter-check"><input type="radio" name="psr" value="custom" onchange="toggleCustomPSR(true)"> <label>Custom %</label></label>
        <div id="custom-psr" style="display:none;margin-top:10px;">
          <input type="number" id="psr-input" min="0" max="100" step="1" placeholder="e.g. 85" style="width:100%;padding:8px 10px;border:1px solid var(--border);border-radius:var(--radius-sm);font-size:.8125rem;">
        </div>
      </div>

      <button class="btn btn-outline btn-sm w-full" style="justify-content:center;">Reset Filters</button>
    </aside>

    <!-- EXPERT RESULTS -->
    <div class="expert-grid">

      <div class="sort-bar">
        <div class="expert-list-count">Showing <strong>3,420</strong> specialists</div>
      </div>

      <div style="display:grid;gap:16px;">

        <!-- EXPERT 1 -->
        <div class="expert-card">
          <div class="expert-meta">
            <div class="avatar-badge"><div class="avatar avatar-lg">DR</div></div>
            <div style="flex:1;">
              <div class="expert-name">Dr. Rania Khalil</div>
              <div class="expert-title">Senior Data Scientist · Machine Learning &amp; NLP</div>
              <div class="flex items-center gap-8 flex-wrap">
                <div class="stars">★★★★★</div>
                <span class="text-xs text-muted">4.97 · 83 projects</span>
                <span class="badge badge-verified badge-dot">Verified</span>
              </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
            </div>
          </div>
          <p class="expert-bio">PhD in Computer Science with 9 years specializing in predictive modeling, NLP for Arabic-English corpora, and production-grade ML pipelines. Expert in model interpretability for regulated industries including banking and healthcare.</p>
          <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <span class="tag">Python</span><span class="tag">PyTorch</span><span class="tag">NLP</span><span class="tag">MLFlow</span><span class="tag">Arabic NLP</span>
          </div>
          <div class="expert-footer">
            <div style="display:flex;gap:8px;">
              <span class="text-xs text-muted font-mono">92% milestone completion</span>
            </div>
            <div style="display:flex;gap:8px;">
              <a href="expert-profile.html" class="btn btn-outline btn-sm">View Profile</a>
              <button class="btn btn-primary btn-sm" type="button" onclick="openInviteModal('Dr. Rania Khalil')">Invite to Bid</button>
            </div>
          </div>
        </div>

        <!-- EXPERT 2 -->
        <div class="expert-card">
          <div class="expert-meta">
            <div class="avatar-badge"><div class="avatar avatar-lg">KA</div></div>
            <div style="flex:1;">
              <div class="expert-name">Karim Al-Azzawi</div>
              <div class="expert-title">Principal Data Engineer · MLOps &amp; Cloud Infrastructure</div>
              <div class="flex items-center gap-8 flex-wrap">
                <div class="stars">★★★★★</div>
                <span class="text-xs text-muted">4.91 · 61 projects</span>
                <span class="badge badge-verified badge-dot">Verified</span>
              </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
            </div>
          </div>
          <p class="expert-bio">Architect of large-scale data pipelines on AWS and GCP. Specializes in real-time streaming with Apache Kafka, Spark, and containerized ML deployment. Previously at IBM Data &amp; AI division for 5 years.</p>
          <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <span class="tag">Spark</span><span class="tag">Kafka</span><span class="tag">AWS</span><span class="tag">Terraform</span><span class="tag">Kubernetes</span>
          </div>
          <div class="expert-footer">
            <div style="display:flex;gap:8px;">
              <span class="text-xs text-muted font-mono">88% milestone completion</span>
            </div>
            <div style="display:flex;gap:8px;">
              <a href="expert-profile.html" class="btn btn-outline btn-sm">View Profile</a>
              <button class="btn btn-primary btn-sm" type="button" onclick="openInviteModal('Karim Al-Azzawi')">Invite to Bid</button>
            </div>
          </div>
        </div>

        <!-- EXPERT 3 -->
        <div class="expert-card">
          <div class="expert-meta">
            <div class="avatar-badge"><div class="avatar avatar-lg">SB</div></div>
            <div style="flex:1;">
              <div class="expert-name">Sofia Benedetti</div>
              <div class="expert-title">Statistical Modeller &amp; Research Scientist</div>
              <div class="flex items-center gap-8 flex-wrap">
                <div class="stars">★★★★★</div>
                <span class="text-xs text-muted">4.89 · 44 projects</span>
                <span class="badge badge-verified badge-dot">Verified</span>
              </div>
            </div>
          </div>
          <p class="expert-bio">Biostatistics PhD with deep expertise in clinical trial analysis, Bayesian modelling, and R/Python-based statistical consulting for pharmaceutical and academic clients. Author of 12 peer-reviewed publications.</p>
          <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <span class="tag">R</span><span class="tag">Bayesian</span><span class="tag">Clinical Trials</span><span class="tag">STATA</span>
          </div>
          <div class="expert-footer">
            <div style="display:flex;gap:8px;">
              <span class="text-xs text-muted font-mono">95% milestone completion</span>
            </div>
            <div style="display:flex;gap:8px;">
              <a href="expert-profile.html" class="btn btn-outline btn-sm">View Profile</a>
              <button class="btn btn-primary btn-sm" type="button" onclick="openInviteModal('Sofia Benedetti')">Invite to Bid</button>
            </div>
          </div>
        </div>

        <!-- LOAD MORE -->
        <div style="text-align:center;padding:20px 0;">
          <button class="btn btn-outline">Load More Specialists</button>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- INVITE MODAL -->
<div class="modal-overlay" id="invite-modal">
  <div class="modal-content">
    <div class="modal-header">Invite Specialist to Bid</div>
    <form id="invite-form" style="display:contents;">
      <div class="modal-form-group">
        <label class="modal-label" for="specialist-name">Specialist</label>
        <input type="text" id="specialist-name" style="width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:var(--radius-sm);font-family:var(--font-body);font-size:.9375rem;color:var(--ink);background:var(--ivory-deep);" readonly>
      </div>
      <div class="modal-form-group">
        <label class="modal-label" for="project-select">Select Project</label>
        <select id="project-select" class="modal-select" required>
          <option value="">Choose a project...</option>
          <option value="proj-1">Predictive Churn Model — FinCorp</option>
          <option value="proj-2">Contract Review — MENA Expansion</option>
          <option value="proj-3">Annual Report — DE Translation</option>
          <option value="proj-4">Data Pipeline Migration — CloudCorp</option>
        </select>
      </div>
      <div class="modal-form-group">
        <label class="modal-label" for="invite-message">Invitation Message</label>
        <textarea id="invite-message" class="modal-textarea" placeholder="Write a brief message explaining why you'd like to invite this specialist to bid..."></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-outline btn-sm" onclick="closeInviteModal()">Cancel</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="sendInvite()">Send Invite</button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}

function toggleCustomRepScore(show) {
  const customInput = document.getElementById('custom-rep-score');
  customInput.style.display = show ? 'block' : 'none';
  if (show) document.getElementById('rep-score-input').focus();
}

function toggleCustomPSR(show) {
  const customInput = document.getElementById('custom-psr');
  customInput.style.display = show ? 'block' : 'none';
  if (show) document.getElementById('psr-input').focus();
}

document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});

function openInviteModal(specialistName) {
  document.getElementById('specialist-name').value = specialistName;
  document.getElementById('project-select').value = '';
  document.getElementById('invite-message').value = '';
  document.getElementById('invite-modal').classList.add('show');
}

function closeInviteModal() {
  document.getElementById('invite-modal').classList.remove('show');
}

function sendInvite() {
  const specialistName = document.getElementById('specialist-name').value;
  const projectId = document.getElementById('project-select').value;
  const message = document.getElementById('invite-message').value.trim();

  if (!projectId) {
    alert('Please select a project');
    return;
  }

  if (!message) {
    alert('Please write an invitation message');
    return;
  }

  console.log('Invite sent:', { specialistName, projectId, message });
  alert(`Invitation sent to ${specialistName}!`);
  closeInviteModal();
}

document.getElementById('invite-modal').addEventListener('click', e => {
  if (e.target.id === 'invite-modal') closeInviteModal();
});
</script>

</body>
</html>
