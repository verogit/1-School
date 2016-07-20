<?php
require_once('DbConnection.php');

class LevelRepository
{
    public function GetAllLevels(){
        $dbConnection = new DbConnection();
        $connection = $dbConnection->createConnection();
        
        $sql = "SELECT *".
               " FROM level";
        
        $result= mysqli_query($connection,$sql);
        
        $levels = array();
        if (mysqli_num_rows($result) > 0){
            
            while ($row = mysqli_fetch_assoc($result)) {
                 $level = new Level();
                 $level->id = $row['id'];
                 $level->name = $row['name'];
                 $levels[] = $level; 
            }
            mysqli_close($connection);
            return $levels;
        }
        else{
            mysqli_close($connection);
            return null;
        }
            
    }
    
    
}


?>