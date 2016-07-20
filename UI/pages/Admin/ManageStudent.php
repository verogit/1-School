<?php 
    require_once('../../../DAL/StudentRepository.php');
    require_once('../../../Domain/Student.php');
    require_once('../../../Domain/Classroom.php');
    require_once('../../../Domain/Level.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'Manage Student';  
    include ("../../includes/headerAdmin.php");
    
    echo "<p class='backButton'><a class='backButton' href='HomeAdmin.php'>Back</a></p>";
    
 	echo "<a class='newButton' href='AddEditStudent.php'>Create new Student</a>";
        
    $studentRepository = new StudentRepository();
    $students = $studentRepository->GetAllStudents();
    
    echo '<p class="search"> Filter: <input type="text" id="search" placeholder="Type to search"> </p>';
    
    if($students != null){
        // Table header:
   		echo '<table id="table">
        		<thead>
        			<tr>
    					<th>Name</th>
    					<th>Email Parent</th>
    					<th>Telephone</th>
    					<th>Address</th>
    					<th>Date of birth</th>
    					<th>Gender</th>
    					<th>Classroom</th>
        			</tr>
        		</thead>';
    	
    	foreach ($students as $student) {
    	    echo "
    	        <tbody>
        	        <tr>
    					<td>" . $student->firstName . " " . $student->lastName . "</td>
                        <td>" . $student->emailParent . "</td>
    					<td>" . $student->telephone . "</td>
    					<td>" . $student->address . "</td>
    					<td>" . $student->dateOfBirth . "</td>
    					<td>" . $student->gender . "</td>
    					<td>" . $student->classroom->level->name." ".$student->classroom->year." ".$student->classroom->sectionName."</td>
    					<td class='editButton'><a class='editButton' href='AddEditStudent.php?studentId=".$student->id."'>Edit</a></td>
    	    			<td class='deleteButton'><a class='deleteButton' onclick=confirmDelete(". $student->id .",". $student->idUser .")  href='#'>Delete</a></td>
    				</tr>
				</tbody>";
    	}
    	echo '</table>';
    
    }
    else{ 
        // If no records were returned.
	    echo '<p>There are not student registered</p>';
    }
        
    
    
?>

<script type="text/javascript" src="../../js/jquery-1.12.3.min.js"></script>

<script type="text/javascript">

    $(document).ready(function() {
        
        
        //FILTER THE TABLE OF STUDENT
        var $rows = $('#table tbody tr');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    });

    
    function confirmDelete(studentId,idUser, StudentFisrtName, studentLastName){

        var answer = confirm("Are you sure you want to delete the student");
        if(answer == true){
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var messageConfirm = confirm("The student was deleted sucessfully");
                    if(messageConfirm == true || messageConfirm == false){
                        window.location.reload(); //redirect to the send page
                    }
                }
                else if (xmlhttp.readyState == 4 && xmlhttp.status != 200) {
                    alert("An error occurred trying to delete the Student");
                }
            };
            xmlhttp.open("DELETE", "DeleteStudent.php?studentId=" + studentId + "&idUser="+ idUser, true);
            xmlhttp.send();
        }
    }
    
</script>


<?php
    include ("../../includes/footer.php");
?>



