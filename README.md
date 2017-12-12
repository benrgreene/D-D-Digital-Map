# D&D Digital Map Plugin

**Author:** Ben Greene

**License:** MIT

This is a plugin that's meant to help Dungeon Masters with game control by the use of digital maps. I've tried using paper (poster) and pen, but it gets unwieldy and clustered. I lose where all the different map parts are, they take up more table space than I have, etc. 

So I decided to make a plugin that takes care of displaying maps, and keeping track of where users are in the world.

## Creating Campaigns

To create a campaign, you'll need a CSV file that is used to create the map grid. I used Google Drive to create the map (it has a download as CSV option). For each table cell, enter in the type of block the cell should be (tree, building, sea, etc). Then when you import the file, the plugin will know how to construct the grid. 

Obviously, the size of each cell (for the world) can vary, and there is no set size or scale.

## Grid Cell Types

There are a couple cell types, with more on their way (eventually, that's lower on the list of things to add):

* Nothing: you can have nothing in the cell, and it's treated as grassland.
* Beach: walkable terrain
* Sea: impassable terrain
* Path: walkable terrain
* Building: a building that players can enter, how you use them, meh.
* Wall: impassable terrain (unless gear allows)
* Rock: impassable terrain (unless gear allows)

At the moment, there's no texture for these; they are simply solid color. That's on the list of things to change. 

## List of Hooks and Filters

Filters: 

* brg_add_tile_parts: filter for setting the different types of terrain. Array of tile arrays, each containing a name and color. 


## Help

There are two ways to help. First, this plugin is still in development, so feel free to request features using GitHub's issues feature. 

Since it's in development, it's also prone to change, meaning if things are broken or just look like code barf, come back soon. Maybe I won't have gotten sidetracked and will have done some updates. 

Second, it's already open for modding. Yes, I am following proper WordPress plugin development standards, and have started adding hooks and filters (listed above), so get modding!