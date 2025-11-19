<?php

namespace App\Rules;

use App\Models\AttachmentLabel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedAttachments implements ValidationRule
{
    protected string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedKeys = AttachmentLabel::where('type', $this->table)->pluck('id')->toArray();
        $keys = array_keys($value);
        $invalidKeys = array_diff($keys, $allowedKeys);
        if (!empty($invalidKeys)) {
            $fail("Invalid attachment keys: " . implode(', ', $invalidKeys));
        }
    }
}
