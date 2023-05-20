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
                            <h5 id="cardtitlename" class="card-title"><?php echo $gateway["name"]; ?></h5>
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
                        <form action="/actions/editGateway.php" id="gweditform">
                            <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $gateway["id"]; ?>">
                            
                            <div class="form-floating">
                                <input type="text" class="form-control" id="gwui" name="gwui" placeholder="GWUI" value="<?php echo $gateway["gwui"]; ?>" disabled>
                                <label for="gwui">GWUI</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo $gateway["name"]; ?>" disabled>
                                <label for="name">Name</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Manufacturer" value="<?php echo $gateway["manufacturer"]; ?>" disabled>
                                <label for="manufacturer">Manufacturer</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                                <input type="coords" class="form-control" id="latitude" name="latitude" placeholder="Latitude" value="<?php echo $gateway["latitude"]; ?>" disabled readonly>
                                <label for="latitude">Latitude</label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                                <input type="coords" class="form-control" id="longitude" name="longitude" placeholder="Longitude" value="<?php echo $gateway["longitude"]; ?>" disabled readonly>
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
            <div class="mb-4 mb-md-0"></div>
            <div class="container">
                <div class="h-100 d-flex align-items-center justify-content-center">
                    <div id="map" style="width: 600px; height: 400px;"></div>
                </div>
            </div>
        </div>
        <div class="mb-4 mb-md-4"></div>
        <div id="packet_second_chart"></div>
    </div>
    </div>
    <script
      type="text/javascript"
      src="https://www.gstatic.com/charts/loader.js"
    ></script>
    <script>

    google.charts.load('current', {
        packages: ['corechart', 'line'],
    });

    google.charts.setOnLoadCallback(drawChart);

    var isEditing = false;

    let map = L.map('map').setView([<?php echo $gateway["latitude"];?>, <?php echo $gateway["longitude"];?>], 16);

    let layer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
    map.addLayer(layer);

    let marker = L.marker([<?php echo $gateway["latitude"];?>, <?php echo $gateway["longitude"];?>]).addTo(map)
        .bindPopup('<b><?php echo $gateway["name"];?></b>').openPopup();

    map.on('click', (event)=> {
        if(isEditing) {
            if(marker !== null){
                map.removeLayer(marker);
            }

            marker = L.marker([event.latlng.lat , event.latlng.lng]).addTo(map);
            document.getElementById('latitude').value = event.latlng.lat;
            document.getElementById('longitude').value = event.latlng.lng;
        }
    });

    function drawChart() {
        let data = google.visualization.arrayToDataTable([
            ['Time', 'Packets per second'],
            [new Date(), 0],
        ]);

        let options = {
            "lineWidth": 2,
            "pointSize": 2,
            title: 'Packets per second on the gateway',
            hAxis: {
                textPosition: "none"
            },
            vAxis: {
                title: 'Packets',
                viewWindow:{ min: 0, max: 5 }
            }
        };

        let chart = new google.visualization.LineChart(
            document.getElementById('packet_second_chart')
        );
        chart.draw(data, options);

        let maxDatas = 10;
        let index = 0;
        setInterval(async function () {
            const req = await fetch("/actions/getGatewayPacketSecond.php?id=<?php echo $gateway["id"]?>");
            const res = await req.json();
            const packetsCount = res.packetsCount;

            if (data.getNumberOfRows() > maxDatas) {
            data.removeRows(0, data.getNumberOfRows() - maxDatas);
            }

            data.addRow([new Date(), packetsCount]);
            chart.draw(data, options);

            index++;
        }, 1000);
    }
    
    function editGateway() {        
        isEditing = true;

        var form = document.getElementById("gweditform");
        var elements = form.elements;
        for (var i = 2, len = elements.length; i < len; ++i) {
            elements[i].disabled = false;
        }

        document.getElementById("editButton").style.display = 'none';
        document.getElementById("editButtonSubmit").style.display = 'block';
    }

    function closeEditGateway() {        
        isEditing = false;

        var form = document.getElementById("gweditform");
        var elements = form.elements;
        for (var i = 2, len = elements.length; i < len; ++i) {
            elements[i].disabled = true;
        }

        document.getElementById("editButton").style.display = 'block';
        document.getElementById("editButtonSubmit").style.display = 'none';

        if(marker !== null){
            map.removeLayer(marker);
        }

        document.getElementById('cardtitlename').textContent = document.getElementById('name').value;

        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;
        marker = L.marker([latitude, longitude]).addTo(map).bindPopup('<b>' + document.getElementById('name').value + '</b>').openPopup();
    }

    $("#gweditform").submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var actionUrl = form.attr('action');

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(),
            success: function(data) {
                if(data) alert(data);
                else {
                    closeEditGateway();                    
                    $.snack("success", "Gateway edited succesfully!", 3000);
                }
            },
            error: function(data) {
                if(data) alert(data);
            }
        });
    });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   
</body>