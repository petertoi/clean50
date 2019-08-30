<?php
/**
 * Filename shortcodes.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Shortcodes;

use Toi\ToiBox\Snippets;

add_shortcode( 'year_from_to', function ( $atts ) {
    // Setup defaults.
    $args = shortcode_atts(
        array(
            'from'      => date( 'Y' ),
            'separator' => '&ndash;',
        ),
        $atts
    );

    return esc_html( Snippets\year_from_to( $args['from'], $args['separator'] ) );
} );
