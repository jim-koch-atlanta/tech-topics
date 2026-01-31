<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'jim', 'zap');

$stmt = $pdo->query("SELECT * FROM users");
echo '<table border="1">' . "\n";
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo(htmlentities($row['name']));
    echo("</td><td>");
    echo(htmlentities($row['email']));
    echo("</td></tr>\n");
}
echo "</table>\n";
?>