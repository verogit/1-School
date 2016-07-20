<?php

    require_once('../../../DAL/StudentRepository.php');

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        
        if (isset($_GET['studentId']) && isset($_GET['idUser'])) {
            $studentRepository = new StudentRepository();
            $studentId = $_GET['studentId'];
            $idUser = $_GET['idUser'];
            $result = $studentRepository->DeleteStudent($studentId, $idUser);
            echo $result;
            if($result == false)
            {
                header('HTTP/1.1 500 Internal Server Error', true, 500);
            }
            
        }
    }

?>