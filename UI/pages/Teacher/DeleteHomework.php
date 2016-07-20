<?php

    require_once('../../../DAL/HomeworkRepository.php');
    require_once('../../../Domain/Homework.php');

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        
        if (isset($_GET['homeworkId'])) {
            $homeworkRepository = new HomeworkRepository();
            $homeworkId = $_GET['homeworkId'];
            
            $homework = $homeworkRepository->GetHomeworkById($homeworkId);
            $filename = $homework->fileName;
            unlink('../../homeworksFiles'.DIRECTORY_SEPARATOR.$filename); //delete file from directory
            $result = $homeworkRepository->DeleteHomework($homeworkId);
            if($result == false)
            {
                header('HTTP/1.1 500 Internal Server Error', true, 500);
            }
            
        }
    }

?>