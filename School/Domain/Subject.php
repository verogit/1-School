<?php

class Subject
{
    public $id;
    public $name;
    public $level; //type of Level class
    public $teacher; //type of Teacher class
    public $timeTables = array(); //array of Time Table objects
    
    
    public function __set($name, $value){
    throw new Exception("Variable ".$name." has not been set.", 1);
    }

    public function __get($name){
      throw new Exception("Variable ".$name." has not been declared and can not be get.", 1);
    }
}


