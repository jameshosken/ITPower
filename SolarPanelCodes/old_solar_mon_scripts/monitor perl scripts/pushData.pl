#!/usr/bin/perl

use LWP::Simple;

require "/home/solarpanel/Scripts/rBatteryVoltage.pl";
require "/home/solarpanel/Scripts/rBatteryVoltageSf.pl";
require "/home/solarpanel/Scripts/rBatterySense.pl";
require "/home/solarpanel/Scripts/rSolarVoltage.pl";
require "/home/solarpanel/Scripts/rChargeCurrent.pl";
require "/home/solarpanel/Scripts/rTargetVoltage.pl";
require "/home/solarpanel/Scripts/rControlState.pl";
require "/home/solarpanel/Scripts/rControlMode.pl";
require "/home/solarpanel/Scripts/rHourmeter.pl";
require "/home/solarpanel/Scripts/rAlarm.pl";
require "/home/solarpanel/Scripts/rFault.pl";
require "/home/solarpanel/Scripts/rPWM.pl";

$url = "http://itp.nyu.edu/solarmonitor/postData.php";
$content = 0;


$batteryVoltage = readBatteryVoltage(0);
$batteryVoltageSf = readBatteryVoltageSf(0);
$batterySense = readBatterySense(0);
$solarVoltage = readSolarVoltage(0);
$chargeCurrent = readChargeCurrent(0);
$targetVoltage = readTargetVoltage(0);
$controlState = readControlState(0);
$controlMode = readControlMode(0);
$hourmeter = readHourmeter(0);
$alarm = readAlarm(0);
$fault = readFault(0);
$pwm = readPWM(0);

$url = $url. "?bv=" . $batteryVoltage . "&bvsf=" . $batteryVoltageSf . "&bs=" . $batterySense  . "&sv=" . $solarVoltage . "&cc=" . $chargeCurrent . "&tv=" . $targetVoltage . "&cm=" . $controlMode  . "&cs=" . $controlState . "&hm=" . $hourmeter . "&a=" . $alarm ."&f=" . $fault . "&pwm=" . $pwm;

# Tom's additions here:
open (LOGFILE, '>>data.txt');
print LOGFILE "$url\n";
close (LOGFILE);

print $url;


$content = get($url);

if($content == '1')
{
	print "SUCCESS\n";
}
elsif($content == 0)
{
	print "No response from the remote script\n";
}
else
{
	print $content;
}
