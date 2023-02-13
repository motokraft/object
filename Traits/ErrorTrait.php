<?php namespace Motokraft\Object\Traits;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://github.com/motokraft/object
 */

trait ErrorTrait
{
    private ?string $error = null;

    function setError(string $error) : void
    {
        $this->error = $error;
    }

    function getError() : ?string
    {
        return $this->error;
    }
}