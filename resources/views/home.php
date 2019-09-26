<?php
/**
 * Filename home.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Snippets\bootstrap_pagination;

$page_for_posts = get_option( 'page_for_posts' );
?>


<?php printf( '<h1>%s</h1>', get_the_title( $page_for_posts ) ); ?>
<?php echo get_the_content( $page_for_posts ); ?>

<?php if ( have_posts() ) : ?>
  <div class="article-grid row">
    <?php while ( have_posts() ) : ?>
      <div class="col col-md-6">
        <?php
        the_post();
        ?>
        <article class="article-grid-item">
          <div class="thumb">
            <?php
            if ( has_post_thumbnail() ) {
              echo get_the_post_thumbnail( null, 'banner-lg-6', [ 'class' => 'img-fluid' ] );
            }
            ?>
          </div>
          <div class="body">
            <a href="<?php the_permalink(); ?>" class="stretched-link">
              <?php the_title( '<h2 class="h3 title">', '</h1>' ); ?>
            </a>
            <div class="excerpt">
              <?php the_excerpt(); ?>
            </div>
          </div>
      </div>
    <?php endwhile; ?>
  </div>

  <nav class="d-flex justify-content-center" aria-label="<?php _ex( 'Article archive pagination', '', '' ); ?>">
    <?php bootstrap_pagination( null, [
      'prev_text' => '&lt;<span class="sr-only">' . _x( 'Previous', '', '' ) . '</span>',
      'next_text' => '&gt;<span class="sr-only">' . _x( 'Next', '', '' ) . '</span>',
    ] ); ?>
  </nav>

<?php endif; ?>
