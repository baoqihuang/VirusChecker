<?php //HomePage.php
    //destroy_session_and_data();
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
            background-image: url("background.jpg");
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            }
        #button1{
            width: 300px;
            height: 40px;
        }
        #button2{
            width: 300px;
            height: 40px;
        }
        #container{
            text-align: center;
        }
        <title> Home Page</title>
        </style>
    </head>
        <body>
            <div class="bg">
            <form method = "post" action = "HomePage.php">
                <div id="container">
                <button type = "submit" id="button1"><a href = "putative.php"> check </a></button>
                <button type = "submit" id="button2"><a href = "authenticate.php"> login </a></button>
                </div>
            </form>
            </div>
        </body>
    </html>
_END;
    
    //destroy the session
    function destroy_session_and_data()
    {
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }
?>


