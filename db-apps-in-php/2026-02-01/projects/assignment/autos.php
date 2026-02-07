<?php
require_once "pdo.php";

// Check if user is logged in
if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die("Name parameter missing");
}

// Initialize message
$message = '';
$message_type = '';

// Handle logout
if (isset($_POST['logout'])) {
    header('Location: index.php');
    return;
}

// Handle add auto
if (isset($_POST['add'])) {
    // Validate make
    if (!isset($_POST['make']) || strlen($_POST['make']) < 1) {
        $message = 'Make is required';
        $message_type = 'error';
    }
    // Validate year and mileage are numeric
    else if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $message = 'Mileage and year must be numeric';
        $message_type = 'error';
    }
    else {
        // Insert the auto
        $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));
        $message = 'Record inserted';
        $message_type = 'success';
    }
}

// Handle delete auto
if (isset($_POST['delete']) && isset($_POST['auto_id'])) {
    $stmt = $pdo->prepare('DELETE FROM autos WHERE auto_id = :id');
    $stmt->execute(array(':id' => $_POST['auto_id']));
    $message = 'Record deleted';
    $message_type = 'success';
}

// Fetch all autos
$stmt = $pdo->query('SELECT auto_id, make, year, mileage FROM autos');
$autos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <title>f2b3e010 - Jim's Automobiles Database</title>
</head>
<body>
    <h1>Tracking Automobiles for <?php echo htmlentities($_GET['name']); ?></h1>

    <?php
    if (strlen($message) > 0) {
        $color = ($message_type === 'success') ? 'green' : 'red';
        echo '<p style="color: ' . $color . ';">' . htmlentities($message) . '</p>';
    }
    ?>

    <h2>Add New Automobile</h2>
    <form method="post">
        <p>Make:
            <input type="text" name="make" size="40"
                   value="<?php echo isset($_POST['make']) ? htmlentities($_POST['make']) : ''; ?>">
        </p>
        <p>Year:
            <input type="text" name="year" size="10"
                   value="<?php echo isset($_POST['year']) ? htmlentities($_POST['year']) : ''; ?>">
        </p>
        <p>Mileage:
            <input type="text" name="mileage" size="10"
                   value="<?php echo isset($_POST['mileage']) ? htmlentities($_POST['mileage']) : ''; ?>">
        </p>
        <p>
            <input type="submit" name="add" value="Add">
            <input type="submit" name="logout" value="Logout">
        </p>
    </form>

    <?php
    if (count($autos) > 0) {
        echo '<h2>Automobiles</h2>';
        echo '<table border="1">';
        echo '<tr><th>Make</th><th>Year</th><th>Mileage</th><th>Action</th></tr>';
        foreach ($autos as $auto) {
            echo '<tr>';
            echo '<td>' . htmlentities($auto['make']) . '</td>';
            echo '<td>' . htmlentities($auto['year']) . '</td>';
            echo '<td>' . htmlentities($auto['mileage']) . '</td>';
            echo '<td>';
            echo '<form method="post" style="display:inline;">';
            echo '<input type="hidden" name="auto_id" value="' . htmlentities($auto['auto_id']) . '">';
            echo '<input type="submit" name="delete" value="Delete">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    ?>
</body>
</html>
