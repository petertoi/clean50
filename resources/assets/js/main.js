/**
 * External dependencies
 */
import 'bootstrap';
import 'select2';
// import SmoothScroll from 'smooth-scroll/dist/smooth-scroll';

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

	// new SmoothScroll( 'a[href*="#"]' );
} );
