<?php

namespace App\Exceptions;

use Exception;

class CloudflareException extends Exception
{
    public array $responseData;

    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}

