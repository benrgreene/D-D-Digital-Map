<?php

/**
  Contains all the different tile pieces
*/

class BRGMapTileParts {

  public static $instance;

  public $types = array(
    'default' => array(
      'can_walk' => true,
      'image'    => 'grass.jpg',
      'color'    => '#CFC'
    ),
    'beach' => array(
      'can_walk' => true,
      'color'    => '#956E66',
      'image'    => 'sand.jpg',
    ),
    'sea' => array(
      'can_walk' => false,
      'color'    => '#119',
      'image'    => 'sea.jpg'
    ),
    'path' => array(
      'can_walk' => false,
      'color'    => '#771',
      'image'    => 'path.jpg',
    ),
    'wall' => array(
      'can_walk' => false,
      'color'    => '#AAA',
    ),
    'building' => array(
      'can_walk' => false,
      'color'    => '#111',
      'image'    => 'building.jpg',
    ),
    'tree' => array(
      'can_walk' => false,
      'color'    => '#191',
    ),
    'rock' => array(
      'can_walk' => false,
      'color'    => '#666',
    ),
  );

  public static function get_instance() {
    if( null == self::$instance) {
      self::$instance = new BRGMapTileParts();
    }
    return self::$instance;
  }

  /* Allow other themes/plugins chance to add any extra tile types they want */
  private function __construct() {
    // Want to allow devs to add their own image folder (easier to overwrite all the textures)
    $this->base_image_dir = apply_filters( 'brg/base_image_dir', plugin_dir_url( __DIR__ ) . 'images/'  );

    add_action( 'init', array( $this, 'set_external_tile_parts' ) );
  }

  public function set_external_tile_parts() {
    $this->types = apply_filters( 'brg_add_tile_parts', $this->types );
  }

  public function the_tile_styles( $cols, $rows ) { ?>
    <style>
    .map {
      display: grid; 
      grid-template-columns: repeat(<?php echo $cols; ?>, 50px );
      grid-template-rows: 100px repeat(<?php echo $rows; ?>, 1fr );
    }
    .map--controls {
      grid-column-start: 1;
      grid-column-end: <?php echo $rows; ?>;
      display: flex;
      align-items: center;
      background-color: #DDD;
    }
    <?php foreach( $this->types as $type => $info ): ?>
      .tile-type__<?php echo $type; ?> {
      background-color: <?php echo $info['color']; ?>;
      <?php if( isset( $info['image'] ) ): ?>
        background-image: url("<?php echo $this->base_image_dir . $info['image']; ?>");
      <?php endif; ?>
      }
    <?php endforeach; ?>
    </style>
  <?php }
}

BRGMapTileParts::get_instance();