<?php
/**
 * Filename archives-honouree.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Assets\get_svg;
use function Toi\ToiBox\Snippets\bootstrap_pagination;
use function Toi\ToiBox\Snippets\get_award;
use function Toi\ToiBox\Snippets\get_award_category;
use function Toi\ToiBox\Snippets\get_award_year;
use function Toi\ToiBox\Snippets\get_individual_award;
use function Toi\ToiBox\Snippets\get_sponsor_carousel;

global $wp_query;

$title   = get_theme_mod( '_toibox_honouree_archive_title' );
$content = get_theme_mod( '_toibox_honouree_archive_content' );

$awards = get_terms( [
  'taxonomy'   => 'award',
  'hide_empty' => false,
] );

$award_years = get_terms( [
  'taxonomy'   => 'award-year',
  'order'      => 'DESC',
  'hide_empty' => false,
] );

$award_categories = get_terms( [
  'taxonomy'   => 'award-category',
  'hide_empty' => false,
] );

$selected_award = filter_input( INPUT_GET, 'award', FILTER_SANITIZE_STRING ) ?: '';
$selected_year  = filter_input( INPUT_GET, 'year', FILTER_SANITIZE_STRING ) ?: '';
$selected_cat   = filter_input( INPUT_GET, 'cat', FILTER_SANITIZE_STRING ) ?: '';
?>

<div class="container-fluid">
  <div class="intro row">
    <div class="col col-md-10 col-lg-9">
      <?php if ( $title ) : ?>
        <?php printf( '<h1>%s</h1>', $title ); ?>
      <?php endif; ?>
      <?php if ( $content ) : ?>
        <?php echo apply_filters( 'the_content', $content ); ?>
      <?php endif; ?>
    </div>
  </div>

  <form class="filters" method="get">
    <div class="row">
      <div class="form-group col-auto">
        <label for="award"><?php _ex( 'Select an award', '', '' ); ?></label>
        <select class="form-control" id="award" name="award">
          <option value=""><?php _ex( 'All awards', '', '' ); ?></option>
          <?php foreach ( $awards as $award ) : ?>
            <?php
            printf( '<option value="%s" %s>%s',
              $award->slug,
              selected( $award->slug, $selected_award, false ),
              $award->name
            );
            ?>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group col-auto">
        <label for="award-year"><?php _ex( 'Select a year', '', '' ); ?></label>
        <select class="form-control" id="award-year" name="year">
          <option value=""><?php _ex( 'All years', '', '' ); ?></option>
          <?php foreach ( $award_years as $year ) : ?>
            <?php
            printf( '<option value="%s" %s>%s',
              $year->slug,
              selected( $year->slug, $selected_year, false ),
              $year->name );
            ?>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group col-auto">
        <label for="award-category"><?php _ex( 'Select a category', '', '' ); ?></label>
        <select class="form-control" id="award-category" name="cat">
          <option value=""><?php _ex( 'All categories', '', '' ); ?></option>
          <?php foreach ( $award_categories as $category ) : ?>
            <?php
            printf( '<option value="%s" %s>%s',
              $category->slug,
              selected( $category->slug, $selected_cat, false ),
              $category->name );
            ?>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <noscript>
      <div class="form-row">
        <div class="form-group col-auto">
          <button type="submit" class="btn btn-primary"><?php _ex( 'Apply Filters', '', '' ); ?></button>
        </div>
      </div>
    </noscript>
  </form>

  <?php if ( $selected_cat ) : ?>
    <?php $category = get_term_by( 'slug', $selected_cat, 'award-category' ); ?>
    <?php $sponsors = get_field( 'sponsors', 'award-category_' . $category->term_id ); ?>
    <header class="header row justify-content-between">
      <div class="col-12 col-md-6">
        <h2><?php echo $category->name; ?></h2>
      </div>
      <?php if ( isset( $sponsors['sponsors'] ) && ! empty( $sponsors['sponsors'] ) ) : ?>
        <div class="col-12 col-md-6">
          <?php echo get_sponsor_carousel( $sponsors ); ?>
        </div>
      <?php endif; ?>
    </header>
  <?php endif; ?>

  <?php if ( have_posts() ) : ?>

    <div class="honouree-grid row">
      <?php while ( have_posts() ) : ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
          <?php
          the_post();
          $award            = get_award();
          $award_year       = get_award_year();
          $award_category   = get_award_category();
          $individual_award = get_individual_award();
          $organization     = get_field( 'organization' );
          ?>
          <article class="honouree-grid-item">
            <div class="thumb text-center">
              <?php echo get_svg( 'boulder', 'boulder' ); ?>
              <?php
              if ( has_post_thumbnail() ) {
                echo get_the_post_thumbnail( null, 'square-lg-3', [ 'class' => 'img-fluid' ] );
              }
              ?>
              <?php if ( $award ) : ?>
                <span class="badge badge-pill badge-<?php echo $award->slug; ?>"><?php echo $award->name ?: ''; ?></span>
              <?php endif; ?>
            </div>
            <div class="body">
              <?php if ( $award_year ) : ?>
                <div class="year h6"><?php echo $award_year->name ?: ''; ?></div>
              <?php endif; ?>
              <a href="<?php the_permalink(); ?>" class="stretched-link">
                <?php the_title( '<h2 class="title h5">', '</h1>' ); ?>
              </a>
              <?php if ( isset( $organization ) ) : ?>
                <div class="organization h6"><?php echo $organization; ?></div>
              <?php endif; ?>
              <?php if ( $award_category ) : ?>
                <div class="category h6"><?php echo $award_category->name ?: ''; ?></div>
              <?php endif; ?>
              <?php if ( $individual_award ) : ?>
                <div class="badge badge-pill badge-individual"><?php echo $individual_award->name; ?></div>
              <?php endif; ?>
            </div>
          </article>
        </div>
      <?php endwhile; ?>
    </div>

    <nav class="d-flex justify-content-center" aria-label="<?php _ex( 'Honouree archive pagination', '', '' ); ?>">
      <?php bootstrap_pagination( null, [
        'prev_text' => '<span aria-hidden="true">&lt;</span><span class="sr-only">' . _x( 'Previous', '', '' ) . '</span>',
        'next_text' => '<span aria-hidden="true">&gt;</span><span class="sr-only">' . _x( 'Next', '', '' ) . '</span>',
      ] ); ?>
    </nav>
  <?php endif; ?>
</div>
