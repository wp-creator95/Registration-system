<?php

$db = mysqli_connect("localhost", "root", "", "vezba");

$message = "";

if(mysqli_connect_errno()) {
    die("Greska prilikom konekcije na bazu". mysqli_connect_error());
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirm_password"];
 

    if($password === $confirmpassword) {

        //Provera da li email postoji
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Ako korisnik postoji
        if($stmt->num_rows > 0) {
            $stmt->close();

            // Hesiraj lozinku
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            //Azuriraj lozinku 
            $stmt = $db->prepare("UPDATE users SET Lozinka = ? WHERE Email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if($stmt->execute()) {
                $message = "Lozinka je uspesno azurirana.";
            } else {
                $message = "Greska prilikom azuriranja lozinke";
            }
            $stmt->close();
        }else {
            $message = "Greska: Nalog sa ovom email adresom ne postoji";
        }


}else {
    $message = "Greska: Lozinke se ne poklapaju";
}
    $db->close();
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
            <input class="log2" type="password" name="password" placeholder="Unesite novi password">
            <input class="log2" type="password" name="confirm_password" placeholder="Ponovite vas password">
            <input class="submit1" type="submit" name="reset" value="Resetuj Password">

        </form>

        <p><?php echo $message; ?></p>

</body>
</html>