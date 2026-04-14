<?php
require_once __DIR__ . '/includes/vars.php';
$projects = [
    ['title' => 'CB Legal', 'tag' => 'Web + Branding', 'img' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=800&q=80', 'service' => 'web', 'excerpt' => 'Full website redesign + brand identity for a Montreal law firm. +140% organic traffic in 6 months.', 'href' => '/work.php'],
    ['title' => 'BabAtlas Car', 'tag' => 'Web + SEO', 'img' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&q=80', 'service' => 'seo', 'excerpt' => 'High-performance automotive showcase site + local SEO campaign. 3x conversion rate improvement.', 'href' => '/work.php'],
    ['title' => 'Zoon Pet', 'tag' => 'E-commerce', 'img' => 'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=800&q=80', 'service' => 'web', 'excerpt' => 'E-commerce store for a premium pet products brand. Full Shopify build with custom UX and brand rollout.', 'href' => '/work.php'],
    ['title' => 'Cool Cook', 'tag' => 'Web + Branding', 'img' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&q=80', 'service' => 'branding', 'excerpt' => 'Brand identity and e-commerce platform for a Montreal food company. +220% online revenue.', 'href' => '/work.php'],
    ['title' => 'TaxiVan-Medic', 'tag' => 'Web + Advertising', 'img' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800&q=80', 'service' => 'ppc', 'excerpt' => 'Lead generation website and Google Ads campaigns for a medical transportation service. 4.2x ROAS.', 'href' => '/work.php'],
    ['title' => 'Ms-Production', 'tag' => 'Web + Branding', 'img' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&q=80', 'service' => 'branding', 'excerpt' => 'Creative agency website and full visual identity. Portfolio showcase with booking flow.', 'href' => '/work.php'],
    ['title' => 'Local SEO Campaign', 'tag' => 'SEO', 'img' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?w=800&q=80', 'service' => 'seo', 'excerpt' => 'Technical SEO + local content strategy. Ranked in top 3 positions for 40+ high-intent keywords.', 'href' => '/work.php'],
    ['title' => 'Performance Ads', 'tag' => 'PPC', 'img' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=800&q=80', 'service' => 'ppc', 'excerpt' => 'Google + Meta advertising for a Quebec retail brand. Scaled from 1.8x to 4.5x ROAS in 3 months.', 'href' => '/work.php'],
    ['title' => 'Full Digital Rebrand', 'tag' => 'Web + Brand + SEO', 'img' => 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=800&q=80', 'service' => 'web', 'excerpt' => 'End-to-end rebrand, new website, and SEO strategy for a professional services firm.', 'href' => '/work.php'],
];
$pageTitle = 'Our Work — Portfolio | Systemiks';
$pageDesc  = 'Web design, branding, SEO, and advertising projects by Systemiks. Real results for real brands in Montreal and Laval.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body class="work-page">
<div id="site-wrap" class="site-wrap">
  <div class="site-wrap-inner">
<?php require_once __DIR__ . '/includes/header.php'; ?>

    <section class="page-hero reveal-on-scroll">
      <span class="badge badge--electric">Portfolio</span>
      <h1>Work that speaks in results</h1>
      <p class="page-hero-lead">Real projects, real metrics. Filter by service — we let the outcomes do the talking.</p>
    </section>

    <section class="section--dark-feature work-stats-strip reveal-on-scroll">
      <div class="section__inner">
        <div class="stats-grid">
          <div class="stat-block stat-block--dark">
            <span class="stat-block__value">50+</span>
            <span class="stat-block__label">Projects delivered</span>
          </div>
          <div class="stat-block stat-block--dark">
            <span class="stat-block__value">4</span>
            <span class="stat-block__label">Core services</span>
          </div>
          <div class="stat-block stat-block--dark">
            <span class="stat-block__value">4.5★</span>
            <span class="stat-block__label">Google rating</span>
          </div>
          <div class="stat-block stat-block--dark">
            <span class="stat-block__value">3x</span>
            <span class="stat-block__label">Avg conversion lift</span>
          </div>
        </div>
      </div>
    </section>

    <section class="work-filter-section" style="padding: 4rem 1.5rem;">
      <div class="section__inner">
        <div class="section-intro section-intro--center reveal-on-scroll">
          <span class="section-intro__eyebrow">Portfolio</span>
          <h2 class="section-intro__heading">Our work</h2>
        </div>
        <div id="work-root"></div>
      </div>
    </section>

    <section class="cta-bar">
      <div class="cta-bar-text">
        <h2>Like what you see?</h2>
        <p>Let's discuss what we can achieve for your business.</p>
      </div>
      <a href="<?= htmlspecialchars($ctaUrl) ?>" class="btn btn--gold">Let's talk</a>
    </section>

    <div id="footer-root"></div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/offcanvas-menu.php'; ?>
<script src="/assets/js/main.js"></script>
<script>
window.__WORK_DATA__ = <?= json_encode($projects) ?>;
</script>
<script type="module" src="/assets/embed.js"></script>
</body>
</html>
