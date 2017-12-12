// Last player clicked (listen for moving)
let player_listener;
// All the map cells
let map_cells;

// Add listener for adding players to the board
document.addEventListener( 'keydown', function( event ) {
  const key_id = event.keyCode;
  console.log(key_id);
  if( 13 == key_id ) {
    add_player();
  }
  else if( 8 == key_id ) {
    delete_player();
  }
});

// Listeners for the map cells (used for moving the player)
document.addEventListener( 'DOMContentLoaded', function( event ) {
  map_cells = document.querySelectorAll( '.map_cell' );
  map_cells.forEach( function( cell ) {
    cell.addEventListener( 'click', update_player_position );
  });
});

// Add a player to the board
function add_player() {
  // Need the player name first
  let player_name = prompt( 'Please add the player\'s name:' , '' );

  player_listener = document.createElement('div');
  player_listener.className = 'player';
  player_listener.textContent = player_name;
  document.body.appendChild( player_listener );
  
  player_listener.addEventListener( 'click', set_player_to_move );

  move_player({ 'x': 0, 'y': 0 });
}

// Keep track of the last player clicked
function set_player_to_move( event ) {
  let player = event.toElement;
  player_listener = player;
}

// Move the player to the clicked cell
function update_player_position( cell_clicked  ) {
  let cell_position = get_cell_position( cell_clicked );
  move_player( cell_position );
}

// When a cell is clicked, get its location
function get_cell_position( event ) {
  cell_clicked = event.toElement;
  let position = {
    'x': cell_clicked.getAttribute( 'data-row' ),
    'y': cell_clicked.getAttribute( 'data-col' ),
  };
  return position;
}

// Move the player to new position
function move_player( location ) { 
  player_listener.style.top  = location.y * 50 + 'px';
  player_listener.style.left = location.x * 50 + 'px';
}

// remove player from the board
function delete_player() {
  player_listener.parentNode.removeChild( player_listener );
}