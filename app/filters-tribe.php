<?php
/**
 * Filename filters-tribe.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Filters_Tribe;

add_filter( 'tribe_tickets_get_ticket_max_purchase', function ( $available_at_a_time, $ticket, $event, $ticket_id ) {
    if ( in_array( $ticket_id, [ 12440, 12449 ], true ) ) {
        $available_at_a_time = 1;
    }

    return $available_at_a_time;
}, 10, 4 );
