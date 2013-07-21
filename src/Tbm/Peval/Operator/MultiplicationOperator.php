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
use Tbm\Peval\Types\Double;
use Tbm\Peval\Types\String;

/**
 * The multiplication operator.
 */
class MultiplicationOperator extends AbstractOperator
{
	/**
	 * Default constructor.
	 */
	function __construct()
    {
		parent::__construct(new String("*"), 6);
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
     * @return String
     */
	public function evaluate2Doubles(Double $leftOperand, Double $rightOperand) {
		$rtnValue = new Double($leftOperand->getValue() * $rightOperand->getValue());

		return $rtnValue;
	}
}