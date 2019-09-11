<?php
/**
 * Filename templates.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Templates;

function get_main() {
    return Template_Wrapper::$main_template;
}

function get_sidebar( $sidebar = 'partials/sidebar.php' ) {
    return new Template_Wrapper( $sidebar );
}

function has_sidebar() {
    static $display;

    isset( $display ) || $display = ! in_array( true, [
        // The sidebar will NOT be displayed if ANY of the following return true.
        // @link https://codex.wordpress.org/Conditional_Tags
        is_404(),
        is_front_page(),
        is_page_template( 'template-custom.php' ),
    ] );

    return $display;
}

/**
 * Theme wrapper
 *
 * @link https://roots.io/sage/docs/theme-wrapper/
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */
class Template_Wrapper {
    // Stores the full path to the main template file
    public static $main_template;

    // Basename of template file
    public $slug;

    // Array of templates
    public $templates;

    // Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
    public static $base;

    public function __construct( $template = 'base.php' ) {
        $this->slug = basename( $template, '.php' );
        $templates  = [ $template ];

        if ( self::$base ) {
            $str = substr( $template, 0, - 4 );
            array_unshift( $templates, sprintf( $str . '-%s.php', self::$base ) );
        }

        $this->templates = [];
        foreach ( $templates as $template ) {
            $this->templates = [
                "views/layouts/$template",
                "views/$template",
                $template
            ];
        }
    }

    public function __toString() {
        return locate_template( $this->templates );
    }

    public static function wrap( $main ) {
        // Check for other filters returning null
        if ( ! is_string( $main ) ) {
            return $main;
        }

        self::$main_template = $main;
        self::$base          = basename( self::$main_template, '.php' );

        if ( self::$base === 'index' ) {
            self::$base = false;
        }

        return new Template_Wrapper();
    }
}

add_filter( 'template_include', [ __NAMESPACE__ . '\\Template_Wrapper', 'wrap' ] );

array_map( function ( $type ) {
    add_filter( "{$type}_template_hierarchy", function ( $templates ) {
        $new_templates = [];
        foreach ( $templates as $template ) {
            $new_templates[] = "views/$template";
            $new_templates[] = $template;
        }

        return $new_templates;
    }, 109, 1 );
}, [
    'index',
    '404',
    'archive',
    'author',
    'category',
    'tag',
    'taxonomy',
    'date',
    'home',
    'frontpage',
    'privacypolicy',
    'page',
    'paged',
    'search',
    'single',
    'embed',
    'singular',
    'attachment',
] );

add_filter( 'get_search_form', function ( $html ) {
    $search_form_template = locate_template( 'views/searchform.php' );
    if ( '' !== $search_form_template ) {
        ob_start();
        require( $search_form_template );
        $html = ob_get_clean();
    }

    return $html;
} );
