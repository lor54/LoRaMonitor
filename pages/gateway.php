<?php
    $actual_page = "gateway";
    require_once("../include/database.php");

    include "../include/header.php";

    $gateways = array();

    if(isset($_GET["id"])) {
        try {
            $query = "SELECT * FROM gateways WHERE userid = :uid AND id = :id";
            $statement = $connect->prepare($query);
            $statement->execute(array('id' => $_GET["id"], 'uid' => $_SESSION["uid"]));
            $count = $statement->rowCount();
            if($count > 0) {
                $gateways = $statement->fetchAll();
            } else {
                header("location:/index.php");
            }
        } catch(PDOException $error) {
            $message = $error->getMessage();
            print_r($message);
        }
    } else {
        header("location:/index.php");
    }

    $gateway = "";
    if(sizeof($gateways) > 0)
        $gateway = $gateways[0];
?>

<body>
  
    <?php include "../include/nav.php"; ?>
    
    <div class="album py-5 bg-body-tertiary">
    <div class="container">
    <div class="row justify-content-evenly">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title"><?php echo $gateway["name"]; ?></h5>
                            <h6 class="card-text">Gateway Information</h6>
                        </div>
                        <div class="col">
                            <div class="float-end">
                                <button type="button" id="editButton" onclick="editGateway()" class="btn btn-success"><i class="bi-pencil-square"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-9 mx-auto text-center">
                        <form id="gweditform">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="gwui" name="gwui" placeholder="GWUI" value="<?php echo $gateway["gwui"]; ?>" disabled>
                                <label for="gwui">GWUI</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Manufacturer" value="<?php echo $gateway["manufacturer"]; ?>" disabled>
                                <label for="manufacturer">Manufacturer</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                                <input type="number" class="form-control" id="latitude" name="latitude" placeholder="Latitude" value="<?php echo $gateway["latitude"]; ?>" disabled>
                                <label for="latitude">Manufacturer</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                                <input type="number" class="form-control" id="longitude" name="longitude" placeholder="Longitude" value="<?php echo $gateway["longitude"]; ?>" disabled>
                                <label for="longitude">Longitude</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="d-grid gap-2 form-group">
                                <button id="editButtonSubmit" type="submit" class="btn btn-success" style="display:none">Edit</button>
                            </div>
                        </form>
                    </div>
    </div>  
</div>
        </div>
        <div class="col-md-6">
            <div id="map" style="width: 600px; height: 400px;"></div>
        </div>
    </div>
    </div>
    <script>

    const map = L.map('map').setView([<?php echo $gateway["latitude"];?>, <?php echo $gateway["longitude"];?>], 16);

    const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const marker = L.marker([<?php echo $gateway["latitude"];?>, <?php echo $gateway["longitude"];?>]).addTo(map)
        .bindPopup('<b><?php echo $gateway["name"];?></b>').openPopup();

    map.on('click', onMapClick);

    map.invalidateSize();

    function editGateway() {        
        var form = document.getElementById("gweditform");
        var elements = form.elements;
        for (var i = 1, len = elements.length; i < len; ++i) {
            elements[i].disabled = false;
        }

        document.getElementById("editButton").style.visibility = 'hidden';
        document.getElementById("editButtonSubmit").style.display = 'block';
    }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   
</body>