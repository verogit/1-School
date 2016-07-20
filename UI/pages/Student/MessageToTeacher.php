<?php 
   
    require_once('../../controllers/Student/MessageToTeacherController.php');
     
    error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'Message to Teacher'; 
    include ("../../includes/headerStudent.php");
     
     echo "<p class='backButton'><a class='backButton' href='StudentSubjects.php'>Back</a></p>";
     
    if(!empty($errors)){
		echo '<h1>Error!</h1>
		<p class="error">There are problems with this form. Please correct the following errors:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
	}
    
    if(isset($message)){
   		echo "<span style='color:green;'>".$message."</span>";
    }
    
    if($teacher != null && $subject != null)
    {
        
        echo '<ul id="classDetail">
          <li>Subject: '.$subject->name .'</li>
          <li>Level: '.$subject->level->name.'</li>
          <li>Section: '. $_GET['section'] .' </li>
          <li>Year: '. $_GET['year'] .' </li>
        </ul> '; 
        
        echo '<ul id="classDetail">
          <li>First Name: '.$teacher->firstName .'</li>
          <li>Last Name: '.$teacher->lastName.'</li>
          <li>Email: '.$teacher->email.'</li>
        </ul> '; 
        
        
        echo '<form action="" method="POST">
        
            <p> Subject: <input type="text" name="emailSubject" size=50	maxlength=100 required value="'. $_POST['emailSubject'].'"> </p>            
            <p> Message: <textarea name="emailBody" cols="50" rows="6" maxlength=400 required>'. $_POST['emailBody'] .'</textarea> </p>
            <p> <button  type="submit" name="formSubmit" >Send</button></p>
        </form>';
    
    }
    else{ 
        // If no records were returned.
	    echo '<p>The teacher does not exist or was deleted</p>';
    }
    
?>

<?php
    include ("../../includes/footer.php");
?>



