<?php // putative.php
    require_once 'login.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);
    //form
    echo<<<_END
    <!DOCTYPE html>
    <html>
    <head>
    <style>
    body, html{
    height: 100%;
    margin: 0;
    }
    .bg{
        background-image: url("check.jpg");
    height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    #container{
    text-align: center;
    }
    <title>Check Page</title>
    </style>
    </head>
    <body>
    <div class="bg">
    <form method= "post" action= "putative.php">
    <div id="container">
    Select File: <input type= "file" name= "putative_filename" />
    <input type= "submit" name = "normal_upload" value= "Upload" />
    <button type = "submit"><a href = "HomePage.php"> Click here to back HomePage:
    </a></button>
    </div>
    </form>
    </div>
    </body>
_END;
    
    //this is for normal user
    if(isset($_POST['normal_upload']))
    {
        $filename = mysql_entities_fix_string($conn, 'putative_filename');
        //sanitazing the file name
        //$name = preg_replace("/[^A-Za-z0-9.]/", "", $name);
        //echo "$filename";
        $fh = fopen("$filename", "r") or die("Failed to open file");
        
        $line = fgets($fh, 10000);
        //echo $line. '<br>';
        $query = "SELECT  signature FROM infactedfile";
        $result = $conn->query($query);
        if(!$result) die($conn->error);
        $rows = $result->num_rows;
        //echo "$rows";
        $j = 1;
        $found = FALSE;
        while($j <= $rows AND !$found)
        {
            $result->data_seek($j - 1);
            //echo "$j". '<br>';
            $row = $result->fetch_array(MYSQLI_NUM);
            //echo "$row[0]". '<br>';
            $found = strpos($line, $row[0]);
            $j ++;
        }
        if($found === FALSE)
        {
            echo<<<_END
            <div id="container">
            <a>Congraduation! The file is clear</a>
            </div>
_END;
        }
        else
        {
            echo "The file has been infected.";
        }
        //echo "filesize". filesize($fh);
        //$pos = strops($, filesize($fh));
        //echo "$filecontent";
        fclose($fh);
    }
    
    
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
        return htmlentities(mysql_fix_string($connection, $_POST[$string]));
    }
    
    //sanitazing a string
    function entities_fix_string($connection, $string)
    {
        return htmlentities(mysql_fix_string($connection, $string));
    }
    
?>
