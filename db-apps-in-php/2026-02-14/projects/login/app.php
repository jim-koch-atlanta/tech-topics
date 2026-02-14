<?php
// There's no actual model code in app.php, because we do not post to app.php. We post to login.php and then redirect back to app.php. This is a common pattern in web applications.
?>

<html>
<head>
<title>Welcome to the App</title>
</head>
<body>
<p>Welcome to the app</p>
<?php
session_start();
if ( isset($_SESSION['success']) ) {
    echo('<p style="color:green">' . $_SESSION['success'] . '</p>');
    unset($_SESSION['success']);
}

// Check if we are logged in!
if ( ! isset($_SESSION['account']) ) {
    echo('<p><a href="login.php">Login to start</a></p>');
} else {
    echo('<p>This is where the cool application is!</p>');
    echo('<p>You are logged in as: ' . htmlentities($_SESSION['account']) . '</p>');
    echo('<p>Please <a href="logout.php">Logout</a> when you are done.</p>');
}
?>
</body>
</html>