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

// Handle confirm edit
if (isset($_POST['edit'])) {
    if (!isset($_POST['autos_id']) || strlen($_POST['autos_id']) < 1) {
        $_SESSION['message'] = 'Bad value for id';
        $_SESSION['message_type'] = 'error';
        header("Location: index.php");
        return;
    }

    // Validate make
    if (!isset($_POST['make']) || strlen($_POST['make']) < 1 ||
        !isset($_POST['model']) || strlen($_POST['model']) < 1 ||
        !isset($_POST['year']) || strlen($_POST['year']) < 1 ||
        !isset($_POST['mileage']) || strlen($_POST['mileage']) < 1
    ) {
        $_SESSION['message'] = 'All fields are required';
        $_SESSION['message_type'] = 'error';
        header("Location: edit.php?autos_id=" . $_POST['autos_id']);
        return;
    }

    // Validate year and mileage are numeric
    else if (!is_numeric($_POST['year'])) {
        $_SESSION['message'] = 'Year must be numeric';
        $_SESSION['message_type'] = 'error';
        header("Location: edit.php?autos_id=" . $_POST['autos_id']);
        return;
    }
    else if (!is_numeric($_POST['mileage'])) {
        $_SESSION['message'] = 'Mileage must be numeric';
        $_SESSION['message_type'] = 'error';
        header("Location: edit.php?autos_id=" . $_POST['autos_id']);
        return;
    }

    else {
        // Update the auto
        $stmt = $pdo->prepare('UPDATE autos SET make = :mk, model = :md, year = :yr, mileage = :mi WHERE auto_id = :id');
        $stmt->execute(array(
            ':id' => $_POST['autos_id'],
            ':mk' => $_POST['make'],
            ':md' => $_POST['model'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));

        $_SESSION['message'] = 'Record updated.';
        $_SESSION['message_type'] = 'success';
        header("Location: index.php");
        return;
    }
}

if (!isset($_GET['autos_id']) || strlen($_GET['autos_id']) < 1) {
    $_SESSION['message'] = 'Bad value for id';
    $_SESSION['message_type'] = 'error';
    header("Location: index.php");
    return;
}

$stmt = $pdo->prepare('SELECT auto_id, make, model, year, mileage FROM autos WHERE auto_id = :auto_id');
$stmt->execute(array(
    ':auto_id' => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ( $row === false ) {
    $_SESSION['message'] = 'Bad value for id';
    $_SESSION['message_type'] = 'error';
    header("Location: index.php");
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
    ?>

    <h2>Editing Automobile</h2>
    <form method="post">
        <p>Make:
            <input type="text" name="make" size="40" value="<?php echo htmlentities($row['make']); ?>">
        </p>
        <p>Model:
            <input type="text" name="model" size="40" value="<?php echo htmlentities($row['model']); ?>">
        </p>
        <p>Year:
            <input type="text" name="year" size="10" value="<?php echo htmlentities($row['year']); ?>">
        </p>
        <p>Mileage:
            <input type="text" name="mileage" size="10" value="<?php echo htmlentities($row['mileage']); ?>">
        </p>
        <p>
            <input type="hidden" name="autos_id" value="<?php echo $row['auto_id']; ?>">
            <input type="submit" name="edit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>

</body>
</html>
