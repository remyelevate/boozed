<?php

/**
 * Text section
 * Fields: tekst_content, tekst_button_text, tekst_button_url, tekst_button_alignment
 */

$content          = function_exists('get_sub_field') ? (string) get_sub_field('tekst_content') : '';
$button_text      = function_exists('get_sub_field') ? (string) get_sub_field('tekst_button_text') : '';
$button_url       = function_exists('get_sub_field') ? (string) get_sub_field('tekst_button_url') : '';
$button_alignment = function_exists('get_sub_field') ? get_sub_field('tekst_button_alignment') : 'left';

$align_class = [
    'left'   => 'align-left',
    'center' => 'align-center',
    'right'  => 'align-right',
];
$wrap_class = $align_class[ $button_alignment ] ?? $align_class['left'];
$show_btn   = $button_url !== '';
?>
<section class="section section-tekst max-w-section mx-auto px-4 md:px-section-x py-10 md:py-section-y">
    <div class="section-inner">
        <?php if ($content) : ?>
            <div class="section-tekst-content prose">
                <?php echo wp_kses_post($content); ?>
            </div>
        <?php endif; ?>
        <?php if ($show_btn) : ?>
            <div class="section-tekst-actions <?php echo esc_attr($wrap_class); ?>">
                <a href="<?php echo esc_url($button_url); ?>" class="button">
                    <?php echo esc_html($button_text ?: __('Read more', 'boozed')); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
