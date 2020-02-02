<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('category');

$params = $api->check_param($needs);


if($params['category'] == 'all'){
  $query = "SELECT * FROM items";
}else{
  $query = "SELECT * FROM items WHERE category ";  
}

if($params['category'] == 'null'){
  $query .= 'IS NULL';
}else{
  $query .= '='.$params['category'];
}

header('Content-Type: text/json');
$res = $base->query($query,true);
echo json_encode($res);
