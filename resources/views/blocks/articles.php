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

<div class="row">
  <div class="col">
    <?php if ( $title ) : ?>
      <h2><?php echo $title; ?></h2>
    <?php endif; ?>
  </div>
</div>
<div class="row">
  <?php foreach ( $posts as $post ) : ?>
    <div class="col col-lg-3">
      <article>
        <?php if ( has_post_thumbnail( $post ) ) : ?>
          <?php printf( '<div><a href="%s">%s</a></div>', get_the_permalink( $post ), get_the_post_thumbnail( $post, 'block-articles', [] ) ); ?>
        <?php endif; ?>
        <?php
        $category = get_the_category( $post );
        // @TODO look at implementing a primary category library (not Yoast)
        /** @var \WP_Term $primary */
        $primary = $category[0] ?: 0;
        if ( $primary ) {
          printf( '<div><a href="%s">%s</a></div>', get_category_link( $primary ), $primary->name );
        }
        ?>
        <?php printf( '<h3><a href="%s">%s</a></h3>', get_the_permalink( $post ), get_the_title( $post ) ); ?>
      </article>
    </div>
  <?php endforeach; ?>
</div>
