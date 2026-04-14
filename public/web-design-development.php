<?php
require_once __DIR__ . '/includes/vars.php';
$pageTitle = 'Web Design &amp; Development | Systemiks';
$pageDesc  = 'Custom, high-performance websites in Laval and Montreal. E-commerce, CMS, showcase sites — built to convert and grow your business.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
<div id="site-wrap" class="site-wrap">
  <div class="site-wrap-inner">
<?php require_once __DIR__ . '/includes/header.php'; ?>

    <section class="page-hero page-hero--dark">
      <span class="badge badge--gold">Web Design &amp; Development</span>
      <h1>Websites that don't just look good —<br>they grow your business</h1>
      <p class="page-hero-lead">Custom sites built for speed, conversion, and your goals. E-commerce, CMS, or custom builds — we deliver performance-first web projects that pay for themselves.</p>
      <div class="page-hero-cta">
        <a href="<?= htmlspecialchars($ctaUrl) ?>" class="btn btn--gold">Book a call</a>
        <a href="/work.php" class="btn btn--ghost-white">See our work</a>
      </div>
    </section>

    <section class="service-pillars">
      <div class="section__inner">
        <div class="section-intro section-intro--center reveal-on-scroll">
          <span class="section-intro__eyebrow">How we do it</span>
          <h2 class="section-intro__heading">Everything your website needs</h2>
          <p class="section-intro__sub">From strategy through launch — every deliverable covered under one roof.</p>
        </div>
        <div class="service-pillars-grid">
          <div class="pillar-card reveal-on-scroll">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
            <div class="pillar-card__num">01</div>
            <h3>Strategy &amp; discovery</h3>
            <p>We start with your goals, audience, and competitors — not a template. Every decision is backed by research.</p>
            <ul class="pillar-card__includes"><li>Goal alignment</li><li>Competitor audit</li><li>UX planning</li></ul>
          </div>
          <div class="pillar-card reveal-on-scroll reveal-delay-1">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg></div>
            <div class="pillar-card__num">02</div>
            <h3>Design &amp; build</h3>
            <p>Mobile-first design, clean code, and a CMS you can actually manage. Built for speed and Core Web Vitals.</p>
            <ul class="pillar-card__includes"><li>Responsive design</li><li>WordPress / Shopify / Custom</li><li>Performance optimized</li></ul>
          </div>
          <div class="pillar-card reveal-on-scroll reveal-delay-2">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218a1.125 1.125 0 001.12-.848l.75-5.25a1.125 1.125 0 00-1.12-1.297H4.125a1.125 1.125 0 00-1.12 1.297l.75 5.25m-3 0V11.25A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v.75m-15 0h15.75"/></svg></div>
            <div class="pillar-card__num">03</div>
            <h3>E-commerce &amp; CMS</h3>
            <p>Full online stores with payment, inventory, and fulfillment. Or a CMS you manage yourself after training.</p>
            <ul class="pillar-card__includes"><li>Shopify / WooCommerce</li><li>Product catalogues</li><li>CMS handoff + training</li></ul>
          </div>
          <div class="pillar-card reveal-on-scroll reveal-delay-3">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15.59 14.37A6 6 0 019.72 21.8v-4.8m5.87-2.63A15 15 0 009.66 4.45 12 12 0 019 9c0 2.4-.5 4.65-1.35 6.59"/><path d="M12 15l3-3m0 0l3 3m-3-3v6"/></svg></div>
            <div class="pillar-card__num">04</div>
            <h3>Launch &amp; support</h3>
            <p>We don't just flip a switch. Every launch includes a performance audit, SEO check, and 30-day support window.</p>
            <ul class="pillar-card__includes"><li>Pre-launch audit</li><li>SEO structure check</li><li>30-day post-launch support</li></ul>
          </div>
        </div>
      </div>
    </section>

    <section class="section--dark-feature reveal-on-scroll">
      <div class="section__inner">
        <div class="section-intro section-intro--center">
          <span class="section-intro__eyebrow">Our process</span>
          <h2 class="section-intro__heading">How a web project unfolds</h2>
        </div>
        <div class="process-timeline">
          <div class="process-timeline__step">
            <div class="process-timeline__num">01</div>
            <h4 class="process-timeline__title">Discovery</h4>
            <p class="process-timeline__desc">Goals, audience, competitor analysis, sitemap</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">02</div>
            <h4 class="process-timeline__title">Design</h4>
            <p class="process-timeline__desc">Wireframes, visual design, client review rounds</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">03</div>
            <h4 class="process-timeline__title">Build</h4>
            <p class="process-timeline__desc">Clean code, CMS setup, integrations, testing</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">04</div>
            <h4 class="process-timeline__title">Launch</h4>
            <p class="process-timeline__desc">Performance audit, SEO, handoff + support</p>
          </div>
        </div>
      </div>
    </section>

    <section class="stats-section stats-section--light reveal-on-scroll">
      <div class="section__inner">
        <div class="section-intro section-intro--center">
          <span class="section-intro__eyebrow">What to expect</span>
          <h2 class="section-intro__heading">Results that speak for themselves</h2>
        </div>
        <div class="stats-grid">
          <div class="stat-block"><span class="stat-block__value">3x</span><span class="stat-block__label">Avg conversion rate improvement</span></div>
          <div class="stat-block"><span class="stat-block__value">&lt;2s</span><span class="stat-block__label">Target page load time</span></div>
          <div class="stat-block"><span class="stat-block__value">100%</span><span class="stat-block__label">Mobile-responsive delivery</span></div>
          <div class="stat-block"><span class="stat-block__value">6–12w</span><span class="stat-block__label">Typical project timeline</span></div>
        </div>
      </div>
    </section>

    <section class="section--white reveal-on-scroll" style="padding: 5rem 1.5rem; text-align:center;">
      <div class="section__inner">
        <span class="section-intro__eyebrow">Our portfolio</span>
        <h2 class="section-intro__heading" style="max-width:600px; margin: 1rem auto;">See real results for real brands</h2>
        <p style="color: var(--text-muted); max-width:480px; margin: 0 auto 2rem;">Custom-built sites for CB Legal, BabAtlas Car, TaxiVan-Medic and more — see how the right website converts visitors into clients.</p>
        <a href="/work.php" class="btn btn--primary">View our work →</a>
      </div>
    </section>

    <section class="service-faq reveal-on-scroll">
      <h2>Questions about web design</h2>
      <ul class="accordion" role="list">
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>What does a web project include?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Strategy, UX design, responsive build, CMS setup (WordPress, Shopify, or custom), performance optimization, and a 30-day post-launch support window. Every project is scoped to your goals — no templates.</p></div>
        </li>
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>How long does a website project take?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Most projects run 6–12 weeks depending on scope. We give you a clear timeline and milestones in the proposal before any work begins.</p></div>
        </li>
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>Do you work with existing websites?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Yes. Redesigns, rebuilds, and CRO audits on existing sites are all projects we take on. We'll audit what's there first and recommend the most cost-effective path forward.</p></div>
        </li>
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>Can we manage the site ourselves after launch?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Yes. CMS setups are handed over with a training session and full documentation. You own the site — we hand you the keys.</p></div>
        </li>
      </ul>
    </section>

    <section class="cta-bar">
      <div class="cta-bar-text">
        <h2>Ready for a website that actually grows your business?</h2>
        <p>Book a free discovery call — no pitch, just an honest conversation about your goals.</p>
      </div>
      <a href="<?= htmlspecialchars($ctaUrl) ?>" class="btn btn--gold">Book a call</a>
    </section>

    <div id="footer-root"></div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/offcanvas-menu.php'; ?>
<script src="/assets/js/main.js"></script>
<script type="module" src="/assets/embed.js"></script>
</body>
</html>
