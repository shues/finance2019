<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('id','name');

$params = $api->check_param($needs);

$query = "UPDATE types SET name='".$params['name']." WHERE id=".$params['id'];

header('Content-Type: text/json');
$base->query($query,false);
echo($query);