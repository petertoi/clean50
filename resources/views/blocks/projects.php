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
          <?php
          $banner_sm_src    = wp_get_attachment_image_url( get_post_thumbnail_id( $featured ), 'banner-md-12' );
          $banner_sm_srcset = wp_get_attachment_image_srcset( get_post_thumbnail_id( $featured ), 'banner-md-12' );
          $banner_lg_src    = wp_get_attachment_image_url( get_post_thumbnail_id( $featured ), 'banner-lg-6' );
          $banner_lg_srcset = wp_get_attachment_image_srcset( get_post_thumbnail_id( $featured ), 'banner-lg-6' );
          ?>
          <picture class="project-feature-image">
            <source
              class="img-fluid rounded"
              src="<?php echo $banner_lg_src; ?>"
              srcset="<?php echo $banner_lg_srcset; ?>"
              media="(min-width: 768px)"
            >
            <img
              class="img-fluid rounded"
              src="<?php echo $banner_sm_src; ?>"
              srcset="<?php echo $banner_sm_srcset; ?>"
              alt=""
            >
          </picture>
        <?php endif; ?>
        <div class="article-label"><?php _ex( 'Featured Project', '', '' ); ?></div>
        <h4 class="project-title"><?php echo get_the_title( $featured ); ?></h4>
        <a class="article-read-more stretched-link" href="<?php echo get_the_permalink( $featured ); ?>"><?php _ex( 'View Project', '', '' ); ?></a>
      </div>
    </div>
  </div>
</div>
