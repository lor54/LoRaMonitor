<?php
    require_once("../include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        http_response_code(403);
    }

    $result = array(); 

    try {
        $query = "SELECT * FROM gateways WHERE userid = :uid";
        $statement = $connect->prepare($query);
        $statement->execute(array('uid' => $_SESSION["uid"]));
        $count = $statement->rowCount();
        if($count > 0) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        http_response_code(200);
        header('Content-type: application/json');
        echo json_encode($result);

    } catch(PDOException $error) {
        $message = $error->getMessage();
        print_r($message);
    }

?>