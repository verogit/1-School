<?php
require_once('DbConnection.php');

class HomeworkRepository
{
    
    public function InsertHomework($homework){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "INSERT INTO homework (title, description, file_name, id_subject)".
                    " VALUES (?, ?, ?, ? )";
                    
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssss', $homework->title,$homework->description,$homework->fileName,$homework->subjectId); 
        
        if ($stmt->execute()) {
            $homework->id = $stmt->insert_id;
            $stmt->close();
            mysqli_close($connection);
            return $homework;
        }
        else
        {
            $stmt->close();
            mysqli_close($connection);
            echo "Error Inserting Homework : (" . $stmt->errno . ") " . $stmt->error;  
            return null;
        }
    }
    
    public function DeleteHomework($idHomework){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
         $sql = "DELETE From homework".
               " WHERE id = ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $idHomework); 
        
        if (!$stmt->execute()) {
            echo "Error Deleting Homework : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
            return false;
        }
        $stmt->close();
        mysqli_close($connection);
        return true;
    }
    
    public function GetHomeworksBySubjectId($subjecId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT id,title,description,file_name".
               " FROM homework".
               " WHERE id_subject = ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $subjecId); 
        
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
            $homeworks = array();
            if ($result->num_rows > 0){
                
                while ($row = $result->fetch_assoc()) {
                     $homework = new Homework();
                     $homework->id = $row['id'];
                     $homework->title = $row['title'];
                     $homework->description = $row['description'];
                     $homework->fileName = $row['file_name'];
                     $homework->subjectId = $subjecId;
                     $homeworks[] = $homework; 
                }
                $stmt->close();
                mysqli_close($connection);
                return $homeworks;
            }
            else{
                $stmt->close();
                mysqli_close($connection);
                return null;
            }
            
        }
        else {
            echo "Execute failed on method GetHomeworksBySubjectId : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
        }
        
        
    }
    
    public function GetHomeworkById($homeworkId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT id,title,description,file_name".
               " FROM homework".
               " WHERE id = ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $homeworkId); 
        
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0){
                 $row = $result->fetch_assoc(); 
                 $homework = new Homework();
                 $homework->id = $row['id'];
                 $homework->title = $row['title'];
                 $homework->description = $row['description'];
                 $homework->fileName = $row['file_name'];
                 $homework->subjectId = $subjecId;
                
                $stmt->close();
                mysqli_close($connection);
                return $homework;
            }
            else{
                $stmt->close();
                mysqli_close($connection);
                return null;
            }
        }
        else {
            echo "Execute failed on method GetHomeworkById : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
        }
        
        
        
    }
    
    
    
    
}


?>