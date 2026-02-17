# Coursera: Building Database Applications in PHP

See https://www.coursera.org/learn/database-applications-php/lecture/fYwaP/create-read-update-and-delete-crud.

## The CRUD Pattern

When we store in a DB, we need to be able to **create** (INSERT), **read** (SELECT), **update**, and **delete**.

We've done nearly all of these now, except for update. To have full CRUD, we would want:

* Add new row
* View all rows
* View single row
* Edit single row
* Delete a row

When dealing with user-entered data, such as a user name, we don't need to **sanitize** the data before inserting it into the SQL DB. That's safely handled by using parameterized queries.

However, when we retrieve and use the data, such as displaying the data, we need to ensure we sanitize it with a `htmlentities()` call.

## Code Walkthrough - CRUD in PHP

See [crud](./projects/crud/), which is available at https://www.wa4e.com/code/crud.zip.

## List existing entries

* index.php lists the existing users. 
* add.php adds a new user.
* delete.php deletes an existing user.
* edit.php updates an existing user.

We see the flash pattern present in all four of those files:
```php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
```

Similarly, we see the guardian pattern wherever a SQL query is executed:
```php
if ( isset($_POST['delete']) && isset($_POST['user_id']) ) {
    $sql = "DELETE FROM users WHERE user_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['user_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}
```
We are checking that we have the necessary data in our `if` statement, prior to using that data in the SQL query. This **guards** against running the code if the `POST` isn't set, which ensures all of the necessary preconditions are met.

## Project Assignment: Automobiles C.R.U.D.

This has very similar requirements to the previous assignment. New requirements are:
* The application will now need to support Edit functionality.
* The application will now need to support Delete functionality.
* Instead of showing the list of existing automobiles on view.php, they will be displayed on index.php when the user is logged in.
* If the user loads add.php, delete.php, or edit.php without being logged in, the page should do only `die('ACCESS DENIED');`

We need to track make and model separately, so we'll have to update the table --
```
CREATE TABLE autos (
   auto_id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
   make VARCHAR(128),
   model VARCHAR(128),
   year INTEGER,
   mileage INTEGER
);
```

The implementation is available at [assignment](./projects/assignment/).
