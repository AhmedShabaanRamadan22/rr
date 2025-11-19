<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpRequest;
use App\Services\WhiteListService;
use App\Traits\OtpTrait;
use Illuminate\Http\Request;

class SendOtpController extends Controller
{
    use OtpTrait;

    public function mobileSendOtp(OtpRequest $request,$message = null){
        $this->validateUserRole($request,'monitor');
        if(WhiteListService::check_white_list_monitor_phone($request,$this->hasDataByPhone($request) )){
            return response()->json(['message' => trans('translation.something went wrong')],400);
        }

        return $this->sendOtp($request,$message);
    }
    //?==========================================================================
    
    public function frontSendOtp(OtpRequest $request){
        $this->validateOrganization();
        $this->validateUserRole($request,'providor');
            
        return $this->sendOtp($request);
    }
    //?==========================================================================
    
    public function mobileResendOtp(OtpRequest $request){
        // $this->validateUserRole($request,'monitor');
        // if(WhiteListService::check_white_list_phone($request)){
        //     return response()->json(['message' => trans('translation.something went wrong')],400);
        // }
        return $this->mobileSendOtp($request, "Otp has resend");
    }
    //?==========================================================================
    
    public function frontResendOtp(OtpRequest $request){
        $this->validateOrganization();
        $this->validateUserRole($request,'providor');
        
        return $this->sendOtp($request, "Otp has resend");
    }

    

}
