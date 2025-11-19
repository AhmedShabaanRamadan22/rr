<?php

namespace App\Traits;

use App\Models\Organization;
use App\Models\AttachmentLabel;
use App\Models\OrganizationUser;
use SebastianBergmann\Type\FalseType;
use Illuminate\Support\Facades\Validator;
use App\Models\OrganizationAttachmentLabel;

trait OrganizationTrait
{

    public function getOrganization() {
        $organization = Organization::find(request()->organization_id ?? 0);
        return $organization;
    }

    public function validateOrganization(){
        $production_error_message = trans('translation.please_contact_customer_service');
        $validator = Validator::make(request()->all(), [
            'organization_id' => 'required|numeric|exists:organizations,id',
        ], 
        is_production() ? 
        [
            'organization_id.required' => $production_error_message,
            'organization_id.exists' => $production_error_message,
            'organization_id.numeric' => $production_error_message,
        ]
        :
        [
            'organization_id.required' => trans('translation.You must provide an organization ID.'),
            'organization_id.exists' => trans('translation.No organization found.'),
            'organization_id.numeric' => trans('translation.The organization ID must be a numeric value.'),
        ]);
        $validator->validate();
    }

    function hasNoOrganization(){
        return $this->getOrganization() == null && request()->organization_id == 0;
    }

    function hasSender(){
        return $this->getOrganization()->sender != null;
    }

    public function getSender(){
        return $this->getOrganization()->sender ?? null;
    }

    function setOrganizationToUser($user)
    {
        $user->update(['organization_id'=>$this->getOrganization()->id??null]);
        // if($this->getOrganizationByDomain() == null){
        //     OrganizationUser::create([
        //         "user_id" => $user->id,
        //         "organization_id" => 1,
        //     ]);
        // }else{
            // $user->organizations()->attach($this->getOrganization()->id);

        // }
    }

}
