<?php
/**
 * Site footer – content from Global Settings > Footer.
 * Uses DESIGN-SYSTEM.md: max-w-section, px-section-x, py-section-y, font-heading/font-body, brand-* colors.
 */

$theme_uri     = get_template_directory_uri();
$footer_menu   = function_exists('get_field') ? get_field('footer_menu', 'option') : null;
$tagline       = function_exists('get_field') ? (string) get_field('footer_tagline', 'option') : '';
$company       = function_exists('get_field') ? (string) get_field('footer_company', 'option') : '';
$address       = function_exists('get_field') ? (string) get_field('footer_address', 'option') : '';
$footer_phone  = function_exists('get_field') ? (string) get_field('footer_phone', 'option') : '';
$footer_email  = function_exists('get_field') ? (string) get_field('footer_email', 'option') : '';
$copyright     = function_exists('get_field') ? (string) get_field('footer_copyright', 'option') : '';
$privacy_url   = function_exists('get_field') ? (string) get_field('footer_privacy_url', 'option') : '';
$terms_url    = function_exists('get_field') ? (string) get_field('footer_terms_url', 'option') : '';
$facebook_url  = function_exists('get_field') ? (string) get_field('footer_facebook_url', 'option') : '';
$instagram_url = function_exists('get_field') ? (string) get_field('footer_instagram_url', 'option') : '';
$footer_image_1 = function_exists('get_field') ? get_field('footer_image_1', 'option') : null;
$footer_image_1_url = function_exists('get_field') ? get_field('footer_image_1_url', 'option') : '';
$footer_image_2 = function_exists('get_field') ? get_field('footer_image_2', 'option') : null;
$footer_image_2_url = function_exists('get_field') ? get_field('footer_image_2_url', 'option') : '';

// CTA Banner (default on when option not yet saved)
$footer_cta_enabled_raw  = function_exists('get_field') ? get_field('footer_cta_enabled', 'option') : null;
$footer_cta_enabled     = $footer_cta_enabled_raw === false || $footer_cta_enabled_raw === 0 ? false : true;
$footer_cta_title          = function_exists('get_field') ? (string) get_field('footer_cta_title', 'option') : '';
$footer_cta_button_text    = function_exists('get_field') ? (string) get_field('footer_cta_button_text', 'option') : '';
$footer_cta_button_url     = function_exists('get_field') ? (string) get_field('footer_cta_button_url', 'option') : '';
$footer_cta_secondary_text = function_exists('get_field') ? (string) get_field('footer_cta_secondary_text', 'option') : '';
$footer_cta_secondary_url  = function_exists('get_field') ? (string) get_field('footer_cta_secondary_url', 'option') : '';
if (empty($footer_cta_title)) {
    $footer_cta_title = __('Klaar voor jullie volgende experience?', 'boozed');
}
if (empty($footer_cta_button_text)) {
    $footer_cta_button_text = __('Neem contact op', 'boozed');
}
if (empty($footer_cta_secondary_text)) {
    $footer_cta_secondary_text = __('Meer over onze werkwijze', 'boozed');
}
$show_cta = $footer_cta_enabled && $footer_cta_title !== '';
$show_secondary_cta = $footer_cta_secondary_text !== '' && $footer_cta_secondary_url !== '';

// Newsletter (default on when option not yet saved)
$footer_newsletter_enabled_raw  = function_exists('get_field') ? get_field('footer_newsletter_enabled', 'option') : null;
$footer_newsletter_enabled     = $footer_newsletter_enabled_raw === false || $footer_newsletter_enabled_raw === 0 ? false : true;
$footer_newsletter_title        = function_exists('get_field') ? (string) get_field('footer_newsletter_title', 'option') : '';
$footer_newsletter_description  = function_exists('get_field') ? (string) get_field('footer_newsletter_description', 'option') : '';
$footer_newsletter_shortcode_raw = function_exists('get_field') ? (string) get_field('footer_newsletter_shortcode', 'option') : '';
$footer_newsletter_shortcode     = $footer_newsletter_shortcode_raw !== '' ? $footer_newsletter_shortcode_raw : (string) get_option(\App\ContactForm7Newsletter::OPTION_SHORTCODE, '');
if (empty($footer_newsletter_title)) {
    $footer_newsletter_title = __('Blijf op de hoogte', 'boozed');
}

$footer_ticker_items = function_exists('get_field') ? get_field('footer_ticker_items', 'option') : [];
$footer_ticker_items = is_array($footer_ticker_items) ? $footer_ticker_items : [];
$ticker_colors = ['text-brand-indigo', 'text-brand-white', 'text-brand-coral'];

if (empty($footer_phone) && function_exists('get_field')) {
    $footer_phone = (string) get_field('business_phone', 'option');
}
if (empty($company)) {
    $company = get_bloginfo('name');
}
if (empty($tagline)) {
    $tagline = __('Experience creators', 'boozed');
}
if (empty($copyright)) {
    $copyright = '© ' . gmdate('Y') . ' ' . get_bloginfo('name') . ' | ' . __('Experience Creators', 'boozed');
}

$nav_args = [
    'container'   => false,
    'menu_class'  => 'site-footer__menu-list list-none p-0 m-0 flex flex-col gap-3 font-body text-body-md text-brand-white',
    'fallback_cb' => false,
];
if (!empty($footer_menu)) {
    $nav_args['menu'] = (int) $footer_menu;
}

$has_contact = $company || $address || $footer_phone || $footer_email;
$has_menu    = !empty($footer_menu);
$has_bottom  = $copyright || $privacy_url || $terms_url || $facebook_url || $instagram_url;
$arrow_lottie_url = $theme_uri . '/assets/animations/arrow.json';
?>

<footer class="site-footer bg-brand-indigo text-brand-white" role="contentinfo">
    <?php // CTA Banner – above main footer content ?>
    <?php if ($show_cta) : ?>
    <div class="site-footer__cta-banner relative overflow-visible border-b border-brand-white/10" data-footer-cta-lottie="<?php echo esc_url($arrow_lottie_url); ?>">
        <div class="relative max-w-section mx-auto px-4 md:px-section-x py-12 md:py-16">
            <div class="site-footer__cta-lottie absolute left-4 md:left-[68px] top-0 -translate-y-1/2 w-[min(50vmin,280px)] h-[min(50vmin,280px)] md:w-[min(45vmin,360px)] md:h-[min(45vmin,360px)] pointer-events-none z-0" aria-hidden="true"></div>
            <div class="relative z-10 flex flex-col items-center text-center">
                <h3 class="font-heading font-bold text-h4 md:text-h2 text-brand-white m-0 mb-6 md:mb-8 max-w-2xl"><?php echo esc_html($footer_cta_title); ?></h3>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 md:gap-6">
                    <?php if ($footer_cta_button_text !== '') : ?>
                    <a href="<?php echo esc_url($footer_cta_button_url ?: '#'); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 font-body text-body font-medium bg-brand-coral text-brand-white hover:opacity-90 no-underline transition-opacity shrink-0" <?php echo $footer_cta_button_url ? '' : ' aria-disabled="true"'; ?>>
                        <?php echo esc_html($footer_cta_button_text); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($show_secondary_cta) : ?>
                    <a href="<?php echo esc_url($footer_cta_secondary_url); ?>" class="site-footer__cta-secondary font-body text-body-md font-medium text-brand-white no-underline relative inline-block hover:opacity-90 transition-opacity">
                        <?php echo esc_html($footer_cta_secondary_text); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php // Newsletter – above main footer, below CTA ?>
    <?php if ($footer_newsletter_enabled) : ?>
    <div class="site-footer__newsletter bg-white border-b border-brand-border">
        <div class="max-w-section mx-auto px-4 md:px-section-x py-12 md:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 md:gap-8 items-center">
                <div class="lg:col-span-5">
                    <h3 class="font-heading font-bold text-h4 md:text-h3 text-brand-indigo m-0 mb-3"><?php echo esc_html($footer_newsletter_title); ?></h3>
                    <?php if ($footer_newsletter_description !== '') : ?>
                    <p class="font-body text-body-md text-brand-indigo/80 m-0"><?php echo esc_html($footer_newsletter_description); ?></p>
                    <?php endif; ?>
                </div>
                <div class="lg:col-span-7 flex items-center w-full">
                    <?php if ($footer_newsletter_shortcode !== '') : ?>
                    <div class="site-footer__newsletter-shortcode w-full">
                        <?php echo do_shortcode($footer_newsletter_shortcode); ?>
                    </div>
                    <?php else : ?>
                    <form class="site-footer__newsletter-form site-footer__newsletter-form--bar w-full flex flex-row flex-nowrap gap-0 border border-[#333333] max-w-xl" action="#" method="post" aria-label="<?php echo esc_attr($footer_newsletter_title); ?>">
                        <span class="site-footer__newsletter-field flex-1 min-w-0 flex relative">
                            <input type="email" name="footer_newsletter_email" placeholder="<?php esc_attr_e('Jouw email', 'boozed'); ?>" class="w-full min-h-[48px] py-3 pl-12 pr-4 font-body text-body bg-white border-0 text-[#333333] placeholder:text-[#7a6f9e] focus:outline-none focus:ring-0 rounded-none" required>
                        </span>
                        <button type="submit" class="site-footer__newsletter-submit inline-flex items-center justify-center min-h-[48px] px-5 py-3 font-body text-body font-medium bg-brand-indigo text-white hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-brand-indigo focus:ring-offset-2 shrink-0 rounded-none" aria-label="<?php esc_attr_e('Aanmelden', 'boozed'); ?>">→</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php // Ticker bar – between newsletter and main footer (nude background, items Indigo/White/Coral) ?>
    <?php if (count($footer_ticker_items) > 0) : ?>
    <div class="site-footer__ticker bg-brand-nude overflow-hidden py-3 border-0" aria-hidden="true">
        <div class="site-footer__ticker-track site-footer__ticker-track--animate flex items-center gap-8 whitespace-nowrap">
            <?php for ($copy = 0; $copy < 6; $copy++) : ?>
                <?php foreach ($footer_ticker_items as $i => $row) :
                    $text = isset($row['text']) ? trim((string) $row['text']) : '';
                    if ($text === '') continue;
                    $color_class = $ticker_colors[ $i % 3 ];
                ?>
                    <span class="site-footer__ticker-item font-body font-medium text-body-md md:text-body-lg <?php echo esc_attr($color_class); ?>"><?php echo esc_html($text); ?></span>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="site-footer__main max-w-section mx-auto px-4 md:px-section-x py-12 md:py-section-y">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
            <?php // Column 1: Logo ?>
            <div class="site-footer__brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center no-underline text-inherit" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <img src="<?php echo esc_url($theme_uri . '/assets/images/logo-light.svg'); ?>" alt="" class="h-12 w-auto md:h-14" width="180" height="64">
                </a>
            </div>

            <?php // Column 2: Contact ?>
            <?php if ($has_contact) : ?>
                <div class="site-footer__contact">
                    <h3 class="font-heading font-bold text-h6 md:text-h6-lg text-brand-white mb-4"><?php esc_html_e('Contact', 'boozed'); ?></h3>
                    <div class="font-body text-body-md text-brand-white space-y-2">
                        <?php if ($company) : ?>
                            <p class="m-0"><?php echo esc_html($company); ?></p>
                        <?php endif; ?>
                        <?php if ($address) : ?>
                            <p class="m-0 whitespace-pre-line"><?php echo esc_html($address); ?></p>
                        <?php endif; ?>
                        <?php if ($footer_phone) : ?>
                            <p class="m-0">
                                <a href="<?php echo esc_url('tel:' . preg_replace('/\s+/', '', $footer_phone)); ?>" class="text-brand-white hover:text-brand-coral transition-colors no-underline"><?php echo esc_html($footer_phone); ?></a>
                            </p>
                        <?php endif; ?>
                        <?php if ($footer_email) : ?>
                            <p class="m-0">
                                <a href="<?php echo esc_url('mailto:' . antispambot($footer_email)); ?>" class="text-brand-white hover:text-brand-coral transition-colors no-underline"><?php echo esc_html(antispambot($footer_email)); ?></a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php // Column 3: Menu ?>
            <?php if ($has_menu) : ?>
                <div class="site-footer__nav">
                    <nav class="site-footer__nav-inner" aria-label="<?php esc_attr_e('Footer', 'boozed'); ?>">
                        <?php wp_nav_menu($nav_args); ?>
                    </nav>
                </div>
            <?php endif; ?>

            <?php // Column 4: B Corp / Eventex badges ?>
            <?php if ($footer_image_1 || $footer_image_2) : ?>
            <div class="site-footer__certified flex flex-col min-h-0">
                <div class="flex flex-1 items-stretch justify-end gap-4">
                    <?php if ($footer_image_1) : ?>
                        <?php if ($footer_image_1_url) : ?>
                            <a href="<?php echo esc_url($footer_image_1_url); ?>" class="flex flex-1 min-w-0 items-center justify-center" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($footer_image_1['alt'] ?? __('Image 1', 'boozed')); ?>">
                                <img src="<?php echo esc_url($footer_image_1['url']); ?>" alt="<?php echo esc_attr($footer_image_1['alt'] ?? ''); ?>" class="h-full max-h-full w-auto max-w-full object-contain" width="<?php echo (int) ($footer_image_1['width'] ?? 120); ?>" height="<?php echo (int) ($footer_image_1['height'] ?? 60); ?>">
                            </a>
                        <?php else : ?>
                            <span class="flex flex-1 min-w-0 items-center justify-center">
                                <img src="<?php echo esc_url($footer_image_1['url']); ?>" alt="<?php echo esc_attr($footer_image_1['alt'] ?? ''); ?>" class="h-full max-h-full w-auto max-w-full object-contain" width="<?php echo (int) ($footer_image_1['width'] ?? 120); ?>" height="<?php echo (int) ($footer_image_1['height'] ?? 60); ?>">
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($footer_image_2) : ?>
                        <?php if ($footer_image_2_url) : ?>
                            <a href="<?php echo esc_url($footer_image_2_url); ?>" class="flex flex-1 min-w-0 items-center justify-center" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($footer_image_2['alt'] ?? __('Image 2', 'boozed')); ?>">
                                <img src="<?php echo esc_url($footer_image_2['url']); ?>" alt="<?php echo esc_attr($footer_image_2['alt'] ?? ''); ?>" class="h-full max-h-full w-auto max-w-full object-contain" width="<?php echo (int) ($footer_image_2['width'] ?? 120); ?>" height="<?php echo (int) ($footer_image_2['height'] ?? 60); ?>">
                            </a>
                        <?php else : ?>
                            <span class="flex flex-1 min-w-0 items-center justify-center">
                                <img src="<?php echo esc_url($footer_image_2['url']); ?>" alt="<?php echo esc_attr($footer_image_2['alt'] ?? ''); ?>" class="h-full max-h-full w-auto max-w-full object-contain" width="<?php echo (int) ($footer_image_2['width'] ?? 120); ?>" height="<?php echo (int) ($footer_image_2['height'] ?? 60); ?>">
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($has_bottom) : ?>
        <div class="site-footer__bottom bg-brand-border text-brand-black">
            <div class="max-w-section mx-auto px-4 md:px-section-x py-4 md:py-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4">
                    <?php if ($copyright) : ?>
                        <span class="font-body text-body-sm text-brand-black"><?php echo esc_html($copyright); ?></span>
                    <?php endif; ?>
                    <?php if ($facebook_url || $instagram_url) : ?>
                        <span class="flex items-center gap-3">
                            <?php if ($facebook_url) : ?>
                                <a href="<?php echo esc_url($facebook_url); ?>" class="text-brand-black hover:text-brand-purple transition-colors focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2 rounded" aria-label="<?php esc_attr_e('Facebook', 'boozed'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if ($instagram_url) : ?>
                                <a href="<?php echo esc_url($instagram_url); ?>" class="text-brand-black hover:text-brand-purple transition-colors focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2 rounded" aria-label="<?php esc_attr_e('Instagram', 'boozed'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </a>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <?php if ($privacy_url) : ?>
                        <a href="<?php echo esc_url($privacy_url); ?>" class="font-body text-body-sm text-brand-black hover:text-brand-purple no-underline transition-colors"><?php esc_html_e('Privacy verklaring', 'boozed'); ?></a>
                    <?php endif; ?>
                    <?php if ($terms_url) : ?>
                        <a href="<?php echo esc_url($terms_url); ?>" class="font-body text-body-sm text-brand-black hover:text-brand-purple no-underline transition-colors"><?php esc_html_e('Algemene voorwaarden', 'boozed'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</footer>
