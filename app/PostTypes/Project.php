<?php

namespace App\PostTypes;

class Project
{
    public static function register(): void
    {
        add_action('init', [ __CLASS__, 'register_post_type' ], 10);
        add_action('init', [ __CLASS__, 'register_taxonomy' ], 10);
        add_action('init', [ __CLASS__, 'maybe_flush_rewrite_rules' ], 20);
        add_action('after_switch_theme', [ __CLASS__, 'flush_rewrite_rules' ]);
        add_filter('request', [ __CLASS__, 'prefer_projecten_page_over_archive' ], 5);
        add_action('pre_get_posts', [ __CLASS__, 'filter_archive_by_project_type' ]);
    }

    /**
     * When /projecten/ is requested on the front-end, serve the Page "Projecten" if it exists so its sections
     * (e.g. Page header + Projects lister) are shown instead of only the archive template.
     * Only applied on the front-end so the admin Projects list still shows actual project posts.
     */
    public static function prefer_projecten_page_over_archive(array $query_vars): array
    {
        if (is_admin()) {
            return $query_vars;
        }
        $is_project_archive = isset($query_vars['post_type']) && $query_vars['post_type'] === 'project'
            && empty($query_vars['name']);
        if (! $is_project_archive) {
            return $query_vars;
        }
        $page = get_page_by_path('projecten', OBJECT, 'page');
        if (! $page || $page->post_status !== 'publish') {
            return $query_vars;
        }
        return [ 'pagename' => 'projecten' ];
    }

    /**
     * On project archive (/projecten), filter the main query by project_type when ?project_type=slug is present.
     */
    public static function filter_archive_by_project_type(\WP_Query $query): void
    {
        if (! $query->is_main_query() || ! $query->is_post_type_archive('project')) {
            return;
        }
        $filter_slug = isset($_GET['project_type']) ? sanitize_text_field(wp_unslash($_GET['project_type'])) : '';
        if ($filter_slug === '') {
            return;
        }
        $query->set('tax_query', [
            [
                'taxonomy' => 'project_type',
                'field'    => 'slug',
                'terms'    => $filter_slug,
            ],
        ]);
    }

    /**
     * Flush rewrite rules once so single project URLs (e.g. /projecten/post-name/) work.
     * Runs after CPT registration; only flushes when the option is not set.
     */
    public static function maybe_flush_rewrite_rules(): void
    {
        if (get_option('boozed_project_rewrite_flushed') === 'yes') {
            return;
        }
        flush_rewrite_rules(false);
        update_option('boozed_project_rewrite_flushed', 'yes');
    }

    public static function register_post_type(): void
    {
        register_post_type('project', [
            'labels'              => [
                'name'               => __('Projects', 'boozed'),
                'singular_name'      => __('Project', 'boozed'),
                'add_new'            => __('Add New', 'boozed'),
                'add_new_item'       => __('Add New Project', 'boozed'),
                'edit_item'          => __('Edit Project', 'boozed'),
                'new_item'           => __('New Project', 'boozed'),
                'view_item'          => __('View Project', 'boozed'),
                'search_items'       => __('Search Projects', 'boozed'),
                'not_found'          => __('No projects found', 'boozed'),
                'not_found_in_trash' => __('No projects found in Trash', 'boozed'),
                'menu_name'          => __('Projects', 'boozed'),
            ],
            'public'              => true,
            'has_archive'         => true,
            'show_in_rest'       => true,
            'menu_icon'          => 'dashicons-portfolio',
            'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'rewrite'            => [ 'slug' => 'projecten' ],
        ]);
    }

    public static function register_taxonomy(): void
    {
        register_taxonomy('project_type', 'project', [
            'labels'            => [
                'name'          => __('Project types', 'boozed'),
                'singular_name' => __('Project type', 'boozed'),
                'search_items'   => __('Search project types', 'boozed'),
                'all_items'      => __('All project types', 'boozed'),
                'edit_item'      => __('Edit project type', 'boozed'),
                'update_item'    => __('Update project type', 'boozed'),
                'add_new_item'   => __('Add new project type', 'boozed'),
                'new_item_name'  => __('New project type name', 'boozed'),
                'menu_name'      => __('Project types', 'boozed'),
            ],
            'hierarchical'      => true,
            'show_ui'          => true,
            'show_in_rest'    => true,
            'show_admin_column' => true,
            'rewrite'         => [ 'slug' => 'project-type' ],
        ]);
    }

    public static function flush_rewrite_rules(): void
    {
        self::register_post_type();
        self::register_taxonomy();
        flush_rewrite_rules();
        update_option('boozed_project_rewrite_flushed', 'yes');
    }
}
