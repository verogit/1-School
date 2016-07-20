<?php 

    require_once('../../../DAL/HomeworkRepository.php');
    require_once('../../../DAL/SubjectRepository.php');
    require_once('../../../Domain/Homework.php');
    require_once('../../../Domain/Subject.php');
    require_once('../../../Domain/Level.php');
    require_once('../../../Domain/Teacher.php');
    require_once('../../../Domain/TimeTable.php');
     
     error_reporting( error_reporting() & ~E_NOTICE );
       
    $homeworkRepository = new HomeworkRepository();
    $subjectRepository = new SubjectRepository();
    
    $target_dir = "../../homeworksFiles/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['subjectId']))
    {
        global $subject;
        $subject = $subjectRepository->GetSubjectsById($_GET['subjectId']);
    } 
    
    
    
    if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) 
    {
        // Check if file already exists
        if (file_exists($target_file)) {
            $errors[] = "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            $errors[] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "pdf") {
            $errors[] = "Sorry, only PDF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 1)
        {
            
            //insert in homework table
            $homework = new Homework();
            $homework->title = $_POST['title'];
            $homework->description = $_POST['description'];
            $homework->fileName = $_FILES["fileToUpload"]["name"];
            $homework->subjectId = $_POST['hdfSubjectId'];
		    
		    $homeworkSaved = $homeworkRepository->InsertHomework($homework);
		    if($homeworkSaved != null)
		    {
		        
		        if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
		        {
		            $message =  "The homework has been saved successfully"; 
		            unset($_POST);
		        }
		        else
		        {
		            $errors[] = "An error occurred trying to upload the file"; 
		        }
		    }
		    else
		    {
		        $errors[] = "An error occurred trying to insert the homework"; 
		    }
		    
        } 
        
}
    
    
    
   
  

?>