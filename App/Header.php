<?php 
    session_start();
    if(!isset($_SESSION["pseudo"])) {
        $_SESSION["pseudo"] = "Guest";
    }
?>