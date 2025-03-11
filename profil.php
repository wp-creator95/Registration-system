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

    <h1>Dobrodošao, <?php echo htmlspecialchars($name . " " . $lastName); ?>!</h1>

    <a href="login.php">Izloguj se</a>
    <a href="resetpassword.php">Resetuj lozinku</a>
    <a href="updateProfil.php">Azuriraj podatke</a>
    <a href="delete_user.php">Obrisi nalog</a>

</body>
</html> 