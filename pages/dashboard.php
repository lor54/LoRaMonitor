<?php
    require_once("include/database.php");

    session_start();
    if(!isset($_SESSION["email"])) {
        header("location:/auth.php");
    }

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

    include "include/header.php";
?>

<body>
  
    <?php include "include/nav.php"; ?>
    
    <div class="album py-5 bg-body-tertiary">
    <div class="container">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php
        for($i = 0; $i < $count; $i++) {
          echo
          '<div class="col">
            <div class="card shadow-sm">
              <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#ad1717"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
              <div class="card-body">
                <p class="card-text text-center">GW sul muro</p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">
                    <small class="text-body-secondary">Marca Sconosciuta</small>
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
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