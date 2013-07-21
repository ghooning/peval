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

namespace Tbm\Peval;

use Tbm\Peval\Types\String;
use Tbm\Peval\Operator\IOperator;

/**
 * Represents an operand being processed in the expression.
 */
class ExpressionOperand
{

    /**
     * The value of the operand.
     *
     * @var String
     */
	private $value = null;

    /**
     * The unary operator for the operand, if one exists.
     *
     * @var IOperator
     */
	private $unaryOperator = null;

    /**
     * Create a new ExpressionOperand.
     *
     * @param Types\String $value
     * @param Operator\IOperator $unaryOperator
     * @internal param $value The value for the new ExpressionOperand.
     *            The value for the new ExpressionOperand.
     * @internal param $unaryOperator The unary operator for this object.
     *            The unary operator for this object.
     */
	function __construct(String $value, IOperator $unaryOperator = null)
    {
		$this->value = $value;
		$this->unaryOperator = $unaryOperator;
	}

	/**
	 * Returns the value of this object.
	 * 
	 * @return The value of this object.
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Returns the unary operator for this object.
	 * 
	 * @return IOperator The unary operator for this object.
	 */
	public function getUnaryOperator() {
		return $this->unaryOperator;
	}
}