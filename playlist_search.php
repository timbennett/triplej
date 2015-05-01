<?php

// Get historical play data from an ABC Radio playlist API

// Configs:
$url = 'http://music.abcradio.net.au/api/v1/plays/search.json';
$station = 'triplej'; // jazz,dig,doublej,unearthed,country,triplej,classic
$limit = '1000'; // Much above 1000 doesn't work
$from = '2015-03-31T14:00:00Z'; // UTC 14:00 is AEST 00:00
$to = '2015-04-30T14:00:00Z';
$offset = '500'; // offset this many records into the total results
date_default_timezone_set('UTC'); // returned times are in UTC

// build URL & fetch JSON results
$url = $url ."?station=".$station."&limit=".$limit."&from=".$from."&to=".$to."&offset=".$offset;
$json = file_get_contents($url,0,null,null);
//$json = file_get_contents('triplej.json'); // debug static file
$responseData = json_decode($json, TRUE);

// some optional debug stuff:
/*
echo "URL: ".$url."<br>";
echo "We have: ". $responseData['total'] ." records<br>";
echo "<pre>"; // debug
*/

// set up CSV column headers 
	// only if we're at the start of the result set
	if ($offset < 1){
		$tracks[] = array('timestamp','date_time','title','artist','release','duration');
		}

// go through each returned JSON item (a track play)
foreach ($responseData['items'] as $track) {
	// Turn ISO8601 UTC timestamp into readable AEST date/time
	$date = $track['played_time'];
	$time = strtotime($date);
	$autime = $time + 36000;
	$prettyDateTime = date('d/m/Y G:i:s', $time);

	// $pushData holds the new array that will go on the end of $tracks
	$pushData = array (
		$time											,
		$prettyDateTime									,									
		$track['recording']['title']					,
		$track['recording']['artists']['0']['name']		,
		$track['recording']['releases']['0']['title']	,
		$track['recording']['duration']					
		);
	// send $pushData to $tracks
	$tracks[] = $pushData;
	}

// write the track data in CSV format (escaping, quotation etc) to browser output; change if you want to write to a file
$fp = fopen("php://output", 'w');
foreach ($tracks as $fields) {
	fputcsv($fp, $fields);
	}
fclose($fp);

?>
