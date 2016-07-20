<?php 
    require_once('../../../DAL/SubjectRepository.php');
    require_once('../../../Domain/Teacher.php');
    require_once('../../../Domain/Level.php');
    require_once('../../../Domain/Subject.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'Manage Subjects';  
    include ("../../includes/headerAdmin.php");
    
    echo "<p class='backButton'><a class='backButton' href='HomeAdmin.php'>Back</a></p>";
    
 	echo "<a class='newButton' href='AddEditSubject.php'>Create new Subject</a>";
        
    $subjectRepository = new SubjectRepository();
    $subjects = $subjectRepository->GetAllSubjects();
    
    echo '<p class="search"> Filter: <input type="text" id="search" placeholder="Type to search"> </p>';
    
    if($subjects != null)
    {
        
        // Table header:
   		echo '<table id="table">
   		        <thead>
        			<tr>
    					<th>Name</th>
    					<th>Level</th>
    					<th>Teacher</th>
        			</tr>
    			</thead>';
    	
    	foreach ($subjects as $subject) {
    	    echo "
    	        <tbody>
        	        <tr>
    					<td>" . $subject->name . "</td>
    					<td>" . $subject->level->name . "</td>
    					<td>" . $subject->teacher->firstName ." ". $subject->teacher->lastName . "</td>
    					<td class='editButton'><a class='editButton' href='AddEditSubject.php?subjectId=". $subject->id."&levelId=".$subject->level->id."&teacherId=".$subject->teacher->id."'>Edit</a></td>
    	    			<td class='deleteButton'><a class='deleteButton' onclick=confirmDelete(". $subject->id .")  href='#'>Delete</a></td>
    				</tr>
				</tbody>";
    	}
    	echo '</table>';
    	
    	echo '<div id="txtHint"></div>';
        
        
    }
    else{ 
        // If no records were returned.
	    echo '<p>There are not subjects registered</p>';
    }
    
?>

<script type="text/javascript" src="../../js/jquery-1.12.3.min.js"></script>

<script type="text/javascript">
    
     $(document).ready(function() {
        
        //FILTER THE TABLE OF SUBJECT
        var $rows = $('#table tbody tr');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    });
    
    function confirmDelete(subjectId){
        var answer = confirm("Are you sure you want to delete the subject");
        if(answer == true){
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var messageConfirm = confirm("The subject was deleted sucessfully");
                    if(messageConfirm == true || messageConfirm == false){
                        window.location.reload(); //redirect to the send page
                    }
                }
                 else if (xmlhttp.readyState == 4 && xmlhttp.status != 200) {
                    alert("An error occurred trying to delete the Subject");
                }
            };
            xmlhttp.open("DELETE", "DeleteSubject.php?subjectId=" + subjectId, true);
            xmlhttp.send();
        }
    }
    
</script>


<?php
    include ("../../includes/footer.php");
?>



