# Routes

Routes are the way to define the URL paths that your application will respond to.
In this skeleton, routes are defined in logically separated files in the `routes`
directory. This allows for better organisation of grouped routes. For example,
you could have an `api.php` file that contains the API routes and a `web.php`
file that contains the web routes.

## Route Syntax

It is recommended to declare routes within a file in the `routes` directory.
This allows for better organisation of your routes.

For further information on routing, see the
[Slim documentation](https://www.slimframework.com/docs/v4/objects/routing.html).

### Single Route

To add a single route, you can use the following syntax:

```php
$app->get('/foo', function ($request, $response, $args) {
    // Do something
});
```

The second parameter of the specific HTTP method function (`get()` above) is
callable and needs to accept the request, response, and any route arguments.
The route arguments are populated with any route placeholders that are
defined in the route URL (e.g. `/foo/{id}` would populate the `id` index in the
`$args` associative array).

### Grouped Routes

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

The above example also shows how to nest groups of routes.

## Actions

In the Slim 4 framework, the action of a route is a callable that accepts the
request, response, and any route arguments. The action is responsible for
returning a response to the client.

The action can be a class method, a closure, or a class that implements the
`__invoke()` method. The latter is the option used in this skeleton, with
classes in the `src/Application/Actions` directory that extend the `Action`
class that includes the `__invoke()` method.

### Base Action Class

The base action class is located in `src/Application/Actions/Action.php`. This
class is responsible for providing the `__invoke()` method that is required for
actions in Slim 4.

Actions that ultimately extend from this class can be used as the callable for
routes. For example:

```php
$app->get('/foo', \App\Application\Actions\FooAction::class);
```

The above example would call the `__invoke()` method on the `FooAction` class
when the `/foo` route is requested. This then calls the `action()` method on
the `FooAction` class.

The base action class also provides a `logger` property that is an instance of
`Psr\Log\LoggerInterface`. This allows for logging to be done in the actions.

It also provides a number of helper methods that can be used in the actions:

- `respond()`: This method is used to return a JSON response to the client.
- `respondWithData()`: This method is used to return a JSON response with data
  to the client.
- `resolveArg()`: This method is used to get a route argument from the request.
- `getFormData()`: This method is used to get form data from the request.
- `getFormDataArray()`: This method is used to get form data from the request
  as an array.

### Grouped Actions

Related actions are best grouped in their own directory. In the example, there
is a `src/Application/Actions/User` directory that contains actions related to
users.

It contains an abstract base `UserAction` class that extends the base `Action`
class. This is used to provide common functionality to all user-related
actions, such as instantiating a `UserService` class for interacting with the
user data.

There are then individual actions that extend the `UserAction` class. These
actions can then be used as the callable for routes. For example, to get
a specific user by ID, the `ViewUserAction` class is used:

```php
class ViewUserAction extends UserAction
{
    protected function action(): ResponseInterface
    {
        $userId = (int)$this->resolveArg('id'); // Action::resolveArg()

        $user = $this->userService->findById($userId); // UserAction::userService

        $this->logger->info("User of id `{$userId}` was viewed."); // Action::logger

        return $this->respondWithData($user); // Action::respondWithData()
    }
}
```

This class can then be used as the callable for a route:

```php
$app->get('/user/{id}', \App\Application\Actions\User\ViewUserAction::class);
```

This would call the `__invoke()` method from the `Action` class when the
`/user/{id}` route is requested. This then calls the `action()` method on the
`ViewUserAction` class.

### Error Action

When an unhanded exception is thrown in the application, the
`HttpErrorHandler` class creates a new `ActionError` object, setting the type
and message of the error, and the status code. This object is then passed into
a new instance of the `ActionPayload` class that is then JSON encoded and
returned to the client.

An example output of an error response would be:

```json
{
  "statusCode": 422,
  "error": {
    "type": "VALIDATION_ERROR",
    "description": "Invalid user properties",
    "errors": [
      {
        "First Name": "First Name must have a length between 1 and 50"
      },
      {
        "Last Name": "Last Name must contain only letters (a-z)"
      }
    ]
  }
}
```
