#!/usr/local/bin/perl
# readControlState() :
# Reads the control state from a Tristar TS-45 via Modbus RTU
# Prints the control state and raw decimal response to the screen
# Returns the control state

use Modbus::Client;
use Math::Round;

sub readControlState()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the hourmeter from the TriStar
# PDU Addr. 0x001B
# Logical Addr. 28
my $controlState = $unit->read_one(30028);

if($verbose == 1)
{
	print "Control State: ".$controlState."\n";
}

return $controlState;

}

1;
