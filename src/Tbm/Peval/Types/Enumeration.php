<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/6/13
 * Time: 2:41 AM
  */

namespace Tbm\Peval\Types;


interface Enumeration
{
    public function hasMoreElements();

    public function nextElement();
}