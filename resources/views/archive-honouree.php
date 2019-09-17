<?php
/**
 * Filename archives-honouree.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

$title   = get_theme_mod( '_toibox_honouree_archive_title' );
$content = get_theme_mod( '_toibox_honouree_archive_content' );

$awards           = get_terms( [
  'taxonomy'   => 'award',
  'hide_empty' => false,
] );
$award_years      = get_terms( [
  'taxonomy'   => 'award-year',
  'hide_empty' => false,
] );
$award_categories = get_terms( [
  'taxonomy'   => 'award-category',
  'hide_empty' => false,
] );
?>

<?php printf( '<h1>%s</h1>', $title ); ?>
<?php echo apply_filters( 'the_content', $content ); ?>

<form>
  <div class="form-row">
    <div class="form-group col-auto">
      <label for="award"><?php _ex( 'Select an award', '', '' ); ?></label>
      <select class="form-control" id="award">
        <option value=""><?php _ex( 'All awards', '', '' ); ?></option>
        <?php foreach ( $awards as $award ) : ?>
          <?php printf( '<option value="%s" %s>%s', $award->term_id, selected( $award->term_id, true, false ), $award->name ); ?>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group col-auto">
      <label for="award-year"><?php _ex( 'Select a year', '', '' ); ?></label>
      <select class="form-control" id="award-year">
        <option value=""><?php _ex( 'All years', '', '' ); ?></option>
        <?php foreach ( $award_years as $year ) : ?>
          <?php printf( '<option value="%s" %s>%s', $year->term_id, selected( $year->term_id, true, false ), $year->name ); ?>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group col-auto">
      <label for="award-category"><?php _ex( 'Select a category', '', '' ); ?></label>
      <select class="form-control" id="award-category">
        <option value=""><?php _ex( 'All categories', '', '' ); ?></option>
        <?php foreach ( $award_categories as $category ) : ?>
          <?php printf( '<option value="%s" %s>%s', $category->term_id, selected( $category->term_id, true, false ), $category->name ); ?>
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
  <div class="row">
    <?php while ( have_posts() ) : ?>
      <div class="col col-md-4 col-lg-3">
        <?php the_post(); ?>
        <?php
        if ( has_post_thumbnail() ) {
          echo get_the_post_thumbnail( null, 'block-articles', [] );
        }
        ?>
        <?php the_title( '<h1>', '</h1>' ); ?>
      </div>
    <?php endwhile; ?>
  </div>
<?php endif; ?>
