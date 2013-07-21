<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/6/13
 * Time: 10:29 AM
  */

namespace Tbm\Peval\Collection;


class Stack
{
    private $internalStack = null;

    function __construct()
    {
        $this->internalStack = array();
    }

    public function push($item)
    {
        array_push($this->internalStack, $item);
        return $item;
    }

    public function pop()
    {
        return array_pop($this->internalStack);
    }

    public function peek()
    {
        if ($this->internalStack != null && count($this->internalStack) > 0) {
            return $this->internalStack[count($this->internalStack) - 1];
        }

        return null;
    }

    public function copy()
    {
        $cpy = array();
        foreach ($this->internalStack as $item) {
            $cpy[] = $item;
        }
    }

    public function isEmpty()
    {
        return $this->internalStack == null || count($this->internalStack) === 0 ? true : false;
    }

    public function size()
    {
        return count($this->internalStack);
    }
}