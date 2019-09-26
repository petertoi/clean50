<?php
/**
 * Filename searchform.php
 *
 * @package Toi/ToiBox
 * @author  Peter Toi <peter@petertoi.com>
 */
?>
<form role="search" aria-label="<?php _e( 'Sitewide', 'label' ); ?>" method="get" class="" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <div class="input-group">
    <input type="search" class="form-control" placeholder="<?php esc_attr_e( 'Search &hellip;', 'placeholder' ); ?>" aria-label="<?php _e( 'Search for:', 'label' ); ?>" value="<?php the_search_query(); ?>" name="s">
    <div class="input-group-append">
      <button class="btn btn-outline-secondary" type="submit" id=""><?php echo esc_attr_x( 'Search', 'submit button' ); ?></button>
    </div>
  </div>
</form>
