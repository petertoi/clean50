<?php
/**
 * Filename home.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Snippets\bootstrap_pagination;

$title          = get_theme_mod( '_toibox_article_archive_title' ) ?: '';
$content        = get_theme_mod( '_toibox_article_archive_content' ) ?: '';
$fallback_image = get_theme_mod( '_toibox_article_archive_fallback_article_image' );

$award_years = get_terms( [
  'taxonomy'   => 'award-year',
  'order'      => 'DESC',
  'hide_empty' => false,
] );

$selected_year = filter_input( INPUT_GET, 'year', FILTER_SANITIZE_STRING ) ?: '';

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
      <div class="form-group col-2">
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
        <label for="search-articles"><?php _ex( 'Search articles', '', '' ); ?></label>
        <input type="search" class="form-control" id="search-articles" name="s" value="<?php the_search_query(); ?>">
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
    <div class="article-grid row">
      <?php while ( have_posts() ) : ?>
        <div class="col-12 col-md-6 mb-4">
          <?php
          the_post();
          ?>
          <article class="article-grid-item">
            <div class="thumb">
              <?php
              if ( has_post_thumbnail() ) {
                echo get_the_post_thumbnail( null, 'banner-lg-6', [ 'class' => 'img-fluid rounded' ] );
              } elseif ( $fallback_image ) {
                echo wp_get_attachment_image( $fallback_image, 'banner-lg-6', false,
                  [ 'class' => 'img-fluid rounded' ] );
              }
              ?>
            </div>
            <div class="body">
              <div class="year h6"><?php echo get_the_date(); ?></div>
              <a href="<?php the_permalink(); ?>" class="stretched-link">
                <?php the_title( '<h2 class="h3 title">', '</h1>' ); ?>
              </a>
              <div class="excerpt">
                <?php the_excerpt(); ?>
              </div>
            </div>
        </div>
      <?php endwhile; ?>
    </div>

    <nav class="d-flex justify-content-center" aria-label="<?php _ex( 'Article archive pagination', '', '' ); ?>">
      <?php bootstrap_pagination( null, [
        'prev_text' => '&lt;<span class="sr-only">' . _x( 'Previous', '', '' ) . '</span>',
        'next_text' => '&gt;<span class="sr-only">' . _x( 'Next', '', '' ) . '</span>',
      ] ); ?>
    </nav>

  <?php endif; ?>
</div>
