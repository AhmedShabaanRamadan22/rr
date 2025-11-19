<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectorOrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'=> $this->id ,
            'slug'=> $this->slug ,
            'name_ar'=> $this->name_ar ,
            'name_en'=> $this->name_en ,
            'primary_color'=> $this->primary_color ,
            // 'domain'=> $this->domain ,
            // 'about_us'=> $this->about_us ,
            // 'contract'=> $this->contract ,
            // 'policies'=> $this->policies ,
            // 'phone'=> $this->phone ,
            // 'has_esnad'=> $this->has_esnad ,
            // 'close_registeration'=> $this->close_registeration ,
            // 'close_order'=> $this->close_order ,
            // 'sender_id'=> $this->sender_id ,
            // 'city_id'=> $this->city_id ,
            // 'district_id'=> $this->district_id ,
            // 'postal_code'=> $this->postal_code ,
            // 'building_number'=> $this->building_number ,
            // 'sub_number'=> $this->sub_number ,
            // 'release_date'=> $this->release_date ,
            // 'email'=> $this->email ,
            // 'release_date_hj'=> $this->release_date_hj ,
            // 'street_name'=> $this->street_name ,
            // 'registration_number'=> $this->registration_number ,
            // 'registration_source'=> $this->registration_source ,
            // 'support_phone'=> $this->support_phone ,
            'logo'=> $this->logo ,
            // 'background_image'=> $this->background_image ,
            // 'attachmentUrl'=> $this->attachmentUrl ,
            'name'=> $this->name ,
            // 'profile_file'=> $this->profile_file ,
            // 'has_classifications'=> $this->has_classifications ,
            // // 'has_employee_contract_template'=> $this->has_employee_contract_template ,
            // 'national_address'=> $this->national_address ,
            // 'logo_attachment'=> $this->logo_attachment ,
            // 'background_image_attachment'=> $this->background_image_attachment ,
            // 'profile_attachment'=> $this->profile_attachment ,
        ];
        return $data;
    }
}
