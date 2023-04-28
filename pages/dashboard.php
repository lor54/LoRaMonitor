<?php
    $actual_page = "dashboard";
    
    require_once("include/database.php");
    include "include/header.php";

    $gateways = array();

    try {
        $query = "SELECT * FROM gateways WHERE userid = :uid";
        $statement = $connect->prepare($query);
        $statement->execute(array('uid' => $_SESSION["uid"]));
        $count = $statement->rowCount();
        if($count > 0) {
          $gateways = $statement->fetchAll();
        }
    } catch(PDOException $error) {
        $message = $error->getMessage();
        print_r($message);
    }

?>

<body>
  
    <?php include "include/nav.php"; ?>
    
    <div class="album py-5 bg-body-tertiary">
    <div class="container">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php
        foreach($gateways as $gateway) {
          echo
          '<div class="col">
            <div class="card shadow-sm w-75">
              <svg class="bd-placeholder-img card-img-top" width="100%" height="125" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#ad1717"/><text x="40%" y="50%" fill="#eceeef" dy=".3em">' . $gateway["name"] . '</text></svg>
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">
                    <small class="text-body-secondary">Manufacturer: ' . $gateway["manufacturer"] . '</small>
                  </div>
                  <a href="pages/gateway.php?id=' . $gateway["id"] . '">
                    <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                  </a>
                </div>
              </div>
            </div>
          </div>';
        }
        ?>
      </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   
</body>