<?php
  $actual_page = "auth";

  require_once("include/database.php");
  session_start();
  $error = "";

  try {
    if(isset($_POST["login"])){
      if(empty($_POST["email"]) || empty($_POST["password"])){
        $error = "emptyField";
      } 
      else {
        $hashPassword = hash("sha256", $_POST["password"]);
        $query = "SELECT * FROM users WHERE email = :email AND password = :password";
        $statement = $connect->prepare($query);
        $statement->execute(
            array('email' => $_POST["email"], 'password' => $hashPassword));
            $count = $statement->rowCount();
            if($count > 0) {
              $user = $statement->fetch();
              $_SESSION["email"] = $_POST["email"];
              $_SESSION["uid"] = $user["id"];
              header("location:actions/login_success.php");
            } else {
              $message = '<label>Wrong Mail or Password</label>';
            }
      }
    }
  } catch(PDOException $error) {
    $message = $error->getMessage();
  }

  if(isset($_POST['signup']))
  {
      if(isset($_POST['name'],$_POST['surname'],$_POST['email'],$_POST['password']) && !empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['email']) && !empty($_POST['password']))
      {
          $name = trim($_POST['name']);
          $surname = trim($_POST['surname']);
          $email = trim($_POST['email']);
          $password = trim($_POST['password']);
          
          $hashPassword = hash("sha256", $_POST["password"]);
   
          if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
              $query = 'select * from users where email = :email';
              $statement = $connect->prepare($query);
              $p = ['email'=>$email];
              $statement->execute($p);
            } catch(PDOException $e) {
                $errors[] = $e->getMessage();
            }
              
              if($statement->rowCount() == 0)
              {
                  $query = "insert into users (name, surname, email, password) values (:name,:surname,:email,:password)";
              
                  try{
                      $statement = $connect->prepare($query);
                      $params = [
                          ':name'=>$name,
                          ':surname'=>$surname,
                          ':email'=>$email,
                          ':password'=>$hashPassword
                      ];
                      
                      $statement->execute($params);
                      $message = '<label>User has been created successfully</label>';                      
                  } catch(PDOException $e){
                      $errors[] = $e->getMessage();
                  }
              }
              else
              {
                  $valname = $name;
                  $valsurname = $surname;
                  $valemail = '';
                  $valpassword = $password;
   
                  $errors[] = 'Email address already registered';
              }
          }
          else
          {
              $errors[] = "Email address is not valid";
          }
      }
      else
      {
          if(!isset($_POST['name']) || empty($_POST['name']))
          {
              $errors[] = 'First name is required';
          }
          else
          {
              $valname = $_POST['name'];
          }
          if(!isset($_POST['surname']) || empty($_POST['surname']))
          {
              $errors[] = 'Last name is required';
          }
          else
          {
              $valsurname = $_POST['surname'];
          }
   
          if(!isset($_POST['email']) || empty($_POST['email']))
          {
              $errors[] = 'Email is required';
          }
          else
          {
              $valemail = $_POST['email'];
          }
   
          if(!isset($_POST['password']) || empty($_POST['password']))
          {
              $errors[] = 'Password is required';
          }
          else
          {
              $valpassword = $_POST['password'];
          }
          
      }
  }

  include "include/header.php";
?>

<body>
<div class="login-page">
  <div class="form rounded">
    <?php
      if(isset($message)){
        echo '<label class="text-success">'.$message.'</label>';
      }

      if(isset($errors) && count($errors) > 0)
			{
				foreach($errors as $error_msg)
				{
					echo '<div class="alert alert-danger">'.$error_msg.'</div>';
				}
      }
    ?>
    
    <div class="auth py-5 bg-body-tertiary">
    <div class="container">
    <div class="row justify-content-evenly">
        <div class="col-sm-4">
          <div class="card bg-light">
                <div class="card-header col-md-9 mx-auto text-center bg-light">
                    <img src="media/logo.svg" class="card-title"/>
                    <h6 class="card-text"></h6>
                </div>
                <div class="card-body">
                    <div class="col-md-9 mx-auto text-center">

                        <form method="post" id="signup" name="signup" class="register-form">
                          <div class="form-floating">
                            <input type="text" class="form-control" name=name id="name" placeholder="Nome">
                            <label for="name">Name</label>
                          </div>

                          <label class="col-md-1 col-form-label"></label>

                          <div class="form-floating">
                            <input type="text" class="form-control" name=surname id="surname" placeholder="Cognome">
                            <label for="name">Surname</label>
                          </div>

                          <label class="col-md-1 col-form-label"></label>

                          <div class="form-floating">
                            <input type="email" class="form-control" name=email id="email" placeholder="name@example.com">
                            <label for="email">Email address</label>
                          </div>

                          <label class="col-md-1 col-form-label"></label>

                          <div class="form-floating">
                            <input type="password" class="form-control" name=password id="password" placeholder="">
                            <label for="password">Password</label>
                          </div>

                          <label class="col-md-1 col-form-label"></label>


                          <label class="col-md-1 col-form-label"></label>

                          <div class="d-grid gap-2 form-group">
                            <button type="submit" class="btn btn-primary btn-lg" name="signup">Signup</button>
                          </div>

                          <p class="message">Are you alredy registered? <a href="#">Signup</a></p>
                        </form>

                        <form method="post" id="login" name="login" class="login-form">
                          <div class="form-floating">
                            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                            <label for="email">Email address</label>
                          </div>

                          <label class="col-md-1 col-form-label"></label>


                          <div class="form-floating">
                            <input type="password" class="form-control" name="password" id="password" placeholder="name@example.com">
                            <label for="password">Password</label>
                          </div>

                          <label class="col-md-1 col-form-label"></label>

                          <div class="d-grid gap-2 form-group">
                            <button type="submit" class="btn btn-primary btn-lg" name=login>Login</button>
                          </div>
                          <p class="message">Not registered? <a href="#">Create an account</a></p>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>  
            </div>
          </div>
          <?php if($error != "") echo '<script> $.snack("error", "code", 3000); </script>';?>
          <script  src="/styles/auth.js"></script>
          <?php include "include/footer.php"; ?>
</body>

</html>