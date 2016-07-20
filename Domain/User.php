<?php

class User
{
    public $id;
    public $userName;
    public $password;
    public $idRole;
    public $roleName;
    
     public function __set($name, $value){
    throw new Exception("Variable ".$name." has not been set.", 1);
    }

    public function __get($name){
      throw new Exception("Variable ".$name." has not been declared and can not be get.", 1);
    }
    
}

?>