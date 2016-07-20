<?php
     	require_once('../../../DAL/TeacherRepository.php');
     	require_once('../../../DAL/LevelRepository.php');
     	require_once('../../../DAL/SubjectRepository.php');
     	require_once('../../../DAL/TimeTableRepository.php');
        require_once('../../../Domain/Teacher.php');
        require_once('../../../Domain/User.php');
        require_once('../../../Domain/Subject.php');
        require_once('../../../Domain/Level.php');
        require_once('../../../Domain/TimeTable.php');
	
        error_reporting( error_reporting() & ~E_NOTICE );
        
        $teacherRepository = new TeacherRepository();
        $levelRepository = new LevelRepository();
        $subjectRepository = new SubjectRepository();
        $timeTableRepository = new TimeTableRepository();
    
    	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    	
    	    LoadAllTeachersAndLevels($levelRepository,$teacherRepository);
    	    if(isset($_GET['subjectId']))
    	    {
    	        LoadSubjectFromDatabase($subjectRepository);
    	    }
    	}
    	
    	//ADD NEW SUBJECT
         if (isset($_POST['add'])) {
             
            $subject = BuildSubject();
            $errors = ValidateSubjectOnAdding($subject,$subjectRepository);
          
           //if the form is valid
            if(empty($errors)){
                
               $subjectSaved = $subjectRepository->InsertSubject($subject);
            
                if($subjectSaved != null){
                    $message = "The subject has been saved successfully";
                    //clear all $_POST variables
                    unset($_POST);    
                    LoadAllTeachersAndLevels($levelRepository,$teacherRepository);    
                }
                else {
                    $errors[] = "An error occurred trying to insert the Subject";
                }
            }
            else
            {
                LoadAllTeachersAndLevels($levelRepository,$teacherRepository);
                
            }
        }
        
         //UPDATE SUBJECT
        if(isset($_POST['update'])){
            
            $subject = BuildSubject();
            $subject->id = $_POST['hdfSubjectId'];
            
            $errors = ValidateSubjectOnUpdating($subject,$subjectRepository);
            
             if(empty($errors)){
                
                $deletedSucessful = $timeTableRepository->DeleteAllTimeTableOfSubject($subject);
                $subjectUpdated = $subjectRepository->UpdateSubject($subject);
                
                if($subjectUpdated != null)
                {
                    $message = "The subject has been updated successfully";
                    unset($_POST);
                    LoadAllTeachersAndLevels($levelRepository,$teacherRepository);    
                    LoadSubjectFromDatabase($subjectRepository);
                }
                else{
                    $errors[] = "An error occurred trying to update the Subject";
                    LoadAllTeachersAndLevels($levelRepository,$teacherRepository);    
                    LoadSubjectFromDatabase($subjectRepository);
                }
             }
             else{
                 LoadAllTeachersAndLevels($levelRepository,$teacherRepository);    
                 LoadSubjectFromDatabase($subjectRepository);
             }
             
        }
        
        
///////////////////PRIVATE METHOD///////////////////////////////////

    function ValidateSubjectOnAdding($subject, $subjectRepo){
        $errors = array();
        
        if (empty($_POST['subject_name']) || empty($_POST['subject_level']) || empty($_POST['subject_teacher'])) {
            $errors[] = "You must fill all the required fields";
        }
        
        $subjectNameTrimmed = trim($subject->name);
        $subjectNameTrimmed = strtolower($subjectNameTrimmed);
        $subjectRepeated = $subjectRepo->GetSubjectByNameAndLevel($subjectNameTrimmed, $subject->level->id);
        if($subjectRepeated != null){
            $errors[] = "There is already a subject with the name '".$subject->name ."' for the level ".$subjectRepeated->level->name;
        }
        
        
        $subjectsOfTeacher = $subjectRepo->GetSubjectsByTeacherId($subject->teacher->id);
        
        foreach ($subject->timeTables as $rowTimeTable) {
            $day = $rowTimeTable->day;
            $startTime = strtotime($rowTimeTable->startTime);
            $finishTime = strtotime($rowTimeTable->finishTime);
            
            if($finishTime <= $startTime)
            {
                $errors[] = "On ".$day." there is a entry where the Finish Time is less or equal than the Start time"; 
            }
            
            
            if($subjectsOfTeacher->timeTables != null)
            {
                foreach ($subjectsOfTeacher->timeTables as $row) {
                    
                    $dayDb = $row->day;
                    $startTimeDb = strtotime($row->startTime);
                    $finishTimeDb = strtotime($row->finishTime);
                    
                    if($day == $dayDb && (($startTime == $startTimeDb || ($startTime > $startTimeDb && $startTime <  $finishTimeDb))
                        || ($finishTime == $finishTimeDb || ($finishTime > $startTimeDb &&  $finishTime < $finishTimeDb ))))
                        {
                            $errors[] = "The Teacher is not avaiable for a class on ".$day ." from ". $rowTimeTable->startTime." to ".$rowTimeTable->finishTime;
                        }
                }
            }
        }    
        
        $subjectsOfLevel = $subjectRepo->GetSubjectsByLevelId($subject->level->id);
        if($subjectsOfLevel->timeTables != null){
            foreach ($subject->timeTables as $rowTimeTable) {
                $day = $rowTimeTable->day;
                $startTime = strtotime($rowTimeTable->startTime);
                $finishTime = strtotime($rowTimeTable->finishTime);
            
                foreach ($subjectsOfLevel->timeTables as $row) {
                    $dayDb = $row->day;
                    $startTimeDb = strtotime($row->startTime);
                    $finishTimeDb = strtotime($row->finishTime);
                
                    if($day == $dayDb && (($startTime == $startTimeDb || ($startTime > $startTimeDb && $startTime <  $finishTimeDb))
                        || ($finishTime == $finishTimeDb || ($finishTime > $startTimeDb &&  $finishTime < $finishTimeDb ))))
                        {
                            $errors[] = "There is already a class scheduled for this level on ".$day ." from ". $rowTimeTable->startTime." to ".$rowTimeTable->finishTime;
                        }
                }
            
            } 
        }
        
        
        return $errors;
    }
    
    function ValidateSubjectOnUpdating($subject, $subjectRepo){
        $errors = array();
        
        if (empty($_POST['subject_name']) || empty($_POST['subject_level']) || empty($_POST['subject_teacher'])) {
            $errors[] = "You must fill all the required fields";
        }
        
        $subjectNameTrimmed = trim($subject->name);
        $subjectNameTrimmed = strtolower($subjectNameTrimmed);
        $subjectRepeated = $subjectRepo->GetSubjectByNameAndLevel($subjectNameTrimmed, $subject->level->id);
    
        if($subjectRepeated != null && $subjectRepeated->id != $subject->id){
            $errors[] = "There is already a subject with the name '".$subject->name ."' for the level ".$subjectRepeated->level->name;
        }
        
         foreach ($subject->timeTables as $rowTimeTable) {
            $day = $rowTimeTable->day;
            $startTime = strtotime($rowTimeTable->startTime);
            $finishTime = strtotime($rowTimeTable->finishTime);
            if($finishTime <= $startTime)
            {
                $errors[] = "On ".$day." there is a entry where the Finish Time is less or equal than the Start time"; 
            }
             
         }
        
        $subjectsOfTeacher = $subjectRepo->GetSubjectsByTeacherId($subject->teacher->id);
        
        foreach ($subject->timeTables as $rowTimeTable) {
            $day = $rowTimeTable->day;
            $startTime = strtotime($rowTimeTable->startTime);
            $finishTime = strtotime($rowTimeTable->finishTime);
            
            if($subjectsOfTeacher->timeTables != null)
            {
                foreach ($subjectsOfTeacher->timeTables as $row) {
                    
                    if ($rowTimeTable->id == $row->id) {
                        continue;
                    }
                    
                    $dayDb = $row->day;
                    $startTimeDb = strtotime($row->startTime);
                    $finishTimeDb = strtotime($row->finishTime);
                    
                    if($day == $dayDb && (($startTime == $startTimeDb || ($startTime > $startTimeDb && $startTime <  $finishTimeDb))
                        || ($finishTime == $finishTimeDb || ($finishTime > $startTimeDb &&  $finishTime < $finishTimeDb ))))
                        {
                            $errors[] = "The Teacher is not avaiable for a class on ".$day ." from ". $rowTimeTable->startTime." to ".$rowTimeTable->finishTime;
                        }
                }
            }
        }    
        
        $subjectsOfLevel = $subjectRepo->GetSubjectsByLevelId($subject->level->id);
        if($subjectsOfLevel->timeTables != null){
            foreach ($subject->timeTables as $rowTimeTable) {
                $day = $rowTimeTable->day;
                $startTime = strtotime($rowTimeTable->startTime);
                $finishTime = strtotime($rowTimeTable->finishTime);
            
                foreach ($subjectsOfLevel->timeTables as $row) {
                    
                    if ($rowTimeTable->id == $row->id) {
                        continue;
                    }
                    
                    $dayDb = $row->day;
                    $startTimeDb = strtotime($row->startTime);
                    $finishTimeDb = strtotime($row->finishTime);
                
                    if($day == $dayDb && (($startTime == $startTimeDb || ($startTime > $startTimeDb && $startTime <  $finishTimeDb))
                        || ($finishTime == $finishTimeDb || ($finishTime > $startTimeDb &&  $finishTime < $finishTimeDb ))))
                        {
                            $errors[] = "There is already a class scheduled for this level on ".$day ." from ". $rowTimeTable->startTime." to ".$rowTimeTable->finishTime;
                        }
                }
            
            } 
        }
        
        
        return $errors;
    }
    

    function BuildSubject(){
            
        $subject = new Subject();
        $subject->name = $_POST['subject_name'];
        $subject->level = new Level();
        $subject->level->id = $_POST['subject_level'];
        $subject->teacher = new Teacher();
        $subject->teacher->id = $_POST['subject_teacher'];
        
        //Process Time table
        $hdfTimeTableId = $_POST['hdfTimeTableId']; //array
        $BX_weekday = $_POST['BX_weekday']; //array
        $BX_start_time_hour = $_POST['BX_start_time_hour']; //array
        $BX_start_time_minute = $_POST['BX_start_time_minute']; //array
        $BX_start_timeofday = $_POST['BX_start_timeofday']; //array
        $BX_finish_time_hour = $_POST['BX_finish_time_hour']; //array
        $BX_finish_time_minute = $_POST['BX_finish_time_minute']; //array
        $BX_finish_timeofday = $_POST['BX_finish_timeofday']; //array
        
        $numberOfRows = sizeof($BX_weekday);
        $timeTables = array();
        for ($i = 0; $i < $numberOfRows; $i++) {
             $timeTable = new TimeTable();
             $timeTable->id = $hdfTimeTableId[$i];
             $timeTable->day = $BX_weekday[$i];
             $timeTable->startTime = $BX_start_time_hour[$i]. ":". $BX_start_time_minute[$i];   //GetTime($BX_start_timeofday[$i],$BX_start_time_hour[$i],$BX_start_time_minute[$i]);
             $timeTable->finishTime = $BX_finish_time_hour[$i]. ":".  $BX_finish_time_minute[$i]; //GetTime($BX_finish_timeofday[$i],$BX_finish_time_hour[$i],$BX_finish_time_minute[$i]);
             $timeTables[] = $timeTable; 
        }
        
        $subject->timeTables = $timeTables;
        return $subject;
    }
    
    function LoadAllTeachersAndLevels($levelRepo, $teacherRepo){
        global $levels, $teachers;
        $levels = $levelRepo->GetAllLevels();
    	$teachers = $teacherRepo->GetAllTeachers();
    }
    
    function GetTime($timeOfDay, $hour, $minute){
        $dt = new DateTime;
        $offset = $timeOfDay == 'PM' ? 12 : 0;
        $dt->setTime($hour + $offset, $minute);
        return $dt->format('H:i');
    }
    
    function LoadSubjectFromDatabase($subjectRepo){
        global $subjectBd;
        $subjectId = $_GET['subjectId'];
        $subjectBd = $subjectRepo->GetSubjectsById($subjectId);
    }
    
?>