<?php
/**
 * Filename honourees.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use Toi\ToiBox\Sprite;
use function Toi\ToiBox\Snippets\render_button;

$tabs = get_field( 'tabs' );
?>
<div class="row">
  <div class="col">
    <ul class="nav nav-ruled justify-content-center" id="tab-content--honourees" role="tablist">
      <?php foreach ( $tabs as $key => $tab ) : ?>
        <li class="nav-item">
          <a class="nav-link h3 <?php echo ( 0 === $key ) ? 'active' : ''; ?>" id="<?php echo sanitize_title_with_dashes( "tab-{$tab['label']}-{$key}" ); ?>" data-toggle="tab" href="#<?php echo sanitize_title_with_dashes( "{$tab['label']}-{$key}" ); ?>" role="tab" aria-controls="home" aria-selected="<?php echo ( 0 === $key ) ? 'true' : 'false'; ?>"><?php echo $tab['label']; ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
    <div class="tab-content" id="tab-content--honourees">
      <?php foreach ( $tabs as $key => $tab ) : ?>
        <div class="tab-pane fade <?php echo ( 0 === $key ) ? 'show active' : ''; ?>" id="<?php echo sanitize_title_with_dashes( "{$tab['label']}-{$key}" ); ?>" role="tabpanel" aria-labelledby="<?php echo sanitize_title_with_dashes( "tab-{$tab['label']}-{$key}" ); ?>">
          <div class="row">
            <div class="col-lg-5">
              <?php
              $honouree_query = new \WP_Query( [
                'post_type'      => 'honouree',
                'posts_per_page' => - 1,
                'fields'         => 'ids',
                'tax_query'      => [
                  'relation' => 'AND',
                  [
                    'taxonomy' => 'award',
                    'field'    => 'term_id',
                    'terms'    => $tab['sprite']['award'],
                  ],
                  [
                    'taxonomy' => 'award-year',
                    'field'    => 'term_id',
                    'terms'    => $tab['sprite']['award_year'],
                  ]
                ],
              ] );

              if ( $honouree_query->have_posts() ) {
                $sprite = new Sprite( $honouree_query->posts );
                // If sprite loaded correctly
                // Display
                $class = sprintf( 'sprite-%s', strtolower( sanitize_title_with_dashes( $tab['label'] ) ) );
                $sprite->render_style( $class );
              }

              ?>
              <div class="row no-gutters">
                <div class="honouree-grid honouree-grid--<?php echo esc_attr( $tab['sprite']['size'] ); ?> d-flex flex-wrap">
                  <?php foreach ( $honouree_query->posts as $honouree ) : ?>
                    <div class="<?php echo $class; ?> honouree-grid-item" style="<?php $sprite->sprite_style( $honouree ); ?>">
                      <a href="<?php echo get_the_permalink( $honouree ); ?>" alt="<?php echo esc_attr( get_the_title( $honouree ) ); ?>" class="stretched-link">
                        <span class="sr-only"><?php echo get_the_title( $honouree ); ?></span>
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-7">
              <div class="content__content">
                <?php echo $tab['content']; ?>
              </div>
              <div class="content__actions">
                <?php render_button( $tab['primary_link'], [ 'btn', 'btn-primary' ] ); ?>
                <?php render_button( $tab['secondary_link'], [ 'btn', 'btn-link' ] ); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
