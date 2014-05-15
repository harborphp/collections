<?php

namespace Harbor\DataContainer;

use ArrayAccess;
use Countable;
use IteratorAggregate;

class DataContainer implements ArrayAccess, Countable, IteratorAggregate
{
    use DataContainerTrait;
}
