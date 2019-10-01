<?php
/**
 * Filename post-types.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Post_Types;

add_action( 'init', function () {
    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_post_type(
        'honouree',
        [
            'menu_icon'      => 'dashicons-awards',
            'show_in_rest'   => true,
            'featured_image' => 'Headshot',
            'admin_cols'     => [
                'featured-image'    => [
                    'title'          => 'Headshot',
                    'featured_image' => 'thumbnail',
                    'width'          => 80,
                    'height'         => 80,
                ],
                'honouree-type'     => [
                    'title'    => 'Type',
                    'taxonomy' => 'honouree-type',
                ],
                'related'           => [
                    'title'    => 'Related',
                    'function' => function () {
                        global $post;
                        $type = get_field( 'type', $post );
                        $term = get_term( $type );
                        if ( 'team-member' === $term->slug ) {
                            $related_team = get_field( 'related_team', $post );
                            if ( ! empty( $related_team ) ) {
                                printf( '<a href="%s">%s</a>', get_edit_post_link( $related_team ), get_the_title( $related_team ) );
                            }
                        } else if ( 'team' === $term->slug ) {
                            $related_team_members = get_field( 'related_team_members', $post );
                            if ( ! empty( $related_team_members ) ) {
                                foreach ( $related_team_members as $related_team_member ) {
                                    printf( '<a href="%s">%s</a><br>', get_edit_post_link( $related_team_member ), get_the_title( $related_team_member ) );
                                }
                            }
                        }
                    },
                ],
                'award'             => [
                    'title'    => 'Award',
                    'taxonomy' => 'award',
                ],
                'award-year'        => [
                    'title'    => 'Year',
                    'taxonomy' => 'award-year',
                ],
                'award-category'    => [
                    'title'    => 'Category',
                    'taxonomy' => 'award-category',
                ],
                'meta_title'        => [
                    'title'    => 'Title',
                    'meta_key' => 'title',
                ],
                'meta_organization' => [
                    'title'    => 'Organization',
                    'meta_key' => 'organization',
                ],
            ],
            'admin_filters'  => [
                'honouree-type'  => [
                    'title'    => 'All Types',
                    'taxonomy' => 'honouree-type'
                ],
                'award'          => [
                    'title'    => 'All Awards',
                    'taxonomy' => 'award',
                ],
                'award-year'     => [
                    'title'    => 'All Years',
                    'taxonomy' => 'award-year',
                ],
                'award-category' => [
                    'title'    => 'All Categories',
                    'taxonomy' => 'award-category',
                ],
            ],
        ]
    );

    register_extended_post_type(
        'project',
        [
            'menu_icon'     => 'dashicons-portfolio',
            'show_in_rest'  => true,
            'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'admin_cols'    => [
                'featured-image' => [
                    'title'          => 'Featured Image',
                    'featured_image' => 'thumbnail',
                    'width'          => 80,
                    'height'         => 80,
                ],
                'leads'          => [
                    'title'    => 'Project Leads',
                    'function' => function () {
                        global $post;
                        $leads = get_field( 'leads', $post ) ?: [];
                        $items = [];
                        foreach ( $leads as $lead ) {
                            $items[] = sprintf( '<li>%s</li>', $lead['name'] );
                        }
                        printf( '<ul>%s</ul>', implode( '', $items ) );
                    },
                ],
                'award-year'     => [
                    'title'    => 'Year',
                    'taxonomy' => 'award-year',
                ],
                'project-award'  => [
                    'title'    => 'Project Awards',
                    'taxonomy' => 'project-award',
                ],
            ],
            'admin_filters' => [
                'award-year' => [
                    'title'    => 'All Years',
                    'taxonomy' => 'award-year',
                ],
            ],
        ]
    );

    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_post_type(
        'sponsor',
        [
            'menu_icon'      => 'dashicons-heart',
            'show_in_rest'   => true,
            'featured_image' => 'Sponsor Logo',
            'admin_cols'     => [
                'featured-image' => [
                    'title'          => 'Logo',
                    'featured_image' => 'sponsor-carousel',
//                    'width'          => 60,
//                    'height'         => 300,
                ],
                'sponsor-tier'   => [
                    'title'    => 'Tier',
                    'taxonomy' => 'sponsor-tier',
                ],
            ],
        ]
    );
} );
