<?php

    require_once('../../../DAL/SubjectRepository.php');
    require_once('../../../DAL/TimeTableRepository.php');
    require_once('../../../Domain/Subject.php');
    require_once('../../../Domain/TimeTable.php');
    
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        
        if (isset($_GET['subjectId'])) {
            $subjectRepository = new SubjectRepository();
            $timeTableRepository = new TimeTableRepository();
            $subjectId = $_GET['subjectId'];
            $subject = new Subject();
            $subject->id = $subjectId;
            
            $timeTableRepository->DeleteAllTimeTableOfSubject($subject);
            $result = $subjectRepository->DeleteSubject($subjectId);
            
            
            if($result == false)
            {
                header('HTTP/1.1 500 Internal Server Error', true, 500);
            }
            
        }
    }

?>