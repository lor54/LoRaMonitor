<?php
    $actual_page = "profile";
    require_once("../include/database.php");

    include "../include/header.php";

    $users = array();

   
        try {
            $query = "SELECT * FROM users WHERE id = :uid";
            $statement = $connect->prepare($query);
            $statement->execute(array('uid' => $_SESSION["uid"]));
            $count = $statement->rowCount();
            if($count > 0) {
                $users = $statement->fetchAll();
            }
        } catch(PDOException $error) {
            $message = $error->getMessage();
            print_r($message);
        }


    $user = "";
    if(sizeof($users) > 0)
        $user = $users[0];
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
                            <h5 id="cardtitlename" class="card-title"><?php echo $language["PR-PROFILE"]; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-9 mx-auto text-center">
                        <form method="post" action="/actions/editUser.php" id="usereditform">
                            <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $user["id"]; ?>">
                        
                            <div class="form-floating">
                                <input type="email" class="form-control" name=email id="email" placeholder="name@example.com" value="<?php echo $user["email"]; ?>">
                                <label for="email"><?php echo $language["PR-EMAIL"]; ?></label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo $user["name"]; ?>">
                                <label for="name"><?php echo $language["GW-NAME"]; ?></label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            <div class="form-floating">
                                <input type="text" class="form-control" name=surname id="surname" placeholder="Cognome" value="<?php echo $user["surname"]; ?>">
                                <label for="name"><?php echo $language["PR-SURNAME"]; ?></label>
                            </div>

                            <label class="col-md-1 col-form-label"></label>

                            
                            <div class="d-grid gap-2 form-group">
                                <button id="editButtonSubmit" type="submit" class="btn btn-success"><?php echo $language["ED-BUTTON"]; ?></button>
                            </div>
                        </form>
                    </div>
                </div>  
            </div>
        </div>
    </div>
    <script>
        $("#usereditform").submit(function(e) {
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
                        $.snack("success", "<?php echo $language["EDIT-SUCCESS"]; ?>", 3000);
                    }
                },
                error: function(data) {
                    if(data) alert(data);
                }
            });
        });
    </script>
    <?php include "../include/footer.php"; ?>
</body>