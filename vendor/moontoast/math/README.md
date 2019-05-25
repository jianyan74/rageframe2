# Moontoast Math Library

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

Moontoast\Math is useful for working with integers that are larger than
(or may become larger than, through mathematical computations) PHP's max
integer value for a given system. On 64-bit systems, this number is
9223372036854775807. On 32-bit systems, it is 2147483647. When overflowing
this boundary, PHP turns the number into a float, reducing precision (see
the PHP manual entry for [Integers][php-integers]).

Moontoast\Math provides an easy-to-use wrapper around the bcmath extension,
allowing one to perform mathematical calculations on numeric strings,
going well outside the integer range of the system and maintaining arbitrary
precision for more precise calculations.

Moontoast\Math requires PHP 5.3+ and the [bcmath extension][].

## Installation

The preferred method of installation is via [Composer][]:

```
composer require moontoast/math
```

## Examples

```php
$bn = new \Moontoast\Math\BigNumber('9,223,372,036,854,775,808');
$bn->multiply(35);

var_dump($bn->getValue());
var_dump($bn->convertToBase(16));
```

This produces the following output:

```
string(21) "322818021289917153280"
string(18) "118000000000000000"
```

You might want to use BigNumber to work with a UUID, which is an unsigned
128-bit integer. For example:

```php
$uuid = \Moontoast\Math\BigNumber::convertToBase10('ff6f8cb0c57d11e19b210800200c9a66', 16);
```

This utility converts the UUID from hexadecimal (base 16) representation to
a string representation of the unsigned 128-bit integer in decimal (base 10).
You may now create a BigNumber object with it to perform calculations, if you
wish.

```php
$bn = new \Moontoast\Math\BigNumber($uuid);
echo $bn; // 339532337419071774305803111643925486182
```

## License

Copyright &copy; 2013-2016 Moontoast, Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.


[bcmath extension]: http://php.net/bcmath
[php-integers]: http://php.net/manual/en/language.types.integer.php
[composer]: https://getcomposer.org/

[badge-source]: https://img.shields.io/badge/source-moontoast/math-blue.svg?style=flat-square
[badge-release]: https://img.shields.io/packagist/v/moontoast/math.svg?style=flat-square
[badge-license]: https://img.shields.io/github/license/ramsey/moontoast-math.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/ramsey/moontoast-math/master.svg?style=flat-square
[badge-coverage]: https://img.shields.io/coveralls/ramsey/moontoast-math/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/moontoast/math.svg?style=flat-square

[source]: https://github.com/ramsey/moontoast-math
[release]: https://packagist.org/packages/moontoast/math
[license]: https://github.com/ramsey/moontoast-math/blob/master/LICENSE
[build]: https://travis-ci.org/ramsey/moontoast-math
[coverage]: https://coveralls.io/r/ramsey/moontoast-math?branch=master
[downloads]: https://packagist.org/packages/moontoast/math
