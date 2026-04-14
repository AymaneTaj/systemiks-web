<?php
/**
 * Systemiks - Ofseek-style homepage
 */
require_once __DIR__ . '/includes/vars.php';
$pageTitle = 'Web, Brand, SEO &amp; Advertising | Systemiks | Laval &amp; Montreal';
$pageDesc  = 'Selective digital partner in Laval and Montreal. We partner with brands ready to own their market—web, brand, SEO, and advertising. Certified Google, Meta, TikTok.';
$tagline = 'Selective. Strategic. We partner with brands ready to own their market—web, brand, SEO, and advertising.';
$rotatingWords = 'Web|SEO|Growth|Brands';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
    <script type="application/ld+json">
    <?= json_encode([
        '@context'  => 'https://schema.org',
        '@type'     => 'ProfessionalService',
        'name'      => 'Systemiks',
        'url'       => 'https://systemiks.ca',
        'logo'      => 'https://systemiks.ca/assets/images/logo.png',
        'description' => 'Digital agency in Laval and Montreal offering web design, branding, SEO, and paid advertising.',
        'address'   => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '271 Boul des Prairies',
            'addressLocality' => 'Laval',
            'addressRegion'   => 'QC',
            'postalCode'      => 'H7N 2T8',
            'addressCountry'  => 'CA',
        ],
        'telephone'     => '+15147461644',
        'email'         => 'info@systemiks.ca',
        'areaServed'    => ['Laval', 'Montreal', 'Quebec'],
        'serviceType'   => ['Web Design', 'SEO', 'Branding', 'Paid Advertising'],
        'priceRange'    => '$$',
        'sameAs'        => [
            'https://www.instagram.com/systemiks',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
    </script>
</head>
<body>
<div id="site-wrap" class="site-wrap">
  <div class="site-wrap-inner">
<?php $headerTransparent = true; require_once __DIR__ . '/includes/header.php'; ?>

    <!-- React gradient hero (mounts here via embed.js) -->
    <div id="hero-root" class="hero-react-root" style="min-height: 100vh; width: 100%; position: relative;"></div>

    <!-- Credibility strip -->
    <section class="credibility-strip reveal-on-scroll">
      <div class="credibility-inner">
        <span class="credibility-item">Trustpilot <span class="stars">★★★★☆</span> 4.5</span>
        <span class="credibility-item">Google <span class="stars">★★★★☆</span> 4.5</span>
        <span class="credibility-item"><span class="stars red">★★★★★</span> 5.0</span>
        <span class="credibility-item"><img src="/assets/images/partners/google-partner.svg" alt="Google Partner" class="credibility-logo"> <span>Google Partner</span></span>
        <span class="credibility-item"><img src="/assets/images/partners/meta-partner.svg" alt="Meta Partner" class="credibility-logo"> <span>Meta Partner</span></span>
        <span class="credibility-item"><img src="/assets/images/partners/tiktok.svg" alt="TikTok Certified" class="credibility-logo"> <span>TikTok Certified</span></span>
      </div>
    </section>

    <!-- Value prop + stats bridge -->
    <section class="section section--value-prop section--white reveal-on-scroll" id="about">
      <div class="divider"></div>
      <h2 class="split-heading"><span class="line"><span class="inner"><?= htmlspecialchars(strtoupper($tagline)) ?></span></span></h2>
      <p class="rotating-words" data-words="<?= htmlspecialchars($rotatingWords) ?>">
        <span class="dynamic-word"><span>Web</span></span>
      </p>
      <div class="stats-grid" style="margin-top: 3rem;">
        <div class="stat-block">
          <span class="stat-block__value">50+</span>
          <span class="stat-block__label">Projects delivered</span>
        </div>
        <div class="stat-block">
          <span class="stat-block__value">8+</span>
          <span class="stat-block__label">Certified partners</span>
        </div>
        <div class="stat-block">
          <span class="stat-block__value">3x</span>
          <span class="stat-block__label">Avg conversion lift</span>
        </div>
        <div class="stat-block">
          <span class="stat-block__value">4.5</span>
          <span class="stat-block__label">Google rating</span>
        </div>
      </div>
    </section>

    <!-- Services (keep entire existing section, no changes) -->
    <section class="section section--gray" id="services">
        <div class="services-section-bg services-bg-tab-website" id="services-section-bg" aria-hidden="true">
            <div class="services-bg-shape services-bg-shape--1"></div>
            <div class="services-bg-shape services-bg-shape--2"></div>
            <div class="services-bg-shape services-bg-shape--3"></div>
        </div>
        <div class="services-grid">
            <div class="services-intro">
                <p class="outline-intro">Strategy, design, and data—one partner for your whole digital journey.</p>
                <p class="services-intro-sub">Choose a focus below. Websites that convert, brands that stick, SEO that ranks, ads that scale.</p>
                <div class="services-list" role="tablist" id="services-tablist">
                    <li><a href="#services" role="tab" aria-selected="true" data-service="website" class="active">Website design</a></li>
                    <li><a href="#services" role="tab" aria-selected="false" data-service="branding">Branding</a></li>
                    <li><a href="#services" role="tab" aria-selected="false" data-service="seo">SEO &amp; Marketing</a></li>
                    <li><a href="#services" role="tab" aria-selected="false" data-service="advertising">Advertising</a></li>
                </div>
            </div>
            <div class="services-content">
                <div class="services-copy-wrap">
                    <p class="copy copy--active" data-service="website">High-performance websites tailored to your goals—showcase sites, e-commerce, content-managed platforms. Speed, mobile, conversion. Certified by Google, Meta, and TikTok. Your site as a growth engine.</p>
                    <p class="copy" data-service="branding">Memorable brands that own their space. Brand strategy, visual identity, guidelines, and roll-out—we shape how your audience sees you. Launch or refresh: a consistent, bold presence that builds trust and recognition.</p>
                    <p class="copy" data-service="seo">We grow your visibility and organic traffic with data-driven SEO and content marketing. Keyword and topic strategy, technical SEO, content that ranks—found by the right people, search into leads. Sustainable, long-term results and clear reporting.</p>
                    <p class="copy" data-service="advertising">Performance campaigns that convert. Google Ads, Meta, TikTok, programmatic—targeting, creatives, budgets. Measurable ROI and scalable acquisition. Our certified team turns ad spend into qualified leads and sales.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--white reveal-on-scroll">
        <h3 class="split-heading"><span class="line"><span class="inner">TRUSTED PLATFORMS &amp; PARTNERSHIPS</span></span></h3>
        <p class="section-sub section-sub--center">We work with the tools and platforms that drive real results for our clients.</p>
        <div class="divider"></div>
        <div class="carousel-wrap">
            <div class="carousel-track" aria-hidden="true">
                <div class="cell"><img src="/assets/images/partners/google-partner.svg" alt="Google Partner" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/meta-partner.svg" alt="Meta Business Partner" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/tiktok.svg" alt="TikTok Certified" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/wordpress.svg" alt="WordPress" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/shopify.svg" alt="Shopify Partner" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/google-cloud.svg" alt="Google Cloud" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/aws.svg" alt="AWS Partner" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/google-partner.svg" alt="Google Partner" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/meta-partner.svg" alt="Meta Business Partner" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/tiktok.svg" alt="TikTok Certified" class="carousel-logo"></div>
                <div class="cell"><img src="/assets/images/partners/shopify.svg" alt="Shopify Partner" class="carousel-logo"></div>
            </div>
        </div>
    </section>

    <section class="section section--white reveal-on-scroll">
        <div class="scrolling-text-wrap on-white">
            <div class="scrolling-text-inner">
                <span>LOCAL &amp; GOOGLE ADVERTISING · </span>
                <span>LOCAL &amp; GOOGLE ADVERTISING · </span>
            </div>
        </div>
    </section>

    <section class="section section--white reveal-on-scroll">
        <h3 class="split-heading"><span class="line"><span class="inner">WHO WE SERVE</span></span></h3>
        <p class="section-sub section-sub--center">From startups to established businesses across Quebec and beyond—e-commerce, professional services, and brands ready to grow.</p>
        <div class="divider"></div>
        <div class="carousel-wrap">
            <div class="carousel-track">
                <div class="cell"><span class="carousel-label">E-commerce &amp; retail</span></div>
                <div class="cell"><span class="carousel-label">Professional services</span></div>
                <div class="cell"><span class="carousel-label">Laval · Montreal · Quebec</span></div>
                <div class="cell"><span class="carousel-label">Startups &amp; scale-ups</span></div>
                <div class="cell"><span class="carousel-label">E-commerce &amp; retail</span></div>
                <div class="cell"><span class="carousel-label">Professional services</span></div>
                <div class="cell"><span class="carousel-label">Laval · Montreal · Quebec</span></div>
                <div class="cell"><span class="carousel-label">Startups &amp; scale-ups</span></div>
            </div>
        </div>
    </section>

    <!-- Featured work — now dark section -->
    <section class="section--dark-feature reveal-on-scroll" id="work">
      <div class="section__inner">
        <div class="section-intro section-intro--center">
          <span class="section-intro__eyebrow">Our work</span>
          <h2 class="section-intro__heading split-heading"><span class="line"><span class="inner">BUILT TO PERFORM</span></span></h2>
          <p class="section-intro__sub">A sample of websites, brands, and campaigns we've delivered for growing businesses in Quebec and beyond.</p>
        </div>
        <?php require_once __DIR__ . '/includes/featured-work.php'; ?>
        <div style="text-align:center; margin-top: 3rem;">
          <a href="/work.php" class="btn btn--ghost-white">View all work →</a>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials-section reveal-on-scroll">
      <div class="section__inner">
        <div class="section-intro section-intro--center">
          <span class="section-intro__eyebrow">What clients say</span>
          <h2 class="section-intro__heading split-heading"><span class="line"><span class="inner">TRUSTED BY GROWING BRANDS</span></span></h2>
        </div>
        <div class="testimonials-grid">
          <div class="testimonial-card reveal-on-scroll">
            <div class="testimonial-card__stars">★★★★★</div>
            <p class="testimonial-card__quote">Systemiks completely transformed our online presence. Our website traffic tripled in six months and we're getting qualified leads every week. Best investment we've made.</p>
            <div class="testimonial-card__author">
              <div class="testimonial-card__avatar">ML</div>
              <div>
                <span class="testimonial-card__name">Marc Létourneau</span>
                <span class="testimonial-card__role">Founder, LT Consulting</span>
              </div>
            </div>
          </div>
          <div class="testimonial-card reveal-on-scroll reveal-delay-1">
            <div class="testimonial-card__stars">★★★★★</div>
            <p class="testimonial-card__quote">The branding work was exceptional — they really understood our market and built something that stands out. Our brand recognition went through the roof after the rebrand.</p>
            <div class="testimonial-card__author">
              <div class="testimonial-card__avatar">ST</div>
              <div>
                <span class="testimonial-card__name">Sarah Tremblay</span>
                <span class="testimonial-card__role">CEO, Tremblay Immobilier</span>
              </div>
            </div>
          </div>
          <div class="testimonial-card reveal-on-scroll reveal-delay-2">
            <div class="testimonial-card__stars">★★★★★</div>
            <p class="testimonial-card__quote">Their Google Ads campaigns are on another level. We went from a 1.8x ROAS to consistently 4.5x in three months. The reporting is crystal clear — no guesswork.</p>
            <div class="testimonial-card__author">
              <div class="testimonial-card__avatar">KA</div>
              <div>
                <span class="testimonial-card__name">Kevin Audet</span>
                <span class="testimonial-card__role">Marketing Director, Audet Sports</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA bar -->
    <section class="cta-bar">
      <div class="cta-bar-text">
        <h2>Ready to own your market?</h2>
        <p>Selective partnerships only — let's see if we're a fit. <a href="/work.php" class="cta-bar-link">View our work</a></p>
      </div>
      <a href="<?= htmlspecialchars($ctaUrl) ?>" class="btn btn--gold">Let's talk</a>
    </section>

    <div id="connect-root"></div>

    <div id="footer-root"></div>

  </div><!-- .site-wrap-inner -->
</div><!-- #site-wrap -->
<?php require_once __DIR__ . '/includes/offcanvas-menu.php'; ?>
    <script src="/assets/js/main.js"></script>
    <script type="module" src="/assets/embed.js"></script>
</body>
</html>
