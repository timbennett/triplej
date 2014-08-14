<?php
// This script scrapes the most recently played Triple J songs to a database
// Written 6 Feb 2012, commented 14 Aug 2014, apologies for poor documentation
// and general hobbyist-level coding

// database connection details, these predate my knowledge of mysqli
$host = "localhost"; 
$user = "";
$pass = "";
$database = "";
$table = "";
$conn = mysql_connect( $host, $user, $pass ) or die( "Error :Couldn't connect" );

// Where to find the data:
// File at $completeurl contains what's playing in Sydney right now
// File at $programurl contains the name of the program currently on air

$completeurl =
"http://www.abc.net.au/triplej/feeds/playout/triplej_sydney_3item_playout.xml";
$xml = simplexml_load_file($completeurl);
$tracks = $xml->items->item; // get current and recent tracks

$programurl = "http://www.abc.net.au/triplej/includes/nowon_radio.xml";
$xml = simplexml_load_file($programurl); // get current program
$onair = addslashes($xml->name); // ...separate the program name
$day = addslashes($xml->day); // ...separate the program day

// Now get the tracks and write them into the database. We don't care about messy 
// overwriting of previous entries here because performance isn't an issue.
// A bit sloppy, I know.

for ($i = 0; $i < 3; $i++) {
	$playing = $tracks[$i]->playing;
    $trackname = addslashes($tracks[$i]->title); // we addslashes() because apostrophes will break the SQL query otherwise
    $artist = addslashes($tracks[$i]->artist->artistname);
    $album = addslashes($tracks[$i]->album->albumname);
    $duration = $tracks[$i]->duration;
	// The ABC's timestamp is in AES(D)T but the extra Z makes PHP think the playedtime is 10/11 hours in the future.
	// Instead we strip out the Z and store the time as UTC. This will cause minor errors during daylight saving changeovers.
	$playedtime = strtotime(str_replace("Z","",$tracks[$i]->playedtime)); 
 
	if ( $playedtime != NULL ) {
	echo $playedtime . "|" . $trackname . "|" . $artist . "|" . $album . "|" . $duration . "<br>";
	$conn = mysql_connect( $host, $user, $pass ) or die( "Err:Connection");
	$rs = mysql_select_db( $database, $conn ) or die( "Err:Database" );
	$sql = "insert into $table (timestamp, trackname, artist, album, duration, program, day) values ('$playedtime', '$trackname', '$artist', '$album', '$duration', '$onair', '$day')";
	$rs = mysql_query( $sql, $conn );	
	}
}
?>
