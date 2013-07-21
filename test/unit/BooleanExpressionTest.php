<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/12/13
 * Time: 1:06 AM
  */

namespace Tbm;

use Exception;
use PHPUnit_Framework_TestCase;
use Tbm\Peval\EvaluationConstants;
use Tbm\Peval\Evaluator;
use Tbm\Peval\Types\String;


class BooleanExpressionTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluateBooleanOperations() {
		$evaluator = new Evaluator();

		try  {
			/*
			 * These tests involve valid expressions.
			 */
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("3 < 3")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("3 < 4")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("4 < 3")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("3 > 3")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("3 > 4")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("4 > 3")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("3 <= 4")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("3 <= -4")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("3 <= 3")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("3 >= 4")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("3 >= -4")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("3 >= 3")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("3 == 3")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("3 == 4")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("3 == 2")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("3 != 3")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("3 != 4")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("3 != 2")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("2 < 3 && 1 == 1")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("2 > 3 && 1 == 1")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("2 < 3 && 1 == 2")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("2 < 3 || 1 == 1")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("2 > 3 || 1 == 1")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("2 > 3 || 1 == 2")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("1 + 2 >= 3 + 0")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("(3 < 3)")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("!(3 < 3)")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("(3 > 4)")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("(3 == 3)")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("!(3 == 3)")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("(3 != 3)")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("(2 < 3) && 1 == 1")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("(2 > 3) && (1 == 1)")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("(2 < 3 && 1 == 2)")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("(2 < 3) || (1 == 1)")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("!1")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("!0")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("!2")));
			$this->assertEquals(EvaluationConstants::BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("((2 < 3) || (1 == 1)) && (3 < 3)")));
        }
        catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * @expectedException \Tbm\Peval\EvaluationException
     */
    public function testInvalidBooleanExpressionException()
    {
        /*
         * These tests involve invalid expressions.
         */
        $evaluator = new Evaluator();

        $evaluator->evaluate1(new String("3 3 < 3"));
        $evaluator->evaluate1(new String("3 << 3"));
        $evaluator->evaluate1(new String("3 <> 4"));
        $evaluator->evaluate1(new String("!!(3 <> 4)"));
        $evaluator->evaluate1(new String("3 = 4"));
        $evaluator->evaluate1(new String("2 < 3 && 1 = 1"));
        $evaluator->evaluate1(new String("(3) (3 < 3)"));
        $evaluator->evaluate1(new String("3 (<<) 3"));
        $evaluator->evaluate1(new String("(2 < 3) && (1 = 1"));
	}
}