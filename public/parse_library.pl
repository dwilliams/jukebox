#!/usr/bin/perl

# use module
use MP3::Tag;

# set filename of MP3 track
#$filename = "track1.mp3";
$filename = "/Users/fbus/Music/[LIBRARY]/Dropkick Murphys/Do or Die/10 Barroom Hero.mp3";

# create new MP3-Tag object
$mp3 = MP3::Tag->new($filename);
$mp3->get_tags();

# if ID3v2 tags exists
if (exists $mp3->{ID3v2})
{
	# get a list of frames as a hash reference
	$frames = $mp3->{ID3v2}->get_frame_ids();

	# iterate over the hash
	# process each frame
	foreach $frame (keys %$frames) 
	{
		# for each frame
		# get a key-value pair of content-description
  		($value, $desc) = $mp3->{ID3v2}->get_frame($frame);
		print "$frame $desc: ";
		# sometimes the value is itself a hash reference containing more values
		# deal with that here
		if (ref $value)
		{
			while (($k, $v) = each (%$value))
			{
				print "\n     - $k: $v";
			}
			print "\n";
		}
		else
		{
			print "$value\n";
		}
	}
}

# clean up
$mp3->close();

