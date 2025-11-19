<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    private $submitted_form_id;
    private $test;
    public function __construct($resource,$test=null, $submitted_form = null)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->submitted_form_id = $submitted_form;
        $this->test = $test;
        // dd($submitted_form);
    }
    public function toArray(Request $request): array
    {
        $sections = $this->sections_has_question;
        if ($this->submitted_form_id != null) {
            foreach ($sections as $section) {
                $section->submitted_form_id = $this->submitted_form_id;
            }
        }
        $data = [
            'id' => $this->id,
            // 'submitted_form_id' => $this->submitted_form_id,
            // 'testtttt' => $this->testtttt,
            'name' => $this->name,
            'display' => $this->display,
            'submissions_times' => $this->submissions_times,
            'submissions_by' => $this->submissions_by,
            'submissions_limit' => $this->submissions_limit,
            'code' => $this->code,
            'description' => $this->description,
            'organization_id' => $this->organization_id,
            'organization_service_id' => $this->organization_service_id,
            'organization_category_id' => $this->organization_category_id,
            'is_visible' => $this->is_visible,
            'null_section' => $this->null_section,
            'display_flag' => $this->display_flag,
            'current_user_last_submission_at' => $this->when($this->relationLoaded('submitted_forms') && request()->has('order_sector_id'), function(){
                return $this->submitted_forms->filter(function($submitted_form){
                    return $submitted_form->is_completed
                    && $submitted_form->user_id == auth()->id()
                    && $submitted_form->order_sector_id == request()->order_sector_id;
                })->sortByDesc('updated_at')->first()?->updated_at;
            }),
            'general_last_submission' => $this->when($this->relationLoaded('submitted_forms') && request()->has('order_sector_id'), function(){
                $submitted_form = $this->submitted_forms->filter(function($submitted_form){
                    return $submitted_form->is_completed
                    && $submitted_form->order_sector_id == request()->order_sector_id;
                })->sortByDesc('updated_at')->first();
                return [
                    'user_name' => $submitted_form?->user->name,
                    'last_submission_at' => $submitted_form?->updated_at,
                ];
            }),
            'created_at' => $this->created_at,
            'sections_has_question' => SectionResource::collection($sections),
        ];
        return $data;
    }
}
