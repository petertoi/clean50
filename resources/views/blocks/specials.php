<?php
/**
 * Filename specials.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

$features = [ 1, 2, 3 ]; //get_field( 'features' );
?>
<div class="container">
  <div class="row">
    <?php foreach ( $features as $feature ) : ?>
      <div class="col">
        <article>
          <div><span class="h6"><?php _ex( 'Special Feature', '', '' ); ?></span></div>
          <h2><a href="#" class="">Hello World!</a></h2>
          <a href="#" class="">View PDF Version</a>
        </article>
      </div>
    <?php endforeach; ?>
  </div>
</div>
