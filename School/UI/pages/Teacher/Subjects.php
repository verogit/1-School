<?php 
     require_once('../../../DAL/ClassroomRepository.php');
     require_once('../../../DAL/TeacherRepository.php');
     require_once('../../../Domain/Dtos/ClassDto.php');
     require_once('../../../Domain/Teacher.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'Subjects'; 
    include ("../../includes/headerTeacher.php");
    session_start();
    
    
       
    $classroomRepository = new ClassroomRepository();
    $teacherRepository = new TeacherRepository();
    $user = $_SESSION['user'];
    $teacher = $teacherRepository->GetTeacherByIdUser($user->id);
    $classroomsDtos = $classroomRepository->GetClassroomInfoByIdTeacher($teacher->id);
    
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
    					<th>Number Of Students</th>
        			</tr>
        		</thead>';
    	
    	foreach ($classroomsDtos as $classDto) {
    	    echo "<tr>
					<td>" . $classDto->subjectName . "</td>
					<td>" . $classDto->levelName . "</td>
					<td>" . $classDto->section . "</td>
					<td>" . $classDto->year . "</td>
					<td>" . $classDto->numberofStudents . "</td>
					<td><a class='button' href='ClassroomDetail.php?levelId=". $classDto->levelId."&section=". $classDto->section."&year=". $classDto->year."&subjectId=". $classDto->subjectId ."'>Details</a></td>
	    			<td><a class='button' href='ManageHomework.php?subjectId=". $classDto->subjectId."&section=". $classDto->section."&year=". $classDto->year."'>Homeworks</a></td>
	    			<td><a class='button' href='Grades.php?subjectId=". $classDto->subjectId."&section=". $classDto->section."&year=". $classDto->year."'>Grades</a></td>
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



