<?php
/**
 * Filename hero.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Snippets\get_sponsor_carousel;

$title     = get_field( 'title' );
$subtitle  = get_field( 'subtitle' );
$content   = get_field( 'content' );
$honourees = get_field( 'honourees' );
$sponsors  = get_field( 'sponsors' );

$honouree = ( is_array( $honourees ) )
  ? $honourees[ rand( 0, count( $honourees ) - 1 ) ]
  : false;
?>
<div class="container-fluid">
  <?php if ( $honouree ) : ?>
    <div class="bg">
      <?php echo wp_get_attachment_image( $honouree['image'], 'full' ); ?>
    </div>
  <?php endif; ?>
  <div class="row">
    <div class="col-md-9 col-lg-7">
      <h1 class="title">
        <?php echo $title; ?>
      </h1>
      <p class="subtitle"><?php echo $subtitle; ?></p>
      <div class="content">
        <?php echo $content; ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-10 col-sm-6 col-md-6 col-lg-4">
      <?php echo get_sponsor_carousel( $sponsors ); ?>
    </div>
  </div>
</div>
