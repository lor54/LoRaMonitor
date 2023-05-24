<?php
    require_once("../include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        http_response_code(403);
    }

    $res = new StdClass();
    $res->deleted = false;

    if(isset($_GET["id"])) {
        try {
            $query = "SELECT * FROM gateways WHERE userid = :uid AND id = :id";
            $statement = $connect->prepare($query);
            $statement->execute(array('id' => $_GET["id"], 'uid' => $_SESSION["uid"]));
            $count = $statement->rowCount();
            if($count > 0) {            
                try {
                    $query = "DELETE FROM gateways where id=:id";

                    $statement = $connect->prepare($query);
                    $params = [
                        ':id' => $_GET["id"]
                    ];
                    
                    $statement->execute($params);
                    $count = $statement->rowCount();

                    if($count > 0) {
                        $res->deleted = true;
                    }
        
                    http_response_code(200);
                } catch(PDOException $e) {
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

    header('Content-type: application/json');
    echo json_encode($res);
?>