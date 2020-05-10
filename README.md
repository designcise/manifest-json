# Designcise\ManifestJson

[![codecov](https://codecov.io/gh/designcise/manifest-json/branch/master/graph/badge.svg)](https://codecov.io/gh/designcise/manifest-json)
[![Build Status](https://travis-ci.org/designcise/manifest-json.svg?branch=master)](https://travis-ci.org/designcise/manifest-json)

PHP `manifest.json` parser/loader.

## Example

```php
use Designcise\ManifestJson\Loader;

$loader = new Loader('path/to/manifest/');

$entry = $loader->get('entry.js');

$manifest = $loader->getAll();

$css = $loader->getByType('css');

$images = $loader->getByTypes(['jpg', 'png']);
```

## API

### get(string $key): string

Gets the `manifest.json` metadata for the specified `$key`.

### getAll(): array

Get all entries in the `manifest.json` file as an array.

### getByType(string $type): array

Gets all files with the specified file extension (for e.g. `css`, `js`, etc.) as an array.

### getAllByTypes(array $types): array

Gets all files with the specified file extensions (e.g. `['css', 'js']`, etc.) as an array.

## Tests

To run the tests you can use the following commands:

| Command          | Type            |
| ---------------- |:---------------:|
| `composer test`  | PHPUnit tests   |
| `composer style` | CodeSniffer     |
| `composer md`    | MessDetector    |
| `composer check` | PHPStan         |

## Contributing

* File issues at https://github.com/designcise/manifest-json/issues
* Issue patches to https://github.com/designcise/manifest-json/pulls

## License

Please see [License File](LICENSE.md) for licensing information.