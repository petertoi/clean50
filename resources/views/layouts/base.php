<?php
/**
 * Filename base.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

use Toi\ToiBox\Templates;
use function Toi\ToiBox\Snippets\breadcrumbs;

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
  <?php if ( ! is_archive() ) : ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-auto">
          <?php breadcrumbs(); ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <main class="main">
    <?php include Templates\get_main(); ?>
  </main>

</div>
</div>
<?php
get_template_part( 'views/partials/footer-sponsor' );
get_template_part( 'views/partials/footer' );
do_action( 'get_footer' );
wp_footer();
?>
</body>
</html>
