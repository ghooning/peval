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
 * The boolean not operator.
 */
class BooleanNotOperator extends AbstractOperator {

	/**
	 * Default constructor.
	 */
	function __construct()
    {
		parent::__construct(new String("!"), 0, true);
	}

    /**
     * Evaluate one double operand.
     *
     * @param \Tbm\Peval\Types\Double $leftOperand
     * @internal param $operand The operand being evaluated.
     *            The operand being evaluated.
     * @return int
     */
    public function evaluate1Double(Double $leftOperand)
    {
		if ($leftOperand->getValue() == (double)1) {
			return new Double(0);
		}

		return new Double(1);
	}
}