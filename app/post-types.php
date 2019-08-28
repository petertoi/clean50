<?php
/**
 * Filename post-types.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

add_action( 'init', function () {
    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_post_type(
        'honouree',
        [
            'menu_icon'      => 'dashicons-businessperson',
            'show_in_rest'   => true,
            'featured_image' => 'Headshot',
            'admin_cols'     => [
                'featured_image' => [
                    'title'          => 'Headshot',
                    'featured_image' => 'thumbnail'
                ],
                'honouree-type'  => [
                    'title'    => 'Type',
                    'taxonomy' => 'honouree-type',
                ],
                'award'          => [
                    'title'    => 'Award',
                    'taxonomy' => 'award',
                ],
                'award-year'     => [
                    'title'    => 'Year',
                    'taxonomy' => 'award-year',
                ],
                'award-category' => [
                    'title'    => 'Category',
                    'taxonomy' => 'award-category',
                ],
            ],
        ]
    );

    register_extended_post_type(
        'project',
        [
            'menu_icon'      => 'dashicons-portfolio',
            'show_in_rest'   => true,
        ]
    );

    /**
     * @see https://github.com/johnbillion/extended-cpts
     */
    register_extended_post_type(
        'sponsor',
        [
            'menu_icon'      => 'dashicons-awards',
            'show_in_rest'   => true,
            'featured_image' => 'Sponsor Logo',
        ]
    );
} );
