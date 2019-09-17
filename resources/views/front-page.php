<?php
/**
 * Filename front-page.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */
if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();
    the_content();
  endwhile;
endif;
