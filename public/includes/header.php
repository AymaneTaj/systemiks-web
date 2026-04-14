<?php
if (!isset($siteName)) {
    require_once __DIR__ . '/vars.php';
}
$headerClass = !empty($headerTransparent) ? 'transparent' : 'scrolled';
?>
<header id="header-outer" class="<?= $headerClass ?>">
    <div class="header-inner">
        <a href="/" class="site-logo"><?= htmlspecialchars($siteName) ?></a>
        <div class="header-actions">
            <div class="header-social" aria-hidden="true">
                <a href="#" aria-label="Instagram">IG</a>
                <a href="#" aria-label="TikTok">TT</a>
            </div>
            <button type="button" class="menu-btn js-menu-open" aria-label="Open menu" aria-expanded="false" aria-controls="offcanvas-menu">
                <span class="menu-btn-inner">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </span>
            </button>
        </div>
    </div>
</header>
