#!/usr/local/bin/perl
# readControlMode() :
# Reads the control mode from a Tristar TS-45 via Modbus RTU
# Prints the control mode and raw decimal response to the screen
# Returns the control mode

use Modbus::Client;

sub readControlMode()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the control mode from the TriStar
# PDU Addr. 0x001A
# Logical Addr. 27
my $controlMode = $unit->read_one(30027);

if($verbose == 1)
{
	print "Control Mode: ".$controlMode."\n";
}

return $controlMode;

}

1;
