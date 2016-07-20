<?php 
     
     require_once('../../controllers/Teacher/GradeController.php');
     
     error_reporting( error_reporting() & ~E_NOTICE );
     $page_title = 'Student Grades'; 
     include ("../../includes/headerTeacher.php");
             echo "<p class='backButton'><a class='backButton' href='Subjects.php'>Back</a></p>";
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
     
     echo '<ul id="classDetail" ">
          <li>Subject: '.$subject->name .'</li>
          <li>Level: '.$subject->level->name.'</li>
          <li>Section: '. $_GET['section'] .' </li>
          <li>Year: '. $_GET['year'] .' </li>
        </ul> ';
    
    if($studentsDtos != null)
    {
        
        // Table header:
   		echo '<form id="grade" action="" method="POST">
   		        <table id="table">
   		            <thead>
            			<tr>
        					<th>Fisrt Name</th>
        					<th>Last Name</th>
        					<th>Gender</th>
        					<th>Final Grade</th>
            			</tr>
            		</thead>';
    	
    	foreach ($studentsDtos as $studentDto) {
    	    echo '<tr>
					<td>' . $studentDto->firstName . '</td>
					<td>' . $studentDto->lastName . '</td>
					<td>' . $studentDto->gender . '</td>
					<td><input type="number" min=0 max=100 name="student'. $studentDto->id .'" size=3	maxlength=3 required value="'. $studentDto->grade .'"></td>            
				</tr>';
    	}
    	    echo '</table>';
    	    echo '<input type="hidden" name="hdfSection" value="'.$_GET['section'].'">';
    	    echo '<input type="hidden" name="hdfYear" value="'.$_GET['year'].'">';
    	    echo '<input type="hidden" name="hdfSubjectId" value="'.$_GET['subjectId'].'">';
    	    echo '<button  type="submit" name="formSubmit" >Send</button>';
    	 echo '</form>';
    
    }
    else{ 
        // If no records were returned.
	    echo '<p>There are not students registered for this class</p>';
    }
        
    
    
?>

<?php
    include ("../../includes/footer.php");
?>



