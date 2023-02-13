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

        foreach($items as $index => $item)
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

        if(!$items = $this->getArrayCopy())
        {
            return $result;
        }

        foreach($items as $index => $item)
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

    function each(callable $callback) : void
    {
        $items = $this->getArrayCopy();

        foreach($items as $index => $item)
        {
            $callback($item, $index);
        }
    }

    function getColumn(string $name) : array
    {
        $items = $this->getArrayCopy();
        return array_column($items, $name);
    }

    function hasKey(mixed $value) : bool
    {
        $keys = (array) $this->getKeys();
        return in_array($value, $keys, true);
    }

    function hasValue(mixed $value) : bool
    {
        $values = (array) $this->getValues();
        return in_array($value, $values, true);
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