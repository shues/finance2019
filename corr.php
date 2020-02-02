<?php
  require_once('./base.php');

  $base = new Base();

  $query = "SELECT id from checks";
  $res = $base->query($query,true);
  forEach($res as $key=>$value){
    $id = $value['id'];
    $bquery = "SELECT sum(sum) AS sum from moves where checkn = $id";
    $bsum = $base->query($bquery,true)[0]['sum'];
    logged($bsum);
    $bquery = "UPDATE checks SET sum = $bsum WHERE id = $id";
    $base->query($bquery,false);
    echo("check $id  sum = $bsum <br>");
  }

function logged($par){
  echo('<pre>');
  print_r($par);
  echo('</pre>');

}
//*/