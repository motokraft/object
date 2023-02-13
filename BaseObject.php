<?php namespace Motokraft\Object;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://github.com/motokraft/object
 */

class BaseObject implements \ArrayAccess, \IteratorAggregate
{
    use Traits\ObjectTrait;

    function __construct(array|object|string $data = [])
    {
        if(is_array($data))
        {
            $this->loadArray($data);
        }
        else if(is_string($data))
        {
            $this->loadString($data);
        }
        else if(is_object($data))
        {
            $this->loadArray((array) $data);
        }
        else if($data instanceof static)
        {
            $this->mergeObject($data);
        }
    }

    function offsetExists(mixed $name) : bool
    {
        return $this->has($name);
    }

    function offsetGet(mixed $name) : mixed
    {
        return $this->get($name);
    }

    function offsetSet(mixed $name, mixed $value) : void
    {
        $this->set($name, $value);
    }

    function offsetUnset(mixed $name) : void
    {
        if($this->has($name))
        {
            unset($this->data[$name]);
        }
    }

    function getIterator() : \Traversable
    {
        return new \ArrayIterator($this->getCombine());
    }
}