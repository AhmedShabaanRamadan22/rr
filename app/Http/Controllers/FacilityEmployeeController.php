<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Traits\WhatsappTrait;
use App\Traits\AttachmentTrait;
use App\Models\FacilityEmployee;
use App\Traits\OrganizationTrait;
use App\Http\Controllers\Controller;
use App\Models\FacilityEmployeePosition;
use App\Http\Requests\FacilityEmployeeRequest;
use App\Http\Resources\WebResources\FacilityEmployeeResource;
use App\Traits\SmsTrait;

class FacilityEmployeeController extends Controller
{

    use OrganizationTrait, AttachmentTrait;
    use WhatsappTrait, SmsTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = User::findOrFail(auth('sanctum')->user()->id);

            $employees = $user->facility_employees;

            if (request()->has('facility_id')) {
                $facility = Facility::find(request()->facility_id);
                if (empty($facility)) {
                    return response()->json([
                        'message' => trans('translation.No facility with this ID')
                    ], 400);
                }
                $employees = $facility->facility_employees;
            }


            return request()->is('api/*')
                ? response()->json(['employees' => FacilityEmployeeResource::collection($employees)], 200)
                : back();
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
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
    public function store(FacilityEmployeeRequest $request)
    {
        $user = User::findOrFail(auth('sanctum')->user()->id);
        
        $facility = Facility::findOrFail($request->facility_id);


        $this->attachments_validator($request->all())->validate();

        $employee = FacilityEmployee::create(request()->only('national_id', 'name', 'facility_employee_position_id', 'facility_id'));


        foreach ($request->attachments as $key => $attachment) {
            $new_attachment = $this->store_attachment($attachment, $employee, $key, null, $user->id);
        }
        $employee->makeHidden(['attachments', 'facility']);
        $message = trans('translation.send-whatsapp-add-new-facility-employee', ['facility_name' => $facility->name, 'name' => $employee->name, 'position' => $employee->position_name]);
        $whatsapp_response = $this->send_message($this->getSender(), $message, $facility->user->phone_code . $facility->user->phone);
        $sending_sms = $this->send_sms($this->getSender(), $message, $facility->user->phone, $facility->user->phone_code);
        $message_notification = trans('translation.add-new-facility-employee', ['facility_name' => $facility->name, 'name' => $employee->name]);
        return response()->json(['message' => $message_notification,'employee' => new FacilityEmployeeResource($employee), 'whatsapp_response' => $whatsapp_response, 'sending_sms' => $sending_sms], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail(auth('sanctum')->user()->id);
            $employee = $user->facility_employees()->find($id);
            if (!$employee) {
                return response()->json([
                    'message' => __("translation.Empolyee with this ID doesn't exist")
                ], 400);
            }

            $employee_name = $employee->name;
            $facility = $employee->facility;
            $employee->delete();
            $message = trans('translation.send-whatsapp-delete-facility-employee', ['facility_name' => $facility->name, 'name' => $employee->name]);
            $whatsapp_response = $this->send_message($this->getSender(), $message, $facility->user->phone_code . $facility->user->phone);
            $sending_sms = $this->send_sms($this->getSender(), $message, $facility->user->phone, $facility->user->phone_code);
            return response()->json([
                'message' => trans('translation.delete-facility-employee', ['facility_name' => $facility->name, 'name' => $employee->name]),
                // 'message' => $employee_name . trans('translation. deleted successfully'),
                'whatsapp_response'  => $whatsapp_response ,
                'sending_sms'  => $sending_sms ,
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
