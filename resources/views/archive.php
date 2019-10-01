<?php
/**
 * Filename archives.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Snippets\bootstrap_pagination;

//$title           = get_theme_mod( '_toibox_article_archive_title' ) ?: '';
//$content         = get_theme_mod( '_toibox_article_archive_content' ) ?: '';
$fallback_banner = get_theme_mod( '_toibox_article_archive_fallback_article_image' );

?>


<div class="intro row">
  <div class="col col-md-10 col-lg-9">
      <?php the_archive_title('<h1>', '</h1>'); ?>
  </div>
</div>

<?php if ( have_posts() ) : ?>
  <div class="article-grid row">
    <?php while ( have_posts() ) : ?>
      <div class="col-12 col-md-6 mb-4">
        <?php
        the_post();
        ?>
        <article class="article-grid-item">
          <div class="thumb">
            <?php
            if ( has_post_thumbnail() ) {
              echo get_the_post_thumbnail( null, 'banner-lg-6', [ 'class' => 'img-fluid rounded' ] );
            } else if ( $fallback_banner ) {
              echo wp_get_attachment_image( $fallback_banner, 'banner-lg-6', false, [ 'class' => 'img-fluid rounded' ] );
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
