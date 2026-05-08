<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/specialist/browse-jobs.php
    Template: Browse Jobs — Specialist View
    Role:     specialist (authenticated)
    Route:    /jobs
              /jobs?niche=data-science
              /jobs?q={search}&niche={slug}&budget_min={n}...
    ============================================================
    PHP Data contract (from JobBrowseController::index()):
      $jobs           — paginated JobRecord[]
      $total          — int total matching
      $filters        — current active filter state
      $niches         — all available niches with counts
      $specialist     — authenticated specialist (for match scoring)
      $savedJobs      — int[] of saved job IDs
      $recommendedIds — int[] jobs flagged as matches
    Each JobRecord includes:
      $j['id'], $j['title'], $j['niche'], $j['engagement_type'],
      $j['client'],   — { org_name, verified, rating, projects }
      $j['budget_min'], $j['budget_max'], $j['total_budget'],
      $j['milestones_count'], $j['duration_days'],
      $j['first_escrow'],
      $j['posted_at'], $j['closes_at'], $j['days_remaining'],
      $j['bid_count'],
      $j['visibility'], — public | invitation
      $j['nda_required'], $j['interview_required'],
      $j['match_score'],  — 0-100 from algorithm
      $j['is_saved'],
      $j['tags'],      — string[]
      $j['brief_excerpt']
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Browse Jobs — Nexus</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/browse-jobs.css">
</head>
<body>

<!-- ══════════ TOPNAV ══════════ -->
<nav class="topnav">
  <div class="container" style="max-width:100%;padding:0 32px;">
    <a class="topnav-logo" href="/">Nexus<span>.</span></a>
    <div class="topnav-links">
      <a href="/dashboard">Dashboard</a>
    </div>
    <div class="topnav-actions">
      <a href="#" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔 <span class="notif-count" style="position:absolute;top:2px;right:2px;">7</span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <div class="avatar-badge"><div class="avatar avatar-sm">DR</div></div>
          <span style="font-size:.875rem;font-weight:700;">Dr. Rania K.</span>
          <span style="color:var(--ink-faint);">▾</span>
        </div>
        <div class="dropdown-menu hidden" id="user-dd">
          <div class="dropdown-item" style="color:var(--ink-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;pointer-events:none;">Freelancer Account</div>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/profile">My Profile</a>
          <a class="dropdown-item" href="/dashboard">Earnings &amp; Wallet</a>
          <a class="dropdown-item" href="#">Account Settings</a>
          <hr class="dropdown-divider">
          <a class="dropdown-item" href="/login" style="color:var(--rust);">Sign Out</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- ══════════ SEARCH HERO ══════════ -->
<div class="search-hero">
  <div class="container" style="max-width:100%;padding:0 32px;">
    <div class="search-bar-wrap">
      <div class="search-input-wrap">
        <span class="search-icon">🔍</span>
        <!-- PHP: value="<?= htmlspecialchars($filters['q'] ?? '') ?>" -->
        <input type="text" class="search-input" id="search-q"
          placeholder="Search by keyword, skill, jurisdiction, or technology…"
          value="" onkeydown="if(event.key==='Enter')doSearch()">
      </div>
      <button class="search-btn" onclick="doSearch()">Search</button>
    </div>
  </div>
</div>

<!-- ══════════ BROWSE SHELL ══════════ -->
<div class="browse-shell">

  <!-- ── FILTER PANEL ── -->
  <aside class="filter-panel">

    <div style="font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--ink-muted);margin-bottom:16px;font-family:var(--font-body);">Filters</div>

    <div class="fg">
      <div class="fg-title">Budget Range</div>
      <div style="display:flex;gap:8px;margin-bottom:8px;">
        <div style="position:relative;flex:1;">
          <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-family:var(--font-mono);font-size:.8rem;color:var(--ink-muted);">$</span>
          <input type="number" class="form-control" style="padding-left:22px;font-size:.8125rem;" placeholder="Min" id="f-min">
        </div>
        <div style="position:relative;flex:1;">
          <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-family:var(--font-mono);font-size:.8rem;color:var(--ink-muted);">$</span>
          <input type="number" class="form-control" style="padding-left:22px;font-size:.8125rem;" placeholder="Max" id="f-max">
        </div>
      </div>
    </div>

    <div class="fg">
      <div class="fg-title">Match Score</div>
      <label class="fg-check"><input type="radio" name="match" value="any" checked style="accent-color:var(--gold);"> Any score</label>
      <label class="fg-check"><input type="radio" name="match" value="70" style="accent-color:var(--gold);"> 70%+ match</label>
      <label class="fg-check"><input type="radio" name="match" value="80" style="accent-color:var(--gold);"> 80%+ match</label>
      <label class="fg-check"><input type="radio" name="match" value="90" style="accent-color:var(--gold);"> 90%+ (Best match)</label>
    </div>

    <div class="fg">
      <div class="fg-title">Project Duration</div>
      <label class="fg-check"><input type="checkbox" style="accent-color:var(--gold);"> Short (&lt;2 weeks)</label>
      <label class="fg-check"><input type="checkbox" style="accent-color:var(--gold);"> Medium (2–6 weeks)</label>
      <label class="fg-check"><input type="checkbox" checked style="accent-color:var(--gold);"> Long (6+ weeks)</label>
    </div>

    <div class="fg">
      <div class="fg-title">Milestones</div>
      <label class="fg-check"><input type="checkbox" style="accent-color:var(--gold);"> 1–2 milestones</label>
      <label class="fg-check"><input type="checkbox" style="accent-color:var(--gold);"> 3–5 milestones</label>
      <label class="fg-check"><input type="checkbox" style="accent-color:var(--gold);"> 6+ milestones</label>
    </div>

    <button class="btn btn-outline btn-sm w-full" style="justify-content:center;" onclick="resetFilters()">Reset All Filters</button>
  </aside>

  <!-- ── JOB FEED ── -->
  <div class="job-feed">

    <!-- RESULTS + SORT BAR -->
    <div class="results-bar">
      <!-- PHP: $total.' jobs matching your profile and filters' -->
      <div class="results-count"><strong>312</strong> jobs matching your profile and filters</div>
      <div style="display:flex;gap:8px;margin-left:auto;align-items:center;">
        <span style="font-size:.8rem;color:var(--ink-muted);">Sort:</span>
        <select class="form-control" style="width:160px;padding:5px 10px;font-size:.8125rem;">
          <option>Best Match</option>
          <option>Newest First</option>
          <option>Budget — High to Low</option>
          <option>Budget — Low to High</option>
          <option>Closing Soon</option>
          <option>Fewest Bids</option>
        </select>
      </div>
    </div>

    <!-- ══════════ JOB CARDS ══════════ -->
    <!-- PHP: foreach($jobs as $j): -->

    <!-- JOB 1: RECOMMENDED / HIGH MATCH -->
    <div class="job-card recommended" id="jc-0">
      <div class="rec-label">✦ Best Match</div>
      <div class="job-card-top">
        <div class="job-niche-icon ji-data">🧠</div>
        <div style="flex:1;min-width:0;">
          <div class="job-title">
            <!-- PHP: <a href="/jobs/<?= $j['slug'] ?>"> -->
            <a href="/job-view">Real-Time Anomaly Detection Pipeline — Banking Sector</a>
          </div>
          <div class="job-meta-row">
            <div class="client-mini-inline">
              <div class="org-chip">GD</div>
              <span>Gulf Digital</span>
              <span class="badge badge-verified badge-dot" style="font-size:.6rem;">Verified</span>
            </div>
            <span>·</span>
            <span class="font-mono">Data Science · ML Ops</span>
            <span>·</span>
            <span>Posted 2 days ago</span>
          </div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <div class="match-badge high">95% match</div>
          <div class="job-budget" style="margin-top:8px;">$18,000</div>
          <div class="job-budget-lbl">6 milestones</div>
        </div>
      </div>

      <p class="job-excerpt">
        <!-- PHP: htmlspecialchars($j['brief_excerpt']) -->
        We require a production-grade real-time anomaly detection pipeline for transaction monitoring, capable of processing 50K events/sec. Must include SHAP explainability, MLFlow experiment tracking, and a Kafka integration layer. ISO 27001 environment — security clearance protocol applies.
      </p>

      <div class="job-tags">
        <span class="badge badge-gold" style="font-size:.65rem;">Data Science</span>
        <span class="badge badge-default" style="font-size:.65rem;">🔏 NDA Required</span>
        <span class="badge badge-default" style="font-size:.65rem;">🎙 Interview</span>
      </div>

      <div class="job-bottom">
        <div>
          <div style="display:flex;gap:14px;font-size:.8rem;color:var(--ink-muted);">
            <span class="font-mono">$3,000 first escrow</span>
            <span>·</span>
            <span>8 proposals so far</span>
            <span>·</span>
            <span>55 days est.</span>
          </div>
        </div>
        <div class="job-actions">
          <a href="/job-view" class="btn btn-outline btn-sm">View Details</a>
        </div>
      </div>
    </div>

    <!-- JOB 2: INVITED -->
    <div class="job-card invited" id="jc-1">
      <div class="invited-label">✉ You're Invited</div>
      <div class="job-card-top">
        <div class="job-niche-icon ji-data">🧠</div>
        <div style="flex:1;min-width:0;">
          <div class="job-title"><a href="/job-view">NLP Sentiment Analysis — Arabic/English Social Media</a></div>
          <div class="job-meta-row">
            <div class="client-mini-inline">
              <div class="org-chip" style="background:#1A4A8A;">DH</div>
              <span>Digital Hub KSA</span>
              <span class="badge badge-verified badge-dot" style="font-size:.6rem;">Verified</span>
            </div>
            <span>·</span>
            <span class="font-mono">Data Science · NLP</span>
            <span>·</span>
            <span>Invited 1 day ago</span>
          </div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <div class="match-badge high">88% match</div>
          <div class="job-budget" style="margin-top:8px;">$7,500</div>
          <div class="job-budget-lbl">4 milestones · Fixed</div>
        </div>
      </div>

      <p class="job-excerpt">Arabic-English sentiment analysis for a social media monitoring platform. Requires BERT-based fine-tuning on Arabic dialect corpus. Client operates in regulated media sector — deliverables must include model cards and bias audit reports.</p>

      <div class="job-tags">
        <span class="badge badge-gold" style="font-size:.65rem;">Data Science</span>
        <span class="badge badge-verified" style="font-size:.65rem;">Invitation Only</span>
      </div>

      <div style="background:#EBF3EA;border:1px solid #C5DBC2;border-radius:var(--radius-sm);padding:10px 14px;font-size:.8125rem;color:var(--sage);margin-bottom:12px;display:flex;gap:8px;align-items:center;">
        <span>✉</span>
        <span><strong>You've been personally invited by Digital Hub KSA.</strong> Invitation expires in 8 days.</span>
      </div>

      <div class="job-bottom">
        <div>
          <div style="display:flex;gap:14px;font-size:.8rem;color:var(--ink-muted);">
            <span class="font-mono">$1,875 first escrow</span>
            <span>·</span>
            <span>2 invited specialists</span>
            <span>·</span>
            <span>35 days est.</span>
          </div>
        </div>
        <div class="job-actions">
          <a href="/job-view" class="btn btn-outline btn-sm">View Details</a>
        </div>
      </div>
    </div>

    <!-- JOB 3: STANDARD / SAVED -->
    <div class="job-card" id="jc-2">
      <div class="job-card-top">
        <div class="job-niche-icon ji-data">🧠</div>
        <div style="flex:1;min-width:0;">
          <div class="job-title"><a href="/job-view">Customer Churn Prediction — Telecom Platform</a></div>
          <div class="job-meta-row">
            <div class="client-mini-inline">
              <div class="org-chip" style="background:#4A6741;">TG</div>
              <span>TeleGulf</span>
              <span class="badge badge-verified badge-dot" style="font-size:.6rem;">Verified</span>
            </div>
            <span>·</span>
            <span class="font-mono">Data Science</span>
            <span>·</span>
            <span>Posted 5 days ago</span>
          </div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <div class="match-badge high">82% match</div>
          <div class="job-budget" style="margin-top:8px;">$9,000</div>
          <div class="job-budget-lbl">5 milestones</div>
        </div>
      </div>

      <p class="job-excerpt">Churn prediction model for a GCC telecom, using 18 months of subscriber data. Must include feature engineering documentation, model explainability outputs, and handoff to an internal Python team. Arabic-language stakeholder reports required.</p>

      <div class="job-tags">
        <span class="badge badge-gold" style="font-size:.65rem;">Data Science</span>
      </div>

      <div class="job-bottom">
        <div><div style="display:flex;gap:14px;font-size:.8rem;color:var(--ink-muted);">
          <span class="font-mono">$1,800 first escrow</span>
          <span>·</span><span>6 proposals</span>
          <span>·</span><span>42 days est.</span>
        </div></div>
        <div class="job-actions">
          <a href="/job-view" class="btn btn-outline btn-sm">View Details</a>
        </div>
      </div>
    </div>

    <!-- JOB 4: LEGAL -->
    <div class="job-card" id="jc-3">
      <div class="job-card-top">
        <div class="job-niche-icon ji-legal">⚖️</div>
        <div style="flex:1;min-width:0;">
          <div class="job-title"><a href="/job-view">GCC Distribution Agreement — Regulatory Review</a></div>
          <div class="job-meta-row">
            <div class="client-mini-inline">
              <div class="org-chip">FC</div>
              <span>FinCorp Egypt</span>
              <span class="badge badge-verified badge-dot" style="font-size:.6rem;">Verified</span>
            </div>
            <span>·</span>
            <span class="font-mono">Legal · Commercial</span>
            <span>·</span>
            <span>Posted 5 days ago</span>
          </div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <div class="match-badge medium">74% match</div>
          <div class="job-budget" style="margin-top:8px;">$9,500</div>
          <div class="job-budget-lbl">3 milestones</div>
        </div>
      </div>

      <p class="job-excerpt">Review and redraft of a software distribution agreement for UAE and KSA markets. Specialist must have documented experience in both jurisdictions' commercial law and be fluent in Arabic and English.</p>

      <div class="job-tags">
        <span class="badge badge-verified" style="font-size:.65rem;">Legal Consulting</span>
        <span class="badge badge-default" style="font-size:.65rem;">🔏 NDA Required</span>
      </div>

      <div class="job-bottom">
        <div><div style="display:flex;gap:14px;font-size:.8rem;color:var(--ink-muted);">
          <span class="font-mono">$3,167 first escrow</span>
          <span>·</span><span>4 proposals</span>
          <span>·</span><span>28 days est.</span>
        </div></div>
        <div class="job-actions">
          <a href="/job-view" class="btn btn-outline btn-sm">View Details</a>
        </div>
      </div>
    </div>

    <!-- JOB 5: FINANCIAL -->
    <div class="job-card" id="jc-4">
      <div class="job-card-top">
        <div class="job-niche-icon ji-fin">📈</div>
        <div style="flex:1;min-width:0;">
          <div class="job-title"><a href="/job-view">Financial Forecasting Model — FMCG Regional Expansion</a></div>
          <div class="job-meta-row">
            <div class="client-mini-inline">
              <div class="org-chip" style="background:#8B3A2A;">MC</div>
              <span>MenaConsult</span>
              <span class="badge badge-verified badge-dot" style="font-size:.6rem;">Verified</span>
            </div>
            <span>·</span>
            <span class="font-mono">Financial Modelling</span>
            <span>·</span>
            <span>Posted 1 day ago</span>
          </div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <div class="match-badge medium">68% match</div>
          <div class="job-budget" style="margin-top:8px;">$6,800</div>
          <div class="job-budget-lbl">4 milestones</div>
        </div>
      </div>

      <p class="job-excerpt">3-year revenue forecast and scenario model for an FMCG company entering EGY, KSA, and UAE markets. Excel-based with Python validation scripts. Sensitivity analysis and Monte Carlo simulation required.</p>

      <div class="job-tags">
        <span class="badge badge-default" style="font-size:.65rem;">Financial Modelling</span>
      </div>

      <div class="job-bottom">
        <div><div style="display:flex;gap:14px;font-size:.8rem;color:var(--ink-muted);">
          <span class="font-mono">$1,700 first escrow</span>
          <span>·</span><span>3 proposals</span>
          <span>·</span><span>30 days est.</span>
        </div></div>
        <div class="job-actions">
          <a href="/job-view" class="btn btn-outline btn-sm">View Details</a>
        </div>
      </div>
    </div>

    <!-- LOAD MORE -->
    <div style="text-align:center;padding:24px 0;border-top:1px solid var(--border);margin-top:8px;">
      <p class="text-sm text-muted mb-12">Showing 5 of 312 matching jobs</p>
      <button class="btn btn-outline" onclick="showToast('Loading next 10 jobs…','info')">Load More Jobs</button>
    </div>

  </div>
</div>

<!-- TOAST -->
<div class="toast-stack" id="toast-stack"></div>

<script>
function doSearch() {
  const q = document.getElementById('search-q')?.value;
  const n = document.getElementById('search-niche')?.value;
  // PHP: window.location = '/jobs?q='+encodeURIComponent(q)+'&niche='+n
  showToast('Search updated.','info');
}
function setNiche(el, val) {
  document.querySelectorAll('.niche-pill').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  const sel = document.getElementById('search-niche');
  if(sel) sel.value = val;
  // PHP: reload with ?niche={val}
}
function resetFilters() {
  document.querySelectorAll('.filter-panel input').forEach(i => {
    if(i.type === 'checkbox') i.checked = false;
    if(i.type === 'radio' && i.value === 'any') i.checked = true;
    if(i.type === 'number') i.value = '';
  });
  showToast('Filters reset.','info');
}
function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});
function showToast(msg, type='success') {
  const s = document.getElementById('toast-stack');
  const icons = {success:'✓', warn:'⚠', info:'ℹ'};
  const cls   = {success:'success', warn:'warning', info:''};
  s.innerHTML = `<div class="toast ${cls[type]}"><span class="toast-icon">${icons[type]}</span><div><div class="toast-title">${type==='warn'?'Notice':type==='info'?'Info':'Done'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(()=>s.innerHTML='',4000);
}
</script>
</body>
</html>
