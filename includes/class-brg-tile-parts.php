<?php

/**
  Contains all the different tile pieces
*/

class BRGMapTileParts {

  public static $instance;

  public static $types = array(
    'default' => array(
      'can_walk' => true,
      'color'    => '#CFC'
    ),
    'beach' => array(
      'can_walk' => true,
      'color'    => '#956E66'
    ),
    'sea' => array(
      'can_walk' => false,
      'color'    => '#119',
    ),
    'path' => array(
      'can_walk' => false,
      'color'    => '#771',
    ),
    'wall' => array(
      'can_walk' => false,
      'color'    => '#AAA',
    ),
    'building' => array(
      'can_walk' => false,
      'color'    => '#111',
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
    add_action( 'init', array( $this, 'set_external_tile_parts' ) );
  }

  public function set_external_tile_parts() {
    self::$types = apply_filters( 'brg_add_tile_parts', self::$types );
  }
}

BRGMapTileParts::get_instance();