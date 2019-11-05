<?php
/**
 * Filename articles.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

$fallback_image_id = get_theme_mod( '_toibox_article_archive_fallback_article_image' );

$title        = get_field( 'title' );
$category__in = get_field( 'category__in' );

$args = [
  'posts_per_page' => 4,
];

if ( $category__in ) {
  $args['category__in'] = $category__in;
}

$posts = get_posts( $args );
?>
<div class="container-fluid">
  <div class="row align-content-center">
    <div class="col">
      <?php if ( $title ) : ?>
        <h2><?php echo $title; ?></h2>
      <?php endif; ?>
    </div>
    <div class="col-auto text-right">
      <?php if ( get_option( 'page_for_posts' ) ) : ?>
        <?php printf( '<a class="article-read-more" href="%s">%s</a>', get_the_permalink( get_option( 'page_for_posts' ) ), _x( 'View all', '', '' ) ); ?>
      <?php endif; ?>
    </div>
  </div>
  <div class="row">
    <?php foreach ( $posts as $post ) : ?>
      <div class="col-12 col-sm-6 col-md-3 mb-4 mb-md-0">
        <article class="article">
          <?php
          $image_id = ( has_post_thumbnail( $post ) )
            ? get_post_thumbnail_id( $post )
            : $fallback_image_id;

          $banner_src = wp_get_attachment_image_url( $image_id, 'banner-sm-12' );
          $banner_srcset = wp_get_attachment_image_srcset( $image_id, 'banner-sm-12' );
          $square_src = wp_get_attachment_image_url( $image_id, 'square-lg-3' );
          $square_srcset = wp_get_attachment_image_srcset( $image_id, 'square-lg-3' );
          ?>
          <picture>
            <source
              class="img-fluid rounded"
              src="<?php echo $square_src; ?>"
              srcset="<?php echo $square_srcset; ?>"
              media="(min-width: 768px)"
            >
            <img
              class="img-fluid rounded"
              src="<?php echo $banner_src; ?>"
              srcset="<?php echo $banner_srcset; ?>"
              alt=""
            >
          </picture>

          <?php
          $category = get_the_category( $post );
          // @TODO look at implementing a primary category library (not Yoast)
          /** @var \WP_Term $primary */
          $primary = $category[0] ?: 0;
          if ( $primary ) {
            printf( '<div><a href="%s" class="article-label">%s</a></div>', get_category_link( $primary ), $primary->name );
          }
          ?>
          <?php printf( '<h4 class="article-title">%s</h4>', get_the_title( $post ) ); ?>
          <?php printf( '<a title="%s" href="%s" class="article-read-more stretched-link">%s</a>', get_the_title( $post ), get_the_permalink( $post ), _x( 'Read more', '', '' ) ); ?>
        </article>
      </div>
    <?php endforeach; ?>
  </div>
</div>
