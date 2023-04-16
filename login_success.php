<?php
    session_start();
    if(isset($_SESSION["email"])){
        echo '<h3>Login succes, benvenuto -' . $_SESSION["email"] . '</h3>';
        echo '<br><br><a href="logout.php">Logout</a>';
    } else {
        header("location:index.php");
    }
?>