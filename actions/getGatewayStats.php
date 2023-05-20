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
                $statPackets = getLastStatPacketsFromTime($connect, $gwui, $time);
                
                $res = new StdClass();
                $res->uplinkPacketsReceivedCount = 0;
                $res->packetsReceivedValidCRCCount = 0;
                $res->packetsForwardedCount = 0;
                $res->downlinkPacketsReceivedCount = 0;
                $res->emittedPacketsCount = 0;
                foreach($statPackets as $packet) {
                    $res->uplinkPacketsReceivedCount += $packet["rxnb"];
                    $res->packetsReceivedValidCRCCount += $packet["rxok"];
                    $res->packetsForwardedCount += $packet["rxfw"];
                    $res->downlinkPacketsReceivedCount +=  $packet["dwnb"];
                    $res->emittedPacketsCount += $packet["txnb"];
                };
            }

            http_response_code(200);
            header('Content-type: application/json');
            echo json_encode($res);
        } catch(PDOException $error) {
            $message = $error->getMessage();
            print_r($message);
        }
    }

    function getLastStatPacketsFromTime($connect, $gwui, $time) {
        $query = "SELECT * FROM statPackets WHERE gwui = :gwui AND time = :time ORDER BY id DESC";
        $statement = $connect->prepare($query);
        $statement->execute(array('gwui' => $gwui, 'time' => $time));
        $count = $statement->rowCount();   

        $result = [];
        if($count > 0) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

?>