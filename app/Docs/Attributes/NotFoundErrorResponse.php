<?php

namespace App\Docs\Attributes;

use Knuckles\Scribe\Attributes\Response;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class NotFoundErrorResponse extends Response
{
    public function __construct()
    {
        parent::__construct(
            content: [
                "flag" => false,
                "message" => trans('translation.Resource not found'),
                "general_error_message" => trans('translation.Please contact customer service'),
            ],
            status: 404
        );
    }
}
