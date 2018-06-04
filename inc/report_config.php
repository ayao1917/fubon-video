<?php

$dsn = "sqlite:/home/fubon/FDATA/log.dat";

$dbh = new PDO($dsn, '', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$command = "attach '/home/fubon/FDATA/u.new' as ag1";
$sth = $dbh->prepare($command);
$sth->execute();

$command = "attach '/home/fubon/FDATA/u.new' as ag2";
$sth = $dbh->prepare($command);
$sth->execute();

$command = "attach '/home/fubon/FDATA/main.dat' as m";
$sth = $dbh->prepare($command);
$sth->execute();
