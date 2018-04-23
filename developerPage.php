<?php // developerPage.php
    session_start();
    
    require_once 'login.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);
    
    $query = "CREATE TABLE IF NOT EXISTS infactedfile(signature VARCHAR(32) NOT NULL UNIQUE, filename VARCHAR(32) NOT NULL UNIQUE, id SMALLINT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id))";
    $result = $conn->query("$query");
    if(!$result) die($conn->error);
    
    if(isset($_POST['logout']))
    {
        echo "This is for test";
        destroy_session_and_data();
    }
    $entername = "";
    if(isset($_POST['infected_file_upload']))
    {
        //echo "This is for test";
        $filename = mysql_entities_fix_string($conn, 'infected_file_name');
        
        if($filename == "")
        {
            echo "You have not upload a file!";
        }
        else
        {
        //sanitazing the file name
        //$name = preg_replace("/[^A-Za-z0-9.]/", "", $filename);
        $fh = fopen("$filename", "r") or die("Failed to open file");
       
        $signature = fread($fh, 20);
        //move_uploaded_file($_FILES['putative_filename']['tmp_name'], $name);
        fclose($fh);
        //echo "$filename.txt ";
        }
    }
    //validate the entered file name
    if(isset($_POST['file_name']))
    {
        $entername = mysql_entities_fix_string($conn, 'file_name');
    }
    $fail = validate_entername($entername);
    echo "<!DOCTYPE html>\n<html><head><title>Infacted File Upload</title>";
    
    if($fail == "")
    {
        echo "</head><body>Form data successfully validated:</body></html>";
        //insert into the user surely infected file information into the database
        $query = "INSERT INTO infactedfile VALUE('$signature', '$filename', NULL)";
        $result = $conn->query("$query");
        if(!$result) die($conn->error);
        
        exit;
    }
    echo <<<_END
    <style>
        .signup{
        border: 1px solid #999999;
        front: normal 14px helvetica; color: #444444;
    }
    body, html{
    height: 100%;
    margin: 0;
    }
    .bg{
        background-image: url("road-to-tomorrow.jpg");
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    
    #container{
    text-align: center;
    }
    </style>
    <script>
    function validate(form)
    {
        fail += validateEntername(form.file_name.value)
        
        if(fail == "") return true
            else {alert(fail); return false}
    }
    
    function validateUsername(field)
    {
        if(field == "")
        {
            return "No Username was entered.\n"
        }
        else if(/[^a-zA-Z0-9]/.test(field))
        {
            return "Only English letters (capitalized or not) and digits allowed in entered file name"
        }
        return "";
    }
    </script>
    <head>
    <body>
    <div class="bg">
    <table border="0" cellpadding="2" cellspacing="5">
    <th colspan="2" align="center">Update infected file</th>
    <form method = "post" action = "developerPage.php" onsubmit="return validate(this)">
        <tr><td>If you want to check a file:</td>
            <td><button type = "submit"><a href = "putative.php"> check </a></button></td></tr>
        <tr><td>Choose the infacted file to upload:</td>
            <td><input type= "file" name= "infected_file_name" /></td></tr>
        <tr><td>infacted file name: <input type = "text" name = "file_name"  value = ""></td></tr>
        <tr><td  colspan="2" align="center"><input type= "submit" name = "infected_file_upload" value= "Upload" />
        <tr><td>Log Out:</td>
            <td><button type = "submit" name = "logout"><a href = "HomePage.php"> logout </a></button></td></tr>
    </form>
    </div>

    <body>
_END;
    
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
    
    //descrie users for debugging
    function describe_infectedfiles($connection)
    {
        $query = "SELECT * FROM infactedfile";
        $result = $connection->query($query);
        if(!$result) die($connection->error);
        $rows = $result->num_rows;
        for($j = 0; $j < $rows; ++$j)
        {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_NUM);
            
            echo <<<_END
            <pre>
            signature $row[0]
            filename $row[1]
            id $row[2]
 
            </pre>
_END;
        }
    }
    //validate the file name
    function validate_entername($field)
    {
        if($field == "")
        {
            return "No Username was entered<br>";
        }
        else if(preg_match("/[^a-zA-Z0-9]/", $field))
        {
            return "Only English letters (capitalized or not) and digits allowed in entered file name";
        }
        return "";
    }
    
    //destroy the session
    function destroy_session_and_data()
    {
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }
    
?>
