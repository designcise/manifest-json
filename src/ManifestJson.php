<?php

/**
 * @author    Daniyal Hamid
 * @copyright Copyright (c) 2020-2024 Daniyal Hamid (https://designcise.com)
 *
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace Designcise\ManifestJson;

use RuntimeException;
use JsonException;
use InvalidArgumentException;

use function file_get_contents;
use function realpath;
use function json_decode;
use function array_filter;
use function pathinfo;
use function str_replace;
use function preg_quote;
use function preg_match;

use const JSON_THROW_ON_ERROR;
use const ARRAY_FILTER_USE_KEY;
use const PATHINFO_EXTENSION;

class ManifestJson
{
    private string $fileName = 'manifest.json';

    private array $metadata;

    private array $typedMetadata = [];

    public static function from(string $dirOrFile): static
    {
        return new static($dirOrFile);
    }

    public function __construct(string $dirOrFile)
    {
        $dir = $dirOrFile;

        if (str_ends_with($dirOrFile, '.json')) {
            $this->fileName = basename($dirOrFile);
            $dir = dirname($dirOrFile);
        }

        $filePath = $this->createFilePathByDirectory($dir);
        $this->metadata = $this->getParsedMetadata($filePath);
    }

    public function has(string $key): bool
    {
        return isset($this->metadata[$key]);
    }

    public function get(string $key): string
    {
        return $this->metadata[$key] ?? throw new InvalidArgumentException("Manifest key \"$key\" does not exist.");
    }

    public function getAll(): array
    {
        return $this->metadata;
    }

    public function getAllByType(string $type): array
    {
        if (isset($this->typedMetadata[$type])) {
            return $this->typedMetadata[$type];
        }

        $this->typedMetadata[$type] = array_filter($this->metadata, fn (string $key) => (
            $type === pathinfo($key, PATHINFO_EXTENSION)
        ), ARRAY_FILTER_USE_KEY);

        return $this->typedMetadata[$type];
    }

    public function getAllByTypes(array $types): array
    {
        $manifest = [];

        foreach ($types as $type) {
            $manifest[$type] = $this->getAllByType($type);
        }

        return $manifest;
    }

    public function getAllByKey(string $key): array
    {
        return $this->filterByKey($key, fn (string $currKey, string $pattern) => preg_match($pattern, $currKey));
    }

    public function getAllByKeyBasename(string $key): array
    {
        return $this->filterByKey($key, fn (string $currKey, string $pattern) => (
            preg_match($pattern, basename($currKey))
        ));
    }

    private function getParsedMetadata(string $filePath): array
    {
        try {
            $fileContents = file_get_contents($filePath);

            return json_decode($fileContents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException("Error parsing JSON: {$e->getMessage()}");
        }
    }

    private function createFilePathByDirectory(string $dir): string
    {
        $dir = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $dir);
        $filePath = realpath($dir . DIRECTORY_SEPARATOR . $this->fileName);

        return $filePath ?: throw new RuntimeException("\"{$this->fileName}\" does not exist.");
    }

    private function filterByKey(string $key, callable $filterFn): array
    {
        $pattern = preg_quote($key, '/');
        $pattern = '/^' . str_replace('\*', '.*', $pattern) . '$/i';

        return array_filter($this->metadata, fn (string $currKey) => (
            $filterFn($currKey, $pattern)
        ), ARRAY_FILTER_USE_KEY);
    }
}
