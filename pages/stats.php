<?php
    $actual_page = "stats";

    require_once("../include/database.php");
    include "../include/header.php";
    
    $result = array(); 

      try {
        $query = "SELECT * FROM gateways WHERE userid = :uid";
        $statement = $connect->prepare($query);
        $statement->execute(array('uid' => $_SESSION["uid"]));
        $count = $statement->rowCount();
          if($count > 0) {
            $result = $statement->fetchAll();
          }
        } catch(PDOException $error) {
          $message = $error->getMessage();
          print_r($message);
      }

?>

<body>
    <?php include "../include/nav.php"; ?>
    
    <div class="container">
      <div class="h-100 d-flex align-items-center justify-content-center">
        <div id="map" style="width: 700px; height: 500px;"></div>
      </div>
    
    <!-- CONTAINER FOR CHART -->
      <div id="chart_div"></div>
    </div>
    <script
      type="text/javascript"
      src="https://www.gstatic.com/charts/loader.js"
    ></script>
    <script>
      // load current chart package
      google.charts.load('current', {
        packages: ['corechart', 'line'],
      });

      // set callback function when api loaded
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        // create data object with default value
        let data = google.visualization.arrayToDataTable([
          ['Time', 'CPU Usage', 'RAM'],
          [0, 0, 0],
        ]);

        // create options object with titles, colors, etc.
        let options = {
          title: 'CPU Usage',
          hAxis: {
            textPosition: 'none',
          },
          vAxis: {
            title: 'Usage',
          },
        };

        // draw chart on load
        let chart = new google.visualization.LineChart(
          document.getElementById('chart_div')
        );
        chart.draw(data, options);

        // max amount of data rows that should be displayed
        let maxDatas = 100;

        // interval for adding new data every 250ms
        let index = 0;
        setInterval(function () {
          // instead of this random, you can make an ajax call for the current cpu usage or what ever data you want to display
          let randomCPU = Math.random() * 20;
          let randomRAM = Math.random() * 50 + 20;

          if (data.getNumberOfRows() > maxDatas) {
            data.removeRows(0, data.getNumberOfRows() - maxDatas);
          }

          data.addRow([index, randomCPU, randomRAM]);
          chart.draw(data, options);

          index++;
        }, 100);
      }
      let mapOptions = {
      center:[41.8902, 12.4922], 
      zoom:10
     }

      let map = new L.map('map', mapOptions);
      let layer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
      map.addLayer(layer);

    <?php
      $count = 0;
      echo 'let locations = [';
      foreach($result as $row) {
        if($count++ > 0) echo ',';
        echo
        '{
          "id":' .$row["id"].','.
          '"lat":' .$row["latitude"].','.
          '"long":' .$row["longitude"].','.
          '"name":"' .$row["name"].'" 
        }';
      }
      echo '        ];';
    ?>

  let popupOption = {
    "closeButton": false
  };

  locations.forEach(element => {
    new L.Marker([element.lat,element.long]).addTo(map)
    .on("mouseover", (event) => {
        event.target.bindPopup('<div class="text-center"><b>' + element.name + '</b></div>', popupOption).openPopup();
    })
    .on("mouseout", (event) => {
        event.target.closePopup();
    })
    .on("click" , () => {
        window.open(element.url);
    })
});
      
        
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>