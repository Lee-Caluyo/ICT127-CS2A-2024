<?php
    // Start the session
    session_start();

    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();

    // Redirect to login page
    if(!isset($_SESSION["username"])){
        header("location: login.php");
        exit;
    }
?>