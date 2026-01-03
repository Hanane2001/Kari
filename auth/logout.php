<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/User.php';

User::logout();

header("Location: ../index.php");
exit();
?>