<?php
/**
 * Filename taxonomies.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Taxonomies;

add_action( 'init', function () {
    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_taxonomy(
        'honouree-type',
        'honouree',
        [
            'public'           => false,
            'show_ui'          => false,
            'hierarchical'     => true,
            'query_var'        => true,
            'exclusive'        => true,
            'allow_hierarchy'  => false,
            'meta_box'         => false,
            'dashboard_glance' => false,
            'checked_ontop'    => false,
            'admin_cols'       => null,
            'required'         => true,
        ]
    );

    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_taxonomy(
        'award',
        'honouree',
        [
            'public'           => true,
            'show_ui'          => true,
            'hierarchical'     => true,
            'query_var'        => true,
            'exclusive'        => true,
            'allow_hierarchy'  => false,
            'meta_box'         => 'radio',
            'dashboard_glance' => false,
            'checked_ontop'    => false,
            'admin_cols'       => null,
            'required'         => true,
        ]
    );

    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_taxonomy(
        'individual-award',
        'honouree',
        [
            'public'           => false,
            'show_ui'          => true,
            'hierarchical'     => true,
            'query_var'        => false,
            'exclusive'        => true,
            'allow_hierarchy'  => false,
            'meta_box'         => 'radio',
            'dashboard_glance' => false,
            'checked_ontop'    => false,
            'required'         => true,
            # Add a custom column to the admin screen:
            'admin_cols'       => [
                'sponsor' => [
                    'title'    => 'Sponsor',
                    'function' => function ( $term_id ) {
                        $sponsor = get_term_meta( $term_id, 'sponsor', true );
                        if ( $sponsor ) {
                            echo get_the_title( $sponsor );
                        }
                    },
                ],
            ],
        ]
    );

    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_taxonomy(
        'award-category',
        'honouree',
        [
            'public'           => true,
            'show_ui'          => true,
            'hierarchical'     => true,
            'query_var'        => true,
            'exclusive'        => true,
            'allow_hierarchy'  => false,
            'meta_box'         => 'radio',
            'dashboard_glance' => false,
            'checked_ontop'    => true,
            'required'         => true,
            # Add a custom column to the admin screen:
            'admin_cols'       => [
                'sponsor' => [
                    'title'    => 'Sponsor',
                    'function' => function ( $term_id ) {
                        $sponsor_group = get_field( 'sponsors', get_term( $term_id, 'award-category' ) );
                        if ( $sponsor_group && ! empty( $sponsor_group['sponsors'] ) ) {
                            foreach ( $sponsor_group['sponsors'] as $sponsor ) {
                                echo get_the_title( $sponsor ) . '<br>';
                            }
                        }
                    },
                ],
            ],
        ],
        [
            'plural' => __( 'Award Categories', '' )
        ]
    );

    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_taxonomy(
        'award-year',
        [ 'honouree', 'project' ],
        [
            'public'           => true,
            'show_ui'          => true,
            'hierarchical'     => true,
            'query_var'        => true,
            'exclusive'        => true,
            'allow_hierarchy'  => false,
            'meta_box'         => 'simple',
            'dashboard_glance' => false,
            'checked_ontop'    => true,
            'admin_cols'       => null,
            'required'         => true,
        ]
    );


    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_taxonomy(
        'project-award',
        [ 'project' ],
        [
            'public'           => false,
            'show_ui'          => true,
            'hierarchical'     => true,
            'query_var'        => true,
            'exclusive'        => false,
            'allow_hierarchy'  => false,
            'meta_box'         => 'simple',
            'dashboard_glance' => false,
            'checked_ontop'    => true,
            'required'         => true,
            # Add a custom column to the admin screen:
            'admin_cols'       => [
                'sponsor' => [
                    'title'    => 'Sponsor',
                    'function' => function ( $term_id ) {
                        $sponsor = get_term_meta( $term_id, 'sponsor', true );
                        if ( $sponsor ) {
                            echo get_the_title( $sponsor );
                        }
                    },
                ],
            ],
        ]
    );

    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_taxonomy(
        'sponsor-tier',
        [ 'sponsor' ],
        [
            'public'           => true,
            'show_ui'          => true,
            'hierarchical'     => true,
            'query_var'        => true,
            'exclusive'        => false,
            'allow_hierarchy'  => false,
            'meta_box'         => 'simple',
            'dashboard_glance' => false,
            'checked_ontop'    => true,
            'required'         => true,
        ]
    );

} );

add_action( 'after_switch_theme', function () {

    /** Honouree Types */
    if ( false === get_term_by( 'slug', 'individual', 'honouree-type' ) ) {
        wp_insert_term( 'Individual', 'honouree-type' );
    }
    if ( false === get_term_by( 'slug', 'team', 'honouree-type' ) ) {
        wp_insert_term( 'Team', 'honouree-type' );
    }
    if ( false === get_term_by( 'slug', 'team-member', 'honouree-type' ) ) {
        wp_insert_term( 'Team Member', 'honouree-type' );
    }

    /** Project Awards */
    if ( false === get_term_by( 'slug', 'top-project', 'project-award' ) ) {
        wp_insert_term( 'Top Project', 'project-award' );
    }

    /** Sponsor Tiers */
    if ( false === get_term_by( 'slug', 'tier-1', 'sponsor-tier' ) ) {
        wp_insert_term( 'Tier 1', 'sponsor-tier' );
    }
    if ( false === get_term_by( 'slug', 'tier-2', 'sponsor-tier' ) ) {
        wp_insert_term( 'Tier 2', 'sponsor-tier' );
    }
    if ( false === get_term_by( 'slug', 'tier-3', 'sponsor-tier' ) ) {
        wp_insert_term( 'Tier 3', 'sponsor-tier' );
    }
    if ( false === get_term_by( 'slug', 'tier-4', 'sponsor-tier' ) ) {
        wp_insert_term( 'Tier 4', 'sponsor-tier' );
    }
    if ( false === get_term_by( 'slug', 'summit', 'sponsor-tier' ) ) {
        wp_insert_term( 'Summit', 'sponsor-tier' );
    }

} );

