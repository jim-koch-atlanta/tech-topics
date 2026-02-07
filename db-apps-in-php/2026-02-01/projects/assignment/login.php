<?php
// Password hash settings
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';

// Initialize message
$message = '';

// Handle form submission
if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

if (isset($_POST['who']) && isset($_POST['pass'])) {
    // Validate that both fields are filled
    if (strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
        $message = 'Email and password are required';
    }
    // Validate email contains @
    else if (strpos($_POST['who'], '@') === false) {
        $message = 'Email must have an at-sign (@)';
    }
    // Check password
    else {
        $check = hash('md5', $salt . $_POST['pass']);
        if ($check === $stored_hash) {
            error_log("Login success " . $_POST['who']);
            header("Location: autos.php?name=" . urlencode($_POST['who']));
            return;
        } else {
            error_log("Login fail " . $_POST['who'] . " $check");
            $message = 'Incorrect password';
        }
    }
}
?>
<html>
<head>
    <title>f2b3e010 - Jim's Automobiles Login</title>
</head>
<body>
    <h1>Please Log In</h1>
    <?php
    if (strlen($message) > 0) {
        echo '<p style="color: red;">' . htmlentities($message) . '</p>';
    }
    ?>
    <form method="post">
        <p>Email:
            <input type="text" name="who" size="40"
                   value="<?php echo isset($_POST['who']) ? htmlentities($_POST['who']) : ''; ?>">
        </p>
        <p>Password:
            <input type="password" name="pass" size="40">
        </p>
        <p>
            <input type="submit" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
    <p>Hint: The password is "php123"</p>
</body>
</html>
