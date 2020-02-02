<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/base.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/finance2019/api/v1/api.php');
$base = new Base();
$api = new ApiServer();
$needs = array('id');

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
    checks.id = '" . $params['id'] . "'";

$res = $base->query($query,true);
if(count($res) == 0) {
  echo('empty period');
  die;
}
$query = "
  SELECT
    moves.id AS moveId,
    moves.checkn AS moveCheck,
    moves.sum AS moveSum,
    moves.itemn AS moveItemId,
    items.name AS moveItemName,
    moves.comment AS moveComment
  FROM
    moves
  LEFT JOIN
    items
  ON
    items.id = moves.itemn
  WHERE
    moves.checkn = '" . $params['id'] . "'";

$res[0]['operations'] = $base->query($query,true);

header('Content-Type: text/json');
echo(json_encode($res[0]));