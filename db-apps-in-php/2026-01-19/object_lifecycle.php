<?php

class SomethingObject {
    public function __construct() {
        echo("Constructed\n");
    }

    public function something() {
        echo("Something\n");
    }

    public function __destruct() {
        echo("Destructed\n");
    }
}

echo("--One\n");
$x = new SomethingObject();
echo("--Two\n");
$y = new SomethingObject();
echo("--Three\n");
$x->something();
echo("--The End?\n");
