<?php

# This is a non-OOP version of the code in 2026-01-19/oop.php
$jim = array(
    'name' => 'Jim',
    'age' => 45,
    'occupation' => 'Developer'
);

$kathleen = array(
    'name' => 'Kathleen',
    'age' => 48,
    'occupation' => 'Physical Therapist'
);

function get_person_name($person) {
    if (!is_array($person) || !isset($person['name'])) {
        return null;
    }

    return $person['name'];
}

print get_person_name($jim) . "\n";
print get_person_name($kathleen) . "\n";
