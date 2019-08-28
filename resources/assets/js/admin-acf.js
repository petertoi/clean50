/* global acf, Clean50 */

const typeField = 'field_5d535704048c2';
const conditionalFieldGroups = {
	team: [ 'field_5d65478971f6f' ],
	'team-member': [ 'field_5d6546cc71f6e' ],
};

function hideFields() {
	Object.keys( conditionalFieldGroups ).forEach( ( slug ) => {
		conditionalFieldGroups[ slug ].forEach( ( key ) => acf.getField( key ).hide() );
	} );
}

function showFieldsForSlug( slug ) {
	const fieldKeys = conditionalFieldGroups[ slug ] || [];
	fieldKeys.forEach( ( fieldKey ) => acf.getField( fieldKey ).show() );
}

function getSlugByTermId( termId ) {
	const filtered = Clean50.honouree_types.filter( ( term ) => parseInt( term.term_id ) === parseInt( termId ) );
	return filtered[ 0 ].slug || '';
}

acf.addAction( `ready_field/key=${ typeField }`, ( field ) => {
	hideFields();
	showFieldsForSlug( getSlugByTermId( field.val() ) );

	field.on( 'change', function( e ) {
		hideFields();
		showFieldsForSlug( getSlugByTermId( e.target.value ) );
	} );
} );
