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
use Tbm\Peval\Types\String;
use Tbm\Peval\Func\IFunction;

/**
 * This class represents a function that has been parsed.
 */
class ParsedFunction {

    /**
     * The function that has been parsed.
     *
     * @var IFunction
     */
    private $function;

    /**
     *
     * @var String
     */
	private $arguments;

    /**
     * The unary operator for this object, if there is one.
     *
     * @var IOperator
     */
	private $unaryOperator;

    /**
     * The constructor for this class.
     *
     * @param Func\IFunction $function
     * @param Types\String $arguments
     * @param IOperator $unaryOperator
     * @internal param $function The function that has been parsed.
     *            The function that has been parsed.
     * @internal param $arguments The arguments to the function.
     *            The arguments to the function.
     * @internal param $unaryOperator The unary operator for this object, if there is one.
     *            The unary operator for this object, if there is one.
     */
	function __construct(IFunction $function, String $arguments, IOperator $unaryOperator = null) {

		$this->function = $function;
		$this->arguments = $arguments;
		$this->unaryOperator = $unaryOperator;
	}

	/**
	 * Returns the function that has been parsed.
	 * 
	 * @return IFunction The function that has been parsed.
	 */
	public function getFunction() {
		return $this->function;
	}

	/**
	 * Returns the arguments to the function.
	 * 
	 * @return String The arguments to the function.
	 */
	public function getArguments() {
		return $this->arguments;
	}

	/**
	 * Returns the unary operator for this object, if there is one.
	 * 
	 * @return IOperator The unary operator for this object, if there is one.
	 */
	public function getUnaryOperator() {
		return $this->unaryOperator;
	}
}