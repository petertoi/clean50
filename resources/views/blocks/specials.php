<?php
/**
 * Filename specials.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

$features = [ 1, 2, 3 ]; //get_field( 'features' );
?>
<div class="container-fluid">
  <div class="row flex-column flex-md-row">
    <?php foreach ( $features as $feature ) : ?>
      <div class="col mb-4 mb-md-0">
        <article class="special d-flex flex-column">
          <div class="special-label h6"><?php _ex( 'Special Feature', '', '' ); ?></div>
          <h2 class="special-title h4">Hello World!</h2>
          <a href="#" class="article-read-more stretched-link mt-auto">View PDF Version</a>
        </article>
      </div>
    <?php endforeach; ?>
  </div>
</div>
