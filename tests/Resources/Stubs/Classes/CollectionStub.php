<?php

namespace RubenMartinDev\PrestaShopModuleInstaller\Tests\Resources\Stubs\Classes;

use ArrayAccess;
use Countable;
use Iterator;
use ObjectModel;
use PrestaShopException;

/**
 * @see \Collection
 */
class CollectionStub implements Iterator, ArrayAccess, Countable
{
    /** @var ObjectModel[] */
    public static $forceElements = [];

    /** @var string */
    private $classname;

    /** @var int */
    private $iterator = 0;

    /**
     * @see \Collection::__construct()
     */
    public function __construct($classname, $id_lang = null)
    {
        $this->classname = $classname;
    }

    /**
     * @see \Collection::current()
     */
    public function current()
    {
        return isset(self::$forceElements[$this->iterator]) ? self::$forceElements[$this->iterator] : null;
    }

    /**
     * @see \Collection::next()
     */
    public function next()
    {
        $this->iterator++;
    }

    /**
     * @see \Collection::key()
     */
    public function key()
    {
        return $this->iterator;
    }

    /**
     * @see \Collection::valid()
     */
    public function valid()
    {
        return isset(self::$forceElements[$this->iterator]);
    }

    /**
     * @see \Collection::rewind()
     */
    public function rewind()
    {
        $this->iterator = 0;
    }

    /**
     * @see \Collection::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset(self::$forceElements[$offset]);
    }

    /**
     * @see \Collection::offsetGet()
     */
    public function offsetGet($offset)
    {
        if (isset(self::$forceElements[$offset])) {
            return self::$forceElements[$offset];
        }

        throw new PrestaShopException("Unknown offset {$offset} for collection {$this->classname}");
    }

    /**
     * @see \Collection::offsetSet()
     */
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

    /**
     * @see \Collection::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset(self::$forceElements[$offset]);
    }

    /**
     * @see \Collection::count()
     */
    public function count()
    {
        return \count(self::$forceElements);
    }
}
