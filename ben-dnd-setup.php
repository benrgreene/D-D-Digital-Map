<?php

/*
  Plugin Name: DND Maps for WordPress
  Description: Create DND maps and control campaigns via WordPress
  Version: 0.1
  Author: Ben Greene
  Author URI: www.benrgreene.com
*/

include 'includes/class-brg-tile-parts.php';
include 'includes/class-brg-map-file-parser.php';
include 'includes/class-brg-meta-data-setup.php';

BRG_DND_Meta_Setup::get_instance();

/** Register the campaign post type for the plugin: */
add_action( 'init', 'brg_create_campaign_cpt' );
function brg_create_campaign_cpt() {
  register_post_type( 'brg-dnd-campaign', array(
    'labels'      => array(
      'name'            => 'Campaigns',
      'singular_name'   => 'Campaign',
    ),
    'rewrite'     => array(
      'slug'            => 'dnd-campaign',
    ),
    'menu_icon'   => 'dashicons-location-alt',
    'public'      => true,
  ) );
}


/** Enqueue styles (in case we need them) */ 
add_action( 'wp_enqueue_scripts', 'brg_add_map_styling' );
function brg_add_map_styling() {
  global $base_image_dir;

  if( is_single() || 'brg-dnd-campaign' == get_post_type() ) {
    wp_enqueue_style( 'map-base-style', plugins_url() . '/ben-dnd/styles/style.css' );

    wp_enqueue_script( 'map-script', plugins_url() . '/ben-dnd/js/players.js' );

    wp_localize_script( 'map-script', 'dnd_info', array(
      'image_path' => $base_image_dir,
    ) );
  }
}

/** Display the map in place of the content */
add_filter( 'the_content', 'brg_display_map_single' );
function brg_display_map_single( $content ) {
  if( ! is_single() || 'brg-dnd-campaign' !== get_post_type() ) {
    return $content;
  }
  
  $map_parser = new MapFileParser();
  $content = $map_parser->display_map();
  return $content;
}
