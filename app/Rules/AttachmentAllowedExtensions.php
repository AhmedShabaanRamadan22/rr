<?php

namespace App\Rules;

use App\Models\AttachmentLabel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class AttachmentAllowedExtensions implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Extract label id from attachments.<label_id>
        $labelId = (int) Str::after($attribute, 'attachments.');
        $label = AttachmentLabel::find($labelId);

        if (!$label) return;

        $allowedExtensions = $label->extensions; // array
        $ext = strtolower($value->getClientOriginalExtension());

        if (!in_array($ext, $allowedExtensions)) {
            $fail("Attachment '{$label->placeholder_en}' must be one of: " . implode(', ', $allowedExtensions));
        }
    }
}
