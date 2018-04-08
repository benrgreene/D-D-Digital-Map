<?php $tile_manager = BRGMapTileParts::get_instance(); ?>
<?php $tile_manager->the_tile_styles( count( $this->map_array ), count( $this->map_array[0] ) ); ?>

<div class="map">
  <?php foreach( $this->map_array as $row_index => $row ): ?>
    <div class="map_row row-<?php echo $row_index; ?>">
      <?php foreach( $row as $col_index => $cell ): ?>
        <div class="map_cell cell-<?php echo $row_index; ?>-<?php echo $col_index; ?> tile-type__<?php echo $cell['type']; ?>" data-row="<?php echo $row_index; ?>" data-col="<?php echo $col_index; ?>">
          &nbsp;
        </div>
      <?php endforeach; ?>    
    </div>
  <?php endforeach; ?>
</div>