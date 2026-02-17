<?php
require_once "pdo.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['name']) || strlen($_SESSION['name']) < 1) {
    echo('<html>');
    echo('<head>');
    echo('<title>f2b3e010 - Jim\'s Automobiles Database</title>');
    echo('</head>');
    echo('<body>');
    echo('<h1>Welcome to the Automobiles Database</h1>');
    echo('<p>');
        echo('<a href="login.php">Please log in</a>');
    echo('</p>');
    echo('<p>Attempt to go to <a href="add.php">add.php</a> without logging in - it should fail with an error message.</p>');
    echo('</body>');
    echo('</html>');
    return;
}
?>

<html>
<head>
    <title>f2b3e010 - Jim's Automobiles Database</title>
</head>
<body>
    <h1>Tracking Automobiles for <?php echo htmlentities($_SESSION['name']); ?></h1>

    <?php
    $message = $_SESSION['message'] ?? '';
    unset($_SESSION['message']);
    $message_type = $_SESSION['message_type'] ?? '';
    unset($_SESSION['message_type']);
    
    if (strlen($message) > 0) {
        $color = ($message_type === 'success') ? 'green' : 'red';
        echo '<p style="color: ' . $color . ';">' . htmlentities($message) . '</p>';
    }

    // Fetch all autos
    $stmt = $pdo->query('SELECT auto_id, make, model, year, mileage FROM autos');
    $autos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($autos) > 0) {
        echo '<h2>Automobiles</h2>';
        echo('<table border="1">'."\n");
        echo '<tr><th>Year</th><th>Make</th><th>Model</th><th>Mileage</th><th>Action</th></tr>';
        foreach ($autos as $auto) {
            echo '<tr>';
            echo '<td>' . htmlentities($auto['year']) . '</td>';
            echo '<td>' . htmlentities($auto['make']) . '</td>';
            echo '<td>' . htmlentities($auto['model']) . '</td>';
            echo '<td>' . htmlentities($auto['mileage']) . '</td>';
            echo('<td><a href="edit.php?autos_id='.$auto['auto_id'].'">Edit</a> / ');
            echo('<a href="delete.php?autos_id='.$auto['auto_id'].'">Delete</a></td>');
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No rows found</p>';
    }
    ?>

    <p><a href="add.php">Add New Entry</a></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
