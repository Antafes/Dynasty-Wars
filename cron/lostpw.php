<?php
require_once(dirname(__FILE__)."/../dw/lib/config.php");
require_once(dirname(__FILE__)."/../dw/lib/bl/unit.php");
require_once(dirname(__FILE__)."/../dw/lib/dal/unit.php");
require_once(dirname(__FILE__).'/../dw/lib/util/mysql.php');
require_once(dirname(__FILE__).'/../dw/lib/util/dateTime.php');

$now = new \DWDateTime();
$now->sub(new DateInterval('PT1H'));
$sql = 'DELETE FROM dw_lostpw WHERE sent_datetime <= '.util\mysql\sqlval($now->format()).'';
util\mysql\query($sql);