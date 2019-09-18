<?php
/**
 * Filename projects.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Snippets\render_button;

$title    = get_field( 'title' );
$content  = get_field( 'content' );
$button   = get_field( 'button' );
$featured = get_field( 'featured' );
?>
<div class="container">
  <div class="row">
    <div class="col-lg-7">
      <h2><?php echo $title; ?></h2>
      <?php echo $content; ?>
      <?php render_button( $button ); ?>
    </div>
    <div class="col-lg-5">
      <h3 class="h6 project__label"><?php _ex( 'Featured Project', '', '' ); ?></h3>
      <div class="article">
        <h4 class="h5"><?php echo get_the_title( $featured ); ?></h4>
        <?php echo wp_get_attachment_image( $featured, 'medium', false, [] ); ?>
        <a class="btn btn-link btn-sm" href="<?php echo get_the_permalink( $featured ); ?>"><?php _ex( 'View Project', '', '' ); ?></a>
      </div>
    </div>
  </div>
</div>
