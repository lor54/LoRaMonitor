<?php
    require_once("../include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        http_response_code(403);
    }

    if(isset($_POST["usereditform"])) {
        if(isset($_POST["id"]) && isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["surname"])) {
            try {
                $query = "SELECT * FROM users WHERE id = :uid";
                $statement = $connect->prepare($query);
                $statement->execute(array('uid' => $_SESSION["uid"]));
                $count = $statement->rowCount();
                if($count > 0) {
                    $query = "UPDATE users SET email=:email, name=:name, surname=:surname where id=:id";
                
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
        }
    }

    if(isset($_POST["passwordeditform"])) {
        if (isset($_POST["id"]) && isset($_POST["oldpassword"]) && isset($_POST["password1"]) && isset($_POST["password2"])) {
            print_r($_POST);
            $id = $_POST["id"];
            $oldpassword = $_POST["oldpassword"];
            $password1 = $_POST["password1"];
            $password2 = $_POST["password2"];
        
            $oldpasswordHash = hash('sha256', $oldpassword);
            
            $query = "SELECT * FROM users WHERE id = :id and password = :password";
            $statement = $connect->prepare($query);
            $statement->execute(array('id' => $id, 'password' => $oldpasswordHash));
            $count = $statement->rowCount();
            if($count > 0) {
                $user = $statement->fetch();

                if ($password1 == $password2) {
                    $hashedPassword = hash('sha256', $password1);
        
                    $query = "UPDATE users SET password = :password WHERE id = :id";
                    $statement = $connect->prepare($query);
                    $statement->execute(array('password' => $hashedPassword, 'id' => $id));
        
                    echo "La password è stata aggiornata con successo!";
                } else {
                    echo "Le nuove password non corrispondono!";
                }
            } else {
                echo "La vecchia password non è corretta!";
            }
        }
    }
?>



