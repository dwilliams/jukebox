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
                $songs[] = array($song->name, $artist->name, $album->name, $song->album_id);
            }
	    
	    $data = array('oldsongs' => array_slice($songs, 1),
                          'currentsong' => $songs[0]);
		return View::make('jukebox.index', $data);
	}

	public function getAdd($artist_name = '', $album_name = '', $song_name = '') {
	    if($artist_name == '') {
                $artist_list = array();
                $artists = Artist::all();
	        // Generate the artist list
                foreach ($artists as $artist) {
                    // If the first letter of the artist is not a key, add it
                    $first_letter = strtoupper(substr($artist->name, 0, 1));
                    if(!array_key_exists($first_letter, $artist_list)) {
                        $artist_list[$first_letter] = array();
                    }
                    // Then add the artist to the array for the letter
                    $artist_list[$first_letter][] = $artist->name;
                }

                // Sort the alphabet...
                ksort($artist_list);

	        // Generate and display the view
	        $data = array('artists' => $artist_list);
	        return View::make('jukebox.add', $data);
	    }
	    
            $artist = Artist::where('name', '=', $artist_name)->first();

	    if($album_name == '' || $song_name == '') {
                $song_list = array();
                $albums = Album::where('artist_id', '=', $artist->id)->get();
	        // Generate the Album and Song list
                foreach ($albums as $album) {
                    $song_list[$album->name] = array();
                    $songs = Song::where('album_id', '=', $album->id)->get();
                    foreach ($songs as $song) {
                        $song_list[$album->name][$song->num] = $song->name;
                    }
                }

	        //$songs = array('Do or Die' => array('Cadence to Arms', 'Do or Die'),
	        //               'Album 2' => array('Song 1', 'Song 2'));
	        // Generate and display the view
	        $data = array('songs' => $song_list,
	                      'artist_name' => $artist->name);
	        return View::make('jukebox.album', $data);
	    }

	    $album = Album::where('name', '=', $album_name)->where('artist_id', '=', $artist->id)->first();
            $song = Song::where('name', '=', $song_name)->where('album_id', '=', $album->id)->first();

	    // If everything's set, insert the song into the queue (if it's not already there)
	    //   and display the Currently playing page
            // Laravel doesn't support upsert functionality, so this will be raw...
            DB::insert('INSERT INTO queue (id, song_id) VALUES (NULL, ?) ON DUPLICATE KEY UPDATE id = id', array($song->id));

            return View::make('jukebox.added');
	}
	
	public function getPic($album_id = 0) {
	    // This should return a picture for the album art
            $pic_data = "";
            $pic_mime = "";
            if($album_id != 0) {
                // Try and find the album and return the artwork
                $album = Album::find($album_id);
                if($album) {
                    $pic_data = $album->pic_data;
                    $pic_mime = $album->pic_mime;
                }
            }
            return Response::make($pic_data, 200, array('Content-Type' => $pic_mime));
	}
}
