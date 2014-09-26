<?php

namespace Harbor\Collections;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;

class Collection implements CollectionInterface, ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    use CollectionTrait;

    /**
     * Constructor
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }
}
