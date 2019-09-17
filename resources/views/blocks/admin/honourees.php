<?php
/**
 * Filename honourees.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use Toi\ToiBox\Sprite;
use function Toi\ToiBox\Snippets\render_button;

$tabs = get_field( 'tabs' );
?>
<div class="row">
  <div class="col">
    <ul class="nav nav-ruled justify-content-center" id="tab-content--honourees" role="tablist">
      <?php foreach ( $tabs as $key => $tab ) : ?>
        <li class="nav-item">
          <a class="nav-link <?php echo ( 0 === $key ) ? 'active' : ''; ?>" id="<?php echo sanitize_title_with_dashes( "tab-{$tab['label']}-{$key}" ); ?>" data-toggle="tab" href="#<?php echo sanitize_title_with_dashes( "{$tab['label']}-{$key}" ); ?>" role="tab" aria-controls="home" aria-selected="<?php echo ( 0 === $key ) ? 'true' : 'false'; ?>"><?php echo $tab['label']; ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
    <div class="tab-content" id="tab-content--honourees">
      <?php foreach ( $tabs as $key => $tab ) : ?>
        <div class="tab-pane fade <?php echo ( 0 === $key ) ? 'show active' : ''; ?>" id="<?php echo sanitize_title_with_dashes( "{$tab['label']}-{$key}" ); ?>" role="tabpanel" aria-labelledby="<?php echo sanitize_title_with_dashes( "tab-{$tab['label']}-{$key}" ); ?>">
          <div class="row">
            <div class="col">
              <div class="content__content">
                <?php echo $tab['content']; ?>
              </div>
              <div class="content__actions">
                <?php render_button( $tab['primary_link'], [ 'btn', 'btn-primary' ] ); ?>
                <?php render_button( $tab['secondary_link'], [ 'btn', 'btn-link' ] ); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
