<?php namespace Motokraft\Object\Traits;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://github.com/motokraft/object
 */

trait ObjectTrait
{
    private $data = [];

    function loadArray(array $data)
    {
        foreach($data as $name => $value)
        {
            $this->set($name, $value);
        }
    }

    function set(string $name, $value) : static
    {
        if(property_exists($this, $name))
        {
            $this->{$name} = $value;
            return $this;
        }

        $this->data[$name] = $value;
        return $this;
    }

    function get(string $name, $default = null)
    {
        if(!$this->has($name))
        {
            return $default;
        }

        if(property_exists($this, $name))
        {
            return $this->{$name};
        }

        return $this->data[$name];
    }

    function remove(string $name) : bool
    {
        if(!$this->has($name))
        {
            return false;
        }

        if(property_exists($this, $name))
        {
            unset($this->{$name});
            return true;
        }

        unset($this->data[$name]);
        return true;
    }

    function has(string $name) : bool
    {
        if(property_exists($this, $name))
        {
            return isset($this->{$name});
        }

        return isset($this->data[$name]);
    }

    function getArray() : array
    {
        return $this->data;
    }

    function __get(string $name)
    {
        return $this->get($name);
    }

    function __set(string $name, $value)
    {
        $this->set($name, $value);
    }
}