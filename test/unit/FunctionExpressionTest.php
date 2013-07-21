<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/12/13
 * Time: 11:16 AM
  */

namespace Tbm;

use PHPUnit_Framework_TestCase;
use Tbm\Peval\Evaluator;
use Tbm\Peval\Types\String;

class FunctionExpressionTest extends PHPUnit_Framework_TestCase
{
    public function testCharAtFunction()
    {
        $evaluator = new Evaluator();

        $this->assertEquals("'s'", $evaluator->evaluate1(new String("charAt('test', 2)")));

        $this->assertEquals("'A'", $evaluator->evaluate1(new String("'A'")));
    }
}