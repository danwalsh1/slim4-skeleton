# Data

Working with data within the Slim 4 framework involves four main components:

- **Interfaces** - These are object interfaces that define the methods that a
  repository class must implement.
- **Repositories** - These are classes that implement the interfaces and provide
  the functionality to interact with the data source.
- **Services** - These are classes that provide the business logic for the
  application. They use the repositories to interact with the data source.
- **Models** - These are classes that represent the data in the application.

The purpose of using interfaces and repositories is to abstract the data source
from the rest of the application. This allows for easier testing and changing of
the data source in the future.

## Interfaces

The interfaces are located in the `src/Application/Domain/Interfaces` directory.
The methods defined in these interfaces can be up to the developer, but typically
they will include the CRUD operations for the data source.

As a general rule in this skeleton, methods use the standard operation names for:

- `create(...)`
- `update($id, ...)`
- `delete($id)`

For "read" operations, there is the standard mix of using "find" vs "get". In this
skeleton, "find" is used when the result is, in theory, unknown. For example, when
searching for a user by email address, the method is `findByEmail($email)`.
"Get" is used when the result should be known to exist. For example, when getting
all users, the method is `getAll()`.

## Repositories

The repositories are located in the
`src/Application/Infrastructure/Persistence/Repositories` directory. These classes
implement the interfaces and provide the functionality to interact with the data
source.

The repositories are responsible for the actual interaction with the data source.
This can be a database, an API, a file system, or any other data source.

It is important to note that the repositories should not contain any business logic.
They should only contain the logic to interact with the data source.

## Services

The services are located in the `src/Application/Domain/Services` directory.
These classes provide the business logic for the application. They use the
repositories to interact with the data source.

The services are responsible for the business logic of the application. This can
include things like validation, calculations, and other logic that is specific to
the application.

## Models

The models are located in the `src/Application/Domain/Models` directory. These
classes represent the data in the application.

The models are responsible for defining the structure of the data in the
application. This can include things like properties, relationships, and other
data-related information.

In this skeleton, all models are also required to implement the `JsonSerializable`
interface. This allows for easy serialization of the models to JSON for returning
to the client and better support for logging tools.

## Factories

Factories are used to create instances of classes. In this skeleton, factories are
used to create instances of models from arrays of data.

The factories are located in the `src/Application/Domain/Factories` directory.

In this skeleton, all factories that create models extend the `BaseModelFactory`
class. This provides a mechanism for checking the provided data for required fields
in order to successfully create the model instance and throws a `ValidationException`
if any required fields are missing.

## End-to-End Examples

The following examples show how the data components work together in the context
of a simple user management system.

### List all users

The route `[GET] /api/users` is requested. This then invokes the `ListUsersAction`
class, which extends the `UserAction` class.

In the `UserAction` class, the `UserService` class is instantiated.

In the `action()` method of the `ListUsersAction` class, the `getAll()` method of
the `UserService` class is called. This then calls the `getAll()` method of the
`UserRepositoryInterface` interface.

In the `app/repositories.php` file, the `UserDatabaseRepository` class is bound to the
`UserRepositoryInterface` interface. This means that when the `getAll()` method is
called on the `UserRepositoryInterface` interface, the `getAll()` method of the
`UserDatabaseRepository` class is actually called.

The `UserDatabaseRepository` class then interacts with the data source to get all
users. This data is then returned back to the `UserService` class.

The `UserService` class then loops through the data and creates instances of the
`User` model using the `UserFactory` class.

The `User` models are then returned to the `ListUsersAction` class, which then
returns the data to the client through an `Action` class that will serialize the
data to JSON.

### Create a new user

The route `[POST] /api/users` is requested with the required data in the request
body. This then invokes the `CreateUserAction` class, which extends the
`UserAction` class.

In the `UserAction` class, the `UserService` class is instantiated.

In the `action()` method of the `CreateUserAction` class, the `getFormData()`
method of the `UserAction` class is called to get the form data from the request.

The `CreateUserAction` class then uses the `UserFactory` class to create a new
instance of the `User` model from the form data.

The `CreateUserAction` class then calls the `create()` method of the
`UserService` class, passing in the new `User` model.

The `UserService` class then creates a new `UserValidator` instance and validates
the new `User` model. If the validation fails, a `ValidationException` is thrown.

If the validation passes, the `create()` method of the `UserRepositoryInterface`
interface is called, passing in the new `User` model.

In the `app/repositories.php` file, the `UserDatabaseRepository` class is bound
to the `UserRepositoryInterface` interface. This means that when the `create()`
method is called on the `UserRepositoryInterface` interface, the `create()`
method of the `UserDatabaseRepository` class is actually called.

The `UserDatabaseRepository` class then interacts with the data source to create
the new user. The new user `id` is then returned back up the chain to the
`CreateUserAction` class.

The `CreateUserAction` class then uses the `findById()` method of the
`UserService` class to get the new user by `id`. This data is then returned to
the client through an `Action` class that will serialize the data to JSON.

If the new user cannot be found, an `Exception` is thrown and caught by the
`HttpErrorHandler` class, which then returns an error response to the client.

