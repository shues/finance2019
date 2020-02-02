<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('date');

$params = $api->check_param($needs);

$query = "
  SELECT 
    checks.type AS operationType,
    types.name AS operationName,
    SUM(checks.sum) AS Summ
  FROM 
    checks 
  LEFT JOIN
    types
  ON
    types.id = checks.type
  WHERE 
    date <= '" . $params['date'] . "'
  GROUP BY
    operationType,
    operationName
  ";
//echo $query;
header('Content-Type: text/json');
$res = $base->query($query,true);
echo(json_encode($res));