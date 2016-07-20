<?php
    require_once('../../../DAL/ClassroomRepository.php');
    require_once('../../../DAL/UserRepository.php');
    require_once('../../../DAL/StudentRepository.php');
    require_once('../../../Domain/Level.php');
    require_once('../../../Domain/Classroom.php');
    require_once('../../../Domain/Student.php');
    require_once('../../../Domain/User.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );

    $userRepository = new UserRepository();
    $classroomRepository = new ClassroomRepository();
    $studentRepository = new StudentRepository();
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        
        if (isset($_GET['studentId'])) {
            global $studentDB;
        
        $studentId = $_GET['studentId'];
        $studentDB = $studentRepository->GetStudentById($studentId);

        }
        
        LoadAllClassrooms($classroomRepository);
    }
    
    //ADD NEW
    if (isset($_POST['add'])) {
        $student = BuiltStudent();
        
        $errors = ValidateStudentOnAdding($student,$userRepository,$studentRepository);
        
        
        if (empty($errors)) {
            $studentSaved = $studentRepository->InsertStudent($student);
            
            if($studentSaved != null)
            {
                $message = "The student has been saved successfully";
                //clear all $_POST variables
                unset($_POST);
                LoadAllClassrooms($classroomRepository);
            }
            else
            {
                $errors[] = "An error occurred trying to insert the Student";
                LoadAllClassrooms($classroomRepository);
            }
        }
        else{
            
            LoadAllClassrooms($classroomRepository);
        }
    }
    
    if (isset($_POST['update'])) {
        $student = BuiltStudent();
        $student->id = $_POST['hdfStudentId'];
        $student->idUser = $_POST['hdfUserId'];
        
        $errors = ValidateStudentOnUpdating($student,$userRepository,$studentRepository);
        
        
        if (empty($errors)) {
            $studentUpdate = $studentRepository->UpdateStudent($student);
            
            if($studentUpdate != null)
            {
                $message = "The student has been update successfully";
                LoadAllClassrooms($classroomRepository);
            }
            else
            {
                $errors[] = "An error occurred trying to update the Student";
                LoadAllClassrooms($classroomRepository);
            }
        }
        else{
            LoadAllClassrooms($classroomRepository);
        }
    }
    
    ///////////////////PRIVATE METHOD///////////////////////////////////
    
    function BuiltStudent(){
        $student = new Student();
        $student->userName = $_POST['username'];
        $student->password = $_POST['password'];
        $student->idRole = 3; //idRole of Student
        $student->roleName = "student";
        $student->firstName = $_POST['first_name'];
        $student->lastName = $_POST['last_name'];
        $student->address = $_POST['address'];
        $student->emailStudent = $_POST['emailStudent'];
        $student->emailParent = $_POST['emailParent'];
        $student->dateOfBirth = $_POST['date_of_birth'];
        $student->gender = $_POST['gender'];
        $student->telephone = $_POST['telephone'];
        $student->isDeleted = 0;
        $student->classroom = new Classroom();
        $student->classroom->id = $_POST['classroom'];
            
        return $student;
    }
    
    function LoadAllClassrooms($classroomRepository){
        global $classrooms;
        $classrooms = $classroomRepository->GetAllClassrooms();
    }
    
    function ValidateStudentOnAdding($student,$userRepository,$studentRepository){
        $errors = array();
        
        if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['first_name']) || empty($_POST['last_name'])
        || empty($_POST['address']) || empty($_POST['emailParent']) || empty($_POST['date_of_birth']) || empty($_POST['gender'])
        || empty($_POST['telephone'])) {
            $errors[] = "You must fill all the required fields";
        }
        
        $user = $userRepository->GetUserByUsername($student->userName);
        if($user != null){
            $errors[] = "The username is already in use.";
        }
        
        $studentEmailStudent = $studentRepository->GetStudentByEmailStudent($student->emailStudent);
        if($studentEmailStudent != null){
            $errors[] = "The email address of the student is already in use.";
        }
        
        return $errors;
    }
    
    function ValidateStudentOnUpdating($student, $userRepo, $studentRepo){
            $errors = array();
            
             if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['first_name']) || empty($_POST['last_name'])
            || empty($_POST['address']) || empty($_POST['emailParent']) || empty($_POST['date_of_birth']) || empty($_POST['gender'])
            || empty($_POST['telephone'])) {
                $errors[] = "You must fill all the required fields";
            }
            
            $userInDatabase = $userRepo->GetUserById($student->idUser);
            
            if($userInDatabase->userName != $student->userName){
                $user = $userRepo->GetUserByUsername($student->userName);
                if($user != null){
                    $errors[] = "The username is already in use.";
                }
            }
            
            $studentInDatabase = $studentRepo->GetStudentById($student->id);
            if($studentInDatabase->emailStudent != $student->emailStudent){
                $studentExist = $studentRepo->GetStudentByEmailStudent($student->emailStudent);
                if($studentExist != null){
                    $errors[] = "The email address of the student is already in use.";
                }
            }
            
            if($studentInDatabase->emailParent != $student->emailParent){
                $studentExist = $studentRepo->GetStudentByEmailParent($student->emailParent);
                if($studentExist != null){
                    $errors[] = "The email address of the student is already in use.";
                }
            }
            
            return $errors;
        }
            
?>