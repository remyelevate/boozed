<?php

$members = function_exists('get_sub_field') ? (array) get_sub_field('team_members') : [];

$items = [];
foreach ($members as $member) {
    $name = isset($member['name']) ? trim((string) $member['name']) : '';
    if ($name === '') {
        continue;
    }

    $items[] = [
        'photo' => isset($member['photo']) ? (int) $member['photo'] : 0,
    ];
}

if (empty($items)) {
    return;
}
?>

<section class="section-team bg-[#f1f1f1] py-10 md:py-section-y">
    <div class="max-w-section mx-auto px-4 md:px-section-x">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 md:gap-8">
            <?php foreach ($items as $item) : ?>
                <article class="text-center">
                    <div class="aspect-square w-full bg-[#ece8e8] overflow-hidden">
                        <?php if (!empty($item['photo'])) : ?>
                            <?php echo wp_get_attachment_image($item['photo'], 'large', false, ['class' => 'w-full h-full object-cover']); ?>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
