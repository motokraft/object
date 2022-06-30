<?php namespace Motokraft\Object\Traits;

/**
 * @copyright   2022 Motokraft. MIT License
 * @link https://motokraft.github.io/object
 */

use \Motokraft\Object\BaseObject;

trait MessageTrait
{
    use ObjectTrait;

    private $messages = [];

    function setPrimary(string $message, array $data = []) : static
    {
        return $this->setMessage('primary', $message, $data);
    }

    function setDanger(string $message, array $data = []) : static
    {
        return $this->setMessage('danger', $message, $data);
    }

    function setSuccess(string $message, array $data = []) : static
    {
        return $this->setMessage('success', $message, $data);
    }

    function setWarning(string $message, array $data = []) : static
    {
        return $this->setMessage('warning', $message, $data);
    }

    function setInfo(string $message, array $data = []) : static
    {
        return $this->setMessage('info', $message, $data);
    }

    function setMessage(string $type, string $message, array $data = []) : static
    {
        if(!$this->hasTypeMessage($type))
        {
            $this->messages[$type] = [];
        }

        $text = $this->_prepareMessage($message, $data);
        array_push($this->messages[$type], $text);

        return $this;
    }

    function getMessages() : array
    {
        return $this->messages;
    }

    function getTypeMessage(string $type) : bool|array
    {
        if(!$this->hasTypeMessage($type))
        {
            return false;
        }

        return $this->messages[$type];
    }

    function removeTypeMessage(string $type) : bool
    {
        if(!$this->hasTypeMessage($type))
        {
            return false;
        }

        unset($this->messages[$type]);
        return true;
    }

    function hasTypeMessage(string $type) : bool
    {
        return isset($this->messages[$type]);
    }

    private function _prepareMessage(string $text, array $data = []) : string
    {
        $data = new BaseObject($data + $this->getArray());
        preg_match_all('/{(.*)}/mU', $text, $matches, 2);

        foreach($matches as $matche)
        {
            $value = (string) $data->get($matche[1]);
            $text = str_replace($matche[0], $value, $text);
        }

        return $text;
    }
}