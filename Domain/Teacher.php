<?php

require_once('User.php');

class Teacher extends User
{
    public $id;
    public $firstName;
    public $lastName;
    public $address;
    public $email;
    public $dateOfBirth;
    public $gender;
    public $idUser;
    public $telephone;
    public $isDeleted;
    
    public function __set($name, $value){
    throw new Exception("Variable ".$name." has not been set.", 1);
    }

    public function __get($name){
      throw new Exception("Variable ".$name." has not been declared and can not be get.", 1);
    }
  
}

?>