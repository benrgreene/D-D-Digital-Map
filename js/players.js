let all_players = [];
// Last player clicked (listen for moving)
let player_listener;
// All the map cells
let map_cells;

// Add listener for adding players to the board
// TODO: these should also have visible buttons
document.addEventListener( 'keydown', function( event ) {
  const key_id = event.keyCode;
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
  loadPlayers();
});

/**
 * Attempt to load player data
 */
function loadPlayers() {
  fetch( dnd_info.endpoint + 'load-game?map_id=' + 
    dnd_info.map_id + '&dm_id=' + dnd_info.dm_id, {
    method: 'get',
  }).then(function(response) {
    // TODO: figure out why I'm doing this.
    return response.json();
  })
  // Will return either true OR false (on failure)
  .then(function(data) {
    data = JSON.parse( data );
    // key is the player name, data[key] is the position data
    Object.keys(data).map( function( key ) {
      var left_pos = data[key].left / 50;
      var top_pos  = data[key].top / 50;
      add_player(key, { 'x': left_pos, 'y': top_pos });
    });

    if( false == data ) {
      alert( 'There was an issue saving the game! Please check your connection.' );
    }
  });
}

// Add a player to the board
function add_player(player_name=false, new_location=false) {
  reset_all_player_classes(); 
  // Need the player name first
  if( false === player_name ) {
    player_name = prompt( 'Please add the player\'s name:' , '' );
  }

  // Add the player element
  player_listener = document.createElement('div');
  player_listener.className = 'player';
  player_listener.textContent = player_name;
  document.body.appendChild( player_listener );
  
  // Listen for when the user clicks on the player
  player_listener.addEventListener( 'click', set_player_to_move );
  player_listener.classList.add('current-player');
  all_players.push( player_listener );
  if( false === new_location ) {
    move_player({ 'x': 0, 'y': 0 });
  } else {
    move_player(new_location);
  }
}

// Keep track of the last player clicked
function set_player_to_move( event ) {
  reset_all_player_classes();
  let player = event.toElement;
  player_listener = player;
  player_listener.classList.add('current-player');
}

// Move the player to the clicked cell
function update_player_position( cell_clicked  ) {
  let cell_position = get_cell_position( cell_clicked );
  move_player( cell_position );
  save_game();
}

// Want to track the position of the last clicked cell
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

/**
 * Save the game state
 */
function save_game() {
  var campaign_data = gather_campaign_data();
  fetch( dnd_info.endpoint + 'save-game', {
    method: 'post',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
    },
    body: JSON.stringify({
      map_id: dnd_info.map_id,
      dm_id: dnd_info.dm_id,
      map_data: campaign_data,
    }),
  }).then(function(response) {
    return response.json();
  })
  // Will return either true OR false (on failure)
  .then(function(data) {
    if( false == data ) {
      alert( 'There was an issue saving the game! Please check your connection.' );
    }
  });
}

// Reset all players to not have the 'current-player' class
function reset_all_player_classes() {
  all_players.forEach(function(player) {
    player.classList.remove('current-player');
  });
}

/**
 * Want to gather all the player data (names + positions) to save. 
 * Returns a stringified version of the JSON object
 */
function gather_campaign_data() {
  // bail early if there are no players to save
  if(0 == all_players.length) {
    return false;
  }

  let players = {};
  all_players.forEach( function( player ) {
    let left = player.style.left.replace('px', '');
    let top  = player.style.top.replace('px', '');
    players[player.innerText] = {
      'left': left,
      'top': top
    };
  });
  return JSON.stringify(players);
}