<?php

namespace App;

/**
 * Nav walker for mobile menu: outputs all items with accordion-friendly submenus.
 * Unlike NavWalker, includes children for mega menu items (Experience, Rental, Fabrications).
 */
class MobileNavWalker extends \Walker_Nav_Menu {

    /** @var array<int, array> Map of parent ID => child items */
    protected $children_map = [];

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        /* depth 1: we output submenu manually in start_el, suppress duplicate */
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        /* depth 1: suppress */
    }

    public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( $depth === 0 ) {
            if ( ! empty( $children_elements[ $element->ID ] ) ) {
                $this->has_manual_children = true;
                $this->manual_parent_id    = $element->ID;
            } else {
                $this->has_manual_children = false;
                $this->manual_parent_id    = null;
            }
        }
        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    /** @var bool */
    protected $has_manual_children = false;

    /** @var int|null */
    protected $manual_parent_id = null;

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        if ( ! isset( $args ) ) {
            return;
        }
        $this->build_children_map_if_needed( $args );
        $children = $this->children_map[ $item->ID ] ?? [];
        $has_children = ! empty( $children );

        $classes = empty( $item->classes ) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        if ( $depth === 0 ) {
            if ( $has_children ) {
                $classes[] = 'mobile-menu__accordion';
            }
        }
        $class_names = implode( ' ', array_filter( $classes ) );

        if ( $depth === 0 ) {
            $title_html = esc_html( $item->title );
            if ( NavWalker::is_vacature_menu_item( $item ) ) {
                $count = NavWalker::get_vacature_count();
                $title_html .= ' <span class="nav-vacature-badge inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full bg-brand-coral text-brand-white font-body text-body-xs font-medium leading-none ml-1.5" aria-label="' . esc_attr( sprintf( _n( '%d vacature', '%d vacatures', $count, 'boozed' ), $count ) ) . '">' . (int) $count . '</span>';
            }
            $output .= '<li class="' . esc_attr( $class_names ) . '">';
            if ( $has_children ) {
                $output .= '<button type="button" class="mobile-menu__accordion-trigger nav-link w-full flex items-center justify-between gap-2 py-4 text-left" aria-expanded="false" aria-controls="mobile-submenu-' . esc_attr( $item->ID ) . '" data-submenu-id="mobile-submenu-' . esc_attr( $item->ID ) . '">';
                $output .= '<span>' . $title_html . '</span>';
                $output .= '<svg class="mobile-menu__accordion-icon w-5 h-5 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
                $output .= '</button>';
                $output .= '<ul id="mobile-submenu-' . esc_attr( $item->ID ) . '" class="mobile-menu__submenu" aria-hidden="true" hidden>';
                foreach ( $children as $child ) {
                    $child_title = esc_html( $child->title );
                    if ( NavWalker::is_vacature_menu_item( $child ) ) {
                        $count = NavWalker::get_vacature_count();
                        $child_title .= ' <span class="nav-vacature-badge inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full bg-brand-coral text-brand-white font-body text-body-xs font-medium leading-none ml-1.5" aria-label="' . esc_attr( sprintf( _n( '%d vacature', '%d vacatures', $count, 'boozed' ), $count ) ) . '">' . (int) $count . '</span>';
                    }
                    $output .= '<li class="mobile-menu__submenu-item">';
                    $output .= '<a href="' . esc_url( NavWalker::ensure_current_host_url( $child->url ) ) . '" class="mobile-menu__submenu-link block py-2 pl-4 pr-2 text-inherit no-underline">' . $child_title . '</a>';
                    $output .= '</li>';
                }
                $output .= '</ul>';
            } else {
                $output .= '<a href="' . esc_url( NavWalker::ensure_current_host_url( $item->url ) ) . '" class="nav-link block py-4">' . $title_html . '</a>';
            }
            return;
        }

        if ( $depth === 1 && $this->has_manual_children && $item->menu_item_parent == $this->manual_parent_id ) {
            return;
        }
        parent::start_el( $output, $item, $depth, $args, $id );
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '</li>';
            return;
        }
        if ( $depth === 1 && $this->has_manual_children ) {
            return;
        }
        parent::end_el( $output, $item, $depth, $args );
    }

    protected function build_children_map_if_needed( $args ) {
        if ( $this->children_map !== [] ) {
            return;
        }
        $menu_id = isset( $args->menu ) ? $args->menu : null;
        if ( ! $menu_id && ! empty( $args->theme_location ) ) {
            $locations = wp_get_nav_menu_locations();
            $menu_id   = isset( $locations[ $args->theme_location ] ) ? $locations[ $args->theme_location ] : null;
        }
        if ( ! $menu_id ) {
            return;
        }
        $items = wp_get_nav_menu_items( $menu_id );
        if ( ! is_array( $items ) ) {
            return;
        }
        foreach ( $items as $i ) {
            $parent = (int) $i->menu_item_parent;
            if ( ! isset( $this->children_map[ $parent ] ) ) {
                $this->children_map[ $parent ] = [];
            }
            $this->children_map[ $parent ][] = $i;
        }
    }
}
