/**
 * External dependencies
 */
import 'bootstrap';
import 'select2';

/**
 * Internal dependencies
 */
import hello from './modules/hello';

/* global $ */
$( document ).ready( () => {
	hello.init();

	$( 'select' ).select2( {
		theme: 'bootstrap4',
	} );
} );
