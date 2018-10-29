#!/usr/local/bin/perl
# readBatterySense() :
# Reads the battery sense voltage from a Tristar TS-45 via Modbus RTU
# Prints the battery sense voltage and raw response to the screen
# Returns the battery sense voltage

use Modbus::Client;
use Math::Round;

sub readBatterySense()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the battery sense voltage from the TriStar
# PDU Addr. 0x0009
# Logical Addr. 9
my $response = $unit->read_one(30010);

my $batterySense = nearest(.01,(($response * 96.667) / 32768));

if($verbose == 1)
{
	print "Battery Sense Voltage: ".$batterySense." V\nRaw Response (DEC): ".$response."\n";
}

return $batterySense;

}

1;
