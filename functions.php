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
 * Resolve auth page URL from page slug, with fallback path.
 */
function boozed_auth_page_url($slug, $fallback_path)
{
    $page = get_page_by_path($slug);
    if ($page instanceof \WP_Post) {
        return trailingslashit(get_permalink($page));
    }
    return trailingslashit(home_url($fallback_path));
}

function boozed_login_page_url()
{
    return boozed_auth_page_url('inloggen', '/inloggen');
}

function boozed_forgot_password_page_url()
{
    return boozed_auth_page_url('wachtwoord-vergeten', '/wachtwoord-vergeten');
}

function boozed_reset_password_page_url()
{
    return boozed_auth_page_url('wachtwoord-resetten', '/wachtwoord-resetten');
}

/**
 * Use /inloggen instead of /wp-login (Dutch login URL).
 */
add_filter('login_url', function ($login_url, $redirect, $force_reauth) {
    $url = boozed_login_page_url();
    if (!empty($redirect) && wp_validate_redirect($redirect, home_url()) !== false) {
        $url = add_query_arg('redirect_to', rawurlencode($redirect), $url);
    }
    return $url;
}, 10, 3);

add_filter('logout_url', function ($logout_url, $redirect) {
    $target = boozed_login_page_url();
    $query  = [];
    $parsed = [];
    $raw_logout_url = (string) $logout_url;

    $logout_query = wp_parse_url($raw_logout_url, PHP_URL_QUERY);
    if (is_string($logout_query) && $logout_query !== '') {
        // Handle both normal query strings and escaped ampersands (&amp;).
        parse_str(str_replace('&amp;', '&', $logout_query), $parsed);
        if (is_array($parsed) && !empty($parsed['action'])) {
            $query['action'] = sanitize_key((string) $parsed['action']);
        }
    }

    if (!isset($query['action'])) {
        $query['action'] = 'logout';
    }

    if (!empty($parsed['_wpnonce'])) {
        $query['_wpnonce'] = sanitize_text_field((string) $parsed['_wpnonce']);
    } else {
        // Fallback: extract nonce directly if query parsing missed it.
        if (preg_match('/(?:[?&]|&amp;)_wpnonce=([a-zA-Z0-9]+)/', $raw_logout_url, $m) === 1) {
            $query['_wpnonce'] = sanitize_text_field((string) $m[1]);
        }
    }

    $resolved_redirect = is_string($redirect) ? $redirect : '';
    if ($resolved_redirect === '' && !empty($parsed['redirect_to'])) {
        $resolved_redirect = (string) $parsed['redirect_to'];
    }
    if ($resolved_redirect !== '' && wp_validate_redirect($resolved_redirect, home_url('/')) !== false) {
        $query['redirect_to'] = $resolved_redirect;
    }

    return add_query_arg($query, $target);
}, 10, 2);

add_filter('register_url', function ($register_url) {
    return str_replace(['wp-login.php', 'wp-login'], 'inloggen', $register_url);
}, 10, 1);

add_filter('lostpassword_url', function ($lostpassword_url) {
    return boozed_forgot_password_page_url();
}, 10, 1);

add_filter('login_redirect', function ($redirect_to, $requested_redirect_to, $user) {
    if ($requested_redirect_to !== '' && wp_validate_redirect($requested_redirect_to, home_url()) !== false) {
        return $requested_redirect_to;
    }
    return $redirect_to;
}, 10, 3);

/**
 * Handle front-end logout action.
 * Supports URLs like /inloggen/?action=logout&_wpnonce=...&redirect_to=...
 */
add_action('init', function () {
    if (strtolower((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'get') {
        return;
    }

    $action = isset($_GET['action']) ? sanitize_key((string) $_GET['action']) : '';
    if ($action !== 'logout') {
        return;
    }

    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce((string) $_GET['_wpnonce'], 'log-out')) {
        wp_die(__('Weet je zeker dat je wilt uitloggen?', 'boozed'), 403);
    }

    wp_logout();

    $redirect_to = isset($_GET['redirect_to']) ? esc_url_raw((string) wp_unslash($_GET['redirect_to'])) : home_url('/');
    if (wp_validate_redirect($redirect_to, home_url('/')) === false) {
        $redirect_to = home_url('/');
    }

    wp_safe_redirect($redirect_to);
    exit;
});

// Redirect GET requests to wp-login.php to /inloggen (allow POST so the login form submission works).
add_action('login_init', function () {
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
    if (strpos($script, 'wp-login') === false || $_SERVER['REQUEST_METHOD'] === 'POST') {
        return;
    }

    $action = isset($_GET['action']) ? sanitize_key((string) $_GET['action']) : 'login';
    $target = boozed_login_page_url();
    if (in_array($action, ['lostpassword', 'retrievepassword'], true)) {
        $target = boozed_forgot_password_page_url();
    } elseif (in_array($action, ['rp', 'resetpass'], true)) {
        $target = boozed_reset_password_page_url();
    }

    $query = [];
    if (isset($_GET['key'])) {
        $query['key'] = sanitize_text_field((string) wp_unslash($_GET['key']));
    }
    if (isset($_GET['login'])) {
        $query['login'] = sanitize_text_field((string) wp_unslash($_GET['login']));
    }
    if (isset($_GET['redirect_to'])) {
        $query['redirect_to'] = esc_url_raw((string) wp_unslash($_GET['redirect_to']));
    }
    if (!empty($query)) {
        $target = add_query_arg($query, $target);
    }

    wp_safe_redirect($target);
    exit;
});

add_action('template_redirect', function () {
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (preg_match('#^/wp-login(\?|$)#', $uri)) {
        $target = boozed_login_page_url();
        if (!empty($_GET['action']) && in_array(sanitize_key((string) $_GET['action']), ['lostpassword', 'retrievepassword'], true)) {
            $target = boozed_forgot_password_page_url();
        } elseif (!empty($_GET['action']) && in_array(sanitize_key((string) $_GET['action']), ['rp', 'resetpass'], true)) {
            $target = boozed_reset_password_page_url();
        }

        $query = [];
        if (isset($_GET['key'])) {
            $query['key'] = sanitize_text_field((string) wp_unslash($_GET['key']));
        }
        if (isset($_GET['login'])) {
            $query['login'] = sanitize_text_field((string) wp_unslash($_GET['login']));
        }
        if (isset($_GET['redirect_to'])) {
            $query['redirect_to'] = esc_url_raw((string) wp_unslash($_GET['redirect_to']));
        }
        if (!empty($query)) {
            $target = add_query_arg($query, $target);
        }

        wp_safe_redirect($target);
        exit;
    }
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
    $login_page = boozed_login_page_url();
    if ($redirect !== '' && wp_validate_redirect($redirect, home_url()) !== false) {
        return add_query_arg('redirect_to', urlencode($redirect), $login_page);
    }
    return $login_page;
}

/**
 * Swap footer "Inloggen" item to logout for authenticated users.
 *
 * Keeps the menu management simple in WP admin: editors can keep one "Inloggen"
 * item in the footer and this filter turns it into "Uitloggen" when needed.
 */
add_filter('wp_nav_menu_objects', function ($items, $args) {
    if (!is_array($items) || !is_user_logged_in() || !is_object($args)) {
        return $items;
    }

    $menu_class = isset($args->menu_class) ? (string) $args->menu_class : '';
    if ($menu_class === '' || strpos($menu_class, 'site-footer__menu-list') === false) {
        return $items;
    }

    foreach ($items as $item) {
        if (!is_object($item) || !isset($item->title)) {
            continue;
        }

        if (trim(wp_strip_all_tags((string) $item->title)) !== __('Inloggen', 'boozed')) {
            continue;
        }

        $item->title = __('Uitloggen', 'boozed');
        $item->url   = wp_logout_url(home_url('/'));
    }

    return $items;
}, 10, 2);

/**
 * Replace wp-login reset links in the password reset email.
 */
add_filter('retrieve_password_message', function ($message, $key, $user_login, $user_data) {
    $target_reset_page = boozed_reset_password_page_url();
    if (!empty($_POST['boozed_reset_target'])) {
        $posted = esc_url_raw((string) wp_unslash($_POST['boozed_reset_target']));
        if ($posted !== '' && wp_validate_redirect($posted, home_url()) !== false) {
            $target_reset_page = $posted;
        }
    }

    $reset_url = add_query_arg(
        [
            'key'   => rawurlencode($key),
            'login' => rawurlencode($user_login),
        ],
        $target_reset_page
    );

    return preg_replace('#https?://[^\s]*wp-login\.php\?action=rp[^\s]*#', $reset_url, $message);
}, 10, 4);

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
