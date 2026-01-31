<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'jim', 'zap');

$stmt = $pdo->query("SELECT * FROM users");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) {
    echo htmlentities($row['name']) . " " . htmlentities($row['email']) . "<br>\n";
}
?>