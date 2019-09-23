<?php
/**
 * Filename class-sprite.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace Toi\ToiBox;

add_action( 'init', function () {
    register_post_type(
        'sprite',
        [
            'public' => 'false',
        ]
    );

    register_taxonomy(
        'sprite2post',
        [ 'sprite', 'post' ],
        [
            'public' => false,
        ]
    );

    register_taxonomy_for_object_type( 'sprite2post', 'sprite' );
} );

add_action( 'save_post', function ( $post_ID, $post, $update ) {
    $sprite2post_terms = wp_get_post_terms( $post_ID, 'sprite2post', [ 'hide_empty' => false ] );

    if ( empty( $sprite2post_terms ) ) {
        return;
    }

    $sprites = [];
    foreach ( $sprite2post_terms as $term ) {
        $sprites = array_merge(
            get_posts( [
                'post_type'      => 'sprite',
                'posts_per_page' => - 1,
                'tax_query'      => [
                    [
                        'taxonomy' => 'sprite2post',
                        'field'    => 'id',
                        'terms'    => $term->term_id,
                    ],
                ],
            ] ),
            $sprites
        );
    }

    $sprites = array_unique( $sprites );

    foreach ( $sprites as $sprite ) {
        // Delete file
        $data = json_decode( $sprite->post_content, true );
        unlink( $data['path'] );

        // Delete sprite post
        wp_delete_post( $sprite->ID );
    }

}, 10, 3 );

/**
 * Class Sprite
 *
 * Summary
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 * @version
 */
class Sprite {

    const SPRITE_DIR = 'sprites';

    public $name;

    public $post_ids;

    public $size;

    public $url;

    public $path;

    public $width;

    public $height;

    public $map;

    public function __construct( $name, $post_ids, $size = 'thumbnail' ) {

        // Normalize post IDs.
        sort( $post_ids, SORT_NUMERIC );

        $query = new \WP_Query( [
            'post_type'      => 'sprite',
            'posts_per_page' => 1,
            'name'           => $this->get_name( $name, $size ),
        ] );

        if ( $query->have_posts() ) {
            $existing = $query->posts[0];

            $data = json_decode( $existing->post_content, true );
            if ( $post_ids === $data['post_ids'] && $size === $data['size'] ) {
                $this->name     = $existing->post_title;
                $this->post_ids = $data['post_ids'];
                $this->size     = $data['size'];
                $this->url      = $data['url'];
                $this->path     = $data['path'];
                $this->width    = $data['width'];
                $this->height   = $data['height'];
                $this->map      = $data['map'];

                return $this;
            }
        }

        $this->name     = $name;
        $this->post_ids = $post_ids;
        $this->size     = $size;

        $this->create( $name, $post_ids, $size );

        return $this;
    }

    /**
     * Load a Sprite from the Database
     *
     * @param $name string Sprite name
     */
    public function load() {

        $query = new \WP_Query( [
            'post_type'      => 'sprite',
            'posts_per_page' => 1,
            'name'           => $this->name,
        ] );

        if ( ! $query->have_posts() ) {
            return false;
        }

        $this->name = $query->posts[0]->post_title;

        $data = json_decode( $query->posts[0]->post_content, true );

        $this->url    = $data['url'];
        $this->path   = $data['path'];
        $this->width  = $data['width'];
        $this->height = $data['height'];
        $this->map    = $data['map'];

        return $this;
    }

    /**
     * Create a Sprite
     *
     * @param $name        string Sprite name
     * @param $ids         array Attachment IDs
     * @param $size        string Image size
     */
    public function create( $name, $post_ids, $size ) {

        global $wpdb;

        $posts_thumbs = $wpdb->get_results(
            sprintf(
                "SELECT post_id, meta_value as attachment_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_thumbnail_id' AND post_id IN (%s)",
                implode( ',', array_map( 'absint', $post_ids ) )
            )
        );

        /**
         * Populate the Sprite & Sprite Map
         */
        $offset = 0;

        $upload_dir = wp_get_upload_dir();

        $imagick = new \Imagick();

        $this->map = [];

        foreach ( $posts_thumbs as $post_thumb ) {
            $metadata = wp_get_attachment_metadata( $post_thumb->attachment_id );
            if ( ! isset( $metadata['sizes'][ $size ] ) ) {
                continue;
                //TODO handle missing image size better
            }

            try {
                $image_path = implode(
                    '/',
                    [
                        $upload_dir['basedir'],
                        dirname( $metadata['file'] ),
                        $metadata['sizes'][ $size ]['file']
                    ]
                );

                $imagick->readImage( $image_path );

                $this->map[ $post_thumb->post_id ] = [
                    'post_id'       => $post_thumb->post_id,
                    'attachment_id' => $post_thumb->attachment_id,
                    'filepath'      => $image_path,
                    'width'         => $metadata['sizes'][ $size ]['width'],
                    'height'        => $metadata['sizes'][ $size ]['height'],
                    'mime-type'     => $metadata['sizes'][ $size ]['mime-type'],
                    'offset'        => $offset,
                ];
                $offset                            += $metadata['sizes'][ $size ]['height'];

            } catch ( \ImagickException $e ) {
                return new \WP_Error( 'sprite-error', 'Imagick exception', $e );
            }
        }

        $imagick->resetIterator();
        $imagick_sprite = $imagick->appendImages( true );

        $filename = $this->get_image_path();

        wp_mkdir_p( dirname( $filename ) );

        $imagick_sprite->writeImage( $filename );

        $this->name     = $name;
        $this->post_ids = $post_ids;
        $this->size     = $size;
        $this->path     = $this->get_image_path( $name, $size );
        $this->url      = $this->get_image_url( $name, $size );
        $this->width    = $imagick_sprite->getImageWidth();
        $this->height   = $imagick_sprite->getImageHeight();

        $data = [
            'size'   => $size,
            'url'    => $this->url,
            'path'   => $this->path,
            'width'  => $this->width,
            'height' => $this->height,
            'map'    => $this->map,
        ];

        $sprite_id = wp_insert_post( [
            'ID'           => $this->exists() ?: 0,
            'post_title'   => $this->get_name( $name, $size ),
            'post_content' => wp_json_encode( $data, JSON_PRETTY_PRINT ),
            'post_status'  => 'publish',
            'post_type'    => 'sprite',
        ] );

//        wp_set_object_terms( $sprite_id, $this->get_name(), 'sprite2post', false );
//        foreach ( $posts_thumbs as $post_thumb ) {
//            wp_set_object_terms( $post_thumb->post_id, $this->get_name(), 'sprite2post', false );
//        }

        return $sprite_id;
    }

    public function render_style() {
        $styles   = [];
        $styles[] = sprintf( 'background-image: url(%s)', esc_attr( $this->get_image_url() ) );
        $styles[] = 'background-size: 100% auto';

        printf( '<style>.%s { %s; }</style>', esc_attr( $this->get_class() ), implode( '; ', $styles ) );

    }

    public function sprite_style( $id ) {
        $offset        = $this->map[ $id ]['offset'];
        $width         = $this->map[ $id ]['width'];
        $height        = $this->map[ $id ]['height'];
        $sprite_height = $this->height - $this->map[ array_key_last( $this->map ) ]['height'];
        $styles        = [];
        $styles[]      = sprintf( 'background-position: 0 %.3F%%', ( 100 * $offset / $sprite_height ) );
        $styles[]      = sprintf( 'padding-bottom: %.3F%%', ( 100 * $height / $width ) );
        printf( '%s;', implode( '; ', $styles ) );
    }

    /**
     * @param string $name
     *
     * @return false|int
     */
    public function exists() {
        $existing_query = new \WP_Query( [
            'post_type'      => 'sprite',
            'posts_per_page' => 1,
            'name'           => $this->get_name(),
            'fields'         => 'ids',
        ] );

        if ( $existing_query->have_posts() ) {
            return (int) $existing_query->posts[0];
        }

        return false;
    }

    /**
     * @return bool
     */
    public function is_loaded() {
        return ( ! empty( $this->map ) && file_exists( $this->get_image_path() ) );
    }

    public function get_name( $name = null, $size = null ) {
        if ( null === $name ) {
            $name = $this->name;
        }
        if ( null === $size ) {
            $size = $this->size;
        }

        return sprintf( '%s-%s', $name, $size );
    }

    public function get_class() {
        return sprintf( 'sprite-%s', sanitize_title_with_dashes( $this->get_name() ) );
    }

    /**
     * Get absolute path to sprite image
     *
     * @return string
     */
    public function get_image_path( $name = null, $size = null ) {
        if ( null === $name ) {
            $name = $this->name;
        }
        if ( null === $size ) {
            $size = $this->size;
        }

        $upload_dir = wp_get_upload_dir();

        $sprite_dir = trailingslashit( $upload_dir['basedir'] ) . self::SPRITE_DIR;

        $image_path = sprintf( '%s/%s-%s.jpg',
            untrailingslashit( $sprite_dir ),
            $name,
            $size
        );

        return $image_path;
    }

    /**
     * Get relative URL to sprite image
     *
     * @return string
     */
    public function get_image_url( $name = null, $size = null ) {
        if ( null === $name ) {
            $name = $this->name;
        }
        if ( null === $size ) {
            $size = $this->size;
        }

        $image_path = $this->get_image_path( $name, $size );

        // @TODO make sure this is multisite compatible
        $image_url = wp_make_link_relative( home_url( str_replace( ABSPATH, '', $image_path ) ) );

        return $image_url;
    }
}
