<?php

class ClassDto
{
    public $subjectId;
    public $subjectName;
    public $levelId;
    public $levelName;
    public $section;
    public $year;
    public $grade;
    public $numberofStudents;
    
    

    public function __set($name, $value){
    throw new Exception("Variable ".$name." has not been set.", 1);
    }

    public function __get($name){
      throw new Exception("Variable ".$name." has not been declared and can not be get.", 1);
    }
}


