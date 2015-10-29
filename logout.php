<?php
/*
 * Logout the user from the vehicle application. 
 * Return to the start page. 
 */

    session_start();
    session_destroy();
    header("Location: index.php");
?>