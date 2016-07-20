<?php
require_once('DbConnection.php');

class TeacherRepository
{
    
    public function GetAllTeachers(){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT *
                FROM teacher
                WHERE is_deleted = false
                ORDER BY first_name, last_name DESC";
        
        $result= mysqli_query($connection,$sql);
        
        $teachers = array();
        if (mysqli_num_rows($result) > 0){
            
            while ($row = mysqli_fetch_assoc($result)) {
                 $teacher = new Teacher();
                 $teacher->id = $row['id'];
                 $teacher->firstName = $row['first_name'];
                 $teacher->lastName = $row['last_name'];
                 $teacher->address = $row['address'];
                 $teacher->email = $row['email'];
                 $teacher->dateOfBirth = $row['date_of_birth'];
                 $teacher->gender = $row['gender'];
                 $teacher->telephone = $row['telephone'];
                 $teacher->idUser = $row['id_user'];
                 $teachers[] = $teacher; 
            }
            mysqli_close($connection);
            return $teachers;
                 
        }
        else{
            mysqli_close($connection);
            return null;
        }
            
    }
    
    public function InsertTeacher($teacher){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        mysqli_autocommit($connection, false); //disable the autocommit
        $areQueriesExecutedSuccessfully = true;
        
        /////////////INSERT IN TABLE USER////////////////////
         $sqlUser = "INSERT INTO user (id_role,username, password) VALUES (?, ?, ?)";
               
        $stmt = $connection->prepare($sqlUser);
        $stmt->bind_param('sss', $teacher->idRole, $teacher->userName,$teacher->password);       
               
        
        if ($stmt->execute()) {
            $teacher->idUser = $stmt->insert_id;
        }
        else {
            $areQueriesExecutedSuccessfully = false;
            echo "Error Inserting User : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
            return null;
        }
        
        //////////////INSERT IN TABLE TEACHER///////////////////////////
        $sql = "INSERT INTO teacher (first_name,last_name,date_of_birth,email,id_user,gender,telephone,address,is_deleted)".
               " VALUES (?, ?,?,?,?,?,?,?,?)";
        
        $stmtTeacher = $connection->prepare($sql);
        $stmtTeacher->bind_param('sssssssss', $teacher->firstName, $teacher->lastName,$teacher->dateOfBirth,$teacher->email,
                                        $teacher->idUser,$teacher->gender,$teacher->telephone,$teacher->address,$teacher->isDeleted);       
        
        if ($stmtTeacher->execute()) {
            $teacher->id = $stmtTeacher->insert_id;
        }
        else {
            $areQueriesExecutedSuccessfully = false;
            echo "Error inserting teacher : (" . $stmtTeacher->errno . ") " . $stmtTeacher->error;  
        }
        
        if($areQueriesExecutedSuccessfully == true)
        {
            mysqli_commit($connection); //COMMIT Changes to the database
            $stmt->close();
            $stmtTeacher->close();
            mysqli_close($connection);
            return $teacher;
            
        }
        else {
            mysqli_rollback($connection); //ROLLBACK changes in the database;
            $stmtTeacher->close();
            mysqli_close($connection);
            return null;
        }
        
    }
    
    public function GetTeacherByEmail($email){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT * FROM teacher WHERE email= ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $email);
        
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $teacher = new Teacher();    
                $teacher->firstName = $row['first_name'];
                $teacher->lastName = $row['last_name'];
                $teacher->address = $row['address'];
                $teacher->email = $row['email'];
                $teacher->dateOfBirth = $row['date_of_birth'];
                $teacher->gender = $row['gender'];
                $teacher->telephone = $row['telephone'];
                $teacher->idUser = $row['id_user'];
                
                $stmt->close();
                mysqli_close($connection);   
                return $teacher;
            }
            else{
                $stmt->close();
                mysqli_close($connection);
                return null;
            }
               
        }
        else {
            echo "Execute failed on method GetTeacherByEmail : (" . $stmt->errno . ") " . $stmt->error;
        }
        
        
    }
    
    public function GetTeacherByIdUser($userId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT * FROM teacher WHERE id_user= ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $userId);
        
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $teacher = new Teacher();
                $teacher->id = $row['id'];
                $teacher->firstName = $row['first_name'];
                $teacher->lastName = $row['last_name'];
                $teacher->address = $row['address'];
                $teacher->email = $row['email'];
                $teacher->dateOfBirth = $row['date_of_birth'];
                $teacher->gender = $row['gender'];
                $teacher->telephone = $row['telephone'];
                $teacher->idUser = $row['id_user'];
                
                $stmt->close();
                mysqli_close($connection);   
                return $teacher;
            }
            else{
                $stmt->close();
                mysqli_close($connection);
                return null;
            }
            
        }
        else {
            echo "Execute failed on method GetTeacherByIdUser : (" . $stmt->errno . ") " . $stmt->error;
        }
        
        
    }
    
    public function GetTeacherById($teacherId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT user.id AS userId, user.id_role, user.password, user.username, teacher.id AS teacherId, teacher.address, teacher.date_of_birth, "
                ." teacher.email, teacher.first_name, teacher.last_name, teacher.gender, teacher.id_user, teacher.is_deleted, teacher.telephone".
               " FROM teacher JOIN user ON user.id = teacher.id_user ".
               " WHERE teacher.id= ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $teacherId);
        
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $teacher = new Teacher();    
                $teacher->firstName = $row['first_name'];
                $teacher->lastName = $row['last_name'];
                $teacher->address = $row['address'];
                $teacher->email = $row['email'];
                $teacher->dateOfBirth = $row['date_of_birth'];
                $teacher->gender = $row['gender'];
                $teacher->telephone = $row['telephone'];
                $teacher->idUser = $row['id_user'];
                $teacher->userName = $row['username'];
                $teacher->password = $row['password'];
                
                $stmt->close();
                mysqli_close($connection);
                return $teacher;
            }
            else{
                $stmt->close();
                echo "Error: " . $sql . "<br>" . "There is not a teacher register under the id ".$teacherId;
                mysqli_close($connection);
                return null;
            }
            
        }
        else {
            echo "Execute failed on method GetTeacherById : (" . $stmt->errno . ") " . $stmt->error;
        }
        
        
    }
    
    public function UpdateTeacher($teacher){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        mysqli_autocommit($connection, false); //disable the autocommit
        $areQueriesExecutedSuccessfully = true;
        
        /////////////UPDATE USER////////////////////
         $sqlUser = "UPDATE user SET id_role= ?, username= ?, password= ? WHERE id= ?";
               
        $stmt = $connection->prepare($sqlUser);
        $stmt->bind_param('ssss', $teacher->idRole, $teacher->userName,$teacher->password,$teacher->idUser);
        
        if (!$stmt->execute()) {
            $areQueriesExecutedSuccessfully = false;
            echo "Error updating User: " . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            mysqli_close($connection);
            return null;
        }
        
        /////////////UPDATE TEACHER////////////////////
        $sql = "UPDATE teacher
               SET first_name= ?, last_name= ?, date_of_birth= ?, email=  ?, id_user =  ?, gender= ?, telephone=  ?,
               address= ?, is_deleted= ?
               WHERE teacher.id = ?";
               
        $stmtTeacher = $connection->prepare($sql);
        $stmtTeacher->bind_param('ssssssssss', $teacher->firstName, $teacher->lastName,$teacher->dateOfBirth,$teacher->email,$teacher->idUser,
                                $teacher->gender, $teacher->telephone,$teacher->address,$teacher->isDeleted,$teacher->id);
        
        if (!$stmtTeacher->execute()) {
            $areQueriesExecutedSuccessfully = false;
            echo "Error updating Teacher: " . $stmtTeacher->errno . ") " . $stmtTeacher->error;
        }
        
         if($areQueriesExecutedSuccessfully == true)
        {
            mysqli_commit($connection); //COMMIT Changes to the database
            $stmt->close();
            $stmtTeacher->close();
            mysqli_close($connection);
            return $teacher;
            
        }
        else {
            mysqli_rollback($connection); //ROLLBACK changes in the database;
            mysqli_close($connection);
            return null;
        }
        
    }
    
    public function DeleteTeacher($teacherId, $userId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        mysqli_autocommit($connection, false); //disable the autocommit
        $areQueriesExecutedSuccessfully = true;
        
        ///DELETE TEACHER
        $sql = "DELETE FROM teacher WHERE id= ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $teacherId);
        
        if (!$stmt->execute()) {
            echo "Error deleting Teacher: " . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            mysqli_close($connection);
            return false;
        }
        
        //DELETE USER
        $sqlUser = "DELETE FROM user WHERE id = ?";
         
        $stmtUser = $connection->prepare($sqlUser);
        $stmtUser->bind_param('s', $userId);
        
        if (!$stmtUser->execute()) {
            $areQueriesExecutedSuccessfully = false;
            echo "Error deleting User: " . $stmtUser->errno . ") " . $stmtUser->error;
        }
        
        if($areQueriesExecutedSuccessfully == true)
        {
            mysqli_commit($connection); //COMMIT Changes to the database
            $stmtUser->close();
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