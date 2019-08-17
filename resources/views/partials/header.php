<?php
/**
 * Filename header.php
 *
 * @package Toi\ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */
?>
<header class="site-header" role="banner">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="<?php home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php if ( has_nav_menu( 'primary' ) ) : ?>
          <?php
          wp_nav_menu( [
            'container'      => '',
            'theme_location' => 'primary',
            'menu_class'     => 'menu navbar-nav mr-auto',
            'walker'         => new \IndigoTree\BootstrapNavWalker\Four\WalkerNavMenu(),
          ] );
          ?>
        <?php endif; ?>

        <div class="form-inline my-2 my-lg-0">
          <?php
          get_search_form( [
            'aria_label' => 'Sitewide'
          ] );
          ?>
        </div>
      </div>
    </nav>

  </div>
</header>
