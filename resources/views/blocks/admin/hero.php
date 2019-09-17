<?php
/**
 * Filename hero.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

$title          = get_field( 'title' );
$subtitle       = get_field( 'subtitle' );
$content        = get_field( 'content' );
$honourees      = get_field( 'honourees' );
$sponsors_title = get_field( 'sponsors_title' );
$sponsors       = get_field( 'sponsors' );
?>

<div class="row">
  <div class="col-lg-7">
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
  <div class="col-lg-4">
    <div class="sponsors-carousel">
      <?php if ( $sponsors_title ) : ?>
        <h4 class="carousel__title"><?php echo $sponsors_title; ?></h4>
      <?php endif; ?>
      <div id="hero-sponsors" class="carousel slide" data-ride="carousel" data-interal="3000">
        <ol class="carousel-indicators">
          <?php foreach ( $sponsors as $key => $sponsor ) : ?>
            <li data-target="#hero-sponsors" data-slide-to="<?php echo esc_attr( $key ); ?>" class="<?php echo ( 0 === $key ) ? 'active' : ''; ?>"></li>
          <?php endforeach; ?>
        </ol>
        <div class="carousel-inner">
          <?php foreach ( $sponsors as $key => $sponsor ) : ?>
            <div class="carousel-item <?php echo ( ! $key ) ?: 'active'; ?>">
              <?php
              echo get_the_post_thumbnail(
                $sponsor,
                'sponsor-carousel',
                [
                  'class' => 'sponsor__logo',
                  'alt'   => get_the_title( $sponsor ),
                ]
              ); ?>
              <div class="sponsor__description"><?php echo get_field( 'short_description', $sponsor ); ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
