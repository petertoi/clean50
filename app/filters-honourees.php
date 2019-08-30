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
