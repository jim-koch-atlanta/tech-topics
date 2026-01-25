<?php

# This is an OOP version of the code in 2026-01-19/non_oop.php
class Person {
    private $name;
    private $age;
    private $occupation;

    public function __construct($name, $age, $occupation) {
        $this->name = $name;
        $this->age = $age;
        $this->occupation = $occupation;
    }

    public function getName() {
        return $this->name;
    }
}
$jim = new Person('Jim', 45, 'Developer');
$kathleen = new Person('Kathleen', 48, 'Physical Therapist');

print($jim->getName() . "\n");
print($kathleen->getName() . "\n");
