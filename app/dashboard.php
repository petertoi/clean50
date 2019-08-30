<?php
/**
 * Filename dashboard.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Dashboard;

/**
 * Enable/Disable Core Dashboard Meta Boxes.
 */
add_action( 'admin_init', function () {
    // Depreciated / Legacy
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );

    // WordPress Events & News
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );

    // Quick Press
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );

    // Recent Drafts
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );

    // Recent Comments
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

    // Activity: Recently Published / Recent Comments
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );

    // At a Glance: Number of Posts, Pages, Comments, Etc.
    // remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
} );

/**
 * Disallow File Mods.
 */
add_filter( 'file_mod_allowed', function ( $disallow, $context ) {
    switch ( $context ) {
        case 'capability_edit_themes':
            $disallow = false;
            break;
        case 'capability_update_core':
        case 'can_install_language_pack':
        case 'automatic_updater':
        case 'download_language_pack':
        default:
            break;
    }

    return $disallow;
}, 10, 2 );

/**
 * Customize Footer Text.
 */
add_filter( 'admin_footer_text', function ( $html ) {
    return '<span id="footer-thankyou">Made in 🇨🇦 by <a href="https://petertoi.com" target="_blank">Peter Toi</a>.</span>';
} );
