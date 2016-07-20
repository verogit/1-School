<?php
    require_once('../../../DAL/ClassroomRepository.php');
    require_once('../../../Domain/Classroom.php');
    require_once('../../../Domain/Level.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'Manage Classroom';  
    include ("../../includes/headerAdmin.php");
    
    echo "<p class='backButton'><a class='backButton' href='HomeAdmin.php'>Back</a></p>";
    
 	echo "<a class='newButton' href='AddEditClassroom.php'>Create new Classroom</a>";
 	
 	$classroomRepository = new ClassroomRepository();
 	$classrooms = $classroomRepository->GetAllClassrooms();
 	
 	echo '<p class="search"> Filter: <input type="text" id="search" placeholder="Type to search"> </p>';
 	
 	if($classrooms != null){
 	    
 	    //Table Header:
 	    echo '<table id="table">
 	            <thead>
     	            <tr>
     	                <th>Level</th>
     	                <th>Year</th>
     	                <th>Section</th>
     	            </tr>
 	            </thead>';
 	    foreach ($classrooms as $classroom) {
 	        echo "
 	            <tbody>
     	            <tr>
     	                <td>".$classroom->level->name."</td>
     	                <td>".$classroom->year."</td>
     	                <td>".$classroom->sectionName."</td>
     	                <td class='editButton'><a class='editButton' href='AddEditClassroom.php?classroomId=". $classroom->id."'>Edit</a></td>
    	    			<td class='deleteButton'><a class='deleteButton' onclick=confirmDelete(". $classroom->id .")  href='#'>Delete</a></td>
     	            </tr>
 	          </tbody>";
 	    }
 	    echo "</table>";
 	}
 	else{ 
        // If no records were returned.
	    echo '<p>There are not classroom registered</p>';
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

    
    function confirmDelete(classroomId){

        var answer = confirm("Are you sure you want to delete the classrooms");
        if(answer == true){
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var messageConfirm = confirm("The classrooms was deleted sucessfully");
                    if(messageConfirm == true || messageConfirm == false){
                        window.location.reload(); //redirect to the send page
                    }
                }
                else if (xmlhttp.readyState == 4 && xmlhttp.status != 200) {
                    alert("An error occurred trying to delete the Classroom");
                }
            };
            xmlhttp.open("DELETE", "DeleteClassroom.php?classroomId=" + classroomId, true);
            xmlhttp.send();
        }
    }
    
</script>


<?php
    include ("../../includes/footer.php");
?>
    
    
    
    
    