# Setup & Instantiation

The web server should be configured to serve the `public` directory as the root
directory. This is where the static files are stored. This directory contains
two key files:

- `index.php` - The entry point for the application.
- `.htaccess` - The configuration file for the Apache web server.

## index.php

The `index.php` file is the entry point for the application. It pulls in the
various dependencies, settings, and routes, builds the container, and then
runs the application.

In this example, we build the container through four files within the `app`
directory:

- `app/settings.php` - Contains the dependency injection definitions for the
  application settings.
- `app/dependencies.php` - Contains the dependency injection definition of the
  logger.
- `app/database.php` - Contains the dependency injection definition of the PDO
  database connection.
- `app/repositories.php` - Contains the dependency injection definitions for
  the repository classes.

The container is then built and the application is instantiated.

Next, middleware is added to the application. For organisation, this is done
in the `app/middleware.php` file. In this example, we add the
`SessionMiddleware` to the application.

Finally, the routes are added to the application. How you choose to organise
your routes is up to you. In this example, we have a `routes` directory that
can contain multiple route files to allow for better organisation of grouped
routes.

However, this example could be extended to include multiple route files
easily;

```php
// Register routes
$routes = require __DIR__ . '/../app/routes/api.php'; // First group of routes
$routes($app);
$routes = require __DIR__ . '/../app/routes/web.php'; // Second group of routes
$routes($app);
```

Next, the error and shutdown handlers are instantiated and registered. This
means that if an uncaught error occurs, the error handler will be utilised by
the shutdown handler to craft and emit a response.

We then add the
[routing](https://www.slimframework.com/docs/v4/middleware/routing.html),
[body parsing](https://www.slimframework.com/docs/v4/middleware/body-parsing.html),
and [error handling](https://www.slimframework.com/docs/v4/middleware/error-handling.html)
middleware to the application. By default,
[FastRoute](https://github.com/nikic/FastRoute) is used as the router and the
body parsing middleware adds support for the following formats that aren't
natively supported by [PSR-7](https://www.php-fig.org/psr/psr-7/):

- `application/json`
- `application/x-www-form-urlencoded`
- `application/xml`
- `text/xml`

Finally, the application is run and the response is emitted.

## Dependency Injection

In this example, we have split the dependency injection definitions into
multiple files to allow for better organisation. This is not a requirement and
you can define all of your dependencies in a single file if you prefer.

In this example, we have a logical split for the settings, database
configuration and repositories. When then have an additional file
(`dependencies.php`) for miscellaneous dependencies.

### app/settings.php

The `app/settings.php` file contains the settings for the application. These
are contained within an instance of the `App\Application\Settings\Settings`
class that allows for easy access to the settings. This class is then added to
the container.

### app/dependencies.php

The `app/dependencies.php` file acts as a "misc" location for dependencies that
need to be added to the container. In this example, we add the logger to the
container.

When defining dependencies, it's possible to access the container within the
definition. In this example, we access the settings from the container to
define the logger.

### app/database.php

The `app/database.php` file contains the database connection settings. In this
example, we use PDO to connect to a MySQL database. The database connection
is then added to the container.

In this example, we access environment variables directly to get the database
credentials. However, you could access the settings from the container similar
to how the logger is accessed in the `dependencies.php` file.

### app/repositories.php

The `app/repositories.php` file contains the dependency injection definitions
for the repository classes. In this example, we have a `UserDatabaseRepository`
that is added to the container.

One of the key benefits of using a dependency injection container is that you
can define the dependencies once and then use them throughout the application.
This means that if you need to change the implementation of a dependency, you
only need to change it in one place. Which is a key advantage for testability.

## Middleware

In this example, we add the `App\Application\Middleware\SessionMiddleware` to
the application. This middleware is responsible for starting the session and
adding it to the request when `HTTP_AUTHORIZATION` header is present.

Global middleware is added to the application in the `app/middleware.php`
file. However, middleware can also be added to individual routes or route
groups, for example:

```php
$app->get('/foo', function ($request, $response, $args) {
    // Do something
})->add(SessionMiddleware::class);
```

For further information on middleware, see the
[Slim documentation](https://www.slimframework.com/docs/v4/concepts/middleware.html).

## Routes

In this example, we have a `routes` directory that contains multiple route
files. This allows for better organisation of grouped routes. For example,
you could have an `api.php` file that contains the API routes and a
`web.php` file that contains the web routes.

To add a single route, you can use the following syntax:

```php
$app->get('/foo', function ($request, $response, $args) {
    // Do something
});
```

To add a group of routes, you can use the following syntax:

```php
$app->group('/foo', function (RouteCollectorProxyInterface $group)
{
    $group->get('/bar', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
    {
       // Do something
       // Route: /foo/bar
    });

    $group->group('/baz', function (RouteCollectorProxyInterface $group)
    {
        $group->get('/buzz', function ($request, $response, $args) {
            // Do something
            // Route: /foo/baz/buzz
        });
    });
});
```

For further information on routing, see the
[Slim documentation](https://www.slimframework.com/docs/v4/objects/routing.html).
