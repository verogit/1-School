<?php
    require_once('../../controllers/Admin/StudentController.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
	$page_title = 'Add Edit Students';  
    include ("../../includes/headerAdmin.php");
    
    
    if (!empty($errors)){
        echo '<h1>Error!</h1>
        <p class="error">There are problems with this form. Please correct the following errors:<br />';
        foreach ($errors as $msg) { // Print each error.
		echo " - $msg<br />\n";
		}
	}
	
	if(isset($message)){
	    echo "<span style='color:green;'>".$message."</span>";
	}
	echo "<p class='backButton'><a class='backButton' href='ManageStudent.php'>Back</a></p>";
?>

    <form action="" method="POST">
			<p> First Name: <input type="TEXT" name="first_name" maxlength=100 autofocus required value="<?php echo !isset($studentDB) ?  $_POST['first_name'] : $studentDB->firstName; ?>"> </p>
			<p> Last Name: <input type="TEXT" name="last_name" size=20 	maxlength=100 required value="<?php echo !isset($studentDB) ?  $_POST['last_name'] : $studentDB->lastName; ?>"> </p>
			<p> DOB: <input type="DATE" name="date_of_birth"  required value="<?php echo !isset($studentDB) ?  $_POST['date_of_birth'] : $studentDB->dateOfBirth; ?>"> </p>
			<p> Gender: </p>
			<p><input type="radio" name="gender" value="M" <?php if($_POST['gender'] == "M" || $studentDB->gender == "M" ) { echo "checked=\"checked\""; } ?> />Male
				<input type="radio" name="gender" value="F" <?php if($_POST['gender'] == "F" || $studentDB->gender == "F") { echo "checked=\"checked\""; } ?> />Female</p>
			<p> Classroom: <select required name="classroom">
				<?php
					if($classrooms != null){
						foreach ($classrooms as $classroom) {
							if (($_POST['classroom'] == $classroom->id) || ($studentDB->classroom->id == $classroom->id)) {
								echo '<option value="'. $classroom->id .'" selected="selected">'. $classroom->level->name.' '.$classroom->year.' '.$classroom->sectionName.'</option>';
							}else{
								echo '<option value="'. $classroom->id .'">'. $classroom->level->name.' '.$classroom->year.' '.$classroom->sectionName.'</option>';
							}
						}
					}
				?>
			</select> </p>
			<p> Username: <input type="TEXT" name="username" size=20 	maxlength=100 required value="<?php echo !isset($studentDB) ?  $_POST['username'] : $studentDB->userName; ?>"> </p>
			<p> Password: <input type="TEXT" name="password" size=20 	maxlength=100 required value="<?php echo !isset($studentDB) ?  $_POST['password'] : $studentDB->password; ?>"> </p>
			<p> Telephone: <input type="tel" name="telephone" onkeypress="onlyNumbers(event);" maxlength=15 required value="<?php echo !isset($studentDB) ?  $_POST['telephone'] : $studentDB->telephone; ?>"> </p>
			<p> Email Student: <input type="email" name="emailStudent" maxlength=100 value="<?php echo !isset($studentDB) ?  $_POST['emailStudent'] : $studentDB->emailStudent; ?>"> </p>
			<p> Email Parent: <input type="email" name="emailParent" maxlength=100 required value="<?php echo !isset($studentDB) ?  $_POST['emailParent'] : $studentDB->emailParent; ?>"> </p>
			<p> Address: <textarea name="address" cols="30" rows="6" maxlength=400 required><?php echo !isset($studentDB) ?  $_POST['address'] : $studentDB->address; ?></textarea> </p>
			<input type="hidden" name="hdfStudentId" value="<?php echo $_GET['studentId'];?>">
			<input type="hidden" name="hdfUserId" value="<?php echo !isset($studentDB) ?  $_POST['hdfUserId']: $studentDB->idUser;?>">
			<?php
				if (!isset($_GET['studentId'])) {
		    		echo "<p> <button  type='submit' name='add' >Add</button></p>";   
		    	}
		    	else {
		    		echo "<p> <button  type='submit' name='update' >Update</button></p>";   
		    	}
			?>
		</form>
		
		
<script type="text/javascript" src="../../js/validations.js"></script>

<?php
	include ("../../includes/footer.php");
?>
