<?php

/**
 * BitFrame Framework (https://www.bitframephp.com)
 *
 * @author    Daniyal Hamid
 * @copyright Copyright (c) 2017-2020 Daniyal Hamid (https://designcise.com)
 * @license   https://bitframephp.com/about/license MIT License
 */

namespace BitFrame\Renderer\Test;

use ReflectionObject;
use PHPUnit\Framework\TestCase;
use Designcise\ManifestJson\ManifestJson;
use RuntimeException;
use InvalidArgumentException;

/**
 * @covers \Designcise\ManifestJson\ManifestJson
 */
class ManifestJsonTest extends TestCase
{
    private ManifestJson $manifest;

    /** @var string */
    private const ASSETS_DIR = __DIR__ . '/Asset/';

    public function setUp(): void
    {
        $this->manifest = new ManifestJson(self::ASSETS_DIR);
    }

    public function testStaticFrom(): void
    {
        $manifest = ManifestJson::from(self::ASSETS_DIR);
        $this->assertTrue($manifest->has('vendors~blog~index.js'));
    }

    public function testInstantiatingWithNonExistentPathShouldThrowException(): void
    {
        $this->expectException(RuntimeException::class);

        new ManifestJson('/non-existent/path/');
    }

    public function testHas(): void
    {
        $this->assertTrue($this->manifest->has('vendors~blog~index.js'));
    }

    public function testGetShouldThrowExceptionForNonExistentKey(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->manifest->get('non-existent.js');
    }

    public function metadataByKeyProvider(): array
    {
        return [
            'js' => ['vendors~blog~index.js', 'js/vendors~blog~index.ef6c1e1242cc8cdc5891.js'],
            'css' => ['blog.css', 'css/blog.ef6c1e1242cc8cdc5891.css'],
            'img' => ['img/logo.png', 'img/logo.png']
        ];
    }

    /**
     * @dataProvider metadataByKeyProvider
     *
     * @param string $original
     * @param string $expected
     */
    public function testGet(string $original, string $expected): void
    {
        $this->assertSame($expected, $this->manifest->get($original));
    }

    public function testGetAll(): void
    {
        $expected = [
            'vendors~blog~index.js' => 'js/vendors~blog~index.ef6c1e1242cc8cdc5891.js',
            'blog.css' => 'css/blog.ef6c1e1242cc8cdc5891.css',
            'blog.js' => 'js/blog.ef6c1e1242cc8cdc5891.js',
            'index.css' => 'css/index.ef6c1e1242cc8cdc5891.css',
            'index.js' => 'js/index.ef6c1e1242cc8cdc5891.js',
            'blog~index.js' => 'js/blog~index.ef6c5464563231231.js',
            'vendors~blog.css' => 'css/vendors~blog.ef6c1e1242cc8cdc5891.css',
            'vendors~blog.js' => 'js/vendors~blog.ef6c1e1242cc8cdc5891.js',
            'img/bg.jpg' => 'img/bg.jpg',
            'img/logo.png' => 'img/logo.png'
        ];

        $this->assertSame($expected, $this->manifest->getAll());
    }

    public function typeProvider(): array
    {
        return [
            'css files' => [
                'css',
                [
                    'blog.css' => 'css/blog.ef6c1e1242cc8cdc5891.css',
                    'index.css' => 'css/index.ef6c1e1242cc8cdc5891.css',
                    'vendors~blog.css' => 'css/vendors~blog.ef6c1e1242cc8cdc5891.css',
                ]
            ],
            'js files' => [
                'js',
                [
                    'vendors~blog~index.js' => 'js/vendors~blog~index.ef6c1e1242cc8cdc5891.js',
                    'blog.js' => 'js/blog.ef6c1e1242cc8cdc5891.js',
                    'index.js' => 'js/index.ef6c1e1242cc8cdc5891.js',
                    'blog~index.js' => 'js/blog~index.ef6c5464563231231.js',
                    'vendors~blog.js' => 'js/vendors~blog.ef6c1e1242cc8cdc5891.js',
                ]
            ],
            'jpg images' => [
                'jpg',
                [
                    'img/bg.jpg' => 'img/bg.jpg',
                ]
            ],
            'png images' => [
                'png',
                [
                    'img/logo.png' => 'img/logo.png',
                ]
            ],
        ];
    }

    /**
     * @dataProvider typeProvider
     *
     * @param string $type
     * @param array $expected
     */
    public function testGetAllByType(string $type, array $expected): void
    {
        $this->assertSame($expected, $this->manifest->getAllByType($type));
    }

    /**
     * @runInSeparateProcess
     *
     * @throws \ReflectionException
     */
    public function testGetAllByTypeGetsFromCachedResultsOnRepeatCalls(): void
    {
        $expected = ['foobar.css' => 'foo.bar1234.css'];

        $manifestReflection = new ReflectionObject($this->manifest);
        $metadata = $manifestReflection->getProperty('typedMetadata');
        $metadata->setAccessible(true);
        $metadata->setValue($this->manifest, ['css' => $expected]);

        $this->assertSame($expected, $this->manifest->getAllByType('css'));
    }

    public function typesProvider(): array
    {
        return [
            'css and js files' => [
                ['css', 'js'],
                [
                    'css' => [
                        'blog.css' => 'css/blog.ef6c1e1242cc8cdc5891.css',
                        'index.css' => 'css/index.ef6c1e1242cc8cdc5891.css',
                        'vendors~blog.css' => 'css/vendors~blog.ef6c1e1242cc8cdc5891.css',
                    ],
                    'js' => [
                        'vendors~blog~index.js' => 'js/vendors~blog~index.ef6c1e1242cc8cdc5891.js',
                        'blog.js' => 'js/blog.ef6c1e1242cc8cdc5891.js',
                        'index.js' => 'js/index.ef6c1e1242cc8cdc5891.js',
                        'blog~index.js' => 'js/blog~index.ef6c5464563231231.js',
                        'vendors~blog.js' => 'js/vendors~blog.ef6c1e1242cc8cdc5891.js',
                    ],
                ]
            ],
            'jpg & png images' => [
                ['jpg', 'png'],
                [
                    'jpg' => [
                        'img/bg.jpg' => 'img/bg.jpg',
                    ],
                    'png' => [
                        'img/logo.png' => 'img/logo.png',
                    ],
                ]
            ],
            'png & jpg images reverse order' => [
                ['png', 'jpg'],
                [
                    'png' => [
                        'img/logo.png' => 'img/logo.png',
                    ],
                    'jpg' => [
                        'img/bg.jpg' => 'img/bg.jpg',
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider typesProvider
     *
     * @param array $types
     * @param array $expected
     */
    public function testGetAllByTypes(array $types, array $expected): void
    {
        $this->assertSame($expected, $this->manifest->getAllByTypes($types));
    }

    public function keysProvider(): array
    {
        return [
            'css files' => [
                '*blog*.css',
                [
                    'blog.css' => 'css/blog.ef6c1e1242cc8cdc5891.css',
                    'vendors~blog.css' => 'css/vendors~blog.ef6c1e1242cc8cdc5891.css',
                ]
            ],
            'js files' => [
                '*index*.js',
                [
                    'vendors~blog~index.js' => 'js/vendors~blog~index.ef6c1e1242cc8cdc5891.js',
                    'index.js' => 'js/index.ef6c1e1242cc8cdc5891.js',
                    'blog~index.js' => 'js/blog~index.ef6c5464563231231.js',
                ]
            ],
            'mix files' => [
                '*blog*',
                [
                    'vendors~blog~index.js' => 'js/vendors~blog~index.ef6c1e1242cc8cdc5891.js',
                    'blog.css' => 'css/blog.ef6c1e1242cc8cdc5891.css',
                    'blog.js' => 'js/blog.ef6c1e1242cc8cdc5891.js',
                    'blog~index.js' => 'js/blog~index.ef6c5464563231231.js',
                    'vendors~blog.css' => 'css/vendors~blog.ef6c1e1242cc8cdc5891.css',
                    'vendors~blog.js' => 'js/vendors~blog.ef6c1e1242cc8cdc5891.js',
                ]
            ],
            'exact match' => [
                'vendors~blog~index.js',
                [
                    'vendors~blog~index.js' => 'js/vendors~blog~index.ef6c1e1242cc8cdc5891.js',
                ]
            ],
            'jpg images' => [
                '*.jpg',
                [
                    'img/bg.jpg' => 'img/bg.jpg',
                ]
            ],
            'png images' => [
                '*.png',
                [
                    'img/logo.png' => 'img/logo.png',
                ]
            ],
            'ending with letter' => [
                '*g',
                [
                    'img/bg.jpg' => 'img/bg.jpg',
                    'img/logo.png' => 'img/logo.png',
                ]
            ],
        ];
    }

    /**
     * @dataProvider keysProvider
     *
     * @param string $key
     * @param array $expected
     */
    public function testGetAllByKey(string $key, array $expected): void
    {
        $this->assertSame($expected, $this->manifest->getAllByKey($key));
    }
}
