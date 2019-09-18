/**
 * Theme Export Script
 *
 * Exports the production-ready theme with only the files and folders needed for
 * uploading to a site or zipping. Edit the `files` or `folders` variables if
 * you need to change something.
 */

// Import required packages.
const mix = require( 'laravel-mix' );
const rimraf = require( 'rimraf' );
const fs = require( 'fs' );

// Folder name to export the files to.
const exportPath = 'clean50';

// Theme root-level files to include.
const files = [
	'changelog.md',
	'license.md',
	'readme.md',
];

// Folders to include.
const folders = [
	'app',
	'public',
	'resources',
	'vendor',
];

// Delete the previous export to start clean.
rimraf.sync( exportPath );

// Loop through the root files and copy them over.
files.forEach( ( file ) => {
	if ( fs.existsSync( file ) ) {
		mix.copy( file, `${ exportPath }/${ file }` );
	}
} );

// Loop through the folders and copy them over.
folders.forEach( ( folder ) => {
	if ( fs.existsSync( folder ) ) {
		mix.copyDirectory( folder, `${ exportPath }/${ folder }` );
	}
} );

// Delete the `vendor/bin` and `vendor/composer/installers` folder, which can
// get left over, even in production. Mix will also create an additional
// `mix-manifest.json` file in the root, which we don't need.
mix.then( () => {
	const filesToDelete = [
		'mix-manifest.json',
		`${ exportPath }/resources/acf`,
		`${ exportPath }/resources/assets`,
		`${ exportPath }/resources/lang`,
		`${ exportPath }/vendor/bin`,
		`${ exportPath }/vendor/composer/installers`,
	];

	filesToDelete.forEach( ( file ) => {
		if ( fs.existsSync( file ) ) {
			rimraf.sync( file );
		}
	} );
} );
