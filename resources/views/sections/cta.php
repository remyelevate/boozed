<?php

/**
 * CTA section
 * Fields: cta_title, cta_button_text, cta_button_url
 */

$title       = function_exists('get_sub_field') ? get_sub_field('cta_title') : 'Get in touch';
$button_text = function_exists('get_sub_field') ? get_sub_field('cta_button_text') : '';
$button_url  = function_exists('get_sub_field') ? get_sub_field('cta_button_url') : '';

$title       = $title ?: 'Get in touch';
$show_button = $button_url && $button_text;
?>
<section class="section section-cta">
    <div class="section-inner section-cta-inner">
        <h2 class="section-cta-title"><?php echo esc_html($title); ?></h2>
        <?php if ($show_button) : ?>
            <a href="<?php echo esc_url($button_url); ?>" class="button section-cta-button">
                <?php echo esc_html($button_text); ?>
            </a>
        <?php endif; ?>
    </div>
</section>
