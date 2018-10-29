<?php
	# MYSQL VARIABLES
	$hostname = "localhost";
	$database = "solarmonitor";
	$user = "solarmonitor";
	$pw = "SLar-Mo0n";
?>
<?php
	/*** UPDATES THE RECENT DATA RSS 2.0 FEED ***/

	$maxEntries = 15;
	
	$filename = "/home/rmn236/solarmonitor/recentdata.rss";
		
	if (is_writable($filename)) {
		if (!$handle = fopen($filename, 'w+')) {
			 die("Cannot open file ($filename)");
		}
    }
    else
    	die("ERROR: '" . $filename . "' is not writeable\n");

	fwrite($handle,"<?xml version=\"1.0\"?>\n");
	fwrite($handle,"<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n");

	fwrite($handle,"<channel>\n");
	
	fwrite($handle,"<title>ITP Solar Monitor: Data</title>\n");
	fwrite($handle,"<link>".$filename."</link>\n");
	fwrite($handle,"<atom:link href=\"".$filename."\" rel=\"self\" type=\"application/rss+xml\" />\n");
	fwrite($handle,"<description>An RSS feed for the most recent data collected from the ITP Solar Monitor</description>\n");
	fwrite($handle,"<lastBuildDate>" . date("r") . "</lastBuildDate>\n");
	fwrite($handle,"<language>en-us</language>\n\n");
	
	# MYSQL VARIABLES
	$table = "solarData";
	
	$link = mysql_connect($hostname, $user, $pw);
	
	if(!$link) {
		die('ERROR: [2] Unable to connect to MySQL server : ' . mysql_error() . "\n");
	}
	
	$db_selected = mysql_select_db($database, $link);
	
	if(!$db_selected) {
		die("ERROR: [2] Can't connect to database : " . mysql_error() . "\n");
	}
	
	$query = sprintf("SELECT * FROM `%s` ORDER BY `id` DESC LIMIT 0, %s",
	mysql_real_escape_string($table),
	mysql_real_escape_string($maxEntries));
	
	$result = mysql_query($query);
	
	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die("ERROR: [2] " . $message);
	}
	
	while ($row = mysql_fetch_object($result))
	{
		fwrite($handle,"<item>\n");
		fwrite($handle,"<title>".$row->timestamp."</title>\n");
		fwrite($handle,"<link>".$filename."</link>\n");
		fwrite($handle,"<pubDate>".date("r",strtotime($row->timestamp))."</pubDate>\n");
		fwrite($handle,"<description>\n");
		
		fwrite($handle,'Battery Voltage: ' . $row->batteryVoltage . 'V &lt;br/&gt;');
		fwrite($handle,'Battery Voltage, Sf: ' . $row->batteryVoltage_sf . 'V &lt;br/&gt;');
		fwrite($handle,'Battery Sense Voltage: ' . $row->batterySense . 'V (Offline)&lt;br/&gt;');
		fwrite($handle,'Solar Voltage: ' . $row->solarVoltage . 'V &lt;br/&gt;');
		fwrite($handle,'Charge Current: ' . $row->chargeCurrent . 'A &lt;br/&gt;');
		fwrite($handle,'Target Voltage: ' . $row->targetVoltage . 'V &lt;br/&gt;');
		
		fwrite($handle,'Control Mode: ');
		
		switch($row->controlMode)
		{
			case 0:
				fwrite($handle,'Charge');
				break;
			case 1:
				fwrite($handle,'Load');
				break;
			case 2:
				fwrite($handle,'Diversion');
				break;
			case 3:
				fwrite($handle,'Lighting');
				break;
		}
		fwrite($handle,'&lt;br/&gt;');
		
		fwrite($handle,'Control State: ');
		
		switch($row->controlState)
		{
			case 0:
				fwrite($handle,'Start');
				break;
			case 1:
				fwrite($handle,'Night Check');
				break;
			case 2:
				fwrite($handle,'Disconnect');
				break;
			case 3:
				fwrite($handle,'Night');
				break;
			case 4:
				fwrite($handle,'Fault');
				break;
			case 5:
				fwrite($handle,'Bulk');
				break;
			case 6:
				fwrite($handle,'PWM');
				break;
			case 7:
				fwrite($handle,'Float');
				break;
			case 8:
				fwrite($handle,'Equalize');
				break;
		}
		fwrite($handle,'&lt;br/&gt;');
		
		fwrite($handle,'Hourmeter: ' . $row->hourmeter . ' Hrs&lt;br/&gt;');
		
		fwrite($handle,sprintf("Fault(s) [%1d]: ", $row->fault));
		
		$faultMsgs = array("External Short", "Overcurrent", "FET Short", "Software", "HVD", "TriStar hot", "DIP SW Changed", "Setting Edit", "Reset?", "Miswire", "RTS Shorted", "RTS Disconnected", "Fault 12", "Fault 13", "Fault 14", "Fault 15");
		
		$count = 0;
		
		for($i = strlen($row->fault) - 1; $i >=0; $i--)
		{
			if($row->fault[$i] == 1)
			{
				if($count > 0)
					fwrite($handle,', ');
				
				fwrite($handle,$faultMsgs[(strlen($row->fault) - 1) - $i]);
				$count++;
			}
		}
		
		if($count == 0)
			fwrite($handle,'None');
		
		fwrite($handle,"&lt;br/&gt;");
			
		fwrite($handle,sprintf("Alarm(s) [%1d]: ", $row->alarm));
		
		$alarmMsgs = array("RTS open", "RTS shorted", "RTS disconnected", "Ths Disconnected", "Ths shorted", "TriStar hot", "Current limit", "Current offset", "Battery Sense", "Batt Sense disc", "Uncalibrated", "RTS miswire", "HVD", "high d", "miswire", "FET open", "P12", "Load Disc.", "Alarm 19", "Alarm 20", "Alarm 21", "Alarm 22", "Alarm 23", "Alarm 24");
		
		$count = 0;
		
		for($i = strlen($row->alarm) - 1; $i >=0; $i--)
		{
			if($row->alarm[$i] == 1)
			{
				if($count > 0)
					fwrite($handle,', ');
				
				fwrite($handle,$alarmMsgs[(strlen($row->alarm) - 1) - $i]);
				$count++;
			}
		}
		
		if($count == 0)
			fwrite($handle,'None');
		
		
		fwrite($handle,"&lt;br/&gt;");
		
		fwrite($handle, "PWM Regulation, Duty Cycle: " . $row->dutyCycle . "\n");
			
		fwrite($handle,"</description>\n");
		fwrite($handle,"</item>\n");
		fwrite($handle,"\n");
	}
	
	mysql_free_result($result);
	mysql_close($link);
	
	fwrite($handle,"</channel>\n");
	fwrite($handle,"</rss>\n");
	
	fclose($handle);
?>
<?php
	/*** UPDATES THE RECENT SERVICE RSS 2.0 FEED ***/

	$maxEntries = 15;
	
	$filename = "/home/rmn236/solarmonitor/recentservice.rss";
		
	if (is_writable($filename)) {
		if (!$handle = fopen($filename, 'w+')) {
			 die("Cannot open file ($filename)");
		}
    }
    else
    	die("ERROR: '" . $filename . "' is not writeable\n");

	fwrite($handle,"<?xml version=\"1.0\"?>\n");
	fwrite($handle,"<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n");

	fwrite($handle,"<channel>\n");
	
	fwrite($handle,"<title>ITP Solar Monitor: Service</title>\n");
	fwrite($handle,"<link>".$filename."</link>\n");
	fwrite($handle,"<atom:link href=\"".$filename."\" rel=\"self\" type=\"application/rss+xml\" />\n");
	fwrite($handle,"<description>An RSS feed for the most recent service messages from the ITP Solar Monitor</description>\n");
	fwrite($handle,"<lastBuildDate>" . date("r") . "</lastBuildDate>\n");
	fwrite($handle,"<language>en-us</language>\n\n");
	
	# MYSQL VARIABLES
	$table = "solarMaintenance";
	
	$link = mysql_connect($hostname, $user, $pw);
	
	if(!$link) {
		die('ERROR: [2] Unable to connect to MySQL server : ' . mysql_error() . "\n");
	}
	
	$db_selected = mysql_select_db($database, $link);
	
	if(!$db_selected) {
		die("ERROR: [2] Can't connect to database : " . mysql_error() . "\n");
	}
	
	$query = sprintf("SELECT * FROM `%s` ORDER BY `id` DESC LIMIT 0, %s", mysql_real_escape_string($table), mysql_real_escape_string($maxEntries));
	
	$result = mysql_query($query);
	
	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die("ERROR: [2] " . $message);
	}
	
	while ($row = mysql_fetch_object($result))
	{
		fwrite($handle,"<item>\n");
		fwrite($handle,"<title>".$row->timestamp."</title>\n");
		fwrite($handle,"<link>".$filename."</link>\n");
		fwrite($handle,"<pubDate>".date("r",strtotime($row->timestamp))."</pubDate>\n");
		fwrite($handle,"<description>" . $row->notification . "</description>\n");
		fwrite($handle,"</item>\n");
		fwrite($handle,"\n");
	}
	
	mysql_free_result($result);
	mysql_close($link);
	
	fwrite($handle,"</channel>\n");
	fwrite($handle,"</rss>\n");
	
	fclose($handle);
?>