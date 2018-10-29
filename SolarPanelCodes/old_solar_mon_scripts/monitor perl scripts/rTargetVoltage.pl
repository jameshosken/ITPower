#!/usr/local/bin/perl
# readTargetVoltage() :
# Reads the target voltage from a Tristar TS-45 via Modbus RTU
# Prints the target voltage and raw decimal response to the screen
# Returns the target voltage

use Modbus::Client;
use Math::Round;

sub readTargetVoltage()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the battery voltage from the TriStar
# PDU Addr. 0x0010
# Logical Addr. 17
my $response = $unit->read_one(30017);

my $targetVoltage = nearest(.01,(($response * 96.667) / 32768));

if($verbose == 1)
{
	print "Target Voltage: ".$targetVoltage." V\nRaw Response (DEC): ".$response."\n";
}

return $targetVoltage;

}

1;
