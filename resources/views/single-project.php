<?php
/**
 * Filename single-project.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Assets\get_url;
use function Toi\ToiBox\Snippets\get_project_award;

$leads = get_field( 'leads' ) ?: [];
$links = get_field( 'links' ) ?: [];
$award = get_project_award();
?>
<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : ?>
    <?php the_post(); ?>

    <div class="row">
      <div class="col-lg-8">
        <?php the_title( '<h1 class="h2">', '</h2>' ); ?>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <?php if ( has_post_thumbnail() ) : ?>
          <figure class="figure feature-image">
            <?php the_post_thumbnail( 'banner-lg-12', [ 'class' => 'img-fluid rounded' ] ); ?>
            <?php if ( get_the_post_thumbnail_caption() ) : ?>
              <figcaption class="figure-caption"><?php the_post_thumbnail_caption(); ?></figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; ?>
      </div>
    </div>
    <div class="row">
      <?php if ( $award ) : ?>
      <div class="award">
        <div class="col-lg-3">
          <div class="row">
            <div class="col-lg-8">
              <?php
              switch ( $award->slug ) {
                case 'top-project':
                default:
                  printf( '<img class="award award-top-project" src="%s" alt="%s">', get_url( 'svg/logo/top-15-projects.svg' ), _x( 'Top 15 Projects Award Logo', '', '' ) );
                  break;
              }
              ?>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <div class="sponsor">
          Sponsor
        </div>

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

