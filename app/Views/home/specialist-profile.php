<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dr. Rania Khalil — Expert Profile · Nexus</title>
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/css/specialist-profile.css">
</head>
<body>

<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="index.php">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="#" onclick="history.back(); return false;">← Back</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.php" class="btn btn-ghost btn-icon">🔔</a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">AT</div></div>
          <span style="font-size:.875rem;font-weight:700;">Dr. Rania K.</span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">My Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="profile-edit.php">My Profile</a>
          <a class="dropdown-item" href="escrow-wallet.html">Wallet &amp; Escrow</a>
          <a class="dropdown-item" href="#">Account Settings</a>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="login.html" style="color:var(--rust);">Sign Out</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- PROFILE HERO -->
<div class="profile-hero">
  <div class="container">
    <div class="profile-hero-inner">
      <div class="avatar-badge">
        <div class="avatar avatar-xl">DR</div>
      </div>
      <div style="flex:1;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
          <h1 id="preview-name" style="font-size:2rem;font-weight:600;">Dr. Rania Khalil</h1>
          <span class="badge badge-verified badge-dot">Verified</span>
        </div>
        <div id="preview-title" style="font-size:1rem;color:var(--ink-mid);margin-bottom:10px;">Senior Data Scientist · Machine Learning &amp; NLP Research</div>
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
          <span id="preview-location" class="text-sm text-muted">📍 Cairo, Egypt · GMT+2</span>
          <span id="preview-degree" class="text-sm text-muted">🏛 PhD, American University in Cairo</span>
          <span id="preview-experience" class="text-sm text-muted">💼 9 years experience</span>
          <span id="preview-languages" class="text-sm text-muted">🌐 Arabic · English · French</span>
        </div>
      </div>
    </div>
    <div class="profile-stats-bar">
      <div class="profile-stat"><div class="val">4.97</div><div class="lbl">Reputation Score</div></div>
      <div class="profile-stat"><div class="val">83</div><div class="lbl">Projects Completed</div></div>
      <div class="profile-stat"><div class="val">92%</div><div class="lbl">Milestone Approval Rate</div></div>
      <div class="profile-stat"><div class="val">$740K</div><div class="lbl">Total Value Delivered</div></div>
      <div class="profile-stat"><div class="val">54</div><div class="lbl">Clients</div></div>
    </div>
  </div>
</div>

<!-- PROFILE BODY -->
<div class="container" style="padding-top:0;">
  <div class="profile-body">

    <!-- LEFT COLUMN -->
    <div>

      <!-- TABS -->
      <div class="tabs mt-24 mb-24">
        <button class="tab-item active" onclick="switchTab(0)">Overview</button>
        <button class="tab-item" onclick="switchTab(1)">Portfolio</button>
        <button class="tab-item" onclick="switchTab(2)">Credentials</button>
        <button class="tab-item" onclick="switchTab(3)">Reviews (83)</button>
        <button class="tab-item" onclick="switchTab(4)">Skills</button>
      </div>

      <!-- OVERVIEW -->
      <div id="tab-0">
        <h3 class="mb-16">About</h3>
        <div id="preview-about">
          <p style="margin-bottom:12px;">With a PhD in Computer Science and nine years of applied research and consulting experience, I specialize in high-stakes machine learning projects where interpretability, reliability, and domain alignment are non-negotiable. My work spans predictive modelling for financial services, NLP systems for Arabic-English corpora, and production ML pipelines for regulated industries.</p>
          <p style="margin-bottom:12px;">I approach every engagement with a structured, milestone-driven methodology: clear deliverables, versioned notebooks, documented assumptions, and stakeholder-ready presentations at each phase. I do not accept projects outside my verified areas of expertise.</p>
          <p>I am particularly experienced in working within the constraints of regulated industries where model explainability (SHAP, LIME) and audit trails are required deliverables, not afterthoughts.</p>
        </div>

        <hr class="divider">
        <h3 class="mb-16">Specializations</h3>
        <div id="preview-specializations" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:24px;">
          <div style="padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-deep);">
            <div style="font-weight:700;font-size:.875rem;margin-bottom:4px;">Predictive Modelling</div>
            <div class="text-xs text-muted">Churn, risk scoring, demand forecasting for FS/retail</div>
          </div>
          <div style="padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-deep);">
            <div style="font-weight:700;font-size:.875rem;margin-bottom:4px;">Arabic NLP</div>
            <div class="text-xs text-muted">Sentiment analysis, named entity recognition, summarization</div>
          </div>
          <div style="padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-deep);">
            <div style="font-weight:700;font-size:.875rem;margin-bottom:4px;">Explainable AI</div>
            <div class="text-xs text-muted">SHAP, LIME, regulatory reporting for banking/healthcare</div>
          </div>
          <div style="padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-deep);">
            <div style="font-weight:700;font-size:.875rem;margin-bottom:4px;">ML Engineering</div>
            <div class="text-xs text-muted">Pipeline architecture, MLFlow, Docker, AWS Sagemaker</div>
          </div>
        </div>

        <hr class="divider">
      </div>

      <!-- PORTFOLIO -->
      <div id="tab-1" class="hidden">
        <h3 class="mb-16">Selected Portfolio</h3>
        <div class="card card-sm mb-12">
          <div class="portfolio-item">
            <div class="portfolio-type">📓</div>
            <div style="flex:1;"><div style="font-weight:700;font-size:.9375rem;margin-bottom:2px;">Credit Risk Scoring Model — GCC Banking</div><div class="text-xs text-muted">Jupyter Notebook · 84MB · Uploaded Apr 2024</div></div>
          </div>
          <div class="portfolio-item">
            <div class="portfolio-type">📊</div>
            <div style="flex:1;"><div style="font-weight:700;font-size:.9375rem;margin-bottom:2px;">Churn Prediction Pipeline — Telecom (MENA)</div><div class="text-xs text-muted">Python Project · Anonymized · Uploaded Jan 2024</div></div>
          </div>
          <div class="portfolio-item">
            <div class="portfolio-type">📄</div>
            <div style="flex:1;"><div style="font-weight:700;font-size:.9375rem;margin-bottom:2px;">Arabic Sentiment Analysis — Social Media</div><div class="text-xs text-muted">Research Paper · Published EMNLP 2023</div></div>
          </div>
        </div>
      </div>

      <!-- CREDENTIALS -->
      <div id="tab-2" class="hidden">
        <h3 class="mb-16">Verified Credentials</h3>
        <div class="credential-item">
          <div class="credential-icon">🎓</div>
          <div style="flex:1;"><div class="credential-name">Doctor of Philosophy — Computer Science</div><div class="credential-org">American University in Cairo · Graduated 2016</div><div class="credential-status"><span class="badge badge-verified badge-dot">Verified by Nexus</span></div></div>
        </div>
        <div class="credential-item">
          <div class="credential-icon">🏅</div>
          <div style="flex:1;"><div class="credential-name">Professional Machine Learning Engineer (PMLE)</div><div class="credential-org">Google Cloud · Expires Dec 2025</div><div class="credential-status"><span class="badge badge-verified badge-dot">Verified by Nexus</span></div></div>
        </div>
        <div class="credential-item">
          <div class="credential-icon">🏅</div>
          <div style="flex:1;"><div class="credential-name">AWS Certified Machine Learning — Specialty</div><div class="credential-org">Amazon Web Services · Expires Jun 2025</div><div class="credential-status"><span class="badge badge-verified badge-dot">Verified by Nexus</span></div></div>
        </div>
      </div>

      <!-- REVIEWS -->
      <div id="tab-3" class="hidden">
        <div style="display:flex;align-items:center;gap:32px;margin-bottom:24px;">
          <div style="text-align:center;">
            <div style="font-family:var(--font-display);font-size:3.5rem;font-weight:300;line-height:1;">4.97</div>
            <div class="stars">★★★★★</div>
            <div class="text-xs text-muted mt-4">83 reviews</div>
          </div>
          <div style="flex:1;">
            <div class="skill-bar"><div class="skill-bar-label"><span>Communication</span><span class="font-mono">5.0</span></div><div class="progress-bar"><div class="progress-fill success" style="width:100%;"></div></div></div>
            <div class="skill-bar"><div class="skill-bar-label"><span>Quality of Work</span><span class="font-mono">4.98</span></div><div class="progress-bar"><div class="progress-fill success" style="width:99%;"></div></div></div>
            <div class="skill-bar"><div class="skill-bar-label"><span>Punctuality</span><span class="font-mono">4.95</span></div><div class="progress-bar"><div class="progress-fill" style="width:99%;"></div></div></div>
            <div class="skill-bar"><div class="skill-bar-label"><span>Milestone Adherence</span><span class="font-mono">4.93</span></div><div class="progress-bar"><div class="progress-fill" style="width:98%;"></div></div></div>
          </div>
        </div>
        <div class="review-item">
          <div class="flex items-center gap-12 mb-8">
            <div class="avatar avatar-sm">AT</div>
            <div><div style="font-weight:700;font-size:.875rem;">Amira Tawfik</div><div class="text-xs text-muted">FinCorp Egypt · Apr 2024</div></div>
            <div class="stars" style="margin-left:auto;">★★★★★</div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);">Rania delivered beyond expectations. Her structured approach — submitting a detailed WIP report before each milestone approval — gave our team full visibility at every stage. The SHAP explanations she provided for regulators were a decisive advantage in our audit.</p>
        </div>
        <div class="review-item">
          <div class="flex items-center gap-12 mb-8">
            <div class="avatar avatar-sm">FH</div>
            <div><div style="font-weight:700;font-size:.875rem;">Farouk Hassan</div><div class="text-xs text-muted">Gulf Digital · Jan 2024</div></div>
            <div class="stars" style="margin-left:auto;">★★★★★</div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);">Outstanding. She flagged scope creep proactively and submitted a formal amendment before proceeding — that is exactly the kind of professional discipline that separates a true specialist from a generalist.</p>
        </div>
      </div>

      <!-- SKILLS -->
      <div id="tab-4" class="hidden">
        <h3 class="mb-16">Technical Skills Assessment</h3>
        <div id="preview-skills">
          <div class="skill-bar"><div class="skill-bar-label"><span>Python (ML Ecosystem)</span><span class="font-mono text-xs">Expert</span></div><div class="progress-bar"><div class="progress-fill level-expert"></div></div></div>
          <div class="skill-bar"><div class="skill-bar-label"><span>PyTorch / TensorFlow</span><span class="font-mono text-xs">Begginer</span></div><div class="progress-bar"><div class="progress-fill level-beginner"></div></div></div>
          <div class="skill-bar"><div class="skill-bar-label"><span>Arabic NLP</span><span class="font-mono text-xs">Expert</span></div><div class="progress-bar"><div class="progress-fill level-expert"></div></div></div>
          <div class="skill-bar"><div class="skill-bar-label"><span>Statistical Modelling</span><span class="font-mono text-xs">Advanced</span></div><div class="progress-bar"><div class="progress-fill level-advanced"></div></div></div>
          <div class="skill-bar"><div class="skill-bar-label"><span>AWS / GCP ML Services</span><span class="font-mono text-xs">Intermediate</span></div><div class="progress-bar"><div class="progress-fill level-intermediate"></div></div></div>
          <div class="skill-bar"><div class="skill-bar-label"><span>MLFlow / Model Registry</span><span class="font-mono text-xs">Advanced</span></div><div class="progress-bar"><div class="progress-fill level-advanced"></div></div></div>
        </div>
        <p class="text-xs text-muted mt-8 font-mono">Skill scores derived from peer reviews, completed project outcomes, and platform assessments. Last updated April 2024.</p>
      </div>

    </div>

    <!-- RIGHT: STICKY CTA -->
    <div>
      <div class="sticky-cta">
        <div style="text-align:center;margin-bottom:20px;">
          <div class="reputation-ring" style="margin:0 auto 12px;"><span>4.97</span></div>
          <div style="font-weight:700;font-size:.9375rem;">Dr. Rania Khalil</div>
          <div class="text-xs text-muted">Available now · GMT+2</div>
        </div>
        <div class="flex justify-between mb-16 text-sm">
          <span class="text-muted">Fixed Projects</span>
          <span class="font-mono">From $3,000</span>
        </div>
        <button class="btn btn-primary w-full" style="justify-content:center;margin-bottom:10px;" onclick="document.getElementById('bid-modal').classList.remove('hidden')">Invite to Bid</button>
        <button class="btn btn-outline w-full" style="justify-content:center;margin-bottom:10px;" onclick="window.location='messages.php'">Send Message</button>
        <button class="btn btn-ghost w-full" style="justify-content:center;font-size:.8125rem;">Schedule Interview</button>
        <hr class="divider">
        <div class="text-xs text-muted text-center">
          <div>🛡 KYC &amp; Credential Verified</div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- INVITE TO BID MODAL -->
<div id="bid-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Invite Dr. Rania Khalil to Bid</h3>
        <p class="text-sm text-muted mt-4">An NDA will be auto-generated upon invitation acceptance.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('bid-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Select Project</label>
        <select class="form-control">
          <option>Predictive Churn Model — FinCorp Q2</option>
          <option>+ Post a new project</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Personal Message <span class="text-muted font-mono" style="font-size:.7rem;">(Optional)</span></label>
        <textarea class="form-control" placeholder="Introduce your project and why you're reaching out to Dr. Khalil specifically…" rows="4"></textarea>
      </div>
      <div class="verify-band">
        <span>📋</span>
        <div style="font-size:.8125rem;">A digitally signable NDA will be generated and sent to both parties immediately upon Dr. Khalil accepting this invitation.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('bid-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('bid-modal').classList.add('hidden');showToast()">Send Invitation</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast-stack" id="toast-stack"></div>

<script>
const tabs = document.querySelectorAll('.tab-item');
function switchTab(i) {
  tabs.forEach((t,j) => t.classList.toggle('active', i===j));
  document.querySelectorAll('[id^="tab-"]').forEach((el,j) => el.classList.toggle('hidden', i!==j));
}
function showToast() {
  const s = document.getElementById('toast-stack');
  s.innerHTML = `<div class="toast success"><span class="toast-icon">✓</span><div><div class="toast-title">Invitation sent</div><div class="toast-body">Dr. Rania Khalil has been notified. NDA generated and pending signature.</div></div></div>`;
  setTimeout(() => s.innerHTML = '', 4000);
}

function previewLevelClass(level) {
  const value = String(level || '').trim().toLowerCase();
  if (value.startsWith('exp')) return 'level-expert';
  if (value.startsWith('adv')) return 'level-advanced';
  if (value.startsWith('int')) return 'level-intermediate';
  return 'level-beginner';
}

function toggleDD() {
  document.getElementById('user-dd')?.classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});

function hydratePreview() {
  const raw = localStorage.getItem('nexusProfilePreview');
  if (!raw) return;
  let data;
  try { data = JSON.parse(raw); } catch (e) { return; }
  if (!data) return;

  const nameEl = document.getElementById('preview-name');
  const titleEl = document.getElementById('preview-title');
  const locationEl = document.getElementById('preview-location');
  const experienceEl = document.getElementById('preview-experience');
  const aboutEl = document.getElementById('preview-about');
  const specsEl = document.getElementById('preview-specializations');
  const skillsEl = document.getElementById('preview-skills');

  if (nameEl && data.name) nameEl.textContent = data.name;
  if (titleEl && data.title) titleEl.textContent = data.title;
  if (locationEl && (data.country || data.timezone)) {
    const country = data.country || 'Unknown location';
    const tz = data.timezone ? ` · ${data.timezone}` : '';
    locationEl.textContent = `📍 ${country}${tz}`;
  }
  if (experienceEl && data.experience) experienceEl.textContent = `💼 ${data.experience} years experience`;
  if (aboutEl && data.bio) {
    aboutEl.innerHTML = '';
    data.bio.split('\n').map(line => line.trim()).filter(Boolean).forEach(line => {
      const p = document.createElement('p');
      p.style.marginBottom = '12px';
      p.textContent = line;
      aboutEl.appendChild(p);
    });
  }
  if (specsEl && Array.isArray(data.specs) && data.specs.length) {
    const cards = data.specs.filter(s => s.title || s.description).slice(0, 4);
    if (cards.length) {
      specsEl.innerHTML = '';
      cards.forEach(spec => {
        const card = document.createElement('div');
        card.style.padding = '14px';
        card.style.border = '1px solid var(--border)';
        card.style.borderRadius = 'var(--radius-sm)';
        card.style.background = 'var(--ivory-deep)';
        const title = document.createElement('div');
        title.style.fontWeight = '700';
        title.style.fontSize = '.875rem';
        title.style.marginBottom = '4px';
        title.textContent = spec.title || 'Specialization';
        const description = document.createElement('div');
        description.className = 'text-xs text-muted';
        description.textContent = spec.description || '';
        card.appendChild(title);
        card.appendChild(description);
        specsEl.appendChild(card);
      });
    }
  }
  if (skillsEl && Array.isArray(data.skills) && data.skills.length) {
    skillsEl.innerHTML = '';
    data.skills.filter(s => s.name).slice(0, 6).forEach(skill => {
      const bar = document.createElement('div');
      bar.className = 'skill-bar';
      bar.innerHTML = `<div class="skill-bar-label"><span>${skill.name}</span><span class="font-mono text-xs">${skill.level}</span></div><div class="progress-bar"><div class="progress-fill ${previewLevelClass(skill.level)}"></div></div>`;
      skillsEl.appendChild(bar);
    });
  }
}

hydratePreview();
</script>
</body>
</html>
