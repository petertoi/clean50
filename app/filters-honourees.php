<?php
/**
 * Filename filters-honourees.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Filters_Honourees;

use function Toi\ToiBox\Snippets\get_award;
use function Toi\ToiBox\Snippets\get_award_category;
use function Toi\ToiBox\Snippets\get_award_year;
use function Toi\ToiBox\Snippets\get_honouree_type;
use function Toi\ToiBox\Snippets\get_team;
use function Toi\ToiBox\Snippets\to_array;

/**
 * SAVE_POST_HONOUREE
 */

/**
 * Set sort name to last name if empty on save
 */
add_action( 'save_post_honouree', function ( $post_ID, $post, $update ) {
    $sort_name = get_field( 'sort_name', $post_ID, false );
    if ( empty( $sort_name ) ) {
        $last_name = get_field( 'last_name', $post_ID, false );
        update_field( 'sort_name', $last_name, $post_ID );
    }
}, 10, 3 );

/**
 * Set _award meta to term->slug on save to aid with ordering clauses
 */
add_action( 'save_post_honouree', function ( $post_ID, $post, $update ) {
    $award = get_award( $post_ID );
    if ( false === $award ) {
        delete_post_meta( $post_ID, '_award' );
    } else {
        update_post_meta( $post_ID, '_award', $award->slug );
    }
}, 10, 3 );

/**
 * Set _award-category meta to term->slug on save to aid with ordering clauses
 */
add_action( 'save_post_honouree', function ( $post_ID, $post, $update ) {
    $award_category = get_award_category( $post_ID );
    if ( false === $award_category ) {
        delete_post_meta( $post_ID, '_award-category' );
    } else {
        update_post_meta( $post_ID, '_award-category', $award_category->slug );
    }
}, 10, 3 );

/**
 * Set _award-year meta to term->slug on save to aid with ordering clauses
 */
add_action( 'save_post_honouree', function ( $post_ID, $post, $update ) {
    $award_year = get_award_year( $post_ID );
    if ( false === $award_year ) {
        delete_post_meta( $post_ID, '_award-year' );
    } else {
        update_post_meta( $post_ID, '_award-year', $award_year->slug );
    }
}, 10, 3 );

/**
 * Update Bi-directional relationship when a "Team Member" Honouree is saved
 * - Updates "Team" Honouree ensuring this "Team Member" is present
 * - Checks for an old "Team" relationship and removes this "Team Member" if present
 */
add_filter( 'acf/update_value/name=related_team', function ( $new_team_id, $post_id, $field ) {

    // Get the old team ID.
    $old_team_id = get_field( 'related_team', $post_id, false );

    // Bail if the team hasn't changed.
    if ( (int) $new_team_id === (int) $old_team_id ) {
        return $new_team_id;
    }

    // Add team member to new team.
    if ( is_numeric( $new_team_id ) ) {
        add_team_member_to_team( $post_id, $new_team_id );
    }

    // Remove team member from old team.
    if ( is_numeric( $old_team_id ) ) {
        remove_team_member_from_team( $post_id, $old_team_id );
    }

    // return
    return $new_team_id;

}, 10, 3 );

/**
 * Update Bi-directional relationship when a "Team" Honouree is saved
 * - Updates "Team Member" Honourees ensuring this "Team" is assigned
 * - Checks for old "Team Member" Honourees and removes this "Team" if present
 */
add_filter( 'acf/update_value/name=related_team_members', function ( $new_team_member_ids, $post_id, $field ) {

    // Get the old team member ids
    $old_team_member_ids = get_field( 'related_team_members', $post_id, false );
    if ( ! is_array( $old_team_member_ids ) ) {
        $old_team_member_ids = [];
    }

    // loop over the new team members and add the $post_id for this team
    $team_members_ids_to_add = array_diff( $new_team_member_ids, $old_team_member_ids );
    if ( ! empty( $team_members_ids_to_add ) ) {
        foreach ( $team_members_ids_to_add as $new_team_member_id ) {
            $existing_related_team = get_field( 'related_team', $new_team_member_id, false );
            if ( ! empty( $existing_related_team ) ) {
                remove_team_member_from_team( $new_team_member_id, $existing_related_team );
            }
            update_post_meta( $new_team_member_id, 'related_team', $post_id );
        }
    }

    $team_members_ids_to_delete = array_diff( $old_team_member_ids, $new_team_member_ids );
    if ( ! empty( $team_members_ids_to_delete ) ) {
        foreach ( $team_members_ids_to_delete as $old_team_member_id ) {
            delete_post_meta( $old_team_member_id, 'related_team' );
        }
    }

    // return
    return $new_team_member_ids;

}, 10, 3 );

/**
 * Remove Team Member from Team
 *
 * @param $team_member_id
 * @param $team_id
 */
function remove_team_member_from_team( $team_member_id, $team_id ) {
    $team_members = get_field( 'related_team_members', $team_id, false );

    if ( empty( $team_members ) ) {
        $team_members = [];
    }

    // find the position of $post_id within $value2 so we can remove it
    $pos = array_search( $team_member_id, $team_members );

    // remove
    if ( false !== $pos ) {
        unset( $team_members[ $pos ] );
    }

    // update the un-selected post's value
    update_post_meta( $team_id, 'related_team_members', $team_members );
}

/**
 * Add Team Member to Team
 *
 * @param $team_member_id
 * @param $team_id
 */
function add_team_member_to_team( $team_member_id, $team_id ) {
    // load existing related posts
    $related_team_members = get_field( 'related_team_members', $team_id, false );

    // allow for selected posts to not contain a value
    if ( empty( $related_team_members ) ) {
        $related_team_members = [];
    }
    // Update value if new
    if ( ! in_array( $team_member_id, $related_team_members ) ) {

        // append the current $team_member_id to the selected post's 'related_posts' value
        $related_team_members[] = $team_member_id;

        // update the selected post's value (use field's key for performance)
        update_post_meta( $team_id, 'related_team_members', $related_team_members );
    }
}

/** REQUEST */

/**
 * Use 'year' query string to filter by 'award-year' instead of default 'post_date'
 */
add_filter( 'request', function ( $query_vars ) {

    if ( empty( $query_vars['post_type'] ) || 'honouree' !== $query_vars['post_type'] ) {
        return $query_vars;
    }

    if ( empty( $query_vars['year'] ) ) {
        return $query_vars;
    }

    $query_vars['award-year'] = $query_vars['year'];
    unset( $query_vars['year'] );

    return $query_vars;

}, 10, 1 );

/**
 * Use 'category' query string to filter by 'award-category' instead of default 'category'
 */
add_filter( 'request', function ( $query_vars ) {

    if ( empty( $query_vars['post_type'] ) || 'honouree' !== $query_vars['post_type'] ) {
        return $query_vars;
    }

    if ( empty( $query_vars['cat'] ) ) {
        return $query_vars;
    }

    $query_vars['award-category'] = $query_vars['cat'];
    unset( $query_vars['cat'] );

    return $query_vars;

}, 10, 1 );

/** PRE_GET_POSTS */

/**
 * Set posts_per_page
 */
add_filter( 'pre_get_posts', function ( $query ) {
    /** @var \WP_Query $query */
    if ( is_admin() ) {
        return $query;
    }

    if ( ! is_post_type_archive( 'honouree' ) ) {
        return $query;
    }

    $per_page = get_theme_mod( '_toibox_honouree_archive_per_page' ) ?: 4;

    $query->set( 'posts_per_page', $per_page );

    return $query;
} );

/**
 * Set orderby
 */
add_filter( 'pre_get_posts', function ( $query ) {
    /** @var \WP_Query $query */
    if ( is_admin() ) {
        return $query;
    }

    if ( 'honouree' !== $query->get( 'post_type' ) ) {
        return $query;
    }

    $meta_query = $query->get( 'meta_query ' );

    if ( empty( $meta_query ) ) {
        $meta_query = [];
    }

    $meta_query['relation'] = 'AND';

    $meta_query['award_year_order'] = [
        'key'     => '_award-year',
        'compare' => 'EXISTS',
    ];

    $meta_query['award_order'] = [
        'key'     => '_award',
        'compare' => 'EXISTS',
    ];

    $meta_query['sort_name_order'] = [
        'key'     => 'sort_name',
        'compare' => 'EXISTS',
    ];

    $query->set( 'meta_query', $meta_query );

    $query->set( 'orderby', [
        'award_year_order' => 'DESC',
        'award_order'      => 'ASC',
        'sort_name_order'  => 'ASC',
    ] );

    return $query;
} );

/**
 * Include Clean16 with any request for Clean50 Award winners
 */
add_filter( 'pre_get_posts', function ( $query ) {
    /** @var \WP_Query $query */
    if ( is_admin() ) {
        return $query;
    }

    $post_type = $query->get( 'post_type' );

    if ( 'honouree' !== $post_type ) {
        return $query;
    }

    $award = $query->get( 'award' );

    if ( 'clean50' !== $award ) {
        return $query;
    }

    $tax_query = $query->get( 'tax_query' );
    if ( empty( $tax_query ) ) {
        $tax_query = array();
    }

    $tax_query[] = [
        'taxonomy' => 'award',
        'operator' => 'IN',
        'field'    => 'slug',
        'terms'    => [ 'clean16', 'clean50' ],
    ];

    $query->set( 'tax_query', $tax_query );
    $query->set( 'award', '' );

    return $query;

}, 10, 1 );

/**
 * Restrict which Honouree Types are returned based on Award or Award Category
 * - All Awards: No Teams
 * - Clean50: No Teams
 * - Clean16: No Team Members
 * - Any Category: No Team Members
 */
add_filter( 'pre_get_posts', function ( $query ) {
    /** @var \WP_Query $query */
    if ( is_admin() ) {
        return $query;
    }

    $post_type = $query->get( 'post_type' );

    if ( 'honouree' !== $post_type ) {
        return $query;
    }

    $tax_query = $query->get( 'tax_query' );

    if ( ! is_array( $tax_query ) ) {
        $tax_query = [];
    }

    $award_id = array_reduce( $tax_query, function ( $carry, $value ) {
        if ( false !== $carry ) {
            return $carry;
        }

        $tax = is_array( $value ) ? $value['taxonomy'] : false;

        if ( 'award' === $tax ) {
            return $value['terms'];
        }

        return false;
    }, false );

    if ( false === $award_id || is_array( $award_id ) ) {
        $tax_query[] = [
            'taxonomy' => 'honouree-type',
            'field'    => 'slug',
            'terms'    => 'team',
            'operator' => 'NOT IN',
        ];
        $query->set( 'tax_query', $tax_query );

        return $query;
    }

    $award = get_term( $award_id, 'award' );

    switch ( $award->name ) {
        case "Clean16":
            $tax_query[] = [
                'taxonomy' => 'honouree-type',
                'field'    => 'slug',
                'terms'    => 'team-member',
                'operator' => 'NOT IN',
            ];
            $query->set( 'tax_query', $tax_query );
            break;
        case "Clean50":
        case "Emerging Leader":
        default:
            $tax_query[] = [
                'taxonomy' => 'honouree-type',
                'field'    => 'slug',
                'terms'    => 'team',
                'operator' => 'NOT IN',
            ];
            $query->set( 'tax_query', $tax_query );
            break;
    }

    return $query;
}, 10, 1 );

/** THE_CONTENT */

/**
 * Prepend Team content to Team Member content
 */
add_filter( 'the_content', function ( $content ) {
    global $post;
    if ( 'honouree' !== $post->post_type ) {
        return $content;
    }

    $type = get_honouree_type( $post->ID );

    if ( 'team-member' !== $type->slug ) {
        return $content;
    }

    $team = get_team( $post->ID );

    return $team->post_content . $content;

}, 10, 1 );

/** TERM_LINK */

/**
 * Set Award term link to point to Honouree archive when in Honouree context
 */
add_filter( 'term_link', function ( $termlink, $term, $taxonomy ) {
    if ( 'award' !== $taxonomy ) {
        return $termlink;
    }

    $link = get_post_type_archive_link( 'honouree' );
    $link = add_query_arg( 'award', $term->slug, $link );

    return $link;
}, 10, 3 );

/**
 * Set Award Year term link to point to Honouree archive when in Honouree context
 */
add_filter( 'term_link', function ( $termlink, $term, $taxonomy ) {
    global $wp_query;

    if ( 'award-year' !== $taxonomy ) {
        return $termlink;
    }

    if ( 'honouree' !== $wp_query->get( 'post_type' ) ) {
        return $termlink;
    }

    $link = get_post_type_archive_link( 'honouree' );
    $link = add_query_arg( 'year', $term->slug, $link );

    return $link;

}, 10, 3 );

/**
 * Set Award Category term link to point to Honouree archive when in Honouree context
 */
add_filter( 'term_link', function ( $termlink, $term, $taxonomy ) {
    if ( 'award-category' !== $taxonomy ) {
        return $termlink;
    }

    $link = get_post_type_archive_link( 'honouree' );
    $link = add_query_arg( 'category', $term->slug, $link );

    return $link;

}, 10, 3 );

