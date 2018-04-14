<?php

$nonce_name = 'brg-campaign-nonce';
$nonce_action = 'brg-campaign-add-file';

class BRG_DND_Meta_Setup {

  public static $instance;
  
  public static function get_instance() {
    if( null == self::$instance) {
      self::$instance = new BRG_DND_Meta_Setup();
    }
    return self::$instance;
  }

  private function __construct() {
    add_action( 'add_meta_boxes', array( $this, 'brg_add_file_upload' ) );
    add_action( 'save_post', array( $this, 'brg_save_map_file' ) );
    add_action( 'post_edit_form_tag', array( $this, 'update_edit_form' ) );
  }

  // Add file upload (contains map of the )
  function brg_add_file_upload() {
    add_meta_box(
      'campaign_map_file',
      'Map File',
      array( $this, 'brg_add_file_callback' ),
      'brg-dnd-campaign',
      'side'
    );
  }

  // Callback for the map file meta box
  function brg_add_file_callback() {
    include plugin_dir_path( __DIR__ ) . '/templates/file-upload-metabox.php';
  }

  /** Save the file upload */
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
      wp_die( 'There was a nuclear catastrophe uploading your file. Soz m8.' );
    } else {
      add_post_meta( $id, 'campaign_map_file', $upload );
      update_post_meta( $id, 'campaign_map_file', $upload );     
    }
  }

  function update_edit_form() {
    echo ' enctype="multipart/form-data"';
  } 
}