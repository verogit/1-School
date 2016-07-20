<?php
require_once('DbConnection.php');

class UserRepository
{
    
    public function GetUserByUsernameAndPassword($userName, $password){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT user.id, id_role,password , username, role.name roleName
                FROM user JOIN role ON user.id_role = role.id 
                WHERE username= ? AND password= ?";
               
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ss', $userName, $password);
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
        
            if ($result->num_rows == 1){
                $user = new User();
                while ($row = $result->fetch_assoc()) {
                     $user->id = $row['id'];
                     $user->idRole = $row['id_role'];
                     $user->password = $row['password'];
                     $user->userName = $row['username'];
                     $user->roleName = $row['roleName'];
                }
                
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return $user;
            }
            else
            {
                $stmt->close();
                $dbConnection->closeConnection($connection);
                return null;
            }
        }
        else
        {
            echo "Execute failed on method GetUserByUsernameAndPassword : (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            mysqli_close($connection);
        }
    }
    
    public function GetUserByUsername($userName){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT user.id, id_role,password , username, role.name roleName".
               " FROM user JOIN role ON user.id_role = role.id ".
               " WHERE username= ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $userName);               
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
        
            if ($result->num_rows == 1){
                $user = new User();
                while ($row = $result->fetch_assoc()) {
                     $user->id = $row['id'];
                     $user->idRole = $row['id_role'];
                     $user->password = $row['password'];
                     $user->userName = $row['username'];
                     $user->roleName = $row['roleName'];
                }
                $stmt->close();
                mysqli_close($connection);
                return $user;
                     
            }
            else{
                $stmt->close();
                mysqli_close($connection);
                return null;
            }    
        }
        else
        {
            echo "Execute failed on method GetUserByUsername : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
        }
        
        
    }
    
    
    public function GetUserById($id){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT user.id, id_role,password , username, role.name roleName".
               " FROM user JOIN role ON user.id_role = role.id ".
               " WHERE user.id= ?";
               
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $id);               
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1){
                $user = new User();
                while ($row = $result->fetch_assoc()) {
                     $user->id = $row['id'];
                     $user->idRole = $row['id_role'];
                     $user->password = $row['password'];
                     $user->userName = $row['username'];
                     $user->roleName = $row['roleName'];
                }
                $stmt->close();
                mysqli_close($connection);
                return $user;
                     
            }
            else{
                $stmt->close();
                mysqli_close($connection);
                return null;
            }
        }
        else {
            echo "Execute failed on method GetUserById : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
        }
        
        
    }
    
    public function DeleteUser($userId){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "DELETE FROM user WHERE id = ?";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $userId);  
        
        if ($stmt->execute()) {
            $stmt->close();
            mysqli_close($connection);
            return $user;
            
        }else {
            echo "Execute failed on method DeleteUser : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
        }
        
        
    }
    
    public function GetUserByEmail($email){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "select user.id, user.id_role,user.username, user.password
                from user
                left join student on user.id = student.id_user
                left join teacher on user.id = teacher.id_user
                where  ( LOWER(student.email_student) = LOWER(?) || LOWER(student.email_parent) = LOWER(?)
                        || LOWER(teacher.email) = LOWER(?))";
                        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('sss', $email,$email,$email);                          
        
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0){
                $user = new User();
                $row = $result->fetch_assoc();
                $user->id = $row['id'];
                $user->idRole = $row['id_role'];
                $user->password = $row['password'];
                $user->userName = $row['username'];
                
                $stmt->close();
                mysqli_close($connection);
                return $user;
            }
            else{
                $stmt->close();
                mysqli_close($connection);
                return null;
            }
        }
        else {
            echo "Execute failed on method GetUserByEmail : (" . $stmt->errno . ") " . $stmt->error;  
            $stmt->close();
            mysqli_close($connection);
            
        }
    }
    
    
}



?>