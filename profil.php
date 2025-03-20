<?php

session_start();

// Proveri da li je korisnik ulogovan
if (!isset($_SESSION["email"])) {
    header("Location: login.php"); // Ako nije ulogovan, preusmeri na login stranicu
    exit();
}

 
// Dohvati podatke iz sesije
$name = $_SESSION["name"];
$lastName = $_SESSION["lastName"];
$profile_pic = isset($_SESSION["profile_pic"]) ? $_SESSION["profile_pic"] : "uploads/default.jpg";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navigation -->

<?php require_once "nav/navigation.php"; ?>
    

    <nav class="nav">

        <?php foreach ($nav as $navData => $navValue) : ?>

            <a href="<?php echo $navValue; ?>"><?php echo $navData; ?></a>

            <?php endforeach; ?>

    </nav>
     <div class="profil">  
        
     <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profilna slika" width="150" height="150" class="img">   

        <h1>Dobrodošao, <?php echo htmlspecialchars($name . " " . $lastName); ?>!</h1>

         <a href="login.php" class="lg">Logout</a>
         <a href="resetpassword.php" class="rp">Reset password</a>
         <a href="updateProfil.php" class="upd">Update your data</a>
         <a href="delete_user.php" class="dlt">Delete account</a>
    </div>    

</body>
</html> 