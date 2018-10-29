#!/usr/local/bin/perl
# readChargeCurrent() :
# Reads the charge current from a Tristar TS-45 via Modbus RTU
# Prints the charge current and raw response to the screen
# Returns the charge current

use Modbus::Client;
use Math::Round;

sub readChargeCurrent()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the charge current from the TriStar
# PDU Addr. 0x000B
# Logical Addr. 12
my $response = $unit->read_one(30012);

my $chargeCurrent = nearest(.01,(($response * 66.667) / 32768));

if($verbose == 1)
{
	print "Charge Current: ".$chargeCurrent." A\nRaw Response: ".$response."\n";
}

return $chargeCurrent;

}

1;
