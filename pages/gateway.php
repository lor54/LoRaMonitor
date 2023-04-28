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
    }
?>

<body>
  
    <?php include "../include/nav.php"; ?>
    
    <div class="album py-5 bg-body-tertiary">
    <div class="container">
    <div class="row row-no-gutters">
        <div class="col-sm-4">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Personal</h5>
                    <h6 class="card-text">Your Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="col-18 col-md-9">
                        <form>
                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label">GWUI</label>
                                <div class="col-md-12">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="birthdate" class="col-md-2 col-form-label">Birth Date</label>
                                <div class="col-md-10">
                                    <input type="date" class="form-control" id="birthdate" name="birthdate">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="age" class="col-md-2 col-form-label">Age</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="age" name="age" placeholder="Your age">
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label for="gender1" class="col-md-2 col-form-label">Gender</label>
                                <div class="col-md-10">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="gender1" name="gender" class="custom-control-input">
                                        <label class="custom-control-label" for="gender1">Male</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="gender2" name="gender" class="custom-control-input">
                                        <label class="custom-control-label" for="gender2">Female</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-md-2 col-form-label">Address</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" id="address" name="address" rows="4"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
    </div>  
</div>
        </div>
        <div class="col-sm-8">
        <div id="map" style="width: 600px; height: 400px;"></div>
        </div>
    </div>
    </div>
    <script>

const map = L.map('map').setView([51.505, -0.09], 13);

const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

const marker = L.marker([51.5, -0.09]).addTo(map)
    .bindPopup('<b>Hello world!</b><br />I am a popup.');

map.on('click', onMapClick);

map.invalidateSize();

</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   
</body>