<?php

session_start();

$db = mysqli_connect("localhost", "root", "", "vezba");

if (!$db) {
    die("Greška prilikom konekcije na bazu: " . mysqli_connect_error());
}

// Provera da li je korisnik ulogovan
if (!isset($_SESSION["email"])) {
    die("Greška: Niste ulogovani ili sesija nije postavljena!");
}

$email = $_SESSION["email"];

// Provera da li korisnik postoji u bazi
$checkQuery = "SELECT Id FROM users WHERE Email = ?";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    die("Greška: Nema korisnika sa tim email-om u bazi!");
}

$checkStmt->close();

// Priprema SQL upita za brisanje
$sql = "DELETE FROM users WHERE Email = ?";
$stmt = $db->prepare($sql);  // Greška u originalnom kodu (trebalo je $db, ne $conn)
$stmt->bind_param("s", $email);

// Izvršavanje upita i provera uspešnosti
if ($stmt->execute()) {
    echo "Korisnički nalog je uspešno obrisan!";
    
    // Brisanje sesije i preusmeravanje
    session_destroy();
    header("Location: index.php"); 
    exit();
} else {
    echo "Greška pri brisanju naloga: " . $stmt->error;
}

$stmt->close();
$db->close();
?>