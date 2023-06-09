<?php
  session_start();
  if(!isset($_SESSION["email"]) && $actual_page != "auth") {
    header("location:/auth.php");
  }

  require_once($_SERVER["DOCUMENT_ROOT"] . "/include/database.php");

  $cookie_name = "LoRaMonitorLanguage";
  $choosenLanguage = "en";

  if(!isset($_COOKIE[$cookie_name])) {
    setcookie($cookie_name, "en", time() + 60*60*24*30, "/");
    $choosenLanguage = "en";
  }

  $choosenLanguage = $_COOKIE[$cookie_name];
  require($_SERVER["DOCUMENT_ROOT"] . "/locals/". $choosenLanguage . ".php");

  $users = array();

  try {
    $query = "SELECT * FROM users WHERE id = :uid";
    $statement = $connect->prepare($query);
    $statement->execute(array('uid' => $_SESSION["uid"]));
    $count = $statement->rowCount();
    if($count > 0) {
      $users = $statement->fetchAll();
    }
  } catch(PDOException $error) {
    $message = $error->getMessage();
    print_r($message);
  }

  $user = "";
  if(sizeof($users) > 0)
      $user = $users[0];
?>

<!doctype html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>LoRaMonitor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/styles/style.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="/scripts/toast.js"></script>
  <link rel="stylesheet" href="/styles/toast.css">
  <script src="/scripts/language.js"></script>

  <?php if($actual_page == "gateway") echo '
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>';?>

  <?php if($actual_page == "antennareg") echo '
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>';?>

<?php if($actual_page == "stats") echo '
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>';?>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css"/>
</head>