<?php
    	require_once('../../DAL/UserRepository.php');
    	require_once('../../Domain/User.php');
    	
        error_reporting( error_reporting() & ~E_NOTICE );

        session_start();
        
        if (isset($_POST['submit'])) {
            if (empty($_POST['userName']) || empty($_POST['password'])) {
                $error = "Username or Password is invalid";
            }
             else {
                 
                 $userRepo = new UserRepository();
                 $user = $userRepo->GetUserByUsernameAndPassword($_POST['userName'],$_POST['password']);
                
                 if($user != null){
                    $_SESSION['user']=$user;
                    $_SESSION['error_login']=null;
                    
                    if($user->roleName == "admin"){
    	    	        header("location: Admin/MenuAdmin.php");
    		        }
    		        elseif ($user->roleName == "teacher")  {
    		            header("location: Teacher/Subjects.php");
    		        }
    		        elseif ($user->roleName == "student")  {
    		            header("location: Student/StudentSubjects.php");
    		        }
                 }
                 else{
                    $error = "Username or Password is invalid";
                    $_SESSION['error_login']=$error;
                    header("Location: home.php"); 
                 }
            }
        }
    	
	?>