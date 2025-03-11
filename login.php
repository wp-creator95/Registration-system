<?php  
   session_start();

   $db = mysqli_connect("localhost", "root", "", "vezba");

   if (!$db) {
       die("Greška prilikom konekcije na bazu: " . mysqli_connect_error());
   }

   if (isset($_POST['login'])) {
       $email = mysqli_real_escape_string($db, $_POST['email']);
       $password = $_POST['password'];

       // Provera da li korisnik postoji u bazi
       $query = "SELECT * FROM users WHERE Email = '$email'";
       $result = mysqli_query($db, $query);

       if ($result && mysqli_num_rows($result) > 0) {
           $user = mysqli_fetch_assoc($result);
           $hashed_password = $user['Lozinka']; // Lozinka iz baze

           // Provera lozinke
           if (password_verify($password, $hashed_password)) {
               $_SESSION['user_id'] = $user['id'];
               $_SESSION['name'] = $user['Ime'];
               $_SESSION['lastName'] = $user['Prezime'];
               $_SESSION['email'] = $user['Email'];

               // Preusmeravanje na početnu stranicu
               header("Location: profil.php");
               exit();
           } else {
               echo "<script>alert('Pogrešna lozinka!');</script>";
           }
       } else {
           echo "<script>alert('Ne postoji korisnik sa tim email-om!');</script>";
       }
   }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

 
    
        <form class="login" method="POST" action="">

            <input class="log1" type="email" name="email" placeholder="Unesite vas email">
            <input class="log2" type="password" name="password" placeholder="Unesite vas password">
            <input class="submit1" type="submit" name="login" value="Login">

            <a href="resetpassword.php" class="pass">Forgot password</a>

        </form>

</body>
</html>