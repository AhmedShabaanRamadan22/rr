<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\User;
use App\Http\Requests\OtpRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

trait OtpTrait
{

    use OrganizationTrait, WhatsappTrait, SmsTrait;


    public function sendOtp(OtpRequest $request, $message =  "Otp has send")
    {
        //? moved to service and set in SendOtpCtl
        // if ( config('app.white_list_wall_flag') && App::environment() === 'production' && ! in_array($request->phone,['509165066','596938018','570044066','557436279','530410927','544021418','565603434'])) {
        //     return response()->json(['message' => trans('translation.something went wrong')],400);
        // }

        // $group_id = Group::where(['domain'=>])->get()->first()->id;
        $user = $this->hasDataByPhone($request);
        return $this->generateOtp($request, $user, $message);
    }
    //?==========================================================================

    public function generateOtp(OtpRequest $request, $user, $message)
    {
        // if($guest_or_user->hasPassedCountLimitVerifications()){
        //     return response()->json(['message' => __('Has passed number of limit per a day') ], 401);
        // }

        if (!$user) {
            return response([
                'message' => trans('translation.User not found')
            ], 400);
        }

        // if user already logged in
        // if (auth('sanctum')->check()) {
        //     return response()->json(['message' => "User already logged in"], 400);
        // }

        $verification = $user->otps()->create([
            'value' => rand(1000, 9999),
            'expired_at' => Carbon::now()->addMinutes(7),
        ]);

        // $message = trans('translation.your_OTP_is',['otp'=>$verification->value]);
        $message = trans('translation.send-otp', ['otp' => $verification->value]);
        $sending_result = $this->send_message($this->getSender(), $message, $user->phone_code . $user->phone);
        // if($user->hasRole(['monitor'])){
        $sending_sms = $this->send_sms($this->getSender(), $message, $user->phone, $user->phone_code);
        // }

        $return_otp = config('app.return_otp_in_response');
        return response()->json([
            'message' => trans('translation.Otp has sent to: ', ['code' => $user->phone_code, 'phone' =>  $user->phone,]),
            'verification' => $return_otp ? $verification:'stp tmp',
            'sending_result' => $sending_result,
            'sending_sms' => $sending_sms ?? 'no-sms',
        ], 200);
    }
    //?==========================================================================

    public function checkOtp($otp, $verification, $phone)
    {
        if (is_null($verification)) {
            return trans('translation.Phone number not has any active OTP', ['phone' => $phone]);
        }
        if (!($is_correct = ($verification->value == $otp) || ($otp == config('app.passed_otp')))) {
            return  trans('translation.Incorrct OTP');
        } elseif ($is_correct && $verification->isExpired()) {
            return  trans('translation.Expired OTP');
        }
        return null;
    }
    //?==========================================================================

    public function verifyOtp($otp, $user)
    {
        $verification =  $user->lastOtp;
        if (!is_null($message = $this->checkOtp($otp, $verification, $user->phone))) {
            return response()->json([
                'message' => $message
            ], 401);
        }
        $verification->update(['expired_at' => Carbon::now()]);

        // if (!$user->isSuperAdmin()) {

        //     $user = $this->createUser($request);
        //     if (!$user->isAdmin()) {
        //         $url = route('orders.index');
        //     }
        // }
        // Auth::login($user);

        return "true";
    }
    //?==========================================================================

    public function hasDataByPhone(OtpRequest $request)
    {
        // $organization = $this->getOrganizationByDomain();
        // $organization_id = $organization->id ?? $request->organization_id;
        return $user = User::where($request->only(['phone', 'phone_code']))->first();
        // if ($user && $user->isSuperAdmin()) {
        //     return $user;
        // }
        // return $user = User::where($request->only(['phone', 'phone_code']) + ['organization_id' => $organization->id])->first();
        // return response()->json(($user->picture),200);
    }

    //?==========================================================================
    public function validateUserRole(OtpRequest $request, $type)
    {
        $user = $this->hasDataByPhone($request);

        if (!$user) { //check for the entered parameter
            // return ['message' => trans("translation.User not found")];
            throw ValidationException::withMessages(['message' => trans("translation.User not found")]);
        }

        $user_type = ['providor' => ['providor'], 'monitor' => ['monitor']];

        if (!array_key_exists($type, $user_type)) { //check for the entered parameter
            // return ['message' => trans("translation.key-does'nt-exists")];
            throw ValidationException::withMessages(['message' => trans("translation.key-does'nt-exists")]);
        }

        if (!array_intersect($user->getRoleNames()->toArray(), $user_type[$type])) { //checking the provided parameters is either providor or monitor
            // return ['message' => trans("translation.Unauthorized User")];
            throw ValidationException::withMessages(['message' => trans("translation.Unauthorized User")]);
        }
        return [];
    }
}
