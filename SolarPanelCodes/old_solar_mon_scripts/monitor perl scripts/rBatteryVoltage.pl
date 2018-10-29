#!/usr/local/bin/perl
# readBatteryVoltage() :
# Reads the battery voltage from a Tristar TS-45 via Modbus RTU
# Prints the battery voltage and raw decimal response to the screen
# Returns the battery voltage

use Modbus::Client;
use Math::Round;

sub readBatteryVoltage()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the battery voltage from the TriStar
# PDU Addr. 0x0008
# Logical Addr. 9
my $response = $unit->read_one(30009);

my $batteryVoltage = nearest(.01,(($response * 96.667) / 32768));

if($verbose == 1)
{
	print "Battery Voltage: ".$batteryVoltage." V\nRaw Response (DEC): ".$response."\n";
}

return $batteryVoltage;

}

1;
