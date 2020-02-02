<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('id','date','contragent','type','items','sum');

$params = $api->check_param($needs);

$query = "
  UPDATE
    `checks` 
  SET
    date = '" . $params['date'] . "', 
    contragent = '" . $params['contragent'] . "', 
    type = '" . $params['type'] . "',
    sum = '" . $params['sum'] . "'
  WHERE
    id = " . $params['id'] . "
  ";
$base->query($query,false);

$query = 'DELETE FROM moves WHERE checkn='.$params['id'];
$base->query($query,false);

$items = json_decode($params['items']);
forEach($items as $key=>$value){
  $query = "
    INSERT INTO
      moves
    VALUES(
      default,
      " . $params['id'] . ",
      " . $value->sum . ",
      " . $value->itemn . ",
      '" . $value->comment . "'
    )
  ";
  $base->query($query,false);
}
$message = "check " . $params['id'] . " updated";
header('Content-Type: text/json');
echo ('{"res":true, "message":"' . $message . '"}');