<?php
require_once __DIR__ . '/includes/vars.php';
$pageTitle = 'Branding &amp; Visual Identity | Systemiks';
$pageDesc  = 'Brand strategy and visual identity for businesses in Laval and Montreal. Logo, color palette, guidelines, and full rollout — we build brands that own their space.';
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
      <span class="badge badge--gold">Branding &amp; Identity</span>
      <h1>A brand that owns its space —<br>from strategy to full identity</h1>
      <p class="page-hero-lead">Memorable brands built for longevity. Strategy, visual identity, guidelines, and rollout — everything needed to show up consistently and confidently in your market.</p>
      <div class="page-hero-cta">
        <a href="<?= htmlspecialchars($ctaUrl) ?>" class="btn btn--gold">Book a call</a>
        <a href="/work.php" class="btn btn--ghost-white">See our work</a>
      </div>
    </section>

    <section class="service-pillars">
      <div class="section__inner">
        <div class="section-intro section-intro--center reveal-on-scroll">
          <span class="section-intro__eyebrow">How we do it</span>
          <h2 class="section-intro__heading">Strategy before visuals</h2>
          <p class="section-intro__sub">Solid strategy ensures your visuals aren't just pretty shapes — they're strategic assets that work hard for your business.</p>
        </div>
        <div class="service-pillars-grid">
          <div class="pillar-card reveal-on-scroll">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9.663 17h4.674M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg></div>
            <div class="pillar-card__num">01</div>
            <h3>Brand strategy</h3>
            <p>Positioning, voice, audience, differentiation. We define what you stand for before we design anything.</p>
            <ul class="pillar-card__includes"><li>Market positioning</li><li>Messaging framework</li><li>Audience mapping</li></ul>
          </div>
          <div class="pillar-card reveal-on-scroll reveal-delay-1">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
            <div class="pillar-card__num">02</div>
            <h3>Visual identity</h3>
            <p>Logo, colour palette, typography, and imagery style. A cohesive system that looks distinctive at every touchpoint.</p>
            <ul class="pillar-card__includes"><li>Logo (primary + variants)</li><li>Colour + typography</li><li>Photography direction</li></ul>
          </div>
          <div class="pillar-card reveal-on-scroll reveal-delay-2">
            <div class="pillar-card__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div>
            <div class="pillar-card__num">03</div>
            <h3>Brand guidelines</h3>
            <p>A clear, comprehensive document so your team — and your agency — applies the brand consistently every time.</p>
            <ul class="pillar-card__includes"><li>20–50 page brand book</li><li>Usage rules + do's/don'ts</li><li>File delivery (all formats)</li></ul>
          </div>
        </div>
      </div>
    </section>

    <section class="section--dark-feature reveal-on-scroll">
      <div class="section__inner">
        <div class="section-intro section-intro--center">
          <span class="section-intro__eyebrow">Our process</span>
          <h2 class="section-intro__heading">How a brand project unfolds</h2>
        </div>
        <div class="process-timeline">
          <div class="process-timeline__step">
            <div class="process-timeline__num">01</div>
            <h4 class="process-timeline__title">Brand audit</h4>
            <p class="process-timeline__desc">Current state, market position, competitor landscape</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">02</div>
            <h4 class="process-timeline__title">Strategy</h4>
            <p class="process-timeline__desc">Positioning, voice, visual direction, target audience</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">03</div>
            <h4 class="process-timeline__title">Identity</h4>
            <p class="process-timeline__desc">Logo, palette, typography, guidelines document</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">04</div>
            <h4 class="process-timeline__title">Rollout</h4>
            <p class="process-timeline__desc">Templates, digital assets, launch kit delivery</p>
          </div>
        </div>
      </div>
    </section>

    <section class="stats-section stats-section--light reveal-on-scroll">
      <div class="section__inner">
        <div class="section-intro section-intro--center">
          <span class="section-intro__eyebrow">What to expect</span>
          <h2 class="section-intro__heading">Everything delivered, nothing left out</h2>
        </div>
        <div class="stats-grid">
          <div class="stat-block"><span class="stat-block__value">6w</span><span class="stat-block__label">Avg brand identity timeline</span></div>
          <div class="stat-block"><span class="stat-block__value">3</span><span class="stat-block__label">Included revision rounds</span></div>
          <div class="stat-block"><span class="stat-block__value">100%</span><span class="stat-block__label">Brand guidelines delivered</span></div>
          <div class="stat-block"><span class="stat-block__value">Yours</span><span class="stat-block__label">All source files, forever</span></div>
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
      <h2>Questions about branding</h2>
      <ul class="accordion" role="list">
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>What does a full branding project include?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Brand strategy, logo design (primary + variants), colour palette, typography system, and a brand guidelines document (20–50 pages). All source files delivered in full.</p></div>
        </li>
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>We already have a logo — can you just refresh it?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Yes. We offer brand refresh projects that evolve what exists without starting from scratch. We'll audit your current brand and recommend the right scope.</p></div>
        </li>
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>Do you handle brand rollout (social templates, etc.)?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Yes. Asset packs — social templates, email signatures, letterheads, presentation templates — are available as an add-on to any branding project.</p></div>
        </li>
        <li class="accordion-item">
          <button type="button" class="accordion-trigger"><span>How many revisions are included?</span><span class="icon">+</span></button>
          <div class="accordion-body"><p>Three revision rounds are standard across all deliverables. Additional rounds are available if needed. We set clear expectations before we start.</p></div>
        </li>
      </ul>
    </section>

    <section class="cta-bar">
      <div class="cta-bar-text">
        <h2>Ready to build a brand that owns its market?</h2>
        <p>Let's start with a discovery call — no commitment, just an honest conversation.</p>
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
