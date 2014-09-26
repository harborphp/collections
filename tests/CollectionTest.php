<?php

namespace Harbor\Collections\Tests;

use Harbor\Collections\Collection;

class DummyObject
{
    public function toArray()
    {
        return [
            'foo' => 'overwrite'
        ];
    }
}

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    protected function getPreLoadedContainer()
    {
        $collection = new Collection([
            'foo' => 'bar',
            'baz' => 'foo',
        ]);

        return $collection;
    }

    public function testImplementsInterfaces()
    {
        $collection = new Collection();

        $this->assertInstanceOf('Harbor\Collections\CollectionInterface', $collection);
        $this->assertInstanceOf('ArrayAccess', $collection);
        $this->assertInstanceOf('Countable', $collection);
        $this->assertInstanceOf('IteratorAggregate', $collection);
    }

    public function testConstructor()
    {
        $items = ['foo' => 'bar'];
        $collection = new Collection($items);

        $this->assertAttributeEquals($items, 'items', $collection);
    }

    public function testSetSingle()
    {
        $collection = new Collection();

        $collection->set('foo', 'bar');
        $this->assertAttributeEquals(['foo' => 'bar'], 'items', $collection);
    }

    public function testSetArray()
    {
        $items = ['foo' => 'bar', 'baz' => 'yay'];

        $collection = new Collection();
        $collection->set($items);

        $this->assertAttributeEquals($items, 'items', $collection);
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
        $this->assertAttributeEquals(['baz' => 'foo'], 'items', $collection);
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

        $this->assertAttributeEquals($expected, 'items', $collection);
    }

    public function testMergeWithObjectWithToArray()
    {
        $collection = $this->getPreLoadedContainer();

        $collection->merge(new DummyObject);

        $expected = [
            'foo' => 'overwrite',
            'baz' => 'foo',
        ];

        $this->assertAttributeEquals($expected, 'items', $collection);
    }

    public function testMergeWithCollection()
    {
        $collection = new Collection([
            'foo' => 'bar',
        ]);

        $collection2 = new Collection([
            'foo' => 'baz',
        ]);

        $collection->merge($collection2);

        $expected = [
            'foo' => 'baz'
        ];

        $this->assertAttributeEquals($expected, 'items', $collection);
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
        $collection->set('test', new DummyObject);

        $this->assertEquals(['foo' => 'bar', 'baz' => 'foo', 'test' => ['foo' => 'overwrite']], $collection->toArray());
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
        $mock = $this->getMock('Harbor\Collections\Collection', ['get']);
        $mock->expects($this->once())
             ->method('get')
             ->with($this->equalTo('foo'));
        $mock->foo;
    }

    public function testMagicSet()
    {
        $mock = $this->getMock('Harbor\Collections\Collection', ['set']);
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
        $mock = $this->getMock('Harbor\Collections\Collection', ['has']);
        $mock->expects($this->once())
             ->method('has')
             ->with($this->equalTo('foo'));

        $mock->offsetExists('foo');
    }

    public function testOffsetSet()
    {
        $mock = $this->getMock('Harbor\Collections\Collection', ['set']);
        $mock->expects($this->once())
             ->method('set')
             ->with($this->equalTo('foo'),
                    $this->equalTo('bar'));

        $mock->offsetSet('foo', 'bar');
    }

    public function testOffsetGet()
    {
        $mock = $this->getMock('Harbor\Collections\Collection', ['get']);
        $mock->expects($this->once())
            ->method('get')
            ->with($this->equalTo('foo'));

        $mock->offsetGet('foo');
    }

    public function testOffsetUnset()
    {
        $mock = $this->getMock('Harbor\Collections\Collection', ['remove']);
        $mock->expects($this->once())
            ->method('remove')
            ->with($this->equalTo('foo'));

        $mock->offsetUnset('foo');
    }

}

