<?php global $nonce_action; ?>
<?php global $nonce_name; ?>
<?php wp_nonce_field( $nonce_action, $nonce_name ); ?>
<?php if( get_post_meta( get_the_ID(), 'campaign_map_file', true ) ):
  $meta = get_post_meta( get_the_ID(), 'campaign_map_file', true );
  $file = $meta['file']; 
  $file = substr( $file, strrpos( $file, '/' ) + 1 ); ?>
  <p><b>Map File</b>: <a href="<?php echo $meta['url']; ?>"><?php
    echo $file;
  ?></p>
<?php endif; ?>
<p class="description">Upload your Map File</p>
<input type="file" id="campaign_map_file" name="campaign_map_file" value="" size="25" />