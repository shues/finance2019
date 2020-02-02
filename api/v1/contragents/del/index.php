<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('id');

$params = $api->check_param($needs);

$query = 'DELETE FROM contragents WHERE id='.$params['id'];

header('Content-Type: text/json');
$base->query($query,false);
echo($query);