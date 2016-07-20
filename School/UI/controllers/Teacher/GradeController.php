<?php 

    require_once('../../../DAL/StudentRepository.php');
    require_once('../../../DAL/SubjectRepository.php');
    require_once('../../../DAL/GradeRepository.php');
    require_once('../../../Domain/Subject.php');
    require_once('../../../Domain/Level.php');
    require_once('../../../Domain/Teacher.php');
    require_once('../../../Domain/TimeTable.php');
    require_once('../../../Domain/Dtos/StudentDto.php');
    require_once('../../../Domain/Student.php');
    require_once('../../../Domain/Grade.php');
    
    error_reporting( error_reporting() & ~E_NOTICE );
       
    $studentRepository = new StudentRepository();
    $subjectRepository = new SubjectRepository();
    $gradeRepository = new GradeRepository();
    
    if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['section']) && isset($_GET['year']) && isset($_GET['subjectId']) )
    {
        LoadStudents($subjectRepository,$studentRepository);
    }
    
    
    if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formSubmit'])) 
    {
        $subjectOfClass = $subjectRepository->GetSubjectsById($_POST['hdfSubjectId']);
        $studentsInClass = $studentRepository->GetStudentOfTheClassroom($subjectOfClass->level->id,$_POST['hdfSection'],$_POST['hdfYear']);    
        
        $areGradesSavedSucessfully = true;
        foreach ($studentsInClass as $student) {
            
            $grade = new Grade();
            $grade->idStudent = $student->id;
            $grade->idSubject = $subjectOfClass->id;
            $grade->grade = $_POST['student'.$student->id];
            
            $result = $gradeRepository->InsertGrade($grade);
            
            if($result == null)
                $areGradesSavedSucessfully = false;
            
        }
        
        if($areGradesSavedSucessfully)
            {
                $message = "Grades has been saved successfully";
                //clear all $_POST variables
                unset($_POST);
                LoadStudents($subjectRepository,$studentRepository);
            }
            else
            {
                $errors[] = "An error occurred trying to save the grades";
            }
        
    }
    
    function LoadStudents($subjetRepo, $studentRepo)
    {
        global $subject, $studentsDtos;
        $subject = $subjetRepo->GetSubjectsById($_GET['subjectId']);
        $studentsDtos = $studentRepo->GetStudentOfTheClassroomWithGrades($subject->level->id,$_GET['section'],$_GET['year'],$_GET['subjectId']);    
    }


?>