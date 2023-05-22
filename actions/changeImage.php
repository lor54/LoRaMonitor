<?php
    require_once("../include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        http_response_code(403);
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if(isset($data["image"])) {
        $query = "UPDATE users SET image=:image where id=:id";
                
        try {
            $statement = $connect->prepare($query);
            $params = [
                ':id' => $_SESSION["uid"],
                ':image' => $data["image"]
            ];
            
            $statement->execute($params);
            http_response_code(200);
        } catch(PDOException $e){
            http_response_code(500);

            $message = $e->getMessage();
            print_r($message);
        }
    }

    
?>