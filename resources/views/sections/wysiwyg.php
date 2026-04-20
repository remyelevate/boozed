<?php
/**
 * WYSIWYG section.
 * Field: wysiwyg_content
 */

$content = function_exists('get_sub_field') ? (string) get_sub_field('wysiwyg_content') : '';

if (!$content) {
    return;
}
?>
<section class="section section-wysiwyg max-w-section mx-auto px-4 md:px-section-x py-10 md:py-section-y">
    <div class="section-inner prose prose-lg max-w-none">
        <?php echo wp_kses_post($content); ?>
    </div>
</section>
