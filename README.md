# Designcise\ManifestJson

[![CI](https://github.com/designcise/manifest-json/actions/workflows/ci.yml/badge.svg)](https://github.com/designcise/manifest-json/actions/workflows/ci.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/0edb897f673eb368fb73/maintainability)](https://codeclimate.com/github/designcise/manifest-json/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/0edb897f673eb368fb73/test_coverage)](https://codeclimate.com/github/designcise/manifest-json/test_coverage)

PHP `manifest.json` parser/loader.

## Installing

```shell
composer require designcise/manifest-json
```

> [!NOTE]  
> Requires PHP 8.0 or later.

## Example

```php
use Designcise\ManifestJson\ManifestJson;

$manifest = new ManifestJson('path/to/manifest/'); // defaults to `manifest.json`
// or $manifest = new ManifestJson('path/to/manifest/my-manifest.json');

$entry = $manifest->get('entry.js');
$metadata = $manifest->getAll();
$css = $manifest->getAllByType('css');
$images = $manifest->getAllByTypes(['jpg', 'png']);
$js = $manifest->getAllByKey('*.js');
$criticalJs = $manifest->getAllByKeyBasename('critical-*.js');
```

## API

### `static from(string $dirOrFile): self`

Static method to load `manifest.json` file from specified directory. If only directory is specified then it is assumed that file name is `manifest.json` by default, otherwise the specified name is used. For example:

```php
ManifestJson::from('path/to/manifest/'); // defaults to `manifest.json`
ManifestJson::from('path/to/manifest/my-manifest.json');
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
