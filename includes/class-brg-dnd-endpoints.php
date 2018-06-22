<?php

class BRG_DND_Endpoints {

  public static $instance;

  public static function get_instance() {
    if( null == self::$instance) {
      self::$instance = new BRG_DND_Endpoints();
    }
    return self::$instance;
  }

  private function __construct() {
    $this->db_manager = BRG_DND_Database::get_instance();
    add_action( 'rest_api_init', array( $this, 'add_rest_endpoints' ) );
  }

  // Register all the REST endpoints the scripts will need
  public function add_rest_endpoints() {
    register_rest_route( 'dnd', '/save-game', array(
      'methods'  => 'POST',
      'callback' => array( $this, 'save_game' )
    ) );

    register_rest_route( 'dnd', '/load-game', array(
      'methods'  => 'GET',
      'callback' => array( $this, 'load_game' )
    ) );
  }

  /**
   * Save a game
   */
  public function save_game( WP_Rest_Request $request ) {
    $body_params = $request->get_body();
    $body        = json_decode( $body_params, true );

    if( empty( $body['dm_id'] ) ||
        empty( $body['map_id'] ) ) {
      return false;
    }

    $dm_id    = $body['dm_id'];
    $map_id   = $body['map_id'];
    $map_data = $body['map_data'];

    // ....... this should actually return if the campaign was saved.
    $this->db_manager->save_campaign( $dm_id, $map_id, $map_data );
    return true;
  }

  public function load_game( WP_Rest_Request $request ) {
    $dm_id  = $request->get_param( 'dm_id' );
    $map_id = $request->get_param( 'map_id' );
    $data   = $this->db_manager->load_campaign( $dm_id, $map_id );

    return $data;
  }
}