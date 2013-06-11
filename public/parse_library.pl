#!/usr/bin/perl

use strict;
use warnings;

# use module
use DBI;
use MP3::Tag;

# Set the base path (this should come from command line in future)
my $base_path = "/tank/safety";

# Set database variables
my $dsn = "dbi:mysql:database=jukebox;host=localhost;port=3306";
my $dbuser = "root";
my $dbpass = "password";

# Connect to the database
my $dbh = DBI->connect($dsn, $dbuser, $dbpass, { RaiseError => 1 }) or die $DBI::errstr;

# Run the processing on the base path
process_folder($base_path);

# Process folder function
sub process_folder {
	# Grab the path
	my $path = shift;

	# Open the directory
	opendir(DIR, $path) or die "Unable to open $path: $!";

	# Read in the files and skip "." and ".."
	my @files = grep { !/^\.{1,2}$/ } readdir(DIR);

	# Close the directory
	closedir(DIR);

	# Expand to full path
	@files = map { $path . '/' . $_ } @files;

	# Process the entries
	for(@files) {
		# If it's a directory, recurse
		if(-d $_) {
			process_folder($_);
		# Otherwise, process it as a file
		} else {
			process_file($_);
		}
	}
}

# Process file function
sub process_file {
	# Grab the path
	my $filename = shift;

	# Check the path for .mp3 extension
	if(substr($filename, -4) eq '.mp3') {

		# create new MP3-Tag object
		my $mp3 = MP3::Tag->new($filename);
		$mp3->get_tags();

		# if ID3v2 tags exists
		if (exists $mp3->{ID3v2})
		{
			# get a list of frames as a hash reference
			#$frames = $mp3->{ID3v2}->get_frame_ids();

			# iterate over the hash
			# process each frame
			#foreach $frame (keys %$frames) 
			#{
			#	# for each frame
			#	# get a key-value pair of content-description
  			#	($value, $desc) = $mp3->{ID3v2}->get_frame($frame);
			#	print "$frame $desc: ";
			#	# sometimes the value is itself a hash reference containing more values
			#	# deal with that here
			#	if (ref $value)
			#	{
			#		while (($k, $v) = each (%$value))
			#		{
			#			#print "\n     - $k: $v";
			#			if($k ne "_Data") {
			#				print "\n     - $k: $v";
			#			} else {
			#				print "\n     - $k: ...";
			#			}
			#		}
			#		print "\n";
			#	}
			#	else
			#	{
			#		#print "$value\n";
			#		print "\n";
			#	}
			#}
	
			# List out each value that is needed
			# Artist
			my $artist = $mp3->artist();

			# Album
			my $album = $mp3->album();

			# Track Name and Number
			my $track = $mp3->track();
			(my $tnum, my $badvalue) = split('\/', $track);
			my $title = $mp3->title();

			# Picture Info
			(my $pic_frame, $badvalue) = $mp3->{ID3v2}->get_frame("APIC");
			my $pic_type = $pic_frame->{'MIME type'};
			my $pic_data = $pic_frame->{'_Data'};

			# Put the info in the database
			my $stm_artist_sel = $dbh->prepare("SELECT id FROM artists WHERE name = ?");
			$stm_artist_sel->execute($artist);
			my $artist_id = $stm_artist_sel->fetchrow();
			if(!$artist_id) {
				my $stm_artist_ins = $dbh->prepare("INSERT INTO artists(id, name) VALUES (NULL, ?) ON DUPLICATE KEY UPDATE id = id");
				$stm_artist_ins->execute($artist);
				$stm_artist_ins->finish();

				$stm_artist_sel->execute($artist);
				$artist_id = $stm_artist_sel->fetchrow();
			}
			$stm_artist_sel->finish();

			my $stm_album_sel = $dbh->prepare("SELECT id FROM albums WHERE artist_id = ? AND name = ?");
			$stm_album_sel->execute($artist_id, $album);
			my $album_id = $stm_album_sel->fetchrow();
			if(!$album_id) {
				my $stm_album_ins = $dbh->prepare("INSERT INTO albums(id, artist_id, name, pic_mime, pic_data) VALUES (NULL, ?, ?, ? ,?) ON DUPLICATE KEY UPDATE id = id");
				$stm_album_ins->execute($artist_id, $album, $pic_type, $pic_data);
				$stm_album_ins->finish();

				$stm_album_sel->execute($artist_id, $album);
				$album_id = $stm_album_sel->fetchrow();
			}
			$stm_album_sel->finish();

			my $stm_song_sel = $dbh->prepare("SELECT id FROM songs WHERE album_id = ? and num = ?");
			$stm_song_sel->execute($album_id, $tnum);
			my $track_id = $stm_song_sel->fetchrow();
			if(!$track_id) {
				my $stm_song_ins = $dbh->prepare("INSERT INTO songs(id, album_id, name, num, path) VALUES (NULL, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE id = id");
				$stm_song_ins->execute($album_id, $title, $tnum, $filename);
				$stm_song_ins->finish();

				$stm_song_sel->execute($album_id, $tnum);
				$track_id = $stm_song_sel->fetchrow();
			}
			$stm_song_sel->finish();

			# Echo line for fun
			print "$artist ($artist_id) - $album ($album_id) - $tnum ($track_id) $title\n";
		}

		# clean up
		$mp3->close();
	}
}

# Close database handle
$dbh->disconnect();

