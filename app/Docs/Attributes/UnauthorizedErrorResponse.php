<?php

namespace App\Docs\Attributes;

use Knuckles\Scribe\Attributes\Response;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class UnauthorizedErrorResponse extends Response
{
    public function __construct()
    {
        parent::__construct(
            content: [
                "flag" => false,
                "message" => trans('translation.This action is unauthorized'),
                "general_error_message" => trans('translation.You do not have permission'),
            ],
            status: 401
        );
    }
}
