<?php

namespace App\Exceptions;

use Exception;

class DuplicateEmailException extends Exception
{
    //
    public function __construct($message = "Duplicate email entry", $code = 420, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        return response()->json(['message' => $this->getMessage()], $this->getCode());
    }
}
