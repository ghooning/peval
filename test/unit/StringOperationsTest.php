<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geert
 * Date: 6/12/13
 * Time: 2:10 PM
  */

namespace Tbm;

use Exception;
use PHPUnit_Framework_TestCase;
use Tbm\Peval\Evaluator;
use Tbm\Peval\EvaluationConstants;
use Tbm\Peval\Types\String;


class StringOperationsTest extends PHPUnit_Framework_TestCase
{
    /**
  	 * Test expressions containing string operations.
  	 */
    public function testEvaluateStringOperations() {
		$evaluator = new Evaluator();

		try {
			/*
			 * These tests involve valid expressions.
			 */
			//$this->assertEquals("'A'", $evaluator->evaluate1(new String("'A'")));
			$this->assertEquals("'AC'", $evaluator->evaluate1(new String("'A' + 'C'")));
			$this->assertEquals("'A + C'", $evaluator->evaluate1(new String("'A + C'")));
			$this->assertEquals("'ABC'", $evaluator->evaluate1(new String("'AB' + 'C'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("'A' < 'C'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("'C' < 'A'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("'C' < 'F'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("'C' < 'c'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("'c' < 'C'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("'A' > 'C'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("'C' > 'A'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("'C' > 'F'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("'A' <= 'A'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("'C' <= 'A'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("'C' <= 'F'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("'A' >= 'A'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("'C' >= 'A'")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_FALSE, $evaluator->evaluate1(new String("'C' >= 'F'")));
			$this->assertEquals("'A'", $evaluator->evaluate1(new String("('A')")));
			$this->assertEquals("'ABC'", $evaluator->evaluate1(new String("('AB' + 'C')")));
			$this->assertEquals("'123ABC'", $evaluator->evaluate1(new String("'123' + ('AB' + 'C')")));
			$this->assertEquals(EvaluationConstants.BOOLEAN_STRING_TRUE, $evaluator->evaluate1(new String("(('C' >= 'A'))")));
        }
        catch (Exception $e) {
            throw new Exception($e);
        }
    }
}