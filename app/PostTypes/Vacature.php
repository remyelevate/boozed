<?php

namespace App\PostTypes;

class Vacature
{
    public static function register(): void
    {
        add_action('init', [ __CLASS__, 'register_post_type' ], 10);
        add_action('init', [ __CLASS__, 'register_taxonomies' ], 10);
        add_action('init', [ __CLASS__, 'maybe_flush_rewrite_rules' ], 20);
        add_action('after_switch_theme', [ __CLASS__, 'flush_rewrite_rules' ]);
        add_action('pre_get_posts', [ __CLASS__, 'filter_archive_by_locatie' ]);
    }

    /**
     * On vacature archive (/vacatures), filter the main query by locatie when ?locatie=slug is present.
     */
    public static function filter_archive_by_locatie(\WP_Query $query): void
    {
        if (! $query->is_main_query() || ! $query->is_post_type_archive('vacature')) {
            return;
        }
        $filter_slug = isset($_GET['locatie']) ? sanitize_text_field(wp_unslash($_GET['locatie'])) : '';
        if ($filter_slug === '') {
            return;
        }
        $query->set('tax_query', [
            [
                'taxonomy' => 'locatie',
                'field'    => 'slug',
                'terms'    => $filter_slug,
            ],
        ]);
    }

    /**
     * Flush rewrite rules once so single vacature URLs (e.g. /vacatures/post-name/) work.
     */
    public static function maybe_flush_rewrite_rules(): void
    {
        if (get_option('boozed_vacature_rewrite_flushed') === 'yes') {
            return;
        }
        flush_rewrite_rules(false);
        update_option('boozed_vacature_rewrite_flushed', 'yes');
    }

    public static function register_post_type(): void
    {
        register_post_type('vacature', [
            'labels'              => [
                'name'               => __('Vacatures', 'boozed'),
                'singular_name'      => __('Vacature', 'boozed'),
                'add_new'            => __('Add New', 'boozed'),
                'add_new_item'       => __('Add New Vacature', 'boozed'),
                'edit_item'          => __('Edit Vacature', 'boozed'),
                'new_item'           => __('New Vacature', 'boozed'),
                'view_item'          => __('View Vacature', 'boozed'),
                'search_items'       => __('Search Vacatures', 'boozed'),
                'not_found'          => __('No vacatures found', 'boozed'),
                'not_found_in_trash' => __('No vacatures found in Trash', 'boozed'),
                'menu_name'          => __('Vacatures', 'boozed'),
            ],
            'public'              => true,
            'has_archive'         => true,
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-id-alt',
            'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'rewrite'             => [ 'slug' => 'vacatures' ],
        ]);
    }

    public static function register_taxonomies(): void
    {
        $taxonomies = [
            'locatie' => [
                'labels' => [
                    'name'          => __('Locaties', 'boozed'),
                    'singular_name' => __('Locatie', 'boozed'),
                    'search_items'  => __('Search locaties', 'boozed'),
                    'all_items'     => __('All locaties', 'boozed'),
                    'edit_item'     => __('Edit locatie', 'boozed'),
                    'update_item'   => __('Update locatie', 'boozed'),
                    'add_new_item'  => __('Add new locatie', 'boozed'),
                    'new_item_name' => __('New locatie name', 'boozed'),
                    'menu_name'     => __('Locatie', 'boozed'),
                ],
                'rewrite' => [ 'slug' => 'locatie' ],
            ],
            'niveau' => [
                'labels' => [
                    'name'          => __('Niveaus', 'boozed'),
                    'singular_name' => __('Niveau', 'boozed'),
                    'search_items'  => __('Search niveaus', 'boozed'),
                    'all_items'     => __('All niveaus', 'boozed'),
                    'edit_item'     => __('Edit niveau', 'boozed'),
                    'update_item'   => __('Update niveau', 'boozed'),
                    'add_new_item'  => __('Add new niveau', 'boozed'),
                    'new_item_name' => __('New niveau name', 'boozed'),
                    'menu_name'     => __('Niveau', 'boozed'),
                ],
                'rewrite' => [ 'slug' => 'niveau' ],
            ],
            'team' => [
                'labels' => [
                    'name'          => __('Teams', 'boozed'),
                    'singular_name' => __('Team', 'boozed'),
                    'search_items'  => __('Search teams', 'boozed'),
                    'all_items'     => __('All teams', 'boozed'),
                    'edit_item'     => __('Edit team', 'boozed'),
                    'update_item'   => __('Update team', 'boozed'),
                    'add_new_item'  => __('Add new team', 'boozed'),
                    'new_item_name' => __('New team name', 'boozed'),
                    'menu_name'     => __('Team', 'boozed'),
                ],
                'rewrite' => [ 'slug' => 'team' ],
            ],
            'dienstverband' => [
                'labels' => [
                    'name'          => __('Dienstverbanden', 'boozed'),
                    'singular_name' => __('Dienstverband', 'boozed'),
                    'search_items'  => __('Search dienstverbanden', 'boozed'),
                    'all_items'     => __('All dienstverbanden', 'boozed'),
                    'edit_item'     => __('Edit dienstverband', 'boozed'),
                    'update_item'   => __('Update dienstverband', 'boozed'),
                    'add_new_item'  => __('Add new dienstverband', 'boozed'),
                    'new_item_name' => __('New dienstverband name', 'boozed'),
                    'menu_name'     => __('Dienstverband', 'boozed'),
                ],
                'rewrite' => [ 'slug' => 'dienstverband' ],
            ],
            'uren' => [
                'labels' => [
                    'name'          => __('Uren', 'boozed'),
                    'singular_name' => __('Uren', 'boozed'),
                    'search_items'  => __('Search uren', 'boozed'),
                    'all_items'     => __('All uren', 'boozed'),
                    'edit_item'     => __('Edit uren', 'boozed'),
                    'update_item'   => __('Update uren', 'boozed'),
                    'add_new_item'  => __('Add new uren', 'boozed'),
                    'new_item_name' => __('New uren name', 'boozed'),
                    'menu_name'     => __('Uren', 'boozed'),
                ],
                'rewrite' => [ 'slug' => 'uren' ],
            ],
        ];

        foreach ($taxonomies as $taxonomy => $args) {
            register_taxonomy($taxonomy, 'vacature', array_merge([
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_in_rest'      => true,
                'show_admin_column' => true,
            ], $args));
        }
    }

    public static function flush_rewrite_rules(): void
    {
        self::register_post_type();
        self::register_taxonomies();
        flush_rewrite_rules();
        update_option('boozed_vacature_rewrite_flushed', 'yes');
    }
}
