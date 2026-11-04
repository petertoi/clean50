<?php
/**
 * Filename core-gallery.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 *
 * @global $block
 *
 */
$ids = [];
if( !empty($block['innerBlocks'])) {
  foreach( $block['innerBlocks'] as $innerBlock ) {
    if( !empty($innerBlock['attrs']['id'])) {
      $ids[] = $innerBlock['attrs']['id'];
    }
  }
}
?>

<?php if ( ! empty( $ids ) ) : ?>
  <?php $lightbox_id = uniqid(); ?>
  <div class="wp-block-gallery">
    <div class="js-block-gallery">
      <?php foreach ( $ids as $id ) : ?>
        <div class="gallery__image">
          <a
            data-lightbox="<?php echo esc_attr( $lightbox_id ); ?>"
            data-title="<?php echo wp_get_attachment_caption( $id ); ?>"
            href="<?php echo wp_get_attachment_image_url( $id, 'full' ); ?>"
          >
            <?php echo wp_get_attachment_image( $id, 'square-lg-4', false, [ 'class' => 'img-fluid' ] ); ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif;
