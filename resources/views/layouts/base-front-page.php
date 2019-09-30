<?php
/**
 * Filename base-front-page.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use Toi\ToiBox\Templates;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<?php get_template_part( 'views/partials/head' ); ?>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
do_action( 'get_header' );
get_template_part( 'views/partials/header' );
?>
<div class="site-content" role="document">
  <main class="main">
    <?php include Templates\get_main(); ?>
  </main>
</div>
<?php
get_template_part( 'views/partials/footer-sponsor' );
get_template_part( 'views/partials/footer' );
do_action( 'get_footer' );
wp_footer();
?>
</body>
</html>
