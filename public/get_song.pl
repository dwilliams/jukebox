#!/usr/bin/perl

use strict;
use warnings;

# use module
use DBI;
use MP3::Tag;

# Set database variables
my $dsn = "dbi:mysql:database=jukebox;host=localhost;port=3306";
my $dbuser = "root";
my $dbpass = "password";

# Connect to the database
my $dbh = DBI->connect($dsn, $dbuser, $dbpass, { RaiseError => 1 }) or die $DBI::errstr;

# Get a random song from the queue
#my $stm_queue_rand = $dbh->prepare("SELECT song_id FROM queue, (SELECT FLOOR(MAX(queue.id) * RAND()) AS randID FROM queue) AS someRandID WHERE queue.id = someRandID");
my $stm_queue_rand = $dbh->prepare("SELECT song_id FROM queue JOIN (SELECT FLOOR(MAX(queue.id) * RAND()) AS ID FROM queue) AS x ON queue.id >= x.ID LIMIT 1");
$stm_queue_rand->execute();
my $song_id = $stm_queue_rand->fetchrow();
$stm_queue_rand->finish();

# If the queue is empty, pick a random song
if(!$song_id) {
	#my $stm_song_rand = $dbh->prepare("SELECT id from songs, (SELECT FLOOR(MAX(songs.id) * RAND()) AS randID FROM songs) AS someRandID WHERE songs.id = someRandID");
	my $stm_song_rand = $dbh->prepare("SELECT songs.id FROM songs JOIN (SELECT FLOOR(MAX(songs.id) * RAND()) AS ID FROM songs) AS x ON songs.id >= x.ID LIMIT 1");
	$stm_song_rand->execute();
	$song_id = $stm_song_rand->fetchrow();
	$stm_song_rand->finish();
}

# Remove from the queue
my $stm_queue_del = $dbh->prepare("DELETE FROM queue WHERE song_id = ?");
$stm_queue_del->execute($song_id);
$stm_queue_del->finish();

# Add to the last played list
my $stm_lplay_ins = $dbh->prepare("INSERT INTO last_played(id, song_id) VALUES (NULL, ?)");
$stm_lplay_ins->execute($song_id);
$stm_lplay_ins->finish();

# Get the song's path and print it
my $stm_song_sel = $dbh->prepare("SELECT path FROM songs WHERE id = ?");
$stm_song_sel->execute($song_id);
my $path = $stm_song_sel->fetchrow();
$stm_song_sel->finish();
print "$path\n";
