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
use Tbm\Peval\Types\String;
use Tbm\Peval\Types\Double;

/**
 * The addition operator.
 */
class AdditionOperator extends AbstractOperator
{
	/**
	 * Default constructor.
	 */
	function __construct()
    {
		parent::__construct(new String("+"), 5, true);
	}

    public function evaluate(Double $leftOperand, Double $rightOperand = null)
    {
        $rtnValue = $leftOperand->add($rightOperand);

        return $rtnValue;
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
     * @return \Tbm\Peval\Types\String
     */
	protected function evaluate2Strings(String $leftOperand, String $rightOperand)
    {
		$rtnValue = new String($leftOperand->substring(0, $leftOperand->length() - 1)
				+ $rightOperand->substring(1, $rightOperand->length()));

		return $rtnValue;
	}

    /**
     * Evaluate one double operand.
     *
     * @param \Tbm\Peval\Types\Double $operand
     * @internal param $operand The operand being evaluated.
     *            The operand being evaluated.
     * @return int|\Tbm\Peval\Types\Double
     */
	public function evaluate1Double(Double $operand) {
		return $operand;
	}
}