<?php
/**
 * Filename base.php
 *
 * @package Toi\ToiBox
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
<div class="container" role="document">
  <div class="site-content row">
    <main class="main">
      <?php include Templates\get_main(); ?>
    </main>
    <?php if ( Templates\has_sidebar() ) : ?>
      <aside class="sidebar">
        <?php include Templates\get_sidebar(); ?>
      </aside>
    <?php endif; ?>
  </div>
</div>
<?php
do_action( 'get_footer' );
get_template_part( 'views/partials/footer' );
?>
<?php wp_footer(); ?>
</body>
</html>
