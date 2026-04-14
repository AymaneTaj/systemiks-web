<?php
require_once __DIR__ . '/includes/vars.php';
$projects = [
    [
        'title' => 'CB Legal',
        'category' => 'Web + Branding',
        'result' => '+140% organic traffic',
        'tags' => ['Web', 'SEO', 'Branding'],
        'color' => 'linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)',
        'image' => null,
        'href' => '/work.php',
        'client' => ['name' => 'CB Legal'],
    ],
    [
        'title' => 'BabAtlas Car',
        'category' => 'Web + SEO',
        'result' => '3x conversion rate',
        'tags' => ['Web', 'SEO'],
        'color' => 'linear-gradient(135deg, #0d0d0d 0%, #1a1a1a 50%, #2d2d2d 100%)',
        'image' => null,
        'href' => '/work.php',
        'client' => ['name' => 'BabAtlas Car'],
    ],
    [
        'title' => 'Zoon Pet',
        'category' => 'E-commerce',
        'result' => '+85% online revenue',
        'tags' => ['E-commerce', 'Branding'],
        'color' => 'linear-gradient(135deg, #1b4332 0%, #2d6a4f 50%, #40916c 100%)',
        'image' => null,
        'href' => '/work.php',
        'client' => ['name' => 'Zoon Pet'],
    ],
    [
        'title' => 'Cool Cook',
        'category' => 'E-commerce + Branding',
        'result' => '+220% online revenue',
        'tags' => ['E-commerce', 'Branding'],
        'color' => 'linear-gradient(135deg, #7f1d1d 0%, #991b1b 50%, #b91c1c 100%)',
        'image' => null,
        'href' => '/work.php',
        'client' => ['name' => 'Cool Cook'],
    ],
    [
        'title' => 'TaxiVan-Medic',
        'category' => 'Web + Ads',
        'result' => '+60% qualified leads',
        'tags' => ['Web', 'Ads'],
        'color' => 'linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 50%, #3b82f6 100%)',
        'image' => null,
        'href' => '/work.php',
        'client' => ['name' => 'TaxiVan-Medic'],
    ],
    [
        'title' => 'Ms-Production',
        'category' => 'Branding + Web',
        'result' => 'Full identity launch',
        'tags' => ['Branding', 'Web'],
        'color' => 'linear-gradient(135deg, #2e1065 0%, #4c1d95 50%, #7c3aed 100%)',
        'image' => null,
        'href' => '/work.php',
        'client' => ['name' => 'Ms-Production'],
    ],
    [
        'title' => 'Local SEO Campaign',
        'category' => 'SEO',
        'result' => 'Top 3 rankings, 12 keywords',
        'tags' => ['SEO'],
        'color' => 'linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%)',
        'image' => null,
        'href' => '/work.php',
        'client' => ['name' => 'Local SEO Campaign'],
    ],
    [
        'title' => 'Performance Ads',
        'category' => 'Paid Ads',
        'result' => '4.2x ROAS',
        'tags' => ['Ads'],
        'color' => 'linear-gradient(135deg, #431407 0%, #7c2d12 50%, #c2410c 100%)',
        'image' => null,
        'href' => '/work.php',
        'client' => ['name' => 'Performance Ads'],
    ],
    [
        'title' => 'Full Digital Rebrand',
        'category' => 'Branding + SEO + Ads',
        'result' => '+300% brand search volume',
        'tags' => ['Branding', 'SEO', 'Ads'],
        'color' => 'linear-gradient(135deg, #18181b 0%, #27272a 50%, #3f3f46 100%)',
        'image' => null,
        'href' => '/work.php',
        'client' => ['name' => 'Full Digital Rebrand'],
    ],
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
