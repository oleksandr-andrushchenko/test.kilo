<?php

namespace App\Exception;

use Exception as BaseException;
use Throwable;

class Exception extends BaseException
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
