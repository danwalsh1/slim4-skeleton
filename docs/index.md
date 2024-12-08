# Slim 4 - Skeleton Application

This is a skeleton application for Slim 4 that you can use as a starting
point for your projects.

## Design Origin

The design of this skeleton is a mixture of learnings I've picked up from years
of development experience with the Slim Framework and my own personal
preferences.

This skeleton is designed to be bare bones for the most part, but with some
of the basic examples of how to use the Slim Framework. This example does
not include usage of an ORM due to my personal preference and the fact that
there are many skeletons available that show how to use an ORM with Slim 4.

## Features of This Skeleton

- **Dependency Injection Container**: Uses the [PSR-11](https://www.php-fig.org/psr/psr-11/)
  compatible container from PHP-DI.
- **Error Handling**: Provides an example for error and shutdown handling.
- **Logging**: Uses [Monolog](https://github.com/Seldaek/monolog) for logging.
- **Routing**: Uses the Slim 4 routing component.
- **Database Example**: Provides an example of a database connection and usage
  via [PDO](https://www.php.net/manual/en/intro.pdo.php).
- **PHP Stan**: Includes a `phpstan.neon` configuration file for running
  [PHP Stan](https://phpstan.org/) and the code is compliant with PHP Stan's
  [level 7](https://phpstan.org/user-guide/rule-levels).
- **PHP Code Sniffer**: Includes a `phpcs.xml` configuration file for running
  [PHP Code Sniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer/) and
  the code is compliant with the [PSR-12](https://www.php-fig.org/psr/psr-12/)
  standard.
