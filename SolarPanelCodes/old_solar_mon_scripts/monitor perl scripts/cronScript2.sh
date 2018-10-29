#!/bin/bash

# wake up
# delay 60 seconds

sleep 60


# take a current reading
# write to local log
# check to see if there's a network connection. If not, sleep
# upload local log entries one at a time
# delete log entries as you succeed
# when you reach the end of the log, go to sleep


# reset the VPNC connection
sh /home/solarpanel/Scripts/resetVPNC.sh


# push new solar data to the web
perl /home/solarpanel/Scripts/pushData.pl

# set a wake up alarm
sudo sh -c "echo 0 > /sys/class/rtc/rtc0/wakealarm"
sudo sh -c "echo `date '+%s' -d '+ 28 minutes'` > /sys/class/rtc/rtc0/wakealarm"

sleep 1

# put the netbook to sleep
/usr/sbin/pmi action suspend
