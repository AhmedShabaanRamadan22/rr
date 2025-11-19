<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\OtpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\OtpRequest;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Providers\RouteServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Resources\WebResources\UserResource;
use App\Services\WhiteListService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, OtpTrait;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    //?==========================================================================

    // override parent functino to add reCaptcha rule
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha',

        ]);
    }

    //?==========================================================================

    public function loginWithOtp(OtpRequest $request, $type)
    {
        

        $user = $this->hasDataByPhone($request);
        if (!$user) {
            return response()->json([
                'message' => __('User Not Found')
            ], 401);
        }
        if ( WhiteListService::check_white_list_monitor_phone($request,$user)) {
            return response()->json(['message' => trans('translation.something went wrong')],400);
        }
        //monitor: eyJpdiI6IlYrTTRKYXRTWnNGdFZFU2ZqbmF6UEE9PSIsInZhbHVlIjoiekp0bGoveDBGTEhSOXNVQ096SzROdz09IiwibWFjIjoiZjdjMmY2ZjllN2NmMDY0NWNjMGI5NzQxM2VmNDJlZTc5OTE4NDQ5MzcwYjIyMmI5Yzg2MTg0OGIzNzRlZThhZiIsInRhZyI6IiJ9
        //providor: eyJpdiI6IkV6WlVFS2JqTXdZbjV2RkNCbllZUHc9PSIsInZhbHVlIjoiaDIrVTRqeFc1SW1SNjkwUWNTYm9kZz09IiwibWFjIjoiYTBkY2FlYjNmMTViOWQ2ODA3NGQ3MjNlMjc2ZjU2MzE5OWRhMWZlZTNjMTI5OThjODY4Y2FmMmQzMDYxZjlhZCIsInRhZyI6IiJ9
        
        // $type = Crypt::decrypt($type);
        // $user_type = ['providor' => ['providor', 'superadmin'], 'monitor' => ['monitor', 'superadmin']];
        // $unauth_user = User::where('phone', $request->phone)->first();

        // if (!array_key_exists($type, $user_type)) { //check for the entered parameter
        //     return response(['message' => __("translation.key-does'nt-exists")], 401);
        // }

        // if(!array_intersect($unauth_user->getRoleNames()->toArray(), $user_type[$type])){ //checking the provided parameters is either providor or monitor
        //     return response()->json(['message' => __("translation.Unauthorized User")], 401);
        // }

        $this->validateUserRole($request,$type);
        
        $has_error = $this->verifyOtp($request->otp, $user, $request);

        if (!is_string($has_error)) {
            return $has_error;
        }
        
        if($type == 'monitor'){
            $userTokens = $user->tokens()->whereNull('expires_at')->get();
            foreach ($userTokens as $token) {
                $token->update(['expires_at' => Carbon::now()]);
            }
        }
        
        $token = $user->createToken("authToken")->plainTextToken;

        $user->setHidden(['roles']);
        return response()->json([
            'message' => trans('translation.Correct OTP'),
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }

    //?==========================================================================

    public function logout(Request $request)
    {
        // $this->guard()->logout();
        if (($request->is('api/*'))) {
            if (!auth('sanctum')->user()) {
                return response()->json([
                    "message" => __('something went wrong!')
                ], 200);
            }
            $currentToken = auth('sanctum')->user()->currentAccessToken();
            $currentToken->update(['expires_at' => Carbon::now()]);
            return response()->json([
                "message" => __('Logout successfully')
            ], 200);
        } else {
            $request->session()->invalidate();
            Auth::guard('web')->logout();
            return redirect()->route('login');
        }
    }
    //?==========================================================================

}
