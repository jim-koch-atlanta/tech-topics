# Coursera: Building Database Applications in PHP

See https://www.coursera.org/learn/database-applications-php/lecture/wfC9R/accessing-mysql-using-pdo-inserting-data.

## Accessing MySQL Using PDO: Inserting Data

With PDO it is possible to insert data by executing an `INSERT` query. In [first/index.php](./projects/first/index.php), we do this in the model portion of the code:
```php
    $sql = "INSERT INTO users (name, email, password) VALUES ( :name, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':password' => $_POST['password'])
    );
```

`:name`, `:email`, and `:password` are **placeholders** in the SQL query, which are populated later on execution. This prevents SQL injection.

## Accessing MySQL Using PDO: Deleting Data

We can also add in delete functionality as well. See [second/index.php](./projects/second/index.php), where we added a Delete button for each of the users.

**Note**: Deletion should never be triggered by a `GET` request. `GET` is meant for retrieving data without side effects -- browsers may prefetch links, and GET URLs appear in history and logs. Use `POST` (or `DELETE`) for state-changing operations.

## Avoided SQL Injection

Why don't we just add the values directly into our SQL query string? Why not just have:
```php
if ( isset($_POST['email']) && isset($_POST['password']) ) {
    $e = $_POST['email'];
    $p = $_POST['password'];
    $sql = "SELECT name FROM users WHERE email = '$e' AND password = '$p'";
    $stmt = $pdo->query($sql);
}
```

That will cause security issues if users add malicious data. For example, let's say a user specified the password:
```
p' OR '1' = '1`
```

In that case, the SQL query would be:
```
SELECT name FROM users WHERE email = '<some email>' AND password = 'p' OR '1' = '1'
```

That would always succeed, because `'1' = '1'` is always true. So this is why we use **parameterized queries** to sanitize the input and protect against SQL injection.

## Next

https://www.coursera.org/learn/database-applications-php/lecture/lo8Wy/error-handling-with-pdo