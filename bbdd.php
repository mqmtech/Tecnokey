<?php

exec("php app/console cache:clear");

exec("mysqldump --opt --single-transaction -u mqmtech_root -pmqmd3vpass --host=localhost mqmtech_tecnokey > nbackup.sql");
//exec("mysql -u mqmtech_root -pmqmd3vpass --host=localhost mqmtech_demo < nbackup.sql");

//exec("mysql -u mqmtech_root -pmqmd3vpass --host=localhost mqmtech_demo < nbackup_2.sql");