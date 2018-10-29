#!/bin/bash

# reset the VPNC connection
sh /home/solarpanel/Scripts/resetVPNC.sh

sleep 5

# push new solar data to the web
perl /home/solarpanel/Scripts/pushData.pl

# set a wake up alarm
sudo sh -c "echo 0 > /sys/class/rtc/rtc0/wakealarm"
sudo sh -c "echo `date '+%s' -d '+ 28 minutes'` > /sys/class/rtc/rtc0/wakealarm"

sleep 1

# put the netbook to sleep
/usr/sbin/pmi action suspend
