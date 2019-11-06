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
 * Get the Honouree display_name if it exists, falling back to Honouree post_title
 *
 * @param int|null $honouree_id
 *
 * @return string
 */
function get_display_name( $honouree_id = null ) {
    if ( is_null( $honouree_id ) ) {
        $honouree_id = get_the_ID();
    }

    $display_name = get_field( 'display_name' );
    if ( empty( $display_name ) ) {
        $display_name = get_the_title( $honouree_id );
    }

    return $display_name;
}

/**
 * @param int|null $honouree_id
 *
 * @return bool|\WP_Term
 */
function get_award( $honouree_id = null ) {
    if ( is_null( $honouree_id ) ) {
        $honouree_id = get_the_ID();
    }

    $awards = get_the_terms( $honouree_id, 'award' );

    if ( empty( $awards ) ) {
        return false;
    }

    return $awards[0];
}

/**
 * @param int|null $post_id Honouree or Project ID
 *
 * @return bool|\WP_Term
 */
function get_award_year( $post_id = null ) {
    if ( is_null( $post_id ) ) {
        $post_id = get_the_ID();
    }

    $award_years = get_the_terms( $post_id, 'award-year' );

    if ( empty( $award_years ) ) {
        return false;
    }

    return $award_years[0];
}

/**
 * @param int|null $honouree_id
 *
 * @return bool|\WP_Term
 */
function get_award_category( $honouree_id = null ) {
    if ( is_null( $honouree_id ) ) {
        $honouree_id = get_the_ID();
    }

    $award_categories = get_the_terms( $honouree_id, 'award-category' );

    if ( empty( $award_categories ) ) {
        return false;
    }

    return $award_categories[0];
}

/**
 * @param int|null $honouree_id
 *
 * @return bool|\WP_Term
 */
function get_individual_award( $honouree_id = null ) {
    if ( is_null( $honouree_id ) ) {
        $honouree_id = get_the_ID();
    }

    $individual_award = get_the_terms( $honouree_id, 'individual-award' );

    if ( empty( $individual_award ) ) {
        return false;
    }

    return $individual_award[0];
}

/**
 * @param int|null $honouree_id
 *
 * @return bool|\WP_Term
 */
function get_honouree_type( $honouree_id = null ) {
    if ( is_null( $honouree_id ) ) {
        $honouree_id = get_the_ID();
    }

    $honouree_types = get_the_terms( $honouree_id, 'honouree-type' );

    if ( empty( $honouree_types ) ) {
        return false;
    }

    return $honouree_types[0];
}

/**
 * @param int|null $project_id
 *
 * @return bool|\WP_Term
 */
function get_project_award( $project_id = null ) {
    if ( is_null( $project_id ) ) {
        $project_id = get_the_ID();
    }

    $project_awards = get_the_terms( $project_id, 'project-award' );

    if ( empty( $project_awards ) ) {
        return false;
    }

    return $project_awards[0];
}

/**
 * @param null|int $honouree_id
 *
 * @return false|\WP_Post
 */
function get_team( $honouree_id = null ) {
    if ( is_null( $honouree_id ) ) {
        $honouree_id = get_the_ID();
    }

    $type = get_honouree_type( $honouree_id );

    if ( false === $type ) {
        return false;
    }

    switch ( $type->slug ) {
        case 'team-member':
            $team = get_field( 'related_team', $honouree_id );
            break;
        case 'team':
            $team = get_post( $honouree_id );
            break;
        case 'individual':
        default:
            $team = false;
            break;
    }

    return $team;
}

/**
 * @param null $team_id
 *
 * @return false|\WP_Post[]
 */
function get_team_members( $team_id = null ) {
    if ( is_null( $team_id ) ) {
        $team_id = get_the_ID();
    }

    $team_members = get_field( 'related_team_members', $team_id );

    if ( empty( $team_members ) ) {
        $team_members = [];
    }

    return $team_members;
}

/**
 * @param null $sponsor_id
 *
 * @return false|\WP_Term
 */
function get_sponsor_tier( $sponsor_id = null ) {
    if ( is_null( $sponsor_id ) ) {
        $sponsor_id = get_the_ID();
    }

    $sponsor_tier = get_the_terms( $sponsor_id, 'sponsor-tier' );

    if ( empty( $sponsor_tier ) ) {
        return false;
    }

    return $sponsor_tier[0];
}

/**
 * @param null $post_id
 *
 * @return string|null
 */
function get_post_byline( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $byline = get_field( 'byline' ) ?: get_the_author();

    return $byline;
}

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
        'aria_label'          => _x( 'Breadcrumbs', 'breadcrumbs aria label', 'clean50' ),
        'home'                => __( 'Home', 'clean50' ),
        'error_404'           => __( '404 Not Found', 'clean50' ),
        'archives'            => __( 'Archives', 'clean50' ),
        // Translators: %s is the search query.
        'search'              => __( 'Search results for: %s', 'clean50' ),
        // Translators: %s is the page number.
        'paged'               => __( 'Page %s', 'clean50' ),
        // Translators: %s is the page number.
        'paged_comments'      => __( 'Comment Page %s', 'clean50' ),
        // Translators: Minute archive title. %s is the minute time format.
        'archive_minute'      => __( 'Minute %s', 'clean50' ),
        // Translators: Weekly archive title. %s is the week date format.
        'archive_week'        => __( 'Week %s', 'clean50' ),

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

function to_array( $value ) {
    if ( ! is_array( $value ) ) {
        $value = [ $value ];
    }

    return $value;
}

function get_sponsor_carousel( $sponsors ) {
    $id    = uniqid( 'sponsors-' );
    $title = ( ! empty( $sponsors['title'] ) )
        ? sprintf( '<h5 class="carousel__title">%s</h5>', $sponsors['title'] )
        : '';

    $indicators = [];
    $slides     = [];
    foreach ( $sponsors['sponsors'] as $key => $sponsor ) {
        $indicators[] = sprintf(
            '<li data-target="#%s" data-slide-to="%s" class="%s"></li>',
            esc_attr( $id ),
            esc_attr( $key ),
            ( 0 === $key ) ? 'active' : ''
        );

        $link = get_field( 'link', $sponsor->ID );

        $slides[] = sprintf(
            '<div class="carousel-item %s"><a href="%s" target="_blank">%s</a><p class="sponsor__description">%s</p></div>',
            ( 0 === $key ) ? 'active' : '',
            ! empty( $link['target'] ) ? $link['target'] : '#',
            get_the_post_thumbnail(
                $sponsor->ID,
                'sponsor-carousel',
                [
                    'class' => 'sponsor__logo',
                    'alt'   => $sponsor->post_title,
                ]
            ),
            get_field( 'short_description', $sponsor->ID )
        );
    }

    if ( 1 === count( $indicators ) ) {
        $indicators = [];
    }

    $carousel_indicators = sprintf( '<ol class="carousel-indicators">%s</ol>', implode( '', $indicators ) );
    $carousel_slides     = sprintf( '<div class="carousel-inner">%s</div>', implode( '', $slides ) );
    $carousel            = sprintf( '<div id="%s" class="carousel slide" data-ride="carousel" data-interval="3000">%s%s</div>', $id, $carousel_indicators, $carousel_slides );
    $inner               = sprintf( '%s%s', $title, $carousel );
    $output              = sprintf( '<div class="sponsors-carousel %s">%s</div>',
        ( 1 < count( $slides ) ) ? 'has-indicators' : 'no-indicators',
        $inner
    );

    return $output;
}
