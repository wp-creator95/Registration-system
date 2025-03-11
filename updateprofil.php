<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = mysqli_connect("localhost", "root", "", "vezba");

if (!$db) {
    die("Greška prilikom konekcije na bazu: " . mysqli_connect_error());
}

// Proveravamo da li je korisnik ulogovan (preko email-a)
if (!isset($_SESSION['email'])) {
    die("Greška: Morate biti ulogovani da biste ažurirali podatke.");
}

$userEmail = $_SESSION['email']; // Dohvatanje email-a iz sesije

// Dohvatanje trenutnih podataka korisnika
$query = "SELECT Ime, Email FROM users WHERE Email = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Greška: Korisnik ne postoji u bazi.");
}

$currentSettings = $result->fetch_assoc();
$stmt->close();

// Ažuriranje podataka korisnika
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    if (!empty($name) && !empty($email)) {
        // Provera da li novi email već postoji u bazi
        $checkEmailQuery = "SELECT Email FROM users WHERE Email = ? AND Email != ?";
        $stmt = $db->prepare($checkEmailQuery);
        $stmt->bind_param("ss", $email, $userEmail);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows > 0) {
            die("Greška: Ovaj email već koristi drugi korisnik.");
        }

        // Ažuriranje podataka ulogovanog korisnika
        $query = "UPDATE users SET Ime = ?, Email = ? WHERE Email = ?";
        $stmt = $db->prepare($query);

        if (!$stmt) {
            die("Greška prilikom pripreme upita: " . $db->error);
        }

        $stmt->bind_param("sss", $name, $email, $userEmail);

        if ($stmt->execute()) {
            $_SESSION['email'] = $email; // Ažuriramo email u sesiji
            echo "Podaci su uspešno ažurirani!";
        } else {
            echo "Greška pri ažuriranju podataka: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Sva polja su obavezna!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Ažuriranje</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once "nav/navigation.php"; ?>

<nav class="nav">
    <?php foreach ($nav as $navData => $navValue) : ?>
        <a href="<?php echo $navValue; ?>"><?php echo $navData; ?></a>
    <?php endforeach; ?>
</nav>

<form class="login" method="POST" action="">
    <input class="log2" type="text" name="name" placeholder="Unesite novo ime" value="<?php echo htmlspecialchars($currentSettings['Ime'] ?? ''); ?>">
    <input class="log1" type="email" name="email" placeholder="Unesite vaš novi email" value="<?php echo htmlspecialchars($currentSettings['Email'] ?? ''); ?>">
    <input class="submit1" type="submit" name="update" value="Update">
</form>

</body>
</html>