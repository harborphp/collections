# Collections

A Generic Collections implementation.

The Trait implements all of the methods for the following interfaces:

* Harbor\Collections\CollectionInterface
* ArrayAccess
* Countable
* IteratorAggregate
* JsonSerializable

The `Harbor\Collections\Collection` class simply `use`s the Trait and implements those interfaces, plus adds a constructor.

## Installation

```
composer require "harbor/collections:2.0.*"
```

## Requirements

PHP 5.4+

## Usage

### As Trait

``` php
<?php

class Foo implements CollectionInterface, ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    use Harbor\Collections\CollectionTrait;
}

// Use it
$foo = new Foo();
$foo->bar = 'bar';
```

### As Object

``` php
<?php

use Harbor\Collections\CollectionInterface;

class Foo
{
    protected $data;

    public function __construct(CollectionInterface $data)
    {
        $this->data = $data;
    }
}

// Use it
$foo = new Foo(new Collection());
```
