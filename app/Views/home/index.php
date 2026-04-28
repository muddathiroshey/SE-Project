<?php
if (isset($_SESSION['user_id'])) {
    header('Location: /dashboard');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus — Specialized Professional Marketplace</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .hero-eyebrow {
            font-family: var(--font-mono); font-size: 0.75rem; letter-spacing: 0.2em;
            text-transform: uppercase; color: var(--gold); margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .hero-eyebrow::before { content: ''; width: 32px; height: 1px; background: var(--gold); }
        .hero-headline {
            font-family: var(--font-display); font-size: clamp(2.8rem, 5vw, 5rem);
            font-weight: 300; line-height: 1.08; color: var(--ink);
            letter-spacing: -0.03em; margin-bottom: 28px;
        }
        .hero-headline em { font-style: italic; color: var(--gold); }
        .hero-sub { font-size: 1.05rem; color: var(--ink-mid); max-width: 500px; margin-bottom: 40px; line-height: 1.75; }
        .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
        .hero-aside {
            position: relative; padding: 40px;
        }
        .hero-aside-card {
            background: var(--ivory-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 28px;
            box-shadow: var(--shadow-lg);
            position: relative; z-index: 1;
        }
        .hero-aside-card::before {
            content: '◆';
            position: absolute; top: -18px; right: 28px;
            font-size: 2rem; color: var(--gold); opacity: .7;
        }
        .hero-floater {
            position: absolute; background: var(--ivory-card); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 12px 16px;
            box-shadow: var(--shadow-md); font-size: 0.8125rem; z-index: 2;
        }
        .hero-floater.f1 { bottom: 24px; left: 0; }
        .hero-floater.f2 { top: 32px; right: -20px; min-width: 160px; }
        .niche-grid { display: flex; flex-wrap: wrap; gap: 8px; margin: 20px 0; }
        .niche-pill {
            padding: 6px 14px; border: 1px solid var(--border);
            border-radius: 2px; font-size: 0.75rem;
            font-family: var(--font-mono); color: var(--ink-mid);
            background: var(--ivory-deep);
            white-space: nowrap;
        }
        .niche-pill.featured { border-color: var(--gold-light); background: var(--gold-pale); color: #7A5C10; }
        .how-step {
            display: flex; gap: 24px; align-items: flex-start;
            padding: 28px 0; border-bottom: 1px solid var(--border);
        }
        .how-step:last-child { border-bottom: none; }
        .how-num {
            font-family: var(--font-display); font-size: 3.5rem; font-weight: 300;
            color: var(--gold-light); line-height: 1; flex-shrink: 0; width: 60px;
        }
        .how-body h3 { font-size: 1.15rem; margin-bottom: 8px; }
        .feature-card {
            padding: 32px;
            background: var(--ivory-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            position: relative; overflow: hidden;
        }
        .feature-card::after {
            content: attr(data-symbol);
            position: absolute; bottom: 12px; right: 16px;
            font-family: var(--font-display); font-size: 3rem;
            color: var(--border); pointer-events: none;
            line-height: 1;
        }
        .feature-card h4 { font-size: 1.1rem; margin-bottom: 10px; }
        .testimonial-card {
            background: var(--ivory-card); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 28px;
            position: relative;
        }
        .testimonial-card::before {
            content: '\201C';
            font-family: var(--font-display); font-size: 5rem; color: var(--gold-light);
            position: absolute; top: -8px; left: 20px; line-height: 1;
        }
        .quote-text { font-family: var(--font-display); font-size: 1.05rem; font-style: italic; color: var(--ink-mid); line-height: 1.65; margin-bottom: 20px; padding-top: 24px; }
        .stat-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 0; border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; margin: 60px 0; }
        .stat-box { padding: 40px 32px; text-align: center; border-right: 1px solid var(--border); }
        .stat-box:last-child { border-right: none; }
        .stat-box .val { font-family: var(--font-display); font-size: 3rem; font-weight: 300; color: var(--ink); }
        .stat-box .lbl { font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ink-muted); font-weight: 700; margin-top: 6px; }
        .cta-band {
            background: var(--ink); color: var(--ivory);
            padding: 80px 0; text-align: center; position: relative; overflow: hidden;
        }
        .cta-band::before {
            content: '';
            position: absolute; inset: 0;
            background: repeating-linear-gradient(-45deg, transparent, transparent 40px, rgba(201,168,76,0.04) 40px, rgba(201,168,76,0.04) 41px);
        }
        .cta-band h2 { font-family: var(--font-display); font-size: 2.8rem; font-weight: 300; color: var(--ivory); margin-bottom: 16px; position: relative; z-index: 1; }
        .cta-band p { color: rgba(247,244,239,0.7); margin-bottom: 32px; position: relative; z-index: 1; }
        footer {
            background: var(--ivory-deep);
            border-top: 1px solid var(--border);
            padding: 60px 0 32px;
        }
        .footer-logo { font-family: var(--font-display); font-size: 1.8rem; color: var(--ink); margin-bottom: 12px; }
        .footer-logo span { color: var(--gold); }
        .footer-cols { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 48px; }
        .footer-col h5 { font-size: 0.7rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--ink-muted); font-weight: 700; margin-bottom: 16px; }
        .footer-col a { display: block; font-size: 0.875rem; color: var(--ink-mid); margin-bottom: 10px; }
        .footer-col a:hover { color: var(--gold); }
        .footer-bottom { border-top: 1px solid var(--border); padding-top: 24px; display: flex; justify-content: space-between; align-items: center; }
        .footer-bottom p { font-size: 0.8125rem; color: var(--ink-muted); }
    </style>
</head>
<body>

<!-- TOPNAV -->
<nav class="topnav">
    <div class="container">
        <a class="topnav-logo" href="/">Nexus<span>.</span></a>
        <div class="topnav-links">
            <a href="/browse-experts">Find Experts</a>
            <a href="/post-job">Post a Project</a>
            <a href="/platform-guide#how-it-works">How It Works</a>
            <a href="/platform-guide#niches">Niches</a>
        </div>
        <div class="topnav-actions">
            <a href="/login" class="btn btn-outline btn-sm">Sign In</a>
            <a href="/login" class="btn btn-primary btn-sm">Join as Client</a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero-section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;">
            <div>
                <div class="hero-eyebrow">Verified Expertise, Every Engagement</div>
                <h1 class="hero-headline">Where <em>Specialized</em><br>Expertise Meets<br>High-Stakes Work.</h1>
                <p class="hero-sub">Nexus connects organizations with credentialed professionals in Data Science, Legal Consulting, Technical Translation, and other demanding disciplines — through a rigorously structured, milestone-secured framework.</p>
                <div class="hero-actions">
                    <a href="/post-job" class="btn btn-primary btn-lg">Post a Project</a>
                    <a href="/browse-experts" class="btn btn-outline btn-lg">Browse Experts</a>
                </div>
                <div class="niche-grid" style="margin-top:32px;">
                    <span class="niche-pill featured">Data Science</span>
                    <span class="niche-pill featured">Legal Consulting</span>
                    <span class="niche-pill featured">Technical Translation</span>
                    <span class="niche-pill">Financial Modelling</span>
                    <span class="niche-pill">Biomedical Research</span>
                    <span class="niche-pill">Cybersecurity Audit</span>
                    <span class="niche-pill">+14 niches</span>
                </div>
            </div>
            <div class="hero-aside">
                <div class="hero-aside-card">
                    <div class="flex items-center gap-12 mb-16">
                        <div class="avatar avatar-lg">DR</div>
                        <div>
                            <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;">Dr. Rania Khalil</div>
                            <div class="text-xs text-muted">Senior Data Scientist · Cairo, EG</div>
                            <div class="stars mt-4">★★★★★ <span class="text-xs text-muted" style="margin-left:4px;">4.97 (83 projects)</span></div>
                        </div>
                    </div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
                        <span class="tag">ML Engineering</span>
                        <span class="tag">NLP</span>
                        <span class="tag">Python</span>
                        <span class="badge badge-verified badge-dot">Verified</span>
                    </div>
                    <div class="progress-bar" style="margin-bottom:6px;"><div class="progress-fill" style="width:92%;"></div></div>
                    <div class="text-xs text-muted">92% Milestone Completion Rate</div>
                    <hr class="divider">
                    <div class="flex justify-between" style="font-size:.8125rem;">
                        <span class="text-muted">Current Rate</span>
                        <span style="font-family:var(--font-mono);font-weight:500;">$145 <span class="text-muted">/ hr</span></span>
                    </div>
                </div>
                <div class="hero-floater f1 flex items-center gap-8">
                    <span style="color:var(--sage);">✓</span>
                    <span><strong>$2.4M</strong> in secured escrow today</span>
                </div>
                <div class="hero-floater f2">
                    <div class="text-xs text-muted mb-4">New bid received</div>
                    <div style="font-weight:700;font-size:.875rem;">Marcus T. — Legal</div>
                    <div style="font-family:var(--font-mono);font-size:.75rem;color:var(--gold);">$8,500 · 6 milestones</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS -->
<div class="container">
    <div class="stat-row">
        <div class="stat-box"><div class="val">12,400<span style="font-size:1.6rem;">+</span></div><div class="lbl">Verified Specialists</div></div>
        <div class="stat-box"><div class="val">$94M</div><div class="lbl">Escrowed & Released</div></div>
        <div class="stat-box"><div class="val">17</div><div class="lbl">Professional Niches</div></div>
        <div class="stat-box"><div class="val">98.1%</div><div class="lbl">Dispute Resolution Rate</div></div>
    </div>
</div>

<!-- HOW IT WORKS -->
<section style="padding:80px 0;">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;">
            <div>
                <div class="section-header">
                    <div class="section-title">Process</div>
                    <div class="section-heading">Engineered for<br>Complex Engagements</div>
                </div>
                <div class="how-step">
                    <div class="how-num">01</div>
                    <div class="how-body">
                        <h3>Post with Precision</h3>
                        <p>Our context-aware wizard adapts fields to your chosen niche — language pairs for translation, data stacks for science. No generic forms.</p>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-num">02</div>
                    <div class="how-body">
                        <h3>Select from Verified Bids</h3>
                        <p>Filter 100+ proposals by niche success rate, certification tier, and verified credentials. Conduct a pre-contract technical interview before committing.</p>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-num">03</div>
                    <div class="how-body">
                        <h3>Execute in Secure Milestones</h3>
                        <p>Funds are locked in escrow per phase. Work begins only when the next milestone is funded. Full revision management and scope-change protocols are built in.</p>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-num">04</div>
                    <div class="how-body">
                        <h3>Resolve with Confidence</h3>
                        <p>Our Dispute &amp; Arbitration Hub assembles all evidence automatically. A neutral arbiter issues binding verdicts with full audit trail.</p>
                    </div>
                </div>
            </div>
            <div>
                <div class="section-header">
                    <div class="section-title">Why Nexus</div>
                    <div class="section-heading">Beyond a<br>General Marketplace</div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="feature-card" data-symbol="✦">
                        <h4>Credential Verification</h4>
                        <p>Professional licenses and certifications are validated through a multi-stage automated workflow before any expert goes live.</p>
                    </div>
                    <div class="feature-card" data-symbol="◈">
                        <h4>Milestone Escrow</h4>
                        <p>Funds held securely, released only on bilateral sign-off. Auto-approval if clients miss their inspection window.</p>
                    </div>
                    <div class="feature-card" data-symbol="⬡">
                        <h4>NDA Generation</h4>
                        <p>Digitally signable NDAs are auto-generated the moment a freelancer is shortlisted — protecting both parties from day one.</p>
                    </div>
                    <div class="feature-card" data-symbol="◇">
                        <h4>Encrypted Archive</h4>
                        <p>All communications and deliverables are archived with a non-repudiable audit trail for every critical action.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section style="padding:80px 0;background:var(--ivory-deep);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="text-center mb-32">
            <div class="section-title" style="justify-content:center;display:flex;">Client Voices</div>
            <h2 style="font-family:var(--font-display);font-size:2.2rem;font-weight:300;">Trusted by Organizations<br>That Cannot Afford Mistakes</h2>
        </div>
        <div class="grid-3">
            <div class="testimonial-card">
                <p class="quote-text">The milestone escrow system gave us control we never had on other platforms. Our $40,000 data pipeline project was delivered in phases, each verified before release.</p>
                <div class="flex items-center gap-12">
                    <div class="avatar avatar-sm">AT</div>
                    <div><div style="font-weight:700;font-size:.875rem;">Amira Tawfik</div><div class="text-xs text-muted">Head of Analytics · FinCorp Egypt</div></div>
                </div>
            </div>
            <div class="testimonial-card">
                <p class="quote-text">As a legal consultant, the NDA auto-generation and privacy-controlled profile visibility were non-negotiable. Nexus was the only platform that understood that.</p>
                <div class="flex items-center gap-12">
                    <div class="avatar avatar-sm">JM</div>
                    <div><div style="font-weight:700;font-size:.875rem;">James Moreau</div><div class="text-xs text-muted">International Trade Lawyer · Geneva</div></div>
                </div>
            </div>
            <div class="testimonial-card">
                <p class="quote-text">The dispute arbitration was remarkably fair. Evidence was assembled automatically — no scrambling for chat logs. The arbitrator ruled within 72 hours.</p>
                <div class="flex items-center gap-12">
                    <div class="avatar avatar-sm">LN</div>
                    <div><div style="font-weight:700;font-size:.875rem;">Lena Novak</div><div class="text-xs text-muted">CTO · BioTranslate GmbH</div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<div class="cta-band">
    <div class="container">
        <h2>Ready to Work at the Highest Level?</h2>
        <p>Join 12,400+ verified specialists and the organizations that trust them.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;position:relative;z-index:1;">
            <a href="/login" class="btn btn-gold btn-lg">Apply as a Specialist</a>
            <a href="/post-job" class="btn btn-lg" style="background:transparent;border-color:rgba(247,244,239,.4);color:var(--ivory);">Post a Project</a>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="footer-cols">
            <div>
                <div class="footer-logo">Nexus<span>.</span></div>
                <p style="font-size:.875rem;max-width:280px;">The specialized marketplace for high-stakes professional engagements. Verified credentials. Secured milestones. Structured resolution.</p>
            </div>
            <div class="footer-col">
                <h5>Platform</h5>
                <a href="/browse-experts">Find Experts</a>
                <a href="/post-job">Post a Project</a>
                <a href="/platform-guide#pricing">Pricing</a>
                <a href="/platform-guide#how-it-works">How It Works</a>
            </div>
            <div class="footer-col">
                <h5>Specialists</h5>
                <a href="/platform-guide#become-specialist">Become a Specialist</a>
                <a href="/platform-guide#verification">Credential Verification</a>
                <a href="/platform-guide#niches">Niche Guidelines</a>
                <a href="/platform-guide#earnings">Earnings</a>
            </div>
            <div class="footer-col">
                <h5>Company</h5>
                <a href="/platform-guide#about">About</a>
                <a href="/platform-guide#trust">Trust & Safety</a>
                <a href="/platform-guide#privacy">Privacy Policy</a>
                <a href="/platform-guide#terms">Terms of Service</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Nexus Platform, Inc. All rights reserved.</p>
            <p class="font-mono text-xs">v2.4.1 · Structured. Verified. Secured.</p>
        </div>
    </div>
</footer>

</body>
</html>