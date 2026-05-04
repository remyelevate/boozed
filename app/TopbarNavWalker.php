<?php

namespace App;

/**
 * Header topbar menu walker with standard dropdowns (no mega menu).
 */
class TopbarNavWalker extends \Walker_Nav_Menu
{
    /** @var array<int, array> */
    protected $children_map = [];

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0) {
            $output .= '<ul class="nav-dropdown">';
            return;
        }
        parent::start_lvl($output, $depth, $args);
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0) {
            $output .= '</ul>';
            return;
        }
        parent::end_lvl($output, $depth, $args);
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        if (!isset($args)) {
            return;
        }

        $this->build_children_map_if_needed($args);
        $children = $this->children_map[$item->ID] ?? [];
        $has_children = !empty($children);

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        if ($depth === 0 && $has_children) {
            $classes[] = 'has-dropdown';
        }

        if ($depth === 0) {
            $class_names = implode(' ', array_filter($classes));
            $output .= '<li class="' . esc_attr($class_names) . '" data-depth="0">';

            $link_class = 'site-header__top-link';
            if ($item->current) {
                $link_class .= ' is-current';
            }

            $output .= '<a href="' . esc_url(NavWalker::ensure_current_host_url((string) $item->url)) . '" class="' . esc_attr($link_class) . '">';
            $output .= '<span>' . esc_html($item->title) . '</span>';
            if ($has_children) {
                $output .= '<svg class="site-header__top-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true" focusable="false"><path d="M211.31,100.69a8,8,0,0,1,0,11.31l-80,80a8,8,0,0,1-11.31,0l-80-80a8,8,0,0,1,11.31-11.31L128,177.37l76.69-76.68A8,8,0,0,1,211.31,100.69Z"></path></svg>';
            }
            $output .= '</a>';
            return;
        }

        if ($depth === 1) {
            $output .= '<li class="nav-dropdown-item">';
            $output .= '<a href="' . esc_url(NavWalker::ensure_current_host_url((string) $item->url)) . '" class="nav-dropdown-link">' . esc_html($item->title) . '</a>';
            return;
        }

        parent::start_el($output, $item, $depth, $args, $id);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if ($depth === 0 || $depth === 1) {
            $output .= '</li>';
            return;
        }
        parent::end_el($output, $item, $depth, $args);
    }

    protected function build_children_map_if_needed($args)
    {
        if ($this->children_map !== []) {
            return;
        }

        $menu_id = isset($args->menu) ? $args->menu : null;
        if (!$menu_id && !empty($args->theme_location)) {
            $locations = wp_get_nav_menu_locations();
            $menu_id = isset($locations[$args->theme_location]) ? $locations[$args->theme_location] : null;
        }
        if (!$menu_id) {
            return;
        }

        $items = wp_get_nav_menu_items($menu_id);
        if (!is_array($items)) {
            return;
        }

        foreach ($items as $menu_item) {
            $parent = (int) $menu_item->menu_item_parent;
            if (!isset($this->children_map[$parent])) {
                $this->children_map[$parent] = [];
            }
            $this->children_map[$parent][] = $menu_item;
        }
    }
}
