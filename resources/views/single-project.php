<?php
/**
 * Filename single-project.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Assets\get_url;
use function Toi\ToiBox\Snippets\get_project_award;
use function Toi\ToiBox\Snippets\get_sponsor_carousel;

$sponsor = get_theme_mod( '_toibox_project_sponsor' );
$leads   = get_field( 'leads' ) ?: [];
$links   = get_field( 'links' ) ?: [];
$award   = get_project_award();
?>
<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : ?>
    <?php the_post(); ?>

    <div class="row">
      <div class="col-lg-8">
        <?php the_title( '<h1 class="h2">', '</h2>' ); ?>
      </div>
    </div>
    <?php if ( has_post_thumbnail() ) : ?>
      <div class="row">
        <div class="col">
          <figure class="figure feature-image">
            <?php the_post_thumbnail( 'banner-lg-12', [ 'class' => 'img-fluid rounded' ] ); ?>
            <?php if ( get_the_post_thumbnail_caption() ) : ?>
              <figcaption class="figure-caption"><?php the_post_thumbnail_caption(); ?></figcaption>
            <?php endif; ?>
          </figure>
        </div>
      </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-3">
        <?php if ( $award ) : ?>
          <div class="award">
            <div class="row">
              <div class="col-lg-8">
                <?php
                switch ( $award->slug ) {
                  case 'top-project':
                    printf( '<img src="%s" alt="%s">', get_url( 'svg/logo/top-project.svg' ), _x( 'Top Project Award Logo', '', '' ) );
                    break;
                  default:
                    break;
                }
                ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <?php if ( $sponsor ) : ?>
          <div class="sponsor">
            <?php
            echo get_sponsor_carousel( [
              'title'    => _x( 'Projects Sponsor', '', '' ),
              'sponsors' => [ get_post( $sponsor ) ]
            ] );
            ?>
          </div>
        <?php endif; ?>

        <?php if ( $leads ) : ?>
          <div class="leads sidebar-links">
            <h4 class="sidebar-links-title"><?php _ex( 'Project Leads', '', '' ); ?></h4>
            <ul class="sidebar-links-list">
              <?php foreach ( $leads as $lead ) : ?>
                <li class="sidebar-links-item"><?php printf( '%s', $lead['name'] ); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if ( $links ) : ?>
          <div class="links sidebar-links">
            <h4 class="sidebar-links-title"><?php _ex( 'Project Links', '', '' ); ?></h4>
            <ul class="sidebar-links-list">
              <?php foreach ( $links as $link ) : ?>
                <li class="sidebar-links-item">link</li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-8">
        <?php the_content(); ?>
      </div>
    </div>

  <?php endwhile; ?>
<?php endif; ?>

