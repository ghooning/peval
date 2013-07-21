<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/6/13
 * Time: 3:10 AM
  */

namespace Tbm\Peval\Collection;

use ArrayIterator;


class ArrayList
{
    private $items = array();

    function __construct()
    {

    }

    public function add($item)
    {
        $this->items[] = $item;
    }

    public function iterator()
    {
        return new ArrayIterator($this->items);
    }

    public function size()
    {
        return count($this->items);
    }

    public function get($idx)
    {
        if ($idx >= 0 && $idx < $this->size()) {
            return $this->items[$idx];
        }

        return null;
    }
}