<?php

namespace App\Docs\Attributes;

use Knuckles\Scribe\Attributes\Response;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class ThrottleRequestErrorResponse extends Response
{
    public function __construct()
    {
        parent::__construct(
            content: [
                "flag" => false,
                "message" => trans('translation.Too Many Attempts.'),
                "general_error_message" => trans('translation.Please try again later'),
            ],
            status: 429
        );
    }
}
