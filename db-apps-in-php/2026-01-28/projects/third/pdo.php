<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'jim', 'zap');

// Set PDO to be more talkative on errors.
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>