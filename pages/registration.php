<?php

require_once '../includes/auth.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $autoryzacja = new authentication();

    $login = $_POST['login'];
    $haslo = $_POST['haslo'];
    $email = $_POST['email'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];

    if($autoryzacja->registration($login, $haslo, $email, $imie, $nazwisko)){
        header("Location: index.php?rejestracja=sukces");
        exit();
    }else{
        $blad = "blad rejestracji";
    }
}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rejestracja</title>
</head>
<body>
    
    <h1>Rejestracja</h1>
    <?php if(isset($blad)): ?>
        <p><?php echo $blad; ?></p>
    <?php endif; ?>

    <form action="registration.php" method="post">
        <label for="login">Login:</label>
        <input type="text" name="login" id="login" required>
        <br>
        <label for="haslo">Haslo:</label>
        <input type="password" name="haslo" id="haslo" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="imie">Imie:</label>
        <input type="text" name="imie" id="imie" required>
        <br>
        <label for="nazwisko">Nazwisko:</label>
        <input type="text" name="nazwisko" id="nazwisko" required>
        <br>
        <input type="submit" value="Zarejestruj się">
    </form>
    
    <p>Masz juz konto? <br><a href="login.php">Zaloguj się</a></p>

</body>
</html>