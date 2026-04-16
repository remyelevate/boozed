<?php

/**
 * Boozed theme – ACF flexible sections via App\Fields.
 */

// PSR-4 style autoload for App namespace (theme app/ directory).
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base   = __DIR__ . '/app/';
    $len    = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative = substr($class, $len);
    $file     = $base . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

// Load theme setup and ACF registration.
$setup = __DIR__ . '/app/setup.php';
if (is_file($setup)) {
    require $setup;
}

/**
 * Use /inloggen instead of /wp-login (Dutch login URL).
 */
add_filter('login_url', function ($login_url) {
    return str_replace(['wp-login.php', 'wp-login'], 'inloggen', $login_url);
}, 10, 1);

add_filter('logout_url', function ($logout_url) {
    return str_replace(['wp-login.php', 'wp-login'], 'inloggen', $logout_url);
}, 10, 1);

add_filter('register_url', function ($register_url) {
    return str_replace(['wp-login.php', 'wp-login'], 'inloggen', $register_url);
}, 10, 1);

add_filter('lostpassword_url', function ($lostpassword_url) {
    return str_replace(['wp-login.php', 'wp-login'], 'inloggen', $lostpassword_url);
}, 10, 1);

add_filter('login_redirect', function ($redirect_to, $requested_redirect_to, $user) {
    if ($requested_redirect_to !== '' && wp_validate_redirect($requested_redirect_to, home_url()) !== false) {
        return $requested_redirect_to;
    }
    return $redirect_to;
}, 10, 3);

// Redirect GET requests to wp-login.php to /inloggen (allow POST so the login form submission works).
add_action('login_init', function () {
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
    if (strpos($script, 'wp-login') !== false && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
        wp_safe_redirect(site_url('/inloggen' . $query));
        exit;
    }
});

add_action('template_redirect', function () {
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (preg_match('#^/wp-login(\?|$)#', $uri)) {
        $query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
        wp_safe_redirect(site_url('/inloggen' . $query));
        exit;
    }
});

/**
 * Make /inloggen work without webserver-specific rewrites.
 *
 * On Apache this is usually handled by .htaccess rewriting /inloggen → /inloggen.php.
 * On Nginx/.htaccess-less setups, we add a WP rewrite so /inloggen resolves via index.php.
 */
add_action('init', function () {
    add_rewrite_rule('^inloggen/?$', 'index.php?boozed_inloggen=1', 'top');
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'boozed_inloggen';
    return $vars;
});

add_action('template_redirect', function () {
    if ((int) get_query_var('boozed_inloggen') !== 1) {
        return;
    }

    $query = isset($_SERVER['QUERY_STRING']) ? (string) $_SERVER['QUERY_STRING'] : '';
    $_SERVER['REQUEST_URI'] = '/inloggen' . ($query ? '?' . $query : '');
    $_SERVER['SCRIPT_NAME'] = '/inloggen';

    require ABSPATH . 'wp-login.php';
    exit;
}, 0);

add_action('after_switch_theme', function () {
    flush_rewrite_rules(false);
});

/**
 * Login page URL with optional redirect. Use for "Inloggen" links so users return after login.
 * Example: assortment page login button → href="<?php echo esc_url( boozed_login_url( get_permalink() ) ); ?>"
 *
 * @param string $redirect Full URL to send the user to after login (e.g. get_permalink() or home_url('/assortiment')).
 * @return string Login page URL with redirect_to query arg when $redirect is provided.
 */
function boozed_login_url($redirect = '')
{
    $login_page = home_url('/inloggen');
    if ($redirect !== '' && wp_validate_redirect($redirect, home_url()) !== false) {
        return add_query_arg('redirect_to', urlencode($redirect), $login_page);
    }
    return $login_page;
}

/**
 * PLP (Product Listing Page) URL.
 *
 * Returns the permalink of the page whose slug is "assortiment".
 * Falls back to the WooCommerce shop page, then home.
 */
function boozed_plp_url()
{
    static $url;
    if ($url !== null) {
        return $url;
    }
    $page = get_page_by_path('assortiment');
    if ($page) {
        $url = get_permalink($page);
        return $url;
    }
    if (function_exists('wc_get_page_permalink')) {
        $url = wc_get_page_permalink('shop');
        if ($url) {
            return $url;
        }
    }
    $url = home_url('/');
    return $url;
}

/**
 * Permalink for the news overview (page slug "nieuws", else Posts page, else home).
 */
function boozed_news_index_url()
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }
    $page = get_page_by_path('nieuws');
    if ($page instanceof \WP_Post) {
        $cached = get_permalink($page);
        return $cached;
    }
    $posts_page = (int) get_option('page_for_posts');
    if ($posts_page > 0) {
        $cached = get_permalink($posts_page);
        return $cached;
    }
    $cached = home_url('/');
    return $cached;
}

/**
 * Estimated reading time in minutes (min 1) from post content.
 */
function boozed_post_reading_time_minutes($post_id)
{
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return 1;
    }
    $post = get_post($post_id);
    if (!$post) {
        return 1;
    }
    $text = wp_strip_all_tags(strip_shortcodes((string) $post->post_content));
    $words = $text !== '' ? str_word_count($text) : 0;
    return max(1, (int) ceil($words / 200));
}
