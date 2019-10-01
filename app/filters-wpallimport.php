<?php
/**
 * Filename filters-wpallimport.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Filters_WPAllImport;

//add_action( 'pmxi_saved_post', function ( $id ) {
//    $type             = get_field( 'type', $id, false );
//    $team_member_term = get_term( $type, 'honouree-type' );
//
//    if ( 'team-member' !== $team_member_term->slug ) {
//        return;
//    }
//
//    $team = get_field( 'related_team', $id, false );
//
//    if ( empty( $team ) ) {
//        return;
//    }
//
//    $related_members = get_field( 'related_team_members', $team, false );
//
//    if ( ! is_array( $related_members ) ) {
//        $related_members = [];
//    }
//
//    if ( ! in_array( $id, $related_members ) ) {
//        $related_members[] = $id;
//        update_field( 'related_team_members', $related_members, $team );
//    }
//
//}, 10, 1 );

//add_filter( 'wp_all_import_is_post_to_update', function ( $continue_import, $pid ) {
//    $type             = get_field( 'type', $pid, false );
//    $team_member_term = get_term( $type, 'honouree-type' );
//    if ( in_array( $team_member_term->slug, [ 'team', 'individual' ] ) ) {
//        $continue_import = false;
//    }
//
//    return $continue_import;
//}, 10, 2 );

/**
 * Run following an import
 */
add_action( 'pmxi_after_xml_import', function ( $import_id ) {

//    update_team_links_legacy();
    update_team_links_2020();

}, 10, 1 );

function update_team_links_legacy() {
    $query = new \WP_Query( [
        'post_type'      => 'honouree',
        'post_status'    => 'all',
        'tax_query'      => [
            'relation' => 'AND',
            [
                'taxonomy' => 'honouree-type',
                'field'    => 'slug',
                'terms'    => 'team-member',
            ],
            [
                'taxonomy' => 'award-year',
                'field'    => 'slug',
                'terms'    => '2020',
                'operator' => 'NOT IN'
            ],
        ],
        'posts_per_page' => - 1,
        'fields'         => 'ids',
    ] );

    $team_member_ids = $query->get_posts();

    foreach ( $team_member_ids as $team_member_id ) {
        $team_slug = get_post_meta( $team_member_id, '_original_team_slug', true );

        $team_query = new \WP_Query( [
            'name'           => $team_slug,
            'post_type'      => 'honouree',
            'post_status'    => 'all',
            'posts_per_page' => 1,
            'fields'         => 'ids',
//            'meta_key'       => '_original_team_slug',
//            'meta_value'     => $team_slug,
            'tax_query'      => [
                [
                    'taxonomy' => 'honouree-type',
                    'field'    => 'slug',
                    'terms'    => 'team',
                ],
            ],

        ] );

        if ( empty( $team_query->posts ) ) {
            continue;
        }

        $team_id = $team_query->posts[0];

        update_field( 'related_team', $team_id, $team_member_id );

        $related_team_members = get_field( 'related_team_members', $team_id, false );

        if ( ! is_array( $related_team_members ) ) {
            $related_team_members = [];
        }

        if ( ! in_array( $team_member_id, $related_team_members ) ) {
            $related_team_members[] = $team_member_id;
            update_field( 'related_team_members', $related_team_members, $team_id );
        }
    }

    return $team_member_ids;
}

function update_team_links_2020() {
    $query = new \WP_Query( [
        'post_type'      => 'honouree',
        'post_status'    => 'all',
        'tax_query'      => [
            'relation' => 'AND',
            [
                'taxonomy' => 'honouree-type',
                'field'    => 'slug',
                'terms'    => 'team-member',
            ],
            [
                'taxonomy' => 'award-year',
                'field'    => 'slug',
                'terms'    => '2020',
                'operator' => 'IN'
            ],
        ],
        'posts_per_page' => - 1,
        'fields'         => 'ids',
    ] );

    $team_member_ids = $query->get_posts();

    foreach ( $team_member_ids as $team_member_id ) {
        $team_slug = get_post_meta( $team_member_id, '_original_team_slug', true );

        $team_query = new \WP_Query( [
            'post_type'      => 'honouree',
            'post_status'    => 'all',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => '_original_team_slug',
            'meta_value'     => $team_slug,
            'tax_query'      => [
                [
                    'taxonomy' => 'honouree-type',
                    'field'    => 'slug',
                    'terms'    => 'team',
                ],
            ],

        ] );

        if ( empty( $team_query->posts ) ) {
            continue;
        }

        $team_id = $team_query->posts[0];

        update_field( 'related_team', $team_id, $team_member_id );

        $related_team_members = get_field( 'related_team_members', $team_id, false );

        if ( ! is_array( $related_team_members ) ) {
            $related_team_members = [];
        }

        if ( ! in_array( $team_member_id, $related_team_members ) ) {
            $related_team_members[] = $team_member_id;
            update_field( 'related_team_members', $related_team_members, $team_id );
        }
    }

    return $team_member_ids;
}
