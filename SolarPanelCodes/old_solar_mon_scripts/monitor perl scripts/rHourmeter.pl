#!/usr/local/bin/perl
# readHourmeter() :
# Reads the hourmeter from a Tristar TS-45 via Modbus RTU
# Prints the hourmeter and raw decimal response to the screen
# Returns the hourmeter response

use Modbus::Client;

sub readHourmeter()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the hourmeter from the TriStar
# PDU Addr. 0x0015 and 0x0016 
# Logical Addr. 22 and 23
my $response_HI = $unit->read_one(30022);
my $response_LO = $unit->read_one(30023);

my $hourmeter = ($response_HI << 8) | $response_LO;

if($verbose == 1)
{
	print "Hourmeter: ".$hourmeter." \nRaw Response: ".$response_HI." and ". $response_LO  ."\n";
}

return $hourmeter;

}

1;
