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
                $downPackets = getLastDownPacketsFromTime($connect, $gwui, $time);
                
                $res = new StdClass();
                $res->packetsCount = count($downPackets) + count($upPackets);
            }

            http_response_code(200);
            header('Content-type: application/json');
            echo json_encode($res);
        } catch(PDOException $error) {
            $message = $error->getMessage();
            print_r($message);
        }
    } else {
        try {
            $now = time();
            $date = new DateTimeImmutable();

            $time = $date->format('Y-m-d H:i:s') . ' GMT';

            $query = "SELECT * FROM gateways WHERE userid = :uid";
            $statement = $connect->prepare($query);
            $statement->execute(array('uid' => $_SESSION["uid"]));
            $count = $statement->rowCount();

            if($count > 0) {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $res = new StdClass();
                $res->packetsCount = 0;
                for($i = 0; $i < $count; $i++) {
                    $gwui = $result[$i]["gwui"];

                    $upPackets = getLastUpPacketsFromTime($connect, $gwui, $time);
                    $downPackets = getLastDownPacketsFromTime($connect, $gwui, $time);

                    $res->packetsCount += count($downPackets) + count($upPackets);
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
        $query = "SELECT * FROM upPackets WHERE gwui = :gwui AND time = :time ORDER BY id DESC";
        $statement = $connect->prepare($query);
        $statement->execute(array('gwui' => $gwui, 'time' => $time));
        $count = $statement->rowCount();   

        $result = [];
        if($count > 0) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    function getLastDownPacketsFromTime($connect, $gwui, $time) {
        $query = "SELECT * FROM downPackets WHERE gwui = :gwui AND time = :time ORDER BY id DESC";
        $statement = $connect->prepare($query);
        $statement->execute(array('gwui' => $gwui, 'time' => $time));
        $count = $statement->rowCount();   

        $result = [];
        if($count > 0) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    function getStatPacketFromUpPacket($connect, $upPacket) {
        $query = "SELECT * FROM statPackets WHERE id = :statid ORDER BY id DESC LIMIT 10";
        $statement = $connect->prepare($query);
        $statement->execute(array('statid' => $upPacket["statId"]));
        $count = $statement->rowCount();

        $result = [];

        if($count > 0) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result[0];
    }

?>