<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/12/13
 * Time: 9:51 AM
  */

namespace Tbm\Peval\Types;


class Integer
{
    private $value = null;

    function __construct($value = null)
    {
        if (is_string($value)) {
            $this->value = intval($value);
        }
        elseif (is_int($value)) {
            $this->value = $value;
        }
    }

    public function getValue()
    {
        return $this->value;
    }
}