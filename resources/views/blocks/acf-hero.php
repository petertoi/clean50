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

$honouree_images = array_map( function ( $honouree ) {
  $caption = sprintf( '<span class="caption__title">%s</span><span class="caption__subtitle">%s</span>',
    $honouree['title'],
    $honouree['subtitle']
  );

  return sprintf( '<div class="honouree__image">%s</div><div class="honouree__caption">%s</div>',
    wp_get_attachment_image( $honouree['image'], 'full' ),
    $caption
  );
}, $honourees );

$noscript_fallback = ( is_array( $honourees ) )
  ? $honourees[ rand( 0, count( $honourees ) - 1 ) ]
  : false;
?>
<div class="container-fluid">
  <?php if ( ! empty( $honourees ) ) : ?>
    <div class="bg">

      <div id="hero-honouree" class="honouree js-hero-honouree">
        <script>
          <?php // Load a random image in the header via JS ?>
          var hero_honorees = <?php echo wp_json_encode( $honouree_images ); ?>;
          if ( hero_honorees.length > 0 ) {
            document.getElementById( 'hero-honouree' ).innerHTML = hero_honorees[ Math.floor( Math.random() * Math.floor( hero_honorees.length ) ) ];
          }
        </script>
        <noscript>
          <?php // Fallback for no JS ?>
          <?php echo $honouree_images[ rand( 0, count( $honouree_images ) - 1 ) ]; ?>
        </noscript>
      </div>
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
