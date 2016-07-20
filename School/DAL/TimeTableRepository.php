<?php

require_once('DbConnection.php');

class TimeTableRepository
{
    
    public function GetTimeTableBySubjectId($subjectId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT time_table.id, time_table.day, time_table.start_time, time_table.finish_time
                FROM subject
                JOIN subject_time ON subject_time.id_subject = subject.id
                JOIN time_table ON time_table.id = subject_time.id_time_table
                WHERE subject.id =?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $subjectId); 
        
        if ($stmt->execute()) {
           
           $result = $stmt->get_result();
           
           $timeTables = array();
            if ($result->num_rows > 0){
                
                while ($row = $result->fetch_assoc()) {
                     $timeTable = new TimeTable();
                     $timeTable->id = $row['id'];
                     $timeTable->day = $row['day'];
                     $timeTable->startTime = $row['start_time'];
                     $timeTable->finishTime = $row['finish_time'];
                     
                     $timeTables[] = $timeTable; 
                }
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return $timeTables;
                     
            }
            else{
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return null;
            }
            
        }
        else {
            echo "Execute failed on method GetTimeTableBySubjectId : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
        }
        
    }
    

    public function DeleteAllTimeTableOfSubject($subject){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        mysqli_autocommit($connection, false); //disable the autocommit
        $areQueriesExecutedSuccessfully = true;
        
        $timeTables = $this->GetTimeTableBySubjectId($subject->id);
        
        if($timeTables != null){
            
            foreach ($timeTables as $row) {
                $sql = "DELETE FROM time_table
                        WHERE id=".$row->id;
                
                if (!mysqli_query($connection,$sql)) {
                    $areQueriesExecutedSuccessfully = false;
                    echo "Error Deleting time table " . $sql . "<br>" . mysqli_error($connection);
                }
                
            }
        }
        else
        {
            mysqli_close($connection);
            return false;
        }
        
        
        //DELETE subject_time
        $sqlSubjectTime = "DELETE FROM subject_time
                       WHERE id_subject = ". $subject->id;
                
        if (!mysqli_query($connection,$sqlSubjectTime)) {
            $areQueriesExecutedSuccessfully = false;
            mysqli_close($connection);
            echo "Error Deleting Subject Time Table : " . $sql . "<br>" . mysqli_error($connection);
            return null;
        }
        
        
        if($areQueriesExecutedSuccessfully == true)
        {
            mysqli_commit($connection); //COMMIT Changes to the database
            mysqli_close($connection);
            return true;
            
        }
        else {
            mysqli_rollback($connection); //ROLLBACK changes in the database;
            mysqli_close($connection);
            return false;
        }        
    }
}

?>