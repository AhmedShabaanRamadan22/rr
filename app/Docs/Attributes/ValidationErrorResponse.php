<?php

namespace App\Docs\Attributes;

use Knuckles\Scribe\Attributes\Response;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ValidationErrorResponse extends Response
{
    public function __construct(array $errors = [])
    {
        $errorContent = array_map(function ($messages) {
            return is_array($messages) ? $messages : [$messages];
        }, $errors);

        $content = json_encode([
            'flag' => false,
            'general_error_message' => trans('translation.Please contact customer service'),
            'message' => trans('translation.Validation failed'),
            'errors' => $errorContent ?: ['field' => ['Error message']]
        ]);

        parent::__construct(
            content: $content,
            status: 422
        );
    }
}
