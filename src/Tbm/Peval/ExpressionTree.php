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

use Tbm\Peval\Evaluator;
use Tbm\Peval\Operator\IOperator;
use Tbm\Peval\Types\String;
use Tbm\Peval\Types\Double;
use Tbm\Peval\Func\FunctionConstants;

/**
 * Represents an expression tree made up of a left operand, right operand,
 * operator and unary operator.
 */
class ExpressionTree
{
	// The left node in the tree.
	private $leftOperand = null;

	// The right node in the tree.
	private $rightOperand = null;

    /**
     * The operator for the two operands.
     *
     * @var IOperator
     */
	private $operator = null;

    /**
     * The unary operator, if one exists.
     *
     * @var IOperator
     */
	private $unaryOperator = null;

	// The Evaluator object processing this tree.
    /**
     *
     * @var Evaluator
     */
	private $evaluator = null;

	/**
	 * Creates a new ExpressionTree.
	 * 
	 * @param evaluator
	 *            The Evaluator object processing this tree.
	 * @param leftOperand
	 *            The left operand to place as the left node of the tree.
	 * @param rightOperand
	 *            The right operand to place as the right node of the tree.
	 * @param operator
	 *            The operator to place as the operator node of the tree.
	 * @param unaryOperator
	 *            The new unary operator for this tree.
	 */
	function __construct(Evaluator $evaluator, $leftOperand,
			$rightOperand, IOperator $operator = null,
			IOperator $unaryOperator = null)
    {

		$this->evaluator = $evaluator;
		$this->leftOperand = $leftOperand;
		$this->rightOperand = $rightOperand;
		$this->operator = $operator;
		$this->unaryOperator = $unaryOperator;
	}

	/**
	 * Returns the left operand of this tree.
	 * 
	 * @return The left operand of this tree.
	 */
	public function getLeftOperand() {
		return $this->leftOperand;
	}

	/**
	 * Returns the right operand of this tree.
	 * 
	 * @return The right operand of this tree.
	 */
	public function getRightOperand() {
		return $this->rightOperand;
	}

	/**
	 * Returns the operator for this tree.
	 * 
	 * @return IOperator The operator of this tree.
	 */
	public function getOperator() {
		return $this->operator;
	}

	/**
	 * Returns the unary operator for this tree.
	 * 
	 * @return IOperator The unary operator of this tree.
	 */
	public function getUnaryOperator() {
		return $this->unaryOperator;
	}

	/**
	 * Evaluates the operands for this tree using the operator and the unary
	 * operator.
	 * 
	 * @param wrapStringFunctionResults
	 *            Indicates if the results from functions that return strings
	 *            should be wrapped in quotes. The quote character used will be
	 *            whatever is the current quote character for this object.
	 * 
	 * @exception EvaluateException
	 *                Thrown is an error is encountered while processing the
	 *                expression.
     *
     * @return String
	 */
	public function evaluate($wrapStringFunctionResults)
    {
		$rtnResult = null;

		// Get the left operand.
		$leftResultString = null;
		$leftResultDouble = null;

		if ($this->leftOperand instanceof ExpressionTree) {
			$leftResultString = $this->leftOperand->evaluate($wrapStringFunctionResults);

			try {
				$leftResultDouble = new Double($leftResultString);
				$leftResultString = null;
			} catch (NumberFormatException $exception) {
				$leftResultDouble = null;
			}
		} else if ($this->leftOperand instanceof ExpressionOperand) {

			$leftExpressionOperand = $this->leftOperand;

			$leftResultString = $leftExpressionOperand->getValue();
			$leftResultString = $this->evaluator->replaceVariables($leftResultString);

			// Check if the operand is a string or not. If it is not a string,
			// then it must be a number.
			if (!$this->evaluator->isExpressionString($leftResultString)) {
				try {
					$leftResultDouble = new Double($leftResultString);
					$leftResultString = null;
				} catch (NumberFormatException $nfe) {
					throw new EvaluationException("Expression is invalid.", $nfe);
				}

				if ($leftExpressionOperand->getUnaryOperator() != null) {
					$leftResultDouble = new Double($leftExpressionOperand
							->getUnaryOperator()->evaluate(
									$leftResultDouble));
				}
			} else {
				if ($leftExpressionOperand->getUnaryOperator() != null) {
					throw new EvaluationException("Invalid operand for unary operator.");
				}
			}
		} else if ($this->leftOperand instanceof ParsedFunction) {

			$parsedFunction = $this->leftOperand;
			$function = $parsedFunction->getFunction();
			$arguments = $parsedFunction->getArguments();
			$arguments = $this->evaluator.replaceVariables($arguments);
			
			if ($this->evaluator->getProcessNestedFunctions()) {
				$arguments = $this->evaluator->processNestedFunctions($arguments);
			}

			try {
				$functionResult = $function->execute($this->evaluator, $arguments);
				$leftResultString = $functionResult->getResult();

				if ($functionResult->getType() == FunctionConstants::FUNCTION_RESULT_TYPE_NUMERIC) {
					$resultDouble = new Double($leftResultString);

					// Process a unary operator if one exists.
					if ($parsedFunction->getUnaryOperator() != null) {
						$resultDouble = new Double($parsedFunction
								->getUnaryOperator()
                                ->evaluate($resultDouble.getValue()));
					}

					// Get the final result.
					$leftResultString = $resultDouble.toString();
				} 
				else if ($functionResult->getType() == FunctionConstants::FUNCTION_RESULT_TYPE_STRING) {
					// The result must be a string result.
					if ($wrapStringFunctionResults) {
						$leftResultString = $this->evaluator->getQuoteCharacter()
								. $leftResultString
								. $this->evaluator->getQuoteCharacter();
					}

					if ($parsedFunction->getUnaryOperator() != null) {
						throw new EvaluationException("Invalid operand for unary operator.");
					}
				}
			} catch (FunctionException $fe) {
				throw new EvaluationException($fe->getMessage(), $fe);
			}

			if (!$this->evaluator->isExpressionString($leftResultString)) {
				try {
					$leftResultDouble = new Double($leftResultString);
					$leftResultString = null;
				} catch (NumberFormatException $nfe) {
					throw new EvaluationException("Expression is invalid.", $nfe);
				}
			}
		} else {
			if ($this->leftOperand != null) {
				throw new EvaluationException("Expression is invalid.");
			}
		}

		// Get the right operand.
		$rightResultString = null;
		$rightResultDouble = null;

		if ($this->rightOperand instanceof ExpressionTree) {
			$rightResultString = $this->rightOperand->evaluate($wrapStringFunctionResults);

			try {
				$rightResultDouble = new Double($rightResultString);
				$rightResultString = null;
			} catch (NumberFormatException $exception) {
				$rightResultDouble = null;
			}

		} else if ($this->rightOperand instanceof ExpressionOperand) {

			$rightExpressionOperand = $this->rightOperand;
			$rightResultString = $this->rightOperand->getValue();
			$rightResultString = $this->evaluator->replaceVariables($rightResultString);

			// Check if the operand is a string or not. If it not a string,
			// then it must be a number.
			if (!$this->evaluator->isExpressionString($rightResultString)) {
				try {
					$rightResultDouble = new Double($rightResultString);
					$rightResultString = null;
				} catch (NumberFormatException $nfe) {
					throw new EvaluationException("Expression is invalid.", $nfe);
				}

				if ($rightExpressionOperand->getUnaryOperator() != null) {
					$rightResultDouble = new Double($rightExpressionOperand
							->getUnaryOperator()->evaluate($rightResultDouble));
				}
			} else {
				if ($rightExpressionOperand->getUnaryOperator() != null) {
					throw new EvaluationException("Invalid operand for unary operator.");
				}
			}
		} else if ($this->rightOperand instanceof ParsedFunction) {

			$parsedFunction = $this->rightOperand;
			$function = $parsedFunction->getFunction();
			$arguments = $parsedFunction->getArguments();
			$arguments = $this->evaluator->replaceVariables($arguments);
			
			if ($this->evaluator->getProcessNestedFunctions()) {
				$arguments = $this->evaluator->processNestedFunctions($arguments);
			}

			try {				
				$functionResult =$function->execute($this->evaluator, $arguments);
				$rightResultString = $functionResult->getResult();

				if ($functionResult->getType() ==
					FunctionConstants::FUNCTION_RESULT_TYPE_NUMERIC) {
					
					$resultDouble = new Double($rightResultString);

					// Process a unary operator if one exists.
					if ($parsedFunction->getUnaryOperator() != null) {
						$resultDouble = new Double($parsedFunction
								->getUnaryOperator()
                                ->evaluate($resultDouble->getValue()));
					}

					// Get the final result.
					$rightResultString = $resultDouble->toString();
				}
				else if ($functionResult->getType() ==
					FunctionConstants::FUNCTION_RESULT_TYPE_STRING) {
					
					// The result must be a string result.
					if ($wrapStringFunctionResults) {
						$rightResultString = $this->evaluator->getQuoteCharacter()
								. $rightResultString
								. $this->evaluator->getQuoteCharacter();
					}

					if ($parsedFunction->getUnaryOperator() != null) {
						throw new EvaluationException("Invalid operand for unary operator.");
					}
				}
			} catch (FunctionException $fe) {
				throw new EvaluationException($fe.getMessage(), $fe);
			}

			if (!$this->evaluator->isExpressionString($rightResultString)) {
				try {
					$rightResultDouble = new Double($rightResultString);
					$rightResultString = null;
				} catch (NumberFormatException $nfe) {
					throw new EvaluationException("Expression is invalid.", $nfe);
				}
			}
		} else if ($this->rightOperand == null) {
			// Do nothing.
		} else {
			throw new EvaluationException("Expression is invalid.");
		}

		// Evaluate the the expression.
		if ($leftResultDouble != null && $rightResultDouble != null) {
			$doubleResult = $this->operator->evaluate($leftResultDouble, $rightResultDouble);

			if ($this->getUnaryOperator() != null) {
				$doubleResult = $this->getUnaryOperator()->evaluate($doubleResult);
			}

			$rtnResult = new Double($doubleResult);
            $rtnResult = $rtnResult->toString();
		} else if ($leftResultString != null && $rightResultString != null) {
			$rtnResult = $this->operator->evaluate($leftResultString, $rightResultString);
		} else if ($leftResultDouble != null && $rightResultDouble == null) {
            $doubleResult = new Double(-1);

			if ($this->unaryOperator != null) {
				$doubleResult = $this->unaryOperator->evaluate($leftResultDouble);
			} else {
				// Do not allow numeric (left) and
				// string (right) to be evaluated together.
				throw new EvaluationException("Expression is invalid.");
			}

			$rtnResult = new Double($doubleResult);
            $rtnResult = $rtnResult->toString();
		} else {
			throw new EvaluationException("Expression is invalid.");
		}

		return $rtnResult;
	}
}