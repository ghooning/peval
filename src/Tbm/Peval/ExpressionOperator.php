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

use Tbm\Peval\Operator\IOperator;

/**
 * Represents an operator being processed in the expression.
 */
class ExpressionOperator
{
    /**
     * The operator that this object represents.
     *
     * @var IOperator
     */
	private $operator = null;

	// The unary operator for this object, if there is one.
    /**
     *
     * @var IOperator
     */
	private $unaryOperator = null;

    /**
     * Creates a new ExpressionOperator.
     *
     * @param Operator\IOperator $operator
     * @param Operator\IOperator $unaryOperator
     * @internal param $operator The operator this object represents.
     *            The operator this object represents.
     * @internal param $unaryOperator The unary operator for this object.
     *            The unary operator for this object.
     */
	function __construct(IOperator $operator, IOperator $unaryOperator = null) {
		$this->operator = $operator;
		$this->unaryOperator = $unaryOperator;
	}

	/**
	 * Returns the operator for this object.
	 * 
	 * @return The IOperator for this object.
	 */
	public function getOperator() {
		return $this->operator;
	}

	/**
	 * Returns the unary operator for this object.
	 * 
	 * @return The unary IOperator for this object.
	 */
	public function getUnaryOperator() {
		return $this->unaryOperator;
	}
}