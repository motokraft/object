<?php namespace Motokraft\Object\Traits;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://github.com/motokraft/object
 */

use \Motokraft\Object\BaseObject;

trait ObjectTrait
{
    private $data = [];

    function loadArray(array $data)
    {
        foreach($data as $name => $value)
        {
            $this->set($name, $value);
        }

        return $this;
    }

    function loadObject($object)
    {
        if($data = $object->getCombine())
        {
            $this->loadArray($data);
        }

        return $this;
    }

    function loadString(string $result)
    {
        $data = json_decode($result);

        if(json_last_error() === JSON_ERROR_NONE)
        {
            $this->loadArray((array) $data);
        }

        return $this;
    }

    function mergeArray(array $data)
    {
        $data = new BaseObject($data);
        return $this->mergeObject($data);
    }

    function mergeObject($object)
    {
        if(!$keys = $object->getKeys())
        {
            return $this;
        }

        foreach($keys as $name)
        {
            if(!$object->has($name))
            {
                continue;
            }

            $new_val = $object->get($name);
            $this->set($name, $new_val);
        }

        return $this;
    }

    function getKeys() : array
    {
        $data = get_object_vars($this);

        if(isset($data['data']))
        {
            unset($data['data']);
        }

        $result = (array) array_keys($data);
        
        if($keys = array_keys($this->data))
        {
            $result = array_merge($result, $keys);
        }

        $filter = function (string $name) : bool
        {
            return (strpos($name, '_') !== 0);
        };

        return array_filter($result, $filter);
    }

    function getValues() : array
    {
        $map_item = function (string $name)
        {
            return $this->get($name);
        };

        $keys = (array) $this->getKeys();
        return array_map($map_item, $keys);
    }

    function getCombine() : array
    {
        $keys = $this->getKeys();
        $vals = $this->getValues();

        return array_combine($keys, $vals);
    }

    function getBaseObject() : BaseObject
    {
        $data = $this->getCombine();
        return new BaseObject($data);
    }

    function set(string $name, $value)
    {
        if(property_exists($this, $name))
        {
            $this->{$name} = $value;
        }
        else
        {
            $this->data[$name] = $value;
        }

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
        if(property_exists($this, $name))
        {
            unset($this->{$name});
            return true;
        }

        if(isset($this->data[$name]))
        {
            unset($this->data[$name]);
            return true;
        }

        return false;
    }

    function has(string $name) : bool
    {
        if(property_exists($this, $name))
        {
            return isset($this->{$name});
        }

        return isset($this->data[$name]);
    }

    function empty(string $name) : bool
    {
        if(property_exists($this, $name))
        {
            return empty($this->{$name});
        }

        return empty($this->data[$name]);
    }

    function getArray() : array
    {
        return $this->data;
    }

    function filter(callable $func) : array
    {
        $data = $this->getCombine();
        $result = [];

        foreach($data as $name => $value)
        {
            if(!$func($name, $value))
            {
                continue;
            }

            $result[$name] = $value;
        }

        return $result;
    }

    function map(callable $func) : array
    {
        $data = $this->getCombine();
        $result = [];

        foreach($data as $name => $value)
        {
            $result[$name] = $func($name, $value);
        }

        return $result;
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