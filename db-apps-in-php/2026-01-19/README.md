# Coursera: Building Database Applications in PHP

See https://www.coursera.org/learn/database-applications-php/home/module/1.

## Course Textbook

This course uses the free textbook [The Missing Link: An Introduction to Web Development and Programming](https://milneopentextbooks.org/the-missing-link-an-introduction-to-web-development-and-programming/).

## Syllabus

> You'll learn how PHP avoids double posting data, how flash messages are implemented, and how to use a session to log in users in web applications.

I'll need to learn what "double posting data" and "flash messages" mean.

## Object Oriented Concepts

OOP was introduced to PHP in PHP 5, and really became solidified as part of the PHP ecosystem in PHP 7.

Prior to PHP 5, functionality was exposed in a non-OOP manner. For example: `date_add`, `date_create_from_format`, `date_create_immutable_from_format`, `date_diff`, `date_parse`, ... All of the functions are then global.

In PHP 5+, there is a `DateTime` class, and the methods are defined on that class --
```
public DateTime add()
public static DateTime createFromFormat()
public DateTime modify()
...
```

## Creating Objects in PHP

We know this already. See [non_oop.php](./non_oop.php) vs. [oop.php](./oop.php).

## Object Life Cycle in PHP

The constructor (`__construct()`) is called to initialize an instance of an object. The destructor (`__destruct()`) is called when the object lifecycle ends.

## Object inheritance in PHP

This is not the standard pattern, but you can build an object from scratch. Sometimes there may be a reason to make an object with public key-value pairs rather than an array. This can be done with `stdClass`:

```
$jim = new stdClass();

$jim->name = "Jim";
$jim->age = 45;
```

## Next

https://www.coursera.org/learn/database-applications-php/assignment-submission/9wtk8/php-objects
