<?php

namespace Seungmun\Sens\Exceptions;

use Exception;

class SensException extends Exception
{
    /**
     * Exception for Invalid NCLOUD SENS Tokens.
     *
     * @param  string  $message
     * @return \Seungmun\Sens\Exceptions\SensException
     */
    public static function InvalidNCPTokens($message)
    {
        return new static($message);
    }
}
