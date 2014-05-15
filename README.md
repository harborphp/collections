# Data Container

A simple Data Container class and Trait.

The Trait implements all of the methods for the following interfaces:

*  ArrayAccess
* Countable
* IteratorAggregate

The `Harbor\DataContainer\DataContainer` class simply `use`s the Trait and implements those interfaces.

## Installation

```
composer require "harbor/data-container:1.0.*"
```

## Usage

### As Trait

``` php
<?php

class Foo
{
    use Harbor\DataContainer\DataContainerTrait;
}

// Use it
$foo = new Foo();
$foo->bar = 'bar';
```

### As Object

``` php
<?php

use Harbor\DataContainer\DataContainer;

class Foo
{
    protected $data;

    public function __construct(DataContainer $data)
    {
        $this->data = $data;
    }
}

// Use it
$foo = new Foo(new DataContainer());
```
