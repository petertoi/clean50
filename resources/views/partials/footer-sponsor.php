<?php
/**
 * Filename footer-sponsor.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

$intro = get_theme_mod( '_toibox_sponsor_footer_intro' ) ?: false;

$tiers = get_terms( [
  'taxonomy' => 'sponsor-tier',
] );

$tiers_sponsors = [];

foreach ( $tiers as $tier ) {
  $sponsor_args = [
    'post_type'      => 'sponsor',
    'post_status'    => 'publish',
    'posts_per_page' => 50,
    'orderby'        => [
      'title' => 'ASC',
    ],
    'tax_query'      => [
      [
        'taxonomy' => 'sponsor-tier',
        'field'    => 'term_id',
        'terms'    => $tier->term_id,
      ],
    ]
  ];

  $sponsors = new \WP_Query( $sponsor_args );

  if ( ! empty( $sponsors->posts ) ) {
    $tiers_sponsors[ $tier->slug ] = $sponsors->posts;
  }
}
?>
<div class="sponsor-footer">
  <div class="container-fluid">
    <?php if ( $intro ) : ?>
      <div class="row">
        <div class="col text-center">
          <h4 class="intro h6"><?php echo $intro; ?></h4>
        </div>
      </div>
    <?php endif; ?>
    <?php foreach ( $tiers_sponsors as $key => $tier_sponsors ) : ?>
      <?php
      if ( 'summit' === $key ) {
        continue;
      }
      ?>
      <div class="<?php echo esc_attr( $key ); ?>">
        <div class="row justify-content-center">
          <div class="col-auto d-flex flex-nowrap align-content-center">
            <div class="sponsors">
              <?php foreach ( $tier_sponsors as $sponsor ) : ?>
                <?php
                if ( has_post_thumbnail( ( $sponsor->ID ) ) ) {
                  $atts  = [ 'class' => 'img-fluid sponsor-logo' ];
                  $scale = get_field( 'logo_scale', $sponsor->ID ) ?: 1;
                  if ( 1 !== $scale ) {
                    $atts['style'] = sprintf( 'transform: scale(%s);', $scale );
                  }
                  $link = get_field( 'link', $sponsor->ID );
                  printf( '<a href="%s" class="sponsor" target="_blank"><h5 class="sponsor-title">%s</h5>%s</a>',
                    ! empty( $link['target'] ) ? $link['target'] : '#',
                    $sponsor->post_title,
                    get_the_post_thumbnail( $sponsor->ID, 'sponsor-carousel', $atts )
                  );
                }
                ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if ( array_key_exists( 'summit', $tiers_sponsors ) ) : ?>
      <div class="summit">
        <div class="row justify-content-center">
          <div class="tier-title">
            <h4><?php echo esc_html_x( 'Summit Sponsors', '', '' ); ?></h4>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-auto align-content-center">
            <div class="sponsors">
              <?php foreach ( $tiers_sponsors['summit'] as $sponsor ) : ?>
                <?php
                if ( has_post_thumbnail( ( $sponsor->ID ) ) ) {
                  $atts  = [ 'class' => 'img-fluid sponsor-logo' ];
                  $scale = get_field( 'logo_scale', $sponsor->ID ) ?: 1;
                  if ( 1 !== $scale ) {
                    $atts['style'] = sprintf( 'transform: scale(%s);', $scale );
                  }
                  $link = get_field( 'link', $sponsor->ID );
                  printf( '<a href="%s" class="sponsor" target="_blank"><h5 class="sponsor-title">%s</h5>%s</a>',
                    ! empty( $link['target'] ) ? $link['target'] : '#',
                    $sponsor->post_title,
                    get_the_post_thumbnail( $sponsor->ID, 'sponsor-carousel', $atts )
                  );
                }
                ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
