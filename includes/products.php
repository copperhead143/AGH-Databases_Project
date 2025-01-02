<?php
require_once __DIR__ . '/../config/database.php';

class Produkty {
    private $baza;

    public function __construct() {
        $this->baza = new Database();
    }

    public function add_product($nazwa, $opis, $cena, $stan, $kategoria_id) {
        $zapytanie = "INSERT INTO produkty (nazwa, opis, cena, stan_magazynowy, kategoria_id) VALUES (?, ?, ?, ?, ?)";
        
        try {
            $this->baza->execute_query($zapytanie, [$nazwa, $opis, $cena, $stan, $kategoria_id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function get_multiple_products() {
        $zapytanie = "SELECT p.*, k.nazwa AS kategoria FROM produkty p JOIN kategorie k ON p.kategoria_id = k.id";
        return $this->baza->execute_query($zapytanie);
    }

    public function get_product($id) {
        $zapytanie = "SELECT * FROM produkty WHERE id = ?";
        return $this->baza->execute_query($zapytanie, [$id]);
    }

    public function update_inventory($produkt_id, $ilosc) {
        $zapytanie = "UPDATE produkty SET stan_magazynowy = stan_magazynowy - ? WHERE id = ?";
        $this->baza->execute_query($zapytanie, [$ilosc, $produkt_id]);
    }
}
?>