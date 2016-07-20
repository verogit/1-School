<?php
    require_once('../../../DAL/LevelRepository.php');
    require_once('../../../DAL/ClassroomRepository.php');
    require_once('../../../Domain/Level.php');
    require_once('../../../Domain/Classroom.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );

    $levelRepository = new LevelRepository();
    $classroomRepository = new ClassroomRepository();
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        LoadAllLevels($levelRepository);
    }
    
    //Get info of a classroom if a classroom id is passed in the querystring(url)
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['classroomId'])) {
        global $classroomBd;
        $classroomId = $_GET['classroomId'];
        $classroomBd = $classroomRepository->GetClassroomsById($classroomId);
    }
    	
    	
    //ADD NEW CLASS
    if (isset($_POST['add'])) {
             
        $classroom = BuildClassroom();
        $errors = ValidateClassroomOnAddingAndUpdate($classroom,$classroomRepository);
          
        //if the form is valid
        if(empty($errors)){
                
            $classroomSaved = $classroomRepository->InsertClassroom($classroom);
            if ($classroomSaved != null) {
                $message = "The classroom has been saved successfully";
                //clear all $_POST variables
                unset($_POST);    
                LoadAllLevels($levelRepository);
            }else{
                $errors[] = "An error occurred trying to insert the Classroom";
                LoadAllLevels($levelRepository);
            }

        }
        else{
          LoadAllLevels($levelRepository);  
        } 
    }
    
    //UPDATE A CLASS
    if (isset($_POST['update'])) {
             
        $classroom = BuildClassroom();
        $classroom->id = $_POST['hdfClassroomId'];
        $errors = ValidateClassroomOnAddingAndUpdate($classroom,$classroomRepository);
          
        //if the form is valid
        if(empty($errors)){
                
            $classroomUpdate = $classroomRepository->UpdateClassroom($classroom);
            if ($classroomUpdate != null) {
                $message = "The classroom has been updated successfully";
                //clear all $_POST variables
                unset($_POST);    
                LoadAllLevels($levelRepository);
                $classroomBd = $classroom;
                
            }else{
                $errors[] = "An error occurred trying to insert the Classroom";
                LoadAllLevels($levelRepository);
                $classroomBd = $classroom;
            }

        }
        else{
          LoadAllLevels($levelRepository);  
        } 
    }
    
    ///////////////////PRIVATE METHOD///////////////////////////////////
    function BuildClassroom(){
        
        $classroom = new Classroom();
        $classroom->level = new Level();
        $classroom->level->id = $_POST['level'];
        $classroom->year = $_POST['year'];
        $classroom->sectionName = $_POST['section'];
        return $classroom;
    }
    
    function ValidateClassroomOnAddingAndUpdate($classroom,$classroomRepository){
        $errors = array();
    
        $classroomValidate = $classroomRepository->ExistClassroomByLevelYearSection($classroom);
        
        if ($classroomValidate) {
            $errors[] = "That classroom is already in the system";
        }
        return $errors;
    }
    
    function LoadAllLevels($levelRepo){
        global $levels;
        $levels = $levelRepo->GetAllLevels();
    }
    
?>