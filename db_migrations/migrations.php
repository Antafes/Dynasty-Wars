<?php
require_once(dirname(__FILE__).'/../dw/lib/config.php');
require_once(dirname(__FILE__).'/../dw/lib/util/mysql.php');
require_once(dirname(__FILE__).'/../dw/lib/bl/general.php');

$GLOBALS['config']['migrations_dir'] = dirname(__FILE__).'/files/';

$result = \util\mysql\migration_manager($_REQUEST);

echo $result;