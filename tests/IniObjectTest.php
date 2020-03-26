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
            'test.ini'
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
            get_class($this->object->getSection('global ini'))
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

    public function test__toString(): void
    {
        $this->assertEquals(
            strlen($this->object->__toString()),
            strlen(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'test.ini'))
        ); // PHP_EOL file windows
    }

    public function testSave(): void
    {
        $this->object->save(__DIR__ . DIRECTORY_SEPARATOR . 'dummy.ini');
        $this->assertFileExists(__DIR__ . DIRECTORY_SEPARATOR . 'dummy.ini');
    }

//    public function getSectionName(): void
//    {
//        $this->assertEquals(
//            $this->object->getSection('global ini')->getName(),
//            'global ini'
//        );
//    }

    public static function tearDownAfterClass(): void
    {
        @unlink(__DIR__ . DIRECTORY_SEPARATOR . 'dummy.ini');
    }
}
