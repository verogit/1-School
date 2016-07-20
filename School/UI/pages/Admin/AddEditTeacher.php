<?php
	require_once('../../controllers/Admin/TeacherController.php');
	
	error_reporting( error_reporting() & ~E_NOTICE );
	$page_title = 'Add Edit Teachers';  
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
    
    echo "<p class='backButton'><a class='backButton' href='ManageTeacher.php'>Back</a></p>";
?>

		<form action="" method="POST">
			<p> First Name: <input type="TEXT" name="first_name" maxlength=100 autofocus required value="<?php echo !isset($firstname) ?  $_POST['first_name'] : $firstname; ?>"> </p>
			<p> Last Name: <input type="TEXT" name="last_name" size=20 	maxlength=100 required value="<?php echo !isset($lastname) ?  $_POST['last_name'] : $lastname; ?>"> </p>
			<p> Username: <input type="TEXT" name="username" size=20 	maxlength=100 required value="<?php echo !isset($username) ?  $_POST['username'] : $username; ?>"> </p>
			<p> Password: <input type="TEXT" name="password" size=20 	maxlength=100 required value="<?php echo !isset($password) ?  $_POST['password'] : $password; ?>"> </p>
			<p> DOB: <input type="DATE" name="date_of_birth" max="1998-12-31" required value="<?php echo !isset($dateOfBirth) ?  $_POST['date_of_birth'] : $dateOfBirth; ?>"> </p>
			<p> Gender: </p>
			<p><input type="radio" name="gender" value="M" <?php if($_POST['gender'] == "M" || $gender == "M" ) { echo "checked=\"checked\""; } ?> />Male
			<input type="radio" name="gender" value="F" <?php if($_POST['gender'] == "F" || $gender == "F") { echo "checked=\"checked\""; } ?> />Female
			</p>
			
			<p> Telephone: <input type="tel" name="telephone" onkeypress="onlyNumbers(event);" maxlength=15 required value="<?php echo !isset($telephone) ?  $_POST['telephone'] : $telephone; ?>"> </p>
			<p> Email: <input type="email" name="email" maxlength=100 required value="<?php echo !isset($email) ?  $_POST['email'] : $email; ?>"> </p>
			<p> Address: <textarea name="address" cols="30" rows="6" maxlength=400 required><?php echo !isset($address) ?  $_POST['address'] : $address; ?></textarea> </p>
			<input type="hidden" name="hdfTeacherId" value="<?php echo $_GET['teacherId'];?>">
			<input type="hidden" name="hdfUserId" value="<?php echo $idUser;?>">
			<?php
				if (!isset($_GET['teacherId'])) {
		    		echo "<p> <button  class='button' type='submit' name='add' >Add</button></p>";   
		    	}
		    	else {
		    		echo "<p> <button  class='button' type='submit' name='update' >Update</button></p>";   
		    	}
			?>
			
		</form>

<script type="text/javascript" src="../../js/validations.js"></script>		

		
<?php
	include ("../../includes/footer.php");
?>
