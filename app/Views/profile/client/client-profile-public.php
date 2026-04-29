<!DOCTYPE html>
<!--
    ============================================================
    NEXUS PLATFORM — Views/client-profile-public.php
    Template: Client Profile — Specialist-Facing Public View
    Role:     specialist (authenticated) viewing a client
    Bound to: /views/specialist/client-profile.php
    Route:    /client/{slug}
    Data:     $client, $org, $stats, $projectHistory,
              $reviews, $activeProjects, $isVerified,
              $trustBadges, $canSendProposal
    ============================================================
-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- PHP: <title><?= htmlspecialchars($org['name']) ?> — Client Profile · Nexus</title> -->
<title>FinCorp Egypt — Client Profile · Nexus</title>
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/client-profile-public.css">
</head>
<body>

<!-- ══════════════════ TOPNAV ══════════════════
     PHP: include 'partials/topnav.php';
     Pass: ['role'=>'specialist','active'=>'browse','user'=>$specialist]
-->
<nav class="topnav">
  <div class="container">
    <a class="topnav-logo" href="index.html">Nexus<span>.</span></a>
    <div class="topnav-links">
      <!-- PHP: if(isset($_GET['from']) && $_GET['from']==='project'): -->
      <a href="#" onclick="if(history.length>1){history.back();return false;}window.location='browse-experts.html';">← Back</a>
    </div>
    <div class="topnav-actions">
      <a href="notifications.html" class="btn btn-ghost btn-icon" style="position:relative;">
        🔔<span class="notif-count" style="position:absolute;top:2px;right:2px;">7</span>
      </a>
      <div class="dropdown">
        <div class="flex items-center gap-8" style="cursor:pointer;" onclick="toggleDD()">
          <!-- PHP: <div class="avatar avatar-sm"><?= $specialist['initials'] ?></div> -->
          <div class="avatar-badge"><div class="avatar avatar-sm">DR</div></div>
          <span style="font-size:.875rem;font-weight:700;">Dr. Rania K.</span>
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

<!-- ══════════════════ CLIENT HERO ══════════════════ -->
<!-- PHP: $org = $client['organization'] -->
<div class="client-hero">
  <div class="container">
    <div class="client-hero-inner">

      <!-- ORG LOGO -->
      <!-- PHP: if($org['logo_url']): <img src="<?= $org['logo_url'] ?>"> else: show initials -->
      <div class="org-logo-box">FC</div>

      <div style="flex:1;">
        <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-muted);font-weight:700;font-family:var(--font-body);margin-bottom:8px;">
          <!-- PHP: <?= htmlspecialchars($org['type']) ?> · <?= htmlspecialchars($org['industry']) ?> -->
          Corporate · Financial Services &amp; Banking
        </div>

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;flex-wrap:wrap;">
          <!-- PHP: <h1 style="font-size:2rem;font-weight:600;"><?= htmlspecialchars($org['name']) ?></h1> -->
          <h1 style="font-size:2rem;font-weight:600;">FinCorp Egypt</h1>
          <!-- PHP: if($org['kyc_verified']): -->
          <span class="trust-badge kyc">Verified</span>
          <span id="org-unverified-badge" class="trust-badge" style="display:none;">Unverified</span>
        </div>

        <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;margin-bottom:16px;">
          <!-- PHP: echo each $metaItem -->
          <span class="text-sm text-muted">📍 Cairo, Egypt · GMT+2</span>
          <span class="text-sm text-muted">🌐 <a href="https://fincorp.eg" target="_blank" style="color:var(--ink-mid);">fincorp.eg</a></span>
          <!-- PHP: <span class="text-sm text-muted">📅 Member since <?= date('M Y', $client['created_at']) ?></span> -->
          <span class="text-sm text-muted">📅 Member since Jan 2023</span>
          <!-- PHP: <span class="text-sm text-muted">👤 <?= htmlspecialchars($client['contact_name']) ?> — <?= htmlspecialchars($client['contact_title']) ?></span> -->
          <span class="text-sm text-muted">👤 Amira Tawfik — Head of Analytics</span>
        </div>

        <!-- NICHE TAGS -->
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <!-- PHP: foreach($client['niche_preferences'] as $n): -->
          <span class="niche-tag">🧠 Data Science</span>
          <span class="niche-tag">⚖️ Legal Consulting</span>
          <span class="niche-tag">📈 Financial Modelling</span>
        </div>
      </div>

      <!-- CONTACT PERSON ASIDE -->
      <div style="text-align:right;padding-bottom:24px;flex-shrink:0;">
        <div class="flex items-center gap-10 justify-end mb-8">
          <div>
            <!-- PHP: <?= htmlspecialchars($client['first_name'].' '.$client['last_name']) ?> -->
            <div style="font-weight:700;font-size:.9rem;">Amira Tawfik</div>
            <div class="text-xs text-muted">Head of Analytics</div>
          </div>
          <div class="avatar avatar-md">AT</div>
        </div>
        <div class="text-xs text-muted font-mono">Contact Person</div>
      </div>
    </div>

    <!-- STATS BAR -->
    <div class="client-stats-bar">
      <!-- PHP: driven by $stats object -->
      <div class="client-stat"><div class="val">14</div><div class="lbl">Projects Posted</div></div>
      <div class="client-stat"><div class="val">12</div><div class="lbl">Completed</div></div>
      <div class="client-stat"><div class="val">2.1%</div><div class="lbl">Dispute Rate</div></div>
      <div class="client-stat"><div class="val">$1.2M</div><div class="lbl">Total Spent</div></div>
      <div class="client-stat"><div class="val">$8,400</div><div class="lbl">Avg. Project Value</div></div>
      <div class="client-stat"><div class="val">71%</div><div class="lbl">Repeat Hire Rate</div></div>
    </div>
  </div>
</div>

<!-- ══════════════════ BODY ══════════════════ -->
<div class="container" style="padding-top:0;">
  <div class="client-body">

    <!-- ── LEFT COLUMN ── -->
    <div>

      <!-- TABS -->
      <div class="tabs mt-24 mb-24">
        <!-- PHP: active tab driven by query param or default -->
        <button class="tab-item active" onclick="switchTab(0)">Overview</button>
        <button class="tab-item" onclick="switchTab(1)">Project History</button>
        <button class="tab-item" onclick="switchTab(2)">Specialist Reviews</button>
        <button class="tab-item" onclick="switchTab(3)">Open Projects</button>
      </div>

      <!-- ══ TAB 0: OVERVIEW ══ -->
      <div id="tab-0">

        <h3 class="mb-12">About FinCorp Egypt</h3>
        <!-- PHP: echo nl2br(htmlspecialchars($org['bio'])) -->
        <p style="margin-bottom:12px;">FinCorp Egypt is a mid-market financial services company providing corporate banking, investment advisory, and digital payment infrastructure across North Africa and the GCC. We engage specialized consultants for data science, legal, and research projects that require high credential standards and structured delivery.</p>
        <p>Our engagements are always milestone-structured, NDA-protected, and subject to stringent deliverable standards. We work exclusively with fully verified Nexus specialists.</p>

        <hr class="divider">

        <h3 class="mb-12">What We Hire For</h3>
        <p style="margin-bottom:16px;">We primarily engage specialists for high-stakes data science projects (predictive modelling, ML pipeline deployment) and cross-jurisdictional legal consulting related to our North African and GCC expansion. All engagements involve regulated deliverables where credential verification and milestone accountability are mandatory.</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:8px;">
          <!-- PHP: foreach($client['hiring_context_cards'] as $card): -->
          <div style="padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-deep);">
            <div style="font-weight:700;font-size:.875rem;margin-bottom:4px;">🧠 Data Science</div>
            <div class="text-xs text-muted">Predictive models, ML pipelines, explainability reports for regulated use</div>
          </div>
          <div style="padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-deep);">
            <div style="font-weight:700;font-size:.875rem;margin-bottom:4px;">⚖️ Legal Consulting</div>
            <div class="text-xs text-muted">Cross-border commercial law, GCC/MENA jurisdictions, multi-language</div>
          </div>
          <div style="padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--ivory-deep);">
            <div style="font-weight:700;font-size:.875rem;margin-bottom:4px;">📈 Financial Modelling</div>
            <div class="text-xs text-muted">Valuation models, financial forecasting, regulatory stress tests</div>
          </div>
        </div>

        <hr class="divider">

        <h3 class="mb-12">Keywords</h3>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <!-- PHP: foreach($client['keywords'] as $kw): -->
          <span class="niche-tag">Regulated Industry</span>
          <span class="niche-tag">GCC Expansion</span>
          <span class="niche-tag">Arabic Markets</span>
          <span class="niche-tag">Explainability Required</span>
          <span class="niche-tag">NDA-Sensitive</span>
        </div>

      </div>

      <!-- ══ TAB 1: PROJECT HISTORY ══ -->
      <div id="tab-1" class="hidden">
        <div class="flex justify-between items-center mb-16">
          <h3>Project History</h3>
          <!-- PHP: <span class="text-sm text-muted"><?= $stats['completed_count'] ?> completed projects</span> -->
          <span class="text-sm text-muted">12 completed projects</span>
        </div>

        <!-- PHP: foreach($projectHistory as $p): -->
        <div class="ph-item">
          <div class="ph-niche-dot" style="background:var(--gold);"></div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.9375rem;margin-bottom:3px;">Predictive Churn Model — FinCorp Q1</div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
              <span class="badge badge-gold" style="font-size:.625rem;">Data Science</span>
              <span class="badge badge-verified" style="font-size:.625rem;">Completed</span>
              <span class="text-xs text-muted font-mono">5 milestones · Apr 2025</span>
            </div>
          </div>
          <span class="ph-amount">$8,400</span>
        </div>

        <div class="ph-item">
          <div class="ph-niche-dot" style="background:var(--sage);"></div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.9375rem;margin-bottom:3px;">MENA Expansion — Contract Framework Review</div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
              <span class="badge badge-verified" style="font-size:.625rem;">Legal</span>
              <span class="badge badge-verified" style="font-size:.625rem;">Completed</span>
              <span class="text-xs text-muted font-mono">3 milestones · Feb 2025</span>
            </div>
          </div>
          <span class="ph-amount">$12,000</span>
        </div>

        <div class="ph-item">
          <div class="ph-niche-dot" style="background:var(--gold);"></div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.9375rem;margin-bottom:3px;">Customer Segmentation — Retail Banking</div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
              <span class="badge badge-gold" style="font-size:.625rem;">Data Science</span>
              <span class="badge badge-verified" style="font-size:.625rem;">Completed</span>
              <span class="text-xs text-muted font-mono">4 milestones · Jan 2025</span>
            </div>
          </div>
          <span class="ph-amount">$6,200</span>
        </div>

        <div class="ph-item">
          <div class="ph-niche-dot" style="background:var(--ink-mid);"></div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.9375rem;margin-bottom:3px;">Annual Report — DE/EN Technical Translation</div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
              <span class="badge badge-default" style="font-size:.625rem;">Translation</span>
              <span class="badge badge-danger" style="font-size:.625rem;">Disputed → Resolved</span>
              <span class="text-xs text-muted font-mono">4 milestones · Nov 2024</span>
            </div>
          </div>
          <span class="ph-amount">$4,100</span>
        </div>

        <div class="ph-item">
          <div class="ph-niche-dot" style="background:var(--gold);"></div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.9375rem;margin-bottom:3px;">Fraud Detection Model — Microfinance</div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
              <span class="badge badge-gold" style="font-size:.625rem;">Data Science</span>
              <span class="badge badge-verified" style="font-size:.625rem;">Completed</span>
              <span class="text-xs text-muted font-mono">4 milestones · Oct 2024</span>
            </div>
          </div>
          <span class="ph-amount">$7,800</span>
        </div>

        <div style="text-align:center;padding:24px 0;border-top:1px solid var(--border);margin-top:8px;">
          <button class="btn btn-outline">Load 7 More Projects</button>
        </div>
      </div>

      <!-- ══ TAB 2: SPECIALIST REVIEWS ══ -->
      <div id="tab-2" class="hidden">

        <!-- AGGREGATE -->
        <div style="display:flex;align-items:center;gap:36px;margin-bottom:28px;">
          <div style="text-align:center;">
            <div style="font-family:var(--font-display);font-size:3.5rem;font-weight:300;line-height:1;color:var(--ink);">4.8</div>
            <div class="stars">★★★★★</div>
            <div class="text-xs text-muted mt-4">from 12 specialists</div>
          </div>
          <div style="flex:1;">
            <!-- PHP: foreach($ratingDimensions as $d): -->
            <div class="skill-bar" style="margin-bottom:10px;">
              <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:.8125rem;"><span>Brief Quality &amp; Clarity</span><span class="font-mono">4.9</span></div>
              <div class="progress-bar"><div class="progress-fill success" style="width:98%;"></div></div>
            </div>
            <div class="skill-bar" style="margin-bottom:10px;">
              <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:.8125rem;"><span>Milestone Review Speed</span><span class="font-mono">4.7</span></div>
              <div class="progress-bar"><div class="progress-fill" style="width:94%;"></div></div>
            </div>
            <div class="skill-bar" style="margin-bottom:10px;">
              <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:.8125rem;"><span>Communication</span><span class="font-mono">4.9</span></div>
              <div class="progress-bar"><div class="progress-fill success" style="width:98%;"></div></div>
            </div>
            <div class="skill-bar">
              <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:.8125rem;"><span>Payment Reliability</span><span class="font-mono">5.0</span></div>
              <div class="progress-bar"><div class="progress-fill success" style="width:100%;"></div></div>
            </div>
          </div>
        </div>

        <!-- PHP: foreach($reviews as $r): -->
        <div class="review-item">
          <div class="flex items-center gap-12 mb-8">
            <div class="avatar avatar-sm">DR</div>
            <div><div style="font-weight:700;font-size:.875rem;">Dr. Rania Khalil</div><div class="text-xs text-muted">Data Science · Apr 2025</div></div>
            <div class="stars" style="margin-left:auto;">★★★★★</div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);">Amira's project briefs are exceptional — detailed, unambiguous, with clear data inventories and regulatory constraints stated upfront. Milestone approvals came within 24 hours of every submission. Payment was released the same day. An ideal client for complex, high-stakes work.</p>
          <div class="text-xs text-muted font-mono mt-8">Predictive Churn Model · $8,400 · 5 milestones</div>
        </div>

        <div class="review-item">
          <div class="flex items-center gap-12 mb-8">
            <div class="avatar avatar-sm">JM</div>
            <div><div style="font-weight:700;font-size:.875rem;">James Moreau</div><div class="text-xs text-muted">Legal Consulting · Feb 2025</div></div>
            <div class="stars" style="margin-left:auto;">★★★★★</div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);">A client who understands the value of specialized legal work and respects the process. The NDA was clear, the scope was well-defined, and the internal team provided all requested documents promptly. The structured milestone approach worked perfectly for legal deliverables.</p>
          <div class="text-xs text-muted font-mono mt-8">MENA Expansion Contract Review · $12,000 · 3 milestones</div>
        </div>

        <div class="review-item">
          <div class="flex items-center gap-12 mb-8">
            <div class="avatar avatar-sm">KA</div>
            <div><div style="font-weight:700;font-size:.875rem;">Karim Al-Azzawi</div><div class="text-xs text-muted">Data Science · Jan 2025</div></div>
            <div class="stars" style="margin-left:auto;">★★★★☆</div>
          </div>
          <p style="font-size:.875rem;color:var(--ink-mid);">Good client with a clear vision. One scope amendment mid-project — handled through formal channels correctly. Would work with FinCorp again without hesitation.</p>
          <div class="text-xs text-muted font-mono mt-8">Customer Segmentation · $6,200 · 4 milestones</div>
        </div>

      </div>

      <!-- ══ TAB 3: OPEN PROJECTS ══ -->
      <div id="tab-3" class="hidden">
        <div class="flex justify-between items-center mb-16">
          <h3>Currently Open Projects</h3>
          <!-- PHP: <span class="text-sm text-muted"><?= count($activeProjects) ?> open for bids</span> -->
          <span class="text-sm text-muted">2 open for bids</span>
        </div>

        <!-- PHP: foreach($activeProjects as $p): -->
        <div class="active-proj-card">
          <div class="flex justify-between items-start mb-8">
            <div>
              <span class="badge badge-gold" style="font-size:.625rem;margin-bottom:6px;">Data Science</span>
              <div style="font-weight:700;font-size:.9375rem;">Predictive Churn Model — Q2 2025</div>
            </div>
            <span style="font-family:var(--font-mono);font-weight:500;">$8,400</span>
          </div>
          <p style="font-size:.8125rem;color:var(--ink-mid);margin-bottom:10px;">Extension of our Q1 churn model to include product cross-sell propensity scoring. Must handle Arabic-language data and include SHAP explainability for regulatory review.</p>
          <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px;">
            <span class="niche-tag" style="font-size:.7rem;">Python</span>
            <span class="niche-tag" style="font-size:.7rem;">XGBoost</span>
            <span class="niche-tag" style="font-size:.7rem;">SHAP</span>
            <span class="niche-tag" style="font-size:.7rem;">Arabic NLP</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-xs text-muted font-mono">5 milestones · Posted 2 days ago · 8 bids received</span>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('bid-modal').classList.remove('hidden')">Submit Proposal</button>
          </div>
        </div>

        <div class="active-proj-card">
          <div class="flex justify-between items-start mb-8">
            <div>
              <span class="badge badge-verified" style="font-size:.625rem;margin-bottom:6px;">Legal</span>
              <div style="font-weight:700;font-size:.9375rem;">GCC Distribution Agreement — Regulatory Review</div>
            </div>
            <span style="font-family:var(--font-mono);font-weight:500;">$9,500</span>
          </div>
          <p style="font-size:.8125rem;color:var(--ink-mid);margin-bottom:10px;">Review and redraft of a distribution agreement under KSA and UAE federal law. Specialist must be admitted or have documented experience in both jurisdictions.</p>
          <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px;">
            <span class="niche-tag" style="font-size:.7rem;">Commercial Law</span>
            <span class="niche-tag" style="font-size:.7rem;">KSA</span>
            <span class="niche-tag" style="font-size:.7rem;">UAE</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-xs text-muted font-mono">3 milestones · Posted 5 days ago · 4 bids received</span>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('bid-modal').classList.remove('hidden')">Submit Proposal</button>
          </div>
        </div>

      </div>
    </div>

    <!-- ── RIGHT SIDEBAR ── -->
    <div>

      <div class="sticky-cta" style="margin-bottom:12px;">
        <div style="text-align:center;margin-bottom:20px;">
          <div class="reputation-ring" style="margin:0 auto 12px;"><span>4.97</span></div>
          <div style="font-weight:700;font-size:.9375rem;">FinCorp Egypt</div>
          <div class="text-xs text-muted">2 projects open for proposals</div>
        </div>
        <div class="flex justify-between mb-16 text-sm">
          <span class="text-muted">Avg. Project Value</span>
          <span class="font-mono">$8,400</span>
        </div>
        <button class="btn btn-primary w-full" style="justify-content:center;margin-bottom:10px;" onclick="window.location='messages.html'">Send Message</button>
        <button class="btn btn-ghost w-full" style="justify-content:center;font-size:.8125rem;">Schedule Interview</button>
        <hr class="divider">
        <div class="text-xs text-muted text-center">
          <div>🛡 KYC &amp; Credential Verified</div>
        </div>
      </div>

      <!-- PAYMENT VERIFIED INDICATOR -->
      <div class="payment-indicator mb-12">
        <span>💳</span>
        <div>
          <div style="font-weight:700;font-size:.875rem;">Payment Verified</div>
          <div class="text-xs text-muted">Corporate card on file · All escrow pre-funded before contract</div>
        </div>
      </div>

<!-- ══════════════════ MODALS ══════════════════ -->

<!-- SUBMIT PROPOSAL MODAL -->
<div id="bid-modal" class="modal-backdrop hidden">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3>Submit a Proposal</h3>
        <p class="text-sm text-muted mt-4">Select which open project you're bidding on and outline your approach.</p>
      </div>
      <button class="modal-close" onclick="document.getElementById('bid-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Project</label>
        <select class="form-control">
          <!-- PHP: foreach($activeProjects as $p): <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['title']) ?></option> -->
          <option>Predictive Churn Model — Q2 2025</option>
          <option>GCC Distribution Agreement — Regulatory Review</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Your Proposed Budget</label>
          <div style="position:relative;">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-family:var(--font-mono);color:var(--ink-mid);">$</span>
            <input type="number" class="form-control" style="padding-left:28px;" placeholder="9200">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Proposed Milestones</label>
          <input type="number" class="form-control" placeholder="5" min="1" max="20">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Estimated Delivery</label>
        <input type="text" class="form-control" placeholder="e.g. 35 days from contract signing">
      </div>
      <div class="form-group">
        <label class="form-label">Proposal Message</label>
        <textarea class="form-control" rows="5" placeholder="Briefly introduce yourself, your relevant experience, and your proposed approach to this project. Be specific — generic proposals are rarely shortlisted by FinCorp."></textarea>
        <p class="form-hint mt-4">FinCorp requires credential verification before review. Ensure your profile is fully verified.</p>
      </div>
      <div class="verify-band">
        <span>📋</span>
        <div style="font-size:.8125rem;">An NDA will be auto-generated and sent to you if FinCorp shortlists your proposal. You must sign before accessing full project details.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('bid-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-primary" onclick="document.getElementById('bid-modal').classList.add('hidden');showToast()">Submit Proposal</button>
    </div>
  </div>
</div>

<!-- SAVE CLIENT MODAL -->
<div id="save-modal" class="modal-backdrop hidden">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3>Save Client</h3>
      <button class="modal-close" onclick="document.getElementById('save-modal').classList.add('hidden')">✕</button>
    </div>
    <div class="modal-body">
      <p class="text-sm text-muted mb-16">Saved clients appear in your dashboard for quick access. You'll also receive alerts when they post new projects matching your niche.</p>
      <div style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" id="alert-new-proj" checked style="accent-color:var(--gold);">
        <label for="alert-new-proj" style="font-size:.875rem;">Notify me when FinCorp posts a new project in my niche</label>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('save-modal').classList.add('hidden')">Cancel</button>
      <button class="btn btn-gold" onclick="document.getElementById('save-modal').classList.add('hidden');showToastSave()">★ Save Client</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast-stack" id="toast-stack"></div>

<script>
/* ── TABS ── */
function switchTab(i) {
  document.querySelectorAll('.tabs .tab-item').forEach((t,j) => t.classList.toggle('active', i===j));
  ['tab-0','tab-1','tab-2','tab-3'].forEach((id,j) => {
    const el = document.getElementById(id);
    if(el) el.classList.toggle('hidden', i!==j);
  });
}

/* ── TOAST ── */
function showToast() {
  const s = document.getElementById('toast-stack');
  s.innerHTML = `<div class="toast success"><span class="toast-icon">✓</span><div><div class="toast-title">Proposal submitted</div><div class="toast-body">FinCorp Egypt has been notified. NDA will be sent if you are shortlisted.</div></div></div>`;
  setTimeout(()=>s.innerHTML='', 5000);
}
function showToastSave() {
  const s = document.getElementById('toast-stack');
  s.innerHTML = `<div class="toast success"><span class="toast-icon">★</span><div><div class="toast-title">Client saved</div><div class="toast-body">You'll be notified when FinCorp posts new projects in your niche.</div></div></div>`;
  setTimeout(()=>s.innerHTML='', 4000);
}

function toggleDD() {
  document.getElementById('user-dd').classList.toggle('hidden');
}

document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});
</script>
</body>
</html>
