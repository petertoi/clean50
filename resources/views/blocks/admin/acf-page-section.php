<?php
/**
 * Filename acf-page-section.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use function Toi\ToiBox\Assets\get_svg;

$collapsible = get_field( 'collapsible' );
$initial_state = get_field( 'initial_state' );
$title         = get_field( 'title' );
$content       = get_field( 'content' );
?>

<?php if ( $title ) : ?>
  <h2 class="h3"><?php echo $title; ?></h2>
<?php endif; ?>
<?php echo $content;
