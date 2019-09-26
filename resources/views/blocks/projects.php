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
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 mb-5 mb-md-0">
      <h2><?php echo $title; ?></h2>
      <?php echo $content; ?>
      <?php render_button( $button ); ?>
    </div>
    <div class="col-md-6">
      <div class="article project">
        <?php if ( has_post_thumbnail( $featured ) ) : ?>
          <?php echo get_the_post_thumbnail( $featured, 'banner-lg-6', [ 'class' => ' project-feature-image img-fluid rounded' ] ); ?>
        <?php endif; ?>
        <span class="article-label"><?php _ex( 'Featured Project', '', '' ); ?></span>
        <h4 class="project-title"><?php echo get_the_title( $featured ); ?></h4>
        <a class="article-read-more stretched-link" href="<?php echo get_the_permalink( $featured ); ?>"><?php _ex( 'View Project', '', '' ); ?></a>
      </div>
    </div>
  </div>
</div>
