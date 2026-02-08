# Coursera: Building Database Applications in PHP

See https://www.coursera.org/learn/database-applications-php/lecture/dVuUw/redirect-routing-and-authentication.

## Redirect, Routing, and Authentication

The "redirect pattern" is a way for the server and PHP code to force the browser to go somewhere else:

1. The browser sends an initial request.
2. The controller responds with a "redirect" response.
3. The browser parses that response and identifies where it is being redirected.
4. The browser sends a request for the redirect location.
5. The server returns the intended response.

### HTTP Status Codes

* **200 OK**
* **404 Not Found**
* **302 Found / Moved**, also known as "redirect"

### Routing and Redirect

If your application has not yet sent any data, it can send a special header as part of the HTTP response. The redirect header includesa URL that the browser is supposed to forward itself to.

We set this header (and other headers) using the `header()` function. For example:

```php
<?php
header('Location: http://www.example.com');
return;
?>
>
```

Remember that `header()` must be called before any output is sent. Pretty much every time there is a `header()` call, it will be followed immediately be `return;`.

## Next

https://www.coursera.org/learn/database-applications-php/lecture/679GQ/post-refresh-redirect
