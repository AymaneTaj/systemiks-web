<?php
require_once __DIR__ . '/includes/vars.php';
$pageTitle = 'Services — Web, Branding, SEO &amp; Advertising | Systemiks';
$pageDesc  = 'Full-service digital agency: custom web design, brand identity, SEO, and paid advertising. One partner for your entire digital journey.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body class="services-page">
<div id="site-wrap" class="site-wrap">
  <div class="site-wrap-inner">
<?php $headerTransparent = false; require_once __DIR__ . '/includes/header.php'; ?>

    <section class="page-hero reveal-on-scroll">
      <span class="badge badge--electric">Full-service digital agency</span>
      <h1>Services that turn visibility into revenue</h1>
      <p class="page-hero-lead">One partner. Web, brand, SEO, and advertising — built for brands ready to own their market. Use one service or combine them for maximum impact.</p>
    </section>

    <section style="padding: 5rem 1.5rem; background: var(--light-0);">
      <div class="section__inner">
        <div class="service-cards-grid">
          <a href="/web-design-development.php" class="service-card reveal-on-scroll" data-service="web">
            <div class="service-card__header">
              <span class="service-card__num">01</span>
              <span class="badge badge--electric">Web</span>
            </div>
            <h3 class="service-card__title">Web Design &amp; Development</h3>
            <p class="service-card__desc">Custom, high-performance websites — showcase sites, e-commerce, CMS platforms. Built for speed, mobile, and conversion. Your site as a growth engine.</p>
            <div class="service-card__footer">
              <span class="service-card__deliverables">Custom · E-commerce · CMS · WordPress · Shopify</span>
              <span class="service-card__arrow">Explore →</span>
            </div>
          </a>
          <a href="/branding.php" class="service-card reveal-on-scroll reveal-delay-1" data-service="branding">
            <div class="service-card__header">
              <span class="service-card__num">02</span>
              <span class="badge badge--neutral">Brand</span>
            </div>
            <h3 class="service-card__title">Branding &amp; Identity</h3>
            <p class="service-card__desc">Memorable brands that own their space. Brand strategy, visual identity, guidelines, and rollout — from logo to full brand system.</p>
            <div class="service-card__footer">
              <span class="service-card__deliverables">Strategy · Logo · Identity · Guidelines · Templates</span>
              <span class="service-card__arrow">Explore →</span>
            </div>
          </a>
          <a href="/seo-marketing.php" class="service-card reveal-on-scroll reveal-delay-2" data-service="seo">
            <div class="service-card__header">
              <span class="service-card__num">03</span>
              <span class="badge badge--neutral">SEO</span>
            </div>
            <h3 class="service-card__title">SEO &amp; Content Marketing</h3>
            <p class="service-card__desc">Organic growth that compounds. Found by the right people, every day. Technical SEO, content strategy, local SEO, and transparent monthly reporting.</p>
            <div class="service-card__footer">
              <span class="service-card__deliverables">Technical · Content · Local · Link building · Reporting</span>
              <span class="service-card__arrow">Explore →</span>
            </div>
          </a>
          <a href="/advertising.php" class="service-card reveal-on-scroll reveal-delay-3" data-service="advertising">
            <div class="service-card__header">
              <span class="service-card__num">04</span>
              <span class="badge badge--gold">PPC</span>
            </div>
            <h3 class="service-card__title">Paid Advertising</h3>
            <p class="service-card__desc">Ad spend that converts. Google, Meta, and TikTok campaigns built for ROI — not vanity metrics. Real attribution, no black box, full transparency.</p>
            <div class="service-card__footer">
              <span class="service-card__deliverables">Google Ads · Meta · TikTok · Creatives · ROAS reporting</span>
              <span class="service-card__arrow">Explore →</span>
            </div>
          </a>
        </div>

        <div class="stats-grid reveal-on-scroll" style="margin-top: 4rem;">
          <div class="stat-block">
            <span class="stat-block__value">50+</span>
            <span class="stat-block__label">Projects delivered</span>
          </div>
          <div class="stat-block">
            <span class="stat-block__value">8+</span>
            <span class="stat-block__label">Platform certifications</span>
          </div>
          <div class="stat-block">
            <span class="stat-block__value">4.5★</span>
            <span class="stat-block__label">Average Google rating</span>
          </div>
        </div>
      </div>
    </section>

    <?php require_once __DIR__ . '/includes/work-comparison.php'; ?>

    <section class="cta-bar">
      <div class="cta-bar-text">
        <h2>One team. Four services. One goal: your growth.</h2>
        <p>Book a free discovery call — we'll identify which services match your goals.</p>
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
