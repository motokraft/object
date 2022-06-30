<?php namespace Motokraft\Object;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://github.com/motokraft/object
 */

class BaseObject implements \ArrayAccess, \Countable, \Serializable
{
    use Traits\ObjectTrait;

    function __construct(array $data = [])
    {
        if(!empty($data))
        {
            $this->loadArray($data);
        }
    }

    function offsetExists($name)
    {
        return $this->has($name);
    }

    function offsetGet($name)
    {
        return $this->get($name);
    }

    function offsetSet($name, $value)
    {
        $this->set($name, $value);
    }

    function offsetUnset($name)
    {
        if(!$this->has($name))
        {
            return false;
        }

        unset($this->data[$name]);
        return true;
    }

    function count()
    {
        return count($this->data);
    }

    function serialize() : string
    {
        return serialize($this->data);
    }

    function unserialize(string $data)
    {
        if(!$data = unserialize($data))
        {
            return false;
        }

        $this->loadArray($data);
    }
}