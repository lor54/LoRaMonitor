<?php
    require_once("../include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        http_response_code(403);
    }

        try {
            $query = "SELECT * FROM users WHERE id = :uid";
            $statement = $connect->prepare($query);
            $statement->execute(array('uid' => $_SESSION["uid"]));
            $count = $statement->rowCount();
            if($count > 0) {
                http_response_code(200);

                $query = "UPDATE users SET name=:name, email=:email, surname=:surname where id=:uid";
            
                try {
                    $statement = $connect->prepare($query);
                    $params = [
                        ':id' => $_POST["id"],
                        ':email' => $_POST["email"],
                        ':name' => $_POST["name"],
                        ':surname' => $_POST["surname"]
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
    
?>