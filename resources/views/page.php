<?php
/**
 * Filename page.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

use TEC\Tickets\Commerce\Checkout;

?>
<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : ?>
    <?php the_post(); ?>

    <div class="container-fluid">
      <div class="row justify-content-between">
        <?php if ( tribe( Checkout::class )->is_current_page() ) : ?>
          <div class="col-lg-12">
            <?php the_title( '<h1>', '</h1>' ); ?>
            <?php the_content(); ?>
          </div>
        <?php else : ?>
          <div class="col-lg-2 d-none d-lg-block">
            <?php if ( has_blocks( $post->post_content ) ) : ?>
              <?php $blocks = parse_blocks( $post->post_content ); ?>
              <nav class="nav-section sticky-top js-nav-section"
                   aria-label="<?php _ex( 'Section navigation', '', '' ); ?>">
                <ol class="nav flex-column text-right">
                  <?php
                  foreach ( $blocks as $block ) {
                    if ( 'acf/page-section' === $block['blockName'] && ! empty( $block['attrs']['data']['title'] ) ) {
                      $anchor =
                        printf(
                          '<li class="nav-item"><a class="nav-link"href="#%1$s" >%2$s</a></li>',
                          $block['attrs']['id'] . '-' . sanitize_title_with_dashes( $block['attrs']['data']['title'] ),
                          $block['attrs']['data']['title']
                        );
                    }
                  }
                  ?>
                </ol>
              </nav>
            <?php endif; ?>
          </div>
          <div class="col-lg-9">
            <?php the_title( '<h1>', '</h1>' ); ?>
            <?php the_content(); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>


