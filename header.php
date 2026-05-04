<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function () {
            try {
                if (window.sessionStorage && sessionStorage.getItem('boozed-page-transition') === '1') {
                    document.documentElement.classList.add('has-page-transition-pending');
                    var s = document.createElement('style');
                    s.textContent = 'html.has-page-transition-pending,html.has-page-transition-pending body{background:#0C0A21!important}.has-page-transition-pending #page > *:not(#page-transition-overlay){opacity:0!important;transition:none!important}.has-page-transition-pending #page-transition-overlay{position:fixed;inset:0;z-index:10000;opacity:1;visibility:visible;pointer-events:auto;background:#0C0A21;transition:none!important}';
                    document.head.appendChild(s);
                }
            } catch (e) {}
        })();
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class('boozed-page'); ?>>
<?php wp_body_open(); ?>

<?php
$theme_uri    = get_template_directory_uri();
$header_menu  = get_field('header_menu', 'option');
$header_topbar_menu = get_field('header_topbar_menu', 'option');
$cta_text     = get_field('header_cta_text', 'option') ?: __('Offerte aanvragen', 'boozed');
$business_phone = get_field('business_phone', 'option');
$is_logged_in = is_user_logged_in();
$request_uri  = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '/';
$current_url  = home_url($request_uri);
if (wp_validate_redirect($current_url, home_url('/')) === false) {
    $current_url = home_url('/');
}
$login_page_url = function_exists('boozed_login_page_url') ? boozed_login_page_url() : home_url('/login/');
$current_path = (string) wp_parse_url($current_url, PHP_URL_PATH);
$login_path   = (string) wp_parse_url($login_page_url, PHP_URL_PATH);
$is_login_path = untrailingslashit($current_path) === untrailingslashit($login_path);
$login_redirect_target = remove_query_arg(['redirect_to', 'action', '_wpnonce'], $current_url);
if (wp_validate_redirect($login_redirect_target, home_url('/')) === false) {
    $login_redirect_target = home_url('/');
}
$login_url = $login_page_url;
if (!$is_login_path) {
    $login_url = function_exists('boozed_login_url') ? boozed_login_url($login_redirect_target) : $login_page_url;
}
$wishlist_url = home_url('/wishlist/');
$logout_url   = wp_logout_url(home_url('/'));
$nav_args     = [
    'container'      => false,
    'menu_class'     => 'nav-menu flex flex-nowrap items-center justify-center gap-4 lg:gap-8 font-body text-body-sm lg:text-body-md font-medium whitespace-nowrap',
    'fallback_cb'    => false,
    'walker'         => new \App\NavWalker(),
];
if ( ! empty( $header_menu ) ) {
    $nav_args['menu'] = (int) $header_menu;
} else {
    $nav_args['theme_location'] = 'primary_navigation';
}
$topbar_nav_args = [
    'container'   => false,
    'menu_class'  => 'site-header__top-menu',
    'fallback_cb' => false,
    'walker'      => new \App\TopbarNavWalker(),
];
if ( ! empty( $header_topbar_menu ) ) {
    $topbar_nav_args['menu'] = (int) $header_topbar_menu;
}
$account_icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor" class="site-header__account-icon" aria-hidden="true" focusable="false"><path d="M230.93,220a8,8,0,0,1-6.93,4H32a8,8,0,0,1-6.92-12c15.23-26.33,38.7-45.21,66.09-54.16a72,72,0,1,1,73.66,0c27.39,8.95,50.86,27.83,66.09,54.16A8,8,0,0,1,230.93,220Z"></path></svg>';
$account_chevron_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor" class="site-header__account-chevron" aria-hidden="true" focusable="false"><path d="M211.31,100.69a8,8,0,0,1,0,11.31l-80,80a8,8,0,0,1-11.31,0l-80-80a8,8,0,0,1,11.31-11.31L128,177.37l76.69-76.68A8,8,0,0,1,211.31,100.69Z"></path></svg>';
?>

<div id="page" class="site <?php echo ! empty( $header_topbar_menu ) ? 'site--has-topbar' : ''; ?>">
    <!-- Page transition overlay: paint-fill forward on leave, reverse on enter -->
    <div id="page-transition-overlay" class="page-transition-overlay fixed inset-0 z-[10000] pointer-events-none opacity-0 invisible flex items-center justify-center" aria-hidden="true">
        <video id="page-transition-forward" class="page-transition-video absolute inset-0 w-full h-full object-cover" muted playsinline preload="auto" src="<?php echo esc_url( $theme_uri . '/assets/images/paint-fill.webm' ); ?>"></video>
        <video id="page-transition-reverse" class="page-transition-video absolute inset-0 w-full h-full object-cover" muted playsinline preload="auto" src="<?php echo esc_url( $theme_uri . '/assets/images/paint-fill-reverse.webm' ); ?>"></video>
    </div>
    <!-- Mega Menu Panel (full-width, outside header for proper positioning) -->
    <?php
    if ( \App\NavWalker::has_mega_menu_items() ) {
        echo \App\NavWalker::get_mega_menu_panel();
    }
    ?>
    
    <header class="site-header <?php echo ! empty( $header_topbar_menu ) ? 'site-header--has-topbar' : ''; ?> fixed top-0 left-0 right-0 z-50 w-full overflow-visible transition-[background-color,color] duration-300 text-brand-white" id="site-header" role="banner">
        <div class="site-header__backdrop absolute inset-0 bg-brand-white opacity-0 transition-opacity duration-300 pointer-events-none" aria-hidden="true"></div>
        <?php if ( ! empty( $header_topbar_menu ) ) : ?>
            <div class="site-header__top relative">
                <div class="site-header__top-inner w-full max-w-section mx-auto px-4 md:px-section-x">
                    <nav class="site-header__top-nav" aria-label="<?php esc_attr_e( 'Topbar', 'boozed' ); ?>">
                        <?php wp_nav_menu( $topbar_nav_args ); ?>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
        <div class="site-header-inner relative flex items-center justify-between gap-4 w-full max-w-section mx-auto px-4 md:px-section-x pt-[max(1.5rem,calc(env(safe-area-inset-top,0px)+0.5rem))] pb-4 md:py-4 min-w-0">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo flex items-center gap-3 shrink-0" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                <img src="<?php echo esc_url( $theme_uri . '/assets/images/logo-light.svg' ); ?>" alt="" class="site-header__logo-img site-header__logo-img--light h-10 w-auto md:h-12" width="140" height="40">
                <img src="<?php echo esc_url( $theme_uri . '/assets/images/logo-dark.svg' ); ?>" alt="" class="site-header__logo-img site-header__logo-img--dark absolute left-0 top-1/2 -translate-y-1/2 h-10 w-auto md:h-12 opacity-0 pointer-events-none" width="140" height="40">
            </a>

            <nav class="site-header__nav hidden md:flex items-center gap-4 lg:gap-8 relative min-w-0 flex-1 justify-center overflow-visible" aria-label="<?php esc_attr_e( 'Primary', 'boozed' ); ?>">
                <?php
                if ( has_nav_menu( 'primary_navigation' ) || ! empty( $header_menu ) ) {
                    wp_nav_menu( $nav_args );
                }
                ?>
            </nav>

            <div class="site-header__actions hidden md:flex items-center gap-3 lg:gap-6 shrink-0">
                <span class="site-header__lang font-body text-body-xs lg:text-body-sm">NL/EN</span>
                <div class="site-header__account js-account-menu">
                    <?php if ( ! $is_logged_in ) : ?>
                        <a href="<?php echo esc_url($login_url); ?>" class="site-header__account-login" aria-label="<?php esc_attr_e('Login', 'boozed'); ?>">
                            <?php echo $account_icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <span><?php esc_html_e('Login', 'boozed'); ?></span>
                        </a>
                    <?php else : ?>
                        <button
                            type="button"
                            class="site-header__account-toggle"
                            aria-label="<?php esc_attr_e('Account menu', 'boozed'); ?>"
                            aria-haspopup="menu"
                        >
                            <?php echo $account_icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <?php echo $account_chevron_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </button>
                        <div class="site-header__account-dropdown" role="menu">
                            <a href="<?php echo esc_url($wishlist_url); ?>" class="site-header__account-dropdown-link" role="menuitem"><?php esc_html_e('Wishlist', 'boozed'); ?></a>
                            <a href="<?php echo esc_url($logout_url); ?>" class="site-header__account-dropdown-link" role="menuitem"><?php esc_html_e('Uitloggen', 'boozed'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ( $business_phone ) : ?>
                    <a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $business_phone ) ); ?>" class="site-header__phone flex items-center gap-1.5 lg:gap-2 font-body text-body-sm lg:text-body-md hover:underline focus:outline-none focus:ring-2 focus:ring-brand-white focus:ring-offset-2 focus:ring-offset-transparent rounded whitespace-nowrap">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        <span><?php echo esc_html( $business_phone ); ?></span>
                    </a>
                <?php endif; ?>
                <?php
                $phosphor_chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256" fill="currentColor" class="w-5 h-5 shrink-0" aria-hidden="true"><path d="m181.66 133.66l-80 80a8 8 0 0 1-11.32-11.32L164.69 128L90.34 53.66a8 8 0 0 1 11.32-11.32l80 80a8 8 0 0 1 0 11.32Z"/></svg>';
                \App\Components::render( 'button', [
                    'variant'         => 'coral',
                    'label'           => $cta_text,
                    'href'            => home_url( '/contact/' ),
                    'icon_right_html' => $phosphor_chevron_right,
                    'class'           => 'site-header__cta !bg-brand-coral shrink-0 whitespace-nowrap',
                ] );
                ?>
            </div>

            <div class="site-header__mobile-controls md:hidden flex items-center gap-1.5 shrink-0">
                <div class="site-header__account js-account-menu">
                    <?php if ( ! $is_logged_in ) : ?>
                        <a href="<?php echo esc_url($login_url); ?>" class="site-header__account-login site-header__account-login--mobile" aria-label="<?php esc_attr_e('Login', 'boozed'); ?>">
                            <?php echo $account_icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </a>
                    <?php else : ?>
                        <button
                            type="button"
                            class="site-header__account-toggle site-header__account-toggle--mobile"
                            aria-label="<?php esc_attr_e('Account menu', 'boozed'); ?>"
                            aria-haspopup="menu"
                        >
                            <?php echo $account_icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <?php echo $account_chevron_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </button>
                        <div class="site-header__account-dropdown" role="menu">
                            <a href="<?php echo esc_url($wishlist_url); ?>" class="site-header__account-dropdown-link" role="menuitem"><?php esc_html_e('Wishlist', 'boozed'); ?></a>
                            <a href="<?php echo esc_url($logout_url); ?>" class="site-header__account-dropdown-link" role="menuitem"><?php esc_html_e('Uitloggen', 'boozed'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" class="site-header__toggle md:hidden flex flex-col justify-center items-center w-10 h-10 rounded focus:outline-none text-inherit" aria-expanded="false" aria-controls="mobile-menu" aria-label="<?php esc_attr_e( 'Open menu', 'boozed' ); ?>">
                    <span class="site-header__toggle-bar w-6 h-0.5 bg-current rounded-full transition-all duration-300"></span>
                    <span class="site-header__toggle-bar w-6 h-0.5 bg-current rounded-full mt-1.5 transition-all duration-300"></span>
                    <span class="site-header__toggle-bar w-6 h-0.5 bg-current rounded-full mt-1.5 transition-all duration-300"></span>
                </button>
            </div>
        </div>
    </header>

    <div class="site-header__mobile fixed inset-0 z-40 bg-brand-indigo opacity-0 invisible transition-opacity duration-300 md:opacity-0 md:invisible md:pointer-events-none overflow-y-auto overflow-x-hidden" id="mobile-menu" aria-hidden="true">
        <?php
        $mobile_menu_lottie_url = get_template_directory_uri() . '/assets/animations/boozed.json';
        ?>
        <div class="mobile-menu__lottie absolute inset-0 z-0 flex items-end justify-center pointer-events-none opacity-50 pb-16" aria-hidden="true" data-mobile-menu-lottie="<?php echo esc_url( $mobile_menu_lottie_url ); ?>">
            <div class="mobile-menu__lottie-inner w-[min(80vmin,400px)] h-[min(80vmin,400px)]"></div>
        </div>
        <div class="mobile-menu__content relative z-10 flex flex-col items-start justify-start pt-28 pb-12 px-6 pl-section-x">
            <?php if ( ! empty( $header_topbar_menu ) ) : ?>
                <nav class="flex flex-col items-stretch gap-0 w-full max-w-md mobile-menu__nav mobile-menu__topbar-nav mb-4" aria-label="<?php esc_attr_e( 'Topbar mobile menu', 'boozed' ); ?>">
                    <?php
                    wp_nav_menu( array_merge( $topbar_nav_args, [
                        'menu_class' => 'flex flex-col gap-0 font-body text-body-md font-medium text-brand-white mobile-menu__items',
                        'walker'     => new \App\MobileNavWalker(),
                    ] ) );
                    ?>
                </nav>
            <?php endif; ?>
            <nav class="flex flex-col items-stretch gap-0 w-full max-w-md mobile-menu__nav" aria-label="<?php esc_attr_e( 'Mobile menu', 'boozed' ); ?>">
                <?php
                if ( has_nav_menu( 'primary_navigation' ) || ! empty( $header_menu ) ) {
                    wp_nav_menu( array_merge( $nav_args, [
                        'menu_class'  => 'flex flex-col gap-0 font-body text-h3 font-medium text-brand-white mobile-menu__items',
                        'walker'      => new \App\MobileNavWalker(),
                    ] ) );
                }
                ?>
            </nav>
            <div class="mobile-menu__actions mt-12 flex flex-col items-start gap-6">
                <?php if ( $business_phone ) : ?>
                    <a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $business_phone ) ); ?>" class="mobile-menu__action flex items-center gap-3 font-body text-body-lg text-brand-white hover:opacity-90 transition-colors">
                        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        <?php echo esc_html( $business_phone ); ?>
                    </a>
                <?php endif; ?>
                <?php
                \App\Components::render( 'button', [
                    'variant'         => 'coral',
                    'label'           => $cta_text,
                    'href'            => home_url( '/contact/' ),
                    'icon_right_html' => $phosphor_chevron_right,
                    'class'           => 'mobile-menu__action !bg-brand-coral px-10 py-4 text-body-lg rounded-none',
                ] );
                ?>
            </div>
        </div>
    </div>

    <main id="main" class="site-main">
