import $ from 'jquery';
import submit from './module/submit';

$( document ).on( 'click', '[data-action="back"]', ( event ) =>
	$( event.currentTarget )
		.parent()
		.find( 'input[type="hidden"]' )
		.attr( 'value', 'back' )
);

$( document ).on( 'click', '.smf-action [type="submit"]', ( event ) => {
	$( event.currentTarget )
		.find( '.smf-sending' )
		.attr( 'aria-hidden', 'false' );
} );

$( '.snow-monkey-form' ).each( ( i, e ) => {
	const form = $( e );
	form.on( 'submit', submit );
} );

$( document ).on( 'change keyup', '[data-invalid="1"]', ( event ) => {
	$( event.currentTarget ).removeAttr( 'data-invalid' );
	$( event.currentTarget )
		.find( '[data-invalid="1"]' )
		.removeAttr( 'data-invalid' );
	$( event.currentTarget )
		.closest( '.smf-placeholder' )
		.find( '.smf-error-messages' )
		.remove();
} );
