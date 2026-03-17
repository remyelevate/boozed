<?php

namespace App\PostTypes;

class Testimonial
{
    public static function register(): void
    {
        add_action('init', [ __CLASS__, 'register_post_type' ], 10);
    }

    public static function register_post_type(): void
    {
        register_post_type('testimonial', [
            'labels'              => [
                'name'               => __('Testimonials', 'boozed'),
                'singular_name'      => __('Testimonial', 'boozed'),
                'add_new'            => __('Add New', 'boozed'),
                'add_new_item'       => __('Add New Testimonial', 'boozed'),
                'edit_item'          => __('Edit Testimonial', 'boozed'),
                'new_item'           => __('New Testimonial', 'boozed'),
                'view_item'          => __('View Testimonial', 'boozed'),
                'search_items'       => __('Search Testimonials', 'boozed'),
                'not_found'          => __('No testimonials found', 'boozed'),
                'not_found_in_trash' => __('No testimonials found in Trash', 'boozed'),
                'menu_name'          => __('Testimonials', 'boozed'),
            ],
            'public'              => true,
            'has_archive'         => false,
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-format-quote',
            'supports'            => [ 'title', 'editor', 'thumbnail' ],
        ]);
    }
}
