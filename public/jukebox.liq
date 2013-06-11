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

playqueue = request.dynamic(my_request_function)

safety = single("/tank/safety/CAKE/Comfort Eagle/03 Shadow Stabbing.mp3")

radio = fallback([playqueue, safety])

output.alsa(radio)

