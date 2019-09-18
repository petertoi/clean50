<?php
/**
 * Filename nominate.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Assets\get_svg;
use function Toi\ToiBox\Snippets\render_button;

$title  = get_field( 'title' );
$button = get_field( 'button' );
?>
<div class="container">
  <div class="row justify-content-center align-items-center">
    <div class="col-auto">
      <h3><?php echo $title; ?></h3>
    </div>
    <div class="col-auto">
      <div class="btn-boulder">
        <?php echo get_svg( 'boulder', 'boulder' ); ?>
        <?php render_button( $button, [ 'btn', 'btn-link' ] ); ?>
      </div>
    </div>
  </div>
</div>
