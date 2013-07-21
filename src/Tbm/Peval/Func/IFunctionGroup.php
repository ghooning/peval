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

use Tbm\Peval\Evaluator;
use Tbm\Peval\Collection\ArrayList;
use Tbm\Peval\Types\String;

/**
 * A groups of functions that can loaded at one time into an instance of
 * Evaluator.
 */
interface IFunctionGroup
{
	/**
	 * Returns the name of the function group.
	 * 
	 * @return Tbm\Peval\Types\String The name of this function group class.
	 */
	public function getName();

	/**
	 * Returns a list of the functions that are loaded by this class.
	 * 
	 * @return Tbm\Peval\Collections\ArrayList A list of the functions loaded by this class.
	 */
	public function getFunctions();

    /**
     * Loads the functions in this function group into an instance of Evaluator.
     *
     * @param \Tbm\Peval\Evaluator $evaluator
     * @return
     * @internal param $ Tbm\Peval\Evaluator
     *            An instance of Evaluator to load the functions into.
     */
	public function load(Evaluator $evaluator);

    /**
     * Unloads the functions in this function group from an instance of
     * Evaluator.
     *
     * @param \Tbm\Peval\Evaluator $evaluator
     * @return
     * @internal param $ Tbm\Peval\Evaluator
     *            An instance of Evaluator to unload the functions from.
     */
	public function unload(Evaluator $evaluator);
}