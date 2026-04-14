<?php
if (!isset($siteName)) {
    require_once __DIR__ . '/vars.php';
}
$current_path = $_SERVER['REQUEST_URI'] ?? '/';
$current_path = strtok($current_path, '?');
$is_home = ($current_path === '/' || $current_path === '/index.php' || $current_path === '');
$is_contact = (strpos($current_path, 'contact') !== false);
?>
<div id="offcanvas-backdrop" class="offcanvas-backdrop" aria-hidden="true"></div>
<aside id="offcanvas-menu" class="offcanvas-panel" aria-label="Main navigation" aria-hidden="true">
    <button type="button" class="offcanvas-close" aria-label="Close menu">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <nav class="offcanvas-nav">
        <a href="/" class="<?= $is_home ? 'active' : '' ?>">Home</a>
        <div class="offcanvas-sub">
            <span class="offcanvas-sub-title">Services</span>
            <a href="/web-design-development.php">Web design &amp; development</a>
            <a href="/branding.php">Branding</a>
            <a href="/seo-marketing.php">SEO</a>
            <a href="/advertising.php">PPC</a>
            <a href="/services.php">All services</a>
        </div>
        <a href="/work.php">Our work</a>
        <a href="/about.php">About</a>
        <a href="/contact.php" class="offcanvas-cta <?= $is_contact ? 'active' : '' ?>">Let's talk</a>
    </nav>
    <div class="offcanvas-social">
        <a href="#" aria-label="Instagram">Instagram</a>
        <a href="#" aria-label="TikTok">TikTok</a>
    </div>
</aside>
