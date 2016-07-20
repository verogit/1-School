<?php
	require_once('../../controllers/Admin/SubjectController.php');
	
	error_reporting( error_reporting() & ~E_NOTICE );
	$page_title = 'Add Edit Subjects';  
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
        
        echo "<p class='backButton'><a class='backButton' href='ManageSubject.php'>Back</a></p>";
	
?>	
	
	
	<form action="" method="POST">
			<p> Name: <input type="text" name="subject_name" maxlength=100 autofocus required value="<?php echo !isset($subjectBd) ?  $_POST['subject_name'] : $subjectBd->name; ?>"> </p>
			<p> Level:
				<?php
					if($levels != null)
					{
						echo '<select required name="subject_level">';
						foreach ($levels as $level) {
							
							if (isset($subjectBd) && $level->id == $subjectBd->level->id ) {
								echo '<option selected="selected" value="'. $level->id .'">'. $level->name .'</option>';
							}
							elseif ($_POST['subject_level'] == $level->id) {
								echo '<option selected="selected" value="'. $level->id .'">'. $level->name .'</option>';
							}
							else 
							{
								echo '<option value="'. $level->id .'">'. $level->name .'</option>';	
							}
							
						}
						echo '</select>';
					}
				?>
			
			</p>
			<p> Teacher:
				<?php
					if($teachers != null)
					{
						echo '<select required name="subject_teacher">';
						foreach ($teachers as $teacher) {
							
							if (isset($subjectBd) && $teacher->id == $subjectBd->teacher->id ) {
								echo '<option selected="selected" value="'. $teacher->id .'">'. $teacher->firstName .' '.  $teacher->lastName .'</option>';
							}
							elseif ($_POST['subject_teacher'] == $teacher->id) {
								echo '<option selected="selected" value="'. $teacher->id .'">'. $teacher->firstName .' '.  $teacher->lastName .'</option>';
							}
							else {
								echo '<option value="'. $teacher->id .'">'. $teacher->firstName .' '.  $teacher->lastName .'</option>';
							}
							
							
						}
						echo '</select>';
					}
				?>
			
			</p>
			<p>Time Table</p>
			<p> 
			  <input type="button" value="Add Row" onClick="addRow('table')" /> 
			  <input type="button" value="Remove Row" onClick="deleteRow('table')" /> 
			  <p id="textform">(All acions apply only to entries with check marked check boxes only.)</p>
			</p>
			<table id="table" border="1">
				<thead>
					 <tr>
					 	<th></th>
					 	<th>Weekday</th>
					    <th>Start Time</th>
					    <th>Finish Time</th>
					 </tr>
				</thead>
				<tbody>
					<?php 
						//creating new sbject
						if(!isset($subjectBd) && $_POST['BX_weekday'] == null){
							include_once("../..//includes/subjectTimeTableNew.html");
						}
						//Creating a new subject but after postback
						elseif ($_POST['BX_weekday'] != null) { 
							include_once("../..//includes/subjectTimeTablePost.php");
						}
						//when editing a subject
						else if(isset($subjectBd)){
							include_once("../..//includes/subjectTimeTableEdit.php");
						}
					?>
			  	
			 	</tbody>
			</table>
			<input type="hidden" name="hdfSubjectId" value="<?php echo $_GET['subjectId'];?>">
			<?php
				if (!isset($_GET['subjectId'])) {
		    		echo "<p> <button  type='submit' name='add'>Add</button></p>";   
		    	}
		    	else {
		    		echo "<p> <button  type='submit' name='update'>Update</button></p>";   
		    	}
			?>
			
		</form>
		
		

<script type="text/javascript">
	    
	function addRow(tableID) 
	{
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		if(rowCount < 5) //limit of rows allowed
		{                            
			var row = table.insertRow(rowCount);
			var colCount = table.rows[1].cells.length;
			for(var i=0; i <colCount; i++) 
			{
				var newcell = row.insertCell(i);
				if(i == 0)
				{
					newcell.innerHTML = '<input type="checkbox" name="chk[]"><input type="hidden" name="hdfTimeTableId[]" value="0">';
				}
				else{
					newcell.innerHTML = table.rows[1].cells[i].innerHTML;
				}
				
			}
		}
		else
		{
			alert("Maximum rows per subject is 5");
		}
	}
	
	function deleteRow(tableID)
	{
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		for(var i=1; i<rowCount; i++) 
		{
			var row = table.rows[i];
			var chkbox = null;
			if(row.cells[0].childNodes.length == 5)
				chkbox = row.cells[0].childNodes[1];
			else
				chkbox = row.cells[0].childNodes[0];
				
			if(null != chkbox && true == chkbox.checked) 
			{
				if(rowCount <= 2) //at least 2 row have to be in the table (header and 1 time row)
				{               
					alert("Cannot Remove all the times.");
					break;
				}
				table.deleteRow(i);
				rowCount--;
				i--;
			}
		}
	}
    
</script>		
		
		
<?php
	include ("../../includes/footer.php");
?>
