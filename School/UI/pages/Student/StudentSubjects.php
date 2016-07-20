<?php 
     require_once('../../../DAL/StudentRepository.php');
     require_once('../../../Domain/Dtos/ClassDto.php');
     require_once('../../../Domain/Student.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'Student Subjects'; 
    include ("../../includes/headerStudent.php");
    session_start();
       
    $studentRepository = new StudentRepository();
    
    $user = $_SESSION['user'];
    $student = $studentRepository->GetStudentByIdUser($user->id);
    $classroomsDtos = $studentRepository->GetSubjectsOfStudent($student->id);
    
    if($classroomsDtos != null)
    {
        
        // Table header:
   		echo '<table id="table">
   		        <thead>
    			    <tr>
					    <th>Subject Name</th>
					    <th>Level</th>
					    <th>Section</th>
					    <th>Year</th>
					    <th>Grade</th>
    			    </tr>
    			</thead>';
    	
    	foreach ($classroomsDtos as $classDto) {
    	    echo "<tr>
					<td>" . $classDto->subjectName . "</td>
					<td>" . $classDto->levelName . "</td>
					<td>" . $classDto->section . "</td>
					<td>" . $classDto->year . "</td>
					<td>" . $classDto->grade . "</td>
					<td><a class='button' href='StudentHomework.php?levelId=". $classDto->levelId."&section=". $classDto->section."&year=". $classDto->year."&subjectId=". $classDto->subjectId ."'>Homeworks</a></td>
	    			<td><a class='button' href='MessageToTeacher.php?subjectId=". $classDto->subjectId."&section=". $classDto->section."&year=". $classDto->year."'>Contact Teacher</a></td>
				</tr>";
    	}
    	echo '</table>';
    
    }
    else{ 
        // If no records were returned.
	    echo '<p>There are not subjects registered for you</p>';
    }
        
    
    
?>

<?php
    include ("../../includes/footer.php");
?>



