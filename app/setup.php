<?php

/**
 * Theme setup and ACF field registration.
 */

/**
 * Resolve ACF flexible content row layout to section view filename (no .php).
 * ACF may return layout key (e.g. layout_boozed_highlight_image) or name (e.g. highlight_image).
 *
 * @param string $layout Raw value from get_row_layout().
 * @return string Section name for use in resources/views/sections/{name}.php.
 */
function boozed_section_layout_name($layout)
{
    if (!is_string($layout) || $layout === '') {
        return '';
    }
    // Already a short name (e.g. highlight_image) – use as-is if it has no layout_ prefix.
    if (strpos($layout, 'layout_') !== 0) {
        return $layout;
    }
    // Strip layout_boozed_ or layout_ so we get the section name.
    if (strpos($layout, 'layout_boozed_') === 0) {
        return substr($layout, strlen('layout_boozed_'));
    }
    if (strpos($layout, 'layout_') === 0) {
        return substr($layout, strlen('layout_'));
    }
    return $layout;
}

/**
 * Check if a section layout is enabled (visible in ACF dropdown and on frontend).
 *
 * @param string $layout Layout key from get_row_layout() (e.g. layout_boozed_hero).
 * @return bool True if section should be shown, false if hidden.
 */
function boozed_section_enabled($layout)
{
    static $config = null;
    if ($config === null) {
        $file = get_template_directory() . '/config/section-visibility.php';
        $config = is_file($file) ? (require $file) : [];
    }
    return !isset($config[$layout]) || $config[$layout];
}

/**
 * Filter ACF flexible content layouts by visibility config.
 * Removes disabled sections from the admin dropdown.
 *
 * @param array $layouts Associative array of layout_key => layout_config.
 * @return array Filtered layouts.
 */
function boozed_filter_sections_by_visibility($layouts)
{
    return array_filter($layouts, function ($key) {
        return boozed_section_enabled($key);
    }, ARRAY_FILTER_USE_KEY);
}

\App\PostTypes\Project::register();
\App\PostTypes\Thema::register();
\App\PostTypes\Vacature::register();
\App\PostTypes\Testimonial::register();

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form', 'script', 'style']);
    add_theme_support('responsive-embeds');
    add_theme_support('woocommerce');
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'boozed'),
    ]);
}, 20);

/**
 * Enqueue design system (Tailwind) styles.
 */
add_action('wp_enqueue_scripts', function () {
    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();
    $build_css = $theme_dir . '/assets/build/app.css';
    if (file_exists($build_css)) {
        wp_enqueue_style(
            'boozed-design-system',
            $theme_uri . '/assets/build/app.css',
            [],
            filemtime($build_css)
        );
    }
    $nav_dropdown_css = $theme_dir . '/assets/css/nav-dropdown.css';
    if (file_exists($nav_dropdown_css)) {
        wp_enqueue_style(
            'boozed-nav-dropdown',
            $theme_uri . '/assets/css/nav-dropdown.css',
            ['boozed-design-system'],
            filemtime($nav_dropdown_css)
        );
    }
    $gsap_js = $theme_dir . '/node_modules/gsap/dist/gsap.min.js';
    if (file_exists($gsap_js)) {
        wp_enqueue_script(
            'gsap',
            $theme_uri . '/node_modules/gsap/dist/gsap.min.js',
            [],
            filemtime($gsap_js),
            true
        );
    }
    $scroll_trigger_js = $theme_dir . '/node_modules/gsap/dist/ScrollTrigger.min.js';
    if (file_exists($scroll_trigger_js)) {
        wp_enqueue_script(
            'gsap-scroll-trigger',
            $theme_uri . '/node_modules/gsap/dist/ScrollTrigger.min.js',
            ['gsap'],
            filemtime($scroll_trigger_js),
            true
        );
    }
    $locomotive_css = $theme_dir . '/node_modules/locomotive-scroll/bundled/locomotive-scroll.css';
    if (file_exists($locomotive_css)) {
        wp_enqueue_style(
            'locomotive-scroll',
            $theme_uri . '/node_modules/locomotive-scroll/bundled/locomotive-scroll.css',
            [],
            filemtime($locomotive_css)
        );
    }
    $locomotive_js = $theme_dir . '/node_modules/locomotive-scroll/bundled/locomotive-scroll.min.js';
    if (file_exists($locomotive_js)) {
        wp_enqueue_script(
            'locomotive-scroll',
            $theme_uri . '/node_modules/locomotive-scroll/bundled/locomotive-scroll.min.js',
            [],
            filemtime($locomotive_js),
            true
        );
    }
    $locomotive_init_js = $theme_dir . '/assets/js/locomotive.js';
    if (file_exists($locomotive_init_js)) {
        wp_enqueue_script(
            'boozed-locomotive',
            $theme_uri . '/assets/js/locomotive.js',
            ['locomotive-scroll'],
            filemtime($locomotive_init_js),
            true
        );
    }
    $header_js = $theme_dir . '/assets/js/header.js';
    if (file_exists($header_js)) {
        wp_enqueue_script(
            'boozed-header',
            $theme_uri . '/assets/js/header.js',
            ['gsap'],
            filemtime($header_js),
            true
        );
    }

    // Lottie: enqueue on front-end for mobile menu (boozed.json), BCorp section (confetti), Thank You section (confetti), and footer CTA (arrow).
    $enqueue_lottie = ! is_admin() && ! wp_doing_ajax();
    if ( $enqueue_lottie ) {
        $lottie_js = $theme_dir . '/node_modules/lottie-web/build/player/lottie.min.js';
        if (file_exists($lottie_js)) {
            wp_enqueue_script(
                'lottie',
                $theme_uri . '/node_modules/lottie-web/build/player/lottie.min.js',
                [],
                filemtime($lottie_js),
                true
            );
        }
    }

    $footer_js = $theme_dir . '/assets/js/footer.js';
    if (file_exists($footer_js)) {
        wp_enqueue_script(
            'boozed-footer',
            $theme_uri . '/assets/js/footer.js',
            ['lottie'],
            filemtime($footer_js),
            true
        );
    }
    $page_transition_js = $theme_dir . '/assets/js/page-transition.js';
    if (file_exists($page_transition_js)) {
        wp_enqueue_script(
            'boozed-page-transition',
            $theme_uri . '/assets/js/page-transition.js',
            [],
            filemtime($page_transition_js),
            true
        );
    }

    if (is_singular('vacature')) {
        $vac_sol_js = $theme_dir . '/assets/js/vacature-apply-modal.js';
        if (file_exists($vac_sol_js)) {
            wp_enqueue_script(
                'boozed-vacature-apply-modal',
                $theme_uri . '/assets/js/vacature-apply-modal.js',
                [],
                filemtime($vac_sol_js),
                true
            );
        }
    }
}, 10);

add_action('acf/init', function () {
    if (!function_exists('acf_add_options_page')) {
        return;
    }
    // Global Settings (parent) with sub tabs – register first so field groups can target them.
    acf_add_options_page([
        'page_title' => __('Global Settings', 'boozed'),
        'menu_title' => __('Global Settings', 'boozed'),
        'menu_slug'  => 'boozed-global-settings',
        'capability' => 'edit_posts',
        'redirect'   => true,
    ]);
    acf_add_options_sub_page([
        'page_title'   => __('Header', 'boozed'),
        'menu_title'   => __('Header', 'boozed'),
        'menu_slug'    => 'header',
        'parent_slug'  => 'boozed-global-settings',
    ]);
    acf_add_options_sub_page([
        'page_title'   => __('Business', 'boozed'),
        'menu_title'   => __('Business', 'boozed'),
        'menu_slug'    => 'business',
        'parent_slug'  => 'boozed-global-settings',
    ]);
    acf_add_options_sub_page([
        'page_title'   => __('Footer', 'boozed'),
        'menu_title'   => __('Footer', 'boozed'),
        'menu_slug'    => 'footer',
        'parent_slug'  => 'boozed-global-settings',
    ]);
    acf_add_options_sub_page([
        'page_title'   => __('Vacatures', 'boozed'),
        'menu_title'   => __('Vacatures', 'boozed'),
        'menu_slug'    => 'vacatures',
        'parent_slug'  => 'boozed-global-settings',
    ]);
    acf_add_options_sub_page([
        'page_title'   => __('PDP (Productpagina)', 'boozed'),
        'menu_title'   => __('PDP', 'boozed'),
        'menu_slug'    => 'pdp',
        'parent_slug'  => 'boozed-global-settings',
    ]);
});

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    \App\Fields\PageSections::init();
    \App\Fields\GlobalSettings::init();
    \App\Fields\ProjectFields::init();
    \App\Fields\BlogFields::init();
    \App\Fields\ThemaFields::init();
    \App\Fields\VacatureFields::init();
    \App\Fields\TestimonialFields::init();
});

// Offerte aanvraag: AJAX form submission.
\App\OfferteAanvraagHandler::init();

// Vacature sollicitatie modal: Contact Form 7 template (shortcode in option).
\App\ContactForm7VacatureSollicitatie::init();


// Contact Form 7: create Boozed Newsletter form and default shortcode for footer.
\App\ContactForm7Newsletter::init();

/**
 * Allow CSV uploads for WooCommerce product import (and other admin flows).
 * Fixes "Je hebt geen toestemming om dit bestandstype te uploaden" when CSV is
 * not in the site's allowed types (e.g. on Multisite or strict host config).
 * Priority 999 so we run after Multisite/other filters that may strip CSV.
 */
add_filter('upload_mimes', function ($mimes) {
    $mimes['csv'] = 'text/csv';
    return $mimes;
}, 999);

/**
 * Ensure CSV is accepted when the upload was explicitly allowed (e.g. WooCommerce
 * import). Core rejects the file if the detected type is not in get_allowed_mime_types();
 * this re-allows CSV when the caller passed csv/txt mimes (extension-based).
 */
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes, $real_mime = null) {
    if ($data['type'] && $data['ext']) {
        return $data;
    }
    if (!is_array($mimes) || empty($mimes)) {
        return $data;
    }
    $ext = strtolower(substr(strrchr($filename, '.'), 1));
    if ($ext === 'csv' && isset($mimes['csv'])) {
        return [
            'ext'             => 'csv',
            'type'            => $mimes['csv'],
            'proper_filename' => false,
        ];
    }
    if ($ext === 'txt' && isset($mimes['txt'])) {
        return [
            'ext'             => 'txt',
            'type'            => $mimes['txt'],
            'proper_filename' => false,
        ];
    }
    return $data;
}, 10, 4);

/**
 * Ensure "Flexible page" template appears in the Page template dropdown (handles theme cache).
 */
add_filter('theme_templates', function ($templates, $theme, $post, $post_type) {
    if ($post_type !== 'page') {
        return $templates;
    }
    $file = 'page-flexible.php';
    if (!isset($templates[$file])) {
        $templates[$file] = 'Flexible page';
    }
    return $templates;
}, 10, 4);

/**
 * Vacancy apply banner: render via wp_footer so it sits outside scroll containers (fixes Locomotive Scroll / transform stacking).
 */
add_action('wp_footer', function () {
    if (!is_singular('vacature')) {
        return;
    }
    $banner_part = get_template_directory() . '/resources/views/partials/vacature-apply-banner.php';
    if (is_file($banner_part)) {
        include $banner_part;
    }
}, 5);

add_action('wp_footer', function () {
    if (!is_singular('vacature')) {
        return;
    }
    $modal_part = get_template_directory() . '/resources/views/partials/vacature-apply-modal.php';
    if (is_file($modal_part)) {
        include $modal_part;
    }
}, 6);

/**
 * Temporary debug: add ?boozed_debug=1 to any page.
 * - Injects a bright red body background to confirm theme loads
 * - Logs stylesheet status to console
 * Remove once styling issue is resolved.
 */
add_action('wp_head', function () {
    if (empty($_GET['boozed_debug'])) {
        return;
    }
    echo '<style id="boozed-debug">body{background:#e63946!important;}</style>';
}, 1);

add_action('wp_footer', function () {
    if (empty($_GET['boozed_debug'])) {
        return;
    }
    ?>
    <script>
    (function(){
        // Check which of our key assets are loaded
        Array.from(document.styleSheets).forEach(function(sheet){
            try {
                var href = sheet.href || '';
                if (href && (href.indexOf('app.css') >= 0 || href.indexOf('locomotive') >= 0 || href.indexOf('nav-dropdown') >= 0)) {
                    var loaded = sheet.cssRules && sheet.cssRules.length > 0;
                    console.log('[Boozed debug] ' + (loaded ? 'OK' : 'EMPTY/FAILED') + ': ' + href);
                }
            } catch (e) { console.error('[Boozed debug] CORS/blocked: ' + (sheet.href || 'inline')); }
        });
    })();
    </script>
    <?php
}, 99);
