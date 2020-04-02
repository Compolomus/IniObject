<?php

declare(strict_types=1);

namespace Compolomus\IniObject;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Exception;

class IniObjectTest extends TestCase
{
    protected $object;

    protected function setUp(): void
    {
        $this->object = new IniObject(
            __DIR__ . DIRECTORY_SEPARATOR . 'test.ini'
//            ,[
//                'strict'    => true,
//                'overwrite' => false,
//            ]
        );
    }

    public function test__construct(): void
    {
        try {
            $this->assertIsObject($this->object);
            $this->assertInstanceOf(IniObject::class, $this->object);
        } catch (Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function testGetSection(): void
    {
        $this->assertEquals(
            Section::class,
            get_class($this->object->getSection('global'))
        );
        $this->expectException(InvalidArgumentException::class);
        $this->object->getSection('Dummy');
    }

    public function testAddSection(): void
    {
        $data = ['exclusive' => 'yes'];
        $this->object->addSection('lns', $data);
        $this->assertInstanceOf(Section::class, $this->object->getSection('lns'));
        $this->expectException(InvalidArgumentException::class);
        $this->object->addSection('lns', $data);
    }

    public function testRemoveSection(): void
    {
        $data = ['exclusive' => 'yes'];
        $this->object->addSection('lns', $data);
        $this->expectException(InvalidArgumentException::class);
        $this->object->removeSection('Dummy');
        $this->object->removeSection('lns');
        $this->expectException(InvalidArgumentException::class);
        $this->object->getSection('lns');
    }

    public function testUpdateSection(): void
    {
        $data = ['exclusive' => 'yes'];
        $this->object->addSection('lns', $data);
        $data += ['ppp debug' => 'yes'];
        $this->object->updateSection('lns', $data);
        $this->expectException(InvalidArgumentException::class);
        $this->object->updateSection('Dummy', $data);
    }

//    public function test__toString(): void
//    {
//        if (PHP_OS_FAMILY === 'Windows') {
//            $this->markTestIncomplete('PHP_EOL file windows'); //markTestSkipped
//        }
//        $this->assertEquals(
//            strlen($this->object->__toString()),
//            strlen(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'test.ini'))
//        );
//    }

    public function testSave(): void
    {
        $this->object->save(__DIR__ . DIRECTORY_SEPARATOR . 'dummy.ini');
        $this->assertFileExists(__DIR__ . DIRECTORY_SEPARATOR . 'dummy.ini');
    }

    public function testGetSectionParamByName(): void
    {
        $this->assertEquals(
            $this->object->getSection('lac')->getParam('mtu'),
            1410
        );
    }

    public function testUpdateSectionParamByName(): void
    {
        $this->object->getSection('lac')->update('mtu', 1409);
        $this->assertEquals(
            $this->object->getSection('lac')->getParam('mtu'),
            1409
        );
        $this->expectException(InvalidArgumentException::class);
        $this->object->getSection('lac')->update('mts', 1409);
    }

    public function testRemoveSectionParamByName(): void
    {
        $this->assertEquals(
            $this->object->getSection('lac')->getParam('mtu'),
            1410
        );
        $this->object->getSection('lac')->remove('mtu');
        $this->expectException(InvalidArgumentException::class);
        $this->object->getSection('lac')->remove('mtu');
    }

    public function testAddSectionParamByName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->object->getSection('lac')->remove('mts');
        $this->object->getSection('lac')->add('mts', 111);
        $this->assertEquals(
            $this->object->getSection('lac')->getParam('mts'),
            111
        );
        $this->expectException(InvalidArgumentException::class);
        $this->object->getSection('lac')->add('mts', 222);
    }

    public static function tearDownAfterClass(): void
    {
        @unlink(__DIR__ . DIRECTORY_SEPARATOR . 'dummy.ini');
    }
}
