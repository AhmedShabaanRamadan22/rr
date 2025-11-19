<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebResources\FacilityResource;
use Throwable;
use App\Models\City;
use App\Models\Iban;
use App\Models\User;
use App\Models\Order;
use App\Models\District;
use App\Models\Facility;
use App\Traits\SmsTrait;
use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Traits\WhatsappTrait;
use App\Models\AttachmentLabel;
use App\Traits\AttachmentTrait;
use App\Traits\OrganizationTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FacilityRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FacilityUpdateRequest;
use App\Http\Requests\FacilityAuthorizeRequest;
use App\Models\Answer;
use App\Models\OrderSector;
use App\Models\Section;
use App\Models\SubmittedForm;
use App\Notifications\FacilityUpdatedNotify;
use Illuminate\Support\Facades\File;

class FacilityController extends Controller
{
    use AttachmentTrait, OrganizationTrait, WhatsappTrait, SmsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user_facilities = Facility::where('user_id', auth()->user()->id ?? 0)->get() ?? [];

        if (request()->has('select')) {
            $user_facilities->pluck(explode(',', request()->select));
        }

        return response()->json(['user_facilities' => FacilityResource::collection($user_facilities)], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FacilityRequest $request)
    {
        $this->validateOrganization();
        $this->attachments_validator($request->all())->validate();


        $user = User::find(auth('sanctum')->user()->id);

        $facility = Facility::create($request->only([
            'registration_number',
            'name',
            'version_date',
            'version_date_hj',
            'end_date',
            'end_date_hj',
            'registration_source',
            'license',
            'capacity',
            'license_expired',
            'license_expired_hj',
            'tax_certificate',
            'employee_number',
            'chefs_number',
            'kitchen_space',
            'building_number',
            'street_name',
            'district_id',
            'city_id',
            'postal_code',
            'sub_number',
        ]) + ['user_id' => $user->id]);

        $bank_info = $facility->iban()->create(
            request()->only(['account_name', 'iban', 'bank_id'])
        );


        if ($request->has('attachments')) {
            foreach ($request->attachments as $key => $attachment) {
                $new_attachment = $this->store_attachment($attachment, $facility, $key, null, $user->id);
            }
        }

        $message = trans('translation.send-whatsapp-add-new-facility', ['facility_name' => $facility->name, 'name' => $facility->user->name, 'support' => $this->getSender()->organization->support_phone ?? 570044066]);
        $whatsapp_response = $this->send_message($this->getSender(), $message, $user->phone_code . $user->phone);
        $sending_sms = $this->send_sms($this->getSender(), $message, $facility->user->phone, $facility->user->phone_code);
        return response()->json(['message' => trans('translation.Facility added successfully'), 'facility' => new FacilityResource($facility), 'whatsapp_response' => $whatsapp_response, 'sending_sms' => $sending_sms], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(FacilityAuthorizeRequest $facilityAuthorizeRequest, Facility $facility)
    {
        return response()->json(['facility' => new FacilityResource($facility)], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FacilityAuthorizeRequest $facilityAuthorizeRequest, FacilityUpdateRequest $request, Facility $facility)
    {
        $activeOrder = Order::where('facility_id', $facility->id)->whereNotIn('status_id', [4, 5])->get();
        if ($activeOrder) {
            response()->json(['message' => __("translation.Can't edit the facility because you have an active order")], 400);
        }

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $facility->getFillable())) { // && $key != "capacity") {
                $facility[$key] = $value;
            }
        }

        if ($request->has('del_attachments')) {
            foreach ($request->del_attachments as $key => $value) {
                $attachment = AttachmentLabel::find($value);
                if ($attachment->is_required) {
                    return response()->json(['message' => trans("translation.You can't delete :attribute attachment", ["attribute" => trans('validation.labels.' . $attachment->label)])], 400);
                }
                $this->delete_attachment($facility, $attachment->id);
                // return response()->json(['message'=> trans("translation.:attribute attachment deleted successfully", ["attribute" => trans('validation.labels.' . $attachment->label)])],400);
            }
        }

        // $flag = null;
        $iban = $facility->iban()->first();
        if (($request->has('account_name') && $request->account_name != $iban->account_name) || ($request->has('iban') && $request->iban != $iban->iban) || ($request->has('bank_id') && $request->bank_id != $iban->bank_id)) {
            if (isset(request()->attachments[AttachmentLabel::IBAN_NUMBER_LABEL])) {
                $iban->update(
                    request()->only(['account_name', 'iban', 'bank_id'])
                );
            } else {
                $label = AttachmentLabel::find(AttachmentLabel::IBAN_NUMBER_LABEL);
                // $message = trans("translation.Bank infromation not updated due to missing attachment", ["attachment_label" => trans('validation.labels.' . $label->placeholder)]);
                $message = trans("translation.Must attach :attachment_label to update bank information", ["attachment_label" => $label->placeholder]);
                return response()->json(['message' => $message], 400);
            }
        }

        if ($request->has('attachments')) { //just in update check if theres and attachments in the request, if so validate it an update it ,, if not then theres no need for validation in the first place
            $this->attachments_validator($request->all(), $facility)->validate();

            foreach ($request->attachments as $key => $value) {
                $this->update_attachment($value, $facility, $attachment_label_id = $key, null, $user_id = $facility->user_id);
            }
        }

        // if($flag){
        //     return response()->json(['message' => trans('translation.Facility updated successfully') . $flag, 'facility' => $facility], 400);
        // }
        $facility->update();
        if ($facility->hasAssignees()) {
            $this->notify_assignees($facility);
        }
        $message = trans('translation.send-whatsapp-update-facility');
        $whatsapp_response = $this->send_message($this->getSender(), $message, $facility->user->phone_code . $facility->user->phone);
        $sending_sms = $this->send_sms($this->getSender(), $message, $facility->user->phone, $facility->user->phone_code);
        return response()->json(['message' => trans('translation.Facility updated successfully'), 'facility' => new FacilityResource($facility), 'whatsapp_response' => $whatsapp_response, 'sending_sms' => $sending_sms], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function notify_assignees($facility)
    {
        $assignees = $facility->get_orders_assignees();
        foreach ($assignees as $assginee) {
            $assginee->notify(new FacilityUpdatedNotify($facility));
        }
    }

    public function report()
    {
        $json_questions = File::get(storage_path('facilities-rating.json'));
        $primary_categories = json_decode(json: $json_questions, associative: true)['primary_categories'];
        $question_ids = [246]; // this is the location place question id

        // getting the ids of the question needed for the rating form the json file
        foreach ($primary_categories as $primary_category)
            foreach ($primary_category['sub_categories'] as $sub_category)
                foreach ($sub_category['questions'] as $question) {
                    if (is_array($question['question_id']))  $question_ids = array_merge($question_ids, $question['question_id']);
                    else $question_ids[] = $question['question_id'];
                }
        $orderSectors = OrderSector::withArchived()
        ->whereHas('order', function ($query) {
            $query->whereHas('organization_service', function ($query) {
                $query->whereIn('organization_id', [2, 6, 7, 8]);
            });
        })->whereNull('parent_id')
        ->with([
                'order',
                'sector.nationality_organization.nationality',
                'submitted_forms' => function ($query) use ($question_ids) {
                    $query->whereDate('updated_at', '>=', '2025-05-25')
                        ->with([
                            'form.sections_has_question',
                            'submitted_sections',
                            'answers' => function ($query) use ($question_ids) {
                                $query->whereHas('question', function ($query) use ($question_ids) {
                                    $query->whereHas('question_bank_organization', function ($query) use ($question_ids) {
                                        $query->whereIn('question_bank_id', $question_ids);
                                    });
                                })->with([
                                    'question.question_bank_organization',
                                    'answerable.form'
                                ]);
                            }
                        ]);
                },
            ])->get();

        $result = [];
        foreach ($orderSectors as $orderSector) {
            $order_sector_result['order_id'] = 'ORD00'. $orderSector->order->id;
            $order_sector_result['sector_number'] = $orderSector->sector->label;
            $order_sector_result['nationality'] = $orderSector->sector->nationality_organization->nationality->name;
            $order_sector_result['name'] = $orderSector->order->facility->name;
            $order_sector_result['organization'] = $orderSector->order->organization_service->organization->name;
            foreach ($primary_categories as $primary_category) {
                $primary_category_result = [];
                foreach ($primary_category['sub_categories'] as $sub_category) {
                    //filtering only the needed forms
                    $filtered_submitted_forms = $orderSector->submitted_forms->filter(function ($submitted_form) use ($sub_category) {
                        if (
                            $submitted_form->form->name == $sub_category['form_name']
                            && $submitted_form->is_completed
                            ) {
                                if ($sub_category['location']) {
                                $option_ids = $sub_category['location'] == 'mina' ?
                                    [124, 129, 159, 288]
                                    :
                                    [125, 130, 160, 234, 284, 289];
                                    $locationAnswer = $submitted_form->answers->first(function ($answer) {
                                    return $answer->question->question_bank_organization->question_bank_id == 246;
                                });
                                return in_array($locationAnswer->value, $option_ids);
                            }
                            return true;
                        } else {
                            return false;
                        }
                    });
                    if ($filtered_submitted_forms->isNotEmpty()) {
                        foreach ($sub_category['questions'] as $question) {
                            $answers = $filtered_submitted_forms
                                ->pluck('answers')
                                ->flatten()
                                ->filter(function ($answer) use ($question) {
                                    if (is_array($question['question_id'])) {
                                        return in_array(
                                            $answer->question->question_bank_organization->question_bank_id,
                                            $question['question_id']
                                        );
                                    } else {
                                        return $answer->question->question_bank_organization->question_bank_id == $question['question_id'];
                                    }
                                });
                            $primary_category_result[] = $this->calculate_average_answer_value($answers, $question['correct_answer']);
                        }
                    } else {
                        foreach ($sub_category['questions'] as $question) {
                            $primary_category_result[] = 1;
                        }
                    }
                }
                $answers_average = (array_sum($primary_category_result) / count($primary_category_result));
                $order_sector_result[$primary_category['name']] = number_format($answers_average * $primary_category['weight'], 2);
            }
            $result[] = $order_sector_result;
        }

        return response()->json([
            'data' =>  $result,
        ]);
    }


    private function calculate_average_answer_value($answers, $correct_answers_value)
    {
        $correct_answers = 0;
        foreach ($answers as $answer) {
            if ($answer->value == $correct_answers_value) $correct_answers++;
        }
        return $correct_answers / $answers->count();
    }
}
