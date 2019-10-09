<?php
/**
 * Ensure compatible version of PHP is used
 */

use Toi\ToiBox\Sprite;
use function Toi\ToiBox\Snippets\get_award_year;

if ( version_compare( '7.1', phpversion(), '>=' ) ) {
    wp_die( __( 'You must be using PHP 7.1 or greater.', '' ), __( 'Invalid PHP version', '' ) );
}

/**
 * Ensure compatible version of WordPress is used
 */
if ( version_compare( '4.7.0', get_bloginfo( 'version' ), '>=' ) ) {
    wp_die( __( 'You must be using WordPress 4.7.0 or greater.', '' ), __( 'Invalid WordPress version', '' ) );
}

/**
 * Check for Composer dependencies
 */
if ( ! file_exists( get_template_directory() . '/../vendor/autoload.php' ) ) {
    wp_die( __( 'Please run composer to install dependencies.', '' ), __( 'Missing Dependencies', '' ) );
}

/**
 * Autoload Composer dependencies
 */
require_once get_template_directory() . '/../vendor/autoload.php';

/**
 * Explicitly load theme files from the /app directory
 */
array_map( function ( $file ) {
    if ( ! $filepath = locate_template( "../app/${file}" ) ) {
        trigger_error( sprintf( __( 'Error locating %s for inclusion', '' ), $file ), E_USER_ERROR );
    }

    require_once $filepath;

}, [
    'assets.php',
    'blocks.php',
    'clean-up.php',
    'custom-fields.php',
    'customizer.php',
    'dashboard.php',
    'filters-core.php',
    'filters-honouree.php',
    'filters-project.php',
    'filters-sponsor.php',
    'filters-theme-mod.php',
    'filters-wpallimport.php',
    'post-types.php',
    'setup.php',
    'shortcodes.php',
    'snippets.php',
    'taxonomies.php',
    'templates.php',
    'widgets.php',
    'wp-sprite/class-sprite.php',
] );
