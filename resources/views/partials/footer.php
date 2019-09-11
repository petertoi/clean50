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
    <div class="row justify-content-between">
      <div class="col-auto">
        <?php echo do_shortcode( get_theme_mod( '_toibox_copyright' ) ); ?>
      </div>
      <div class="col-auto">
        <?php if ( has_nav_menu( 'primary' ) ) : ?>
          <?php
          wp_nav_menu( [
            'container'      => '',
            'theme_location' => 'primary',
            'menu_class'     => 'menu mr-auto',
            'walker'         => new WalkerNavMenu(),
          ] );
          ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</footer>
