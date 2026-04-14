<?php
require_once __DIR__ . '/includes/vars.php';
$headerTransparent = false;
$pageTitle = '404 — Page Not Found | Systemiks';
$pageDesc  = 'The page you were looking for doesn\'t exist.';
http_response_code(404);
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

    <section style="min-height:70vh; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:6rem 1.5rem;">
      <p style="font-family:var(--font-head); font-size:clamp(6rem,20vw,12rem); font-weight:900; line-height:1; margin:0; color:#f0f0f0; letter-spacing:-0.04em;">404</p>
      <h1 style="font-family:var(--font-head); font-size:clamp(1.5rem,3vw,2rem); margin:0 0 1rem;">Page not found</h1>
      <p style="color:#666; max-width:420px; margin:0 0 2rem;">The page you were looking for doesn't exist or has been moved.</p>
      <div style="display:flex; gap:1rem; flex-wrap:wrap; justify-content:center;">
        <a href="/" style="background:#111; color:#fff; padding:0.75rem 2rem; border-radius:99px; font-weight:600; text-decoration:none;">Back to home</a>
        <a href="/contact.php" style="border:2px solid #111; color:#111; padding:0.75rem 2rem; border-radius:99px; font-weight:600; text-decoration:none;">Contact us</a>
      </div>
    </section>

    <div id="footer-root"></div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/offcanvas-menu.php'; ?>
<script src="/assets/js/main.js"></script>
<script type="module" src="/assets/embed.js"></script>
</body>
</html>
