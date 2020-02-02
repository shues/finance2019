<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('id');

$params = $api->check_param($needs);

$query = "SELECT * FROM types WHERE id = ".$params['id'];

header('Content-Type: text/json');
$res = $base->query($query,true);
echo json_encode($res);