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

namespace Tbm\Peval\Func\String;

use Tbm\Peval\Collection\ArrayList;

use Tbm\Peval\EvaluationConstants;
use Tbm\Peval\Evaluator;
use Tbm\Peval\Func\IFunction;
use Tbm\Peval\Func\FunctionConstants;
use Tbm\Peval\Func\FunctionException;
use Tbm\Peval\Func\FunctionHelper;
use Tbm\Peval\Func\FunctionResult;
use Tbm\Peval\Types\String;

/**
 * This class is a function that executes within Evaluator. The function returns
 * the character at the specified index in the source string. See the
 * String.charAt(int) method in the JDK for a complete description of how this
 * function works.
 */
class CharAt implements IFunction
{
	/**
	 * Returns the name of the function - "charAt".
	 * 
	 * @return \Tbm\Peval\Types\String The name of this function class.
	 */
	public function getName()
    {
		return new String("charAt");
	}

    /**
     * Executes the function for the specified argument. This method is called
     * internally by Evaluator.
     *
     * @param Evaluator $evaluator
     * @param String|\Tbm\Peval\Types\String $arguments
     *            A string argument that will be converted into one string and
     *            one integer argument. The first argument is the source string
     *            and the second argument is the index. The string argument(s)
     *            HAS to be enclosed in quotes. White space that is not enclosed
     *            within quotes will be trimmed. Quote characters in the first
     *            and last positions of any string argument (after being
     *            trimmed) will be removed also. The quote characters used must
     *            be the same as the quote characters used by the current
     *            instance of Evaluator. If there are multiple arguments, they
     *            must be separated by a comma (",").
     *
     * @throws \Tbm\Peval\Func\FunctionException Thrown if the argument(s) are not valid for this use Tbm\Peval\Func\
     * @return \Tbm\Peval\Func\FunctionResult A character that is located at the specified index. The value is
     *         returned as a string.
     */
	public function execute(Evaluator $evaluator, String $arguments)
	{
		$result = null;
		$exceptionMessage = "One string and one integer argument are required.";

		$values = FunctionHelper::getOneStringAndOneInteger($arguments, EvaluationConstants::FUNCTION_ARGUMENT_SEPARATOR);

		if ($values->size() != 2) {
			throw new FunctionException($exceptionMessage);
		}

		try {
			$argumentOne = FunctionHelper::trimAndRemoveQuoteChars($values->get(0), $evaluator->getQuoteCharacter());
			$index = $values->get(1)->getValue();

			$character = array();
			$character[0] = $argumentOne->charAt($index);

            $result = new String($character);
		} catch (FunctionException $fe) {
			throw new FunctionException($fe.getMessage(), $fe);
		} catch (Exception $e) {
			throw new FunctionException($exceptionMessage, $e);
		}

		return new FunctionResult($result, FunctionConstants::FUNCTION_RESULT_TYPE_STRING);
	}
}