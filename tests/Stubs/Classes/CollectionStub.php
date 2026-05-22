<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Stubs\Classes;

use ArrayAccess;
use Countable;
use Iterator;
use ObjectModel;
use PrestaShopException;

class CollectionStub implements Iterator, ArrayAccess, Countable
{
    /** @var ObjectModel[] */
    public static $forceElements = [];

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
        return isset(self::$forceElements[$this->iterator]) ? self::$forceElements[$this->iterator] : null;
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
        return isset(self::$forceElements[$this->iterator]);
    }

    public function rewind()
    {
        $this->iterator = 0;
    }

    public function offsetExists($offset)
    {
        return isset(self::$forceElements[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset(self::$forceElements[$offset])) {
            return self::$forceElements[$offset];
        }

        throw new PrestaShopException("Unknown offset {$offset} for collection {$this->classname}");
    }

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof $this->classname) {
            throw new PrestaShopException("Value must be an instance of {$this->classname}");
        }

        if (null === $offset) {
            self::$forceElements[] = $value;
        } else {
            self::$forceElements[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset(self::$forceElements[$offset]);
    }

    public function count()
    {
        return \count(self::$forceElements);
    }
}
