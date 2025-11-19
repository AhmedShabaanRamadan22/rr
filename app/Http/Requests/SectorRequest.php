<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Monitor;
use App\Models\MonitorOrderSector;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MessageFormRequest;
use App\Models\OrderSector;
use Illuminate\Foundation\Http\FormRequest;

class SectorRequest extends MessageFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $monitor = Monitor::where('user_id',Auth::user()->id)->first();
        $sector = MonitorOrderSector::where(['order_sector_id' => request()->order_sector_id, 'monitor_id' => $monitor->id])->first();
        $supervisorSector = OrderSector::where('id' , request()->order_sector_id)->whereHas('sector',function($q){ $q->where('supervisor_id',Auth::user()->id)->orWhere('boss_id',Auth::user()->id);})->first();
        return isset($sector) || isset($supervisorSector);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_sector_id' => 'required|numeric',//|exists:monitor_order_sectors,order_sector_id',
        ];
    }

    public function messages(){
        return [
            'required' => trans('translation.No order sector provided'),
        ];
    }
}
