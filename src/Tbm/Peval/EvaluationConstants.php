<?php

namespace Tbm\Peval;

use Tbm\Peval\Types\String;

/**
 * Contains constants used by classes in this package.
 */
class EvaluationConstants
{
	/**
	 * The single quote character.
	 */
	const SINGLE_QUOTE = "'";

	/**
	 * The double quote character.
	 */
	const DOUBLE_QUOTE = '"';

	/**
	 * The open brace character.
	 */
	const OPEN_BRACE = '{';

	/**
	 * The closed brace character.
	 */
	const CLOSED_BRACE = '}';

	/**
	 * The pound sign character.
	 */
	const POUND_SIGN = '#';

	/**
	 * The open variable string.
	 */
    const OPEN_VARIABLE = '#{';

	/**
	 * The closed brace string.
	 */
    const CLOSED_VARIABLE = EvaluationConstants::CLOSED_BRACE;

	/**
	 * The true value for a Boolean string.
	 */
	const BOOLEAN_STRING_TRUE = "1.0";

	/**
	 * The false value for a Boolean string.
	 */
	const BOOLEAN_STRING_FALSE = "0.0";
	
	/**
	 * The comma character.
	 */
	const COMMA = ',';
	
	/**
	 * The function argument separator.
	 */
	const FUNCTION_ARGUMENT_SEPARATOR = EvaluationConstants::COMMA;
}
