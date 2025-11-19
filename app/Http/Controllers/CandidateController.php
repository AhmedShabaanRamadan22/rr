<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Traits\WhatsappTrait;
use App\Models\AttachmentLabel;
use App\Traits\AttachmentTrait;
use App\Http\Requests\CandidateRequest;
use App\Http\Resources\CandidateResource;
use App\Http\Requests\CandidateUpdateRequest;
use App\Traits\SmsTrait;

class CandidateController extends Controller
{

    use AttachmentTrait, WhatsappTrait, SmsTrait;


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
    public function store(CandidateRequest $request)
    {
        $candidate           = Candidate::create($request->all());
        //        $candidate_cv        = $request->file( 'candidate_cv' );
        //        $candidate_portfolio = $request->file( 'candidate_portfolio' );
        //
        //        if ( $candidate_cv ) {
        //            $label_id = AttachmentLabel::CANDIDATE_CV_LABEL;
        //            $this->validateExtension( $label_id, $candidate_cv );
        //            $this->store_attachment( $candidate_cv, $candidate, $label_id );
        //        }
        //
        //        if ( $candidate_portfolio ) {
        //            $label_id = AttachmentLabel::CANDIDATE_PORTFOLIO_LABEL;
        //            $this->validateExtension( $label_id, $candidate_portfolio );
        //            $this->store_attachment( $candidate_portfolio, $candidate, $label_id );
        //        }

        if ($request->has('attachments')) {
            foreach ($request->attachments as $key => $attachment) {
                $new_attachment = $this->store_attachment($attachment, $candidate, $key, null, null);
            }
        }

        $message = trans('translation.send-whatsapp-add-new-candidate', ['name' => $candidate->name]);
        $whatsapp_response = $this->send_message($this->getSender(), $message, $candidate->phone_code . $candidate->phone);
        // $sending_sms = $this->send_sms($this->getSender(),$message,$candidate->phone,$candidate->phone_code);

        return response()->json(['message' => trans('translation.submitted-successfully'), 'code' => $candidate->code, 'whatsapp_response' => $whatsapp_response], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        $candidate = Candidate::where('uuid', $uuid)->first();
        if(!$candidate ){
            return response()->json(['message' => trans('translation.something-wrong-contact-support'),'expired_flag'=>false], 200);
        }
        if( $candidate->status_id != Status::AWAITING_DATA_COMPLETION_CANDIDATE){
            return response()->json(['message' => trans('translation.something-wrong-contact-support'),'expired_flag'=>false], 200);
        }
        return response()->json(['candidate' => new CandidateResource($candidate),'expired_flag'=>true], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CandidateUpdateRequest $candidateUpdateRequest, $uuid)
    {
        $candidate = Candidate::where(['uuid' => $uuid, 'status_id' => Status::AWAITING_DATA_COMPLETION_CANDIDATE])->first();
        if(!$candidate ){
            return response()->json(['message' => trans('translation.something-wrong-contact-support'),'expired_flag'=>false], 200);
        }
        //!! comments until take an approve and deployed from front
        if(!isset(request()->attachments[AttachmentLabel::CANDIDATE_NATIONAL_ID]) && !isset($candidate->attachment_candidate_national_id)){//if candidate doesnt have national id att & didnt attach one
                return response()->json(['message' => trans('translation.attachments required'),'attachment_id'=>AttachmentLabel::CANDIDATE_NATIONAL_ID], 422);
        }

        if (!isset(request()->attachments[AttachmentLabel::CANDIDATE_EDUCATION_CERTIFICATE]) && !isset($candidate->attachment_candidate_education_certificate)) {
            return response()->json(['message' => trans('translation.attachments required'),'attachment_id'=>AttachmentLabel::CANDIDATE_EDUCATION_CERTIFICATE], 422);
        }
        // if (!isset(request()->attachments[AttachmentLabel::CANDIDATE_NATIONAL_ADDRESS]) && !isset($candidate->attachment_candidate_national_address)) {
        //     return response()->json(['message' => trans('translation.attachments required'),'attachment_id'=>AttachmentLabel::CANDIDATE_NATIONAL_ADDRESS], 422);
        // }
        if (    (substr($candidateUpdateRequest->national_id,0,1) != '1')    &&    !isset(request()->attachments[AttachmentLabel::CANDIDATE_PASSPORT])    &&    !isset($candidate->attachment_candidate_passport)) {
            return response()->json(['message' => trans('translation.attachments required'),'attachment_id'=>AttachmentLabel::CANDIDATE_PASSPORT], 422);
        }
        if (isset(request()->attachments)) {
            foreach ($candidateUpdateRequest->attachments as $key => $attachment) {
                $candidate_has_attachment = $candidate->attachments->where('attachment_label_id',$key)->first();
                if($candidate_has_attachment){
                    $this->update_attachment($attachment, $candidate, $key, null, null);
                }else{
                    $this->store_attachment($attachment, $candidate, $key, null, null);
                }
            }
        }
        if(isset($candidate->iban)){
            $candidate->iban()->update(request()->only(['account_name', 'owner_national_id', 'iban', 'bank_id']));
        }else{
            $candidate->iban()->create(request()->only(['account_name', 'owner_national_id', 'iban', 'bank_id']));
        }
        //! need to change all() later
        $candidate->update(request()->all() + ['status_id' => Status::COMPLETED_DATA_CANDIDATE]);
        $message = trans('translation.candidate-updated-successfully');
        $send_whatsapp = $this->send_message(null, $message, $candidate->phone_code . $candidate->phone);
        // $sending_sms = $this->send_sms(null,$message,$candidate->phone,$candidate->phone_code);

        return response()->json(['message' => trans('translation.updated successfully'), 'candidate' => new CandidateResource($candidate)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        //
    }
}
