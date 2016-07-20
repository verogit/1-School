<?php

class TimetableDto{
    
    public $subject;
    public $day;
    public $timeStart;
    public $timeFinish;
    
    public function __set($name, $value){
    throw new Exception("Variable ".$name." has not been set.", 1);
    }

    public function __get($name){
      throw new Exception("Variable ".$name." has not been declared and can not be get.", 1);
    }
}
?>