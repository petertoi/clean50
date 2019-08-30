/* global acf, Clean50 */

const typeField = 'field_5d535704048c2';

const fieldGroupsToHide = {
	individual: [
		'field_5d6546cc71f6e', // related team
		'field_5d65478971f6f', // related team members
	],
	team: [
		'field_5d6546cc71f6e', // related team
		'field_5d656ce8b5867', // full name
		'field_5d656cddb5865', // first name
		'field_5d656ce3b5866', // last name
		'field_5d656cedb5868', // title
		'field_5d683cdf29e3d', // organization
	],
	'team-member': [
		'field_5d65478971f6f', // related team members
	],
};

function showFields() {
	Object.keys( fieldGroupsToHide ).forEach( ( slug ) => {
		fieldGroupsToHide[ slug ].forEach( ( key ) => acf.getField( key ).show() );
	} );
}

function hideFields( slug ) {
	const fieldKeys = fieldGroupsToHide[ slug ] || [];
	fieldKeys.forEach( ( fieldKey ) => acf.getField( fieldKey ).hide() );
}

function getSlugByTermId( termId ) {
	const filtered = Clean50.honouree_types.filter( ( term ) => parseInt( term.term_id ) === parseInt( termId ) );
	return filtered[ 0 ].slug || '';
}

acf.addAction( `ready_field/key=${ typeField }`, ( field ) => {
	showFields();
	hideFields( getSlugByTermId( field.val() ) );

	field.on( 'change', function( e ) {
		showFields();
		hideFields( getSlugByTermId( e.target.value ) );
	} );
} );
