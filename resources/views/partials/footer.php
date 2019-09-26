<?php
/**
 * Filename footer.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

use IndigoTree\BootstrapNavWalker\Four\WalkerNavMenu;
use function Toi\ToiBox\Snippets\cached_menu;

?>
<footer class="site-footer">
  <div class="container-fluid">
    <div class="row flex-xl-row-reverse justify-content-center justify-content-xl-between">
      <div class="col-12 col-xl-auto">
        <?php
        cached_menu( 'footer', [
          'container'      => '',
          'menu_class'     => 'menu nav justify-content-center justify-content-xl-end mb-3 mb-xl-0',
          'walker'         => new WalkerNavMenu(),
        ] );
        ?>
      </div>
      <div class="col-12 col-xl-auto">
        <div class="copyright text-center">
          <?php echo do_shortcode( get_theme_mod( '_toibox_copyright' ) ); ?>
        </div>
      </div>
    </div>
  </div>
</footer>
