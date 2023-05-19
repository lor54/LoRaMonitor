<?php
    $actual_page = "stats";

    require_once("../include/database.php");
    include "../include/header.php";

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
      google.charts.load('current', {
        packages: ['corechart', 'line'],
      });

      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        let data = google.visualization.arrayToDataTable([
          ['Time', 'Packets per second'],
          [0, 0],
        ]);

        let options = {
          title: 'Packets per second',
          hAxis: {
            textPosition: 'none',
          },
          vAxis: {
            title: 'Usage',
          },
        };

        let chart = new google.visualization.LineChart(
          document.getElementById('chart_div')
        );
        chart.draw(data, options);

        let maxDatas = 1000;
        let index = 0;
        setInterval(async function () {
          const req = await fetch("/actions/getGatewayPacketSecond.php?id=1");
          const res = await req.json();
          const packetsCount = res.packetsCount;

          if (data.getNumberOfRows() > maxDatas) {
            data.removeRows(0, data.getNumberOfRows() - maxDatas);
          }

          data.addRow([index, packetsCount]);
          chart.draw(data, options);

          index++;
        }, 1000);
      }
      let mapOptions = {
      center:[41.8902, 12.4922], 
      zoom:10
     }

      let map = new L.map('map', mapOptions);
      let layer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
      map.addLayer(layer);

    let locations = [];
    
    fetch("/actions/getGateway.php")
    .then((response) => {
      return response.json();
    })
    .then((jsonDecodedResponse) => {
      locations = jsonDecodedResponse;

      let popupOption = {
        "closeButton": false
      };

      locations.forEach(element => {
        new L.Marker([element.latitude,element.longitude]).addTo(map)
        .on("mouseover", (event) => {
          event.target.bindPopup('<div class="text-center"><b>' + element.name + '</b></div>', popupOption).openPopup();
        })
        .on("mouseout", (event) => {
          event.target.closePopup();
        })
        .on("click" , () => {
          window.location = "/pages/gateway.php?id=" + element.id;
        })
      });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>