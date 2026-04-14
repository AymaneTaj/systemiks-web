<?php
require_once __DIR__ . '/includes/vars.php';
$pageTitle = 'About Us | Systemiks — Digital Agency Laval &amp; Montreal';
$pageDesc  = 'Meet the Systemiks team. Selective digital partner helping brands in Laval and Montreal grow with web design, branding, SEO, and paid advertising.';
$headerTransparent = false;
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

    <section class="page-hero reveal-on-scroll">
      <span class="badge badge--electric">Laval &amp; Montreal</span>
      <h1>We partner with brands<br>ready to own their market</h1>
      <p class="page-hero-lead">Selective. Strategic. We don't work with everyone — we work with the clients we can genuinely move the needle for.</p>
    </section>

    <section class="section section--white" style="padding: 4rem 1.5rem;">
      <div class="section__inner">
        <div class="section-intro section-intro--center reveal-on-scroll">
          <span class="section-intro__eyebrow">What we stand for</span>
          <h2 class="section-intro__heading">The Systemiks way</h2>
        </div>
        <div class="value-cards">
          <div class="card card--number reveal-on-scroll">
            <div class="card__num">01</div>
            <h3>Selective partnerships</h3>
            <p>We choose our partners. We work with brands that are ready to grow — clear goals, commitment to the long term, and a market worth winning.</p>
          </div>
          <div class="card card--number reveal-on-scroll reveal-delay-1">
            <div class="card__num">02</div>
            <h3>Performance obsession</h3>
            <p>We measure success in revenue impact, visibility, and real results. No vanity metrics. You get clear reporting and a team that cares about what moves the needle.</p>
          </div>
          <div class="card card--number reveal-on-scroll reveal-delay-2">
            <div class="card__num">03</div>
            <h3>Long-term partnership</h3>
            <p>We don't chase quick wins and disappear. Strategy, web, brand, SEO, and advertising — aligned, accountable, and built for the long game.</p>
          </div>
        </div>
      </div>
    </section>

    <main class="about-content">
      <section class="about-block reveal-on-scroll">
        <h2 class="about-heading">Our story</h2>
        <p class="about-body">Systemiks exists to be the one partner ambitious brands need: high-performance websites, memorable branding, data-driven SEO, and performance advertising under one roof. Certified by Google, Meta, and TikTok, we're based in Laval and serve clients across Montreal, Quebec, and beyond — startups and established businesses ready to own their market.</p>
        <img
          src="/assets/images/about/office.png"
          alt="Systemiks office — Laval, Quebec"
          class="about-office-img"
          width="700"
          height="700"
          loading="lazy"
        >
      </section>

      <section class="about-block reveal-on-scroll reveal-delay-1">
        <h2 class="about-heading">How we work</h2>
        <p class="about-body">We start with your goals and your audience. Every project is tailored — strategy, design, and execution aligned so your website, brand, and campaigns work together. Measurable results, clear reporting, long-term partnership. No black box, no one-size-fits-all. You get a dedicated team that cares about your success.</p>
      </section>

      <div class="about-standout reveal-on-scroll">
        <p>If you need convincing, we're not the right fit. If you're ready to invest in growth and own your space — <a href="<?= htmlspecialchars($ctaUrl) ?>" class="about-link">let's talk</a>.</p>
      </div>

      <section class="about-block reveal-on-scroll" style="margin-top: 2rem;">
        <h2 class="about-heading">Based in Laval — serving all of Quebec</h2>
        <p class="about-body">Our team is based at 271 Boul des Prairies, Laval, QC. We work with businesses in Montreal, Laval, Quebec City, and across Canada. Most client relationships are remote-friendly — we show up in person when it counts.</p>
        <div style="margin-top: 1rem;">
          <a href="tel:+15147461644" style="color: var(--blue); font-weight: 600; text-decoration: none;">+1 514-746-1644</a>
          <span style="margin: 0 0.75rem; color: var(--text-muted);">·</span>
          <a href="mailto:info@systemiks.ca" style="color: var(--blue); font-weight: 600; text-decoration: none;">info@systemiks.ca</a>
        </div>
      </section>

      <section class="about-block reveal-on-scroll reveal-delay-2">
        <h2 class="about-heading">Why work with us</h2>
        <ul class="about-list">
          <li><strong>Certified partners</strong> — Google Partner, Meta Business Partner, TikTok Certified</li>
          <li><strong>Full stack</strong> — Web, branding, SEO, and paid advertising in one place</li>
          <li><strong>Transparent reporting</strong> — Clear metrics, regular updates, honest communication</li>
          <li><strong>Results-driven</strong> — Traffic, leads, and revenue — not vanity metrics</li>
          <li><strong>Local presence</strong> — Based in Laval; serving Montreal, Quebec, and beyond</li>
        </ul>
      </section>

      <div class="cert-strip reveal-on-scroll">
        <div class="cert-badge">
          <img src="/assets/images/partners/google-partner.svg" alt="Google Partner">
          <div>
            <span class="cert-badge__name">Google Partner</span>
            <span class="cert-badge__type">Certified</span>
          </div>
        </div>
        <div class="cert-badge">
          <img src="/assets/images/partners/meta-partner.svg" alt="Meta Business Partner">
          <div>
            <span class="cert-badge__name">Meta Business Partner</span>
            <span class="cert-badge__type">Certified</span>
          </div>
        </div>
        <div class="cert-badge">
          <img src="/assets/images/partners/tiktok.svg" alt="TikTok">
          <div>
            <span class="cert-badge__name">TikTok</span>
            <span class="cert-badge__type">Certified</span>
          </div>
        </div>
        <div class="cert-badge">
          <img src="/assets/images/partners/shopify.svg" alt="Shopify Partner">
          <div>
            <span class="cert-badge__name">Shopify Partner</span>
            <span class="cert-badge__type">Certified</span>
          </div>
        </div>
      </div>

      <section class="about-block reveal-on-scroll reveal-delay-3">
        <h2 class="about-heading">Get in touch</h2>
        <p class="about-body">New website, brand refresh, SEO, or performance campaigns — we're here. <a href="<?= htmlspecialchars($ctaUrl) ?>" class="about-link">Book a discovery call</a> or send a message. We respond within 24 hours.</p>
      </section>
    </main>

    <section class="section--white reveal-on-scroll" style="padding: 5rem 1.5rem; background: var(--light-1);">
      <div class="section__inner">
        <div class="section-intro section-intro--center">
          <span class="section-intro__eyebrow">From inquiry to partnership</span>
          <h2 class="section-intro__heading">How it works</h2>
        </div>
        <div class="process-timeline process-timeline--light">
          <div class="process-timeline__step reveal-on-scroll">
            <div class="process-timeline__num">01</div>
            <h4 class="process-timeline__title">Get in touch</h4>
            <p class="process-timeline__desc">Book a call or send a message. We review every inquiry.</p>
          </div>
          <div class="process-timeline__step reveal-on-scroll reveal-delay-1">
            <div class="process-timeline__num">02</div>
            <h4 class="process-timeline__title">Goals &amp; fit</h4>
            <p class="process-timeline__desc">We review your goals, market, and fit. Only the right partnerships move forward.</p>
          </div>
          <div class="process-timeline__step reveal-on-scroll reveal-delay-2">
            <div class="process-timeline__num">03</div>
            <h4 class="process-timeline__title">Discovery call</h4>
            <p class="process-timeline__desc">A real conversation about your strategy and how we can help.</p>
          </div>
          <div class="process-timeline__step reveal-on-scroll reveal-delay-3">
            <div class="process-timeline__num">04</div>
            <h4 class="process-timeline__title">Partnership</h4>
            <p class="process-timeline__desc">Custom strategy and execution. You get a dedicated team.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="cta-bar">
      <div class="cta-bar-text">
        <h2>For the brands seeking to be found!</h2>
        <p>Book a free discovery call or send a message — we reply within 24 hours. <a href="/work.php" class="cta-bar-link">View our work</a></p>
      </div>
      <a href="<?= htmlspecialchars($ctaUrl) ?>" class="btn btn--gold">Let's talk</a>
    </section>

    <div id="footer-root"></div>

  </div><!-- .site-wrap-inner -->
</div><!-- #site-wrap -->
<?php require_once __DIR__ . '/includes/offcanvas-menu.php'; ?>
    <script src="/assets/js/main.js"></script>
    <script type="module" src="/assets/embed.js"></script>
</body>
</html>
