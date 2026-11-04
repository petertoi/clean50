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

?>

<div class="container-fluid">
  <div class="row">
    <div class="">
      <h1 class="title">
        <?php echo $title; ?>
      </h1>
      <h2 class=""><?php echo $subtitle; ?></h2>
      <div class="content">
        <?php echo $content; ?>
      </div>
    </div>
  </div>
</div>
