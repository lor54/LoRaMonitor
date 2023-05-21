<?php
    $cookie_name = "LoRaMonitorLanguage";

    $lang = $_GET["lang"];
    if(isset($lang)) {
        if($lang == "it") {
            setcookie($cookie_name, "it", time() + 60*60*24*30, "/");
        }

        if($lang == "en") {
            setcookie($cookie_name, "en", time() + 60*60*24*30, "/");
        }
    }