<img src="https://static.germania-kg.com/logos/ga-logo-2016-web.svgz" width="250px">

------


# JsonDecoder



## Installation with Composer

```bash
$ composer require germania-kg/jsondecoder
```



## Usage

The callable **JsonDecoder** accepts the same parameters than described in PHP's documentation on [json_decode](https://www.php.net/manual/en/function.json-decode.php).

Additionally, it accepts some other widely-used variable types as well:

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