<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes;

use ArrayAccess;
use Countable;
use Iterator;
use PrestaShopException;

class Collection implements Iterator, ArrayAccess, Countable
{
    /** @var array */
    public $elements = [];

    /** @var string */
    private $classname;

    /** @var int */
    private $iterator = 0;

    /**
     * @param string $classname
     * @param int|null $id_lang
     */
    public function __construct($classname, $id_lang = null)
    {
        $this->classname = $classname;
    }

    public function current()
    {
        return isset($this->elements[$this->iterator]) ? $this->elements[$this->iterator] : null;
    }

    public function next()
    {
        $this->iterator++;
    }

    public function key()
    {
        return $this->iterator;
    }

    public function valid()
    {
        return isset($this->elements[$this->iterator]);
    }

    public function rewind()
    {
        $this->iterator = 0;
    }

    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->elements[$offset])) {
            return $this->elements[$offset];
        }

        throw new PrestaShopException("Unknown offset {$offset} for collection {$this->classname}");
    }

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof $this->classname) {
            throw new PrestaShopException("Value must be an instance of {$this->classname}");
        }

        if (null === $offset) {
            $this->elements[] = $value;
        } else {
            $this->elements[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->elements[$offset]);
    }

    public function count()
    {
        return \count($this->elements);
    }
}
