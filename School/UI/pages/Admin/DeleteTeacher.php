<?php

    require_once('../../../DAL/TeacherRepository.php');

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        
        if (isset($_GET['teacherId']) && isset($_GET['idUser'])) {
            $teacherRepository = new TeacherRepository();
            $teacherId = $_GET['teacherId'];
            $idUser = $_GET['idUser'];
            $result = $teacherRepository->DeleteTeacher($teacherId, $idUser);
            if($result == false)
            {
                header('HTTP/1.1 500 Internal Server Error', true, 500);
            }
            
        }
    }

?>