<?php
require_once "pdo.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['name']) || strlen($_SESSION['name']) < 1) {
    die('ACCESS DENIED');
    return;
}

if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

// Handle add auto
if (isset($_POST['add'])) {
    // Validate make
    if (!isset($_POST['make']) || strlen($_POST['make']) < 1 ||
        !isset($_POST['model']) || strlen($_POST['model']) < 1 ||
        !isset($_POST['year']) || strlen($_POST['year']) < 1 ||
        !isset($_POST['mileage']) || strlen($_POST['mileage']) < 1
    ) {
        $_SESSION['message'] = 'All fields are required';
        $_SESSION['message_type'] = 'error';
        header("Location: add.php");
        return;
    }

    // Validate year and mileage are numeric
    else if (!is_numeric($_POST['year'])) {
        $_SESSION['message'] = 'Year must be numeric';
        $_SESSION['message_type'] = 'error';
        header("Location: add.php");
        return;
    }
    else if (!is_numeric($_POST['mileage'])) {
        $_SESSION['message'] = 'Mileage must be numeric';
        $_SESSION['message_type'] = 'error';
        header("Location: add.php");
        return;
    }

    else {
        // Insert the auto
        $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':md' => $_POST['model'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));

        $_SESSION['message'] = 'Record added.';
        $_SESSION['message_type'] = 'success';
        header("Location: index.php");
        return;
    }
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
    ?>

    <h2>Add New Automobile</h2>
    <form method="post">
        <p>Make:
            <input type="text" name="make" size="40">
        </p>
        <p>Model:
            <input type="text" name="model" size="40">
        </p>
        <p>Year:
            <input type="text" name="year" size="10">
        </p>
        <p>Mileage:
            <input type="text" name="mileage" size="10">
        </p>
        <p>
            <input type="submit" name="add" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>

</body>
</html>
