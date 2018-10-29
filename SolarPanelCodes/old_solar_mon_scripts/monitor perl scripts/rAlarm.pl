#!/usr/local/bin/perl
# readAlarm() :
# Reads the alarms from a Tristar TS-45 via Modbus RTU
# Prints the alarms and raw response to the screen
# Returns the alarms

use Modbus::Client;

sub dec2binAdv {     
    my ($dec_str, $bin_length) = @_;      
    my $str = unpack("B32", pack("N", $dec_str));      
    my @arr_str=split("",$str);      
    my $arr_in=0;      
    my $sixstr="";
   for($arr_in=($#arr_str-$bin_length+1); $arr_in<=$#arr_str; $arr_in++)     
   {      
     $sixstr=$sixstr.$arr_str[$arr_in];
   }     
   return $sixstr;      
}

sub readAlarm()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the hourmeter from the TriStar
# PDU Addr. 0x0017 and 0x001D
# Logical Addr. 24 and 30
my $response_HI = $unit->read_one(30030);
my $response_LO = $unit->read_one(30024);

my $alarms = dec2binAdv(($response_HI << 16) | $response_LO,24);

if($verbose == 1)
{
	print "Alarms: " . $alarms . "\n";
}

return $alarms;

}

1;
