<?php

class MapFileParser {

  private $cell_delimiter = ',';
  private $map_array = array();
  private $tile_parts;

  // Load the file
  public function __construct() {
    $this->tile_parts = BRGMapTileParts::get_instance();

    $map_info = get_post_meta( get_the_ID(), 'campaign_map_file', true );
    $map_file = $map_info['file'];
    $reader   = fopen( $map_file, "r" );
    $row_on = 0;
    
    // go thorugh line by line
    while( false !== ( $line = fgets( $reader ) ) ) {
      $parts = explode( $this->cell_delimiter, $line );
      foreach( $parts as $key => $value ) {
        $this->map_array[$row_on][] = $this->make_map_cell( trim( $value ) );
      }
      $row_on++;
    }

    fclose( $reader );

    // need to rotate the array 90 degrees (since grid treats row/col different then my brain)
    array_unshift($this->map_array, null);
    $this->map_array = call_user_func_array('array_map', $this->map_array);
  }

  // Create an array of map info 
  public function make_map_cell( $cell_info ) {
    if( '' !== $cell_info && null != $cell_info ) {
      // Allow extra text after cell type
      $key = explode(' ', $cell_info)[0];
      $key = trim( $key );
      
      return array(
        'text'  => $cell_info,
        'color' => $this->tile_parts::$types[$key]['color'],
      );
    }
    return array(
      'text'  => '&nbsp;',
      'color' => $this->tile_parts::$types['default']['color'],
    );
  }
  
  public function display_map() {
    ob_start();
    include plugin_dir_path( __DIR__ ) . 'templates/map.php';
    return ob_get_clean();
  }
}