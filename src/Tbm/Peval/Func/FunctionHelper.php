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

use Tbm\Peval\ArgumentTokenizer;
use Tbm\Peval\Collection\ArrayList;
use Tbm\Peval\Func\FunctionException;
use Tbm\Peval\Types\Double;
use Tbm\Peval\Types\Integer;
use Tbm\Peval\Types\String;


/**
 * This class contains many methods that are helpful when writing functions.
 * Some of these methods were created to help with the creation of the math and
 * string functions packaged with Evaluator.
 */
class FunctionHelper
{
    /**
     * This method first removes any white space at the beginning and end of the
     * input string. It then removes the specified quote character from the the
     * first and last characters of the string if a quote character exists in
     * those positions. If quote characters are not in the first and last
     * positions after the white space is trimmed, then a FunctionException will
     * be thrown.
     *
     * @param \Tbm\Peval\Types\String $arguments
     * @param $quoteCharacter
     *            The character to remove from the first and last position of
     *            the trimmed string.
     *
     * @return \Tbm\Peval\Types\String The arguments with white space and quote characters removed.
     *
     * @throws \Tbm\Peval\Func\FunctionException
     *                Thrown if quote characters do not exist in the first and
     *                last positions after the white space is trimmed.
     */
	public static function trimAndRemoveQuoteChars(String $arguments, $quoteCharacter)
    {
        $trimmedArgument = $arguments;

		$trimmedArgument = $trimmedArgument->trim();

		if ($trimmedArgument->charAt(0) == $quoteCharacter) {
            $trimmedArgument = $trimmedArgument->substring(1, $trimmedArgument->length());
		} else {
			throw new FunctionException("Value does not start with a quote.");
		}

		if ($trimmedArgument->charAt($trimmedArgument->length() - 1) == $quoteCharacter) {
            $trimmedArgument = $trimmedArgument->substring(0, $trimmedArgument->length() - 1);
		} else {
			throw new FunctionException("Value does not end with a quote.");
		}

		return $trimmedArgument;
	}

    /**
     * This methods takes a string of input function arguments, evaluates each
     * argument and creates a Double value for each argument from the result of
     * the evaluations.
     *
     * @param \Tbm\Peval\Types\String $arguments
     * @param $delimiter
     *            The delimiter to use while parsing.
     *
     * @return \Tbm\Peval\Collection\ArrayList An array list of Double values found in the input string.
     *
     * @throws \Tbm\Peval\Func\FunctionException
     *                Thrown if the string does not properly parse into Double
     *                values.
     */
	public static function getDoubles(String $arguments, $delimiter)
    {
		$returnValues = new ArrayList();

		try {

			$tokenizer = new ArgumentTokenizer($arguments, $delimiter);

			while ($tokenizer->hasMoreTokens()) {
				$token = $tokenizer->nextToken()->trim();
				$returnValues->add(new Double($token));
			}
		} catch (Exception $e) {
			throw new FunctionException("Invalid values in string.", $e);
		}

		return $returnValues;
	}

    /**
     * This methods takes a string of input function arguments, evaluates each
     * argument and creates a String value for each argument from the result of
     * the evaluations.
     *
     * @param \Tbm\Peval\Types\String $arguments
     * @param $delimiter
     *            The delimiter to use while parsing.
     *
     * @return \Tbm\Peval\Collection\ArrayList An array list of String values found in the input string.
     *
     * @throws \Tbm\Peval\Func\FunctionException
     *                Thrown if the string does not properly parse into String
     *                values.
     */
	public static function getStrings(String $arguments, $delimiter)
    {
		$returnValues = new ArrayList();

		try {
			$tokenizer = new ArgumentTokenizer($arguments, $delimiter);

			while ($tokenizer->hasMoreTokens()) {
				$token = $tokenizer->nextToken();
                $returnValues->add($token);
			}
		} catch (Exception $e) {
			throw new FunctionException("Invalid values in string.", $e);
		}

		return $returnValues;
	}

    /**
     * This methods takes a string of input function arguments, evaluates each
     * argument and creates a one Integer and one String value for each argument
     * from the result of the evaluations.
     *
     * @param \Tbm\Peval\Types\String $arguments
     * @param delimiter
     *            The delimiter to use while parsing.
     *
     * @return \Tbm\Peval\Collection\ArrayList An array list of object values found in the input string.
     *
     * @throws FunctionException
     *                Thrown if the string does not properly parse into the
     *                proper objects.
     */
	public static function getOneStringAndOneInteger(String $arguments, $delimiter)
    {
		$returnValues = new ArrayList();

		try {
			$tokenizer = new ArgumentTokenizer($arguments, $delimiter);

			$tokenCtr = 0;
			while ($tokenizer->hasMoreTokens()) {
				if ($tokenCtr == 0) {
					$token = $tokenizer->nextToken();
                    $returnValues->add($token);
				} else if ($tokenCtr == 1) {
					$token = $tokenizer->nextToken()->trim();
                    $tokenDouble = new Double($token);
                    $tokenInteger = new Integer($tokenDouble->intValue());
                    $returnValues->add($tokenInteger);
				} else {
					throw new FunctionException("Invalid values in string.");
				}

				$tokenCtr++;
			}
		} catch (Exception $e) {
			throw new FunctionException("Invalid values in string.", $e);
		}

		return $returnValues;
	}

    /**
     * This methods takes a string of input function arguments, evaluates each
     * argument and creates a two Strings and one Integer value for each
     * argument from the result of the evaluations.
     *
     * @param \Tbm\Peval\Types\String $arguments
     * @param $delimiter
     *            The delimiter to use while parsing.
     *
     * @return \Tbm\Peval\Collection\ArrayList An array list of object values found in the input string.
     *
     * @throws \Tbm\Peval\Func\FunctionException
     *                Thrown if the string does not properly parse into the
     *                proper objects.
     */
	public static function getTwoStringsAndOneInteger(String $arguments, $delimiter)
    {
		$returnValues = new ArrayList();

		try {
			$tokenizer = new ArgumentTokenizer($arguments, $delimiter);

			$tokenCtr = 0;
			while ($tokenizer->hasMoreTokens()) {
				if ($tokenCtr == 0 || $tokenCtr == 1) {
					$token = $tokenizer->nextToken();
                    $returnValues->add($token);
				} else if ($tokenCtr == 2) {
					$token = $tokenizer->nextToken()->trim();
                    $tokenDouble = new Double($token);
                    $tokenInteger = new Integer($tokenDouble->intValue());
                    $returnValues->add($tokenInteger);
				} else {
					throw new FunctionException("Invalid values in string.");
				}

                $tokenCtr++;
			}
		} catch (Exception $e) {
			throw new FunctionException("Invalid values in string.", $e);
		}

		return $returnValues;
	}

    /**
     * This methods takes a string of input function arguments, evaluates each
     * argument and creates a one String and two Integers value for each
     * argument from the result of the evaluations.
     *
     * @param \Tbm\Peval\Types\String $arguments
     * @param $delimiter
     *            The delimiter to use while parsing.
     *
     * @return \Tbm\Peval\Collection\ArrayList An array list of object values found in the input string.
     *
     * @throws \Tbm\Peval\Func\FunctionException
     *                Thrown if the string does not properly parse into the
     *                proper objects.
     */
	public static function getOneStringAndTwoIntegers(String $arguments, $delimiter)
    {
		$returnValues = new ArrayList();

		try {
			$tokenizer = new ArgumentTokenizer($arguments, $delimiter);

			$tokenCtr = 0;
			while ($tokenizer->hasMoreTokens()) {
				if ($tokenCtr == 0) {
					$token = $tokenizer->nextToken()->trim();
                    $returnValues.add($token);
				} else if ($tokenCtr == 1 || $tokenCtr == 2) {
					$token = $tokenizer->nextToken()->trim();
                    $tokenDouble = new Double($token);
                    $tokenInteger = new Integer($tokenDouble->intValue());
                    $returnValues->add($tokenInteger);
				} else {
					throw new FunctionException("Invalid values in string.");
				}

                $tokenCtr++;
			}
		} catch (Exception $e) {
			throw new FunctionException("Invalid values in string.", $e);
		}

		return $returnValues;
	}
}
