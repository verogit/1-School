 <?php
require_once('DbConnection.php');

class SubjectRepository
{
  
    public function GetAllSubjects(){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT subject.id as subjectId, subject.name as subjectName, subject.id_level, subject.id_teacher, 
                        level.name as levelName, teacher.first_name, teacher.last_name
                FROM subject 
                JOIN teacher ON teacher.id = subject.id_teacher
                JOIN level ON level.id = subject.id_level
                GROUP BY subject.id,subject.id_level";
        
        $result= mysqli_query($connection,$sql);
        
        $subjects = array();
        if (mysqli_num_rows($result) > 0){
            
            while ($row = mysqli_fetch_assoc($result)) {
                 $subject = new Subject();
                 $subject->id = $row['subjectId'];
                 $subject->name = $row['subjectName'];
                 
                 $subject->level = new Level();
                 $subject->level->id = $row['id_level'];
                 $subject->level->name = $row['levelName'];
                 
                 $subject->teacher = new Teacher();
                 $subject->teacher->id = $row['id_teacher'];
                 $subject->teacher->firstName = $row['first_name'];
                 $subject->teacher->lastName = $row['last_name'];
                 
                 $subjects[] = $subject; 
            }
            $dbConnection->closeConnection($connection);
            return $subjects;
                 
        }
        else{
            $dbConnection->closeConnection($connection);
            return null;
        }
    }
    
    public function InsertSubject($subject){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        mysqli_autocommit($connection, false); //disable the autocommit
        $areQueriesExecutedSuccessfully = true;
        
        //INSERT SUBJECT
        $sqlSubject = "INSERT INTO subject (name,id_level, id_teacher) 
                VALUES (?, ?, ?)";
                
        $stmt = $connection->prepare($sqlSubject);
        $stmt->bind_param('sss', $subject->name, $subject->level->id,$subject->teacher->id);
                
        if ($stmt->execute()) {
            $subject->id = $stmt->insert_id;
        }
        else {
            $areQueriesExecutedSuccessfully = false;
            echo "Error Inserting Subject : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
            return null;
        }
        
        //INSERT TIME TABLES
        
        foreach ($subject->timeTables as $row) {
            $sqlTimeTable = "INSERT INTO time_table (day, start_time, finish_time) 
                VALUES ('" . $row->day . "', '". $row->startTime . "', '". $row->finishTime ."')";        
            
            if (mysqli_query($connection,$sqlTimeTable)) {
                $row->id = mysqli_insert_id($connection);
            }
            else {
                $areQueriesExecutedSuccessfully = false;
                echo "Error Inserting time_table : " . $sql . "<br>" . mysqli_error($connection);
            }
        }
        
        //INSERT SUBJECT_TIME
        
        foreach ($subject->timeTables as $row) {
            $sqlSubjectTime = "INSERT INTO subject_time (id_subject,id_time_table) 
                VALUES (" . $subject->id . ", ". $row->id . ")";        
            
            if (!mysqli_query($connection,$sqlSubjectTime)) {
                $areQueriesExecutedSuccessfully = false;
                echo "Error Inserting SUBJECT_TIME : " . $sql . "<br>" . mysqli_error($connection);
            }
        }
        
        if($areQueriesExecutedSuccessfully == true)
        {
            mysqli_commit($connection); //COMMIT Changes to the database
            mysqli_close($connection);
            return $subject;
            
        }
        else {
            mysqli_rollback($connection); //ROLLBACK changes in the database;
            mysqli_close($connection);
            return null;
        }        
    }
    
    public function GetSubjectByNameAndLevel($subjectName, $levelId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT subject.id,LOWER(REPLACE(subject.name,  ' ','')) as subjetName, level.name as levelName
                FROM subject
                JOIN level ON level.id = subject.id_level
                WHERE subject.name= ? AND level.id = ?";
                
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ss', $subjectName, $levelId);       
        
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0){
                $subject = new Subject();
                while ($row = $result->fetch_assoc()) {
                     $subject->id = $row['id'];
                     $subject->name = $row['subjetName'];
                     $subject->level = new Level();
                     $subject->level->name =  $row['levelName'];
                }
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return $subject;
            }
            else{
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return null;
            }
            
        }
        else {
            echo "Execute failed on method GetSubjectByNameAndLevel : (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            mysqli_close($connection);
        }
        
        
    }
    
    public function GetSubjectsByTeacherId($teacherId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "select subject.id as subjectId, subject.name as subjectName, level.id as levelId, level.name as levelName,
                teacher.first_name as teacherFirstName, time_table.id as timeTableId, time_table.day, time_table.start_time, time_table.finish_time
                from subject
                join level on level.id = subject.id_level
                join teacher on teacher.id = subject.id_teacher
                join subject_time on subject_time.id_subject = subject.id
                join time_table on time_table.id = subject_time.id_time_table
                where teacher.id = ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $teacherId);
        
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0){
                $subject = new Subject();
                while ($row = $result->fetch_assoc()) {
                     $subject->id = $row['subjectId'];
                     $subject->name = $row['subjectName'];
                     $subject->level = new Level();
                     $subject->level->id = $row['levelId'];
                     $subject->level->name =  $row['levelName'];
                     $subject->teacher = new Teacher();
                     $subject->teacher->firstName = $row['teacherFirstName'];
                     $timeTable = new TimeTable();
                     $timeTable->id = $row['timeTableId'];
                     $timeTable->day = $row['day'];
                     $timeTable->startTime = $row['start_time'];
                     $timeTable->finishTime = $row['finish_time'];
                     $subject->timeTables[] = $timeTable;
                }
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return $subject;
            }
            else{
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return null;
            }
            
        }
        else {
            echo "Execute failed on method GetSubjectsByTeacherId : (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            mysqli_close($connection);
        }
        
        
    }
    
    public function GetSubjectsByLevelId($levelId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "select subject.id as subjectId, subject.name as subjectName, level.id as levelId, level.name as levelName,
                teacher.first_name as teacherFirstName, time_table.id as timeTableId, time_table.day, time_table.start_time, time_table.finish_time
                from subject
                join level on level.id = subject.id_level
                join teacher on teacher.id = subject.id_teacher
                join subject_time on subject_time.id_subject = subject.id
                join time_table on time_table.id = subject_time.id_time_table
                where level.id = ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $levelId);
        
        if ($stmt->execute()) {
           
           $result = $stmt->get_result();
           
           if ($result->num_rows > 0){
                $subject = new Subject();
                while ($row = $result->fetch_assoc()) {
                     $subject->id = $row['subjectId'];
                     $subject->name = $row['subjectName'];
                     $subject->level = new Level();
                     $subject->level->id = $row['levelId'];
                     $subject->level->name =  $row['levelName'];
                     $subject->teacher = new Teacher();
                     $subject->teacher->firstName = $row['teacherFirstName'];
                     $timeTable = new TimeTable();
                     $timeTable->id = $row['timeTableId'];
                     $timeTable->day = $row['day'];
                     $timeTable->startTime = $row['start_time'];
                     $timeTable->finishTime = $row['finish_time'];
                     $subject->timeTables[] = $timeTable;
                }
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return $subject;
            }
            else{
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return null;
            }
           
        }
        else {
            
            echo "Execute failed on method GetSubjectsByLevelId : (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            mysqli_close($connection);
            
        }
        
        
    }
    
    
    public function GetSubjectsById($subjectId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "select subject.id as subjectId, subject.name as subjectName, level.id as levelId, level.name as levelName,
                teacher.first_name as teacherFirstName, teacher.id as teacherId, time_table.id as timeTableId, time_table.day, time_table.start_time, time_table.finish_time
                from subject
                join level on level.id = subject.id_level
                join teacher on teacher.id = subject.id_teacher
                join subject_time on subject_time.id_subject = subject.id
                join time_table on time_table.id = subject_time.id_time_table
                where subject.id = ?";
                
                
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $subjectId); 
        
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
           if ($result->num_rows > 0){
                $subject = new Subject();
                while ($row = $result->fetch_assoc()) {
                     $subject->id = $row['subjectId'];
                     $subject->name = $row['subjectName'];
                     $subject->level = new Level();
                     $subject->level->id = $row['levelId'];
                     $subject->level->name =  $row['levelName'];
                     $subject->teacher = new Teacher();
                     $subject->teacher->id = $row['teacherId'];
                     $subject->teacher->firstName = $row['teacherFirstName'];
                     $timeTable = new TimeTable();
                     $timeTable->id = $row['timeTableId'];
                     $timeTable->day = $row['day'];
                     $timeTable->startTime = $row['start_time'];
                     $timeTable->finishTime = $row['finish_time'];
                     $subject->timeTables[] = $timeTable;
                }
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return $subject;
            }
            else{
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return null;
            } 
            
        }
        else {
            echo "Execute failed on method GetSubjectsById : (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            mysqli_close($connection);
        }
        
        
        
    }
    
    
    public function UpdateSubject($subject){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        mysqli_autocommit($connection, false); //disable the autocommit
        $areQueriesExecutedSuccessfully = true;
        
        //UPDATE SUBJECT
        $sqlSubject = "UPDATE subject
                       SET name = ?, id_level = ?, id_teacher = ?
                       WHERE id = ?";
                       
        $stmt = $connection->prepare($sqlSubject);
        $stmt->bind_param('ssss',  $subject->name,$subject->level->id,$subject->teacher->id,$subject->id); 
                
        if (!$stmt->execute()) {
            $areQueriesExecutedSuccessfully = false;
            echo "Error  Updating Subject : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
            return null;
        }
        
        //INSERTING TIME TABLES
        
        foreach ($subject->timeTables as $row) {
            
             $sqlTimeTable = "INSERT INTO time_table (day, start_time, finish_time) 
                                VALUES ('" . $row->day . "', '". $row->startTime . "', '". $row->finishTime ."')";
                                
            if (mysqli_query($connection,$sqlTimeTable)) {
                $row->id = mysqli_insert_id($connection);
                    
            }
            else {
                $areQueriesExecutedSuccessfully = false;
                echo "Error Updating time_table : " . $sql . "<br>" . mysqli_error($connection);
            }                                
        }
        
        //UPDATE SUBJECT_TIME
        
        foreach ($subject->timeTables as $row) {
            
            $sqlSubjectTime = "INSERT INTO subject_time (id_subject,id_time_table) 
                                    VALUES (" . $subject->id . ", ". $row->id . ")";
            
            if (!mysqli_query($connection,$sqlSubjectTime)) {
                $areQueriesExecutedSuccessfully = false;
                echo "Error Updating SUBJECT_TIME : " . $sql . "<br>" . mysqli_error($connection);
            }
        }
        
        if($areQueriesExecutedSuccessfully == true)
        {
            mysqli_commit($connection); //COMMIT Changes to the database
            mysqli_close($connection);
            return $subject;
            
        }
        else {
            mysqli_rollback($connection); //ROLLBACK changes in the database;
            mysqli_close($connection);
            return null;
        }        
    }
    
    
     public function DeleteSubject($subjectId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        ///DELETE SUBJECT
        $sql = "DELETE FROM subject WHERE id= ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $subjectId); 
        
        if ($stmt->execute()) {
            $stmt->close();
            mysqli_close($connection);
            return true;
        }
        else {
            echo "Error  Deleting Subject : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
            return false;
        }
    }
    
    
}



?>