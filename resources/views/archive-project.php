<?php
/**
 * Filename archive-project.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

$title   = get_theme_mod( '_toibox_project_archive_title' );
$content = get_theme_mod( '_toibox_project_archive_content' );
?>

<?php printf( '<h1>%s</h1>', $title ); ?>
<?php echo apply_filters( 'the_content', $content ); ?>

<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : ?>
    <?php the_post(); ?>
    <?php the_title( '<h1>', '</h1>' ); ?>
  <?php endwhile; ?>
<?php endif; ?>
