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

    public function testLoadToArray(): void
    {
        $class = new IniObject(
            'test.ini',
            [
                'test'  => [
                    'param1' => 1,
                    'param2' => 2,
                ],
                'test2' => [
                    'param3' => 3,
                    'param4' => 4,
                ],
            ]
        );
        $this->assertContainsOnlyInstancesOf(
            Section::class,
            [
                $class->getSection('test'),
                $class->getSection('test2'),
            ]
        );
    }

    public function testToArray(): void
    {
        $this->assertIsArray($this->object->toArray());
        $this->assertArrayHasKey('global', $this->object->toArray());
        $this->assertCount(3, $this->object->toArray());
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
        $class = $this->object->getSection('lns');
        $this->assertContainsOnlyInstancesOf(Section::class, [$class]);
        $this->expectException(InvalidArgumentException::class);
        $this->object->addSection('lns', $data);
    }

    public function testRemoveSection(): void
    {
        $this->object->removeSection('global');
        $this->expectException(InvalidArgumentException::class);
        $this->object->removeSection('Dummy');
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

    public function testSave(): void
    {
        $this->object->save(__DIR__ . DIRECTORY_SEPARATOR . 'dummy.ini');
        $this->assertFileExists(__DIR__ . DIRECTORY_SEPARATOR . 'dummy.ini');
        $this->expectException(InvalidArgumentException::class);
        $object = new IniObject(
            __DIR__ . DIRECTORY_SEPARATOR . 'test.ini',
            [],
            [
                'strict'    => true,
                'overwrite' => false,
            ]
        );
        $object->save(__DIR__ . DIRECTORY_SEPARATOR . 'dummy.ini');
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
        $object = $this->object->getSection('lac');
        $this->assertEquals(
            $object->getParam('mtu'),
            1410
        );
        $object->remove('mtu');
        $this->expectException(InvalidArgumentException::class);
        $object->remove('mmm');
    }

    public function testAddSectionParamByName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $object = $this->object->getSection('lac');
        $object->add('mts', 111);
        $this->assertEquals(
            $object->getParam('mts'),
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
