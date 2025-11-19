<?php

namespace App\Exceptions;

use Exception;

class DuplicateActiveOrderException extends Exception
{
    public function __construct(
        string $message = 'An active order already exists for this service.',
        int $code = 422
    ) {
        parent::__construct($message, $code);
    }
}
