<?php
    require_once('../../controllers/Admin/ClassroomController.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
	$page_title = 'Add Edit Classrooms';  
    include ("../../includes/headerAdmin.php");
        
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
        
        echo "<p class='backButton'><a class='backButton' href='ManageClassroom.php'>Back</a></p>";
	
?>

    <form action="" method="POST">
        <p> Level:
				<?php
					if($levels != null)
					{
						echo '<select required name="level">';
						foreach ($levels as $level) {

							if (isset($classroomBd) && $level->id == $classroomBd->level->id) {
								echo '<option selected="selected" value="'. $level->id .'">'. $level->name .'</option>';
							}
							else if (($_POST['level'] == $level->id)) {
								echo '<option value="'. $level->id .'" selected="selected">'. $level->name .'</option>';
							}else{
								echo '<option value="'. $level->id .'">'. $level->name .'</option>';
							}
						}
						echo '</select>';
					}
			echo '</p>';
			echo '<p>Year:';
		
				echo '<select required name="year">';
				if (($_POST['year'] == 2016) xor (isset($classroomBd) && $classroomBd->year == 2016)) {
					echo '<option value="2016" selected="selected">2016</option>'; 	
				}else{
					echo '<option value="2016">2016</option>'; 		
				}
			    echo '</select></p>';
			
			echo '<p>Section:'; 
			
				echo '<select required name="section">';
				if (($_POST['section'] == 'A') xor (isset($classroomBd) && $classroomBd->sectionName == 'A')){
					echo '<option value="A" selected="selected">A</option> ';
				}else{
					echo '<option value="A">A</option> ';
				}
			    
			    if ($_POST['section'] == 'B'xor (isset($classroomBd) && $classroomBd->sectionName == 'B')){
					echo '<option value="B" selected="selected">B</option> ';
				}else{
					echo '<option value="B">B</option> ';
				}  
				
				if ($_POST['section'] == 'C' xor (isset($classroomBd) && $classroomBd->sectionName == 'C')){
					echo '<option value="C" selected="selected">C</option> ';
				}else{
					echo '<option value="C">C</option> ';
				}
			echo '</select></p>';
			
			
				if (!isset($_GET['classroomId'])) {
		    		echo "<p> <button  type='submit' name='add'>Add</button></p>";   
		    	}
		    	else {
		    		echo "<p> <button  type='submit' name='update'>Update</button></p>";   
		    	}
			?>
   <input type="hidden" name="hdfClassroomId" value="<?php echo $_GET['classroomId'];?>">
    </form>

<?php
	include ("../../includes/footer.php");
?>