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

use Tbm\Peval\Types\Enumeration;
use Tbm\Peval\Types\String;

/**
 * This class allow for tokenizer methods to be called on a String of arguments.
 */
class ArgumentTokenizer implements Enumeration
{
	/**
	 * The default delimitor.
	 */
	const defaultDelimiter = EvaluationConstants::FUNCTION_ARGUMENT_SEPARATOR;

    /**
     * The arguments to be tokenized. This is updated every time the nextToken
     * method is called.
     *
     * @var String|\Tbm\Peval\String
     */
	private $arguments = null;

	// The separator between the arguments.
	private $delimiter = ArgumentTokenizer::defaultDelimiter;

	/**

	 * 
	 * @param arguments
	 *            The String of srguments to be tokenized.
	 * @param delimiter
	 *            The argument tokenizer.
	 */

    /**
     * Constructor that takes a String of arguments and a delimiter.
     *
     * @param String|\Tbm\Peval\Types\String $arguments
     * @param $delimiter
     */
    function __construct(String $arguments, $delimiter) {
		$this->arguments = $arguments;
		$this->delimiter = $delimiter;
	}

	/**
	 * Indicates if there are more elements.
	 * 
	 * @return True if there are more elements and false if not.
	 */
	public function hasMoreElements() {
		return $this->hasMoreTokens();
	}

	/**
	 * Indicates if there are more tokens.
	 * 
	 * @return True if there are more tokens and false if not.
	 */
	public function hasMoreTokens()
    {
        if ($this->arguments !== null && is_string($this->arguments)) {
            return strlen($this->arguments) > 0 ? true : false;
        }

        $len = $this->arguments->length();
		if ($this->arguments !== null && $len > 0) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the next element.
	 * 
	 * @return The next element.
	 */
	public function nextElement() {
		return $this->nextToken();
	}

	/**
	 * Returns the next token.
	 * 
	 * @return String The next element.
	 */
	public function nextToken() {
		$charCtr = 0;
		$size = $this->arguments->length();
		$parenthesesCtr = 0;
		$returnArgument = null;

		// Loop until we hit the end of the arguments String.
		while ($charCtr < $size) {
			if ($this->arguments->charAt($charCtr) == '(') {
				$parenthesesCtr++;
			} else if ($this->arguments->charAt($charCtr) == ')') {
				$parenthesesCtr--;
			} else if ($this->arguments->charAt($charCtr) == $this->delimiter
					&& $parenthesesCtr == 0) {

				$returnArgument = $this->arguments->substring(0, $charCtr);
				$this->arguments = $this->arguments->substring($charCtr + 1);
				break;
			}

			$charCtr++;
		}

		if ($returnArgument == null) {
			$returnArgument = $this->arguments;
			$this->arguments = "";
		}

		return $returnArgument;
	}
}
