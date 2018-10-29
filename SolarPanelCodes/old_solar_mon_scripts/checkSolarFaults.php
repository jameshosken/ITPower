<?php
	# MYSQL VARIABLES
	$hostname = "localhost";
	$database = "solarmonitor";
	$user = "solarmonitor";
	$pw = "SLar-Mo0n";

	$table1 = "solarData";
	$table2 = "solarMaintenance";
	
	$link = mysql_connect($hostname, $user, $pw);
	
	if(!$link) {
		die('ERROR: [2] Unable to connect to MySQL server : ' . mysql_error() . "\n");
	}
	
	$db_selected = mysql_select_db($database, $link);
	
	if(!$db_selected) {
		die("ERROR: [2] Can't connect to database : " . mysql_error() . "\n");
	}
	
	$query = sprintf("SELECT * FROM `%s` ORDER BY `id` DESC LIMIT 0, 2",
	mysql_real_escape_string($table1));
	
	$result = mysql_query($query);
	
	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die("ERROR: [2] " . $message);
	}
	
	$count = 1;
	
	while ($row = mysql_fetch_object($result))
	{
		if($count == 1)
			$data1 = $row;
		else if($count == 2)
			$data2 = $row;
		
		$count++;
	}
	
	# BEGIN CHECKING RECENT DATA FOR FAULTS #
	
	# more than thirty minutes since the last data has been posted, check post script and then the netbook's logs
	if((time() - strtotime($data1->timestamp)) > 1800)
	{
		$notifications[] = "It has been more than thirty (30) minutes since the last data has been collected from the solar monitor. Please check to make sure everything is functioning properly.";
	}
	
	# the hourmeter has been reset, most likely the battery has been serviced or swapped
	if($data1->hourmeter < $data2->hourmeter)
	{	
		if($data1->batteryVoltage > 0)
			$notifications[] = "The battery has recently be serviced or swapped.";
		else
			$notifications[] = "The battery appears to be offline.";
	}
	
	# the battery appears to be charged. the battery voltage has reached the target voltage.
	if($data1->batteryVoltage >= $data1->targetVoltage)
	{
		$notifications[] = "The battery is charged.";
	}
	
	# END CHECKING RECENT DATA FOR FAULTS #
	
	mysql_free_result($result);

	if(isset($notifications))
	{
		foreach($notifications as $notification)
		{
			# echo $notification."\n";
			$sNotifications = $notification . " ";
		}
	}

	if(isset($sNotifications))
	{
		$query = sprintf("INSERT INTO `%s` (`notification`) VALUES ('%s')",
		mysql_real_escape_string($table2),
		mysql_real_escape_string($sNotifications));
		
		if(!mysql_query($query))
		{
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die("ERROR: [2] " . $message);
		}
	}

	mysql_close($link);
?>