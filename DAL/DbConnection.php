<?php

class DbConnection
{
    private $servername = "127.0.0.1";
    private $username = "root";
    private $password = "vero";
    private $db= "school";
    
    public function createConnection()
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->db);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        return $conn;
    }
    
    public function closeConnection($connection)
    {
        $connection->close();
    }    
}

?>