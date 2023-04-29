<?php
    $actual_page = "antennareg";

    require_once("../include/database.php");
    include "../include/header.php";
    $gateways = array();


  if(isset($_POST['signup']))
  {
    if(isset($_POST['gwui'],$_POST['name'],$_POST['id'],$_POST['manufacturer']) && !empty($_POST['gwui']) && !empty($_POST['name']) && !empty($_POST['gwui']) && !empty($_POST['manufacturer']))
    {
        $gwui = trim($_POST['gwui']);
        $name = trim($_POST['name']);
        $id = trim($_POST['id']);
        $manufacturer = trim($_POST['manufacturer']);
        $userid = trim($_POST['userid']);
  
        if(filter_var($id, FILTER_VALIDATE_ID)) {
          try {
            $query = 'select * from users where id = :id';
            $statement = $connect->prepare($query);
            $p = ['id'=>$id];
            $statement->execute($p);
          } catch(PDOException $e) {
              $errors[] = $e->getMessage();
          }
            
            if($statement->rowCount() == 0)
            {
                $query = "insert into users (gwui, name, id, manufacturer, userid) values (:gwui,:name,:id,:manufacturer, :userid)";
            
                try{
                    $statement = $connect->prepare($query);
                    $params = [
                        ':gwui'=>$gwui,
                        ':name'=>$name,
                        ':id'=>$id,
                        ':manufacturer'=>$manufacturer,
                        ':userid'=>$userid
                    ];
                    
                    $statement->execute($params);
                    $message = '<label>Antenna has been added successfully</label>';                      
                } catch(PDOException $e){
                    $errors[] = $e->getMessage();
                }
            }
            else
            {
                $valgwui = $gwui;
                $valname = $name;
                $valid = '';
                $valmanufacturer = $manufacturer;
  
                $errors[] = 'Id address already registered';
            }
        }
        else
        {
            $errors[] = "Id address is not valid";
        }
    }
    else
    {
        if(!isset($_POST['gwui']) || empty($_POST['gwui']))
        {
            $errors[] = 'First gwui is required';
        }
        else
        {
            $valgwui = $_POST['gwui'];
        }
        if(!isset($_POST['name']) || empty($_POST['name']))
        {
            $errors[] = 'Last gwui is required';
        }
        else
        {
            $valname = $_POST['name'];
        }
  
        if(!isset($_POST['id']) || empty($_POST['id']))
        {
            $errors[] = 'Id is required';
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
        <div class="col-sm-4 text-center">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Antenna</h5>
                    <h6 class="card-text">Your Antenna Information</h6>
                </div>
                <div class="card-body">
                    <div class="col-18">
                        <form>

                            <div class="form-floating">
                              <input type="text" class="form-control" name=name id="name" placeholder="Nome">
                              <label for="name">Nome</label>
                            </div>

                            <div class="form-floating">
                              <input type="text" class="form-control" name=gwui id="gwui" placeholder="GWUI">
                              <label for="name">GWUI</label>
                            </div>

                            <div class="form-floating">
                              <input type="text" class="form-control" name=manufacturer id="manufacturer" placeholder="Marca">
                              <label for="name">Marca</label>
                            </div>

                            <div class="form-floating">
                              <input type="text" class="form-control" name=name id="latitude" placeholder="latitude">
                              <label for="name">Latitudine</label>
                            </div>
                            <div class="form-floating">
                              <input type="text" class="form-control" name=name id="longitude" placeholder="longitude">
                              <label for="name">Longitudine</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg" gwui="signup">Aggiungi antenna +</button>
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