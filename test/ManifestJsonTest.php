<?php

/**
 * BitFrame Framework (https://www.bitframephp.com)
 *
 * @author    Daniyal Hamid
 * @copyright Copyright (c) 2017-2020 Daniyal Hamid (https://designcise.com)
 * @license   https://bitframephp.com/about/license MIT License
 */

namespace BitFrame\Renderer\Test;

use PHPUnit\Framework\TestCase;
use Designcise\ManifestJson\ManifestJson;

/**
 * @covers \Designcise\ManifestJson\ManifestJson
 */
class ManifestJsonTest extends TestCase
{
    private ManifestJson $loader;

    /** @var string */
    private const ASSETS_DIR = __DIR__ . '/Asset/';

    public function setUp(): void
    {
        $this->loader = new ManifestJson(self::ASSETS_DIR);
    }

    public function testHas(): void
    {
        $this->assertTrue($this->loader->has('vendors~blog~index.js'));
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
        $this->assertSame($expected, $this->loader->get($original));
    }

    public function testGetAll(): void
    {
        $expected = [
            'vendors~blog~index.js' => 'js/vendors~blog~index.ef6c1e1242cc8cdc5891.js',
            'blog.css' => 'css/blog.ef6c1e1242cc8cdc5891.css',
            'blog.js' => 'js/blog.ef6c1e1242cc8cdc5891.js',
            'index.css' => 'css/index.ef6c1e1242cc8cdc5891.css',
            'index.js' => 'js/index.ef6c1e1242cc8cdc5891.js',
            'vendors~blog.css' => 'css/vendors~blog.ef6c1e1242cc8cdc5891.css',
            'vendors~blog.js' => 'js/vendors~blog.ef6c1e1242cc8cdc5891.js',
            'img/bg.jpg' => 'img/bg.jpg',
            'img/logo.png' => 'img/logo.png'
        ];

        $this->assertSame($expected, $this->loader->getAll());
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
                    'img/logo.png' => 'img/logo.png'
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
        $this->assertSame($expected, $this->loader->getAllByType($type));
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
        $this->assertSame($expected, $this->loader->getAllByTypes($types));
    }
}
