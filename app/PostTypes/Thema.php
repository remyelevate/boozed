<?php

namespace App\PostTypes;

class Thema
{
    public static function register(): void
    {
        add_action('init', [ __CLASS__, 'register_post_type' ], 10);
        add_action('init', [ __CLASS__, 'register_taxonomy' ], 11);
        add_action('init', [ __CLASS__, 'maybe_flush_rewrite_rules' ], 20);
        add_action('after_switch_theme', [ __CLASS__, 'flush_rewrite_rules' ]);
    }

    public static function register_taxonomy(): void
    {
        register_taxonomy('thema_categorie', 'thema', [
            'labels'            => [
                'name'          => __('Thema categories', 'boozed'),
                'singular_name'  => __('Thema category', 'boozed'),
                'search_items'   => __('Search categories', 'boozed'),
                'all_items'      => __('All categories', 'boozed'),
                'edit_item'      => __('Edit category', 'boozed'),
                'update_item'    => __('Update category', 'boozed'),
                'add_new_item'   => __('Add new category', 'boozed'),
                'new_item_name'  => __('New category name', 'boozed'),
            ],
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'rewrite'           => [ 'slug' => 'thema-categorie' ],
        ]);
    }

    /**
     * Flush rewrite rules once so single Thema URLs (e.g. /themas/post-name/) work.
     */
    public static function maybe_flush_rewrite_rules(): void
    {
        if (get_option('boozed_thema_rewrite_flushed') === 'yes') {
            return;
        }
        flush_rewrite_rules(false);
        update_option('boozed_thema_rewrite_flushed', 'yes');
    }

    public static function register_post_type(): void
    {
        register_post_type('thema', [
            'labels'              => [
                'name'               => __("Thema's", 'boozed'),
                'singular_name'      => __("Thema", 'boozed'),
                'add_new'            => __('Add New', 'boozed'),
                'add_new_item'       => __("Add New Thema", 'boozed'),
                'edit_item'          => __("Edit Thema", 'boozed'),
                'new_item'           => __("New Thema", 'boozed'),
                'view_item'          => __("View Thema", 'boozed'),
                'search_items'       => __("Search Thema's", 'boozed'),
                'not_found'          => __("No thema's found", 'boozed'),
                'not_found_in_trash' => __("No thema's found in Trash", 'boozed'),
                'menu_name'          => __("Thema's", 'boozed'),
            ],
            'public'              => true,
            'has_archive'         => true,
            'show_in_rest'       => true,
            'menu_icon'          => 'dashicons-admin-appearance',
            'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'rewrite'            => [ 'slug' => 'themas' ],
        ]);
    }

    public static function flush_rewrite_rules(): void
    {
        self::register_post_type();
        self::register_taxonomy();
        flush_rewrite_rules();
        update_option('boozed_thema_rewrite_flushed', 'yes');
    }
}
