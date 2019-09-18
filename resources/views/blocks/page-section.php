<?php
/**
 * Filename content-section.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

global $block;

$collapsible   = get_field( 'collapsible' );
$initial_state = get_field( 'initial_state' );
$title         = get_field( 'title' );
$content       = get_field( 'content' );

if ( $collapsible ) {
  $title   = sprintf( '<a class="toggle" href="#%1$s" data-toggle="collapse" role="button" aria-expanded="%3$s" aria-controls="%1$s">%2$s</a>', sanitize_title_with_dashes( $title ), $title, 'open' === $initial_state ? 'true' : 'false' );
  $content = sprintf( '<div class="collapse %s" id="%s">%s</div>', ( 'open' === $initial_state ) ? 'show' : '', sanitize_title_with_dashes( $title ), $content );
}


?>

<?php if ( $title ) : ?>
  <h2 class="h3"><?php echo $title; ?></h2>
<?php endif; ?>
<?php echo $content;
