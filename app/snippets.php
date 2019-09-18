<?php
/**
 * Filename snippets.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Snippets;

use Hybrid\Breadcrumbs\Breadcrumbs;
use Hybrid\Breadcrumbs\Trail;

/**
 * Returns a formatted year to year string suitable for use in copyright statements etc.
 *
 * @param string $from
 * @param string $separator
 *
 * @return string Range of years if different, single year if the same.
 */
function year_from_to( $from, $separator = '&ndash;' ) {
    $to = date( 'Y' );

    $from_to = ( $from === $to )
        ? $from
        : "{$from}{$separator}{$to}";

    return $from_to;
}


function render_button( $button, $classes = [ 'btn', 'btn-primary' ], $echo = true ) {
    if ( 'page' === $button['link']['type'] ) {
        $target = sprintf( '#%s', $button['link']['target'] );
    } elseif ( 'internal' === $button['link']['type'] ) {
        if ( is_numeric( $button['link']['target'] ) ) {
            //TODO ACF bug with cloned fields yields raw value in certain cases
            $target = get_the_permalink( $button['link']['target'] );
        } else {
            $target = $button['link']['target'];
        }
    } else {
        $target = $button['link']['target'];
    }


    $button = sprintf( '<a class="%s" href="%s">%s</a>',
        esc_attr( implode( ' ', $classes ) ),
        esc_attr( $target ),
        wp_kses_post( $button['label'] )
    );

    if ( $echo ) {
        echo $button;
    }

    return $button;
}

function bootstrap_pagination( $wp_query = null, $args = [], $echo = true ) {
    if ( null === $wp_query ) {
        global $wp_query;
    }

    unset( $args['type'] );

    $args = wp_parse_args( $args, [
        'base'         => str_replace( PHP_INT_MAX, '%#%', esc_url( get_pagenum_link( PHP_INT_MAX ) ) ),
        'format'       => '?paged=%#%',
        'current'      => max( 1, get_query_var( 'paged' ) ),
        'total'        => $wp_query->max_num_pages,
        'type'         => 'array',
        'show_all'     => false,
        'end_size'     => 3,
        'mid_size'     => 1,
        'prev_next'    => true,
        'prev_text'    => _x( 'Previous', '', '' ),
        'next_text'    => _x( 'Next', '', '' ),
        'add_args'     => false,
        'add_fragment' => '',
    ] );

    $pages = paginate_links( $args );

    if ( empty( $pages ) ) {
        return '';
    }

    $items = array_map( function ( $page ) {
        $classes = [ 'page-item' ];
        if ( false !== strpos( $page, 'current' ) ) {
            $classes[] = 'active';
        }
        $link = sprintf(
            '<li class="%s">%s</li>',
            implode( ' ', array_filter( $classes, 'esc_attr' ) ),
            str_replace( 'page-numbers', 'page-link', $page )
        );

        return $link;
    }, $pages );

    $pagination = sprintf( '<ul class="pagination">%s</ul>', implode( '', $items ) );

    if ( $echo ) {
        echo $pagination;
    }

    return $pagination;
}

function cached_menu( $location, $args = [] ) {
    if ( ! has_nav_menu( 'primary' ) ) {
        return '';
    }

    $echo = isset( $args['echo'] ) ? $args['echo'] : true;

    $transient = "toibox_cached_menu_$location";

    $args['echo']     = false;
    $args['location'] = $location;

    $args = wp_parse_args( $args, [] );

    $menu = get_transient( $transient );
    if ( false === $menu ) {
        $menu = wp_nav_menu( $args );
        set_transient( $transient, $menu, PHP_INT_MAX );
    }

    if ( $echo ) {
        echo $menu;
    }

    return $menu;
}

function breadcrumbs() {
    $labels = [
        'title'               => false, //__( 'Browse:', 'hybrid-core' ),
        'aria_label'          => _x( 'Breadcrumbs', 'breadcrumbs aria label', 'hybrid-core' ),
        'home'                => __( 'Home', 'hybrid-core' ),
        'error_404'           => __( '404 Not Found', 'hybrid-core' ),
        'archives'            => __( 'Archives', 'hybrid-core' ),
        // Translators: %s is the search query.
        'search'              => __( 'Search results for: %s', 'hybrid-core' ),
        // Translators: %s is the page number.
        'paged'               => __( 'Page %s', 'hybrid-core' ),
        // Translators: %s is the page number.
        'paged_comments'      => __( 'Comment Page %s', 'hybrid-core' ),
        // Translators: Minute archive title. %s is the minute time format.
        'archive_minute'      => __( 'Minute %s', 'hybrid-core' ),
        // Translators: Weekly archive title. %s is the week date format.
        'archive_week'        => __( 'Week %s', 'hybrid-core' ),

        // "%s" is replaced with the translated date/time format.
        'archive_minute_hour' => '%s',
        'archive_hour'        => '%s',
        'archive_day'         => '%s',
        'archive_month'       => '%s',
        'archive_year'        => '%s',
    ];

    $args = [
        'labels'          => $labels,
        'post_taxonomy'   => [
            'honouree' => 'award-year',
            'project'  => 'award-year',
        ],
        'show_on_front'   => false,
        'show_trail_end'  => true,
        'network'         => false,
        'before'          => '',
        'after'           => '',
        'container_tag'   => 'nav',
        'title_tag'       => 'h2',
        'list_tag'        => 'ol',
        'item_tag'        => 'li',
        'container_class' => false,
        'title_class'     => 'title',
        'list_class'      => 'breadcrumb',
        'item_class'      => 'breadcrumb-item'
    ];

    $breadcrumbs = new Breadcrumbs( $args );
    $breadcrumbs->make()->display();
}
