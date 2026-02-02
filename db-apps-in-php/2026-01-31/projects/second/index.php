<?php

require_once "pdo.php";
if ( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) ) {
    $sql = "INSERT INTO users (name, email, password) VALUES ( :name, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':password' => $_POST['password'])
    );

    echo("<pre>\n" . $sql . "\n</pre>\n");
}

if ( isset($_POST['delete']) && isset($_POST['user_id']) ) {
    $sql = "DELETE FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':user_id' => $_POST['user_id'])
    );

    echo("<pre>\n" . $sql . "\n</pre>\n");
}

echo '<table border="1">' . "\n";
$stmt = $pdo->query("SELECT * FROM users");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo(htmlentities($row['name']));
    echo("</td><td>");
    echo(htmlentities($row['email']));
    echo("</td><td>");
    echo(htmlentities($row['password']));
    echo("</td><td>");
    echo('<form method="post"><input type="hidden" name="user_id" value="' . htmlentities($row['user_id']) . '"><input type="submit" name="delete" value="Delete"></form>');
    echo("</td></tr>\n");
}
echo "</table>\n";
?>

<html>
    <head></head>
    <body>
        <p>Add a New User</p>
        <form method="post">
            <p>Name:
                <input type="text" name="name" size="40">
            </p>
            <p>Email:
                <input type="text" name="email" size="40">
            </p>
            <p>Password:
                <input type="text" name="password" size="40">
            </p>
            <p>
                <input type="submit" value="Add New"/>
                <input type="submit" name="cancel" value="Cancel"/>
            </p>
    </form>
    </body>
</html>