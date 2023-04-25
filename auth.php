<?php
  require_once("include/database.php");
  session_start();

  try {
    if(isset($_POST["login"])){
      if(empty($_POST["email"]) || empty($_POST["password"])){
        $message = '<label>Campi non completi</label>';
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
              $message = '<label>Mail o Password errate</label>';
            }
      }
    }
  } catch(PDOExecption $error) {
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
   
          if(filter_var($email, FILTER_VALIDATE_EMAIL))
          {
            try{
              $query = 'select * from users where email = :email';
              $statement = $connect->prepare($query);
              $p = ['email'=>$email];
              $statement->execute($p);
            }
            catch(PDOException $e){
                $errors[] = $e->getMessage();
            }
              
              if($statement->rowCount() == 0)
              {
                  $query = "insert into users (name, surname, email, password, ruolo) values (:name,:surname,:email,:password, :ruolo)";
              
                  try{
                      $statement = $connect->prepare($query);
                      $params = [
                          ':name'=>$name,
                          ':surname'=>$surname,
                          ':email'=>$email,
                          ':password'=>$hashPassword,
                          ':ruolo'=>"studente"
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
?>

<!doctype html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LoRaMonitor</title>
  <link rel="stylesheet" href="./style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

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
    

    <form method="post" id="signup" name="signup" class="register-form">
      <div class="form-floating">
        <input type="text" class="form-control" name=name id="name" placeholder="Nome">
        <label for="name">Nome</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" name=surname id="surname" placeholder="Cognome">
        <label for="name">Cognome</label>
      </div>
      <div class="form-floating">
        <input type="email" class="form-control" name=email id="email" placeholder="name@example.com">
        <label for="email">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" name=password id="password" placeholder="name@example.com">
        <label for="password">Password</label>
      </div>

      <label for="ruolo"></label>
      <div class="form-floating">
        <select class="form-select" name="role" id="role" aria-label="Role">
          <option value="" disabled selected>Seleziona un ruolo</option>
          <option value="studente">Studente</option>
          <option value="ricercatore">Ricercatore</option>
          <option value="professore">Professore</option>
          <option value="altro">Altro</option>
        </select>
        <label for="role">Ruolo</label>
      </div>
      <button type="submit" class="btn btn-primary btn-lg" name="signup">signup</button>
      <p class="message">Sei già registrato? <a href="#">Accedi</a></p>
    </form>

    <form method="post" id="login" name="login" class="login-form">
      <div class="form-floating">
        <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
        <label for="email">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" name="password" id="password" placeholder="name@example.com">
        <label for="password">Password</label>
      </div>
      <div>
        <button type="submit" class="btn btn-primary btn-lg" name=login>login</button>
      </div>
      <p class="message">Non ancora registrato? <a href="#">Crea un account</a></p>
    </form>
  </div>
</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script><script  src="./script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</body>

</html>