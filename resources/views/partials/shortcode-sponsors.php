<?php
/**
 * Filename shortcode-sponsors.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Snippets\render_button;

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

<?php foreach ( $tiers_sponsors as $key => $tier_sponsors ) : ?>
  <?php
  if ( 'summit' === $key ) {
    continue;
  }
  ?>
  <div class="shortcode-sponsors">
    <?php foreach ( $tier_sponsors as $sponsor ) : ?>
      <div class="shortcode-sponsors-item">
        <?php if ( has_post_thumbnail( ( $sponsor->ID ) ) ) : ?>
          <?php echo get_the_post_thumbnail( $sponsor->ID, 'sponsor-carousel', [ 'class' => 'img-fluid sponsor-logo' ] ); ?>
        <?php endif; ?>
        <?php
        printf( '<h3 class="sponsor-title">%s</h3>', esc_html( $sponsor->post_title ) );

        printf( '<div class="sponsor-description">%s</div>', apply_filters( 'the_content', get_field( 'long_description', $sponsor->ID ) ) );

        $link = get_field( 'link', $sponsor->ID );
        if ( isset( $link['target'] ) ) {
          printf( '<div class="sponsor-link"><a href="%s" target="_blank">%s</a></div>',
            $link['target'],
            sprintf( 'Visit %s', $sponsor->post_title ),
          );
        }
        ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endforeach; ?>
