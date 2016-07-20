	<?php
     	require_once('../../../DAL/UserRepository.php');
     	require_once('../../../DAL/TeacherRepository.php');
        require_once('../../../Domain/Teacher.php');
        require_once('../../../Domain/User.php');

        error_reporting( error_reporting() & ~E_NOTICE );
        	
        $userRepository = new UserRepository();
        $teacherRepository = new TeacherRepository();
        
        
        
        //Get info of a teacher if a teacher id is passed in the querystring(url)
    	if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['teacherId'])) {
    	    $teacherId = $_GET['teacherId'];
    	    $teacher = $teacherRepository->GetTeacherById($teacherId);
    	    
    	    if($teacher != null){
    	       SetTeacherVariables($teacher);
    	    }
    	}
    	
        //ADD NEW
         if (isset($_POST['add'])) {
             
            $teacher = BuildTeacher();
            
            $errors = ValidateTeacherOnAdding($teacher,$userRepository,$teacherRepository);
            
            //if the form is valid
            if(empty($errors)){
                
                $teacherSaved = $teacherRepository->InsertTeacher($teacher);
            
                if($teacherSaved != null)
                {
                    $message = "The teacher has been saved successfully";
                     //clear all $_POST variables
                    unset($_POST);
                }
                else
                {
                    $errors[] = "An error occurred trying to insert the Teacher";
                }
            }
        }
        
        //UPDATE
        if(isset($_POST['update'])){
            
            $teacher = BuildTeacher();
            $teacher->id = $_POST['hdfTeacherId'];
            $teacher->idUser = $_POST['hdfUserId'];
            
            $errors = ValidateTeacherOnUpdating($teacher,$userRepository,$teacherRepository);
            
             if(empty($errors)){
                 
                $teacherUpdated = $teacherRepository->UpdateTeacher($teacher);
                
                if($teacherUpdated != null)
                {
                    $message = "The teacher has been updated successfully";
                    unset($_POST);
                    SetTeacherVariables($teacher);    
                }
                else{
                    $errors[] = "An error occurred trying to update the Teacher";
                }
             }
             else{
                 SetTeacherVariables($teacher);
             }
             
        }
        
        

      
/////// PRIVATE METHODS/////////////////
        
        function ValidateTeacherOnAdding($teacher, $userRepo, $teacherRepo){
            $errors = array();
            
            if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['first_name']) || empty($_POST['last_name'])
            || empty($_POST['address']) || empty($_POST['email']) || empty($_POST['date_of_birth']) || empty($_POST['gender'])
            || empty($_POST['telephone'])) {
                $errors[] = "You must fill all the required fields";
            }
            
            $user = $userRepo->GetUserByUsername($teacher->userName);
            if($user != null){
                $errors[] = "The username is already in use.";
            }
            
            $teacher = $teacherRepo->GetTeacherByEmail($teacher->email);
            if($teacher != null){
                $errors[] = "The email address is already in use.";
            }
            
            return $errors;
        }
        
        function ValidateTeacherOnUpdating($teacher, $userRepo, $teacherRepo){
            $errors = array();
            
            if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['first_name']) || empty($_POST['last_name'])
            || empty($_POST['address']) || empty($_POST['email']) || empty($_POST['date_of_birth']) || empty($_POST['gender'])
            || empty($_POST['telephone'])) {
                $errors[] = "You must fill all the required fields";
            }
            
            $userInDatabase = $userRepo->GetUserById($teacher->idUser);
            
            if($userInDatabase->userName != $teacher->userName){
                $user = $userRepo->GetUserByUsername($teacher->userName);
                if($user != null){
                    $errors[] = "The username is already in use.";
                }
            }
            
            $teacherInDatabase = $teacherRepo->GetTeacherById($teacher->id);
            if($teacherInDatabase->email != $teacher->email){
                $teacherExist = $teacherRepo->GetTeacherByEmail($teacher->email);
                if($teacherExist != null){
                    $errors[] = "The email address is already in use.";
                }
            }
            
            return $errors;
        }
        
        function BuildTeacher(){
            
            $teacher = new Teacher();
            $teacher->userName = $_POST['username'];
            $teacher->password = $_POST['password'];
            $teacher->idRole = 2; //idrole of teacher
            $teacher->roleName = "teacher";
            $teacher->firstName = $_POST['first_name'];
            $teacher->lastName = $_POST['last_name'];
            $teacher->address = $_POST['address'];
            $teacher->email = $_POST['email'];
            $teacher->dateOfBirth = $_POST['date_of_birth'];
            $teacher->gender = $_POST['gender'];
            $teacher->telephone = $_POST['telephone'];
            $teacher->isDeleted = 0;
            
            return $teacher;
        }
        
        //This variables are set in order to fill the form after a posting with the same values that the user inputted 
        function SetTeacherVariables($objectTeacher){
            global $firstname,$lastname,$username,$password,$dateOfBirth,$gender,$telephone,$email,$address,$idUser;
            $firstname = $objectTeacher->firstName;
	        $lastname = $objectTeacher->lastName;
	        $username = $objectTeacher->userName;
	        $password = $objectTeacher->password;
	        $dateOfBirth = $objectTeacher->dateOfBirth;
	        $gender = $objectTeacher->gender;
	        $telephone = $objectTeacher->telephone;
	        $email = $objectTeacher->email;
	        $address = $objectTeacher->address;
	        $idUser = $objectTeacher->idUser;
        }
        
	?>