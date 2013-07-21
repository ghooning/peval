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

namespace Tbm\Peval\Func;

use Tbm\Peval\Types\String;
use Tbm\Peval\Func\FunctionConstants;

/**
 * This is a wrapper for the result value returned from a function that not only
 * contains the result, but the type. All custom functions must return a
 * FunctionResult.
 */
class FunctionResult
{
    /**
     * The value returned from a function call.
     *
     * @var \Tbm\Peval\Types\String
     */
    private $result;

	// The type of the result. Can be a numeric or string. Boolean values come
	// back as numeric values.
    /**
     * @var int
     */
	private $type;

    /**
     * Constructor.
     *
     * @param \Tbm\Peval\Types\String $result
     * @param type
     *            The result type.
     *
     * @throws \Tbm\Peval\Func\FunctionException
     */
	function __construct(String $result, $type)
    {
		if ($type < FunctionConstants::FUNCTION_RESULT_TYPE_NUMERIC || $type > FunctionConstants::FUNCTION_RESULT_TYPE_STRING) {
			throw new FunctionException("Invalid function result type.");
		}

		$this->result = $result;
		$this->type = $type;
	}

	/**
	 * Returns the result value.
	 * 
	 * @return \Tbm\Peval\Types\String The result value.
	 */
	public function getResult()
    {
		return $this->result;
	}

	/**
	 * Returns the result type.
	 * 
	 * @return int The result type.
	 */
	public function getType()
    {
		return $this->type;
	}
}
