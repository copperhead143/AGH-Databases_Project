<?php
require_once("../includes/auth.php");
require_once("../includes/orders.php");
require_once("../includes/products.php");

if(!authentication::czy_zalogowany()){
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produkt_id = $_POST['produkt_id'];
    $ilosc = $_POST['ilosc'];

    $produkty = new Produkty();
    $rezultat = $produkty->get_product($produkt_id);
    
    if ($produkt = $rezultat->fetch_assoc()) {
        if (!isset($_SESSION['koszyk'])) {
            $_SESSION['koszyk'] = [];
        }

        $znaleziono = false;
        foreach ($_SESSION['koszyk'] as &$element) {
            if ($element['id'] == $produkt_id) {
                $element['ilosc'] += $ilosc;
                $znaleziono = true;
                break;
            }
        }

        if (!$znaleziono) {
            $_SESSION['koszyk'][] = [
                'id' => $produkt_id,
                'nazwa' => $produkt['nazwa'],
                'cena' => $produkt['cena'],
                'ilosc' => $ilosc
            ];
        }

        header("Location: cart.php");
        exit();
    }
}
?>