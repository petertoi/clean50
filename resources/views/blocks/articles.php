<?php
/**
 * Filename articles.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

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
          <?php if ( has_post_thumbnail( $post ) ) : ?>
            <?php echo get_the_post_thumbnail( $post, 'square-lg-3', [ 'class' => 'd-none d-md-block img-fluid rounded' ] ); ?>
            <?php echo get_the_post_thumbnail( $post, 'banner-lg-6', [ 'class' => 'd-md-none img-fluid rounded' ] ); ?>
          <?php endif; ?>
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
