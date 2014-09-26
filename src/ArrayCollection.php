<?php

namespace Harbor\Collections;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;

class ArrayCollection implements Collection, ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    use ArrayCollectionTrait;
}
