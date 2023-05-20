<?php
    require_once("../include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        http_response_code(403);
    }

    $data = json_decode(file_get_contents('php://input'), true);

    $result = array(); 

    if(isset($data["name"])) {
        try {
            $query = "SELECT * FROM gateways WHERE userid = :uid AND name LIKE CONCAT('%', :name, '%')";
            $statement = $connect->prepare($query);
            $statement->execute(array('uid' => $_SESSION["uid"], 'name' => $data["name"]));
            $count = $statement->rowCount();
            if($count > 0) {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                header('Content-type: application/json');
                echo json_encode($result);
            } else {
                http_response_code(404);
            }
            return;
        } catch(PDOException $error) {
            $message = $error->getMessage();
            print_r($message);
        }
    }

    
?>