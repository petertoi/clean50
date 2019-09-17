<?php
/**
 * Filename archives-honouree.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Assets\get_svg;

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
?>

<?php printf( '<h1>%s</h1>', $title ); ?>
<?php echo apply_filters( 'the_content', $content ); ?>

<form method="get">
  <div class="row">
    <div class="form-group col-auto">
      <label for="award"><?php _ex( 'Select an award', '', '' ); ?></label>
      <select class="form-control" id="award" name="award">
        <option value=""><?php _ex( 'All awards', '', '' ); ?></option>
        <?php foreach ( $awards as $award ) : ?>
          <?php
          printf( '<option value="%s" %s>%s',
            $award->slug,
            selected( $award->slug, filter_input( INPUT_GET, 'award', FILTER_SANITIZE_STRING ), false ),
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
            selected( $year->slug, filter_input( INPUT_GET, 'year', FILTER_SANITIZE_STRING ), false ),
            $year->name );
          ?>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group col-auto">
      <label for="award-category"><?php _ex( 'Select a category', '', '' ); ?></label>
      <select class="form-control" id="award-category" name="category">
        <option value=""><?php _ex( 'All categories', '', '' ); ?></option>
        <?php foreach ( $award_categories as $category ) : ?>
          <?php
          printf( '<option value="%s" %s>%s',
            $category->slug,
            selected( $category->slug, filter_input( INPUT_GET, 'category', FILTER_SANITIZE_STRING ), false ),
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

<?php if ( have_posts() ) : ?>

  <div class="honouree-grid row">
    <?php while ( have_posts() ) : ?>
      <div class="col col-md-4 col-lg-3">
        <?php
        the_post();
        $award        = get_the_terms( get_the_ID(), 'award' );
        $years        = get_the_terms( get_the_ID(), 'award-year' );
        $categories   = get_the_terms( get_the_ID(), 'award-category' );
        $organization = get_field( 'organization' );
        ?>
        <article class="honouree-grid-item">
          <div class="thumb">
            <?php echo get_svg( 'boulder', 'boulder' ); ?>
            <?php
            if ( has_post_thumbnail() ) {
              echo get_the_post_thumbnail( null, 'archive-honouree-thumb', [] );
            }
            ?>
            <?php if ( isset( $award[0] ) ) : ?>
              <span class="badge badge-pill badge-<?php echo $award[0]->slug; ?>"><?php echo $award[0]->name ?: ''; ?></span>
            <?php endif; ?>
          </div>
          <div class="body">
            <?php if ( isset( $years[0] ) ) : ?>
              <div class="year h6"><?php echo $years[0]->name ?: ''; ?></div>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>" class="stretched-link">
              <?php the_title( '<h2 class="title h5">', '</h1>' ); ?>
            </a>
            <?php if ( isset( $organization ) ) : ?>
              <div class="organization h6"><?php echo $organization; ?></div>
            <?php endif; ?>
            <?php if ( isset( $years[0] ) ) : ?>
              <div class="category h6"><?php echo $categories[0]->name ?: ''; ?></div>
            <?php endif; ?>
          </div>
        </article>
      </div>
    <?php endwhile; ?>
  </div>
<?php endif; ?>
