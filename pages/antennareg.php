<?php
    $actual_page = "antennareg";

    require_once("../include/database.php");
    include "../include/header.php";
    $gateways = array();


  if(isset($_POST['addantenna']))
  {
    if(isset($_POST['gwui'], $_POST['name'], $_POST['manufacturer'], $_POST['latitude'], $_POST['longitude']) && !empty($_POST['gwui']) && !empty($_POST['name']) && !empty($_POST['manufacturer']) && !empty($_POST['latitude']) && !empty($_POST['longitude']))
    {
      $gwui = trim($_POST['gwui']);
      $name = trim($_POST['name']);
      $manufacturer = trim($_POST['manufacturer']);
      $latitude = trim($_POST['latitude']);
      $longitude = trim($_POST['longitude']);
        if(ctype_xdigit($gwui)) {
            try {
              $query = 'select * from gateways where gwui = :gwui';
              $statement = $connect->prepare($query);
              $p = ['gwui'=> $gwui];
              $statement->execute($p);
            } catch(PDOException $e) {
              $errors[] = $e->getMessage();
            }

            if($statement->rowCount() == 0)
            {
                $query = "insert into gateways (gwui, name, manufacturer, latitude, longitude, userid) values (:gwui,:name,:manufacturer,:latitude,:longitude, :userid)";
            
                try {
                    $statement = $connect->prepare($query);
                    $params = [
                        ':gwui' => $gwui,
                        ':name' => $name,
                        ':manufacturer' => $manufacturer,
                        ':latitude' => $latitude,
                        ':longitude' => $longitude,
                        ':userid' => $_SESSION["uid"]
                    ];
                    
                    $statement->execute($params);

                    header("location:/");
                    $message = '<label>Antenna has been added successfully</label>';                      
                } catch(PDOException $e){
                    $errors[] = $e->getMessage();
                    echo $e->getMessage();
                }
            }
            else
            {
              $valgwui = '';
              $valname = $name;
              $valmanufacturer = $manufacturer;
              $vallatitude = $latitude;
              $vallongitude = $longitude;
  
              $errors[] = 'gwui address already registered';
            }
        }
        else
        {
          $errors[] = "gwui address is not valid";
        }
    }
    else
    {
        if(!isset($_POST['name']) || empty($_POST['name']))
        {
            $errors[] = 'Name is required';
        }
        else
        {
            $valname = $_POST['name'];
        }
        if(!isset($_POST['name']) || empty($_POST['name']))
        {
            $errors[] = 'Last name is required';
        }
        else
        {
            $valname = $_POST['name'];
        }
  
        if(!isset($_POST['id']) || empty($_POST['id']))
        {
            $errors[] = 'gwui is required';
        }
        else
        {
            $valid = $_POST['id'];
        }
  
        if(!isset($_POST['manufacturer']) || empty($_POST['manufacturer']))
        {
            $errors[] = 'Password is required';
        }
        else
        {
            $valmanufacturer = $_POST['manufacturer'];
        }
        
    }
}
?>

<!doctype html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta gwui="viewport" content="width=device-width, initial-scale=1">
  <title>LoRaMonitor</title>
  <link rel="stylesheet" href="./style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>


<body>
  
    <?php include "../include/nav.php"; ?>
    
    <div class="album py-5 bg-body-tertiary">
    <div class="container">
    <div class="row justify-content-evenly">
        <div class="col-sm-4">
          <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Gateway</h5>
                    <h6 class="card-text">Gateway Information</h6>
                </div>
                <div class="card-body">
                    <div class="col-md-9 mx-auto text-center">
                        <form method="post" id="addantenna">
                            <div class="form-floating">
                              <input type="text" class="form-control" name="name" id="name" placeholder="Nome">
                              <label for="name">Nome</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                              <input type="text" class="form-control" name="gwui" id="gwui" placeholder="GWUI">
                              <label for="name">GWUI</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                              <input type="text" class="form-control" name="manufacturer" id="manufacturer" placeholder="Marca">
                              <label for="name">Marca</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                              <input type="coords" class="form-control" name="latitude" id="latitude" placeholder="latitude">
                              <label for="name">Latitudine</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                              <input type="coords" class="form-control" name="longitude" id="longitude" placeholder="longitude">
                              <label for="name">Longitudine</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="d-grid gap-2 form-group">
                              <button type="submit" class="btn btn-success btn-lg" name="addantenna">Aggiungi antenna +</button>
                            </div>
                        </form>
                    </div>
                  </div>  
              </div>
            </div>
        <div class="col-md-6 text-center ">
          <div id="map" style="width: 700px; height: 500px;"></div>
        </div>
    </div>
  </div>

<script>

  let mapOptions = {
    center:[41.8902, 12.4922], 
    zoom:10
  }

  let map = new L.map('map', mapOptions);
  let layer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
  map.addLayer(layer);

  let marker = null;
  map.on('click', (event)=> {
    if(marker !== null){
        map.removeLayer(marker);
    }
    
  marker = L.marker([event.latlng.lat , event.latlng.lng]).addTo(map);
  document.getElementById('latitude').value = event.latlng.lat;
  document.getElementById('longitude').value = event.latlng.lng;

})

  

</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   
</body>