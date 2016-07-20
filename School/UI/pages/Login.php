<?php
    	require_once('../controllers/Admin/LoginController.php');
    	
    	if(isset($_SESSION['user'])){
    		
    		if(strtolower($_SESSION['user']->roleName) == "admin"){
    	    	header("location: Admin/HomeAdmin.php");
    		}elseif (strtolower($_SESSION['user']->roleName) == "teacher") {
    			header("location: Teacher/HomeTeacher.php");
    		}
    		elseif (strtolower($_SESSION['user']->roleName) == "student") {
    			header("location: Student/HomeStudent.php");
    		}
    	}
            
    	if(isset($_SESSION['error_login'])){
    		echo "<span>".$_SESSION['error_login']."</span>";// IF EXIST A ERROR IN THE LOGIN PRINT A ERROR
    	}
           
	?>
	
	<form id="login" action="" method="POST">
		<p>User Name: <input type="text" name="userName" required/></p>
		<p>Password: <input type="password" name="password" required/></p>
		<button type="submit" name="submit" value="Submit">Sign In</button>
	</form>
	<a class="forgot" href="ForgotPassword.php">Forgot Password</a>
</body>
</html>

