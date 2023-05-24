<?php
    $actual_page = "profile";
    include "../include/header.php";
?>

<body>

    <?php include "../include/nav.php"; ?>
    <div class="album py-5 bg-body-tertiary">
    <div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h5 id="cardtitlename" class="card-title"><?php echo $language["ADD-IMAGE"]; ?></h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="col-md-7 mx-auto">
                        <form method="post" action="/actions/editUser.php" id="imageditform">
                            <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $user["id"]; ?>">
                        
                            <div class="ratio ratio-1x1 rounded-circle overflow-hidden">
                                <div class="profile-pic">
                                    <img src="<?php if($user["image"]) echo $user["image"]; else echo '/media/man.jpeg'; ?>" class="card-img-top img-cover" id="profileimg" name="profileimg">
                                    <input id="file" type="file" hidden/>
                                </div>
                            </div>
                            <label class="col-md-1 col-form-label"></label>
                        </form>                            
                            <div class="d-grid gap-2 form-group">
                                <button id="editImageButton" class="btn btn-success"><?php echo $language["ED-BUTTON"]; ?></button>
                            </div>
                    </div>
                </div>  
            </div>
            <div class="mb-4 mb-md-4"></div>
        </div>
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
                            <form method="post" action="/actions/editUser.php" id="usereditform" name="usereditform">
                                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $user["id"]; ?>">
                            
                                <div class="form-floating">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" value="<?php echo $user["email"]; ?>">
                                    <label for="email"><?php echo $language["PR-EMAIL"]; ?></label>
                                </div>

                                <label class="col-md-1 col-form-label"></label>

                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo $user["name"]; ?>">
                                    <label for="name"><?php echo $language["GW-NAME"]; ?></label>
                                </div>

                                <label class="col-md-1 col-form-label"></label>

                                <div class="form-floating">
                                    <input type="text" class="form-control" name="surname" id="surname" placeholder="Cognome" value="<?php echo $user["surname"]; ?>">
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
                <div class="mb-4 mb-md-4"></div>
        </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h5 id="cardtitlename" class="card-title"><?php echo $language["CHANGE-PASSWORD"]; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-9 mx-auto text-center">
                            <form method="post" action="/actions/editUser.php" id="passwordeditform" name="passwordeditform">
                                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $user["id"]; ?>">
                            
                                <div class="form-floating">
                                    <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="">
                                    <label for="oldpassword"><?php echo $language["OLD-PASS"]; ?></label>
                                </div>

                                <label class="col-md-1 col-form-label"></label>

                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password1" name="password1" placeholder="">
                                    <label for="password1"><?php echo $language["INS-PASSWORD"]; ?></label>
                                </div>

                                <label class="col-md-1 col-form-label"></label>

                                <div class="form-floating">
                                    <input type="password" class="form-control" name="password2" id="password2" placeholder="">
                                    <label for="password2"><?php echo $language["REINS-PASSWORD"]; ?></label>
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
                    if(data) {
                        $.snack("success", "<?php echo $language["EDIT-SUCCESS"]; ?>", 3000);
                    }
                },
                error: function(data) {
                    if(data) alert(data);
                }
            });
        });

        $("#passwordeditform").submit(function(e) {
            e.preventDefault();

            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: "POST",
                url: actionUrl,
                data: form.serialize(),
                success: function(data) {
                    console.log(data);
                    if(data) {
                        $.snack("success", "<?php echo $language["EDIT-SUCCESS"]; ?>", 3000);
                    }
                },
                error: function(data) {
                    if(data) {
                        $.snack("error", "<?php echo $language["WRONG-PASSWORD"]; ?>", 3000);
                    }
                }
            });
        });

        document.getElementById('editImageButton').onclick = function() {
            document.getElementById('file').click();
        };

        $('input[type=file]').change(function (e) {
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
            {
                var reader = new FileReader();

                reader.onload = async (e) => {
                    var base64 = e.target.result;
                    var newbase64 = await resizeBase64Img(base64, 115, 115);
                    $.ajax({
                        type: "POST",
                        url: "/actions/changeImage.php",
                        data: JSON.stringify({"image": newbase64}),
                        success: function(data) {
                            document.getElementById("profileimg").src = newbase64;
                            document.getElementById("profileimagenav").src = newbase64;
                            
                            $.snack("success", "<?php echo $language["EDIT-SUCCESS"]; ?>", 3000);
                        },
                        error: function(data) {
                            console.log(data.responseText);
                            if(data) alert(data);
                        }
                    });
                };

                reader.readAsDataURL(input.files[0]);
            }

            function resizeBase64Img(src, newX, newY) {
                return new Promise((res, rej) => {
                    const img = new Image();
                    img.src = src;
                    img.onload = () => {
                        const elem = document.createElement('canvas');
                        elem.width = newX;
                        elem.height = newY;
                        const ctx = elem.getContext('2d');
                        ctx.drawImage(img, 0, 0, newX, newY);
                        const data = ctx.canvas.toDataURL();
                        res(data);
                    }
                    img.onerror = error => rej(error);
                });
            }
        });
    </script>
    <?php include "../include/footer.php"; ?>
</body>