<?php

require_once 'pdo.php';

echo '<table border="1">' . "\n";
$stmt = $pdo->query("SELECT * FROM users");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo(htmlentities($row['name']));
    echo("</td><td>");
    echo(htmlentities($row['email']));
    echo("</td></tr>\n");
}
echo "</table>\n";
?>