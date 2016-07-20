<?php
    require_once('DbConnection.php');

    class StudentRepository
    {
        public function InsertStudent($student) {
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
        
            mysqli_autocommit($connection, false); //disable the autocommit
            $areQueriesExecutedSuccessfully = true;
        
            /////////////INSERT IN TABLE USER////////////////////
            $sqlUser = "INSERT INTO user (id_role,username, password)".
               " VALUES (?,?,?)";
            
            $stmt = $connection->prepare($sqlUser);
            $stmt->bind_param('sss', $student->idRole,$student->userName,$student->password); 
        
            if ($stmt->execute()) {
                $student->idUser = $stmt->insert_id;
            }
            else {
                $areQueriesExecutedSuccessfully = false;
                echo "Error Inserting User : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
                return null;
            }
            
             //////////////INSERT IN TABLE STUDENT///////////////////////////
            $sql = "INSERT INTO student (first_name,last_name,date_of_birth,telephone,email_student,email_parent,id_user,address,gender,is_deleted)
                    VALUES (?,?, ?, ?, ?,?, ?, ?, ?, ?)";
        
            $stmtStudent = $connection->prepare($sql);
            $stmtStudent->bind_param('ssssssssss', $student->firstName,$student->lastName,$student->dateOfBirth,$student->telephone
                                    ,$student->emailStudent,$student->emailParent,$student->idUser,$student->address,$student->gender,$student->isDeleted); 
            
            if ($stmtStudent->execute()) {
                $student->id = $stmtStudent->insert_id;
            }
            else {
                $areQueriesExecutedSuccessfully = false;
                 echo "Error Inserting Student : (" . $stmtStudent->errno . ") " . $stmtStudent->error;  
            }
            
            ////////////////////////INSERT IN TABLE CLASSROOM-STUDENT////////////////////////
            $sqlClassStudent = "INSERT INTO classroom_student (id_class,id_student)
                                VALUES (?, ?)";
                                
            $stmtClass = $connection->prepare($sqlClassStudent);
            $stmtClass->bind_param('ss', $student->classroom->id,$student->id);               
                                
            if (!$stmtClass->execute()) {
                $areQueriesExecutedSuccessfully = false;
                 echo "Error Inserting Class Student : (" . $stmtClass->errno . ") " . $stmtClass->error;  
            }
        
            if($areQueriesExecutedSuccessfully == true)
            {
                mysqli_commit($connection); //COMMIT Changes to the database
                mysqli_close($connection);
                return $student;
            }
            else {
                mysqli_rollback($connection); //ROLLBACK changes in the database;
                mysqli_close($connection);
                return null;
            }
        }
        
        public function UpdateStudent($student){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
        
            mysqli_autocommit($connection, false); //disable the autocommit
            $areQueriesExecutedSuccessfully = true;
        
            /////////////UPDATE IN TABLE USER////////////////////
            $sqlUser = "UPDATE user
                        SET username = ?,password =?
                        WHERE id = ?";
                        
            $stmt = $connection->prepare($sqlUser);
            $stmt->bind_param('sss', $student->userName,$student->password,$student->idUser);               
   
            if (!$stmt->execute()) {
                $areQueriesExecutedSuccessfully = false;
                echo "Error Updating User : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
                return null;
            }
            
             //////////////UPDATE IN TABLE STUDENT///////////////////////////
            $sql = "UPDATE student 
                    SET first_name = ?,last_name=?,date_of_birth=?,telephone=?,email_student=?,email_parent=?,
                        address=?,gender=?,is_deleted=?
                     WHERE id= ?";
                    
            $stmtStudent = $connection->prepare($sql);
            $stmtStudent->bind_param('ssssssssss', $student->firstName,$student->lastName,$student->dateOfBirth,$student->telephone,$student->emailStudent,
                                        $student->emailParent,$student->address,$student->gender,$student->isDeleted,$student->id);               

            if (!$stmtStudent->execute()) {
               $areQueriesExecutedSuccessfully = false;
               echo "Error Updating Student : (" . $stmtStudent->errno . ") " . $stmtStudent->error;  
            }
            
            ////////////////////////UPDATE IN TABLE CLASSROOM-STUDENT////////////////////////
            $sqlClassStudent = "UPDATE classroom_student 
                                SET id_class=?
                                WHERE id_student=?";
                                
            $stmtClass = $connection->prepare($sqlClassStudent);
            $stmtClass->bind_param('ss', $student->classroom->id,$student->id);               
                                
            if (!$stmtClass->execute()) {
                $areQueriesExecutedSuccessfully = false;
                echo "Error Updating Class Student : (" . $stmtClass->errno . ") " . $stmtClass->error;  
            }
        
            if($areQueriesExecutedSuccessfully == true)
            {
                mysqli_commit($connection); //COMMIT Changes to the database
                mysqli_close($connection);
                return $student;
            }
            else {
                mysqli_rollback($connection); //ROLLBACK changes in the database;
                mysqli_close($connection);
                return null;
            }    
        }
        
        public function GetAllStudents(){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
           
            $sql = "SELECT student.id, student.first_name, student.last_name, student.date_of_birth, student.telephone, student.email_student, student.email_parent, student.id_user, student.address, student.gender, classroom.year, classroom.section_name, level.name FROM student
	            	JOIN classroom_student
		                ON student.id = classroom_student.id_student
		            JOIN classroom
		                ON classroom.id = classroom_student.id_class
		            JOIN level
		                ON level.id = classroom.id_level
                    WHERE is_deleted = false
                    ORDER BY student.first_name, student.last_name DESC ";
                    
            $result = mysqli_query($connection,$sql);
            
            $students = array();
            if (mysqli_num_rows($result) > 0){
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $student = new Student();
                    $student->id = $row['id'];
                    $student->firstName = $row['first_name'];
                    $student->lastName = $row['last_name'];
                    $student->address = $row['address'];
                    $student->emailStudent = $row['email_student'];
                    $student->emailParent = $row['email_parent'];
                    $student->dateOfBirth = $row['date_of_birth'];
                    $student->gender = $row['gender'];
                    $student->telephone = $row['telephone'];
                    $student->idUser = $row['id_user'];
                    $student->classroom = new Classroom();
                    $student->classroom->level = new Level();
                    $student->classroom->level->name = $row['name'];
                    $student->classroom->year = $row['year'];
                    $student->classroom->sectionName = $row['section_name'];
                    $students[] = $student; 
                }
            mysqli_close($connection);
            return $students;
            
            }else{
                mysqli_close($connection);
                return null;
            }
        }
        
        public function GetStudentById($studentId){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
           
            $sql = "SELECT student.id, student.first_name, student.last_name, student.date_of_birth, student.telephone, 
            student.email_student, student.email_parent, student.id_user, student.address, student.gender, classroom.year, 
            classroom.section_name, classroom.id as idclassroom, level.name, user.username, user.password
					FROM student
	            	JOIN classroom_student
		                ON student.id = classroom_student.id_student
		            JOIN classroom
		                ON classroom.id = classroom_student.id_class
		            JOIN level
		                ON level.id = classroom.id_level
					JOIN user
						ON user.id = student.id_user
                    WHERE student.id=?";
                    
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $studentId); 
            
            if ($stmt->execute()) {
                
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0){
                
                    while ($row = $result->fetch_assoc()) {
                        $student = new Student();
                        $student->id = $row['id'];
                        $student->firstName = $row['first_name'];
                        $student->lastName = $row['last_name'];
                        $student->address = $row['address'];
                        $student->emailStudent = $row['email_student'];
                        $student->emailParent = $row['email_parent'];
                        $student->dateOfBirth = $row['date_of_birth'];
                        $student->gender = $row['gender'];
                        $student->telephone = $row['telephone'];
                        $student->idUser = $row['id_user'];
                        $student->userName = $row['username'];
                        $student->password = $row['password'];
                        $student->classroom = new Classroom();
                        $student->classroom->level = new Level();
                        $student->classroom->id = $row['idclassroom'];
                        $student->classroom->level->name = $row['name'];
                        $student->classroom->year = $row['year'];
                        $student->classroom->sectionName = $row['section_name'];
                    }
                    
                $stmt->close();
                mysqli_close($connection);
                return $student;
                
                }else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
                
            }
            else {
                echo "Execute failed on method GetStudentById : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
            
            
        }
        
        public function GetStudentByEmailStudent($email){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
           
            $sql = "SELECT *
                    FROM student
                    WHERE email_student = ?";
                    
            
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $email); 
            
            if ($stmt->execute()) {
                
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $student = new Student();
                    $student->userName = $row['username'];
                    $student->firstName = $row['first_name'];
                    $student->lastName = $row['last_name'];
                    $student->address = $row['address'];
                    $student->emailStudent = $row['emailStudent'];
                    $student->emailParent = $row['emailParent'];
                    $student->dateOfBirth = $row['date_of_birth'];
                    $student->gender = $row['gender'];
                    $student->telephone = $row['telephone'];
                    
                    $stmt->close();
                    mysqli_close($connection);
                    
                    return $student;
                }
                else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
                
            }
            else {
                echo "Execute failed on method GetStudentByEmailStudent : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
            
            
       }
       
       public function GetStudentByEmailParent($email){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
           
            $sql = "SELECT *
                    FROM student
                    WHERE email_parent = ?";
                    
            
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $email); 
            
            if ($stmt->execute()) {
                
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $student = new Student();
                    $student->userName = $row['username'];
                    $student->firstName = $row['first_name'];
                    $student->lastName = $row['last_name'];
                    $student->address = $row['address'];
                    $student->emailStudent = $row['emailStudent'];
                    $student->emailParent = $row['emailParent'];
                    $student->dateOfBirth = $row['date_of_birth'];
                    $student->gender = $row['gender'];
                    $student->telephone = $row['telephone'];
                    
                    $stmt->close();
                    mysqli_close($connection);
                    
                    return $student;
                }
                else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
                
            }
            else {
                echo "Execute failed on method GetStudentByEmailParent : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
            
            
       }
       
       
       public function GetStudentOfTheClassroom($levelId, $section, $year){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
           
            $sql = "SELECT student.id as studentId, first_name, last_name, date_of_birth, telephone, email_student, email_parent, gender, address
                    from classroom
                    join classroom_student on classroom_student.id_class = classroom.id
                    join student on student.id = classroom_student.id_student
                    WHERE classroom.id_level = ? and classroom.section_name = ? and classroom.year= ? 
                    ORDER BY first_name, last_name DESC";
            
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $levelId,$section,$year); 
            
            if ($stmt->execute()) {
                
                $result = $stmt->get_result();
                
                $students = array();
                if ($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()){
                        $student = new Student();
                        $student->id = $row['studentId'];
                        $student->firstName = $row['first_name'];
                        $student->lastName = $row['last_name'];
                        $student->address = $row['address'];
                        $student->emailStudent = $row['email_student'];
                        $student->emailParent = $row['email_parent'];
                        $student->dateOfBirth = $row['date_of_birth'];
                        $student->gender = $row['gender'];
                        $student->telephone = $row['telephone'];
                        $students[] = $student;
                    }
                    $stmt->close();
                    mysqli_close($connection);
                    
                    return $students;
                }
                else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
            }
            else {
                echo "Execute failed on method GetStudentOfTheClassroom : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
            
            
       }
       
        public function DeleteStudent($studentId, $idUser){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
        
            mysqli_autocommit($connection, false); //disable the autocommit
            $areQueriesExecutedSuccessfully = true;
        
        
            ///DELETE STUDENT
            $sql = "DELETE FROM student WHERE id= ?";
            
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $studentId); 
        
            if (!$stmt->execute()) {
                echo "Error deleting Student : (" . $stmt->errno . ") " . $stmt->error; 
                $stmt->close();
                mysqli_close($connection);
                return false;
            }
        
            //DELETE USER
            $sqlUser = "DELETE FROM user WHERE id = ?";
            
            $stmtUser = $connection->prepare($sqlUser);
            $stmtUser->bind_param('s', $idUser);
        
            if (!$stmtUser->execute()) {
                $areQueriesExecutedSuccessfully = false;
                echo "Error deleting User : (" . $stmtUser->errno . ") " . $stmtUser->error; 
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
        
        public function GetStudentByIdUser($userId){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
           
            $sql = "SELECT *
                    FROM student
                    WHERE id_user = ?";
              
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $userId); 
            
            if ($stmt->execute()) {
                
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $student = new Student();
                    $student->id = $row['id'];
                    $student->userName = $row['username'];
                    $student->firstName = $row['first_name'];
                    $student->lastName = $row['last_name'];
                    $student->address = $row['address'];
                    $student->emailStudent = $row['emailStudent'];
                    $student->emailParent = $row['emailParent'];
                    $student->dateOfBirth = $row['date_of_birth'];
                    $student->gender = $row['gender'];
                    $student->telephone = $row['telephone'];
                    
                    $stmt->close();
                    mysqli_close($connection);
                    
                    return $student;
    
                }
                else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
            }
            else {
                echo "Execute failed on method GetStudentByIdUser : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
        }
        
        public function GetTimetableByStudent($idStudent){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
           
            $sql = "SELECT subject.name, time_table.day, time_table.start_time, time_table.finish_time 
                    FROM student
	                JOIN classroom_student ON student.id= classroom_student.id_student
	                jOIN classroom ON classroom_student.id_class=classroom.id
	                jOIN level on classroom.id_level=level.id
	                jOIN subject on level.id=subject.id_level
	                jOIN subject_time on subject.id=subject_time.id_subject
	                jOIN time_table on subject_time.id_time_table=time_table.id
	                WHERE student.id = ? 
	                ORDER BY time_table.start_time";
              
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $idStudent); 
            
            if ($stmt->execute()) {
                
                $result = $stmt->get_result();
                
                $timetables = array();
                if ($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()){
                        $timetable = new TimetableDto();
                        $timetable->subject = $row['name'];
                        $timetable->day = $row['day'];
                        $timetable->timeStart = $row['start_time'];
                        $timetable->timeFinish = $row['finish_time'];
        
                        $timetables[] = $timetable;
                    }
                    
                    $stmt->close();
                    mysqli_close($connection);
                    
                    return $timetables;
                }
                else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
                
            }
            else {
                echo "Execute failed on method GetTimetableByStudent : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
        }
        
        public function GetStudentOfTheClassroomWithGrades($levelId, $section, $year, $subjectId){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
           
            $sql = "SELECT student.id as studentId, first_name, last_name, date_of_birth, telephone, email_student, email_parent, 
                    gender, address, grade.grade
                    from classroom
                    join level on level.id = classroom.id_level
                    join subject on subject.id_level = level.id
                    join classroom_student on classroom_student.id_class = classroom.id 
                    join student on student.id = classroom_student.id_student 
                    left join grade on grade.id_subject = subject.id and grade.id_student = student.id
                    WHERE classroom.id_level = ? and classroom.section_name = ?
                          and subject.id = ? and classroom.year= ?
                    ORDER BY student.first_name, student.last_name DESC";
            
            
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ssss', $levelId,$section,$subjectId,$year);   
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                $studentDtos = array();
                if ($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()){
                        $studentDto = new StudentDto();
                        $studentDto->id = $row['studentId'];
                        $studentDto->firstName = $row['first_name'];
                        $studentDto->lastName = $row['last_name'];
                        $studentDto->gender = $row['gender'];
                        $studentDto->subjectId = $subjectId;
                        $studentDto->grade = $row['grade'];
                        $studentDtos[] = $studentDto;
                    }
                    
                    $stmt->close();
                    mysqli_close($connection);
                    
                    return $studentDtos;
                }
                else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
            }
            else {
                echo "Execute failed on method GetStudentOfTheClassroomWithGrades : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
       }
        
        
        public function GetSubjectsOfStudent($studentId){
            $dbConnection = new DbConnection();
            $connection = $dbConnection->createConnection();
           
            $sql = "select subject.id as subjectId, subject.name as subjectName, level.id as levelId, level.name as levelName, 
                    classroom.section_name, classroom.year, IFNULL(grade.grade,'-') as grade
                    from teacher 
                    join subject on subject.id_teacher = teacher.id
                    join level on level.id = subject.id_level
                    join classroom on level.id = classroom.id_level
                    join classroom_student on classroom.id = classroom_student.id_class
                    join student on student.id = classroom_student.id_student
                    left join grade on grade.id_subject = subject.id
                    where student.id = ?
                    group by subject.name
                    order by subject.name desc";
            
            
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $studentId); 
            
            if ($stmt->execute()) {
                
                $result = $stmt->get_result();
                
                $classDtos = array();
                if ($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()){
                        $classDto = new ClassDto();
                        $classDto->subjectId = $row['subjectId'];
                        $classDto->subjectName = $row['subjectName'];
                        $classDto->levelId = $row['levelId'];
                        $classDto->levelName = $row['levelName'];
                        $classDto->section = $row['section_name'];
                        $classDto->year = $row['year'];
                        $classDto->grade = $row['grade'];
                        $classDtos[] = $classDto;
                    }
                    
                    $stmt->close();
                    mysqli_close($connection);
                    return $classDtos;
                }
                else{
                    $stmt->close();
                    mysqli_close($connection);
                    return null;
                }
            }
            else {
                echo "Execute failed on method GetSubjectsOfStudent : (" . $stmt->errno . ") " . $stmt->error;  
                $stmt->close();
                mysqli_close($connection);
            }
            
            
       }
        
    }

?>