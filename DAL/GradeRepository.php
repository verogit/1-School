<?php
require_once('DbConnection.php');

class GradeRepository
{
    
    public function InsertGrade($grade){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        mysqli_autocommit($connection, false); //disable the autocommit
        $areQueriesExecutedSuccessfully = true;
        
        $sqldelete = "DELETE From grade".
               " WHERE id_student = ? and id_subject = ?";
               
        $stmt = $connection->prepare($sqldelete);
        $stmt->bind_param('ss', $grade->idStudent,$grade->idSubject); 
        
        if (!$stmt->execute()) {
            echo "Error Deleting a Grade : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
            return null;
        }
        
        $sql = "INSERT INTO grade (id_student,id_subject, grade)".
                    " VALUES (?, ?, ?)";
                    
        $stmtGrade = $connection->prepare($sql);
        $stmtGrade->bind_param('sss', $grade->idStudent,$grade->idSubject,$grade->grade);               
        
        if (!$stmtGrade->execute()) {
            $areQueriesExecutedSuccessfully = false;
            echo "Error Inserting a Grade : (" . $stmtGrade->errno . ") " . $stmtGrade->error;  
            mysqli_close($connection);
            
            return null;
        }
        
        if($areQueriesExecutedSuccessfully == true)
        {
            mysqli_commit($connection); //COMMIT Changes to the database
            mysqli_close($connection);
            return $grade;
            
        }
        else {
            mysqli_rollback($connection); //ROLLBACK changes in the database;
            mysqli_close($connection);
            return null;
        }
        
        
    }
    
    public function UpdateGrade($grade){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
         $sql = "UPDATE grade".
               " SET id_student= ?, id_subject= ?, grade= ?".
               " WHERE id_student = ? and id_subject = ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('sssss', $grade->idStudent,$grade->idSubject,$grade->grade,$grade->idStudent,$grade->idSubject);               
        
        if (!$stmt->execute()) {
            echo "Error Updating a Grade : (" . $stmtGrade->errno . ") " . $stmtGrade->error;  
            $stmt->close();
            mysqli_close($connection);
            return null;
        }
        mysqli_close($connection);
    }
    
    public function DeleteGrade($grade){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        
         $sql = "DELETE From grade".
               " WHERE id_student = ? and id_subject = ?";
               
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ss', $grade->idStudent,$grade->idSubject); 
        
        if (!$stmt->execute()) {
            echo "Error Deleting a Grade : (" . $stmtGrade->errno . ") " . $stmtGrade->error; 
            $stmt->close();
            mysqli_close($connection);
            return false;
        }
        mysqli_close($connection);
        return true;
    }
    
    
    
}


?>