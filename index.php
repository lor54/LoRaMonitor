<?php
    session_start();
    if(!isset($_SESSION["email"])) {
      header("location:auth.php");
    }

    include "pages/dashboard.php";
?>