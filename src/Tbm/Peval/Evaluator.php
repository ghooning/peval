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

use Tbm\Peval\EvaluationHelper;
use Tbm\Peval\ExpressionOperand;
use Tbm\Peval\ExpressionOperator;
use Tbm\Peval\Collection\ArrayList;
use Tbm\Peval\Collection\Stack;
use Tbm\Peval\Collection\Map;
use Tbm\Peval\EvaluationConstants;
use Tbm\Peval\EvaluationException;
use Tbm\Peval\NextOperator;
use Tbm\Peval\ParsedFunction;
use Tbm\Peval\ExpressionTree;
use Tbm\Peval\Types\String;
use Tbm\Peval\Types\Double;
use Tbm\Peval\Func\FunctionConstants;
use Tbm\Peval\Func\String\StringFunctions;
use Tbm\Peval\Func\IFunction;
use Tbm\Peval\Operator\OpenParenthesesOperator;
use Tbm\Peval\Operator\ClosedParenthesesOperator;
use Tbm\Peval\Operator\AdditionOperator;
use Tbm\Peval\Operator\BooleanAndOperator;
use Tbm\Peval\Operator\BooleanNotOperator;
use Tbm\Peval\Operator\BooleanOrOperator;
use Tbm\Peval\Operator\DivisionOperator;
use Tbm\Peval\Operator\EqualOperator;
use Tbm\Peval\Operator\GreaterThanOperator;
use Tbm\Peval\Operator\GreaterThanOrEqualOperator;
use Tbm\Peval\Operator\LessThanOperator;
use Tbm\Peval\Operator\LessThanOrEqualOperator;
use Tbm\Peval\Operator\ModulusOperator;
use Tbm\Peval\Operator\MultiplicationOperator;
use Tbm\Peval\Operator\NotEqualOperator;
use Tbm\Peval\Operator\SubtractionOperator;


/**
 * This class is used to evaluate mathematical, string, Boolean and functional
 * expressions. It is the main entry point into the JEval API.<br>
 * <br>
 * The following types of expressions are supported:<br>
 * <ul>
 * <li><i>mathematical</i> Expression involving numbers. Numbers are treated
 * as doubles, so resulting numbers will contain at least one decimal place.</li>
 * <li><i>string</i> String can also be added together, compared, etc...</li>
 * <li><i>Boolean</i> Expression that evaluate to true (1.0) and false (0.0).</li>
 * <li><i>functional</i> Custom functions can be created or there are many
 * Math and String functions that JEval supplies with this class.</li>
 * </ul>
 * The following operators are supported:<br>
 * <ul>
 * <li>( open parentheses</li>
 * <li>) closed parentheses</li>
 * <li>+ addition (for numbers and strings)</li>
 * <li>- subtraction</li>
 * <li>* multiplication</li>
 * <li>/ division</li>
 * <li>% modulus</li>
 * <li>+ unary plus</li>
 * <li>- unary minus</li>
 * <li>= equal (for numbers and strings)</li>
 * <li>!= not equal (for numbers and strings)</li>
 * <li>< less than (for numbers and strings)</li>
 * <li><= less than or equal (for numbers and strings)</li>
 * <li>> greater than (for numbers and strings)</li>
 * <li>>= greater than or equal (for numbers and strings)</li>
 * <li>&& boolean and</li>
 * <li>|| boolean or</li>
 * <li>! boolean not</li>
 * </ul>
 * Allows for prebuilt and custom functions.<br>
 * <ul>
 * <li>JEval already comes with many functions which represent most of the
 * methods in the Math and String classes in the standard JDK.</li>
 * <li>Thirty-nine math and string functions come built in. See the
 * net.sourceforge.jeval.functions.math and
 * net.sourceforge.jeval.functions.string packages for details on these ready to
 * use functions. You can choose to not load these functions if we you want to
 * gain a small improvement in performance.</li>
 * <li>Functions must be followed by an open parentheses and a closed
 * parentheses which contain any required parameters.</li>
 * <li>For more details on functions, see the Function class and the test
 * classes.</li>
 * </ul>
 * Allows for variables.<br>
 * <ul>
 * <li>Variable must be enclosed by a pound sign and open brace #{ and a closed
 * brace }. i.e. expression = "#{a} + #{b}"</li>
 * <li>Two math variables come built in. The E and PI variables represent the
 * same value as the Math.E and Math.PI constants in the standard Java SDK. You
 * can choose not to load these variables.</li>
 * </ul>
 * Notes on expression parsing:
 * <ul>
 * <li>Spaces are ignored when parsing expressions.</li>
 * <li>The order of precedence used by this class is as follows from highest to
 * lowest.</li>
 * <li>The expression is evaluated as one or more subexpressions.
 * Subexpressions within open parentheses and closed parentheses are evaluated
 * before other parts of the expression.</li>
 * <li>Inner most subexpression are evaluated first working outward.</li>
 * <li>Subexpressions at the same level are evaluated from left to right.</li>
 * <li>When evaluating expressions and subexpressions, operators are evaluated
 * with the following precedence listed below.</li>
 * <li>Operators with with the same precedence are evaluated from left to
 * right.</li>
 * <li>Once the expression is parsed, Variables are replaced with their values.
 * The evaluator has its own internal variable map that it used to resolve
 * variable values. All of the variable related methods on the evaluator refer
 * to this internal map. You can choose to set you own variable resolver on your
 * evaluator instance. If you do this, then variables resolved by your resolver
 * will override any variables in the evaluator's internal variable map.</li>
 * <li>Functions are then executed and replaced with their results. Function
 * arguments are each individually evaluated as subexpressions that are comma
 * separated. This gives you the ability to use nested functions in your
 * expressions. You can choose not to evaluate function arguments as expressions
 * and instead let the functions handle the arguments themselves. This in effect
 * turns off nested expressions, unless you code nested expression support into
 * your own custom functions.</li>
 * <li>Once all variables and functions are resolved, then the parsed
 * expression and subexpressions are evaluated according to operator precedence.</li>
 * </ul>
 * Operator precedence:
 * <ul>
 * <li>+ unary plus, - unary minus, ! boolean not</li>
 * <li>* multiplication, / division, % modulus</li>
 * <li>+ addition, - subtraction</li>
 * <li>< less than, <= less than or equal, > greater than, >= greater than or
 * equal</li>
 * <li>= equal, != not equal</li>
 * <li>&& boolean and</li>
 * <li>|| boolean or</li>
 * </ul>
 * Function and variable names can not break any of the following rules:<br>
 * <ul>
 * <li>can not start with a number</li>
 * <li>can not contain an operator (see the above list of operators)/li>
 * <li>can not contain a quote character - single or double/li>
 * <li>can not contain a brace character - open or closed/li>
 * <li>can not contain one of the following special characters: #, ~ , ^ !</li>
 * </ul>
 * Other Notes:
 * <ul>
 * <li>This class is not thread safe.</li>
 * <li>Allows for the quote character (single or double) to be specified at run
 * time. Quote characters are required for specifying string values.</li>
 * <li>Expressions can contain different types of expressions within the same
 * expression. However, Numeric and string types can not be mixed in a left /
 * right operand pair.</li>
 * <li>An expression can be parsed before being evaluated by calling the parse()
 * method. This may save on response time if parsing takes more than a few 
 * seconds. However, parsing is usually very fast, so this is probably not 
 * needed.</li>
 * <li>If an expression does not change, it will not be parsed each
 * time the expression is evaluated. Therefore, variables values can change and
 * the expression can be evaluated again without having to re-parse the
 * expression.</li>
 * <li>Nested functions calls are supported. Nested function support can be
 * turned off to improve performance. Custom functions can be coded to handle
 * nested calls instead if desired.</li>
 * <li>The string used to start variables, "#{", can not appear in an
 * expression.
 * <li>See the evaluate methods in this class, JUnit tests and samples for more
 * details.</li>
 * </ul>
 */
class Evaluator {

	/**
	 * Contains all of the operators.
	 *
	 * @var array (List)
	 */
	private $operators = null;

	/**
	 * Contains all of the functions in use.
	 *
	 * @var array (Map)
	 */
	private $functions;

	/**
	 * Contains all of the variables in use.
	 *
	 * @var array (HashMap)
	 */
	private $variables = array();

	/**
	 * The quote character in use.
	 *
	 * @var string
	 */
	private $quoteCharacter = EvaluationConstants::SINGLE_QUOTE;

	/**
	 * The open parentheses operator.
	 *
	 * @var IOperator
	 */
	private $openParenthesesOperator = null;

	/**
	 * The closed parentheses operator.
	 *
	 * @var IOperator
	 */
	private $closedParenthesesOperator = null;

	/**
	 * Indicates if the user wants to load the system math variables.
	 *
	 * @var boolean
	 */
	private $loadMathVariables;

	/**
	 * Indicates if the user wants to load the system math functions.
	 *
	 * @var boolean
	 */
	private $loadMathFunctions;

	/**
	 * Indicates if the user wants to load the system string functions.
	 *
	 * @var boolean
	 */
	private $loadStringFunctions;
	
	/**
	 * Indicates if the user wants to process nested function calls.
	 *
	 * @var boolean
	 */
	private $processNestedFunctions;

	/**
	 * Saves the previous expression, because we do not want to parse it, if
	 * it did not change.
	 *
	 * @var string
	 */
	private $previousExpression = null;

	/**
	 * The previous stack of parsed operators.
	 *
	 * @var array (Stack)
	 */
	private $previousOperatorStack = null;

	/**
	 * The previous stack of parsed operands.
	 *
	 * @var array (Stack)
	 */
	private $previousOperandStack = null;

	/**
	 * The stack of parsed operators
	 *
	 * @var array (Stack)
	 */
	private $operatorStack = null;

	/**
	 * The stack of parsed operands.
	 *
	 * @var array (Stack)
	 */
	private $operandStack = null;
	
	/**
	 * Allows for user to set their own variable resolver.
	 *
	 * @var VariableResolver
	 */
	private $variableResolver = null;

	/**
	 * The default constructor. This constructor calls the five parameter
	 * Evaluator constructor and passes in the following default values:
	 * SINGLE_QUOTE, true, true, true and true.
	 */
	function __construct()
    {
        $this->operators = new ArrayList();
        $this->functions = new Map();

        $this->previousExpression = new String();
        $this->openParenthesesOperator = new OpenParenthesesOperator();
	    $this->closedParenthesesOperator = new ClosedParenthesesOperator();

        // TODO: LOAD MATH VARS (param 2)
		$this->evaluator5(EvaluationConstants::SINGLE_QUOTE, false, false, true, false);
	}

	/**
	 * The main constructor for Evaluator.
	 * 
	 * @param quoteCharacter
	 *            The quote character to use when evaluating expression.
	 * @param loadMathVariables
	 *            Indicates if the standard Math variables should be loaded or
	 *            not.
	 * @param loadMathFunctions
	 *            Indicates if the standard Math functions should be loaded or
	 *            not.
	 * @param loadStringFunctions
	 *            Indicates if the standard String functions should be loaded or
	 *            not.
	 * @param processNestedFunctions
	 *            Indicates if nested function calls should be processed or not.
	 * 
	 * @throws InvalidArgumentException
	 *                Thrown when the quote character is not a valid quote
	 *                character.
	 */
	public function evaluator5($quoteCharacter,
			$loadMathVariables, $loadMathFunctions,
			$loadStringFunctions,
			$processNestedFunctions) {

		// Install the operators used by Evaluator.
		$this->installOperators();

		// Install the system variables.
		$this->loadMathVariables = $loadMathVariables;
		$this->loadSystemVariables();

		// Install the system functions.
		$this->loadMathFunctions = $loadMathFunctions;
		$this->loadStringFunctions = $loadStringFunctions;
		$this->loadSystemFunctions();

		// Set the default quote character.
		$this->setQuoteCharacter($quoteCharacter);

		// Process nested function calls.
		$this->processNestedFunctions = $processNestedFunctions;
	}

	/**
	 * Returns the current quote character in use.
	 * 
	 * @return The quote character in use.
	 */
	public function getQuoteCharacter() {
		return $this->quoteCharacter;
	}

	/**
	 * Sets the quote character to use when evaluating expressions.
	 * 
	 * @param quoteCharacter
	 *            The quote character to use when evaluating expressions.
	 * 
	 * @throws InvalidArgumentException
	 *                Thrown when the quote character is not a valid quote
	 *                character.
	 */
	public function setQuoteCharacter($quoteCharacter) {
		if ($quoteCharacter == EvaluationConstants::SINGLE_QUOTE
				|| quoteCharacter == EvaluationConstants::DOUBLE_QUOTE) {
			$this->quoteCharacter = $quoteCharacter;
		} else {
			throw new InvalidArgumentException("Invalid quote character.");
		}
	}

    /**
     * Adds a function to the list of functions to use when evaluating
   	 * expressions.
     *
     * @param IFunction $function
     * @throws InvalidArgumentException
     *                Thrown when the function name is not valid or the function
   	 *                name is already in use.
     */
    public function putFunction(IFunction $function)
    {
		// Make sure the function name is valid.
		$this->isValidName($function->getName());

        $functionName = $function->getName()->getValue();

		// Make sure the function name isn't already in use.
		$existingFunction = $this->functions->get($functionName);

		if ($existingFunction == null) {
			$this->functions->put($functionName, $function);
		} else {
			throw new InvalidArgumentException("A function with the same name already exists.");
		}
	}

	/**
	 * Returns a function from the list of functions. If the function can not be
	 * found in the list of functions, then null will be returned.
	 * 
	 * @param functionName
	 *            The name of the function to retrieve the value for.
	 * 
	 * @return The value for a function in the list of function.
	 */
	public function getFunction($functionName) {
		return $this->functions->get($functionName);
	}

    /**
     * Removes the function from the list of functions to use when evaluating
   	 * expressions.
     *
     * @param $functionName
     *
     * @throws InvalidArgumentException
     *            The name of the function to remove.
     */
    public function removeFunction($functionName) {
		if ($this->functions->containsKey($functionName)) {
			$this->functions>remove($functionName);
		} else {
			throw new InvalidArgumentException("The function does not exist.");
		}
	}

	/**
	 * Removes all of the functions at one time.
	 */
	public function clearFunctions() {
		// Remove all functions.
		$this->functions->clear();

		// Reload the system functions if necessary.
		$this->loadSystemFunctions();
	}
	
	/**
	 * Returns the map of functions currently set on this object.
	 * 
	 * @return the map of functions currently set on this object.
	 */
	public function getFunctions() {
		return $this->functions;
	}
	
	/**
	 * Sets the map of functions for this object.
	 * 
	 * @param $functions The map of functions for this object.
	 */
	public function setFunctions($functions) {
		$this->functions = $functions;
	}

	/**
	 * Adds or replaces a variable to the list of variables to use when
	 * evaluating expressions. If the variable already exists, then its value
	 * will be overlaid.
	 * 
	 * @param variableName
	 *            The name of the variable being set.
	 * @param variableValue
	 *            The value for the variable being set.
	 */
	public function putVariable($variableName, $variableValue) {
		// Make sure the variable name is valid.
		$this->isValidName($variableName);

		$this->variables->put($variableName, $variableValue);
	}

    /**
     * Returns the value for a variable in the list of variables. If the
   	 * variable can not be found in the list of variables, then null will be
   	 * returned.
     *
     * @param $variableName
     *
     * @return null|string The value for a variable in the list of variables.
     *
     * @throws EvaluationException
     *             an EvaluatorException if the variable name can not be
   	 *             resolved.
     *
     */
    public function getVariableValue($variableName)
    {
		$variableValue = null;

		/*
		 * If the user has implemented a variable resolver and set it onto this
		 * object, then use it before looking in the variable map.
		 */
		if ($this->variableResolver != null) {
			try {
				$variableValue = $this->variableResolver->resolveVariable($variableName);
			} catch (FunctionException $fe) {
				throw new EvaluationException($fe->getMessage(), $fe);
			}
		}

		/*
		 * If no variable value at this point, then go to the internal variable
		 * map to resolve the variable.
		 */
		if ($variableValue == null) {
			$variableValue = (string) $this->variables->get($variableName);
		}

		if ($variableValue == null) {
			throw new EvaluationException("Can not resolve variable with name equal to '" . $variableName . "'");
		}

		return $variableValue;
	}

    /**
     * Removes the variable from the list of variables to use when evaluating
   	 * expressions.
     *
     * @param $variableName
     * @throws InvalidArgumentException
     *            The name of the variable to remove.
     */
    public function removeVaraible($variableName) {
		if ($this->variables->containsKey($variableName)) {
			$this->variables->remove($variableName);
		} else {
			throw new InvalidArgumentException("The variable does not exist.");
		}
	}

	/**
	 * Removes all of the variables at one time.
	 */
	public function clearVariables() {
		// Remove all functions.
		$this->variables->clear();

		// Reload the system variables if necessary.
		$this->loadSystemVariables();
	}
	
	/**
	 * Returns the map of variables currently set on this object.
	 * 
	 * @return the map of variables currently set on this object.
	 */
	public function getVariables() {
		return $this->variables;
	}
	
	/**
	 * Sets the map of variables for this object.
	 * 
	 * @param $variables The map of variables for this object.
	 */
	public function setVariables($variables) {
		$this->variables = $variables;
	}	
	
	/**
	 * Returns the variable resolver.  The variable resolver can be used by 
	 * the user to resolve their own variables.  Variables in the variable
	 * resolver override any variables that are in this classes internal
	 * variable map.
	 * 
	 * @return VariableResolver
	 */
	public function getVariableResolver() {
		return $this->variableResolver;
	}

	/**
	 * Sets the variable resolver for this class.  Varaibles resolved by the
	 * variable resolver will override any variables in this class's internal
	 * variable map.
	 * 
	 * @param VariableResolver $variableResolver
     *              The variable resolver for this class.
	 */
	public function setVariableResolver(VariableResolver $variableResolver) {
		$this->variableResolver = $variableResolver;
	}

	/**
	 * This method evaluates mathematical, boolean or functional expressions.
	 * See the class description and test classes for more information on how to
	 * write an expression. If quotes exist around a string expression, then
	 * they will be left in the result string. Function will also have their
	 * results wrapped with the appropriate quote characters.
	 * 
	 * @param expression
	 *            The expression to evaluate.
	 * 
	 * @return The result of the evaluated. expression. Numbers are treated as
	 *         doubles, so resulting numbers will contain at least one decimal
	 *         place.
	 * 
	 * @throws EvaluateException
	 *                Thrown when an error is found while evaluating the
	 *                expression.
	 */
	public function evaluate1($expression)
    {
		return $this->evaluate3($expression, true, true);
	}

    /**
     * This method evaluates mathematical, boolean or functional expressions.
   	 * See the class description and test classes for more information on how to
   	 * write an expression. The expression used will be the one previously
   	 * specified when using the parse method. If the parse method has not been
   	 * called before calling this method, then an exception will be thrown. If
   	 * quotes exist around a string expression, then they will be left in the
   	 * result string. Function will also have their results wrapped with the
   	 * appropriate quote characters.
     *
     * @return The result of the evaluated. expression. Numbers are treated as
   	 *         doubles, so resulting numbers will contain at least one decimal
   	 *         place.
     *
     * @throws EvaluationException
     *                Thrown when an error is found while evaluating the
   	 *                expression.
     */
    public function evaluate0()
    {
		// Get the previously parsed expression.
		$expression = $this->previousExpression;

		if ($expression == null || $expression.length() == 0) {
			throw new EvaluationException("No expression has been specified.");
		}

		return $this->evaluate3($expression, true, true);
	}

	/**
	 * This method evaluates mathematical, boolean or functional expressions.
	 * See the class description and test classes for more information on how to
	 * write an expression.
	 * 
	 * @param $expression

	 * @param $keepQuotes

	 * @param $wrapStringFunctionResults
	 *            Indicates if the results from functions that return strings
	 *            should be wrapped in quotes. The quote character used will be
	 *            whatever is the current quote character for this object.
	 * 
	 * @return The result of the evaluated expression. Numbers are treated as
	 *         doubles, so resulting numbers will contain at least one decimal
	 *         place.
	 * 
	 * @throws EvaluateException
	 *                Thrown when an error is found while evaluating the
	 *                expression.
	 */


    /**
     * This method is a simple wrapper around the evaluate(String) method. Its
     * purpose is to return a more friendly boolean return value instead of the
     * string "1.0" (for true) and "0.0" (for false) that is normally returned.
     *
     * @param expression
     *            The expression to evaluate.
     *
     * @return A boolean value that represents the result of the evaluated
     *         expression.
     *
     * @throws EvaluateException
     *                Thrown when an error is found while evaluating the
     *                expression. It is also thrown if the result is not able to
     *                be converted to a boolean value.
     */
    public function getBooleanResult($expression)
    {
        $result = $this->evaluate1($expression);

        try {
            $doubleResult = new Double($result);

            if ($doubleResult->doubleValue() == 1.0) {
                return true;
            }
        } catch (NumberFormatException $exception) {
            return false;
        }

        return false;
    }

    /**
     * @param $expression
     *            The expression to evaluate.
     * @param $keepQuotes
     *            Indicates if the the quotes should be kept in the result or
   	 *            not. This is only for string expression that are enclosed in
   	 *            quotes prior to being evaluated.
     * @param $wrapStringFunctionResults
     *            Indicates if the the quotes should be kept in the result or
   	 *            not. This is only for string expression that are enclosed in
   	 *            quotes prior to being evaluated.
     *
     * @return mixed
     */
    public function evaluate3($expression, $keepQuotes, $wrapStringFunctionResults)
    {
		// Parse the expression.
		$this->parse($expression);

		$result = $this->getResult($this->operatorStack, $this->operandStack, $wrapStringFunctionResults);
        $result = new String($result);

		// Remove the quotes if necessary.
		if ($this->isExpressionString($result) && !$keepQuotes) {
			$result = $result->substring(1, $result->length() - 1);
		}

		return $result;
	}

    /**
     * @param $keepQuotes
     *            Indicates if the the quotes should be kept in the result or
   	 *            not. This is only for string expressions that are enclosed in
   	 *            quotes prior to being evaluated.
     * @param $wrapStringFunctionResults
     *            Indicates if the results from functions that return strings
   	 *            should be wrapped in quotes. The quote character used will be
   	 *            whatever is the current quote character for this object.
     *
     * @return mixed The result of the evaluated expression. Numbers are treated as
     *         doubles, so resulting numbers will contain at least one decimal
     *         place.
     *
     * @throws EvaluationException
     */
    public function evaluate2($keepQuotes, $wrapStringFunctionResults)
    {
		// Get the previously parsed expression.
		$expression = $this->previousExpression;

		if ($expression == null || $expression.length() == 0) {
			throw new EvaluationException("No expression has been specified.");
		}

		return evaluate3($expression, $keepQuotes, $wrapStringFunctionResults);
	}

	/**
	 * This method is a simple wrapper around the evaluate(String) method. Its
	 * purpose is to return a more friendly double return value instead of the
	 * string number that is normally returned.
	 * 
	 * @param $expression
	 *            The expression to evaluate.
	 * 
	 * @return A double value that represents the result of the evaluated
	 *         expression.
	 * 
	 * @throws EvaluateException
	 *                Thrown when an error is found while evaluating the
	 *                expression. It is also thrown if the result is not able to
	 *                be converted to a double value.
	 */

    /**
     * This method is a simple wrapper around the evaluate(String) method. Its
   	 * purpose is to return a more friendly double return value instead of the
   	 * string number that is normally returned.
   	 *
     * @param $expression
     * @return string
     * @throws EvaluationException
     */
    public function getNumberResult($expression)
    {
        $result = $this->evaluate1(expression);
        $doubleResult = null;

        try {
            $doubleResult = new Double(result);
        } catch (NumberFormatException $nfe) {
            throw new EvaluationException("Expression does not produce a number.", $nfe);
        }

		return doubleResult.doubleValue();
	}

    /**
     * This method parses a mathematical, boolean or functional expressions.
   	 * When the expression is eventually evaluated, as long as the expression
   	 * has not changed, it will not have to be reparsed. See the class
   	 * description and test classes for more information on how to write an
   	 * expression.
     *
     * @param $expression
     *            The expression to evaluate.
     *
     * @throws EvaluationException
     *                Thrown when an error is found while evaluating the
   	 *                expression.
     */
    public function parse(String $expression)
    {
		// Save the expression.
		$parse = true;
		if (!$expression->equals($this->previousExpression)) {
			$this->previousExpression = $expression;
		} else {
			$parse = false;
			$this->operatorStack = clone $this->previousOperatorStack;
			$this->operandStack = clone $this->previousOperandStack;
		}

		try {
			if ($parse) {
				// These stacks will keep track of the operands and operators.
				$this->operandStack = new Stack();
				$this->operatorStack = new Stack();

				// Flags to help us keep track of what we are processing.
				$haveOperand = false;
				$haveOperator = false;
				$unaryOperator = null;

				// We are going to process until we get to the end, so get the
				// length.
				$numChars = $expression->length();
				$charCtr = 0;

				// Process until the counter exceeds the length. The goal is to
				// get
				// all of the operands and operators.
				while ($charCtr < $numChars) {
					$operator = null;
					$operatorIndex = -1;

					// Skip any white space.
					if (EvaluationHelper::isSpace($expression->charAt($charCtr))) {
						$charCtr++;
						continue;
					}

					// Get the next operator.
					$nextOperator = $this->getNextOperator($expression, $charCtr, null);

					if ($nextOperator != null) {
						$operator = $nextOperator->getOperator();
						$operatorIndex = $nextOperator->getIndex();
					}

					// Check if it is time to process an operand.
					if ($operatorIndex > $charCtr || $operatorIndex == -1) {
						$charCtr = $this->processOperand($expression, $charCtr,
								$operatorIndex, $this->operandStack, $unaryOperator);

						$haveOperand = true;
						$haveOperator = false;
						$unaryOperator = null;
					}

					// Check if it is time to process an operator.
					if ($operatorIndex == $charCtr) {
						if ($nextOperator->getOperator()->isUnary()
								&& ($haveOperator || $charCtr == 0)) {
							$charCtr = $this->processUnaryOperator($operatorIndex,
									$nextOperator->getOperator());

							if ($unaryOperator == null) {
								// We have an unary operator.
								$unaryOperator = $nextOperator->getOperator();
							} else {
								throw new EvaluationException(
										"Consecutive unary "
												. "operators are not allowed (index="
												. $charCtr . ").");
							}
						} else {
							$charCtr = $this->processOperator($expression,
									$operatorIndex, $operator, $this->operatorStack,
									$this->operandStack, $haveOperand, $unaryOperator);

							$unaryOperator = null;
						}

						if (!($nextOperator->getOperator() instanceof ClosedParenthesesOperator)) {
							$haveOperand = false;
							$haveOperator = true;
						}
					}
				}

				// Save the parsed operators and operands.
				$this->previousOperatorStack = clone $this->operatorStack;
				$this->previousOperandStack = clone $this->operandStack;
			}
		} catch (Exception $e) {
			// Clear the previous expression, because it is invalid.
			$this->previousExpression = "";

			throw new EvaluationException($e.getMessage(), $e);
		}
	}

	/**
	 * Install all of the operators into the list of operators to use when
	 * evaluating expressions.
	 */
	private function installOperators() {
		// Install the most used operators first.
		$this->operators->add($this->openParenthesesOperator);
        $this->operators->add($this->closedParenthesesOperator);
        $this->operators->add(new AdditionOperator());
        $this->operators->add(new SubtractionOperator());
        $this->operators->add(new MultiplicationOperator());
        $this->operators->add(new DivisionOperator());
        $this->operators->add(new EqualOperator());
        $this->operators->add(new NotEqualOperator());

		// If there is a first character conflict between two operators,
		// then install the operator with the greatest length first.
        $this->operators->add(new LessThanOrEqualOperator()); // Length of 2.
        $this->operators->add(new LessThanOperator()); // Length of 1.
        $this->operators->add(new GreaterThanOrEqualOperator()); // Length of 2.
        $this->operators->add(new GreaterThanOperator()); // Length of 1.

		// Install the least used operators last.
        $this->operators->add(new BooleanAndOperator());
        $this->operators->add(new BooleanOrOperator());
        $this->operators->add(new BooleanNotOperator());
        $this->operators->add(new ModulusOperator());
	}

    /**
     * Processes the operand that has been found in the expression.
   	 *
   	 * @param $expression
   	 *            The expression being evaluated.
     * @param $charCtr
     * @param $operatorIndex
   	 *            The position in the expression where the current operator
   	 *            being processed is located.
   	 * @param $operandStack
   	 *            The stack of operands.
   	 * @param $unaryOperator
   	 *            The unary operator if we are working with one.
     *
     * @return string
     *
     * @throws EvaluationException
     */
    private function processOperand($expression, $charCtr,
			$operatorIndex, $operandStack,
			$unaryOperator)
    {
		$operandString = null;
		$rtnCtr = -1;

		// Get the operand to process.
		if ($operatorIndex == -1) {
			$operandString = $expression->substring($charCtr)->trim();
			$rtnCtr = $expression->length();
		} else {
			$operandString = $expression->substring($charCtr, trim($operatorIndex));
			$rtnCtr = $operatorIndex;
		}

		if ($operandString->length() == 0) {
			throw new EvaluationException("Expression is invalid.");
		}

		$operand = new ExpressionOperand($operandString, $unaryOperator);
        $operandStack->push($operand);

		return $rtnCtr;
	}

    /**
     * @param $expression
     *            The expression being evaluated.
     * @param $originalOperatorIndex
     *            The position in the expression where the current operator
   	 *            being processed is located.
     * @param $originalOperator
     *            The operator being processed.
     * @param $operatorStack
     *            The stack of operators.
     * @param $operandStack
     *            The stack of operands.
     * @param $haveOperand
     *            Indicates if have an operand to process.
     * @param $unaryOperator
     *            The unary operand associated with thi operator. This may be
   	 *            null.

     * @return mixed The new position in the expression where processing should
     *            continue.
     *
     * @throws EvaluationException
     *                Thrown is an error is encoutnered while processing the
   	 *                expression.
     */
    private function processOperator($expression,
			$originalOperatorIndex, $originalOperator,
			&$operatorStack, &$operandStack,
			$haveOperand, $unaryOperator)
	{
		$operatorIndex = $originalOperatorIndex;
		$operator = $originalOperator;

		// If we have and operand and the current operator is an instance
		// of OpenParenthesesOperator, then we are ready to process a function.
		if ($haveOperand && $operator instanceof OpenParenthesesOperator) {
			$nextOperator = $this->processFunction($expression,
					$operatorIndex, $operandStack);

			$operator = $nextOperator->getOperator();
			$operatorIndex = $nextOperator->getIndex() + $operator->getLength();

			$nextOperator = $this->getNextOperator($expression, $operatorIndex, null);

			// Look to see if there is another operator.
			// If there is, the process it, else get out of this routine.
			if ($nextOperator != null) {
				$operator = $nextOperator->getOperator();
				$operatorIndex = $nextOperator->getIndex();
			} else {
				return $operatorIndex;
			}
		}

		// Determine what type of operator we are left with and process
		// accordingly.
		if ($operator instanceof OpenParenthesesOperator) {
			$expressionOperator = new ExpressionOperator(
					$operator, $unaryOperator);
			$this->operatorStack->push($expressionOperator);
		} else if ($operator instanceof ClosedParenthesesOperator) {
			$stackOperator = null;

			if ($operatorStack->size() > 0) {
				$stackOperator = $operatorStack->peek();
			}

			// Process until we reach an open parentheses.
			while ($stackOperator != null
					&& !($stackOperator->getOperator() instanceof OpenParenthesesOperator)) {
				$this->processTree($operandStack, $operatorStack);

				if ($operatorStack->size() > 0) {
					$stackOperator = $operatorStack->peek();
				} else {
					$stackOperator = null;
				}
			}

			if ($operatorStack->isEmpty()) {
				throw new EvaluationException("Expression is invalid.");
			}

			// Pop the open parameter from the stack.
			$expressionOperator = $operatorStack->pop();

			if (!($expressionOperator->getOperator() instanceof OpenParenthesesOperator)) {
				throw new EvaluationException("Expression is invalid.");
			}

			// Process the unary operator if we have one.
			if ($expressionOperator->getUnaryOperator() != null) {
				$operand = $operandStack->pop();

				$tree = new ExpressionTree($this, $operand, null,
						null, $expressionOperator->getUnaryOperator());

				$operandStack->push($tree);
			}
		} else {
			// Process non-param operator.
			if ($operatorStack->size() > 0) {
				$stackOperator = $operatorStack->peek();

				while ($stackOperator != null
						&& $stackOperator->getOperator()->getPrecedence() >= $operator->getPrecedence()) {
					$this->processTree($operandStack, $operatorStack);

					if ($operatorStack->size() > 0) {
						$stackOperator = $operatorStack->peek();
					} else {
						$stackOperator = null;
					}
				}
			}

			$expressionOperator = new ExpressionOperator($operator, $unaryOperator);

			$operatorStack->push($expressionOperator);
		}

		$rtnCtr = $operatorIndex + $operator->getLength();

		return $rtnCtr;
	}

	/**
	 * Processes the unary operator that has been found in the expression.
	 * 
	 * @param $operatorIndex
	 *            The position in the expression where the current operator
	 *            being processed is located.
	 * @param $operator
	 *            The operator being processed.
	 * 
	 * @return The new position in the expression where processing should
	 *         continue.
	 */
	private function processUnaryOperator($operatorIndex, $operator) {

		$rtnCtr = $operatorIndex + $operator->getSymbol()->length();

		return $rtnCtr;
	}

    /**
     * @param $expression
     *            The expression being evaluated.
     * @param $operatorIndex
     *            The position in the expression where the current operator
   	 *            being processed is located.
     * @param $operandStack
     *            The stack of operands.
     *
     * @return mixed The next operator in the expression. This should be the closed
     *         parentheses operator.
     *
     * @throws EvaluationException
     */
    private function processFunction($expression, $operatorIndex, $operandStack)
	{
		$parenthesisCount = 1;
		$nextOperator = null;
		$nextOperatorIndex = $operatorIndex;

		// Loop until we find the function's closing parentheses.
		while ($parenthesisCount > 0) {
			$nextOperator = $this->getNextOperator($expression, $nextOperatorIndex + 1, null);

			if ($nextOperator == null) {
				throw new EvaluationException("Function is not closed.");
			} else if ($nextOperator->getOperator() instanceof OpenParenthesesOperator) {
				$parenthesisCount++;
			} else if ($nextOperator->getOperator() instanceof ClosedParenthesesOperator) {
				$parenthesisCount--;
			}

			// Get the next operator index.
			$nextOperatorIndex = $nextOperator->getIndex();
		}

		// Get the function argument.
		$arguments = $expression->substring($operatorIndex + 1, $nextOperatorIndex);

		// Pop the function name from the stack.
		$operand = $operandStack->pop();
		$unaryOperator = $operand->getUnaryOperator();
		$functionName = $operand->getValue();

		// Validate that the function name is valid.
		try {
			$this->isValidName($functionName);
		} catch (InvalidArgumentException $iae) {
			throw new EvaluationException("Invalid function name of '". $functionName . "'.", $iae);
		}

		// Get the function object.
		$function = $this->functions->get($functionName->getValue());

		if ($function == null) {
			throw new EvaluationException("A function is not defined (index=" . $operatorIndex . ").");
		}

		$parsedFunction = new ParsedFunction($function, $arguments, $unaryOperator);
		$operandStack->push($parsedFunction);

		return $nextOperator;
	}

	/**
	 * Processes an expression tree that has been parsed into an operand stack
	 * and operator stack.
	 * 
	 * @param operandStack
	 *            The stack of operands.
	 * @param operatorStack
	 *            The stack of operators.
	 */
	private function processTree(&$operandStack, &$operatorStack) {

		$rightOperand = null;
		$leftOperand = null;
		$operator = null;

		// Get the right operand node from the tree.
		if ($operandStack->size() > 0) {
			$rightOperand = $operandStack->pop();
		}

		// Get the left operand node from the tree.
		if ($operandStack->size() > 0) {
			$leftOperand = $operandStack->pop();
		}

		// Get the operator node from the tree.
		$operator = $operatorStack->pop()->getOperator();

		// Build an expression tree from the nodes.
		$tree = new ExpressionTree($this, $leftOperand, $rightOperand, $operator, null);

		// Push the tree onto the stack.
		$operandStack->push($tree);
	}

    /**
     * @param $operatorStack
   	 *            The stack of operators.
   	 * @param $operandStack
   	 *            The stack of operands.
   	 * @param $wrapStringFunctionResults
   	 *            Indicates if the results from functions that return strings
   	 *            should be wrapped in quotes. The quote character used will be
   	 *            whatever is the current quote character for this object.
     * @return string The final result of the evaluated expression.
     *
     * @throws EvaluationException
     *                Thrown is an error is encoutnered while processing the
   	 *                expression.
     */
    private function getResult(&$operatorStack, &$operandStack, $wrapStringFunctionResults)
	{
		// The result to return.
		$resultString = null;

		// Process the rest of the operators left on the stack.
		while ($operatorStack->size() > 0) {
			$this->processTree($operandStack, $operatorStack);
		}

		// At this point only one operand should be left on the tree.
		// It may be a tree operand that contains other tree and/or
		// other operands.
		if ($operandStack->size() != 1) {
			throw new EvaluationException("Expression is invalid.");
		}

		$finalOperand = $operandStack->pop();

		// Check if the final operand is a tree.
		if ($finalOperand instanceof ExpressionTree) {
			// Get the final result.
			$resultString = $finalOperand->evaluate($wrapStringFunctionResults);
		}
		// Check if the final operand is an operand.
		else if ($finalOperand instanceof ExpressionOperand) {
			$resultExpressionOperand = $finalOperand;

			$resultString = $finalOperand->getValue();
			$resultString = $this->replaceVariables($resultString);

			// Check if the operand is a string or not. If it is not a string,
			// then it must be a number.
			if (!$this->isExpressionString($resultString)) {
				$resultDouble = null;
				try {
					$resultDouble = new Double($resultString);
				} catch (Exception $e) {
					throw new EvaluationException("Expression is invalid.", $e);
				}

				// Process a unary operator if one exists.
				if ($resultExpressionOperand->getUnaryOperator() != null) {
					$resultDouble = new Double($resultExpressionOperand
							->getUnaryOperator()->evaluate($resultDouble));
				}

				// Get the final result.
				$resultString = $resultDouble->toString();
			} else {
				if ($resultExpressionOperand->getUnaryOperator() != null) {
					throw new EvaluationException("Invalid operand for unary operator.");
				}
			}
		} else if ($finalOperand instanceof ParsedFunction) {
			$parsedFunction = $finalOperand;
			$function = $parsedFunction->getFunction();
			$arguments = $parsedFunction->getArguments();
			
			if ($this->processNestedFunctions) {
				$arguments = $this->processNestedFunctions($arguments);
			}
			
			$arguments = $this->replaceVariables($arguments);

			// Get the final result.
			try {				
				$functionResult = $function->execute($this, $arguments);
				$resultString = $functionResult->getResult();

				if ($functionResult->getType() == FunctionConstants::FUNCTION_RESULT_TYPE_NUMERIC) {
					$resultDouble = new Double($resultString);

					// Process a unary operator if one exists.
					if ($parsedFunction->getUnaryOperator() != null) {
						$resultDouble = new Double($parsedFunction->getUnaryOperator()->evaluate1($resultDouble->doubleValue()));
					}

					// Get the final result.
					$resultString = $resultDouble->toString();
				} 
				else if ($functionResult->getType() == FunctionConstants::FUNCTION_RESULT_TYPE_STRING) {
					// The result must be a string result.
					if ($wrapStringFunctionResults) {
						$resultString = $this->quoteCharacter . $resultString . $this->quoteCharacter;
					}

					if ($parsedFunction->getUnaryOperator() != null) {
						throw new EvaluationException("Invalid operand for unary operator.");
					}
				}
			} catch (FunctionException $fe) {
				throw new EvaluationException($fe->getMessage(), $fe);
			}
		} else {
			throw new EvaluationException("Expression is invalid.");
		}

		return $resultString;
	}

	/**
	 * Returns the next operator in the expression.
	 * 
	 * @param expression
	 *            The expression being evaluated.
	 * @param start
	 *            The position in the expression to start searching for the next
	 *            operator.
	 * @param match
	 *            The operator to search for. This can be null if you want the
	 *            very next operator. If it is not null, it searches until it
	 *            finds the match.
	 * 
	 * @return The next operator in the expression. Returns null if no next
	 *         operator is returned.
	 */
	private function getNextOperator($expression, $start, $match) {

		$numChars = $expression->length();
		$numQuoteCharacters = 0;

		for ($charCtr = $start; $charCtr < $numChars; $charCtr++) {
			// Keep track of open strings.
			if ($expression->charAt($charCtr) == $this->quoteCharacter) {
				$numQuoteCharacters++;
			}

			// Do not look into open strings.
			if (($numQuoteCharacters % 2) == 1) {
				continue;
			}

			// Assumes the operators are installed in order of length.
			$numOperators = $this->operators->size();
			for ($operatorCtr = 0; $operatorCtr < $numOperators; $operatorCtr++) {
				$operator = $this->operators->get($operatorCtr);

				if ($match != null) {
					// Look through the operators until we find the
					// one we are searching for.
					if (!$match->equals($operator)) {
						continue;
					}
				}

				// The operator can have 1 or 2 characters in length.
				if ($operator->getLength() == 2) {
					$endCtr = -1;
					if ($charCtr + 2 <= $expression->length()) {
						$endCtr = $charCtr + 2;
					} else {
						$endCtr = $expression->length();
					}

					// Look for a match.
					if ($expression->substring($charCtr, $endCtr)->trim()->equals($operator->getSymbol())) {
						$nextOperator = new NextOperator($operator, $charCtr);

						return $nextOperator;
					}
				} else {
					// Look for a match.
					if ($expression->charAt($charCtr) == $operator->getSymbol()->charAt(0)) {
						$nextOperator = new NextOperator($operator, $charCtr);

						return $nextOperator;
					}
				}
			}
		}

		return null;
	}

    /**
     * Determines if the string represents a valid expression string or not.
   	 * Valid expression strings must start and end with a quote character.

     * @param $expressionString
     *            The string being evaluated.
     *
     * @return bool True if the string is a valid string and false if not.
     *
     * @throws EvaluationException
     */
    public function isExpressionString(String $expressionString)
	{
		if ($expressionString->length() > 1
				&& $expressionString->charAt(0) == $this->quoteCharacter
				&& $expressionString->charAt($expressionString->length() - 1) == $this->quoteCharacter) {
			return true;
		}

        $idx = $expressionString->indexOf($this->quoteCharacter);
		if ($idx >= 0) {
			throw new EvaluationException("Invalid use of quotes.");
		}

		return false;
	}

	/**
	 * This method verifies if a function or variable name is valid or not.
	 * 
	 * Function and variable names must follow these rules...
	 * <ul>
	 * <li>can not start with a number</li>
	 * <li>can not contain an operator (see the above list of operators)</li>
	 * <li>can not contain a quote character - single or double</li>
	 * <li>can not contain a brace character - open or closed</li>
	 * <li>can not contain one of the following special characters: #, ~ , ^ !</li>
	 * <ul>
	 * 
	 * @param String|\Tbm\Peval\Types\String $name
	 *            The function or variable name being validated.
	 * 
	 * @throws InvalidArgumentException
	 *                Thrown if the name is invalid.
	 */
	public function isValidName(String $name)
    {
		if ($name->length() == 0) {
			throw new InvalidArgumentException("Variable is empty.");
		}

		// Check if name starts with a number.
		$firstChar = $name->charAt(0);
		if ($firstChar >= '0' && $firstChar <= '9') {
			throw new InvalidArgumentException("A variable or function name can not start with a number.");
		}

		// Check if name contains a quote character.
        $idx = $name->indexOf(EvaluationConstants::SINGLE_QUOTE);
		if ($idx > -1) { //EvaluationConstants::SINGLE_QUOTE) > -1) {
			throw new InvalidArgumentException("A variable or function name can not contain a quote character.");
		}
        $idx = $name->indexOf(EvaluationConstants::DOUBLE_QUOTE);
        if ($idx > -1) {
            throw new InvalidArgumentException("A variable or function name can not contain a quote character.");
        }

		// Check if name contains a brace character.
        $idx = $name->indexOf(EvaluationConstants::OPEN_BRACE);
		if ($idx > -1) {
			throw new InvalidArgumentException("A variable or function name can not contain an open brace character.");
		}
        $idx = $name->indexOf(EvaluationConstants::CLOSED_BRACE);
        if ($idx > -1) {
            throw new InvalidArgumentException("A variable or function name can not contain a closed brace character.");
        }
        $idx = $name->indexOf(EvaluationConstants::POUND_SIGN);
        if ($idx > -1) {
            throw new InvalidArgumentException("A variable or function name can not contain a pound sign character.");
        }

		// Check if name contains an operator character.
		$operatorIterator = $this->operators->iterator();

		while ($operatorIterator->valid()) {
            $operator = $operatorIterator->current();

            $idx = $name->indexOf($operator->getSymbol()->getValue());
			if ($idx > -1) {
				throw new InvalidArgumentException("A variable or function name can not contain an operator symbol.");
			}

            $operatorIterator->next();
		}

		// Check if name contains other special characters.
        $idx = $name->indexOf("!");
		if ($idx > -1) {
			throw new InvalidArgumentException("A variable or function name can not contain a special character.");
		}
        $idx = $name->indexOf("~");
        if ($idx > -1) {
			throw new InvalidArgumentException("A variable or function name can not contain a special character.");
		}
        $idx = $name->indexOf("^");
        if ($idx > -1) {
			throw new InvalidArgumentException("A variable or function name can not contain a special character.");
		}
        $idx = $name->indexOf(",");
        if ($idx > -1) {
			throw new InvalidArgumentException("A variable or function name can not contain a special character.");
		}
	}

	/**
	 * This method loads the system functions is necessary.
	 */
	private function loadSystemFunctions() {
		// Install the math functions.
		if ($this->loadMathFunctions) {
			$mathFunctions = new MathFunctions();

			$mathFunctions.load($this);
		}

		// Install the string functions.
		if ($this->loadStringFunctions) {
			$stringFunctions = new StringFunctions();

			$stringFunctions->load($this);
		}
	}

	/**
	 * This method loads the system variables is necessary.
	 */
	private  function loadSystemVariables() {
		// Install the math variables.
		if ($this->loadMathVariables) {
			// Add the two math variables.

            $e = new Double(Math::E);
            $pi = new Double(Math::PI);
			$this->putVariable("E", $e->toString());
			$this->putVariable("PI", $pi->toString());
		}
	}

    /**
     * Replaces the variables in the expression with the values of the variables
   	 * for this instance of the evaluator.

     * @param $expression
     *            The expression being processed.
     *
     * @return mixed A new expression with the variables replaced with their values.
     *
     * @throws EvaluationException
     *            Thrown is an error is encountered while processing the
   	 *            expression.
     */
    public function replaceVariables($expression)
    {
		$openIndex = $expression->indexOf(EvaluationConstants::OPEN_VARIABLE);

		if ($openIndex < 0) {
			return $expression;
		}

		$replacedExpression = $expression;

		while ($openIndex >= 0) {

			$closedIndex = -1;
			if ($openIndex >= 0) {
				$closedIndex = $replacedExpression->indexOf(
						EvaluationConstants::CLOSED_VARIABLE, $openIndex + 1);
				if ($closedIndex > $openIndex) {
					$variableName = $replacedExpression->substring($openIndex
									+ strlen(EvaluationConstants::OPEN_VARIABLE), $closedIndex);
					
					// Validate that the variable name is valid.
					try {
						$this->isValidName($variableName);
					} catch (InvalidArgumentException $iae) {
						throw new EvaluationException("Invalid variable name of '". $variableName + "'.", $iae);
					}
					
					$variableValue = $this->getVariableValue($variableName);

					$variableString = EvaluationConstants::OPEN_VARIABLE . $variableName . EvaluationConstants::CLOSED_VARIABLE;

					$replacedExpression = EvaluationHelper::replaceAll($replacedExpression, $variableString, $variableValue);
				} else {
					break;
				}
			}

			// Start looking at the beginning of the string, since
			// the length string has changed and characters have moved
			// positions.
			$openIndex = $replacedExpression.indexOf(
					EvaluationConstants::OPEN_VARIABLE);
		}

		// If an open brace is left over, then a variable could not be replaced.
		$openBraceIndex = $replacedExpression->indexOf(EvaluationConstants::OPEN_VARIABLE);
		if ($openBraceIndex > -1) {
			throw new EvaluationException("A variable has not been closed (index=" . $openBraceIndex . ").");
		}

		return $replacedExpression;
	}

    /**
     * This method process nested function calls that may be in the arguments
     * passed into a higher level function.
     *
     * @param String|\Tbm\Peval\Types\String $arguments
     *
     * @throws \Tbm\Peval\EvaluationException
     * @return \Tbm\Peval\Types\String The arguments with any nested function calls evaluated.
     *
     */
	public function processNestedFunctions(String $arguments)
	{
		$evaluatedArguments = new String();

		// Process nested function calls.
		if ($arguments->length() > 0) {

			$argumentsEvaluator = new Evaluator($this->quoteCharacter,
                $this->loadMathVariables, $this->loadMathFunctions, $this->loadStringFunctions,
                $this->processNestedFunctions);
            $this->argumentsEvaluator->setFunctions(getFunctions());
            $this->argumentsEvaluator->setVariables(getVariables());
            $this->argumentsEvaluator->setVariableResolver(getVariableResolver());

			$tokenizer = new ArgumentTokenizer($arguments, EvaluationConstants::FUNCTION_ARGUMENT_SEPARATOR);

			$evaluatedArgumentList = new ArrayList();
			while ($tokenizer.hasMoreTokens()) {

				$argument = $tokenizer->nextToken()->trim();

				try {
					$argument = $argumentsEvaluator->evaluate1($argument);
				} catch (Exception $e) {
					throw new EvaluationException($e->getMessage(), $e);
				}

				$evaluatedArgumentList->add($argument);
			}

			$evaluatedArgumentIterator = $evaluatedArgumentList->iterator();

			while ($evaluatedArgumentIterator->valid()) {
				if ($evaluatedArguments->length() > 0) {
					$evaluatedArguments->append(EvaluationConstants::FUNCTION_ARGUMENT_SEPARATOR);
				}

                $evaluatedArgumentIterator->next();
				$evaluatedArgument = (String) $evaluatedArgumentIterator->current();
				$evaluatedArguments.append($evaluatedArgument);
			}
		}

		return $evaluatedArguments;
	}

	/**
	 * Returns the value used during construction of this object to specify if
	 * math variables should be loaded.
	 * 
	 * @return the loadMathVariables
	 */
	public function isLoadMathVariables() {
		return $this->loadMathVariables;
	}

	/**
	 * Returns the value used during construction of this object to specify if
	 * math functions should be loaded.
	 * 
	 * @return the loadMathFunctions
	 */
	public function getLoadMathFunctions() {
		return $this->loadMathFunctions;
	}

	/**
	 * Returns the value used during construction of this object to specify if
	 * string functions should be loaded.
	 * 
	 * @return the loadStringFunctions
	 */
	public function getLoadStringFunctions() {
		return $this->loadStringFunctions;
	}

	/**
	 * Returns the value used during construction of this object to specify if
	 * nested functions should be processed.
	 * 
	 * @return the processNestedFunctions
	 */
	public function getProcessNestedFunctions() {
		return $this->processNestedFunctions;
	}
}
