<?php
// Creates a DateTime for the current datetime
var_dump(new DateTime());

$now = new DateTime();
$nowStr = $now->format("D, d M Y H:i:s");
echo "It is currently $nowStr";

date_default_timezone_set("America/Moncton");

var_dump(new DateTime());

$now = new DateTime();
$nowStr = $now->format("D, d M Y H:i:s");
echo "It is currently $nowStr<br>";

$nowStr = $now->format("l, j F Y - g:i:sa");
echo "It is currently $nowStr<hr>";

echo "time(): " . time() . "<br>";

$newDate = new DateTime();

var_dump($newDate);

$newDate->setTimestamp(time() - 60);

var_dump($newDate);

echo "<hr>";

$timestampFromStr = strtotime("tomorrow 8pm");

var_dump($timestampFromStr);

$dateFromTimestamp = new DateTime();
$dateFromTimestamp->setTimestamp($timestampFromStr);

var_dump($dateFromTimestamp);

echo '<hr>';

$today = new DateTime();
$dateStr = $today->format("Ymd");

echo $dateStr;

echo '<hr>';

$now = new DateTime();
var_dump($now);
$interval = new DateInterval("P9DT3H10M");

$now->add($interval);
var_dump($now);
