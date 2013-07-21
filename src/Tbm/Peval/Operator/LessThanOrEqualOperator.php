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

use Tbm\Peval\Operator\AbstractOperator;
use Tbm\Peval\EvaluationConstants;
use Tbm\Peval\Types\Double;
use Tbm\Peval\Types\String;

/**
 * The less than or equal operator.
 */
class LessThanOrEqualOperator extends AbstractOperator
{
	/**
	 * Default constructor.
	 */
	function __construct()
    {
		parent::__construct(new String("<="), 4);
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
     * @return int|\Tbm\Peval\Types\Double
     */
	public function evaluate2Doubles(Double $leftOperand, Double $rightOperand) {
		if ($leftOperand->getValue() <= $rightOperand->getValue()) {
			return new Double(1);
		}

		return new Double(0);
	}

    /**
     * Evaluates two string operands.
     *
     * @param \Tbm\Peval\Types\String $leftOperand
     * @param \Tbm\Peval\Types\String $rightOperand
     * @internal param $leftOperand The left operand being evaluated.
     *            The left operand being evaluated.
     * @internal param $rightOperand The right operand being evaluated.
     *            The right operand being evaluated.
     * @return String
     */
	public function evaluate2Strings(String $leftOperand, String $rightOperand) {
		if ($leftOperand.compareTo($rightOperand) <= 0) {
			return EvaluationConstants::BOOLEAN_STRING_TRUE;
		}

		return EvaluationConstants::BOOLEAN_STRING_FALSE;
	}
}