<?php

namespace Harbor\DataContainer;

use ArrayIterator;
use DomainException;
use InvalidArgumentException;

/**
 * Trait DataContainerTrait
 *  implements ArrayAccess, Countable, IteratorAggregate
 */
trait DataContainerTrait
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Checks if the container has the given key.
     * @param  string $key The key to check.
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Gets the given key from the container, or returns the default if it does not
     * exist.
     * @param  string $key     The key to get.
     * @param  mixed  $default Default value to return.
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    /**
     * Sets the given key in the container.
     * @param mixed $key   The key to set
     * @param mixed $value The value.
     * @return $this
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Returns the data as an array.
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Returns the data as JSON.
     * @param int $options
     * @param int $depth
     * @throws \DomainException
     * @return array
     */
    public function toJson($options = 0, $depth = 512)
    {
        $encodedData = json_encode($this->data, $options, $depth);

        // @codeCoverageIgnoreStart
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encodedData;
            case JSON_ERROR_DEPTH:
                throw new DomainException('JSON Error: Maximum stack depth exceeded');
            case JSON_ERROR_STATE_MISMATCH:
                throw new DomainException('JSON Error: Underflow or the modes mismatch');
            case JSON_ERROR_CTRL_CHAR:
                throw new DomainException('JSON Error: Unexpected control character found');
            case JSON_ERROR_SYNTAX:
                throw new DomainException('JSON Error: Syntax error, malformed JSON');
            case JSON_ERROR_UTF8:
                throw new DomainException('JSON Error: Malformed UTF-8 characters, possibly incorrectly encoded');
            default:
                throw new DomainException('JSON Error: Unknown error');
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Removes the given key from the container.
     * @param  string $key The key to forget.
     * @return void
     */
    public function remove($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Merges an array, or any object that implements a toArray method,
     * into the current data set. The data being merged in wins on conflicts.
     * @param  mixed $data
     * @return $this
     */
    public function merge($data)
    {
        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        if (! is_array($data)) {
            throw new InvalidArgumentException('Cannot merge a value that is not an Array or an object implementing a toArray method.');
        }

        $this->data = $data + $this->data;

        return $this;
    }

    /**
     * Magic method to allow object-type semantics for the container.
     * @param  string $key The key to get.
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Magic method to allow object-type semantics for the container.
     * @param  string $key   The key to set.
     * @param  mixed  $value Value to set
     * @return mixed
     */
    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * IteratorAggregate: Gets an Iterator for the container.
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Countable: Gets the number of items in the container.
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * ArrayAccess: Checks if the key exists.
     * @param  string $key The key to check.
     * @return boolean
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * ArrayAccess: Unsets the given key.
     * @param  string $key The key to unset.
     * @return boolean
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * ArrayAccess: Gets the given key.
     * @param  string $key The key to get.
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * ArrayAccess: Sets the given offset.
     * @param  string $key   The key to set.
     * @param  mixed  $value The value to set.
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }
}
