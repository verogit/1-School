<?php

    require_once('../../../DAL/ClassroomRepository.php');

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        
        if (isset($_GET['classroomId'])) {
            $classroomRepository = new ClassroomRepository();
            $classroomId = $_GET['classroomId'];
            $result = $classroomRepository->DeleteClassroom($classroomId);
            if($result == false)
            {
                header('HTTP/1.1 500 Internal Server Error', true, 500);
            }
            
        }
    }

?>