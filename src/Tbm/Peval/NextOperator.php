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
 * Represents the next operator in the expression to process.
 */
class NextOperator {

    /**
     * The operator this object represents.
     *
     * @var IOperator
     */
	private $operator = null;

	// The index of the operator in the expression.
    /**
     *
     * @var int
     */
	private $index = -1;

    /**
     * Create a new NextOperator from an operator and index.
     *
     * @param Operator\IOperator $operator
     * @param index
     *            The index of the operator in the expression.
     */
	function __construct(IOperator $operator, $index)
    {
		$this->operator = $operator;
		$this->index = $index;
	}

	/**
	 * Returns the operator for this object.
	 * 
	 * @return IOperator The operator represented by this object.
	 */
	public function getOperator() {
		return $this->operator;
	}

	/**
	 * Returns the index for this object.
	 * 
	 * @return int The index of the operator in the expression.
	 */
	public function getIndex() {
		return $this->index;
	}
}