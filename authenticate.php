<?php // authenticate.php

    require_once 'login.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);
    
    if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
    {
        $un_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_USER']);
        $pw_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_PW']);
        //test for password
        //echo "pw_temp:". $pw_temp. '<br>';
        //echo "un_temp:". $un_temp.'<br>';
        $query = "SELECT * FROM users WHERE username='$un_temp'";
        $result = $conn->query($query);
        if (!$result)
            die($conn->error);
        else if($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            
            //two salts
            $salt1 = "*&g!";
            $salt2 = "hb%$";
            
            $token = hash('ripemd128', "$salt1$pw_temp$salt2");
            //echo "token:". $token. '<br>';
            //echo "row[3]:". $row[3]. '<br>';

            if($token == $row[3])
            {
                session_start();
                $_SESSION['username'] = $un_temp;
                $_SESSION['password'] = $pw_temp;
                $_SESSION['firstname'] = $row[0];
                $_SESSION['lastname'] = $row[1];
                echo "Hi! $row[0] $row[1], you are user now logged in as '$row[2]'";
                die("<p><a href=continue.php> Click here to continue</a></p>");
            }
            else
                die("Invalid username/password combination");
        }
        
        else
            die("Invalid username/password combination");

    }
    else
    {
        header('WWW-Authenticate: Basic realm="Restricted Section"');
        header('HTTP/1.0 401 Unauthorized');
        die("Please enter your username and password");
    }
    $conn->close();
    
    //sanitazing from MySQL
    function mysql_fix_string($connection, $string)
    {
        if(get_magic_quotes_gpc())
            $string = stripslashes($string);
        return $connection->real_escape_string($string);
    }
    
    //sanitazing from HTML
    function mysql_entities_fix_string($connection, $string)
    {
        return htmlentities(mysql_fix_string($connection, $string));
    }
    
    
    
?>
