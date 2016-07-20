<?php 

     require_once('../../../DAL/StudentRepository.php');
     require_once('../../../DAL/SubjectRepository.php');
     require_once('../../../DAL/TeacherRepository.php');
     require_once('../../../Domain/Subject.php');
     require_once('../../../Domain/Level.php');
     require_once('../../../Domain/Classroom.php');
     require_once('../../../Domain/TimeTable.php');
     require_once('../../../Domain/Student.php');
     require_once('../../../Domain/Teacher.php');
     require_once('../../../Domain/User.php');
    
     error_reporting( error_reporting() & ~E_NOTICE );	
     session_start();
    
    $studentRepository = new StudentRepository(); 
    $subjectRepository = new SubjectRepository();
    $teacherRepository = new TeacherRepository();
    
    if( isset($_GET['subjectId']) && isset($_GET['section']) && isset($_GET['year']) )
    {
        global $subject, $teacher;
        $subject = $subjectRepository->GetSubjectsById($_GET['subjectId']);
        $teacher = $teacherRepository->GetTeacherById($subject->teacher->id);    
    }
    else {
        header("location: StudentSubjects.php");
    }
    
    
    if (isset($_POST['formSubmit']))
    {
        if( !isset($_POST['emailSubject']) || !isset($_POST['emailBody']) )
        {
            $errors[] = "You must fill all the required fields";
        }
        else
        {
            $student = $studentRepository->GetStudentByIdUser($_SESSION['user']->id);
            $subject = $subjectRepository->GetSubjectsById($_GET['subjectId']);
            $teacher = $teacherRepository->GetTeacherById($subject->teacher->id);
            
            $to = $teacher->email;
            
            $subject = $_POST['emailSubject'];
            
            $messageEmail = "
                <html>
                    <head>
                        <title>Message from Student ". $student->firstName ." ". $student->lastName."</title>
                    </head>
                    <body>
                        <p> Message from Student ". $student->firstName ." ". $student->lastName."</p>
                        <br/>
                        <p>". $_POST['emailBody'] ."</p>
                    </body>
                </html>
                ";
            
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
            // More headers
            $headers .= 'From: <'.$student->firstName.' '. $student->lastName .'@school.com>' . "\r\n";
            
            mail($to,$subject,$messageEmail,$headers);
            
            $message = "An email has been successfully sent to the teacher";
            unset($_POST);
        }
    }


?>