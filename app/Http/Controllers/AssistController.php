<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Assist;
use App\Models\Status;
use App\Models\Support;
use Illuminate\Http\Request;
use App\Traits\AttachmentTrait;
use App\Notifications\CrudNotify;
use App\Traits\LocalizationTrait;
use App\Http\Requests\SectorRequest;
use App\Traits\LocationTrackerTrait;
use App\Http\Resources\AssistResource;
use App\Jobs\GenerateAndSendPDFJob;
use App\Models\Danger;
use App\Services\AnswerService;
use Illuminate\Support\Carbon;

class AssistController extends Controller
{
    use AttachmentTrait, LocationTrackerTrait;
    public function index(){
        if(!request()->support_id){
            return response()->json(['message'=> trans('translation.Must provide support id')], 400);
        }
        $assists = Assist::where('support_id', request()->support_id)->get();
        return response()->json(['assists'=> AssistResource::collection($assists)],200);
    }

    public function store(Request $request){
        $this->attachments_validator($request->all())->validate();

        $assist = Assist::create(request()->only('quantity','support_id','assigner_id', 'assistant_id'));
        
        foreach ($request->attachments as $key => $attachment) {
            $new_attachment = $this->store_attachment($attachment, $assist, $key, null, $request->user()->id);
        }
         
        // User::find($assist->support->user->id)->notify(new CrudNotify($assist, 'create'));
        return response()->json([
            'message' => trans('translation.Assist created successfully'),
            'assist'=> $assist],
            200);
    }

    public function update(SectorRequest $sectorRequest, Assist $assist){//for submitting an assist 
        $assist = Assist::find(request()->assist_id);
        if($assist->status_id == Status::DELIVERED_ASSIST){
            return response()->json(['message'=> trans('translation.Assist already submitted')], 200);
        }
        if($assist->status_id == Status::CANCELED_ASSIST){
            return response()->json(['message'=> trans('translation.Assist has been canceled')], 200);
        }

        $this->attachments_validator(request()->all())->validate();
        foreach (request()->attachments as $key => $attachment) {
            $new_attachment = $this->store_attachment($attachment, $assist, $key, null, request()->user()->id);
        }
        $assist->update([
            'status_id' => Status::DELIVERED_ASSIST,
        ]);
        $support = $assist->support;
        User::find($support->user->id)->notify(new CrudNotify($assist, 'changeStatus'));

        if (
            ($support->status_id == Status::HAS_ENOUGH_SUPPORT && $support->assigned_quantity == $support->deliverd_quantity)
            || ($support->delivered_quantity == $support->quantity)
        ) {
            $old_support_id = $support->status_id;
            $support->update([
                'status_id' => Status::CLOSED_SUPPORT,
            ]);
            User::find($support->user->id)->notify(new CrudNotify($support, 'changeStatus'));

            // send email to chairmen
            $organization = $support->order_sector->order->organization_service->organization;
            $statuses = Status::where('type', 'supports')->get();
            $danger_levels = Danger::get();
            $answer_service = new AnswerService();
            $pdfData = [
                'attachment_label' => 'تقرير عن إسناد',
                'organization_data' => $organization,
                'body_content' => $support,
                'statuses' => $statuses,
                'danger_levels' => $danger_levels,
                'answer_service' => $answer_service,
            ];

            $statusFrom = $statuses->firstWhere('id', $old_support_id);
            $statusTo = $statuses->firstWhere('id', Status::CLOSED_SUPPORT);
            $templateData = [
                'supportCode' => $support->code,
                'statusFrom' => $statusFrom?->name_ar,
                'statusFromColor' => $statusFrom?->color,
                'statusTo' =>  $statusTo?->name_ar,
                'statusToColor' =>  $statusTo?->color,
            ];

            GenerateAndSendPDFJob::dispatch(
                organization: $organization,
                pdfName: $support->id . ' - ' . $support->order_sector->order->facility->name . ' - ' . Carbon::now() . '.pdf',
                pdfTemplate: 'support.support',
                pdfData: $pdfData,
                mailTopic: 'تغيير حالة عملية اسناد',
                mailTemplate: 'mails.templates.support-template',
                mailTemplateData: $templateData,
            );
        }
        
        $action = trans('translation.Submit assist for support: ') . $support->code;
        $this->tracker($sectorRequest, $assist, $action);
        return response()->json([
            'message' => trans('translation.Assist submitted successfully'),
            'assist'=> $assist],
            200);


    }
}
