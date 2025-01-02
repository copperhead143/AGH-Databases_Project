<?php
require_once ("../includes\auth.php");

authentication::wyloguj();
header("Location: ../index.php");
exit();
?>