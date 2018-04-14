<?php global $nonce_action; ?>
<?php global $nonce_name; ?>
<?php wp_nonce_field( $nonce_action, $nonce_name ); ?>
<p class="description">Upload your Map File</p>
<input type="file" id="campaign_map_file" name="campaign_map_file" value="" size="25" />
     