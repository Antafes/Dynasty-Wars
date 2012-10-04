<?php
session_start();
include_once(__DIR__.'/../config.php');

header('Content-Type: application/json');

include_once(__DIR__.'/../bl/general.inc.php');
include_once(__DIR__.'/../bl/login.php');
include_once(__DIR__.'/../dal/tribunal.php');
include_once(__DIR__.'/../bl/tribunal.php');

$item_list = json_decode($_GET['items']);
$new_item_list = array();
foreach ($item_list as $part)
	$new_item_list[$part->name] = utf8_decode($part->value);
$item_list = $new_item_list;

bl\tribunal\deleteComment($item_list['tcoid']);

echo json_encode(array('status' => 'ok'));