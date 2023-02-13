<?php namespace Motokraft\Object\Traits;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://github.com/motokraft/object
 */

use \Motokraft\Object\BaseObject;

trait ObjectTrait
{
    private array $data = [];

    function loadArray(array $data) : void
    {
        foreach($data as $name => $value)
        {
            $this->set($name, $value);
        }
    }

    function loadObject($object) : void
    {
        if($data = $object->getCombine())
        {
            $this->loadArray($data);
        }
    }

    function loadString(string $result) : void
    {
        $data = json_decode($result);

        if(json_last_error() === JSON_ERROR_NONE)
        {
            $this->loadArray((array) $data);
        }
    }

    function mergeArray(array $data) : void
    {
        $data = new BaseObject($data);
        $this->mergeObject($data);
    }

    function mergeObject($object) : void
    {
        foreach($object->getKeys() as $name)
        {
            if(!$object->has($name))
            {
                continue;
            }

            $new_val = $object->get($name);
            $this->set($name, $new_val);
        }
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

    function map(callable $callback, bool $preserve = true) : static
    {
        $result = new static;

        if(!$data = $this->getCombine())
        {
            return $result;
        }

        foreach($data as $index => $item)
        {
            $value = $callback($item, $index);

            if($preserve)
            {
                $result[$index] = $value;
            }
            else
            {
                $result->append($value);
            }
        }

        return $result;
    }

    function filter(callable $callback, bool $preserve = true) : static
    {
        $result = new static;

        if(!$data = $this->getCombine())
        {
            return $result;
        }

        foreach($data as $index => $item)
        {
            if(!$callback($item, $index))
            {
                continue;
            }

            if($preserve)
            {
                $result[$index] = $item;
            }
            else
            {
                $result->append($value);
            }
        }

        return $result;
    }

    function __get(string $name) : mixed
    {
        return $this->get($name);
    }

    function __set(string $name, $value) : void
    {
        $this->set($name, $value);
    }
}