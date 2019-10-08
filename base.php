<?php
    class Base{
        private $host;
        private $login;
        private $password;
        private $base;
        
        function __construct($host, $login, $password, $base){
            $this->host = $host;
            $this->login = $login;
            $this->password = $password;
            $this->base = $base;
        }
        
        private function conn(){
            $b = mysqli_connect($this->host, $this->login, $this->password, $this->base);
            $query = "SET NAMES 'utf8'";
            mysqli_query($b,$query);
            return $b;
        }
        
        public function query($query, $getres){
            $result = [];
            $b = $this->conn();
            $res = mysqli_query($b, $query);
            if($getres){
                while($buf = mysqli_fetch_assoc($res)){
                    $result[] = $buf;
                }
                return $result;
            }
        }
    }