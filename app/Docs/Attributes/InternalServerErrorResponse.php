<?php

namespace App\Docs\Attributes;

use Knuckles\Scribe\Attributes\Response;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class InternalServerErrorResponse extends Response
{
    public function __construct()
    {
        parent::__construct(
            content: [
                'flag' => false,
                'message' => trans('translation.Internal server error'),
                'general_error_message' => trans('translation.Please contact customer service'),
            ],
            status: 500
        );
    }
}
