<?php

require __DIR__.'/../vendor/autoload.php';

use \org\bovigo\vfs\vfsStream;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Abimo\Config
     */
    public $config;

    /**
     * @var array
     */
    public $paths = [
        ['/dir/', '/dir'],
        ['/dir//', '/dir'],
        ['dir/', 'dir'],
    ];

    /**
     * @var array
     */
    public $structures = [
        ['dir', 'file', 'return ["key1" => "value1", "key2" => "value2", "key3" => "value3", "key4" => "value4"];', null, null],
        ['dir', 'file', 'return ["key1" => "value1", "key2" => "value2", "key3" => "value3", "key4" => "value4"];', 'key1', 'value1'],
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        $this->config = new \Abimo\Config();
    }

    /**
     * @return array
     */
    public function pathProvider()
    {
        return $this->paths;
    }

    /**
     * @dataProvider pathProvider
     */
    public function testPath($pathGiven, $pathExpected)
    {
        $this->config->path($pathGiven);
        $this->assertEquals($pathExpected, $this->config->path);
    }

    /**
     * @return array
     */
    public function structureProvider()
    {
        return $this->structures;
    }

    /**
     * @dataProvider structureProvider
     */
    public function testGet($directory, $file, $content, $key, $value)
    {
        $root = vfsStream::setup($directory);
        vfsStream::newFile(ucfirst($file).'.php')
            ->at($root)
            ->setContent('<?php '.$content);

//        print_r(vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure());

        $this->assertEquals(null === $key ? eval($content) : $value, $this->config
            ->path(vfsStream::url($directory))
            ->get(ucfirst($file), $key)
        );
    }

    /**
     * @dataProvider structureProvider
     */
    public function testSet($directory, $file, $content, $key, $value)
    {
        if (null !== $key || null !== $value) {
//            $this->markTestSkipped('Key and value must be set.');
        }

        $file = ucfirst($file);

        $this->config->set($file, $key, $value);

        $this->assertEquals($value, $this->config->data[$file][$key]);
    }
}

