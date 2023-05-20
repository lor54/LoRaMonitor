<?php
    $actual_page = "dashboard";

    include "include/header.php";
?>

<body>
  
    <?php include "include/nav.php"; ?>
    
    <div class="container w-100">
      <div class="d-flex justify-content-center">
        <div id="loadingDiv" class="text-center">
          <div class="spinner-border text-primary" role="status"></div>
        </div>
      </div>
      <div id="gateways" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 my-auto mx-auto"></div>
    </div>

    <script>
      document.getElementsByName("search")[0].addEventListener('input', updateSearch);

      var $loading = $('#loadingDiv').hide();
      $(document)
      .ajaxStart(function () {
        $loading.show();
      })
      .ajaxStop(function () {
        $loading.hide();
      });

      searchGateway("");

      function updateSearch() {
        $('#gateways').children().fadeOut(500).promise().then(function() {
          $('#gateways').empty();
        }).done(() => {
          searchGateway(this.value);
        });
      }

      function searchGateway(gwName) {
        $.ajax({
            type: "POST",
            url: "/actions/searchGateways.php",
            headers: {'Content-Type': 'application/json'}, 
            data: JSON.stringify({name: gwName}),
            success: function(response) {
                if(response) {
                  response.forEach(gw => {
                    var card = `<div class="col">
                      <a href="pages/gateway.php?id=` + gw["id"] + `" style="color: inherit; text-decoration: inherit;">
                      <div class="card shadow-sm w-75 light mx-auto">

                        <svg class="bd-placeholder-img card-img-top" width="100%" height="125" xmlns="http://www.w3.org/2000/svg" role="img" preserveAspectRatio="xMidYMid slice" focusable="false">
                          <rect x="0" y="0" width="100%" height="100%" fill="#00A99D"/>
                          <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#eceeef" dy=".3em">` + gw["name"] + `</text>
                        </svg>

                        <div class="card-body">
                          <div class="d-flex justify-content-between">
                            <small class="text-body-secondary"><?php echo $language["GW-MANUFACTURER"]; ?>: ` + gw["manufacturer"] + `</small>            
                          </div>
                        </div>
                        </a>
                      </div>
                    </div>`;
                    
                    $(card).hide().appendTo("#gateways").fadeIn(500);
                  });
                }
            },
            error: function(data) {
              if(data) alert(data);
            }
        });
      }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   
</body>