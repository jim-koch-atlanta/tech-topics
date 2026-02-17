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

// Handle confirm deletion
if (isset($_POST['delete']) && isset($_POST['auto_id'])) {
    $stmt = $pdo->prepare('DELETE FROM autos WHERE auto_id = :id');
    $stmt->execute(array(':id' => $_POST['auto_id']));
    $_SESSION['message'] = 'Record deleted.';
    $_SESSION['message_type'] = 'success';
    header("Location: index.php");
    return;
}

// Handle deletion
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
    $_SESSION['error'] = 'Bad value for id';
    header("Location: index.php");
    return;
}

?>
<html>
<head>
    <title>f2b3e010 - Jim's Automobiles Database</title>
</head>
<body>
    <p>Confirm: Deleting <?php echo htmlentities($row['make']); ?></p>
    <form method="post">
        <input type="hidden" name="auto_id" value="<?php echo $row['auto_id']; ?>">
        <input type="submit" name="delete" value="Delete">

        <a href="index.php">Cancel</a>
    </form>
</body>
</html>
