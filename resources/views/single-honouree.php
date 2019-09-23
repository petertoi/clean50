<?php
/**
 * Filename single-honouree.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use Toi\ToiBox\Sprite;
use function Toi\ToiBox\Assets\get_svg;
use function Toi\ToiBox\Assets\get_url;
use function Toi\ToiBox\Snippets\get_award;
use function Toi\ToiBox\Snippets\get_award_category;
use function Toi\ToiBox\Snippets\get_award_year;
use function Toi\ToiBox\Snippets\get_honouree_type;
use function Toi\ToiBox\Snippets\get_team;
use function Toi\ToiBox\Snippets\get_team_members;

$organization = get_field( 'organization' );
$award        = get_award();
$year         = get_award_year();
$category     = get_award_category();
$type         = get_honouree_type();
$team         = get_team();
$social       = get_field( 'social' );
$links        = get_field( 'links' );

?>
<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : ?>
    <?php the_post(); ?>

    <div class="row">
      <div class="col-lg-9">
        year / category
      </div>
      <div class="col-lg3">
        category sponsor
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3">
        <?php
        if ( has_post_thumbnail() ) {
          echo get_the_post_thumbnail( null, 'single-honouree-thumb', [ 'alt' => get_the_title() . ' headshot', 'class' => 'featured-image img-fluid rounded' ] );
        }
        ?>
        <div class="row">
          <div class="col-lg-8">
            <?php
            if ( $award ) {
              switch ( $award->slug ) {
                case 'emerging-leader':
                  printf( '<img class="award award-emerging-leader" src="%s" alt="%s">', get_url( 'svg/logo/emerging-leader.svg' ), _x( 'Clean50 Emerging Leader Award Logo', '', '' ) );
                  break;
                case 'clean16':
                  printf( '<img class="award award-clean16" src="%s" alt="%s">', get_url( 'svg/logo/clean16.svg' ), _x( 'Clean16 Award Logo', '', '' ) );
                  break;
                case 'clean50':
                default:
                  printf( '<img class="award award-clean50" src="%s" alt="%s">', get_url( 'svg/logo/clean50.svg' ), _x( 'Clean50 Award Logo', '', '' ) );
                  break;
              }
            }
            ?>
          </div>
        </div>
        <?php if ( $links ) : ?>
          <div class="links sidebar-links">
            <h4 class="sidebar-links-title"><?php _ex( 'Learn More', '', '' ); ?></h4>
            <ul class="sidebar-links-list">
              <?php foreach ( $links as $link ) : ?>
                <?php printf( '<li class="sidebar-links-item"><a href="%1$s" title="%2$s">%2$s</a></li>', $link['target'], $link['label'] ); ?>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

      </div>
      <div class="col-lg-6">
        <header class="header">
          <?php the_title( '<h1 class="h2 title">', '</h1>' ); ?>
          <?php if ( $organization ) : ?>
            <div class="organization h3"><?php echo $organization; ?></div>
          <?php endif; ?>
          <?php if ( $social ) : ?>
            <?php
            uasort( $social, function ( $a, $b ) {
              if ( $a['service'] === $b['service'] ) {
                return 0;
              }

              return ( $a['service'] < $b['service'] ) ? - 1 : 1;
            } )
            ?>
            <div class="social d-flex">
              <?php foreach ( $social as $profile ) : ?>
                <?php
                printf( '<a href="%s" class="profile profile-%s">%s</a>',
                  $profile['target'],
                  $profile['service'],
                  get_svg( $profile['service'] )
                );
                ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </header>
        <?php the_content(); ?>
      </div>
      <div class="col-lg-3">
        <?php if ( $team ) : ?>
          <div class="related-team">
            <h5 class="related-team-title">
              <a class="h5" href="<?php echo get_the_permalink( $team ); ?>"><?php echo get_the_title( $team ); ?></a>
            </h5>
            <?php
            $team_members    = get_team_members( $team );
            $team_member_ids = wp_list_pluck( $team_members, 'ID' );

            $sprite = new Sprite(
              sprintf( 'team-%s', $team->post_name ),
              $team_member_ids,
              'sprite-lg'
            );

            $sprite->render_style();
            ?>
            <div class="sprite-row related-team-members">
              <?php foreach ( $team_member_ids as $team_member ) : ?>
                <div class="sprite-col">
                  <div class="<?php echo $sprite->get_class(); ?> rounded" style="<?php $sprite->sprite_style( $team_member ); ?>">
                    <a href="<?php echo get_the_permalink( $team_member ); ?>" alt="<?php echo esc_attr( get_the_title( $team_member ) ); ?>" class="stretched-link">
                      <span class="sr-only"><?php echo get_the_title( $team_member ); ?></span>
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <?php if ( 'team-member' === $type->slug ) : ?>
              <a class="related-team-link h5" href="<?php echo get_the_permalink( $team ); ?>"><?php _ex( 'View Team', '', '' ); ?></a>
            <?php endif; ?>
          </div>
        <?php endif; ?>


        <?php if ( is_active_sidebar( 'sidebar-single-honouree' ) ) : ?>
          <?php dynamic_sidebar( 'sidebar-single-honouree' ); ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>

