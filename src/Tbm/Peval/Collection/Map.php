<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/12/13
 * Time: 12:51 PM
  */

namespace Tbm\Peval\Collection;


class Map
{
    private $internalMap = null;

    function __construct()
    {
        $this->internalMap = array();
    }

    public function put($key, $value)
    {
        $this->internalMap[$key] = $value;
    }

    public function get($key)
    {
        if ($this->containsKey($key)) {
            return $this->internalMap[$key];
        }

        return null;
    }

    public function remove($key)
    {
        if ($this->containsKey($key)) {
            unset($this->internalMap[$key]);
        }
    }

    public function clear()
    {
        $this->internalMap = array();
    }

    public function size()
    {
        return count($this->internalMap);
    }

    public function isEmpty()
    {
        return $this->size() === 0 ? true : false;
    }

    public function containsKey($key)
    {
        return array_key_exists($key, $this->internalMap);
    }
}