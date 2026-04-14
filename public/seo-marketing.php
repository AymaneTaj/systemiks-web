<?php
require_once __DIR__ . '/includes/vars.php';
$pageTitle = 'SEO &amp; Content Marketing | Systemiks';
$pageDesc  = 'Organic growth that compounds. Technical SEO, content strategy, and local SEO for businesses in Laval and Montreal. Transparent monthly reporting.';
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
      <span class="badge badge--gold">SEO &amp; Content Marketing</span>
      <h1>Organic growth that compounds.<br>Found by the right people, every day.</h1>
      <p class="page-hero-lead">Technical SEO, content strategy, local search, and transparent monthly reporting. We build the kind of organic presence that keeps paying off long after the work is done.</p>
      <div class="page-hero-cta">
        <a href="<?= htmlspecialchars($ctaUrl) ?>" class="btn btn--gold">Book a call</a>
        <a href="/work.php" class="btn btn--ghost-white">See our work</a>
      </div>
    </section>

    <section class="service-pillars">
      <div class="section__inner">
        <div class="section-intro section-intro--center reveal-on-scroll">
          <span class="section-intro__eyebrow">How we do it</span>
          <h2 class="section-intro__heading">SEO that drives real revenue</h2>
          <p class="section-intro__sub">We start where your customers search — and build a strategy around what actually drives leads and sales, not just rankings.</p>
        </div>
        <div class="service-pillars-grid">
          <div class="pillar-card reveal-on-scroll">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg></div>
            <div class="pillar-card__num">01</div>
            <h3>Search &amp; growth strategy</h3>
            <p>Keyword research, competitor analysis, topic clusters — a roadmap that maps to your buyer journey from awareness to decision.</p>
            <ul class="pillar-card__includes"><li>Keyword research</li><li>Competitor gap analysis</li><li>Topic cluster mapping</li></ul>
          </div>
          <div class="pillar-card reveal-on-scroll reveal-delay-1">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2.25 18L9 11.25l4.306 4.307L22.25 6.75M2.25 18h6.75M2.25 18v-6.75"/></svg></div>
            <div class="pillar-card__num">02</div>
            <h3>Technical SEO</h3>
            <p>Site speed, crawlability, indexation, schema markup, Core Web Vitals — the foundation that lets your content rank.</p>
            <ul class="pillar-card__includes"><li>Site audit + fixes</li><li>Schema markup</li><li>Core Web Vitals</li></ul>
          </div>
          <div class="pillar-card reveal-on-scroll reveal-delay-2">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div>
            <div class="pillar-card__num">03</div>
            <h3>Content that ranks</h3>
            <p>We write every piece in-house, targeting specific keywords and user intent. You review and approve before anything publishes.</p>
            <ul class="pillar-card__includes"><li>Articles + blog posts</li><li>Landing pages</li><li>Local SEO content</li></ul>
          </div>
          <div class="pillar-card reveal-on-scroll reveal-delay-3">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div>
            <div class="pillar-card__num">04</div>
            <h3>Reporting &amp; analytics</h3>
            <p>Monthly reports with keyword rankings, traffic growth, lead attribution, and a clear action plan for next month. No black box.</p>
            <ul class="pillar-card__includes"><li>Monthly ranking reports</li><li>GA4 + Search Console</li><li>Lead attribution</li></ul>
          </div>
        </div>
      </div>
    </section>

    <section class="section--dark-feature reveal-on-scroll">
      <div class="section__inner">
        <div class="section-intro section-intro--center">
          <span class="section-intro__eyebrow">Our process</span>
          <h2 class="section-intro__heading">How SEO gets done</h2>
        </div>
        <div class="process-timeline">
          <div class="process-timeline__step">
            <div class="process-timeline__num">01</div>
            <h4 class="process-timeline__title">Audit</h4>
            <p class="process-timeline__desc">Technical gaps, keyword baseline, competitor landscape</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">02</div>
            <h4 class="process-timeline__title">Strategy</h4>
            <p class="process-timeline__desc">Topic clusters, priority pages, backlink plan</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">03</div>
            <h4 class="process-timeline__title">Execution</h4>
            <p class="process-timeline__desc">On-page fixes, content creation, link building</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">04</div>
            <h4 class="process-timeline__title">Reporting</h4>
            <p class="process-timeline__desc">Monthly tracking, pivots, growth planning</p>
          </div>
        </div>
      </div>
    </section>

    <section class="stats-section stats-section--light reveal-on-scroll">
      <div class="section__inner">
        <div class="section-intro section-intro--center">
          <span class="section-intro__eyebrow">What to expect</span>
          <h2 class="section-intro__heading">Organic growth you can measure</h2>
        </div>
        <div class="stats-grid">
          <div class="stat-block"><span class="stat-block__value">+124%</span><span class="stat-block__label">Avg organic traffic in 6 months</span></div>
          <div class="stat-block"><span class="stat-block__value">Top 3</span><span class="stat-block__label">Target keyword positions</span></div>
          <div class="stat-block"><span class="stat-block__value">3–6mo</span><span class="stat-block__label">Typical result horizon</span></div>
          <div class="stat-block"><span class="stat-block__value">Monthly</span><span class="stat-block__label">Reporting frequency</span></div>
        </div>
      </div>
    </section>

    <section class="section--white reveal-on-scroll" style="padding: 5rem 1.5rem; text-align:center;">
      <div class="section__inner">
        <span class="section-intro__eyebrow">Our portfolio</span>
        <h2 class="section-intro__heading" style="max-width:600px; margin: 1rem auto;">See real results for real brands</h2>
        <p style="color: var(--text-muted); max-width:480px; margin: 0 auto 2rem;">Real projects, real metrics. CB Legal, BabAtlas Car, Zoon Pet, and more.</p>
        <a href="/work.php" class="btn btn--primary">View our work →</a>
      </div>
    </section>

    <section class="section--white reveal-on-scroll" style="padding: 3rem 1.5rem; background: var(--light-1);">
      <div class="section__inner" style="max-width: 700px; text-align:center;">
        <span class="section-intro__eyebrow">Transparent pricing</span>
        <h3 style="font-size: var(--text-2xl); margin: 0.75rem 0;">Custom scoped — no generic packages</h3>
        <p style="color: var(--text-muted); margin: 0 0 1.5rem;">Every project is scoped to your goals. We quote after a discovery call — no guessing, no hidden costs. Book a call and get a clear proposal within 48 hours.</p>
        <a href="/contact.php" class="btn btn--gold">Get a quote →</a>
      </div>
    </section>

    <section class="service-faq reveal-on-scroll">
      <h2>Questions about SEO</h2>
      <ul class="accordion" role="list">
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>How long before we see SEO results?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Typically 3–6 months for meaningful organic traffic growth. We set realistic expectations from day one and track progress monthly so you see the trajectory early.</p></div>
        </li>
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>Do you write the content or do we?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>We handle all content creation as part of the SEO strategy. You review and approve before anything publishes. We write to rank — and to convert.</p></div>
        </li>
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>Do you do local SEO for Laval and Montreal?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Yes — local SEO is a specialty. We optimize Google Business Profile, build local citations, create geo-targeted content, and help you show up when people search "near me" in your market.</p></div>
        </li>
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>What does monthly reporting look like?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>You get a plain-language monthly report: keyword rankings, organic traffic growth, on-page progress, lead attribution, and a clear summary of what we did and what's coming next. No jargon.</p></div>
        </li>
      </ul>
    </section>

    <section class="cta-bar">
      <div class="cta-bar-text">
        <h2>Ready to get found by the right people?</h2>
        <p>Book a discovery call — we'll audit your current organic presence for free.</p>
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
