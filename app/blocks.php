<?php
/**
 * Filename blocks.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Blocks;

add_filter( 'block_categories_all', function ( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'clean50',
                'title' => __( 'Clean50', 'clean50' ),
                'icon'  => 'code-editor',
            ),
        )
    );
}, 10, 2 );

/**
 * Control which blocks appear where.
 */
add_filter( 'allowed_block_types_all', function ( $allowed_block_types, $block_editor_context ) {

    if ( (int) get_option( 'page_on_front' ) === (int) $block_editor_context->post->ID ) {
        $allowed_block_types = [
            'acf/hero',
            'acf/honourees',
            'acf/projects',
            'acf/nominate',
            'acf/specials',
            'acf/articles',
            'acf/tweets',
        ];
    } else if ( 'page' === $block_editor_context->post->post_type ) {
        $allowed_block_types = [
            'acf/page-section',
        ];
    } else {
        $allowed_block_types = [
            'core/paragraph',
            'core/image',
            'core/heading',
            'core/gallery',
            'core/list',
            'core/quote',
            'core/file',
            'core/audio',
            'core/video',
            'core/preformatted',
            'core/code',
            'core/freeform',
            'core/html',
            'core/pullquote',
            'core/table',
            'core/shortcode',
            'core/embed',
            'core-embed/twitter',
            'core-embed/youtube',
            'core-embed/facebook',
            'core-embed/instagram',
            'core-embed/soundcloud',
            'core-embed/vimeo',
            'core-embed/issuu',
            'core-embed/scribd',
            'core-embed/slideshare',
            'core-embed/ted',
        ];
    }

    return $allowed_block_types;
}, 10, 2 );

/**
 * Register ACF Gutenberg Blocks
 */
add_action( 'acf/init', function () {

    // check function exists
    if ( function_exists( 'acf_register_block' ) ) {

        $block_args = [
            'mode'            => 'auto',
            'category'        => 'clean50',
            'icon'            => 'editor-code',
            'render_callback' => __NAMESPACE__ . '\\acf_block_render_callback',
        ];

        // Page Section
        acf_register_block(
            [
                'name'        => 'page-section',
                'title'       => __( 'Page Section', '' ),
                'description' => __( 'Page Section', '' ),
                'keywords'    => [ 'text' ],
                'supports'    => [
                    'align'    => false,
                    'multiple' => true,
                ]
            ] + $block_args
        );

        // Hero
        acf_register_block(
            [
                'name'        => 'hero',
                'title'       => __( 'Hero', '' ),
                'description' => __( 'Hero', '' ),
                'keywords'    => [ 'text' ],
                'supports'    => [
                    'align'    => false,
                    'multiple' => false,
                ]
            ] + $block_args
        );

        // Honourees
        acf_register_block(
            [
                'name'        => 'honourees',
                'title'       => __( 'Honourees', '' ),
                'description' => __( 'Honourees', '' ),
                'keywords'    => [ 'text' ],
                'supports'    => [
                    'align'    => false,
                    'multiple' => false,
                ]
            ] + $block_args
        );

        // Nominate
        acf_register_block(
            [
                'name'        => 'nominate',
                'title'       => __( 'Nominate', '' ),
                'description' => __( 'Nominate', '' ),
                'keywords'    => [ 'text' ],
                'supports'    => [
                    'align'    => false,
                    'multiple' => true,
                ]
            ] + $block_args
        );

        // Projects
        acf_register_block(
            [
                'name'        => 'projects',
                'title'       => __( 'Projects', '' ),
                'description' => __( 'Projects', '' ),
                'keywords'    => [ 'text' ],
                'supports'    => [
                    'align'    => false,
                    'multiple' => false,
                ]
            ] + $block_args
        );

        // Special Features
        acf_register_block(
            [
                'name'        => 'specials',
                'title'       => __( 'Special Features', '' ),
                'description' => __( 'Special Features', '' ),
                'keywords'    => [ 'text' ],
                'supports'    => [
                    'align'    => false,
                    'multiple' => false,
                ]
            ] + $block_args
        );

        // Articles
        acf_register_block(
            [
                'name'        => 'articles',
                'title'       => __( 'Articles', '' ),
                'description' => __( 'Articles', '' ),
                'keywords'    => [ 'text' ],
                'supports'    => [
                    'align'    => false,
                    'multiple' => false,
                ]
            ] + $block_args
        );

        // Tweets
        acf_register_block(
            [
                'name'        => 'tweets',
                'title'       => __( 'Tweets', '' ),
                'description' => __( 'Tweets', '' ),
                'keywords'    => [ 'text' ],
                'supports'    => [
                    'align'    => false,
                    'multiple' => false,
                ]
            ] + $block_args
        );
    }
} );

function acf_block_render_callback( $block ) {

    $slug = str_replace( '/', '-', $block['name'] );

    if ( is_admin() ) {
        $template = get_theme_file_path( "/views/blocks/admin/{$slug}.php" );
        if ( file_exists( $template ) ) {
            include( $template );

            return;
        }
    }

    $template = get_theme_file_path( "/views/blocks/{$slug}.php" );
    if ( file_exists( $template ) ) {
        include( $template );
    }
}

add_filter( 'render_block', function ( $block_content, $block ) {

    if ( empty( $block['blockName'] ) ) {
        return $block_content;
    }

    if ( 0 !== strpos( $block['blockName'], 'acf/' ) ) {
        return $block_content;
    }

    $slug = str_replace( 'acf/', '', $block['blockName'] );

    $id = $block['attrs']['id'] ?? '';
    if ( ! empty( $block['anchor'] ) ) {
        $id = $block['anchor'];
    }

    // Create class attribute allowing for custom "className" and "align" values.
    $classes = [ 'block' ];
    if ( ! empty( $block['blockName'] ) ) {
        $classes[] = "block-{$slug}";
    }
    if ( ! empty( $block['className'] ) ) {
        $classes[] = sanitize_title_with_dashes( $block['className'] );
    }
    if ( ! empty( $block['align'] ) ) {
        $classes[] = "align{$block['align']}";
    }

    $block_content = sprintf(
        '<section id="%s" class="%s">%s</section>',
        esc_attr( $id ),
        esc_attr( implode( ' ', $classes ) ),
        $block_content
    );

    return $block_content;
}, 10, 2 );

/** Dequeue Block Styles */
add_action( 'wp_enqueue_scripts', function () {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
}, 99 );

/** Add lightbox to wp-block-image */
add_filter( 'render_block', function ( $block_content, $block ) {

    if( is_admin() ) {
        return $block_content;
    }

    if ( empty( $block['blockName'] ) ) {
        return $block_content;
    }

    if ( 'core/image' !== $block['blockName'] ) {
        return $block_content;
    }

    $id = uniqid();

    $block_content = str_replace( "a href", "a data-lightbox='$id' href", $block_content );

    return $block_content;
}, 10, 2 );

/** Add slick carousel to wp-block-gallery */
add_filter( 'render_block', function ( $block_content, $block ) {

    if( is_admin() ) {
        return $block_content;
    }

    if ( empty( $block['blockName'] ) ) {
        return $block_content;
    }

    if ( 'core/gallery' !== $block['blockName'] ) {
        return $block_content;
    }

    $slug = str_replace( '/', '-', $block['blockName'] );

    $template = get_theme_file_path( "/views/blocks/{$slug}.php" );
    if ( file_exists( $template ) ) {
        ob_start();
        include( $template );
        $block_content = ob_get_clean();
    }

    return $block_content;
}, 10, 2 );
