<!DOCTYPE html>
<html>
    <?php
        include_once('../../../Domain/User.php');
        
        session_start();
        
        if(isset($_SESSION['user'])){
            $user = $_SESSION['user'];
            if ($user->roleName != 'admin'){
                header("location: ../Login.php");
            }
        }else{
            header("location: ../home.php");
        }
    ?>
    
    <head>
        <title><?php echo $page_title;?></title>
        <meta charset="utf-8" />
 		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<link rel="stylesheet" type="text/css" href="../../css/style.css">
	</head>
	
	<body>
	    <header id="header">
            <h1><img id="logo" src="../../images/logo.png" alt="logo"></img></h1>
            <nav>
			    <div id="nav">
			        <a class='sign_out' href='../SignOut.php'>Sing Out</a>
			            <ul class="topnav">
			                <li class="menu">Menu</li>
				            <li><a href="ManageTeacher.php">Manage Teachers</a></li>
				            <li class="separetion">|</li>
				            <li><a href="ManageStudent.php">Manage Students</a></li>
			    	        <li class="separetion">|</li>
				            <li><a href="ManageSubject.php">Manage Subjects</a></li>
				            <li class="separetion">|</li>
				            <li><a href="ManageClassroom.php">Manage Classrooms</a></li>
				            <li class="icon">
                                <a href="javascript:void(0);" style="font-size:20px;" onclick="headerChange()">☰</a>
                            </li>
                        </ul>
			        </div>
	    	</nav>
        </header>

        <div id="content">
            
    <script type="text/javascript">
        function headerChange() {
            document.getElementsByClassName("topnav")[0].classList.toggle("responsive");
        }
    </script>
        
