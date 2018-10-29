#!/usr/local/bin/perl
# readFault() :
# Reads the faults from a Tristar TS-45 via Modbus RTU
# Prints the faults and raw decimal response to the screen
# Returns the faults

use Modbus::Client;
use Math::Round;

sub dec2bin {
    my $str = unpack("B8", pack("N", shift));
    #$str =~ s/^0+(?=\d)//;   # otherwise you'll get leading zeros
    return $str;
}

sub readFault()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the hourmeter from the TriStar
# PDU Addr. 0x0018
# Logical Addr. 25
my $response = $unit->read_one(30025);

my $faults = dec2bin($response);

if($verbose == 1)
{
	print "Faults: " . $faults . "\n";
}

return $faults;

}

1;
