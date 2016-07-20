<?php
    require_once('../../controllers/Teacher/HomeworkController.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
	$page_title = 'Homeworks';  
    include ("../../includes/headerTeacher.php");
    
    echo "<p class='backButton'><a class='backButton' href='ManageHomework.php'>Back</a></p>";
    
        
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
	
?>

<p>Subject: <?php echo isset($subject) ?  $subject->name : ""; ?> </p>


<form action="" method="POST" enctype="multipart/form-data">
    <p>Title: <input type="text" name="title" maxlength=100 size=100 autofocus required value="<?php echo $_POST['title']; ?>"> </p>
    <p> Description: <textarea name="description" cols="100" rows="6" maxlength=400 required><?php echo $_POST['description']; ?></textarea> </p>
    
    
    Select file to upload (pdf):
    <input type="file" name="fileToUpload" id="fileToUpload">
    <br/>
    <br/>
    <input type="submit" value="Save" name="submit">
    <input type="hidden" name="hdfSubjectId" value="<?php echo $_GET['subjectId'];?>">
</form>

<?php
    include ("../../includes/footer.php");
?>



