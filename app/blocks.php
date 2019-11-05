<?php
/**
 * Filename blocks.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox\Blocks;

add_filter( 'block_categories', function ( $categories, $post ) {
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
add_filter( 'allowed_block_types', function ( $allowed_block_types, $post ) {

    if ( (int) get_option( 'page_on_front' ) === (int) $post->ID ) {
        $allowed_block_types = [
            'acf/hero',
            'acf/honourees',
            'acf/projects',
            'acf/nominate',
            'acf/specials',
            'acf/articles',
            'acf/tweets',
        ];
    } else if ( 'page' === $post->post_type ) {
        $allowed_block_types = [
            'acf/page-section',
        ];
    } else {
//        $allowed_block_types = [];
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

        // Hero
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

    $slug = str_replace( 'acf/', '', $block['name'] );

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
