<?php
  /***
    *  LastPlayed.php
    *  Model for songs table
   ***/

class LastPlayed extends Eloquent {
  protected $table = 'last_played';
  public $timestamps = false;

  public function scopeLatest($query) {
    return $query->orderBy('id', 'desc')->take(4);
  }
}

/* DPW */
/* EOF */
