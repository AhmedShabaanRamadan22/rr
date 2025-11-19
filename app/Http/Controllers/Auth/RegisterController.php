<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AttachmentLabel;
use App\Traits\AttachmentTrait;
use App\Traits\OrganizationTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\WebResources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Traits\SmsTrait;
use App\Traits\WhatsappTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, AttachmentTrait, OrganizationTrait, WhatsappTrait, SmsTrait;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // return Validator::make($data, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['sometimes', 'string', 'min:8'],
        //     'phone' => ['required', 'unique:users', 'numeric', 'digits:9'],
        //     'phone_code' => ['required'],
        //     'nationality' => ['required'],
        //     'national_id' => ['required', 'unique:users', 'numeric', 'digits:10'],
        //     'national_id_expired' => ['required'],
        //     'birthday' => ['required'],
        //     'role' => ['sometimes', 'string', 'exists:roles,name']
        // ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $password = [];
        if (isset($data['password'])) {
            $password = [
                'password' => Hash::make($data['password']),
            ];
        }
        // $organization_id = $this->getOrganization();
        // if (isset($data['organization_id'])) {
        //     $organization_id = $data['organization_id'];
        // }

        $user =  User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'phone_code' => $data['phone_code'],
                'nationality' => $data['nationality'],
                'national_id' => $data['national_id'],
                'national_source' => $data['national_source'],
                'national_id_expired' => $data['national_id_expired'],
                'birthday' => $data['birthday'],
                'birthday_hj' => $data['birthday_hj'],
            ]
                +
                $password
        );

        // if (request()->has('role')) {
        //     $user->assignRole(request()->role);
        // }

        $user->assignRole([9]);// providor role id

        // if (request()->has('attachments')) {
        //     $response = $this->validate_attachments(request()->attachments);
        //     if ($response->getStatusCode() == 401) {
        //         return json_decode($response->getContent());
        //     }
        // }

        // $keys = array_keys(request()->attachments);
        // if (!in_array(4, $keys)) { //not dynamic at all, must find a way to make it dynamic
        //     return response()->json(["message" => "must attach a copy of your national id "], 401);
        // }

        if (request()->has('favourit_organizations')) {
            // Rakaya platform
            $user->favourit_organizations()->attach(request()->favourit_organization);
        } else {

            // Organization website
            $this->setOrganizationToUser($user);
        }

        foreach (request()->attachments as $key => $attachment) {
            $new_attachment = $this->store_attachment($attachment, $user, $key, null,  $user->id);
        }

        return $user;
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        // dd($request->has('organization_id'), $request->organization_id);
        // if($request->has('organization_id')){

        // }
        if ($request->has('organization_id') && $this->getOrganization()->close_registeration) {
            return response(['message' => trans('translation.Registration is closed for this organization')], 401);
        }

        $this->attachments_validator($request->all())->validate();

        $user = $this->create($request->all());

        Auth::login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        $token = $user->createToken("authToken")->plainTextToken;
        $message = trans('translation.send-create-account');
        $whatsapp_response = $this->send_message($this->getSender(),$message,$user->phone_code . $user->phone);
        $sending_sms = $this->send_sms($this->getSender(),$message,$user->phone,$user->phone_code);

        $user = new UserResource($user);

        return $request->is('api/*')
            ? response(compact('user', 'token', 'whatsapp_response', 'sending_sms'), 201)
            : redirect($this->redirectPath());
    }
}
