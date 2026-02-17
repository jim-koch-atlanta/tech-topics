<?php
session_start();

// Password hash settings
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';

// Handle form submission
if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

if (isset($_POST['email']) && isset($_POST['pass'])) {
    // Initialize message
    $_SESSION['message'] = '';
    $email = $_POST['email'];
    unset($_POST['email']);
    $pass = $_POST['pass'];
    unset($_POST['pass']);

    // Validate that both fields are filled
    if (strlen($email) < 1 || strlen($pass) < 1) {
        $_SESSION['message'] = 'Email and password are required';
        header("Location: login.php");
        return;
    }
    // Validate email contains @
    else if (strpos($email, '@') === false) {
        $_SESSION['message'] = 'Email must have an at-sign (@)';
        header("Location: login.php");
        return;
    }
    // Check password
    else {
        $check = hash('md5', $salt . $pass);
        if ($check === $stored_hash) {
            error_log("Login success " . $email);
            $_SESSION['name'] = $email;
            header("Location: index.php");
            return;
        } else {
            error_log("Login fail " . $email . " $check");
            $_SESSION['message'] = 'Incorrect password';
            header("Location: login.php");
            return;
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
    if ( isset($_SESSION['message']) && strlen($_SESSION['message']) > 0 ) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        echo '<p style="color: red;">' . htmlentities($message) . '</p>';
    }
    ?>
    <form method="post">
        <p>Email:
            <input type="text" name="email" size="40">
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
