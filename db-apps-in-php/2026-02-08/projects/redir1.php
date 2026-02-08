<?php
    session_start();
    if ( isset($_POST['choice']) ) {
        $choice = $_POST['choice'];
        if ( $choice == 1 ) {
            header("Location: redir1.php");
            return;
        } else if ( $choice == 2 ) {
            header("Location: redir2.php?param=12345");
            return;
        } else if ( $choice == 3 ) {
            header("Location: index.php?param=54321");
            return;
        }
    }
?>
<html>
<body>
<h1>This is redir1.</h1>

<p>
<form method="post">
    <h2>Where to go? (1-3)</h2>
    <input type="text" name="choice" id="inp1" size="5" />
    <input type="submit" value="Go" />
</form>
</p>