<?php
/**
 * Filename single.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Snippets\get_post_byline;

?>
<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : ?>
    <?php the_post(); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-8">
          <header>
            <?php the_title( '<h1 class="h2">', '</h2>' ); ?>
          </header>
        </div>
      </div>
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="row">
          <div class="col">
            <figure class="figure feature-image">
              <?php the_post_thumbnail( 'banner-lg-12', [ 'class' => 'img-fluid rounded' ] ); ?>
              <?php if ( get_the_post_thumbnail_caption() ) : ?>
                <figcaption class="figure-caption"><?php the_post_thumbnail_caption(); ?></figcaption>
              <?php endif; ?>
            </figure>
          </div>
        </div>
      <?php endif; ?>

      <div class="row">
        <div class="col-lg-3">
          <?php if ( is_active_sidebar( 'sidebar-single' ) ) : ?>
            <?php dynamic_sidebar( 'sidebar-single' ); ?>
          <?php endif; ?>
        </div>
        <div class="col-lg-8">
          <div class="meta">
            <time class="published" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
              <?php printf( _x( 'Published: %s', '', '' ), esc_attr( get_the_date() ) ); ?>
            </time>
            <div class="author">
              <?php printf( _x( 'By: %s', '', '' ), get_post_byline() ); ?>
            </div>
            <?php
            ?>
          </div>

          <?php the_content(); ?>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>


