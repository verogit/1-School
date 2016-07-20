<?php
    require_once('../../../DAL/StudentRepository.php');
    require_once('../../../Domain/Dtos/TimetableDto.php');
    require_once('../../../Domain/Student.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
    $page_title = 'TimeTable Student'; 
    include("../../includes/headerStudent.php");
    session_start();
    
    $studentRepository = new StudentRepository();
    $user = $_SESSION['user'];
    $student = $studentRepository->GetStudentByIdUser($user->id);
    $timetables = $studentRepository->GetTimetableByStudent($student->id);
    
    include_once("../../includes/studentTimetable.php");
    
    include ("../../includes/footer.php");
?>