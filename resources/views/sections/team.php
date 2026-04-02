<?php

$members = function_exists('get_sub_field') ? (array) get_sub_field('team_members') : [];
$background = function_exists('get_sub_field') ? (string) get_sub_field('team_background') : 'gray';
$sectionBackgroundClass = $background === 'white' ? 'bg-white' : 'bg-[#f1f1f1]';

$items = [];
foreach ($members as $member) {
    $name = isset($member['name']) ? trim((string) $member['name']) : '';
    if ($name === '') {
        continue;
    }

    $items[] = [
        'photo' => isset($member['photo']) ? (int) $member['photo'] : 0,
        'name'  => $name,
        'role'  => isset($member['role']) ? trim((string) $member['role']) : '',
    ];
}

if (empty($items)) {
    return;
}
?>

<section class="section-team <?php echo esc_attr($sectionBackgroundClass); ?> py-10 md:py-section-y">
    <div class="max-w-section mx-auto px-4 md:px-section-x">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 md:gap-8">
            <?php foreach ($items as $item) : ?>
                <article class="text-center">
                    <div class="aspect-square w-full bg-[#ece8e8] overflow-hidden">
                        <?php if (!empty($item['photo'])) : ?>
                            <?php echo wp_get_attachment_image($item['photo'], 'large', false, ['class' => 'w-full h-full object-cover']); ?>
                        <?php endif; ?>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold leading-tight"><?php echo esc_html($item['name']); ?></h3>
                    <?php if ($item['role'] !== '') : ?>
                        <p class="mt-1 text-sm opacity-80"><?php echo esc_html($item['role']); ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
