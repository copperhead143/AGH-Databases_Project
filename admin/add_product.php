<?php
session_start();
require_once 'autoryzacja.php';
require_once 'produkty.php';

if (!authentication::czy_admin()) {
    header("Location: index.php");
    exit();
}

$produkty = new Produkty();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazwa = $_POST['nazwa'];
    $opis = $_POST['opis'];
    $cena = $_POST['cena'];
    $stan = $_POST['stan'];
    $kategoria = $_POST['kategoria'];

    if ($produkty->add_product($nazwa, $opis, $cena, $stan, $kategoria)) {
        $komunikat = "Produkt został dodany pomyślnie!";
    } else {
        $blad = "Błąd podczas dodawania produktu.";
    }
}

// Pobierz kategorie
$baza = new Database();
$kategorie = $baza->execute_query("SELECT * FROM kategorie");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodaj produkt</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Dodaj nowy produkt</h1>

    <?php if (isset($komunikat)): ?>
        <p style="color: green;"><?php echo $komunikat; ?></p>
    <?php endif; ?>

    <?php if (isset($blad)): ?>
        <p style="color: red;"><?php echo $blad; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nazwa produktu: <input type="text" name="nazwa" required></label><br>
        <label>Opis: <textarea name="opis"></textarea></label><br>
        <label>Cena: <input type="number" step="0.01" name="cena" required></label><br>
        <label>Stan magazynowy: <input type="number" name="stan" required></label><br>
        
        <label>Kategoria:
            <select name="kategoria">
                <?php while ($kategoria = $kategorie->fetch_assoc()): ?>
                    <option value="<?php echo $kategoria['id']; ?>">
                        <?php echo htmlspecialchars($kategoria['nazwa']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label><br>

        <input type="submit" value="Dodaj produkt">
    </form>

    <p><a href="index.php">Wróć do sklepu</a></p>
</body>
</html>