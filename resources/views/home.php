<?php
/**
 * Filename home.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

$page_for_posts = get_option( 'page_for_posts' );
?>


<?php printf( '<h1>%s</h1>', get_the_title( $page_for_posts ) ); ?>
<?php echo get_the_content( $page_for_posts ); ?>

<?php if ( have_posts() ) : ?>
  <div class="row">
    <?php while ( have_posts() ) : ?>
      <div class="col col-md-6">
        <?php the_post(); ?>
        <?php
        if ( has_post_thumbnail() ) {
          echo get_the_post_thumbnail( null, 'archive-post-thumb', [] );
        }
        ?>
        <?php the_title( '<h1>', '</h1>' ); ?>
      </div>
    <?php endwhile; ?>
  </div>
<?php endif; ?>
