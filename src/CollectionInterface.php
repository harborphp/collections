<?php
namespace Harbor\Collections;


/**
 * Trait ArrayCollectionTrait
 *  implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
 */
interface CollectionInterface
{
    /**
     * Checks if the container has the given key.
     * @param  string $key The key to check.
     * @return boolean
     */
    public function has($key);

    /**
     * Gets the given key from the container, or returns the default if it does not
     * exist.
     * @param  string $key     The key to get.
     * @param  mixed  $default Default value to return.
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Sets the given key in the container.
     * @param mixed $key   The key to set
     * @param mixed $value The value.
     * @return $this
     */
    public function set($key, $value = null);

    /**
     * Returns the data as an array.
     * @return array
     */
    public function toArray();

    /**
     * Returns the data as JSON.
     * @param int $options
     * @param int $depth
     * @throws \DomainException
     * @return array
     */
    public function toJson($options = 0, $depth = 512);

    /**
     * Removes the given key from the container.
     * @param  string $key The key to forget.
     * @return void
     */
    public function remove($key);

    /**
     * Merges an array, or any object that implements a toArray method,
     * into the current data set. The data being merged in wins on conflicts.
     * @param  mixed $data
     * @return $this
     */
    public function merge($data);

    /**
     * Implements the JsonSerializable interface so it can be used with
     * json_encode().
     * @return string
     */
    public function jsonSerialize();

    /**
     * Magic method to allow object-type semantics for the container.
     * @param  string $key The key to get.
     * @return mixed
     */
    public function __get($key);

    /**
     * Magic method to allow object-type semantics for the container.
     * @param  string $key   The key to set.
     * @param  mixed  $value Value to set
     * @return mixed
     */
    public function __set($key, $value);

    /**
     * IteratorAggregate: Gets an Iterator for the container.
     * @return \ArrayIterator
     */
    public function getIterator();

    /**
     * Countable: Gets the number of items in the container.
     * @return int
     */
    public function count();

    /**
     * ArrayAccess: Checks if the key exists.
     * @param  string $key The key to check.
     * @return boolean
     */
    public function offsetExists($key);

    /**
     * ArrayAccess: Unsets the given key.
     * @param  string $key The key to unset.
     * @return boolean
     */
    public function offsetUnset($key);

    /**
     * ArrayAccess: Gets the given key.
     * @param  string $key The key to get.
     * @return mixed
     */
    public function offsetGet($key);

    /**
     * ArrayAccess: Sets the given offset.
     * @param  string $key   The key to set.
     * @param  mixed  $value The value to set.
     * @return void
     */
    public function offsetSet($key, $value);
}
