<?php
    session_start();
    if(!isset($_SESSION["email"])) {
        header("location:/auth.php");
    }

    include "include/header.php";

    echo '<h3>Login succes, benvenuto -' . $_SESSION["email"] . '</h3>';
    echo '<br><br><a href="actions/logout.php">Logout</a>';
?>

<body>
    <?php include "include/nav.php"; ?>
</body>