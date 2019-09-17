<?php
/**
 * Filename header.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

use IndigoTree\BootstrapNavWalker\Four\WalkerNavMenu;
use Toi\ToiBox\Assets;

?>
<header class="site-header">
  <div class="boulders">
    <div class="container">
      <?php echo Assets\get_svg( 'boulder', 'boulder-1' ); ?>
      <?php echo Assets\get_svg( 'boulder', 'boulder-2' ); ?>
    </div>
  </div>
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light pl-0">
      <a class="navbar-brand" href="<?php echo home_url( '/' ); ?>">
        <img src="<?php echo Assets\get_url( 'svg/logo/clean50.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
        <span class="sr-only"><?php bloginfo( 'name' ); ?></span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-primary" aria-controls="navbar-primary" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbar-primary">
        <?php if ( has_nav_menu( 'primary' ) ) : ?>
          <?php
          wp_nav_menu( [
            'container'      => '',
            'theme_location' => 'primary',
            'menu_class'     => 'menu navbar-nav',
            'walker'         => new WalkerNavMenu(),
          ] );
          ?>
        <?php endif; ?>

        <!--
        <div class="form-inline my-2 my-lg-0">
          <?php
        get_search_form( [
          'aria_label' => 'Sitewide'
        ] );
        ?>
        </div>
        -->
      </div>
    </nav>

  </div>
</header>
