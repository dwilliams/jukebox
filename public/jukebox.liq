#!/usr/bin/liquidsoap -v

# Setup logging
set("log.file.path", "/tmp/<script>.log")
set("log.stdout", true)

def my_request_function() = 
  # Get a line from an external program
  result = list.hd(get_process_lines("perl /var/www/public/get_song.pl"))
  # Create and return a request using this result
  request.create(result)
end

# Get the song URL from the script, then skip blanks, then buffer.  The buffer prevents the fallback from
#    falling back to the safety stream and hopefully prevents buffer underruns with alsa.
#playqueue = buffer(fallible=true, start=true, max=1.1, skip_blank(length=3., request.dynamic(my_request_function)))
playqueue = skip_blank(length=3., request.dynamic(my_request_function))

safety = audio_to_stereo(single("/tank/safety/silence1sec.wav"))

radio = fallback([playqueue, safety])

output.alsa(radio)

