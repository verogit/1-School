<?php 
     
     require_once('../../../DAL/StudentRepository.php');
     require_once('../../../DAL/SubjectRepository.php');
     require_once('../../../Domain/Subject.php');
     require_once('../../../Domain/Level.php');
     require_once('../../../Domain/Teacher.php');
     require_once('../../../Domain/TimeTable.php');
     require_once('../../../Domain/Student.php');

    error_reporting( error_reporting() & ~E_NOTICE );     
    $page_title = 'Classroom Detail'; 
    include ("../../includes/headerTeacher.php");
       
    $studentRepository = new StudentRepository();
    $subjectRepository = new SubjectRepository();
    
    echo "<p class='backButton'><a class='backButton' href='Subjects.php'>Back</a></p>";
        
    if( isset($_GET['levelId']) && isset($_GET['section']) && isset($_GET['year']) && isset($_GET['subjectId']) )
    {
        $subject = $subjectRepository->GetSubjectsById($_GET['subjectId']);
        $students = $studentRepository->GetStudentOfTheClassroom($_GET['levelId'],$_GET['section'],$_GET['year'],$_GET['subjectId']);    
        
        echo '<ul id="classDetail">
          <li>Subject: '.$subject->name .'</li>
          <li>Level: '.$subject->level->name.'</li>
          <li>Section: '. $_GET['section'] .' </li>
          <li>Year: '. $_GET['year'] .' </li>
        </ul> '; 
    }
    else {
        header("location: Subjects.php");
    }
    
    if($students != null)
    {
        
        // Table header:
   		echo '<table id="table">
   		        <thead>
        			<tr>
    					<th>Fisrt Name</th>
    					<th>Last Name</th>
    					<th>Gender</th>
    					<th>Telephone</th>
    					<th>Email</th>
    					<th>Parent Email</th>
        			</tr>
        		</thead>';
    	
    	foreach ($students as $student) {
    	    echo "<tr>
					<td>" . $student->firstName . "</td>
					<td>" . $student->lastName . "</td>
					<td>" . $student->gender . "</td>
					<td>" . $student->telephone . "</td>
					<td>" . $student->emailStudent . "</td>
					<td>" . $student->emailParent . "</td>
					<td><a class='button' href='MessageStudent.php?subjectId=". $_GET['subjectId']."&studentId=".$student->id ."&section=". $_GET['section']  ."&year=". $_GET['year']."'>Send Message</a></td>
				</tr>";
    	}
    	echo '</table>';
    
    }
    else{ 
        // If no records were returned.
	    echo '<p>There are not students registered for this class</p>';
    }
        
    
    
?>

<?php
    include ("../../includes/footer.php");
?>



