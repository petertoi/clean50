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

	const $tooltips = $( '[data-toggle="tooltip"]' );
	$tooltips.tooltip();

	const $select = $( 'select' );

	$select.select2( {
		theme: 'bootstrap4',
	} );

	$select.on( 'select2:select', function() {
		const $form = $( this ).parents( 'form:first' );
		$form.find( 'select' ).prop( 'readonly', true );
		$form.submit();
	} );

	const $navSectionItems = $( '.js-nav-section a' );
	$navSectionItems.on( 'click', function() {
		const target = '#' + this.href.split( '#' )[ 1 ];
		$( target ).collapse( 'show' );
		$( 'html, body' ).animate( {
			scrollTop: $( $.attr( this, 'href' ) ).offset().top - 100,
		}, 500 );
	} );
} );
