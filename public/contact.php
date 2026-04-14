<?php
require_once __DIR__ . '/includes/vars.php';
$pageTitle = 'Book a Discovery Call | Systemiks';
$pageDesc  = 'Get in touch with Systemiks. Book a free discovery call or send a message — we reply within 24 hours. Based in Laval, serving Montreal and Quebec.';
$thanks = isset($_GET['thanks']);
$errRaw = isset($_GET['error']) ? (string) $_GET['error'] : '';
$error = $errRaw !== '' ? array_values(array_filter(explode(',', $errRaw), static fn ($e) => $e !== '')) : [];
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

    <section class="contact-page-hero">
      <span class="badge badge--electric">Get in touch</span>
      <h1>Book a discovery call</h1>
      <p>We reply within 24 hours. No commitment, no hard sell — just an honest conversation about your goals.</p>
    </section>

    <section class="section--light" style="padding: 3rem 1.5rem 1.5rem; background: var(--light-1);">
      <div class="section__inner">
        <div class="process-timeline process-timeline--light" style="max-width: 900px;">
          <div class="process-timeline__step">
            <div class="process-timeline__num">01</div>
            <h4 class="process-timeline__title">Book or message</h4>
            <p class="process-timeline__desc">Pick a time or send a message below</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">02</div>
            <h4 class="process-timeline__title">We review</h4>
            <p class="process-timeline__desc">We check your inquiry and fit within 24 hours</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">03</div>
            <h4 class="process-timeline__title">Discovery call</h4>
            <p class="process-timeline__desc">30–45 min — your goals, our approach, honest assessment</p>
          </div>
          <div class="process-timeline__step">
            <div class="process-timeline__num">04</div>
            <h4 class="process-timeline__title">Proposal</h4>
            <p class="process-timeline__desc">Custom scope and next steps — no generic packages</p>
          </div>
        </div>
      </div>
    </section>

    <div class="contact-layout">
      <div class="contact-layout__left">
        <span class="badge badge--electric" style="margin-bottom: 1.5rem;">Let's connect</span>
        <h2 class="contact-left__heading">Let's talk about your growth.</h2>
        <p class="contact-left__sub">We work with a select number of clients at a time. If you have clear goals and are ready to invest in growth, we want to hear from you.</p>

        <div class="contact-info-block">
          <div class="contact-info-item">
            <span class="contact-info-item__label">Phone</span>
            <a href="tel:+15147461644" class="contact-info-item__value">(514) 746-1644</a>
          </div>
          <div class="contact-info-item">
            <span class="contact-info-item__label">Email</span>
            <a href="mailto:<?= htmlspecialchars($email) ?>" class="contact-info-item__value"><?= htmlspecialchars($email) ?></a>
          </div>
          <div class="contact-info-item">
            <span class="contact-info-item__label">Location</span>
            <span class="contact-info-item__value"><?= htmlspecialchars($address) ?></span>
          </div>
        </div>

        <div class="contact-trust-list">
          <div class="contact-trust-item">
            <span class="contact-trust-item__icon">✓</span>
            <span>Reply within 24 hours — guaranteed</span>
          </div>
          <div class="contact-trust-item">
            <span class="contact-trust-item__icon">✓</span>
            <span>No obligation, no hard sell</span>
          </div>
          <div class="contact-trust-item">
            <span class="contact-trust-item__icon">✓</span>
            <span>Certified Google + Meta + TikTok team</span>
          </div>
          <div class="contact-trust-item">
            <span class="contact-trust-item__icon">✓</span>
            <span>Based in Laval, serving all of Quebec</span>
          </div>
        </div>
      </div>

      <div class="contact-layout__right">
        <?php if ($thanks): ?>
        <div class="contact-form-card" style="text-align:center; padding: 3rem 2rem;">
          <div style="font-size: 2.5rem; margin-bottom: 1rem;">✓</div>
          <h3>Message received!</h3>
          <p style="color: var(--text-muted); margin-top: 0.5rem;">We'll review your inquiry and get back to you within 24 hours.</p>
          <a href="/work.php" class="btn btn--primary" style="margin-top: 1.5rem;">View our work →</a>
        </div>
        <?php else: ?>
        <div class="contact-form-card">
          <h3 class="contact-form-card__title">Send a message</h3>
          <p class="contact-form-card__sub">Or <a href="#calendar">book a time directly</a> below.</p>
          <?php if (!empty($error)): ?>
          <div class="form-error-message">
            Please fill in all required fields and try again.
          </div>
          <?php endif; ?>
          <form class="contact-form" method="post" action="/contact-submit.php">
            <div class="form-row form-row--2col">
              <div class="form-field">
                <label for="cn" class="form-label">Name <span style="color:var(--blue)">*</span></label>
                <input type="text" id="cn" name="name" class="form-input<?= in_array('name', $error, true) ? ' form-input--error' : '' ?>" placeholder="Your name" required>
              </div>
              <div class="form-field">
                <label for="ce" class="form-label">Email <span style="color:var(--blue)">*</span></label>
                <input type="email" id="ce" name="email" class="form-input<?= in_array('email', $error, true) ? ' form-input--error' : '' ?>" placeholder="you@company.com" required>
              </div>
            </div>
            <div class="form-row form-row--2col">
              <div class="form-field">
                <label for="cp" class="form-label">Phone <span class="form-label__opt">(optional)</span></label>
                <input type="tel" id="cp" name="phone" class="form-input" placeholder="(514) 000-0000">
              </div>
              <div class="form-field">
                <label for="cc" class="form-label">Company <span class="form-label__opt">(optional)</span></label>
                <input type="text" id="cc" name="company" class="form-input" placeholder="Company name">
              </div>
            </div>
            <div class="form-field">
              <label for="cs" class="form-label">Service you're interested in</label>
              <select id="cs" name="service" class="form-input form-input--select">
                <option value="">Select a service...</option>
                <option value="web">Web Design &amp; Development</option>
                <option value="branding">Branding</option>
                <option value="seo">SEO &amp; Marketing</option>
                <option value="advertising">Advertising</option>
                <option value="all">Multiple / full-service</option>
              </select>
            </div>
            <div class="form-field">
              <label for="cm" class="form-label">Message <span style="color:var(--blue)">*</span></label>
              <textarea id="cm" name="message" rows="5" class="form-input form-input--textarea<?= in_array('message', $error, true) ? ' form-input--error' : '' ?>" placeholder="Tell us about your project, goals, or timeline..." required></textarea>
            </div>
            <input type="text" name="website" value="" class="honeypot-field" tabindex="-1" autocomplete="off">
            <button type="submit" class="btn btn--primary btn--full">Send message →</button>
          </form>
        </div>
        <?php endif; ?>

        <div class="contact-calendar-card" id="calendar">
          <h3 class="contact-calendar-card__title">Or book a call directly</h3>
          <p style="color: var(--text-muted); font-size: var(--text-sm); margin: 0 0 1rem;">Pick a time that works for you — 30 min discovery call.</p>
          <div id="calendar-root" data-booking-url="<?= htmlspecialchars($ctaUrl) ?>">
            <a href="<?= htmlspecialchars($ctaUrl) ?>" class="btn btn--ghost-dark" style="width:100%; justify-content:center;">Book a time →</a>
          </div>
        </div>
      </div>
    </div>

    <div id="footer-root"></div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/offcanvas-menu.php'; ?>
<script src="/assets/js/main.js"></script>
<script type="module" src="/assets/embed.js"></script>
</body>
</html>
