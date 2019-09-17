<?php
/**
 * Filename snippets.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Snippets;

/**
 * Returns a formatted year to year string suitable for use in copyright statements etc.
 *
 * @param string $from
 * @param string $separator
 *
 * @return string Range of years if different, single year if the same.
 */
function year_from_to( $from, $separator = '&ndash;' ) {
    $to = date( 'Y' );

    $from_to = ( $from === $to )
        ? $from
        : "{$from}{$separator}{$to}";

    return $from_to;
}


function render_button( $button, $classes = [ 'btn', 'btn-primary' ], $echo = true ) {
    if ( 'page' === $button['link']['type'] ) {
        $target = sprintf( '#%s', $button['link']['target'] );
    } elseif ( 'internal' === $button['link']['type'] ) {
        if ( is_numeric( $button['link']['target'] ) ) {
            //TODO ACF bug with cloned fields yields raw value in certain cases
            $target = get_the_permalink( $button['link']['target'] );
        } else {
            $target = $button['link']['target'];
        }
    } else {
        $target = $button['link']['target'];
    }


    $button = sprintf( '<a class="%s" href="%s">%s</a>',
        esc_attr( implode( ' ', $classes ) ),
        esc_attr( $target ),
        wp_kses_post( $button['label'] )
    );

    if ( $echo ) {
        echo $button;
    }

    return $button;
}
