<?php

namespace App;

/**
 * Custom nav walker: first top-level item with children = mega menu;
 * other top-level items with children = small dropdown.
 */
class NavWalker extends \Walker_Nav_Menu {

    /** @var array<int, array> Map of parent ID => child items */
    protected $children_map = [];

    /** @var int Index of current top-level item (0 = first) */
    protected $top_level_index = 0;

    /** @var bool Whether current top-level item should get mega menu */
    protected $use_mega = false;

    /** @var int Index of current child inside mega or dropdown (0 = featured in mega) */
    protected $child_index = 0;

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( ! isset( $args ) ) {
            return;
        }
        if ( $depth === 0 && $this->use_mega ) {
            return;
        }
        if ( $depth === 0 ) {
            $output .= '<ul class="nav-dropdown">';
            return;
        }
        parent::start_lvl( $output, $depth, $args );
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 && $this->use_mega ) {
            return;
        }
        if ( $depth === 0 ) {
            $output .= '</ul>';
            return;
        }
        parent::end_lvl( $output, $depth, $args );
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        if ( ! isset( $args ) ) {
            return;
        }
        $classes = empty( $item->classes ) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if ( $depth === 0 ) {
            $this->build_children_map_if_needed( $args );
            $children = $this->children_map[ $item->ID ] ?? [];
            $has_children = ! empty( $children );
            $is_first = $this->top_level_index === 0;
            $this->use_mega = $is_first && self::has_mega_menu_items(); /* First nav item = mega menu when ACF has items */

            if ( $this->use_mega ) {
                $classes[] = 'has-mega-menu';
            } elseif ( $has_children ) {
                $classes[] = 'has-dropdown';
            }
        }

        $class_names = implode( ' ', array_filter( $classes ) );
        $link_attrs = [
            'class' => $depth === 0 ? 'nav-link' : ( $this->use_mega && $this->child_index === 0 ? 'mega-menu-featured-link' : 'mega-menu-link' ),
            'href'  => ! empty( $item->url ) ? self::ensure_current_host_url( $item->url ) : '#',
        ];
        if ( $item->current ) {
            $link_attrs['class'] .= ' is-current';
        }
        $atts = [];
        foreach ( $link_attrs as $attr => $value ) {
            if ( $attr === 'href' ) {
                $atts['href'] = esc_url( $value );
            } else {
                $atts[ $attr ] = esc_attr( $value );
            }
        }
        $attr_string = '';
        foreach ( $atts as $k => $v ) {
            $attr_string .= ' ' . $k . '="' . $v . '"';
        }

        if ( $depth === 0 ) {
            $output .= '<li class="' . esc_attr( $class_names ) . '" data-depth="0">';
            $title_html = esc_html( $item->title );
            if ( self::is_vacature_menu_item( $item ) ) {
                $count = self::get_vacature_count();
                $title_html .= ' <span class="nav-vacature-badge inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full bg-brand-coral text-brand-white font-body text-body-xs font-medium leading-none ml-1.5" aria-label="' . esc_attr( sprintf( _n( '%d vacature', '%d vacatures', $count, 'boozed' ), $count ) ) . '">' . (int) $count . '</span>';
            }
            $output .= '<a' . $attr_string . '>' . $title_html . '</a>';
            /* Mega panel is now rendered separately outside the nav via get_mega_menu_panel() */
            return;
        }

        if ( $depth === 1 && $this->use_mega ) {
            return;
        }

        if ( $depth === 1 && ! $this->use_mega ) {
            $title_html = esc_html( $item->title );
            if ( self::is_vacature_menu_item( $item ) ) {
                $count = self::get_vacature_count();
                $badge = '<span class="nav-vacature-badge inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full bg-brand-coral text-brand-white font-body text-body-xs font-medium leading-none ml-1.5" aria-label="' . esc_attr( sprintf( _n( '%d vacature', '%d vacatures', $count, 'boozed' ), $count ) ) . '">' . (int) $count . '</span>';
                $title_html = '<span class="nav-dropdown-link__inner">' . $title_html . ' ' . $badge . '</span>';
            }
            $output .= '<li class="nav-dropdown-item">';
            $output .= '<a href="' . esc_url( self::ensure_current_host_url( $item->url ) ) . '" class="nav-dropdown-link">' . $title_html . '</a>';
            return;
        }

        parent::start_el( $output, $item, $depth, $args, $id );
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $this->top_level_index++;
            $output .= '</li>';
            return;
        }
        if ( $depth === 1 && ! $this->use_mega ) {
            $output .= '</li>';
            return;
        }
        if ( $depth === 1 && $this->use_mega ) {
            return;
        }
        parent::end_el( $output, $item, $depth, $args );
    }

    /** @var bool|null Cached result of has_mega_menu_items */
    protected static $has_mega_items = null;

    /**
     * Whether the mega menu has items (from ACF option header_mega_menu).
     */
    public static function has_mega_menu_items(): bool {
        if ( self::$has_mega_items === null ) {
            $raw = get_field( 'header_mega_menu', 'option' );
            self::$has_mega_items = is_array( $raw ) && count( $raw ) > 0;
        }
        return self::$has_mega_items;
    }

    /**
     * Get mega menu items (from ACF option header_mega_menu).
     * Returns array of [ 'title' => string, 'desc' => string, 'url' => string ].
     */
    public static function get_mega_menu_items(): array {
        $raw   = get_field( 'header_mega_menu', 'option' );
        $items = [];
        if ( is_array( $raw ) ) {
            foreach ( $raw as $row ) {
                $url = isset( $row['url'] ) ? (string) $row['url'] : '';
                $items[] = [
                    'title' => isset( $row['label'] ) ? (string) $row['label'] : '',
                    'desc'  => isset( $row['description'] ) ? (string) $row['description'] : '',
                    'url'   => $url ? self::ensure_current_host_url( $url ) : '',
                ];
            }
        }
        return $items;
    }

    /**
     * Static method to render the mega menu panel (call from header.php).
     * Items are loaded from ACF option header_mega_menu (repeater: label, description, url).
     */
    public static function get_mega_menu_panel() {
        $items = self::get_mega_menu_items();

        $arrow = '<svg class="mega-menu-item-arrow w-6 h-6 shrink-0 text-brand-red" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>';

        $html  = '<div class="mega-menu-panel" id="header-mega-panel" aria-hidden="true">';
        $html .= '<div class="mega-menu-panel__bg"></div>';
        $html .= '<div class="mega-menu-inner mega-menu-inner--first">';
        $html .= '<div class="mega-menu-first-content">';
        $html .= '<div class="mega-menu-items">';

        foreach ( $items as $item ) {
            $html .= '<div class="mega-menu-item-wrapper">';
            $html .= '<a href="' . esc_url( $item['url'] ) . '" class="mega-menu-first-item">';
            $html .= '<span class="mega-menu-item-title">' . esc_html( $item['title'] ) . '</span>';
            $html .= $arrow;
            $html .= '</a>';
            $html .= '<p class="mega-menu-item-desc"><span class="mega-menu-item-desc-text">' . esc_html( $item['desc'] ) . '</span></p>';
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Ensure internal URLs use the current host (fixes hardcoded localhost:8000 etc).
     * External URLs are returned unchanged.
     */
    public static function ensure_current_host_url( string $url ): string {
        if ( $url === '' || $url === '#' ) {
            return $url;
        }
        $parsed  = wp_parse_url( $url );
        $path    = isset( $parsed['path'] ) ? $parsed['path'] : '/';
        $path    = $path ?: '/';
        $query   = isset( $parsed['query'] ) ? '?' . $parsed['query'] : '';
        $fragment = isset( $parsed['fragment'] ) ? '#' . $parsed['fragment'] : '';
        if ( empty( $parsed['host'] ) ) {
            return home_url( $path . $query . $fragment );
        }
        $home_parsed = wp_parse_url( home_url() );
        $home_host   = isset( $home_parsed['host'] ) ? strtolower( $home_parsed['host'] ) : '';
        $url_host    = strtolower( $parsed['host'] );
        if ( $home_host !== '' && $url_host === $home_host ) {
            return home_url( $path . $query . $fragment );
        }
        return $url;
    }

    /**
     * Whether this menu item links to the vacature archive (Werken bij Boozed).
     */
    public static function is_vacature_menu_item( $item ): bool {
        if ( empty( $item->url ) ) {
            return false;
        }
        $archive_url = get_post_type_archive_link( 'vacature' );
        if ( ! $archive_url ) {
            return false;
        }
        $item_path   = rtrim( wp_parse_url( $item->url, PHP_URL_PATH ) ?? '', '/' );
        $archive_path = rtrim( wp_parse_url( $archive_url, PHP_URL_PATH ) ?? '', '/' );
        return $item_path !== '' && $archive_path !== '' && $item_path === $archive_path;
    }

    /**
     * Number of published vacature posts (for nav badge).
     */
    public static function get_vacature_count(): int {
        $counts = wp_count_posts( 'vacature' );
        return (int) ( $counts->publish ?? 0 );
    }

    protected function build_children_map_if_needed( $args ) {
        if ( $this->children_map !== [] ) {
            return;
        }
        $menu_id = isset( $args->menu ) ? $args->menu : null;
        if ( ! $menu_id && ! empty( $args->theme_location ) ) {
            $locations = get_nav_menu_locations();
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
