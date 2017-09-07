<?php
include 'ICS.php';

function generateICS($location='',$description='',$dtstart,$dtend,$summary,$url='')
{
	
	header('Content-type: text/calendar; charset=utf-8');
	//header('Content-Disposition: attachment; filename=lue_calendar.ics');

	$ics = new ICS(array(
  	'location' => $location,
  	'description' => $description,
  	'dtstart' => $dtstart,
  	'dtend' => $dtend,
  	'summary' => $summary,
  	'url' => $url
	));

echo $ics->to_string();
}
