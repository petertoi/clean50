<?php
/**
 * Filename filters-sponsor.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Snippets\get_sponsor_tier;

/**
 * Set _award meta to term->slug on save to aid with ordering clauses
 */
add_action( 'save_post_sponsor', function ( $post_ID, $post, $update ) {
    $sponsor_tier = get_sponsor_tier( $post_ID );
    if ( false === $sponsor_tier ) {
        delete_post_meta( $post_ID, '_sponsor-tier' );
    } else {
        update_post_meta( $post_ID, '_sponsor-tier', $sponsor_tier->slug );
    }
}, 10, 3 );
