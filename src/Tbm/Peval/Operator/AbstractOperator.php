<?php
/*
 * Copyright 2002-2007 Robert Breidecker.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Tbm\Peval\Operator;

use Tbm\Peval\EvaluationException;
use Tbm\Peval\Operator\IOperator;
use Tbm\Peval\Types\Double;
use Tbm\Peval\Types\String;

/**
 * This is the standard operator that is the parent to all operators found in
 * expressions.
 */
abstract class AbstractOperator implements IOperator
{
    /**
     * @var String
     */
    private $symbol = null;

    /**
     * @var int
     */
	private $precedence = 0;

    /**
     * @var bool
     */
    private $unary = false;

    /**
     * @param String|\Tbm\Peval\Operator\String|\Tbm\Peval\Types\String $symbol
     * @param $precedence
     * @param null $unary
     */
    function __construct(String $symbol, $precedence, $unary = null)
    {
        if ($unary === null) {
            $this->AbstractOperator2($symbol, $precedence);
        }
        else {
            $this->AbstractOperator3($symbol, $precedence, $unary);
        }
    }

    /**
     * A constructor that takes the operator symbol and precedence as input.
     *
     * @param String|\Tbm\Peval\Operator\String|\Tbm\Peval\Types\String $symbol
     * @param $precedence
     * @internal param $symbol The character(s) that makes up the operator.
     *            The character(s) that makes up the operator.
     * @internal param $precedence The precedence level given to this operator.
     *            The precedence level given to this operator.
     */
	private function AbstractOperator2(String $symbol, $precedence) {

		$this->symbol = $symbol;
		$this->precedence = $precedence;
	}

    /**
     * A constructor that takes the operator symbol, precedence, unary indicator
     * and unary precedence as input.
     *
     * @param String|\Tbm\Peval\Operator\String|\Tbm\Peval\Types\String $symbol
     * @param $precedence
     * @param $unary
     * @internal param $symbol The character(s) that makes up the operator.
     *            The character(s) that makes up the operator.
     * @internal param $precedence The precedence level given to this operator.
     *            The precedence level given to this operator.
     * @internal param $unary Indicates of the operator is a unary operator or not.
     *            Indicates of the operator is a unary operator or not.
     */
	private function AbstractOperator3(String $symbol, $precedence, $unary) {

		$this->symbol = $symbol;
		$this->precedence = $precedence;
		$this->unary = $unary;
	}

    public function evaluate(Double $leftOperand, Double $rightOperand = null)
    {
        if ($rightOperand === null) {
            return $this->evaluate1Double($leftOperand);
        }
        elseif ($leftOperand && $rightOperand instanceof Double) {
            return $this->evaluate2Doubles($leftOperand, $rightOperand);
        }
        elseif ($leftOperand && $rightOperand instanceof String) {
            return $this->evaluate2Strings($leftOperand, $rightOperand);
        }
    }

    /**
     * Evaluates two double operands.
     *
     * @param \Tbm\Peval\Types\Double $leftOperand
     * @param \Tbm\Peval\Types\Double $rightOperand
     * @internal param $leftOperand The left operand being evaluated.
     *            The left operand being evaluated.
     * @internal param $rightOperand The right operand being evaluated.
     *            The right operand being evaluated.
     * @return int
     */
	protected function evaluate2Doubles(Double $leftOperand, Double $rightOperand)
    {
		return 0;
	}

    /**
     * Evaluates two string operands.
     *
     * @param \Tbm\Peval\Types\String $leftOperand
     * @param \Tbm\Peval\Types\String $rightOperand
     * @throws \Tbm\Peval\EvaluationException
     * @internal param $leftOperand The left operand being evaluated.
     *            The left operand being evaluated.
     * @internal param $rightOperand The right operand being evaluated.
     *            The right operand being evaluated.
     *
     * @return String The value of the evaluated operands.
     *
     * @exception EvaluateException
     *                Thrown when an error is found while evaluating the
     *                expression.
     */
	protected function evaluate2Strings(String $leftOperand, String $rightOperand)
    {
		throw new EvaluationException("Invalid operation for a string.");
	}

    /**
     * Evaluate one double operand.
     *
     * @param \Tbm\Peval\Types\Double $operand
     * @return int
     * @internal param $operand The operand being evaluated.
     *            The operand being evaluated.
     */
	protected function evaluate1Double(Double $operand) {
		return 0;
	}

	/**
	 * Returns the character(s) that makes up the operator.
	 * 
	 * @return The operator symbol.
	 */
	public function getSymbol() {
		return $this->symbol;
	}

	/**
	 * Returns the precedence given to this operator.
	 * 
	 * @return The precedecne given to this operator.
	 */
	public function getPrecedence() {
		return $this->precedence;
	}

	/**
	 * Returns the length of the operator symbol.
	 * 
	 * @return The length of the operator symbol.
	 */
	public function getLength() {
		return $this->symbol->length();
	}

	/**
	 * Returns an indicator of if the operator is unary or not.
	 * 
	 * @return An indicator of if the operator is unary or not.
	 */
	public function isUnary() {
		return $this->unary;
	}

    /**
     * Determines if this operator is equal to another operator. Equality is
     * determined by comparing the operator symbol of both operators.
     *
     * @param object
     *            The object to compare with this operator.
     *
     * @throws IllegalStateException
     * @return True if the object is equal and false if not.
     *
     * @exception IllegalStateException
     *                Thrown if the input object is not of the Operator type.
     */
	public function equals($object)
    {
		if ($object == null) {
			return false;
		}

		if (!($object instanceof AbstractOperator)) {
			throw new IllegalStateException("Invalid operator object.");
		}

		$this->operator = $object;

		if ($this->symbol->equals($this->operator->getSymbol())) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the String representation of this operator, which is the symbol.
	 * 
	 * @return The operator symbol.
	 */
	public function toString() {
		return $this->getSymbol();
	}
}