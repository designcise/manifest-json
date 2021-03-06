# Designcise\ManifestJson

[![codecov](https://codecov.io/gh/designcise/manifest-json/branch/master/graph/badge.svg)](https://codecov.io/gh/designcise/manifest-json)
[![Build Status](https://travis-ci.org/designcise/manifest-json.svg?branch=master)](https://travis-ci.org/designcise/manifest-json)

PHP `manifest.json` parser/loader.

## Example

```php
use Designcise\ManifestJson\ManifestJson;

$mainfest = new ManifestJson('path/to/manifest/');

$entry = $mainfest->get('entry.js');
$metadata = $mainfest->getAll();
$css = $mainfest->getAllByType('css');
$images = $mainfest->getAllByTypes(['jpg', 'png']);
$js = $mainfest->getAllByKey('*.js');
```

## API

### `static from(string $dir): self`

Static method to load `manifest.json` file from specified directory; for example:

```php
ManifestJson::from('path/to/manifest/');
```

### `get(string $key): string`

Gets the `manifest.json` metadata for the specified `$key`.

### `getAll(): array`

Get all entries in the `manifest.json` file as an array.

### `getAllByType(string $type): array`

Gets all files with the specified file extension (for e.g. `css`, `js`, etc.) as an array.

### `getAllByTypes(array $types): array`

Gets all files with the specified file extensions (e.g. `['css', 'js']`, etc.) as an array.

### `getAllByKey(string $key): array`

Gets all files that match the specified key. Can optionally have wildcard using asterisk (e.g. `*.js`, `*index*`, etc.).

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