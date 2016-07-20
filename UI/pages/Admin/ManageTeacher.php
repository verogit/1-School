<?php 
    require_once('../../../DAL/TeacherRepository.php');
    require_once('../../../Domain/Teacher.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'Manage Teacher';  
    include ("../../includes/headerAdmin.php");
    
    echo "<p class='backButton'><a class='backButton' href='HomeAdmin.php'>Back</a></p>";
    
 	echo "<a class='newButton' href='AddEditTeacher.php'>Create new Teacher</a>";
        
    $teacherRepository = new TeacherRepository();
    $teachers = $teacherRepository->GetAllTeachers();
    
    
    echo '<p class="search"> Filter: <input type="text" id="search" placeholder="Type to search"> </p>';
    
    
    if($teachers != null)
    {
        
        // Table header:
   		echo '<table id="table">
   		        <thead>
        			<tr>
    					<th>Name</th>
    					<th>Email</th>
    					<th>Telephone</th>
    					<th>Address</th>
    					<th>Date of birth</th>
    					<th>Gender</th>
        			</tr>
    			</thead>';
    	
    	foreach ($teachers as $teacher) {
    	    echo "
    	        <tbody>
        	         <tr>
    					<td>" . $teacher->firstName . " " . $teacher->lastName . "</td>
    					<td>" . $teacher->email . "</td>
    					<td>" . $teacher->telephone . "</td>
    					<td>" . $teacher->address . "</td>
    					<td>" . $teacher->dateOfBirth . "</td>
    					<td>" . $teacher->gender . "</td>
    					<td class='editButton'><a class='editButton' href='AddEditTeacher.php?teacherId=". $teacher->id."'>Edit</a></td>
    	    			<td class='deleteButton'><a class='deleteButton' onclick=confirmDelete(". $teacher->id .",". $teacher->idUser .")  href='#'>Delete</a></td>
    				</tr>
				</tbody>";
    	}
    	echo '</table>';
    
    }
    else{ 
        // If no records were returned.
	    echo '<p>There are not teachers registered</p>';
    }
        
    
    
?>

<script type="text/javascript" src="../../js/jquery-1.12.3.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        
        
        //FILTER THE TABLE OF TEACHER
        var $rows = $('#table tbody tr');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    });


    function confirmDelete(teacherId,idUser, teacherFisrtName, teacherLastName){

        var answer = confirm("Are you sure you want to delete the teacher");
        if(answer == true){
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var messageConfirm = confirm("The teacher was deleted sucessfully");
                    if(messageConfirm == true || messageConfirm == false){
                        window.location.reload(); //redirect to the send page
                    }
                }
                else if (xmlhttp.readyState == 4 && xmlhttp.status != 200) {
                    alert("An error occurred trying to delete the Teacher");
                }
            };
            xmlhttp.open("DELETE", "DeleteTeacher.php?teacherId=" + teacherId + "&idUser="+ idUser, true);
            xmlhttp.send();
        }
    }
    
    
    
</script>


<?php
    include ("../../includes/footer.php");
?>



