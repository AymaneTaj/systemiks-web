<?php
/**
 * Work comparison section: before/after or "what we did" items.
 */
if (!isset($workComparisonTitle)) {
    $workComparisonTitle = 'What we did';
}
$items = [
    ['title' => 'E-commerce redesign', 'before' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&q=75', 'after' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&q=75', 'caption' => 'New storefront and checkout flow — +220% revenue'],
    ['title' => 'Brand identity refresh', 'before' => 'https://images.unsplash.com/photo-1572044162444-ad60f128bdea?w=400&q=75', 'after' => 'https://images.unsplash.com/photo-1561070791-2526d31fe5b6?w=400&q=75', 'caption' => 'Logo, palette, and guidelines delivered'],
    ['title' => 'SEO & organic traffic', 'before' => 'https://images.unsplash.com/photo-1504868584819-f8e8b4b6d7e3?w=400&q=75', 'after' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?w=400&q=75', 'caption' => '+140% organic sessions in 6 months'],
    ['title' => 'Landing page conversion', 'before' => 'https://images.unsplash.com/photo-1605902711622-cfb43c4437b5?w=400&q=75', 'after' => 'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=400&q=75', 'caption' => 'Conversion rate from 1.2% to 4.8%'],
    ['title' => 'Paid campaign creative', 'before' => 'https://images.unsplash.com/photo-1611162616305-c69b3fa7fbe0?w=400&q=75', 'after' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=400&q=75', 'caption' => '4.2x ROAS — Google + Meta campaigns'],
    ['title' => 'Website speed & UX', 'before' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=400&q=75', 'after' => 'https://images.unsplash.com/photo-1547658719-da2b51169166?w=400&q=75', 'caption' => 'PageSpeed from 34 → 97, mobile-first'],
];
?>
<section class="work-comparison section section--white" aria-labelledby="work-comparison-heading">
    <h2 id="work-comparison-heading" class="work-comparison-title"><?= htmlspecialchars($workComparisonTitle) ?></h2>
    <p class="work-comparison-sub">Before and after we got involved—websites, brands, SEO, and campaigns that deliver real results.</p>
    <div class="work-comparison-grid">
        <?php foreach ($items as $i => $item): ?>
        <article class="work-comparison-item">
            <h3 class="work-comparison-item-title"><?= htmlspecialchars($item['title']) ?></h3>
            <div class="work-comparison-images">
                <figure class="work-comparison-fig">
                    <img src="<?= htmlspecialchars($item['before']) ?>" alt="" width="400" height="260" loading="lazy" class="work-comparison-img">
                    <figcaption>Before</figcaption>
                </figure>
                <figure class="work-comparison-fig">
                    <img src="<?= htmlspecialchars($item['after']) ?>" alt="" width="400" height="260" loading="lazy" class="work-comparison-img">
                    <figcaption>After</figcaption>
                </figure>
            </div>
            <p class="work-comparison-caption"><?= htmlspecialchars($item['caption']) ?></p>
        </article>
        <?php endforeach; ?>
    </div>
</section>
