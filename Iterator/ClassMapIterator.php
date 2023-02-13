<?php namespace Motokraft\Object\Iterator;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://github.com/motokraft/object
 */

class ClassMapIterator extends \RecursiveIteratorIterator implements \RecursiveIterator
{
    function __construct(\ReflectionClass $class)
    {
        $iterator = new \RecursiveArrayIterator([$class]);
        parent::__construct($iterator, self::SELF_FIRST);
    }

    function callGetChildren() : \RecursiveArrayIterator
    {
        $current = parent::current();
        $parent = $current->getParentClass();

        return new \RecursiveArrayIterator([$parent]);
    }

    function callHasChildren() : bool
    {
        if(!$current = parent::current())
        {
            return false;
        }

        $parent = $current->getParentClass();
        return ($parent instanceof \ReflectionClass);
    }

    function hasChildren() : bool
    {
        return $this->callHasChildren();
    }

    function getChildren() : \RecursiveArrayIterator
    {
        return $this->callGetChildren();
    }
}