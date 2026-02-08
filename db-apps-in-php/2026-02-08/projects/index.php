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
<h1>This is the index.</h1>

<?php
    if ( isset($_GET['param']) && $_GET['param'] == "54321" ) {
        echo("<p>Got a param: " . htmlentities($_GET['param']) . "</p>\n");
        echo("<p>Return to <a href='index.php'>index</a></p>\n");
        return;
    }
?>
<p>
<form method="post">
    <h2>Where to go? (1-3)</h2>
    <input type="text" name="choice" id="inp1" size="5" />
    <input type="submit" value="Go" />    
</form>
</p>