<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('id');

$params = $api->check_param($needs);

$query = 'DELETE FROM checks WHERE id='.$params['id'];
$base->query($query,false);

$query = 'DELETE FROM moves WHERE checkn='.$params['id'];
$res = $base->query($query,false);

header('Content-Type: text/json');
echo($res);