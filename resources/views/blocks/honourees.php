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
<div class="container">
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
              <div class="col-lg-5 col-sprite">
                <?php
                $award = get_term( $tab['sprite']['award'], 'award' );
                $year  = get_term( $tab['sprite']['award_year'], 'award-year' );
                $size  = $tab['sprite']['size'];

                $honouree_query = new \WP_Query( [
                  'post_type'      => 'honouree',
                  'posts_per_page' => - 1,
                  'fields'         => 'ids',
                  'tax_query'      => [
                    'relation' => 'AND',
                    [
                      'taxonomy' => 'award',
                      'field'    => 'term_id',
                      'terms'    => $award->term_id,
                    ],
                    [
                      'taxonomy' => 'award-year',
                      'field'    => 'term_id',
                      'terms'    => $year->term_id,
                    ]
                  ],
                ] );

                $name = sprintf( '%s-%s', $award->slug, $year->slug );

                $sprite = new Sprite( $name, $honouree_query->posts, $size );

                if ( ! $sprite->is_loaded() ) {
                  $sprite->create( $honouree_query->posts, $size );
                }

                $sprite->render_style();

                ?>
                <div class="sprite-row <?php echo esc_attr( $size ); ?>">
                  <?php foreach ( $honouree_query->posts as $honouree ) : ?>
                    <div class="sprite-col">
                      <div class="<?php echo $sprite->get_class(); ?> rounded" style="<?php $sprite->sprite_style( $honouree ); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo get_the_title( $honouree ); ?>">
                        <a href="<?php echo get_the_permalink( $honouree ); ?>" alt="<?php echo esc_attr( get_the_title( $honouree ) ); ?>" class="stretched-link">
                          <span class="sr-only"><?php echo get_the_title( $honouree ); ?></span>
                        </a>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="col-lg-7">
                <div class="content">
                  <?php echo $tab['content']; ?>
                </div>
                <div class="actions">
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
</div>
