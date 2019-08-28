<?php
/**
 * Filename taxonomies.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

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
            'exclusive'        => false,
            'allow_hierarchy'  => false,
            'meta_box'         => 'simple',
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
        'award-category',
        'honouree',
        [
            'public'           => true,
            'show_ui'          => true,
            'hierarchical'     => false,
            'query_var'        => true,
            'exclusive'        => true,
            'allow_hierarchy'  => false,
            'meta_box'         => 'dropdown',
            'dashboard_glance' => false,
            'checked_ontop'    => true,
            'admin_cols'       => null,
            'required'         => true,
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
        ['honouree', 'project'],
        [
            'public'           => true,
            'show_ui'          => true,
            'hierarchical'     => false,
            'query_var'        => true,
            'exclusive'        => true,
            'allow_hierarchy'  => false,
            'meta_box'         => 'dropdown',
            'dashboard_glance' => false,
            'checked_ontop'    => true,
            'admin_cols'       => null,
            'required'         => true,
        ]
    );
} );

add_action( 'after_switch_theme', function () {
    if ( false === get_term_by( 'slug', 'individual', 'honouree-type' ) ) {
        wp_insert_term( 'Individual', 'honouree-type' );
    }
    if ( false === get_term_by( 'slug', 'team', 'honouree-type' ) ) {
        wp_insert_term( 'Team', 'honouree-type' );
    }
    if ( false === get_term_by( 'slug', 'team-member', 'honouree-type' ) ) {
        wp_insert_term( 'Team Member', 'honouree-type' );
    }
} );

