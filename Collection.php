<?php namespace Motokraft\Object;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://github.com/motokraft/object
 */

class Collection extends \ArrayIterator
{
    function map(callable $callback, bool $preserve = true) : static
    {
        $result = new static;

        if(!$items = $this->getArrayCopy())
        {
            return $result;
        }

        foreach($items as $key => $item)
        {
            $value = $callback($item, $key);

            if($preserve)
            {
                $result->set($key, $value);
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

        if(!$items = $this->getArrayCopy())
        {
            return $result;
        }

        foreach($items as $key => $item)
        {
            if(!$callback($item, $key))
            {
                continue;
            }

            if($preserve)
            {
                $result->set($key, $item);
            }
            else
            {
                $result->append($value);
            }
        }

        return $result;
    }

    function each(callable $callback) : void
    {
        $items = $this->getArrayCopy();

        foreach($items as $key => $item)
        {
            $callback($item, $key);
        }
    }

    function getColumn(string $name) : array
    {
        $items = $this->getArrayCopy();
        return array_column($items, $name);
    }

    function hasKey(mixed $value, bool $strict = false) : bool
    {
        $keys = (array) $this->getKeys();
        return in_array($value, $keys, $strict);
    }

    function hasValue(mixed $value, bool $strict = false) : bool
    {
        $values = (array) $this->getValues();
        return in_array($value, $values, $strict);
    }

    function set(string $key, mixed $value) : void
    {
        $this[$key] = $value;
    }

    function get(string $key, mixed $default = null) : mixed
    {
        if(!$this->hasKey($key))
        {
            return $default;
        }

        return $this[$key];
    }

    function remove(string $key) : bool
    {
        if(!$this->hasKey($key))
        {
            return false;
        }

        unset($this[$key]);
        return true;
    }

    function implodeKey(string $separator) : string
    {
        $keys = (array) $this->getKeys();
        return implode($separator, $keys);
    }

    function implodeValue(string $separator) : string
    {
        $values = (array) $this->getValues();
        return implode($separator, $values);
    }

    function diffKey(array $data) : static
    {
        $keys = (array) $this->getKeys();
        $values = array_diff($keys, $data);

        return new static($values);
    }

    function diffValue(array $data) : static
    {
        $keys = (array) $this->getValues();
        $values = array_diff($keys, $data);

        return new static($values);
    }

    function uniqueKey() : static
    {
        if(!$values = $this->getKeys())
        {
            return new static;
        }

        $values = array_unique($values);
        return new static($values);
    }

    function uniqueValue() : static
    {
        if(!$values = $this->getValues())
        {
            return new static;
        }

        $values = array_unique($values);
        return new static($values);
    }

    function slice(int $offset, int $length) : static
    {
        if(!$items = (array) $this->getArrayCopy())
        {
            return new static;
        }

        return new static(
            array_slice($items, $offset, $length)
        );
    }

    function getKeys() : array
    {
        return array_keys($this->getArrayCopy());
    }

    function getValues() : array
    {
        return array_values($this->getArrayCopy());
    }

    function getArray() : array
    {
        return $this->getArrayCopy();
    }
}