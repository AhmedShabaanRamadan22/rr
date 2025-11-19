<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpRequest;
use App\Http\Resources\WebResources\UserResource;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\OtpTrait;
use App\Traits\SmsTrait;
use App\Traits\WhatsappTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails, OtpTrait, WhatsappTrait, SmsTrait;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('signed')->only('verify');
        // $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    //?==========================================================================


    public function verifyWithOtp(OtpRequest $request)
    {
        // $group_id = Group::where(['domain'=>get_domain($request)])->get()->first()->id;
        // check if (otp, phone) for any (user or guest) in db

        $user = $this->hasDataByPhone($request);
        if (!$user) {
            return response()->json([
                'message' => __('User Not Found!')], 401);
        }

        // check if user has already verified
        if ($user->is_verified) {
            return response()->json([
                'message' => __('User already verified!')], 401);
        }

        $has_error = $this->verifyOtp($request->otp, $user, $request);

        if (!is_string($has_error)) {
            return response()->json([
                'message' => $has_error], 401);
            // return $has_error;
        }

        $user->update(['verified_at' => now()]);
        //! need to check if the sender exist
        $message = trans('translation.send-whatsapp-verify-user',['user_name' => $user->name]);
        $whatsapp_response = $this->send_message($this->getSender(), $message, $user->phone_code . $user->phone);
        $sending_sms = $this->send_sms($this->getSender(),$message,$user->phone,$user->phone_code);

        // $user->setHidden(['roles']);
        // $user->append('is_verified');

        $user = new UserResource($user);

        return response()->json([
            'message' => trans('translation.Correct OTP, User has verified'),
            'user' => $user,
            'whatsapp_response' => $whatsapp_response,
            'sms_response' => $sending_sms,
        ], 200);
    }
}
