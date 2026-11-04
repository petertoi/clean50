/**
 * External dependencies
 */
import 'bootstrap';
import 'select2';
import 'lightbox2';
import 'slick-carousel';

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
		theme: 'bootstrap',
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

	$( '.js-block-gallery' ).slick( {
		dots: true,
		infinite: true,
		speed: 300,
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
				},
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
				},
			},
		],
	} );
} );

