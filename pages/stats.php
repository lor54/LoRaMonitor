<?php
    $actual_page = "stats";

    require_once("../include/database.php");
    include "../include/header.php";
    $gateways = array();
?>


  <body>
    <?php include "../include/nav.php"; ?>
   
    <!-- CONTAINER FOR CHART -->
    <div id="chart_div"></div>
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
        let maxDatas = 50;

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
      var isEditing = false;

      let map = L.map('map').setView([<?php echo $gateway["latitude"];?>, <?php echo $gateway["longitude"];?>], 16);

      let layer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
      map.addLayer(layer);

      
        
    </script>
  </body>
</html>