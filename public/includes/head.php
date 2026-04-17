<?php
/**
 * Shared <head> partial.
 * Expects these variables to be set before include (with fallbacks):
 *   $pageTitle       — string, e.g. "About us | Systemiks"
 *   $pageDesc        — string, meta description (~160 chars)
 *   $pageImage       — string, absolute URL to OG image (optional, defaults to logo)
 *   $pageUrl         — string, canonical URL (optional, defaults to current URL)
 *   $noindex         — bool, true to add noindex (optional, default false)
 */
$siteUrl     = 'https://systemiks.ca';
$defaultImage = $siteUrl . '/assets/images/og-default.jpg';
$pageTitle   = $pageTitle   ?? 'Digital Agency in Laval &amp; Montreal | Systemiks';
$pageDesc    = $pageDesc    ?? 'Selective digital partner in Laval and Montreal — web design, branding, SEO, and advertising for brands ready to grow.';
$pageImage   = $pageImage   ?? $defaultImage;
$pageUrl     = $pageUrl     ?? $siteUrl . strtok($_SERVER['REQUEST_URI'], '?');
$noindex     = $noindex     ?? false;
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(html_entity_decode($pageTitle)) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
    <meta name="author" content="Systemiks">
    <?php if ($noindex): ?><meta name="robots" content="noindex,nofollow"><?php endif; ?>
    <link rel="canonical" href="<?= htmlspecialchars($pageUrl) ?>">

    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="Systemiks">
    <meta property="og:title"       content="<?= htmlspecialchars(html_entity_decode($pageTitle)) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDesc) ?>">
    <meta property="og:image"       content="<?= htmlspecialchars($pageImage) ?>">
    <meta property="og:url"         content="<?= htmlspecialchars($pageUrl) ?>">
    <meta property="og:locale"      content="en_CA">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= htmlspecialchars(html_entity_decode($pageTitle)) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDesc) ?>">
    <meta name="twitter:image"       content="<?= htmlspecialchars($pageImage) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/embed.css">
    <!-- Pre-set hero CSS variables so background is dark before React mounts (prevents white flash on mobile) -->
    <style>
      body {
        --gradient-background-start: rgb(10, 10, 10);
        --gradient-background-end: rgb(108, 0, 82);
        --first-color: 245, 209, 0;
        --second-color: 232, 122, 158;
        --third-color: 240, 160, 80;
        --fourth-color: 0, 38, 255;
        --fifth-color: 245, 209, 0;
        --pointer-color: 255, 255, 255;
        --size: 80%;
        --blending-value: hard-light;
      }
    </style>
