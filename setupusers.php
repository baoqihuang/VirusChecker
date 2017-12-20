<?php // setupusers.php
    require_once 'login.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);
    //create the user table query
    $query = "CREATE TABLE IF NOT EXISTS users(firstname VARCHAR(32) NOT NULL, lastname VARCHAR(32) NOT NULL, username VARCHAR(32) NOT NULL UNIQUE, password VARCHAR(32) NOT NULL)";
    //check if successfully connected
    $result = $conn->query($query);
    if(!$result) die($conn->error);
    describe_users($conn);
    
    //two salts
    $salt1 = "*&g!";
    $salt2 = "hb%$";
    
    $firstname = 'Michael';
    $lastname = 'Huang';
    $username = 'xilin';
    $password = 'huangbaoqi';
    $token = hash('ripemd128', "$salt1$password$salt2");
    add_user($conn, $firstname, $lastname, $username, $token);
    
    $firstname = 'Heilam';
    $lastname = 'Wu';
    $username = 'junyi';
    $password = 'huangyanghai';
    $token = hash('ripemd128', "$salt1$password$salt2");
    add_user($conn, $firstname, $lastname, $username, $token);
    
    //add user
    function add_user($connection, $firstname, $lastname, $username, $password)
    {
        $query = "INSERT INTO users VALUES('$firstname', '$lastname','$username', '$password')";
        $result = $connection->query($query);
        if (!$result) die($connection->error);
    }
    
    //descrie users for debugging
    function describe_users($connection)
    {
        $query = "SELECT * FROM users";
        $result = $connection->query($query);
        $rows = $result->num_rows;
        for($j = 0; $j < $rows; ++$j)
        {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_NUM);
            
            echo <<<_END
            <pre>
            firstname $row[0]
            lastname $row[1]
            username $row[2]
            password $row[3]
            </pre>
_END;
        }
       
    }
    
    
    
?>
