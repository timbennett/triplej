triplej
=======

Code relating to triplej.abc.net.au playlist data

* triplej_plays_2015.csv: This file contains every track played on Australian radio station Triple J, as reported by the ABC Radio API, in 2015 (using GMT timestamps). Columns: timestamp (GMT), track name, 1st-4th listed artists.
* playlist_20140929.zip: "Now Playing" data collected from http://www.abc.net.au/triplej/feeds/playout/triplej_sydney_3item_playout.xml (also listed on http://www.abc.net.au/triplej/player/triplej.htm) from 2012-02-07 to 2014-09-29.
* scrape.php: Scrapes the most recently played Triple J songs to a database. Kind of ugly, and obsolescent.
* get_triplej_tracks.py: This file searches the ABC Radio API for plays between two timestamps (q_from & q_to) on a given radio station (default triplej). Newer (and pulls from a current system) but lacking documentation.

###Playlist CSV details:

One track (song) per row.

Column details:

* Timestamp: **Don't use this field.** Time the current track started playing. Recorded off Triple J website. Bit of a mistake as they change server timezone sometimes and I have to correct for it.
* Date/time: **Use this for date and time.** Corrected date & time values
* Trackname: Song title. Sometimes contains extra information, e.g. live performance data
* Artist: Person or group that played the song
* Album: Album the track appears on
* Duration: hours:minutes:seconds
* Seconds: Duration field expressed as seconds
* Program: name of the radio program on air at the time the track was played
* Day: day of week when track was played

Caveats:

* Song data isn't captured if the ABC studio in use doesn't send data to the playout stream (either because it is old, or the current track is being played off vinyl/CD instead of from the computer, etc). This mainly affects late night (after 9pm).
* I'm not aware of any massive gaps in data but I haven't checked thoroughly. Some program names may be missing or inaccurate.
* Sometimes Triple J has corrected errors in its data (e.g. artist name spelling) but the original mistakes persist in my data.
