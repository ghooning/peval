<?php

/**
 * This interface can be implement with a custom resolver and set onto the
 * Evaluator class. It will then be used to resolve variables when they are
 * replaced in an expression as it gets evaluated. Variables resolved by the
 * resolved will override any variables that exist in the variable map of an
 * Evaluator instance.
 */

namespace Tbm\Peval;

interface IVariableResolver {

    /**
     * Returns a variable value for the specified variable name.
     *
     * @param variableName
     *            The name of the variable to return the variable value for.
     *
     * @return A variable value for the specified variable name. If the variable
     *         name can not be resolved, then null should be returned.
     *         
     * @throws Can throw a FunctionException if needed.
     */
    public function resolveVariable(String $variableName);
}