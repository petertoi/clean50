<?php
/**
 * Filename filters-honourees.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Filters_Honourees;

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

/**
 * Filter query for Honourees based on Award to limit
 * Honouree Type returned.
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
        return $query;
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
        return $query;
    }

    $award = get_term( $award_id, 'award' );

    switch ( $award->name ) {
        case "Clean50":
            $tax_query[] = [
                'taxonomy' => 'honouree-type',
                'field'    => 'slug',
                'terms'    => 'team',
                'operator' => 'NOT IN',
            ];
            $query->set( 'tax_query', $tax_query );
            break;
        case "Clean16":
            $tax_query[] = [
                'taxonomy' => 'honouree-type',
                'field'    => 'slug',
                'terms'    => 'team-member',
                'operator' => 'NOT IN',
            ];
            $query->set( 'tax_query', $tax_query );
            break;
        case "Emerging Leader":
        default:
            break;
    }

    return $query;
}, 10, 1 );

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

add_filter( 'term_link', function ( $termlink, $term, $taxonomy ) {
    if ( 'award' !== $taxonomy ) {
        return $termlink;
    }

    $link = get_post_type_archive_link( 'honouree' );
    $link = add_query_arg( 'award', $term->slug, $link );

    return $link;
}, 10, 3 );

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

add_filter( 'term_link', function ( $termlink, $term, $taxonomy ) {
    if ( 'award-category' !== $taxonomy ) {
        return $termlink;
    }

    $link = get_post_type_archive_link( 'honouree' );
    $link = add_query_arg( 'category', $term->slug, $link );

    return $link;

}, 10, 3 );

add_filter( 'pre_get_posts', function ( $query ) {

    /** @var $query \WP_Query */
    if ( $query->is_admin ) {
        return $query;
    }

    if ( ! $query->is_post_type_archive( 'honouree' ) ) {
        return $query;
    }
    $year = $query->get( 'year' );
    $query->set( 'year', '' );

    $query->set( 'award-year', $year );

    return $query;

}, 10, 1 );

add_filter( 'pre_get_posts', function ( $query ) {

    /** @var $query \WP_Query */
    if ( $query->is_admin ) {
        return $query;
    }

    if ( ! $query->is_post_type_archive( 'honouree' ) ) {
        return $query;
    }

    $category = filter_input( INPUT_GET, 'category', FILTER_SANITIZE_STRING );
    if ( ! empty( $category ) ) {
        $query->set( 'award-category', $category );
    }

    return $query;

}, 10, 1 );
