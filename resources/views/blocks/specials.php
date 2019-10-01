<?php
/**
 * Filename specials.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

$features = get_field( 'features' );
?>
<div class="container-fluid">
  <div class="row flex-column flex-md-row">
    <?php foreach ( $features as $feature ) : ?>
      <div class="col mb-4 mb-md-0">
        <article class="special d-flex flex-column">
          <div class="special-label h6"><?php _ex( 'Special Feature', '', '' ); ?></div>
          <h2 class="special-title h4"><?php echo $feature->post_title; ?></h2>
          <a href="<?php echo get_the_permalink( $feature->ID ); ?>" class="article-read-more stretched-link mt-auto"><?php _ex( 'Read More', '', '' ); ?></a>
        </article>
      </div>
    <?php endforeach; ?>
  </div>
</div>
