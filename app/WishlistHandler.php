<?php

namespace App;

class WishlistHandler
{
    public const NONCE_ACTION = 'boozed_wishlist';
    public const PRODUCT_META_KEY = '_boozed_wishlist_products';
    public const QUERY_VAR = 'boozed_wishlist';
    public const INDEX_QUERY_VAR = 'boozed_wishlist_index';
    public const REWRITE_FLUSH_OPTION = 'boozed_wishlist_route_flushed_v2';

    public static function init(): void
    {
        add_action('init', [__CLASS__, 'registerWishlistRoute'], 20);
        add_action('init', [__CLASS__, 'maybeFlushRewriteRules'], 30);
        add_action('after_switch_theme', [__CLASS__, 'flushRewriteRules']);
        add_filter('query_vars', [__CLASS__, 'registerQueryVars']);
        add_filter('template_include', [__CLASS__, 'templateInclude']);
        add_action('template_redirect', [__CLASS__, 'protectWishlistRoute']);
        add_filter('wp_robots', [__CLASS__, 'wishlistRouteRobots']);
        add_action('send_headers', [__CLASS__, 'wishlistRouteHeaders']);

        add_action('wp_ajax_boozed_wishlist_list', [__CLASS__, 'ajaxListWishlists']);
        add_action('wp_ajax_boozed_wishlist_create', [__CLASS__, 'ajaxCreateWishlist']);
        add_action('wp_ajax_boozed_wishlist_rename', [__CLASS__, 'ajaxRenameWishlist']);
        add_action('wp_ajax_boozed_wishlist_delete', [__CLASS__, 'ajaxDeleteWishlist']);
        add_action('wp_ajax_boozed_wishlist_add_product', [__CLASS__, 'ajaxAddProduct']);
        add_action('wp_ajax_boozed_wishlist_remove_product', [__CLASS__, 'ajaxRemoveProduct']);
        add_action('wp_ajax_boozed_wishlist_move_product', [__CLASS__, 'ajaxMoveProduct']);
        add_action('wp_ajax_boozed_wishlist_request_quote', [__CLASS__, 'ajaxRequestQuote']);
    }

    public static function getWishlistsForCurrentUser(): array
    {
        $user_id = get_current_user_id();
        if ($user_id <= 0) {
            return [];
        }

        $posts = get_posts([
            'post_type'      => 'wishlist',
            'post_status'    => ['publish', 'private'],
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'author'         => $user_id,
        ]);

        return array_map([__CLASS__, 'formatWishlistPost'], $posts);
    }

    public static function getOrCreateDefaultWishlistId(int $user_id): int
    {
        if ($user_id <= 0) {
            return 0;
        }

        $existing = get_posts([
            'post_type'      => 'wishlist',
            'post_status'    => ['publish', 'private'],
            'posts_per_page' => 1,
            'author'         => $user_id,
            'orderby'        => 'date',
            'order'          => 'ASC',
            'fields'         => 'ids',
        ]);
        if (!empty($existing[0])) {
            return (int) $existing[0];
        }

        return self::createWishlist($user_id, __('Algemene Wenslijst', 'boozed'));
    }

    public static function getCurrentUserWishlistsWithProducts(): array
    {
        $wishlists = self::getWishlistsForCurrentUser();
        foreach ($wishlists as &$wishlist) {
            $wishlist['products'] = self::getProductsForWishlist((int) $wishlist['id']);
        }
        unset($wishlist);

        return $wishlists;
    }

    public static function registerWishlistRoute(): void
    {
        add_rewrite_rule(
            '^wishlist/?$',
            'index.php?' . self::INDEX_QUERY_VAR . '=1',
            'top'
        );
        add_rewrite_rule(
            '^wishlist/([^/]+)/?$',
            'index.php?' . self::QUERY_VAR . '=$matches[1]',
            'top'
        );
    }

    public static function maybeFlushRewriteRules(): void
    {
        if (get_option(self::REWRITE_FLUSH_OPTION) === 'yes') {
            return;
        }
        self::flushRewriteRules();
    }

    public static function flushRewriteRules(): void
    {
        self::registerWishlistRoute();
        flush_rewrite_rules(false);
        update_option(self::REWRITE_FLUSH_OPTION, 'yes');
    }

    public static function registerQueryVars(array $vars): array
    {
        $vars[] = self::QUERY_VAR;
        $vars[] = self::INDEX_QUERY_VAR;
        return $vars;
    }

    public static function templateInclude(string $template): string
    {
        if (!self::isWishlistRouteRequest() && !self::isWishlistIndexRequest()) {
            return $template;
        }

        if (self::isWishlistIndexRequest()) {
            $index_template = get_template_directory() . '/wishlist-index.php';
            return is_file($index_template) ? $index_template : $template;
        }

        $wishlist = self::getRequestedWishlist();
        if ($wishlist instanceof \WP_Post) {
            set_query_var('boozed_current_wishlist', $wishlist);
        }
        $custom = get_template_directory() . '/wishlist-route.php';
        return is_file($custom) ? $custom : $template;
    }

    public static function protectWishlistRoute(): void
    {
        if (!self::isWishlistRouteRequest() && !self::isWishlistIndexRequest()) {
            return;
        }

        if (!is_user_logged_in()) {
            $current = self::getCurrentUrl();
            $login_url = function_exists('boozed_login_url') ? boozed_login_url($current) : wp_login_url($current);
            wp_safe_redirect($login_url);
            exit;
        }

        if (self::isWishlistRouteRequest() && !self::getRequestedWishlist()) {
            global $wp_query;
            if ($wp_query instanceof \WP_Query) {
                $wp_query->set_404();
            }
            status_header(404);
            nocache_headers();
        }
    }

    public static function wishlistRouteRobots(array $robots): array
    {
        if (!self::isWishlistRouteRequest() && !self::isWishlistIndexRequest()) {
            return $robots;
        }
        $robots['index'] = false;
        $robots['follow'] = false;
        $robots['noarchive'] = true;
        return $robots;
    }

    public static function wishlistRouteHeaders(): void
    {
        if (!self::isWishlistRouteRequest() && !self::isWishlistIndexRequest()) {
            return;
        }
        header('X-Robots-Tag: noindex, nofollow, noarchive', true);
    }

    public static function isProductInCurrentUserWishlists(int $product_id): bool
    {
        if ($product_id <= 0 || !is_user_logged_in()) {
            return false;
        }

        $wishlist_ids = get_posts([
            'post_type'      => 'wishlist',
            'post_status'    => ['publish', 'private'],
            'posts_per_page' => -1,
            'author'         => get_current_user_id(),
            'fields'         => 'ids',
        ]);

        foreach ($wishlist_ids as $wishlist_id) {
            if (in_array($product_id, self::getWishlistProductIds((int) $wishlist_id), true)) {
                return true;
            }
        }

        return false;
    }

    public static function ajaxListWishlists(): void
    {
        if (!self::checkRequestAccess()) {
            return;
        }
        wp_send_json_success([
            'wishlists' => self::getWishlistsForCurrentUser(),
        ]);
    }

    public static function ajaxCreateWishlist(): void
    {
        if (!self::checkRequestAccess()) {
            return;
        }
        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
        if ($name === '') {
            wp_send_json_error(['message' => __('Geef je lijst een naam.', 'boozed')]);
        }

        $wishlist_id = self::createWishlist(get_current_user_id(), $name);
        if ($wishlist_id <= 0) {
            wp_send_json_error(['message' => __('Kon de wenslijst niet aanmaken.', 'boozed')]);
        }

        wp_send_json_success([
            'wishlist'  => self::formatWishlistPost(get_post($wishlist_id)),
            'wishlists' => self::getWishlistsForCurrentUser(),
        ]);
    }

    public static function ajaxRenameWishlist(): void
    {
        if (!self::checkRequestAccess()) {
            return;
        }
        $wishlist_id = isset($_POST['wishlist_id']) ? absint($_POST['wishlist_id']) : 0;
        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
        $wishlist = self::getOwnedWishlist($wishlist_id);
        if (!$wishlist) {
            wp_send_json_error(['message' => __('Wenslijst niet gevonden.', 'boozed')]);
        }
        if ($name === '') {
            wp_send_json_error(['message' => __('Geef je lijst een naam.', 'boozed')]);
        }

        $base_slug = sanitize_title($name);
        if ($base_slug === '') {
            $base_slug = 'wenslijst';
        }
        $unique_slug = wp_unique_post_slug(
            $base_slug,
            $wishlist->post_status ?: 'publish',
            $wishlist->post_type,
            (int) $wishlist->post_parent,
            (int) $wishlist->ID
        );

        $updated = wp_update_post([
            'ID'         => $wishlist_id,
            'post_title' => $name,
            'post_name'  => $unique_slug,
        ], true);

        if (is_wp_error($updated)) {
            wp_send_json_error(['message' => __('Kon de wenslijst niet hernoemen.', 'boozed')]);
        }

        wp_send_json_success(['wishlists' => self::getWishlistsForCurrentUser()]);
    }

    public static function ajaxDeleteWishlist(): void
    {
        if (!self::checkRequestAccess()) {
            return;
        }

        $wishlist_id = isset($_POST['wishlist_id']) ? absint($_POST['wishlist_id']) : 0;
        $wishlist = self::getOwnedWishlist($wishlist_id);
        if (!$wishlist) {
            wp_send_json_error(['message' => __('Wenslijst niet gevonden.', 'boozed')]);
        }

        wp_delete_post($wishlist_id, true);
        wp_send_json_success(['wishlists' => self::getWishlistsForCurrentUser()]);
    }

    public static function ajaxAddProduct(): void
    {
        if (!self::checkRequestAccess()) {
            return;
        }

        $wishlist_id = isset($_POST['wishlist_id']) ? absint($_POST['wishlist_id']) : 0;
        $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
        if ($product_id <= 0 || !self::isValidProduct($product_id)) {
            wp_send_json_error(['message' => __('Dit product kon niet worden toegevoegd.', 'boozed')]);
        }

        if ($wishlist_id <= 0) {
            $wishlist_id = self::getOrCreateDefaultWishlistId(get_current_user_id());
        }
        $wishlist = self::getOwnedWishlist($wishlist_id);
        if (!$wishlist) {
            wp_send_json_error(['message' => __('Wenslijst niet gevonden.', 'boozed')]);
        }

        $ids = self::getWishlistProductIds($wishlist_id);
        if (!in_array($product_id, $ids, true)) {
            $ids[] = $product_id;
            update_post_meta($wishlist_id, self::PRODUCT_META_KEY, array_values($ids));
        }

        wp_send_json_success([
            'message'   => sprintf(
                /* translators: %s: product title */
                __('"%s" toegevoegd aan Wenslijst', 'boozed'),
                get_the_title($product_id)
            ),
            'wishlist_url' => self::getWishlistUrl((int) $wishlist_id),
            'wishlists' => self::getWishlistsForCurrentUser(),
        ]);
    }

    public static function ajaxRemoveProduct(): void
    {
        if (!self::checkRequestAccess()) {
            return;
        }

        $wishlist_id = isset($_POST['wishlist_id']) ? absint($_POST['wishlist_id']) : 0;
        $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
        $wishlist = self::getOwnedWishlist($wishlist_id);
        if (!$wishlist || $product_id <= 0) {
            wp_send_json_error(['message' => __('Kon product niet verwijderen.', 'boozed')]);
        }

        $ids = array_values(array_filter(
            self::getWishlistProductIds($wishlist_id),
            static function ($id) use ($product_id) {
                return (int) $id !== $product_id;
            }
        ));
        update_post_meta($wishlist_id, self::PRODUCT_META_KEY, $ids);

        wp_send_json_success([
            'wishlists' => self::getCurrentUserWishlistsWithProducts(),
        ]);
    }

    public static function ajaxMoveProduct(): void
    {
        if (!self::checkRequestAccess()) {
            return;
        }

        $source_id = isset($_POST['source_wishlist_id']) ? absint($_POST['source_wishlist_id']) : 0;
        $target_id = isset($_POST['target_wishlist_id']) ? absint($_POST['target_wishlist_id']) : 0;
        $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

        $source = self::getOwnedWishlist($source_id);
        $target = self::getOwnedWishlist($target_id);
        if (!$source || !$target || $product_id <= 0 || $source_id === $target_id) {
            wp_send_json_error(['message' => __('Verplaatsen is mislukt.', 'boozed')]);
        }

        $source_ids = self::getWishlistProductIds($source_id);
        if (!in_array($product_id, $source_ids, true)) {
            wp_send_json_error(['message' => __('Product staat niet in de gekozen wenslijst.', 'boozed')]);
        }

        $source_ids = array_values(array_filter($source_ids, static function ($id) use ($product_id) {
            return (int) $id !== $product_id;
        }));
        $target_ids = self::getWishlistProductIds($target_id);
        if (!in_array($product_id, $target_ids, true)) {
            $target_ids[] = $product_id;
        }

        update_post_meta($source_id, self::PRODUCT_META_KEY, $source_ids);
        update_post_meta($target_id, self::PRODUCT_META_KEY, array_values($target_ids));

        wp_send_json_success([
            'wishlists' => self::getCurrentUserWishlistsWithProducts(),
        ]);
    }

    public static function ajaxRequestQuote(): void
    {
        if (!self::checkRequestAccess()) {
            return;
        }

        $wishlist_id = isset($_POST['wishlist_id']) ? absint($_POST['wishlist_id']) : 0;
        $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
        $wishlist = self::getOwnedWishlist($wishlist_id);
        if (!$wishlist) {
            wp_send_json_error(['message' => __('Wenslijst niet gevonden.', 'boozed')]);
        }

        $product_ids = self::getWishlistProductIds($wishlist_id);
        if (empty($product_ids)) {
            wp_send_json_error(['message' => __('Je wenslijst is leeg.', 'boozed')]);
        }

        $user = wp_get_current_user();
        $to = get_option('admin_email');
        $subject = sprintf(
            '[%s] Offerteaanvraag wenslijst: %s',
            get_bloginfo('name'),
            $wishlist->post_title
        );

        $product_lines = [];
        foreach ($product_ids as $pid) {
            $product_lines[] = sprintf(
                '- %s (%s)',
                get_the_title($pid),
                get_permalink($pid)
            );
        }

        $body = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="font-family:sans-serif;line-height:1.5;">';
        $body .= '<h2>Offerteaanvraag vanaf wenslijst</h2>';
        $body .= '<p><strong>Gebruiker:</strong> ' . esc_html($user->display_name) . ' (' . esc_html($user->user_email) . ')</p>';
        $body .= '<p><strong>Wenslijst:</strong> ' . esc_html($wishlist->post_title) . '</p>';
        $body .= '<p><strong>Wenslijst URL:</strong> ' . esc_html(self::getWishlistUrl((int) $wishlist->ID)) . '</p>';
        if ($message !== '') {
            $body .= '<p><strong>Extra informatie:</strong><br>' . nl2br(esc_html($message)) . '</p>';
        }
        $body .= '<h3>Producten</h3><pre style="white-space:pre-wrap;">' . esc_html(implode("\n", $product_lines)) . '</pre>';
        $body .= '</body></html>';

        $sent = wp_mail($to, $subject, $body, ['Content-Type: text/html; charset=UTF-8']);
        if (!$sent) {
            wp_send_json_error(['message' => __('Kon de aanvraag niet versturen. Probeer opnieuw.', 'boozed')]);
        }

        wp_send_json_success([
            'message' => __('Je aanvraag is verstuurd. We nemen snel contact op.', 'boozed'),
        ]);
    }

    private static function checkRequestAccess(): bool
    {
        if (!check_ajax_referer(self::NONCE_ACTION, 'nonce', false)) {
            wp_send_json_error(['message' => __('Sessie verlopen. Vernieuw de pagina en probeer opnieuw.', 'boozed')]);
            return false;
        }
        if (!is_user_logged_in()) {
            $current_url = self::getCurrentUrl();
            $login_url = function_exists('boozed_login_url') ? boozed_login_url($current_url) : wp_login_url($current_url);
            wp_send_json_error([
                'requires_login' => true,
                'login_url'      => $login_url,
            ]);
            return false;
        }
        return true;
    }

    private static function getCurrentUrl(): string
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash((string) $_SERVER['REQUEST_URI']) : '/';
        return home_url($uri);
    }

    private static function isWishlistRouteRequest(): bool
    {
        $slug = get_query_var(self::QUERY_VAR);
        return is_string($slug) && $slug !== '';
    }

    private static function isWishlistIndexRequest(): bool
    {
        return (string) get_query_var(self::INDEX_QUERY_VAR) === '1';
    }

    private static function getRequestedWishlist(): ?\WP_Post
    {
        if (!is_user_logged_in()) {
            return null;
        }
        $slug = get_query_var(self::QUERY_VAR);
        $slug = is_string($slug) ? sanitize_title($slug) : '';
        if ($slug === '') {
            return null;
        }

        $posts = get_posts([
            'name'           => $slug,
            'post_type'      => 'wishlist',
            'post_status'    => ['publish', 'private'],
            'posts_per_page' => 1,
            'author'         => get_current_user_id(),
        ]);

        return !empty($posts[0]) && $posts[0] instanceof \WP_Post ? $posts[0] : null;
    }

    private static function getWishlistUrl(int $wishlist_id): string
    {
        $wishlist = get_post($wishlist_id);
        if (!$wishlist instanceof \WP_Post || $wishlist->post_type !== 'wishlist') {
            return home_url('/wishlist/');
        }
        return home_url('/wishlist/' . $wishlist->post_name . '/');
    }

    private static function createWishlist(int $user_id, string $name): int
    {
        $post_id = wp_insert_post([
            'post_type'   => 'wishlist',
            'post_status' => 'publish',
            'post_title'  => $name,
            'post_author' => $user_id,
        ], true);

        if (is_wp_error($post_id)) {
            return 0;
        }

        update_post_meta((int) $post_id, self::PRODUCT_META_KEY, []);
        return (int) $post_id;
    }

    private static function getOwnedWishlist(int $wishlist_id): ?\WP_Post
    {
        if ($wishlist_id <= 0) {
            return null;
        }
        $wishlist = get_post($wishlist_id);
        if (!$wishlist || $wishlist->post_type !== 'wishlist') {
            return null;
        }
        if ((int) $wishlist->post_author !== get_current_user_id()) {
            return null;
        }
        return $wishlist;
    }

    private static function getWishlistProductIds(int $wishlist_id): array
    {
        $raw = get_post_meta($wishlist_id, self::PRODUCT_META_KEY, true);
        if (!is_array($raw)) {
            return [];
        }
        $ids = array_values(array_unique(array_filter(array_map('absint', $raw))));
        return array_values(array_filter($ids, [__CLASS__, 'isValidProduct']));
    }

    private static function isValidProduct(int $product_id): bool
    {
        if ($product_id <= 0 || get_post_type($product_id) !== 'product') {
            return false;
        }
        return get_post_status($product_id) === 'publish';
    }

    private static function formatWishlistPost($wishlist): array
    {
        if (!$wishlist instanceof \WP_Post) {
            return [];
        }
        return [
            'id'            => (int) $wishlist->ID,
            'title'         => $wishlist->post_title ?: __('Wenslijst', 'boozed'),
            'slug'          => $wishlist->post_name,
            'url'           => self::getWishlistUrl((int) $wishlist->ID),
            'product_count' => count(self::getWishlistProductIds((int) $wishlist->ID)),
        ];
    }

    private static function getProductsForWishlist(int $wishlist_id): array
    {
        $product_ids = self::getWishlistProductIds($wishlist_id);
        $items = [];
        foreach ($product_ids as $product_id) {
            $product = function_exists('wc_get_product') ? wc_get_product($product_id) : null;
            $items[] = [
                'id'         => $product_id,
                'title'      => get_the_title($product_id),
                'url'        => get_permalink($product_id),
                'image_url'  => get_the_post_thumbnail_url($product_id, 'thumbnail') ?: '',
                'price_html' => $product ? $product->get_price_html() : '',
            ];
        }
        return $items;
    }
}
