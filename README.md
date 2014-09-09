# Traitor

[![Build Status]](https://travis-ci.org/IcecaveStudios/traitor)
[![Test Coverage]](https://coveralls.io/r/IcecaveStudios/traitor?branch=develop)
[![SemVer]](http://semver.org)

**Traitor** is a PHP library for dynamically generating classes that implement certain interfaces by use of traits. The
primary purpose is for creating test mocks.

* Install via [Composer](http://getcomposer.org) package [icecave/traitor](https://packagist.org/packages/icecave/traitor)
* Read the [API documentation](http://icecavestudios.github.io/traitor/artifacts/documentation/api/)

## Example

The example below creates an instance of an object that implements the `SomeInterface` interface by using the `SomeTrait`
trait. It is passed the values `1`, `2`, and `3` as constructor parameters.

```php
use Icecave\Traitor\Traitor;

$instance = Traitor::create()
    ->implements_(SomeInterface::CLASS)
    ->use_(SomeTrait::CLASS)
    ->instance(1, 2, 3);
```

<!-- references -->
[Build Status]: http://img.shields.io/travis/IcecaveStudios/traitor/develop.svg
[Test Coverage]: http://img.shields.io/coveralls/IcecaveStudios/traitor/develop.svg
[SemVer]: http://img.shields.io/:semver-1.0.0-green.svg
