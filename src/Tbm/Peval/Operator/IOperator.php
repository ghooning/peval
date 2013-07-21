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
use Tbm\Peval\Types\Double;
use Tbm\Peval\Types\String;

/**
 * An operator that can be specified in an expression.
 */
interface IOperator
{
    /**
     * Evaluates two double operands.
     *
     * @param \Tbm\Peval\Types\Double $leftOperand
     * @param \Tbm\Peval\Types\Double $rightOperand
     * @return
     * @internal param $leftOperand The left operand being evaluated.
     *            The left operand being evaluated.
     * @internal param $rightOperand The right operand being evaluated.
     *            The right operand being evaluated.
     */
	public function evaluate(Double $leftOperand, Double $rightOperand = null);

	/**
	 * Returns the character(s) that makes up the operator.
	 * 
	 * @return String The operator symbol.
	 */
	public function getSymbol();

	/**
	 * Returns the precedence given to this operator.
	 * 
	 * @return int The precedecne given to this operator.
	 */
	public function getPrecedence();

	/**
	 * Returns the length of the operator symbol.
	 * 
	 * @return int The length of the operator symbol.
	 */
	public function getLength();

	/**
	 * Returns an indicator of if the operator is unary or not.
	 * 
	 * @return boolean An indicator of if the operator is unary or not.
	 */
	public function isUnary();
}