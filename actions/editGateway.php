<?php
    require_once("../include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        http_response_code(403);
    }

    if(isset($_POST["id"])) {
        try {
            $query = "SELECT * FROM gateways WHERE userid = :uid AND id = :id";
            $statement = $connect->prepare($query);
            $statement->execute(array('id' => $_POST["id"], 'uid' => $_SESSION["uid"]));
            $count = $statement->rowCount();
            if($count > 0) {
                http_response_code(200);

                $query = "UPDATE gateways SET name=:name, manufacturer=:manufacturer, latitude=:latitude, longitude=:longitude where id=:id";
            
                try {
                    $statement = $connect->prepare($query);
                    $params = [
                        ':id' => $_POST["id"],
                        ':name' => $_POST["name"],
                        ':manufacturer' => $_POST["manufacturer"],
                        ':latitude' => $_POST["latitude"],
                        ':longitude' => $_POST["longitude"]
                    ];
                    
                    $statement->execute($params);
                    http_response_code(200);
                } catch(PDOException $e){
                    $message = $e->getMessage();
                    print_r($message);
                }

            } else {
                http_response_code(404);
            }
        } catch(PDOException $e) {
            $message = $e->getMessage();
            print_r($message);
        }
    } else {
        http_response_code(403);
    }
?>