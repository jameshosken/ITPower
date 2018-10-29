#!/bin/bash
echo 'Updating RSS feed...';
php /home/rmn236/updateSolarRSS.php

echo 'Updating XML data...';
php /home/rmn236/updateSolarXML.php
