<?php
    require_once ('DbConnection.php');
    
    class ClassroomRepository{
        
        public function InsertClassroom($classroom){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
            
            $sql = "INSERT INTO classroom (id_level, year, section_name)
                    VALUES (?, ?, ?)";
                    
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $classroom->level->id,$classroom->year,$classroom->sectionName);               
            
            if ($stmt->execute()) {
                $classroom->id = $stmt->insert_id;
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return $classroom;
            }
            else {
                echo "Error Inserting Classroom : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return null;
            }
        }
        
        public function ExistClassroomByLevelYearSection($classroom){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
            
            $sql = "SELECT * FROM classroom 
                    WHERE id_level = ? and year = ? and section_name = ?";
                    
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $classroom->level->id,$classroom->year,$classroom->sectionName);               

            if ($stmt->execute()) {
                
                $result = $stmt->get_result();
                
                if($result->num_rows > 0){
                    return true;    
                }else{
                    return false;
                }
                
            }
            else {
                echo "Execute failed on method ExistClassroomByLevelYearSection : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
        }
        
        public function GetAllClassrooms(){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
            
            $sql = "SELECT classroom.id, classroom.id_level, level.name, classroom.year, classroom.section_name 
                    FROM classroom 
                    JOIN level ON level.id=classroom.id_level";
                    
            $result = mysqli_query($connection,$sql);
            
            $classrooms = array();
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)){
                    $classroom = new Classroom();
                    $classroom->id = $row['id'];
                    
                    $classroom->level = new Level();
                    $classroom->level->id = $row['id_level'];
                    $classroom->level->name = $row['name'];
                    
                    $classroom->year = $row['year'];
                    $classroom->sectionName = $row['section_name'];
                    
                    $classrooms[] = $classroom;
                }
                
                mysqli_close($connection);
                return $classrooms;
            
            }else{
                mysqli_close($connection);
                return null;
            }
        }
        
        public function GetClassroomsById($classroomId){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
            
            $sql = "SELECT classroom.id, classroom.id_level, level.name, classroom.year, classroom.section_name 
                    FROM classroom 
                    JOIN level ON level.id=classroom.id_level
                    WHERE classroom.id= ?";
                    
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $classroomId);                       
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()){
                        $classroom = new Classroom();
                        $classroom->id = $row['id'];
                        
                        $classroom->level = new Level();
                        $classroom->level->id = $row['id_level'];
                        $classroom->level->name = $row['name'];
                        
                        $classroom->year = $row['year'];
                        $classroom->sectionName = $row['section_name'];
                    }
                    
                    $stmt->close();
                    mysqli_close($connection);
                    return $classroom;
                
                }else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
                
                
            }
            else {
                echo "Execute failed on method GetClassroomsById : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
            
            
        }
        
        function UpdateClassroom($classroom){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
            
            $sql = "UPDATE classroom 
                    SET id_level = ?, year = ?, section_name = ?
                    WHERE id= ?";
                    
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ssss', $classroom->level->id,$classroom->year,$classroom->sectionName,$classroom->id);  
            
            if (!$stmt->execute()) {
                echo "Error Updating Classroom : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return null;
            }
            
            $stmt->close();
            mysqli_close($connection);
            return $classroom;
        }
        
        function DeleteClassroom($classroomId){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
            
            $sql = "DELETE FROM classroom 
                    WHERE id = ?";
            
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $classroomId);               
            
            if ($stmt->execute()) {
                $stmt->close();
                mysqli_close($connection);
                return true;  
            }
            else {
                $stmt->close();
                mysqli_close($connection);
                return false;
            }
            
            
            
            
            
        }
        
        //Return an array of ClassDto objects
        public function GetClassroomInfoByIdTeacher($teacherId){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
            
            $sql = "select subject.id as subjectId, subject.name as subjectName, level.id as levelId, level.name as levelName, classroom.section_name, classroom.year,
                    count(student.id) as numberOfStudent
                    from teacher 
                    join subject on subject.id_teacher = teacher.id
                    join level on level.id = subject.id_level
                    join classroom on level.id = classroom.id_level
                    join classroom_student on classroom.id = classroom_student.id_class
                    join student on student.id = classroom_student.id_student
                    where teacher.id = ?
                    group by subject.name
                    order by subject.name desc";
                    
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $teacherId);               
            
            if ($stmt->execute()) {
                
                $result = $stmt->get_result();
               
               $classrooms = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()){
                        $classDto = new ClassDto();
                        $classDto->subjectId = $row['subjectId'];
                        $classDto->subjectName = $row['subjectName'];
                        $classDto->levelId = $row['levelId'];
                        $classDto->levelName = $row['levelName'];
                        $classDto->section = $row['section_name'];
                        $classDto->year = $row['year'];
                        $classDto->numberofStudents = $row['numberOfStudent'];
                        $classrooms[] = $classDto; 
                    }
                    $stmt->close();
                    mysqli_close($connection);
                    return $classrooms;
                
                }else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
                
                
            }
            else {
                echo "Execute failed on method GetClassroomInfoByIdTeacher : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
            
            
        }
        
    }

?>