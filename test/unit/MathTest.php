<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/12/13
 * Time: 12:05 AM
  */

namespace Tbm;

use Exception;
use PHPUnit_Framework_TestCase;
use Tbm\Peval\Evaluator;
use Tbm\Peval\Types\String;


class MathTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluateMathematicalOperations()
    {
        $evaluator = new Evaluator();

        try
        {
            /*
             * These tests involve valid expressions.
             */
            $this->assertEquals("4.0", $evaluator->evaluate1(new String("4")));
            $this->assertEquals("-4.0", $evaluator->evaluate1(new String("-4")));
            $this->assertEquals("5.0", $evaluator->evaluate1(new String("4 + 1")));
            $this->assertEquals("3.0", $evaluator->evaluate1(new String("4 + -1")));
            $this->assertEquals("0.2", $evaluator->evaluate1(new String("0.2")));
            $this->assertEquals("1.6", $evaluator->evaluate1(new String("1.2 + 0.4")));
            $this->assertEquals("1.6", $evaluator->evaluate1(new String("1.2 + .4")));
            $this->assertEquals("0.6", $evaluator->evaluate1(new String("0.2 + 0.4")));
            $this->assertEquals("-0.2", $evaluator->evaluate1(new String("0.2 - 0.4")));
            $this->assertEquals("6.0", $evaluator->evaluate1(new String("2 - -4")));
            $this->assertEquals("-3.0", $evaluator->evaluate1(new String("-4 + 1")));
            $this->assertEquals("-5.0", $evaluator->evaluate1(new String("-4 + -1")));
            $this->assertEquals("3.0", $evaluator->evaluate1(new String("4 - 1")));
            $this->assertEquals("-3.0", $evaluator->evaluate1(new String("1 - 4")));
            $this->assertEquals("12.0", $evaluator->evaluate1(new String("4 * 3")));
            $this->assertEquals("-12.0", $evaluator->evaluate1(new String("4 * -3")));
            $this->assertEquals("12.0", $evaluator->evaluate1(new String("-4 * -3")));
            $this->assertEquals("2.0", $evaluator->evaluate1(new String("4 / 2")));
            $this->assertEquals("0.5", $evaluator->evaluate1(new String("2 / 4")));
            $this->assertEquals("-2.0", $evaluator->evaluate1(new String("4 / -2")));
            $this->assertEquals("1.0", $evaluator->evaluate1(new String("7 % 2")));
            $this->assertEquals("1.0", $evaluator->evaluate1(new String("7 % -2")));
            $this->assertEquals("14.0", $evaluator->evaluate1(new String("4 * 3 + 2")));
            $this->assertEquals("10.0", $evaluator->evaluate1(new String("4 + 3 * 2")));
            $this->assertEquals("16.0", $evaluator->evaluate1(new String("4 / 2 * 8")));
            $this->assertEquals("4.0", $evaluator->evaluate1(new String("(4)")));
            $this->assertEquals("-4.0", $evaluator->evaluate1(new String("(-4)")));
            $this->assertEquals("-4.0", $evaluator->evaluate1(new String("-(4)")));
            $this->assertEquals("4.0", $evaluator->evaluate1(new String("-(-4)")));
            $this->assertEquals("4.0", $evaluator->evaluate1(new String("-(-(4))")));
            $this->assertEquals("7.0", $evaluator->evaluate1(new String("(4 + 3)")));
            $this->assertEquals("-6.0", $evaluator->evaluate1(new String("-(3 + 3)")));
            $this->assertEquals("4.0", $evaluator->evaluate1(new String("(3) + 1")));
            $this->assertEquals("2.0", $evaluator->evaluate1(new String("(3) - 1")));
            $this->assertEquals("14.0", $evaluator->evaluate1(new String("(4 + 3) * 2")));
            $this->assertEquals("13.0", $evaluator->evaluate1(new String("4 + (3 + 1) + (3 + 1) + 1")));
            $this->assertEquals("14.0", $evaluator->evaluate1(new String("((4 + 3) * 2)")));
            $this->assertEquals("42.0", $evaluator->evaluate1(new String("((4 + 3) * 2) * 3")));
            $this->assertEquals("-42.0", $evaluator->evaluate1(new String("((4 + 3) * -2) * 3")));
            $this->assertEquals("-2.0", $evaluator->evaluate1(new String("((4 + 3) * 2) / -7")));
            $this->assertEquals("16.0", $evaluator->evaluate1(new String("(4 / 2) * 8")));
            $this->assertEquals("0.25", $evaluator->evaluate1(new String("4 / (2 * 8)")));
            $this->assertEquals("1.0", $evaluator->evaluate1(new String("(4 * 2) / 8")));
            $this->assertEquals("1.0", $evaluator->evaluate1(new String("4 * (2 / 8)")));
            $this->assertEquals("16.0", $evaluator->evaluate1(new String("(4 / (2) * 8)")));
            $this->assertEquals("-4.0", $evaluator->evaluate1(new String("-(3 + -(3 - 4))")));
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * @expectedException Tbm\Peval\EvaluationException
     */
    public function testInvalidMathExpressionException()
    {
        /*
         * These tests involve invalid expressions.
         */
        $evaluator = new Evaluator();
        
        $evaluator->evaluate1(new String("-"));
        $evaluator->evaluate1(new String("4 + "));
        $evaluator->evaluate1(new String("4 - "));
        $evaluator->evaluate1(new String("4 + -"));
        $evaluator->evaluate1(new String("--4"));
        $evaluator->evaluate1(new String("4 * / 3"));
        $evaluator->evaluate1(new String("* 3"));
        $evaluator->evaluate1(new String("((4"));
        $evaluator->evaluate1(new String("4 ("));
        $evaluator->evaluate1(new String("(4))"));
        $evaluator->evaluate1(new String("((4 + 3)) * 2)"));
        $evaluator->evaluate1(new String("4 ()"));
        $evaluator->evaluate1(new String("4 (+) 3"));
    }
}
