<?php

class StudentDto 
{
    public $id;
    public $firstName;
    public $lastName;
    public $gender;
    public $subjectId;
    public $grade;
    
    public function __set($name, $value){
    throw new Exception("Variable ".$name." has not been set.", 1);
    }

    public function __get($name){
      throw new Exception("Variable ".$name." has not been declared and can not be get.", 1);
    }
  
}

?>