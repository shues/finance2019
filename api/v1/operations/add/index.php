<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('date','contragent','type','items','sum');

$params = $api->check_param($needs);

$query = "
  INSERT INTO 
    `checks` 
  VALUES ( 
    default, 
    '" . $params['date'] . "', 
    '" . $params['contragent'] . "', 
    '" . $params['type'] . "',
    '" . $params['sum'] . "'
  )";
$base->query($query,false);

$query = "SELECT id from `checks`";
$idList = $base->query($query,true);
$lastId = $idList[count($idList)-1]['id'];

$items = json_decode($params['items']);
forEach($items as $key=>$value){
  $query = "
    INSERT INTO
      moves
    VALUES(
      default,
      $lastId,
      " . $value->sum . ",
      " . $value->itemn . ",
      '" . $value->comment . "'
    )
  ";
  echo $query;
  $base->query($query,false);
}
$message = "check $lastId added";
header('Content-Type: text/json');
echo ('{"res":true, "message":"' . $message . '"}');