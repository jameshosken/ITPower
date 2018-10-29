<?php
	/*** UPDATES THE RECENT DATA XML FEED ***/

	$maxEntries = 15;
	
	$filename = "/home/rmn236/solarmonitor/recentdata.xml";
	
	if (is_writable($filename)) {
		if (!$handle = fopen($filename, 'w+')) {
			 die("Cannot open file ($filename)");
		}
    }
    else
    	die("ERROR: '" . $filename . "' is not writeable\n");

	fwrite($handle,"<?xml version=\"1.0\"?>\n");
	fwrite($handle,"<solarData>\n");
	
	# MYSQL VARIABLES
	$hostname = "localhost";
	$database = "solarmonitor";
	$user = "solarmonitor";
	$pw = "SLar-Mo0n";
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
		fwrite($handle,"<entry>\n");
		fwrite($handle,"\t<id>" . $row->id  . "</id>\n");
		fwrite($handle,"\t<timestamp>".date("r",strtotime($row->timestamp))."</timestamp>\n");
		
		fwrite($handle,"\t<batteryVoltage>" . $row->batteryVoltage . "</batteryVoltage>\n");
		fwrite($handle,"\t<batteryVoltageSf>" . $row->batteryVoltage_sf . "</batteryVoltageSf>\n");
		fwrite($handle,"\t<batterySense>" . $row->batterySense . "</batterySense>\n");
		fwrite($handle,"\t<solarVoltage>" . $row->solarVoltage . "</solarVoltage>\n");
		fwrite($handle,"\t<chargeCurrent>" . $row->chargeCurrent . "</chargeCurrent>\n");
		fwrite($handle,"\t<targetVoltage>" . $row->targetVoltage . "</targetVoltage>\n");
		
		fwrite($handle,"\t<controlMode>");
		
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
		fwrite($handle,"</controlMode>\n");
		
		fwrite($handle,"\t<controlState>");
		
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
		fwrite($handle,"</controlState>\n");
		
		fwrite($handle,"\t<hourmeter>" . $row->hourmeter . "</hourmeter>\n");
		
		fwrite($handle,"\t<alarm>");
		
		$alarmMsgs = array("RTS open", "RTS shorted", "RTS disconnected", "Ths Disconnected", "Ths shorted", "TriStar hot", "Current limit", "Current offset", "Battery Sense", "Batt Sense disc", "Uncalibrated", "RTS miswire", "HVD", "high d", "miswire", "FET open", "P12", "Load Disc.", "Alarm 19", "Alarm 20", "Alarm 21", "Alarm 22", "Alarm 23", "Alarm 24");
		
		$count = 0;
		
		for($i = strlen($row->alarm) - 1; $i >=0; $i--)
		{
			if($row->alarm[$i] == 1)
			{
				if($count > 0)
					fwrite($handle,',');
				
				fwrite($handle,$alarmMsgs[(strlen($row->alarm) - 1) - $i]);
				$count++;
			}
		}
		
		if($count == 0)
			fwrite($handle,'None');
		
		fwrite($handle,"</alarm>\n");
		
		fwrite($handle,"\t<fault>");
		
		$faultMsgs = array("External Short", "Overcurrent", "FET Short", "Software", "HVD", "TriStar hot", "DIP SW Changed", "Setting Edit", "Reset?", "Miswire", "RTS Shorted", "RTS Disconnected", "Fault 12", "Fault 13", "Fault 14", "Fault 15");
		
		$count = 0;
		
		for($i = strlen($row->fault) - 1; $i >=0; $i--)
		{
			if($row->fault[$i] == 1)
			{
				if($count > 0)
					fwrite($handle,',');
				
				fwrite($handle,$faultMsgs[(strlen($row->fault) - 1) - $i]);
				$count++;
			}
		}
		
		if($count == 0)
			fwrite($handle,'None');
		
		fwrite($handle,"</fault>\n");
		
		fwrite($handle,"\t<dutyCycle>" . $row->dutyCycle . "</dutyCycle>\n");
		
		fwrite($handle,"</entry>\n");
		fwrite($handle,"\n");
	}
	
	fwrite($handle, "</solarData>");
	
	mysql_free_result($result);
	mysql_close($link);
	
	fclose($handle);
?>
<?php
	/*** UPDATES THE RECENT SERVICE XML FEED ***/

	$maxEntries = 15;
	
	$filename = "/home/rmn236/solarmonitor/recentservice.xml";
	
	if (is_writable($filename)) {
		if (!$handle = fopen($filename, 'w+')) {
			 die("Cannot open file ($filename)");
		}
    }
    else
    	die("ERROR: '" . $filename . "' is not writeable\n");

	fwrite($handle,"<?xml version=\"1.0\"?>\n");
	fwrite($handle,"<solarService>\n");
	
	# MYSQL VARIABLES
	$hostname = "localhost";
	$database = "solarmonitor";
	$user = "solarmonitor";
	$pw = "SLar-Mo0n";
	$table = "solarMaintenance";
	
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
		fwrite($handle,"<entry>\n");
		fwrite($handle,"\t<notification>" . $row->notification  . "</id>\n");
		fwrite($handle,"\t<timestamp>" . $row->timestamp  . "</timestamp>\n");
		fwrite($handle,"</entry>\n");
		fwrite($handle,"\n");
	}
	
	fwrite($handle, "</solarService>");
	
	mysql_free_result($result);
	mysql_close($link);
	
	fclose($handle);
?>