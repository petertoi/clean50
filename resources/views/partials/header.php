<?php
/**
 * Filename header.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */

use IndigoTree\BootstrapNavWalker\Four\WalkerNavMenu;
use Toi\ToiBox\Assets;
use function Toi\ToiBox\Snippets\cached_menu;

?>
<header class="site-header">
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light pl-0">
      <a class="navbar-brand" href="<?php echo home_url( '/' ); ?>">
        <img src="<?php echo Assets\get_url( 'svg/logo/clean50.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
        <span class="sr-only"><?php bloginfo( 'name' ); ?></span>
      </a>

      <button class="navbar-toggler hamburger hamburger--spin" type="button" data-toggle="collapse" data-target="#navbar-primary" aria-controls="navbar-primary" aria-expanded="false" aria-label="Toggle navigation">
        <span class="hamburger-box">
          <span class="hamburger-inner"></span>
        </span>
      </button>

      <div class="collapse navbar-collapse justify-content-end text-right" id="navbar-primary">
        <?php
        cached_menu( 'primary', [
          'container'  => '',
          'menu_class' => 'menu navbar-nav',
          'walker'     => new WalkerNavMenu(),
        ] );
        ?>
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
  <div class="boulders">
    <?php echo Assets\get_svg( 'boulder', 'boulder-1' ); ?>
    <?php echo Assets\get_svg( 'boulder', 'boulder-2' ); ?>
  </div>
</header>
