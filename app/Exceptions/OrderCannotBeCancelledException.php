<?php

namespace App\Exceptions;

use Exception;

class OrderCannotBeCancelledException extends Exception
{
    public function __construct(
        string $message = 'Only new orders can be cancelled.',
        int $code = 422
    ) {
        parent::__construct($message, $code);
    }
}
