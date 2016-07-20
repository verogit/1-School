<?php

class Grade
{
    public $idStudent;
    public $idSubject;
    public $grade;
    
    public function __set($name, $value){
    throw new Exception("Variable ".$name." has not been set.", 1);
    }

    public function __get($name){
      throw new Exception("Variable ".$name." has not been declared and can not be get.", 1);
    }
}

?>