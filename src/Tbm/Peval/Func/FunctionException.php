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

use Exception;
use Tbm\Peval\Types\String;

/**
 * This exception is thrown when an error occurs while processing a function.
 */
class FunctionException extends Exception
{
    function __construct($object1, $object2 = null)
    {
        if ($object2 === null) {
            if ($object1 instanceof Exception) {
                $this->FunctionException1($object1);
            }
            elseif ($object1 instanceof String) {
                $this->FunctionException($object1);
            }
        }
        elseif ($object1 instanceof String && $object2 instanceof Exception) {
            $this->FunctionException2($object1, $object2);
        }
    }

    /**
     * This constructor takes a custom message as input.
     *
     * @param \Tbm\Peval\Types\String $message
     * @internal param $message A custom message for the exception to display.
     *            A custom message for the exception to display.
     */
	private function FunctionException(String $message)
    {
	    parent::__constructor($message);
	}

    /**
     * This constructor takes an exception as input.
     *
     * @param \Exception $exception
     * @internal param $exception An exception.*            An exception.
     */
	private function FunctionException1(Exception $exception) {
		parent::__constructor($exception->getMessage(), 0, $exception);
	}

    /**
     * This constructor takes an exception as input.
     *
     * @param \Tbm\Peval\Types\String $message
     * @param \Exception $exception
     * @internal param $message A custom message for the exception to display.
     *            A custom message for the exception to display.
     * @internal param $exception An exception.*            An exception.
     */
	private function FunctionException2(String $message, Exception $exception) {
		paranr::__constructor($message, 0, $exception);
	}
}