<?php
/**
 * Featured work strip — animated cards linking to Work page.
 */
$featured = [
    [
        'title' => 'CB Legal',
        'tag' => 'Web + Branding',
        'img' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=600&q=80',
        'url' => '/work.php',
        'result' => '+140% organic traffic',
    ],
    [
        'title' => 'BabAtlas Car',
        'tag' => 'Web + SEO',
        'img' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=600&q=80',
        'url' => '/work.php',
        'result' => '3x conversion rate',
    ],
    [
        'title' => 'Cool Cook',
        'tag' => 'E-commerce + Branding',
        'img' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=600&q=80',
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
                <img src="<?= htmlspecialchars($item['img']) ?>" alt="" width="600" height="380" loading="lazy" class="featured-work-img">
            </div>
            <h3 class="featured-work-card-title"><?= htmlspecialchars($item['title']) ?></h3>
        </a>
        <?php endforeach; ?>
    </div>
    <p class="featured-work-cta">
        <a href="/work.php" class="featured-work-link">View all work →</a>
    </p>
</section>
