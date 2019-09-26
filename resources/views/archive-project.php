<?php
/**
 * Filename archive-project.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Snippets\bootstrap_pagination;

$title           = get_theme_mod( '_toibox_project_archive_title' );
$content         = get_theme_mod( '_toibox_project_archive_content' );
$fallback_banner = get_theme_mod( '_toibox_project_archive_fallback_project_image' );

$award_years = get_terms( [
  'taxonomy'   => 'award-year',
  'order'      => 'DESC',
  'hide_empty' => false,
] );
?>

<?php printf( '<h1>%s</h1>', $title ); ?>
<?php echo apply_filters( 'the_content', $content ); ?>

<form method="get">
  <div class="row">
    <div class="form-group col-auto">
      <label for="award-year"><?php _ex( 'Select a year', '', '' ); ?></label>
      <select class="form-control" id="award-year" name="year">
        <option value=""><?php _ex( 'All years', '', '' ); ?></option>
        <?php foreach ( $award_years as $year ) : ?>
          <?php
          printf( '<option value="%s" %s>%s',
            $year->slug,
            selected( $year->slug, filter_input( INPUT_GET, 'award-year', FILTER_SANITIZE_STRING ), false ),
            $year->name );
          ?>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group col-auto">
      <label for="search-projects"><?php _ex( 'Search projects', '', '' ); ?></label>
      <input type="search" class="form-control" id="search-projects" name="s" value="<?php the_search_query(); ?>">
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
  <div class="project-grid row">
    <?php while ( have_posts() ) : ?>
      <div class="col col-md-6">
        <?php
        the_post();
        $leads = get_field( 'project-leaders' );
        ?>
        <article class="project-grid-item">
          <div class="thumb">
            <?php
            if ( has_post_thumbnail() ) {
              echo get_the_post_thumbnail( null, 'banner-lg-6', [ 'class' => 'img-fluid' ] );
            } else if ( $fallback_banner ) {
              echo wp_get_attachment_image( $fallback_banner, 'banner-lg-6', [ 'class' => 'img-fluid' ] );
            }
            ?>
          </div>
          <div class="body">
            <a href="<?php the_permalink(); ?>" class="stretched-link">
              <?php the_title( '<h2 class="h3 title">', '</h1>' ); ?>
            </a>
            <div class="excerpt">
              <?php the_excerpt(); ?>
            </div>
            <div class="project-leads">
              <?php echo $leads; ?>
            </div>
          </div>
      </div>
    <?php endwhile; ?>
  </div>

  <nav class="d-flex justify-content-center" aria-label="<?php _ex( 'Project archive pagination', '', '' ); ?>">
    <?php bootstrap_pagination( null, [
      'prev_text' => '&lt;<span class="sr-only">' . _x( 'Previous', '', '' ) . '</span>',
      'next_text' => '&gt;<span class="sr-only">' . _x( 'Next', '', '' ) . '</span>',
    ] ); ?>
  </nav>

<?php endif; ?>
