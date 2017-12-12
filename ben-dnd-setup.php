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

$nonce_name   = 'brg-campaign-nonce';
$nonce_action = 'brg-campaign-add-file';

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

// Add file upload (contains map of the )
add_action( 'add_meta_boxes', 'brg_add_file_upload' );
function brg_add_file_upload() {
  add_meta_box(
    'campaign_map_file',
    'Map File',
    'brg_add_file_callback',
    'brg-dnd-campaign',
    'side'
  );
}

// Callback for the map file meta box
function brg_add_file_callback() {
  global $nonce_action;
  global $nonce_name;
  include 'templates/file-upload-metabox.php';
}

/** Save the file upload */
add_action('save_post', 'brg_save_map_file');
function brg_save_map_file( $id ) {
  global $nonce_action;
  global $nonce_name;

  // Security checks
  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
      //! wp_verify_nonce( $_POST[$nonce_name], $nonce_action ) ||
      ! current_user_can('edit_page', $id ) ) {
    // TODO: fix nonce not working
    return $id;
  }

  // Get upload info - attempt the upload
  $attachment = $_FILES['campaign_map_file'];
  $upload = wp_upload_bits( $attachment['name'], null, file_get_contents( $attachment['tmp_name'] ) );

  // Check if the file was succesfully uploaded
  if( isset( $upload['error'] ) && 0 != $upload['error'] ) {
    error_log('dying');
    wp_die( 'There was a nuclear catastrophe uploading your file. Soz m8.' );
  } else {
    error_log($id);
    add_post_meta( $id, 'campaign_map_file', $upload );
    update_post_meta( $id, 'campaign_map_file', $upload );     
  }
}

function update_edit_form() {
  echo ' enctype="multipart/form-data"';
} 
add_action('post_edit_form_tag', 'update_edit_form');


/** Enqueue styles (in case we need them) */ 
add_action( 'wp_enqueue_scripts', 'brg_add_map_styling' );
function brg_add_map_styling() {
  if( is_single() || 'brg-dnd-campaign' == get_post_type() ) {
    wp_enqueue_style( 'map-base-style', plugins_url() . '/ben-dnd/styles/style.css' );

    wp_enqueue_script( 'map-script', plugins_url() . '/ben-dnd/js/players.js' );
  }
}

/** Need to display the content now */
add_filter( 'the_content', 'brg_display_map_single' );
function brg_display_map_single( $content ) {
  if( ! is_single() || 'brg-dnd-campaign' !== get_post_type() ) {
    return $content;
  }
  
  $map_parser = new MapFileParser();
  $content = $map_parser->display_map();
  return $content;
}
