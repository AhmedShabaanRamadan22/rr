<?php

namespace App\Rules;

use App\Models\AttachmentLabel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RequiredAttachments implements ValidationRule
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
        if (!is_array($value)) {
            $fail('Attachments must be an array.');
            return;
        }

        // Get required labels for this type
        $requiredLabels = AttachmentLabel::where('type', $this->table)
            ->where('is_required', '1')
            ->get();

        foreach ($requiredLabels as $label) {
            if (!array_key_exists($label->id, $value)) {
                $fail("Attachment for label '{$label->placeholder_en}' is required.");
            }
        }
    }
}
