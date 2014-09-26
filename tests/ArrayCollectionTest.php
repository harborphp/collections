<?php

namespace Harbor\Collections\Tests;

use Harbor\Collections\ArrayCollection;

class DummyObject
{
    public function toArray()
    {
        return [
            'foo' => 'overwrite'
        ];
    }
}

class ArrayCollectionTest extends \PHPUnit_Framework_TestCase
{
    protected function getPreLoadedContainer()
    {
        $collection = new ArrayCollection();
        $class = new \ReflectionClass($collection);
        $dataProperty = $class->getProperty('data');
        $dataProperty->setAccessible(true);
        $dataProperty->setValue($collection, [
            'foo' => 'bar',
            'baz' => 'foo',
        ]);

        return $collection;
    }
    public function testImplementsInterfaces()
    {
        $collection = new ArrayCollection();

        $this->assertInstanceOf('Harbor\Collections\Collection', $collection);
        $this->assertInstanceOf('ArrayAccess', $collection);
        $this->assertInstanceOf('Countable', $collection);
        $this->assertInstanceOf('IteratorAggregate', $collection);
    }

    public function testSetSingle()
    {
        $collection = new ArrayCollection();

        $collection->set('foo', 'bar');
        $this->assertAttributeEquals(['foo' => 'bar'], 'data', $collection);
    }

    public function testSetArray()
    {
        $data = ['foo' => 'bar', 'baz' => 'yay'];

        $collection = new ArrayCollection();
        $collection->set($data);

        $this->assertAttributeEquals($data, 'data', $collection);
    }

    public function testHas()
    {
        $collection = $this->getPreLoadedContainer();

        $this->assertTrue($collection->has('foo'));
        $this->assertFalse($collection->has('yummy'));
    }

    public function testGet()
    {
        $collection = $this->getPreLoadedContainer();

        $this->assertEquals('bar', $collection->get('foo'));
        $this->assertEquals('i_am_default', $collection->get('yummy', 'i_am_default'));
    }

    public function testRemove()
    {
        $collection = $this->getPreLoadedContainer();

        $collection->remove('foo');
        $this->assertAttributeEquals(['baz' => 'foo'], 'data', $collection);
    }

    public function testMergeWithArray()
    {
        $collection = $this->getPreLoadedContainer();

        $collection->merge([
            'foo' => 'overwrite',
            'new' => 'value'
        ]);

        $expected = [
            'foo' => 'overwrite',
            'baz' => 'foo',
            'new' => 'value',
        ];

        $this->assertAttributeEquals($expected, 'data', $collection);
    }

    public function testMergeWithObjectWithToArray()
    {
        $collection = $this->getPreLoadedContainer();

        $collection->merge(new DummyObject);

        $expected = [
            'foo' => 'overwrite',
            'baz' => 'foo',
        ];

        $this->assertAttributeEquals($expected, 'data', $collection);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMergeWithInvalidValue()
    {
        $collection = $this->getPreLoadedContainer();

        $collection->merge(new \StdClass);
    }

    public function testToArray()
    {
        $collection = $this->getPreLoadedContainer();

        $this->assertEquals(['foo' => 'bar', 'baz' => 'foo'], $collection->toArray());
    }

    public function testJson()
    {
        $collection = $this->getPreLoadedContainer();

        $this->assertEquals('{"foo":"bar","baz":"foo"}', $collection->toJson());
    }

    public function testJsonSerializable()
    {
        $collection = $this->getPreLoadedContainer();

        $this->assertEquals('{"foo":"bar","baz":"foo"}', json_encode($collection));
    }

    public function testMagicGet()
    {
        $mock = $this->getMock('Harbor\Collections\ArrayCollection', array('get'));
        $mock->expects($this->once())
             ->method('get')
             ->with($this->equalTo('foo'));
        $mock->foo;
    }

    public function testMagicSet()
    {
        $mock = $this->getMock('Harbor\Collections\ArrayCollection', array('set'));
        $mock->expects($this->once())
             ->method('set')
             ->with($this->equalTo('foo'),
                    $this->equalTo('HAHA'));

        $mock->foo = 'HAHA';
    }

    public function testGetIterator()
    {
        $collection = $this->getPreLoadedContainer();

        $this->assertInstanceOf('Iterator', $collection->getIterator());
    }

    public function testCount()
    {
        $collection = $this->getPreLoadedContainer();

        $this->assertEquals(2, $collection->count());
    }

    public function testOffsetExists()
    {
        $mock = $this->getMock('Harbor\Collections\ArrayCollection', array('has'));
        $mock->expects($this->once())
             ->method('has')
             ->with($this->equalTo('foo'));

        $mock->offsetExists('foo');
    }

    public function testOffsetSet()
    {
        $mock = $this->getMock('Harbor\Collections\ArrayCollection', array('set'));
        $mock->expects($this->once())
             ->method('set')
             ->with($this->equalTo('foo'),
                    $this->equalTo('bar'));

        $mock->offsetSet('foo', 'bar');
    }

    public function testOffsetGet()
    {
        $mock = $this->getMock('Harbor\Collections\ArrayCollection', array('get'));
        $mock->expects($this->once())
            ->method('get')
            ->with($this->equalTo('foo'));

        $mock->offsetGet('foo');
    }

    public function testOffsetUnset()
    {
        $mock = $this->getMock('Harbor\Collections\ArrayCollection', array('remove'));
        $mock->expects($this->once())
            ->method('remove')
            ->with($this->equalTo('foo'));

        $mock->offsetUnset('foo');
    }

}

