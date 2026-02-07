# Coursera: Building Database Applications in PHP

See https://www.coursera.org/learn/database-applications-php/lecture/lo8Wy/error-handling-with-pdo.

## Error Handling with PDO

You'll generally want to treat errors as exceptions and catch them:
```php
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
    $stmt = $pdo->prepare(...);
    $stmt->execute(...);
} catch (Exception $ex) {
    // Do something with $ex->getMessage().
}
```

## Project Assignment

See the "Automobiles and Databases" assignment requirements in [ASSIGNMENT.md](./ASSIGNMENT.md). The assignment implementation is in [./projects/assignments/](./projects/assignment/).

## Next

See https://www.coursera.org/learn/database-applications-php/lecture/9BTbS/cookies.