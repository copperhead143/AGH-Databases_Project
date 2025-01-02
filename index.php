<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'includes/products.php';

$products_manager = new Produkty();
$products = $products_manager->get_multiple_products();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>strona glowna</title>
</head>
<body>
    <h1>STRONA GLOWNA SKLEPU</h1>

    <?php if(!authentication::czy_zalogowany()): ?>
        <p>
            <a href="pages\login.php">Zaloguj się</a>
            <a href="pages\registration.php">Zarejestruj się</a>
        </p>
    <?php else: ?>
        <p>
            <?php echo "Witaj, " . $_SESSION['user_id']; ?> | <a href="pages\logut.php">Wyloguj się</a>
        </p>
    <?php endif; ?>

    <h2>Produkty</h2>
    <table border="1">
        <tr>
            <th>Nazwa</th>
            <th>Kategoria</th>
            <th>Cena</th>
            <th>Stan magazynowy</th>
            <th>Kategoria</th>
            <?php if (authentication::czy_zalogowany()): ?>
                <th>Akcje</th>
            <?php endif; ?>
        </tr>
        <?php while ($produkt = $products->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($produkt['nazwa']); ?></td>
                <td><?php echo htmlspecialchars($produkt['kategoria']); ?></td>
                <td><?php echo number_format($produkt['cena'], 2); ?> zł</td>
                <td><?php echo $produkt['stan_magazynowy']; ?></td>
                <?php if (authentication::czy_zalogowany()): ?>
                    <td>
                        <form action="pages/cart.php" method="post">
                            <input type="hidden" name="produkt_id" value="<?php echo $produkt['id']; ?>">
                            <input type="number" name="ilosc" value="1" min="1" max="<?php echo $produkt['stan_magazynowy']; ?>">
                            <input type="submit" value="Dodaj do koszyka">
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php if (authentication::czy_admin()): ?>
        <h2>Panel Administracyjny</h2>
        <ul>
            <li><a href="admin/add_product.php">Dodaj produkt</a></li>
            <li><a href="admin/index.php">Dodaj kategorię</a></li>
        </ul>
    <?php endif; ?>
</body>
</html>