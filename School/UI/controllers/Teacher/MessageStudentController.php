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
    
    if( isset($_GET['subjectId']) && isset($_GET['studentId']) && isset($_GET['section']) && isset($_GET['year']) )
    {
        global $subject, $student;
        $subject = $subjectRepository->GetSubjectsById($_GET['subjectId']);
        $student = $studentRepository->GetStudentById($_GET['studentId']);    
    }
    else {
        header("location: ClassroomDetail.php");
    }
    
    
    if (isset($_POST['formSubmit']))
    {
        if(!isset($_POST['emailAddress']) || !isset($_POST['emailSubject']) || !isset($_POST['emailBody']) )
        {
            $errors[] = "You must fill all the required fields";
        }
        else
        {
            $teacher = $teacherRepository->GetTeacherByIdUser($_SESSION['user']->id);
            
            $countSelected = count($_POST['emailAddress']);
            $to = "";
            if($countSelected == 1)
            {
                $to = $_POST['emailAddress'][0];
            }
            else {
                $to = $_POST['emailAddress'][0];
                $to = $to.";".$_POST['emailAddress'][1];
            }
            
            $subject = $_POST['emailSubject'];
            
            $messageEmail = "
                <html>
                    <head>
                        <title>Message from Teacher ". $teacher->firstName ." ". $teacher->lastName."</title>
                    </head>
                    <body>
                        <p>". $_POST['emailBody'] ."</p>
                    </body>
                </html>
                ";
            
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
            // More headers
            $headers .= 'From: <'.$teacher->firstName.' '. $teacher->lastName .'@school.com>' . "\r\n";
            
            mail($to,$subject,$messageEmail,$headers);
            
            $message = "An email has been successfully sent to the student";
            unset($_POST);
        }
    }


?>