<?php
require_once 'autoryzacja.php';

authentication::wyloguj();
header("Location: index.php");
exit();
?>