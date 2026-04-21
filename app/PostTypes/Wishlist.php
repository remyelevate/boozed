<?php

namespace App\PostTypes;

class Wishlist
{
    public static function register(): void
    {
        add_action('init', [__CLASS__, 'register_post_type'], 10);
    }

    public static function register_post_type(): void
    {
        register_post_type('wishlist', [
            'labels' => [
                'name'               => __('Wishlists', 'boozed'),
                'singular_name'      => __('Wishlist', 'boozed'),
                'add_new'            => __('Add New', 'boozed'),
                'add_new_item'       => __('Add New Wishlist', 'boozed'),
                'edit_item'          => __('Edit Wishlist', 'boozed'),
                'new_item'           => __('New Wishlist', 'boozed'),
                'view_item'          => __('View Wishlist', 'boozed'),
                'search_items'       => __('Search Wishlists', 'boozed'),
                'not_found'          => __('No wishlists found', 'boozed'),
                'not_found_in_trash' => __('No wishlists found in Trash', 'boozed'),
                'menu_name'          => __('Wishlists', 'boozed'),
            ],
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_rest'        => false,
            'supports'            => ['title', 'author'],
            'map_meta_cap'        => true,
            'capability_type'     => 'post',
            'has_archive'         => false,
            'rewrite'             => false,
            'menu_icon'           => 'dashicons-heart',
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'query_var'           => false,
        ]);
    }
}
