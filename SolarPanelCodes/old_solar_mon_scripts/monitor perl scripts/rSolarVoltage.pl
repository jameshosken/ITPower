#!/usr/local/bin/perl
# readSolarVoltage() :
# Reads the solar voltage from a Tristar TS-45 via Modbus RTU
# Prints the solar voltage and raw decimal response to the screen
# Returns the solar voltage

use Modbus::Client;
use Math::Round;

sub readSolarVoltage()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the solar voltage from the TriStar
# PDU Addr. 0x000A
# Logical Addr. 11
my $response = $unit->read_one(30009);

my $solarVoltage = nearest(.01,(($response * 139.15) / 32768));

if($verbose == 1)
{
	print "Solar Voltage: ".$solarVoltage." V\nRaw Response (DEC): ".$response."\n";
}

return $solarVoltage;

}

1;
