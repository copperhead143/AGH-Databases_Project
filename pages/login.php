<?php
require_once("../includes\auth.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $autoryzacja = new authentication();

    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    if($autoryzacja->login($login, $haslo)){
        header("Location: ../index.php");
        exit();
    }else{ 
        $blad = "Błędne dane logowania";
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logowanie</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Logowanie</h1>

    <?php if (isset($blad)): ?>
        <p style="color: red;"><?php echo $blad; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Login: <input type="text" name="login" required></label><br>
        <label>Hasło: <input type="password" name="haslo" required></label><br>
        <input type="submit" value="Zaloguj się">
    </form>

    <p>Nie masz konta? <a href="registration.php">Zarejestruj się</a></p>
</body>
</html>