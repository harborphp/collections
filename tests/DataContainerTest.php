<?php

namespace Harbor\DataContainer\Tests;

use Harbor\DataContainer\DataContainer;

class DataContainerTest extends \PHPUnit_Framework_TestCase
{
    protected function getPreLoadedContainer()
    {
        $container = new DataContainer();
        $class = new \ReflectionClass($container);
        $dataProperty = $class->getProperty('data');
        $dataProperty->setAccessible(true);
        $dataProperty->setValue($container, [
            'foo' => 'bar',
            'baz' => 'foo',
        ]);

        return $container;
    }
    public function testImplementsInterfaces()
    {
        $container = new DataContainer();

        $this->assertInstanceOf('ArrayAccess', $container);
        $this->assertInstanceOf('Countable', $container);
        $this->assertInstanceOf('IteratorAggregate', $container);
    }

    public function testSetSingle()
    {
        $container = new DataContainer();

        $container->set('foo', 'bar');
        $this->assertAttributeEquals(['foo' => 'bar'], 'data', $container);
    }

    public function testSetArray()
    {
        $data = ['foo' => 'bar', 'baz' => 'yay'];

        $container = new DataContainer();
        $container->set($data);

        $this->assertAttributeEquals($data, 'data', $container);
    }

    public function testHas()
    {
        $container = $this->getPreLoadedContainer();

        $this->assertTrue($container->has('foo'));
        $this->assertFalse($container->has('yummy'));
    }

    public function testGet()
    {
        $container = $this->getPreLoadedContainer();

        $this->assertEquals('bar', $container->get('foo'));
        $this->assertEquals('i_am_default', $container->get('yummy', 'i_am_default'));
    }

    public function testRemove()
    {
        $container = $this->getPreLoadedContainer();

        $container->remove('foo');
        $this->assertAttributeEquals(['baz' => 'foo'], 'data', $container);
    }

    public function testToArray()
    {
        $container = $this->getPreLoadedContainer();

        $this->assertEquals(['foo' => 'bar', 'baz' => 'foo'], $container->toArray());
    }

    public function testJson()
    {
        $container = $this->getPreLoadedContainer();

        $this->assertEquals('{"foo":"bar","baz":"foo"}', $container->toJson());
    }

    public function testMagicGet()
    {
        $mock = $this->getMock('Harbor\DataContainer\DataContainer', array('get'));
        $mock->expects($this->once())
             ->method('get')
             ->with($this->equalTo('foo'));
        $mock->foo;
    }

    public function testMagicSet()
    {
        $mock = $this->getMock('Harbor\DataContainer\DataContainer', array('set'));
        $mock->expects($this->once())
             ->method('set')
             ->with($this->equalTo('foo'),
                    $this->equalTo('HAHA'));

        $mock->foo = 'HAHA';
    }

    public function testGetIterator()
    {
        $container = $this->getPreLoadedContainer();

        $this->assertInstanceOf('Iterator', $container->getIterator());
    }
    public function testCount()
    {
        $container = $this->getPreLoadedContainer();

        $this->assertEquals(2, $container->count());
    }

    public function testOffsetExists()
    {
        $mock = $this->getMock('Harbor\DataContainer\DataContainer', array('has'));
        $mock->expects($this->once())
             ->method('has')
             ->with($this->equalTo('foo'));

        $mock->offsetExists('foo');
    }

    public function testOffsetSet()
    {
        $mock = $this->getMock('Harbor\DataContainer\DataContainer', array('set'));
        $mock->expects($this->once())
             ->method('set')
             ->with($this->equalTo('foo'),
                    $this->equalTo('bar'));

        $mock->offsetSet('foo', 'bar');
    }

    public function testOffsetGet()
    {
        $mock = $this->getMock('Harbor\DataContainer\DataContainer', array('get'));
        $mock->expects($this->once())
            ->method('get')
            ->with($this->equalTo('foo'));

        $mock->offsetGet('foo');
    }

    public function testOffsetUnset()
    {
        $mock = $this->getMock('Harbor\DataContainer\DataContainer', array('remove'));
        $mock->expects($this->once())
            ->method('remove')
            ->with($this->equalTo('foo'));

        $mock->offsetUnset('foo');
    }

}

