<?php
  require_once("include/database.php");
  session_start();

  try {
    if(isset($_POST["login"])){
      print_r($_POST);
      if(empty($_POST["email"]) || empty($_POST["password"])){
        $message = '<label>Campi non completi</label>';
      }
      else{
        $query = "SELECT * FROM users WHERE email = :email AND password = :password";
        $statement = $connect->prepare($query);
        $statement->execute(
            array('email' => $_POST["email"], 'password' => $_POST["password"]));
            $count = $statement->rowCount();
            if($count > 0) {
              $_SESSION["email"] = $_POST["email"];
              header("location:login_success.php");
            } else {
              $message = '<label>Mail o Password errate</label>';
            }
      }
    }
  } catch(PDOExecption $error) {
    $message = $error->getMessage();
  }
?>

<!doctype html>
<html lang="it" >

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login test 1</title>
  <link rel="stylesheet" href="./style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
<div class="login-page">
  <div class="form rounded">
     

    <?php
      if(isset($message)){
        echo '<label class="text-danger">'.$message.'</label>';
      }
    ?>
    

    <form method="post" id="signup" name="signup" class="register-form">
      <div class="form-floating">
        <input type="text" class="form-control" id="name" placeholder="Nome">
        <label for="name">Nome</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="surname" placeholder="Cognome">
        <label for="name">Cognome</label>
      </div>
      <div class="form-floating">
        <input type="email" class="form-control" id="email" placeholder="name@example.com">
        <label for="email">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="password" placeholder="name@example.com">
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
      <button type="button" class="btn btn-primary btn-lg">signup</button>
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
