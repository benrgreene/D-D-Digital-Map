<style>
  .map {
    display: grid; 
    grid-template-columns: repeat(<?php echo count( $this->map_array[0] ); ?>, 50px );
    grid-template-rows: repeat(<?php echo count( $this->map_array ); ?>, 1fr );
  }
</style>
<?php error_log( count( $this->map_array ) . ' ' . count( $this->map_array[0] ) ); ?>
<div class="map">
  <?php foreach( $this->map_array as $row_index => $row ): ?>
    <div class="map_row row-<?php echo $row_index; ?>">
      <?php foreach( $row as $col_index => $cell ): ?>
        <div class="map_cell cell-<?php echo $row_index; ?>-<?php echo $col_index; ?>" data-row="<?php echo $row_index; ?>" data-col="<?php echo $col_index; ?>" >
          <?php echo $cell['text']; ?>
          <style>
            .cell-<?php echo $row_index; ?>-<?php echo $col_index; ?> {
              background-color: <?php echo $cell['color']; ?>
            }
          </style>
        </div>
      <?php endforeach; ?>    
    </div>
  <?php endforeach; ?>
</div>