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

class EvaluationHelper
{
    /**
     * Replaces all old string within the expression with new strings.
     *
     * @param String|\Tbm\Peval\Types\String $expression
     *            The string being processed.
     * @param String|\Tbm\Peval\Types\String $oldString
     *            The string to replace.
     * @param String|\Tbm\Peval\Types\String $newString
     *            The string to replace the old string with.
     *
     * @return mixed The new expression with all of the old strings replaced with new
     *         strings.
     */
    public function replaceAll(String $expression, String $oldString, String $newString) {

		$replacedExpression = $expression;

		if ($replacedExpression != null) {
			$charCtr = 0;
			$oldStringIndex = $replacedExpression->indexOf($oldString, $charCtr);

			while ($oldStringIndex > -1) {
				// Remove the old string from the expression.
				$buffer = new StringBuffer($replacedExpression
                    ->subString(0, oldStringIndex)->getValue() .
					    $replacedExpression->substring($oldStringIndex + $oldString.length()));

				// Insert the new string into the expression.
				$buffer.insert($oldStringIndex, $newString);

				$replacedExpression = buffer.toString();

				$charCtr = $oldStringIndex + $newString->length();

				// Determine if we need to continue to search.
				if ($charCtr < $replacedExpression->length()) {
					$oldStringIndex = $replacedExpression->indexOf($oldString, $charCtr);
				} else {
					$oldStringIndex = -1;
				}
			}
		}

		return $replacedExpression;
	}

	/**
	 * Determines if a character is a space or white space.
	 * 
	 * @param character
	 *            The character being evaluated.
	 * 
	 * @return True if the character is a space or white space and false if not.
	 */
	public function isSpace($character)
    {
		if ($character == ' ' || $character == '\t' || $character == '\n'
				|| $character == '\r' || $character == '\f') {
			return true;
		}

		return false;
	}
}
