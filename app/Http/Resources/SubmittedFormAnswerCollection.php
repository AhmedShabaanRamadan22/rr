<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubmittedFormAnswerCollection extends ResourceCollection
{
    protected $submittedForm;

    public function __construct($resource, $submittedForm)
    {
        parent::__construct($resource);
        $this->submittedForm = $submittedForm;
    }

    public function toArray($request)
    {
        return $this->collection->map(function ($section) {
            return new SubmittedFormAnswerResource($section, $this->submittedForm);
        })->all();
    }
}
