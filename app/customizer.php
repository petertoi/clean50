<?php
/**
 * Filename customizer.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Customizer;

include __DIR__ . '/customizer/panels.php';
include __DIR__ . '/customizer/sections.php';
include __DIR__ . '/customizer/settings.php';

/**
 * Remove the Custom CSS control from the Custom CSS section.
 */
add_action( 'customize_register', function ( $wp_customize ) {
    /**
     * @var \WP_Customize_Manager $wp_customize
     */

    $wp_customize->remove_control( 'custom_css' );

} );
