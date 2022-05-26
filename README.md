# Designcise\ManifestJson

[![codecov](https://codecov.io/gh/designcise/manifest-json/branch/master/graph/badge.svg?token=XUM5LZlXOz)](https://codecov.io/gh/designcise/manifest-json)
[![Build Status](https://travis-ci.com/designcise/manifest-json.svg?branch=master)](https://travis-ci.com/designcise/manifest-json)

PHP `manifest.json` parser/loader.

## Installing

```shell
composer require designcise/manifest-json
```

## Example

```php
use Designcise\ManifestJson\ManifestJson;

$manifest = new ManifestJson('path/to/manifest/');

$entry = $manifest->get('entry.js');
$metadata = $manifest->getAll();
$css = $manifest->getAllByType('css');
$images = $manifest->getAllByTypes(['jpg', 'png']);
$js = $manifest->getAllByKey('*.js');
$criticalJs = $manifest->getAllByKeyBasename('critical-*.js');
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

Gets all files that match the specified key. It looks for a full match in the manifest entries' key. It can optionally have wildcard using asterisk (e.g. `*.js`, `*index*`, etc.).

### `getAllByKeyBasename(string $key): array`

Gets all files that match the trailing name component of path in the manifest entry's key. It can optionally have wildcard using asterisk (e.g. `*.js`, `index*`, etc.).

## Tests

To run the tests you can use the following commands:

| Command             | Type             |
| ------------------- |:----------------:|
| `composer test`     | PHPUnit tests    |
| `composer style`    | CodeSniffer      |
| `composer style-fix`| CodeSniffer Fixer|
| `composer md`       | MessDetector     |
| `composer check`    | PHPStan          |

## Contributing

* File issues at https://github.com/designcise/manifest-json/issues
* Issue patches to https://github.com/designcise/manifest-json/pulls

## License

Please see [License File](LICENSE.md) for licensing information.
