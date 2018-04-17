<?php 

include "brg-database-manager.php";

class BRG_DND_Database extends Database_Table_Manager {

  public static $instance;
  public $tb_name = 'brg_dnd_saves';

  public static function get_instance() {
    if( null == self::$instance) {
      self::$instance = new BRG_DND_Database();
    }
    return self::$instance;
  }

  private function __construct() {
    $db_path = plugin_dir_path( __DIR__ ) . 'database.ini';
    $this->init_db( $db_path );
  }

  /**
   * Checks if there is a game associated with a DM/map,
   * if so, get said map data 
   */
  public function load_campaign( $dm, $map ) {
    if( $this->campagin_exists( $dm, $map ) ) {
      $results = $this->get_campaign( $dm, $map );
      $campaign = $results[0];
      return $campaign->player_data;
    } else {
      return false;
    }
  }

  /**
   * If there is a campaign with the given DM/map, update the
   * associated game info
   */
  public function save_campaign( $dm, $map, $map_json ) {
    if( $this->campagin_exists( $dm, $map ) ) {
      $this->update_campaign( $dm, $map, $map_json );
    } else {
      $this->add_campaign( $dm, $map, $map_json );
    }
  }

  /**
   * Check if there is a campaign that exists for the DM on a given map
   */
  function campagin_exists( $dm, $map ) {
    $campaign_results = $this->get_campaign( $dm, $map );
    return count( $campaign_results ) > 0;
  }

  /**
   * Add a new campaign to the DB
   */
  function add_campaign( $dm, $map, $map_json ) {
    global $wpdb;

    $sql = $wpdb->prepare( "INSERT INTO {$wpdb->prefix}{$this->tb_name} (player_id, map_id, player_data) VALUES (%d, %d, '%s')", array(
      $dm,
      $map,
      $map_json
    ) );

    $wpdb->query( $sql );
  }

  /**
   * Update a campaign's game data
   */
  function update_campaign( $dm, $map, $map_json ) {
    global $wpdb;

    $sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}{$this->tb_name} SET player_data=%s WHERE player_id=%d AND map_id=%d", array(
      $map_json,
      $dm,
      $map,
    ) );

    $wpdb->query( $sql );
  }

  /**
   * Pull a campaign from the database associated with the DM/map
   */
  function get_campaign( $dm, $map ) {
    global $wpdb;

    $sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$this->tb_name} WHERE player_id=%d AND map_id=%d", array(
      $dm,
      $map,
    ) );

    $results = $wpdb->get_results( $sql );
    return $results;
  }
}