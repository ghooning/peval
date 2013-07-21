<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/6/13
 * Time: 1:30 AM
  */

namespace Tbm\Peval\Types;

use Tbm\Peval\Types\String;


class Double
{
    private $value = null;

    function __construct($double)
    {
        if ($double instanceof String) {
            $this->Double($double);
        }
        elseif ($double instanceof Double) {
            $this->value = $double->value;
        }
        elseif (is_string($double)) {
            $this->Double(new String($double));
        }
        elseif (is_int($double) || is_float($double)) {
            $this->Double(new String((string)$double));
        }
    }

    private function Double(String $string)
    {
        $number_array = explode('.', $string->getValue());
        if ($number_array !== null && count($number_array) > 1) {
            $fraction = $number_array[1];
            $this->value = number_format(floatval($string->getValue()), strlen($fraction));
        }
        else {
            $this->value = floatval(number_format(floatval($string->getValue()), 1));
        }
    }

    public function add(Double $other)
    {
        return new Double(new String((string)($this->value + $other->value)));
    }

    public function divide(Double $other)
    {
        // TODO: add tests
        return new Double($this->getValue() / $other->getValue());
    }

    public function getValue()
    {
        return $this->value;
    }

    public function intValue()
    {
        if ($this->value !== null) {
            return intval($this->value);
        }
    }

    public function toString()
    {
        return (string)$this->value;
    }

    function __toString()
    {
        return (string)$this->value;
    }
}