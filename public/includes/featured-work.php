<?php
/**
 * Featured work strip — animated cards linking to Work page.
 */
$featured = [
    [
        'title' => 'CB Legal',
        'tag' => 'Web + Branding',
        'color' => 'linear-gradient(135deg, #1a1a2e, #0f3460)',
        'url' => '/work.php',
        'result' => '+140% organic traffic',
    ],
    [
        'title' => 'BabAtlas Car',
        'tag' => 'Web + SEO',
        'color' => 'linear-gradient(135deg, #0d0d0d, #2d2d2d)',
        'url' => '/work.php',
        'result' => '3x conversion rate',
    ],
    [
        'title' => 'Cool Cook',
        'tag' => 'E-commerce + Branding',
        'color' => 'linear-gradient(135deg, #7f1d1d, #b91c1c)',
        'url' => '/work.php',
        'result' => '+220% online revenue',
    ],
];
?>
<section class="featured-work section section--white reveal-on-scroll" aria-labelledby="featured-work-heading">
    <h2 id="featured-work-heading" class="featured-work-title">Our work</h2>
    <p class="featured-work-sub">A selection of projects across web, branding, SEO, and advertising—see how we help brands grow.</p>
    <div class="featured-work-grid">
        <?php foreach ($featured as $i => $item): ?>
        <a href="<?= htmlspecialchars($item['url']) ?>" class="featured-work-card">
            <span class="featured-work-tag"><?= htmlspecialchars($item['tag']) ?></span>
            <span class="featured-work-result"><?= htmlspecialchars($item['result']) ?></span>
            <div class="featured-work-img-wrap">
                <div
                    class="featured-work-thumb"
                    style="background: <?= htmlspecialchars($item['color'], ENT_QUOTES, 'UTF-8') ?>;"
                    aria-hidden="true"
                >
                    <span style="color: rgba(255,255,255,0.15); font-size: 2.5rem; font-weight: 900; letter-spacing: -0.05em;"><?= htmlspecialchars(strtoupper(substr($item['title'], 0, 2)), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
            <h3 class="featured-work-card-title"><?= htmlspecialchars($item['title']) ?></h3>
        </a>
        <?php endforeach; ?>
    </div>
    <p class="featured-work-cta">
        <a href="/work.php" class="featured-work-link">View all work →</a>
    </p>
</section>
