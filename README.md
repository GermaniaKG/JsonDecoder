<img src="https://static.germania-kg.com/logos/ga-logo-2016-web.svgz" width="250px">

------


# JsonDecoder

**Decode JSON in strings and PSR-7 messages. Supports *JsonException* on older PHP**.

[![Packagist](https://img.shields.io/packagist/v/germania-kg/jsondecoder.svg?style=flat)](https://packagist.org/packages/germania-kg/jsondecoder)
[![PHP version](https://img.shields.io/packagist/php-v/germania-kg/jsondecoder.svg)](https://packagist.org/packages/germania-kg/jsondecoder)
[![Build Status](https://img.shields.io/travis/GermaniaKG/JsonDecoder.svg?label=Travis%20CI)](https://travis-ci.org/GermaniaKG/JsonDecoder)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/JsonDecoder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/JsonDecoder/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/JsonDecoder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/JsonDecoder/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/GermaniaKG/JsonDecoder/badges/build.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/JsonDecoder/build-status/master)



## Installation with Composer

```bash
$ composer require germania-kg/jsondecoder
```



## Usage

The callable **JsonDecoder** accepts the same parameters than described in PHP's documentation on [json_decode](https://www.php.net/manual/en/function.json-decode.php). Additionally, it accepts some widely-used kinds of data:

- `Psr\Http\Message\MessageInterface`
- `Psr\Http\Message\ResponseInterface`
- `Psr\Http\Message\StreamInterface`
- `string`

```php
use Germania\JsonDecoder\JsonDecoder;

// Let:
// $response instance of ResponseInterface,
// $body instance of StreamInterface
$body = $response->getBody();
$str = $body->__toString();

try {
  $decoder = new JsonDecoder;
  $decoded = $decoder( $response );  
  $decoded = $decoder( $body );  
  $decoded = $decoder( $str );    
}
catch ( \JsonException $e)
{
  echo $e->getMessage();
}

```



## Exceptions

When the decoding fails, a **\JsonException** (mind the global namespace!) will be thrown. This class is provided by **[Symfony's Polyfill PHP 7.3](https://github.com/symfony/polyfill-php73)** for those lacking PHP 7.3.





## Issues

See [full issues list.][i0]

[i0]: https://github.com/GermaniaKG/JsonDecoder/issues



## Development

```bash
$ git clone https://github.com/GermaniaKG/JsonDecoder.git
$ cd JsonDecoder
$ composer install
```



## Unit tests

Either copy `phpunit.xml.dist` to `phpunit.xml` and adapt to your needs, or leave as is. Run [PhpUnit](https://phpunit.de/) test or composer scripts like this:

```bash
$ composer test
# or
$ vendor/bin/phpunit
```