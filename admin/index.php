<?php
session_start();
require_once ("config\database.php");
require_once ("includes\auth.php");
require_once ("../includes\products.php");
require_once ("../includes/orders.php");


if (!authentication::czy_admin()) {
    header("Location: ../index.php");
    exit();
}

$baza = new Database();

$statystyki = [
    'produkty' => $baza->execute_query("SELECT COUNT(*) as liczba FROM produkty")->fetch_assoc()['liczba'],
    'zamowienia' => $baza->execute_query("SELECT COUNT(*) as liczba FROM zamowienia")->fetch_assoc()['liczba'],
    'uzytkownicy' => $baza->execute_query("SELECT COUNT(*) as liczba FROM uzytkownicy")->fetch_assoc()['liczba'],
    'kategorie' => $baza->execute_query("SELECT COUNT(*) as liczba FROM kategorie")->fetch_assoc()['liczba']
];

$ostatnie_zamowienia = $baza->execute_query("
    SELECT z.id, z.data_zamowienia, z.status, u.login as klient,
           (SELECT SUM(sz.ilosc * sz.cena_jednostkowa) 
            FROM szczegoly_zamowienia sz 
            WHERE sz.zamowienie_id = z.id) as wartosc
    FROM zamowienia z
    JOIN uzytkownicy u ON z.uzytkownik_id = u.id
    ORDER BY z.data_zamowienia DESC
    LIMIT 5
");

$niski_stan = $baza->execute_query("
    SELECT id, nazwa, stan_magazynowy 
    FROM produkty 
    WHERE stan_magazynowy < 10
    ORDER BY stan_magazynowy ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel Administratora</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Panel Administratora</h1>
    
    <p><a href="../index.php">Powrót do sklepu</a> | <a href="../pages/logout.php">Wyloguj</a></p>

    <h2>Statystyki</h2>
    <table border="1">
        <tr>
            <td>Liczba produktów:</td>
            <td><?php echo $statystyki['produkty']; ?></td>
        </tr>
        <tr>
            <td>Liczba zamówień:</td>
            <td><?php echo $statystyki['zamowienia']; ?></td>
        </tr>
        <tr>
            <td>Liczba użytkowników:</td>
            <td><?php echo $statystyki['uzytkownicy']; ?></td>
        </tr>
        <tr>
            <td>Liczba kategorii:</td>
            <td><?php echo $statystyki['kategorie']; ?></td>
        </tr>
    </table>

    <h2>Zarządzanie</h2>
    <ul>
        <li><a href="add_product.php">Dodaj nowy produkt</a></li>
        <li><a href="manage_products.php">Zarządzaj produktami</a></li>
        <li><a href="manage_categories.php">Zarządzaj kategoriami</a></li>
        <li><a href="manage_orders.php">Zarządzaj zamówieniami</a></li>
        <li><a href="manage_users.php">Zarządzaj użytkownikami</a></li>
    </ul>

    <h2>Ostatnie zamówienia</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Klient</th>
            <th>Wartość</th>
            <th>Status</th>
            <th>Akcje</th>
        </tr>
        <?php while ($zamowienie = $ostatnie_zamowienia->fetch_assoc()): ?>
            <tr>
                <td><?php echo $zamowienie['id']; ?></td>
                <td><?php echo $zamowienie['data_zamowienia']; ?></td>
                <td><?php echo htmlspecialchars($zamowienie['klient']); ?></td>
                <td><?php echo number_format($zamowienie['wartosc'], 2); ?> zł</td>
                <td><?php echo $zamowienie['status']; ?></td>
                <td>
                    <a href="view_order.php?id=<?php echo $zamowienie['id']; ?>">Szczegóły</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Niski stan magazynowy</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Produkt</th>
            <th>Stan magazynowy</th>
            <th>Akcje</th>
        </tr>
        <?php while ($produkt = $niski_stan->fetch_assoc()): ?>
            <tr>
                <td><?php echo $produkt['id']; ?></td>
                <td><?php echo htmlspecialchars($produkt['nazwa']); ?></td>
                <td><?php echo $produkt['stan_magazynowy']; ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $produkt['id']; ?>">Edytuj</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>