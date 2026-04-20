<?php
/**
 * Thank You section
 * Fields: thank_you_title, thank_you_subtitle, thank_you_button_label, thank_you_button_link
 */

$title = function_exists('get_sub_field') ? (string) get_sub_field('thank_you_title') : '';
$subtitle = function_exists('get_sub_field') ? (string) get_sub_field('thank_you_subtitle') : '';
$button_label = function_exists('get_sub_field') ? (string) get_sub_field('thank_you_button_label') : '';
$button_link = function_exists('get_sub_field') ? (string) get_sub_field('thank_you_button_link') : '';

$show_button = $button_label !== '' && $button_link !== '';
?>

<section class="thank-you py-16 md:py-24"<?php echo $title !== '' ? ' aria-label="' . esc_attr($title) . '"' : ''; ?>>
    <div class="max-w-section mx-auto px-4 md:px-section-x">
        <div class="max-w-[680px] mx-auto text-center">
            <div class="thank-you__icon mx-auto mb-6 flex items-center justify-center" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" height="150px" viewBox="0 -960 960 960" width="150px" fill="#312783">
                    <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"></path>
                </svg>
            </div>

            <?php if ($title !== '') : ?>
                <h2 class="font-heading font-bold text-h2 md:text-h2-lg text-brand-indigo mb-3"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <?php if ($subtitle !== '') : ?>
                <p class="font-body text-body-md text-brand-indigo mb-6"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>

            <?php if ($show_button) : ?>
                <?php
                \App\Components::render('button', [
                    'variant' => 'coral',
                    'label'   => $button_label,
                    'href'    => $button_link,
                ]);
                ?>
            <?php endif; ?>
        </div>
    </div>
</section>
