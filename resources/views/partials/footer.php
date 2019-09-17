<?php
/**
 * Filename footer.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

use IndigoTree\BootstrapNavWalker\Four\WalkerNavMenu;

?>
<footer class="site-footer">
  <div class="container">
    <div class="row flex-xl-row-reverse justify-content-center justify-content-xl-between">
      <div class="col-12 col-xl-auto">
        <?php if ( has_nav_menu( 'primary' ) ) : ?>
          <?php
          wp_nav_menu( [
            'container'      => '',
            'theme_location' => 'primary',
            'menu_class'     => 'menu nav justify-content-center justify-content-xl-end mb-3 mb-xl-0',
            'walker'         => new WalkerNavMenu(),
          ] );
          ?>
        <?php endif; ?>
      </div>
      <div class="col-12 col-xl-auto">
        <div class="copyright text-center">
          <?php echo do_shortcode( get_theme_mod( '_toibox_copyright' ) ); ?>
        </div>
      </div>

    </div>
  </div>
</footer>
