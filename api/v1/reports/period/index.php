<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('start','finish');

$params = $api->check_param($needs);

$query = "
  SELECT 
    checks.id AS checkId,
    checks.date AS checkDate,
    checks.contragent AS contragentId,
    contragents.name AS contragentName,
    checks.type AS operationType,
    types.name AS operationName,
    checks.sum AS checkSumm
  FROM 
    checks 
  LEFT JOIN
    contragents
  ON
    contragents.id = checks.contragent
  LEFT JOIN
    types
  ON
    types.id = checks.type
  WHERE 
    date >= '" . $params['start'] . "'
  AND
    date <= '" . $params['finish'] . "'
  ORDER BY
    checkDate
  ";

header('Content-Type: text/json');
$res = $base->query($query,true);
echo(json_encode($res));