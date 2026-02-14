<?php
    session_start();
    if ( isset($_POST['account']) && isset($_POST['password']) ) {
        unset($_SESSION['account']); // Logout the current user
        if ( $_POST['account'] === 'jimbo' && $_POST['password'] === 'jimbo' ) {
            $_SESSION['success'] = "Logged in.";
            $_SESSION['account'] = $_POST['account'];
            header("Location: app.php");
            return;
        } else {
            $_SESSION['error'] = "Incorrect password.";
            header("Location: login.php");
            return;
        }
    }
?>
<html>
<head>Login Page</head>
<body>
<h1>Please Log In</h1>
<?php
// This is the "flash message" pattern. Typically we would creatte a reusable function to do this
if ( isset($_SESSION['error']) ) {
    echo('<p style="color:red">' . $_SESSION['error'] . '</p>');
    unset($_SESSION['error']);
}

if ( isset($_SESSION['success']) ) {
    echo('<p style="color:green">' . $_SESSION['success'] . '</p>');
    unset($_SESSION['success']);
}
?>

<form method="post">
    <p>Account: <input type="text" name="account"></p>
    <p>Password: <input type="password" name="password"></p>
    <!-- password is jimbo -->
    <p><input type="submit" value="Log In">
    <a href="app.php">Cancel</a></p>
</form>
</body>
</html>