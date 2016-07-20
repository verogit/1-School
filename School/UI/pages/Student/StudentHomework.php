<?php 
     require_once('../../../DAL/HomeworkRepository.php');
     require_once('../../../DAL/SubjectRepository.php');
     require_once('../../../Domain/Homework.php');
     require_once('../../../Domain/Subject.php');
     require_once('../../../Domain/Level.php');
     require_once('../../../Domain/Teacher.php');
     require_once('../../../Domain/TimeTable.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'Student Homeworks'; 
    include ("../../includes/headerStudent.php");
    session_start();
       
    $homeworkRepository = new HomeworkRepository();
    $subjectRepository = new SubjectRepository();
    
        echo "<p class='backButton'><a class='backButton' href='StudentSubjects.php'>Back</a></p>";
    
    if( isset($_GET['levelId']) && isset($_GET['section']) && isset($_GET['year']) && isset($_GET['subjectId']) )
    {
        $subject = $subjectRepository->GetSubjectsById($_GET['subjectId']);
        $homeworks = $homeworkRepository->GetHomeworksBySubjectId($_GET['subjectId']);
        
        echo '<ul id="classDetail" ">
          <li>Subject: '.$subject->name .'</li>
          <li>Level: '.$subject->level->name.'</li>
          <li>Section: '. $_GET['section'] .' </li>
          <li>Year: '. $_GET['year'] .' </li>
        </ul> '; 
        
    }
    else {
        header("location: StudentSubjects.php");
    }
    
    if($homeworks != null)
    {
        // Table header:
   		echo '<table id="table">
    			<thead>
    			    <tr>
					    <th>Title</th>
					    <th>Description</th>
					    <th>File</th>
    			    </tr>
    	        </thead>';
    	foreach ($homeworks as $homework) {
    	    echo "<tr>
					<td>" . $homework->title . "</td>
					<td>" . $homework->description . "</td>
					<td > <a class='downloadButton'target='_blank' href='../../homeworksFiles/". $homework->fileName . "'>Download</a> </td>
				</tr>";
    	}
    	echo '</table>';
    
    }
    else{ 
        // If no records were returned.
	    echo '<p>There are not homeworks registered for this subject</p>';
    }
    
?>

<?php
    include ("../../includes/footer.php");
?>



