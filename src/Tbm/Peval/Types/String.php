<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/6/13
 * Time: 1:44 AM
  */

namespace Tbm\Peval\Types;


class String
{
    private $value = null;

    function __construct($string = null)
    {
        if ($string instanceof String) {
            $this->value = $string->getValue();
        }
        if (is_string($string)) {
            $this->value = $string;
        }
        elseif (is_array($string)) {
            foreach ($string as $char) {
                $this->value .= $char;
            }
        }
    }

    public static function valueOf($value)
    {
        if (is_string($value)) {
            return new String($value);
        }

        else return null;
    }

    public function length()
    {
        return strlen($this->value);
    }

    public function substring($beginIdx, $endIdx = null)
    {
        if ($endIdx !== null) {
            return new String(substr($this->value, $beginIdx, $endIdx - $beginIdx));
        }
        else {
            return new String(substr($this->value, $beginIdx));
        }
    }

    public function indexOf($char)
    {
        $idx = strpos($this->value, $char);
        if ($idx === false) {
            return -1;
        }

        return $idx;
    }

    public function charAt($idx)
    {
        return $this->value{$idx};
    }

    public function trim()
    {
        $this->value = trim($this->value);
        return $this;
    }

    public function append($string)
    {
        return new String($this->value . $string);
    }

    public function equals(String $other)
    {
        return ($this->getValue() === $other->getValue());
    }

    public function compareTo(String $other)
    {
        return strcmp($this->getValue(), $other->getValue());
    }

    public function getValue()
    {
        return $this->value;
    }

    function __toString()
    {
        return (string)$this->value;
    }
}