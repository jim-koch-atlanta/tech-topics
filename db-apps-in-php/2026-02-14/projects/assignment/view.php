<?php
require_once "pdo.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['name']) || strlen($_SESSION['name']) < 1) {
    echo("<html><body>Not logged in</body></html>");
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
    $stmt = $pdo->query('SELECT auto_id, make, year, mileage FROM autos');
    $autos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($autos) > 0) {
        echo '<h2>Automobiles</h2>';
        echo '<ul>';
        echo '<li><strong>Year</strong> | <strong>Make</strong> | <strong>Mileage</strong></li>';
        foreach ($autos as $auto) {
            echo '<li>';
            echo htmlentities($auto['year']) . ' ' . htmlentities($auto['make']) . ' / ' . htmlentities($auto['mileage']);
            echo '</li>';
        }
        echo '</ul>';
    }
    ?>

    <p><a href="add.php">Add New</a> | <a href="logout.php">Logout</a></p>
</body>
</html>
