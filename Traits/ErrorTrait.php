<?php namespace Motokraft\Object\Traits;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://github.com/motokraft/object
 */

trait ErrorTrait
{
    private $error;

    function setError(string $error) : static
    {
        $this->error = $error;
        return $this;
    }

    function getError() : null|string
    {
        return $this->error;
    }
}