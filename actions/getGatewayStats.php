<?php
    require_once("../include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        http_response_code(403);
    }

    $result = array(); 

    if(isset($_GET["id"]) && isset($_GET["limit"]) && ctype_digit($_GET['limit'])) {
        $limit = (int) $_GET["limit"];

        try {
            $query = "SELECT * FROM gateways WHERE userid = :uid AND id = :id";
            $statement = $connect->prepare($query);
            $statement->execute(array('uid' => $_SESSION["uid"], 'id' => $_GET["id"]));
            $count = $statement->rowCount();
            if($count > 0) {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $gwui = $result[0]["gwui"];
                $packets = getLastUpPackets($connect, $gwui, $limit);

                $result = [];

                foreach($packets as $packet) {
                    $statPacket = getStatPacketFromUpPacket($connect, $packet);

                    $savedTime = explode(" ", $statPacket["time"])[0] . ":" . explode(" ", $statPacket["time"])[1];
                    $dateInfo = DateTime::createFromFormat('Y-m-d:H:i:s', $savedTime);
                    print_r($dateInfo);

                    $now = time();
                    $date = new DateTimeImmutable();
                    print_r($date);

                    if(($dateInfo->format('Y-m-d') == $date->format('Y-m-d'))) {
                        if (round(($now - $savedTime) / 60,2) >= 10){
                            echo "Your account is unlocked";
                        } elseif (round(($now - $savedTime) / 60,2) < 10){
                            echo "Your account is locked";
                        }

                        array_push($result, $statPacket);
                    }
                }
            }

            http_response_code(200);
            header('Content-type: application/json');
            echo json_encode($result);

        } catch(PDOException $error) {
            $message = $error->getMessage();
            print_r($message);
        }
    }



    function getLastUpPackets($connect, $gwui, $num) {
        $query = "SELECT * FROM upPackets WHERE gwui = :gwui ORDER BY id DESC LIMIT " . $num;
        $statement = $connect->prepare($query);
        $statement->execute(array('gwui' => $gwui));
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