<?php

class ApiServer{
  
  public function check_param($paramList){
    $param = [];
    forEach($paramList as $key=>$item){
      $res = $this->check_value($item);
      if(!$res){
        echo('this parameter must be setted: '.$item);
        die;
      }
      $param[$item] = $res;
    }
    return $param;
  }
  
  private function check_value($param){
    if(isset($_GET[$param])){
      return $_GET[$param];
    }elseif(isset($_POST[$param])){
      return $_POST[$param];
    }else{
      return false;
    }
  }
}
