<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
      if(isset($_POST["submit"])){
        $username = $_POST["username"];
        $email =$_POST["email"];
        $phone = $_POST["phone"];
        $password =$_POST["password"];
        $repassword =$_POST["repassword"];

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $errors = array();
        
        if(empty($username) OR empty($email) OR empty($phone) OR empty($password) OR empty($repassword)){
          array_push($errors,"All fields are required");
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
          array_push($errors, "Email is not valid");
        }
        if(strlen($password)<8){
          array_push($errors, "Password muse be at least 8 charactes long");
        }
        if($password !== $repassword){
          array_push($errors, "Password does not match");
        }
        require_once "database.php";
        $sql ="SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn,$sql);
        $rowcount = mysqli_num_rows($result);
        if($rowcount){
          array_push($errors,"Email already exsist!");
        }

        if(count($errors)>0){
          foreach($errors as $error){
            echo "<div class='alert alert-danger'>$error</div>";
          }
        }else{
          
          $sql = "INSERT INTO users (username, email , phone ,password) VALUE (?,?,?,?)";
          $stmt = mysqli_stmt_init($conn);
          $preparestmt = mysqli_stmt_prepare($stmt,$sql); 
          if($preparestmt){
            mysqli_stmt_bind_param($stmt,"ssss",$username,$email,$phone,$hash);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>You are registered successfully</div>";
          }else{
            die("Someing went wrong");
          }
        }
      }
      ?>
    <form action="./registration.php"  method="post">
    <div class="form-group">
    <input type="text" class="form-control" name="username" placeholder="Username:"  minlength="8">
    </div>
  <div class="form-group">
    <input type="email" class="form-control" placeholder="Email:" name="email">
  </div>
  <div class="form-group">
    <input type="tel" class="form-control" placeholder="Phone:" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" required>
  </div>
  <div class="form-group">
    <input type="text" class="form-control"  placeholder="Password:" name="password">
  </div>
  <div class="form-group">
    <input type="text" class="form-control"  placeholder="Repeat Password:" name="repassword">
  </div>
  <div class="form-btn">
    <input type="submit" class="btn btn-primary"  value="Register" name=submit>
  </div>
  
</form>
        <div>
        <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
      </div>
    </div>
</body>
</html>