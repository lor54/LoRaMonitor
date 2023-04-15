<?php
session_start();
if(isset($SESSION["mail"])){
    echo '<h3>Login succes, benvenuto -'.$_SESSION["mail"].'</h3>';
    echo '<br><br><a href="index.php">Logout</a>';
}else{
    header("location:index.php");
}
?>