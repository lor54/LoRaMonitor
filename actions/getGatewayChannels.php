<?php
    require_once("../include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        http_response_code(403);
    }

    $result = array(); 

    if(isset($_GET["id"])) {
        try {
            $now = time();
            $date = new DateTimeImmutable();

            $time = $date->format('Y-m-d H:i:s') . ' GMT';

            $query = "SELECT * FROM gateways WHERE userid = :uid AND id = :id";
            $statement = $connect->prepare($query);
            $statement->execute(array('uid' => $_SESSION["uid"], 'id' => $_GET["id"]));
            $count = $statement->rowCount();

            if($count > 0) {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $gwui = $result[0]["gwui"];
                $upPackets = getLastUpPacketsFromTime($connect, $gwui, $time);
                                
                $res = new StdClass();
                foreach($upPackets as $packet) {
                    $freq = $packet["freq"];
                    if(property_exists($res, $freq)) {
                        $res->{$freq} += 1;
                    } else {
                        $res->{$freq} = 0;
                    }
                }
            }

            http_response_code(200);
            header('Content-type: application/json');
            echo json_encode($res);
        } catch(PDOException $error) {
            $message = $error->getMessage();
            print_r($message);
        }
    }

    function getLastUpPacketsFromTime($connect, $gwui, $time) {
        $query = "SELECT freq FROM upPackets WHERE gwui = :gwui";
        $statement = $connect->prepare($query);
        $statement->execute(array('gwui' => $gwui));
        $count = $statement->rowCount();   

        $result = [];
        if($count > 0) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

?>