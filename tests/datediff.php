<?php
require_once('../dw/lib/util/dateTime.php');
require_once('../dw/lib/util/dateInterval.php');

$now = new DWDateTime();
$date = DWDateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', 1281157285));
$diff = $now->diff($date);
var_dump($diff);