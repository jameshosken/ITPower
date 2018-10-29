#!/usr/local/bin/perl
# readPWM() :
# Reads the PWM regulation duty cycle from a Tristar TS-45 via Modbus RTU
# Prints the pwm and raw response to the screen
# Returns the pwm response

use Modbus::Client;

sub readPWM()
{

my $verbose = $_[0];

my $bus = new Modbus::Client "/dev/ttyUSB0";
my $unit = $bus->device(1);

# Read the hourmeter from the TriStar
# PDU Addr. 0x001C 
# Logical Addr. 29
my $pwm = $unit->read_one(30029);

if($verbose == 1)
{
	print "PWM: ".$pwm."\n";
}

return $pwm;

}

1;
