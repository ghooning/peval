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
use Tbm\Peval\Collection\Iterator;

use Tbm\Peval\Evaluator;
use Tbm\Peval\Func\IFunction;
use Tbm\Peval\Func\IFunctionGroup;

/**
 * A groups of functions that can loaded at one time into an instance of
 * Evaluator. This group contains all of the functions located in the
 * net.sourceforge.jeval.function.string package.
 */
class StringFunctions implements IFunctionGroup
{
	/**
	 * Used to store instances of all of the functions loaded by this class.
     *
     * @var \Tbm\Peval\Collection\ArrayList
	 */
	private $functions;

	/**
	 * Default constructor for this class. The functions loaded by this class are
	 * instantiated in this constructor.
     *
	 */
	function __construct()
    {
        $this->functions = new ArrayList();

		$this->functions->add(new CharAt());

        //TODO: add other functions
//		functions.add(new CompareTo());
//		functions.add(new CompareToIgnoreCase());
//		functions.add(new Concat());
//		functions.add(new EndsWith());
//		functions.add(new Equals());
//		functions.add(new EqualsIgnoreCase());
//		functions.add(new Eval());
//		functions.add(new IndexOf());
//		functions.add(new LastIndexOf());
//		functions.add(new Length());
//		functions.add(new Replace());
//		functions.add(new StartsWith());
//		functions.add(new Substring());
//		functions.add(new ToLowerCase());
//		functions.add(new ToUpperCase());
//		functions.add(new Trim());
	}

	/**
	 * Returns the name of the function group - "stringFunctions".
	 * 
	 * @return \Tbm\Peval\Types\String The name of this function group class.
	 */
	public function getName()
    {
		return new String("stringFunctions");
	}

	/**
	 * Returns a list of the functions that are loaded by this class.
	 * 
	 * @return \Tbm\Peval\Collection\ArrayList A list of the functions loaded by this class.
	 */
	public function getFunctions()
    {
		return $this->functions;
	}

	/**
	 * Loads the functions in this function group into an instance of Evaluator.
	 * 
	 * @param \Tbm\Peval\Evaluator $evaluator
	 *            An instance of Evaluator to load the functions into.
	 */
	public function load(Evaluator $evaluator) {
		$functionIterator = $this->functions->iterator();

		while ($functionIterator->valid()) {
            $function = $functionIterator->current();
			$evaluator->putFunction($function);

            $functionIterator->next();
		}
	}

	/**
	 * Unloads the functions in this function group from an instance of
	 * Evaluator.
	 * 
	 * @param \Tbm\Peval\Evaluator $evaluator
	 *            An instance of Evaluator to unload the functions from.
	 */
	public function unload(Evaluator $evaluator)
    {
		$functionIterator = $this->functions->iterator();

		while ($functionIterator->valid()) {
            $function = $functionIterator->current();
			$evaluator->removeFunction($function);

            $functionIterator->next();
		}
	}
}
