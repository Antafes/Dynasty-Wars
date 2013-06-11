<?php
require_once(__DIR__.'/../dw/lib/config.default.php');
require_once(__DIR__.'/../dw/lib/util/mysql.php');
require_once(__DIR__.'/../dw/lib/bl/general.php');

$GLOBALS['config']['migrations_dir'] = __DIR__.'/files/';

$result = \util\mysql\migration_manager($_REQUEST);

echo $result;