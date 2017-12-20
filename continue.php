<?php // continue.php
    session_start();
    if(isset($_SEESION['username']))
    {
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        $firstname = $_SESSION['firstname'];
        $lastname = $_SESSION['lastname'];
        //destroy_session_and_data();
        echo "Welcome back $firstname";
    }
    else echo "Please<a href='developerPage.php'> click here</a> continue.";
    
    //destroy the session
    function destroy_session_and_data()
    {
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }
?>
