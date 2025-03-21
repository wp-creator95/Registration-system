<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$db = mysqli_connect("localhost", "root", "", "vezba");

if (!$db) {
    die("Greška prilikom konektovanja na bazu: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 

    if (
        isset($_POST["name"]) && isset($_POST["lastName"]) &&
        isset($_POST["email"]) && isset($_POST["password"]) &&
        isset($_POST["repeat_password"])
    ) {
        $name = trim($_POST["name"]);
        $lastName = trim($_POST["lastName"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $repeat_password = $_POST["repeat_password"];

        if ($password !== $repeat_password) {
            die("Greška: Lozinke nisu identične!");
        }

        $stmt = $db->prepare("SELECT id FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->execute()) {
            echo "Uspešno registrovan!";
        } else {
            die("Greška pri unosu podataka: " . $stmt->error);
        }
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            die("Greška: Ovaj email već postoji!");
        }
        $stmt->close();

        // OBRADA SLIKE
        $profile_pic = "uploads/default.jpg"; // Default slika ako korisnik ne doda svoju

        if (!empty($_FILES["pp"]["name"])) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true); // Napravi folder ako ne postoji
            }

            $imageFileType = strtolower(pathinfo($_FILES["pp"]["name"], PATHINFO_EXTENSION));
            $allowed_types = ["jpg", "jpeg", "png", "gif"];

            if (!in_array($imageFileType, $allowed_types)) {
                die("Greška: Dozvoljeni formati su JPG, JPEG, PNG, GIF!");
            }

            $profile_pic = $target_dir . uniqid() . "." . $imageFileType;
            if (!move_uploaded_file($_FILES["pp"]["tmp_name"], $profile_pic)) {
                die("Greška prilikom premještanja fajla! Kod greške: " . $_FILES["pp"]["error"]);
            }
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $db->prepare("INSERT INTO users (Ime, Prezime, Email, Lozinka, ProfilnaSlika) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $lastName, $email, $hashed_password, $profile_pic);

        if ($stmt->execute()) {
            $_SESSION["name"] = $name;
            $_SESSION["lastName"] = $lastName;
            $_SESSION["email"] = $email;
            $_SESSION["profile_pic"] = $profile_pic;
            header("Location: login.php");
            exit();
        } else {
            die("Greška pri unosu podataka: " . $stmt->error);
        }
        $stmt->close();
    }
}
  

/*
$name = "";
$lastName = "";
$email = "";
$password = "";
 
if(!empty($_POST)) {


if(!isset($_POST["name"])) {
    die("Ime ne postoji!");
}

if(!isset($_POST["lastName"])) {
    die("Prezime ne postoji!");
}

if(!isset($_POST["email"])) {
    die("Email ne postoji!");
}

if(!isset($_POST["password"])) {
    die("Password ne postoji!");
}   

if(!isset($_POST["repeat_password"])) {
    die("Ponovljeni password ne postoji!");
}   
 

if($password !== $_POST["repeat_password"]) {
    die("Lozinka nije identicna");
}

 
 $db = mysqli_connect("localhost", "root", "", "vezba");

 if(mysqli_connect_errno()) {
  die("Greska prilikom konektovanja na bazu". mysqli_connect_error());
 }

 $rezultat = mysqli_query($db, "SELECT * FROM users WHERE email = '$email' ");

 $broj_redova = mysqli_num_rows($rezultat);

 
 if($broj_redova > 0 ) {
    die("Ovaj email vec postoji");
  }
 
$password = password_hash($password, PASSWORD_BCRYPT);

 mysqli_query($db, "INSERT INTO users (Ime, Prezime, Email, Lozinka) VALUES ('$name', '$lastName', '$email', '$password')");
}
 
*/ 
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

 
    <form method="POST" action="index.php" enctype="multipart/form-data">

    <div class="form">
      <div class="title">Welcome</div>
      <div class="subtitle">Let's create your account!</div>
      <div class="input-container ic1">
        <input id="firstname" class="input <?php if(empty($name)) {echo "error";}?>" type="text" name="name" placeholder=" " required />
        <div class="cut"></div>
        <label for="firstname" class="placeholder">First name</label>
      </div>
      <div class="input-container ic2">
        <input id="lastname" class="input <?php if(empty($lastName)) {echo "error";}?>" type="text" name="lastName" placeholder=" " required />
        <div class="cut"></div>
        <label for="lastname" class="placeholder">Last name</label>
      </div>
      <div class="input-container ic2">
        <input id="email" class="input <?php if(empty($email)) {echo "error";}?>" type="email" name="email" placeholder=" " required />
        <div class="cut cut-short"></div>
        <label for="email" class="placeholder">Email</>
      </div>
      <div class="input-container ic2">
        <input id="email" class="input <?php if(empty($password)) {echo "error";}?>" type="password" name="password" placeholder=" " requrired/>
        <div class="cut cut-short"></div>
        <label for="password" class="placeholder">Password</>
      </div>
      <div class="input-container ic2">
        <input id="email" class="input <?php if(empty($repeat_password)) {echo "error";}?>" type="password" name="repeat_password" placeholder=" " requrired/>
        <div class="cut cut-short"></div>
        <label for="repeat_password" class="placeholder">Repeat password</>
      </div>
      <div class="input-container ic2">
      <input type="file" name="pp" accept="image/*">
      </div>
      <button type="text" class="submit">submit</button>
      
    </div>


    </form>

   