<?php
require_once '../config/database.php';
require_once '../includes/products.php';

class Zamowienia {
    private $baza;
    private $produkty;

    public function __construct() {
        $this->baza = new Database();
        $this->produkty = new Produkty();
    }

    public function create_order($uzytkownik_id, $produkty) {
        try {
            //rozpoczęcie transakcji
            $this->baza->conn->begin_transaction();

            $zapytanie_zamowienie = "INSERT INTO zamowienia (uzytkownik_id) VALUES (?)";
            $this->baza->execute_query($zapytanie_zamowienie, [$uzytkownik_id]);
            $zamowienie_id = $this->baza->conn->insert_id;

            foreach ($produkty as $produkt) {
                $zapytanie_szczegoly = "INSERT INTO szczegoly_zamowienia (zamowienie_id, produkt_id, ilosc, cena_jednostkowa) VALUES (?, ?, ?, ?)";
                $this->baza->execute_query($zapytanie_szczegoly, [
                    $zamowienie_id, 
                    $produkt['id'], 
                    $produkt['ilosc'], 
                    $produkt['cena']
                ]);

                
                $this->produkty->update_inventory($produkt['id'], $produkt['ilosc']);
            }

            $this->baza->conn->commit();
            return $zamowienie_id;

        } catch (Exception $e) {
            $this->baza->conn->rollback();
            return false;
        }
    }

    public function pobierz_zamowienia_uzytkownika($uzytkownik_id) {
        $zapytanie = "
            SELECT z.*, sz.produkt_id, sz.ilosc, sz.cena_jednostkowa, p.nazwa AS nazwa_produktu
            FROM zamowienia z
            JOIN szczegoly_zamowienia sz ON z.id = sz.zamowienie_id
            JOIN produkty p ON sz.produkt_id = p.id
            WHERE z.uzytkownik_id = ?
        ";
        return $this->baza->execute_query($zapytanie, [$uzytkownik_id]);
    }
}
?>