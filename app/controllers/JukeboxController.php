<?php

class JukeboxController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| The Default Controller
	|--------------------------------------------------------------------------
	|
	| Instead of using RESTful routes and anonymous functions, you might wish
	| to use controllers to organize your application API. You'll love them.
	|
	| This controller responds to URIs beginning with "home", and it also
	| serves as the default controller for the application, meaning it
	| handles requests to the root of the application.
	|
	| You can respond to GET requests to "/home/profile" like so:
	|
	|		public function action_profile()
	|		{
	|			return "This is your profile!";
	|		}
	|
	| Any extra segments are passed to the method as parameters:
	|
	|		public function action_profile($id)
	|		{
	|			return "This is the profile for user {$id}.";
	|		}
	|
	*/

	public function getIndex() {
            $songs = array();
            $last_played = LastPlayed::latest()->get();
            foreach ($last_played as $item) {
                $song = Song::find($item->song_id);
                $album = Album::find($song->album_id);
                $artist = Artist::find($album->artist_id);
                $songs[] = array($song->name, $artist->name, $album->name);
            }
	    //$songs = array(array('Bleeding Mascara', 'Atreyu', 'The Curse'),
	    //               array('Generator', 'Bad Religion', 'Against The Grain'),
	    //               array('Nugget', 'CAKE', 'Fashion Nugget'));
	    // Asset::add('style', 'css/style.css');
	    // Asset::add('jquery', 'js/jquery-1.9.1.js');
	    // Asset::add('jquery-ui', 'js/jquery-ui.js');
	    
	    $data = array('oldsongs' => array_slice($songs, 1),
                          'currentsong' => $songs[0]);
		return View::make('jukebox.index', $data);
	}

	public function getAdd($artist = '', $album = '', $song = '') {
	    // Asset::add('style', 'css/style.css');
	    // Asset::add('jquery', 'js/jquery-1.9.1.js');
	    // Asset::add('jquery-ui', 'js/jquery-ui.js');
	    
	    if($artist == '') {
	        // Generate the artist list
	        $artists = array('A' => array('Atreyu', 'Avenged Sevenfold'),
	                         'B' => array('Bad Religion', 'Big D and the Kids Table'),
	                         'C' => array('CAKE', 'Catch 22'),
	                         'D' => array('Deerhoof', 'Dropkick Murphys'));
	        // Generate and display the view
	        $data = array('artists' => $artists);
	        return View::make('jukebox.add', $data);
	    }
	    
	    if($album == '' || $song == '') {
	        // Generate the Album and Song list
	        $songs = array('Do or Die' => array('Cadence to Arms', 'Do or Die'),
	                       'Album 2' => array('Song 1', 'Song 2'));
	        // Generate and display the view
	        $data = array('songs' => $songs,
	                      'artist_name' => $artist);
	        return View::make('jukebox.album', $data);
	    }
	    
	    // If everything's set, insert the song into the queue (if it's not already there)
	    //   and display the Currently playing page
	}
	
	public function getPic($album_id = 0) {
	    // This should return a picture for the album art
	}
}
