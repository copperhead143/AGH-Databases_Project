<?php

require_once 'config/database.php';

class authentication {
    private $baza;

    public function __construct() {
        $this->baza = new Database();
    }

    public function registration($login , $password, $email, $name, $surname) {
    
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO uzytkownicy (login, password, email, name, surname) VALUES (?, ?, ?, ?, ?)";

        try {
            $this->baza->execute_query($query, array($login, $hashed_password, $email, $name, $surname));
            return true;
        }catch (PDOException $e) {
            return false;
        }
    }

    public function login($login, $password){
        $query = "SELECT id, haslo, rola FROM uzytkownicy WHERE login = ?";
        $result = $this->baza->execute_query($query, array($login));

        if($user = $result->fetch_assoc()){
            if(password_verify($password, $user["password"])){
                session_start();
                $_SESSION['zalogowany'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['rola'] = $user['rola'];
                return true;
            }
        }
        return false;
    }

    public static function czy_zalogowany(){
        session_start();
        return isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true;
    }   

    public static function czy_admin(){
        session_start();
        return isset($_SESSION['rola']) && $_SESSION['rola'] == 'admin';
    }

    public static function wyloguj(){
        session_start();
        session_unset();
        session_destroy();
    }
}
?>