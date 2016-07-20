<?php 
     
     require_once('../../../DAL/HomeworkRepository.php');
     require_once('../../../DAL/SubjectRepository.php');
     require_once('../../../Domain/Subject.php');
     require_once('../../../Domain/Level.php');
     require_once('../../../Domain/Teacher.php');
     require_once('../../../Domain/TimeTable.php');
     require_once('../../../Domain/Student.php');
     require_once('../../../Domain/Homework.php');
     
     error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'Homeworks'; 
    include ("../../includes/headerTeacher.php");
       
    $homeworkRepository = new HomeworkRepository();
    $subjectRepository = new SubjectRepository();
    
    
    if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['section']) && isset($_GET['year']) && isset($_GET['subjectId']) )
    {
        $subject = $subjectRepository->GetSubjectsById($_GET['subjectId']);
        $homeworks = $homeworkRepository->GetHomeworksBySubjectId($subject->id);    
    }
    else {
        header("location: Subjects.php");
    }
    
   
        
        echo '<ul id="classDetail">
          <li>Subject: '.$subject->name .'</li>
          <li>Level: '.$subject->level->name.'</li>
          <li>Section: '. $_GET['section'] .' </li>
          <li>Year: '. $_GET['year'] .' </li>
        </ul> '; 
        
        echo "<p class='backButton'><a class='backButton' href='Subjects.php'>Back</a></p>";
    
        echo "<a class='newButton' href='AddEditHomework.php?subjectId=".$subject->id."'>Create new Homework</a>";
        echo '<br/>';
        echo '<br/>';
        
        if($homeworks != null)
        {
        // Table header:
   		echo '<table id="table">
   		        <thead>
    			<tr>
					<th>Title</th>
					<th>Description</th>
					<th>File Name</th>
    			</tr>
    			</thead>';
    	
    	foreach ($homeworks as $homework) {
    	    echo "<tr>
					<td>" . $homework->title . "</td>
					<td>" . $homework->description . "</td>
					<td>" . $homework->fileName . "</td>
	    			<td><a class='deleteButton' onclick=confirmDelete(". $homework->id .")  href='#'>Delete</a></td>
				</tr>";
    	}
    	echo '</table>';
    
    }
    else{ 
        // If no records were returned.
	    echo '<p>There are not homeworks registered for this subject</p>';
    }
     
?>

<script type="text/javascript">
    
    function confirmDelete(homeworkId){

        var answer = confirm("Are you sure you want to delete the homework");
        if(answer == true){
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var messageConfirm = confirm("The homework was deleted sucessfully");
                    if(messageConfirm == true || messageConfirm == false){
                        window.location.reload(); //redirect to the send page
                    }
                }
                else if (xmlhttp.readyState == 4 && xmlhttp.status != 200) {
                    alert("An error occurred trying to delete the Homework");
                }
            };
            xmlhttp.open("DELETE", "DeleteHomework.php?homeworkId=" + homeworkId, true);
            xmlhttp.send();
        }
    }
    
</script>


<?php
    include ("../../includes/footer.php");
?>



