<?php
/**
 * Filename custom-contact.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 *
 * Template Name: Contact
 */

?>
<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : ?>
    <?php the_post(); ?>

    <div class="container-fluid">
      <div class="row justify-content-around">
        <div class="col-md-8">
          <?php the_title( '<h1>', '</h1>' ); ?>
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>


