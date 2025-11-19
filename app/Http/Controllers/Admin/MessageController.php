<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReceiverResource;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Sender;
use App\Models\User;
use App\Traits\SmsTrait;
use App\Traits\WhatsappTrait;
use Spatie\Permission\Models\Role;

class MessageController extends Controller
{
    use WhatsappTrait,SmsTrait;
    //??=========================================================================================================
    public function index()
    {
        $senders = Sender::all();
        $users = User::all();
        $roles = Role::all();
        $receivers = ReceiverResource::collection($users); //User::all()->toJson();

        return view('admin.message.index', compact('senders', 'users', 'roles', 'receivers'));
    }
    //??=========================================================================================================
    public function recievers(Request $request)
    {
        $roles = explode(',', $request->roles);
        $users = User::WhereHas('roles', function ($q) use ($roles) {
            $q->whereIn('id', $roles);
        })->get();
        // $users = User::all();
        // return compact('users', 'roles');
    }
    //??=========================================================================================================
    public function send(Request $request)
    {
        $data = [];
        if ($request->has('send_whatsapp') && $request->send_whatsapp == "true") {
            $data['whatsapp'] = $this->send_whatsapp($request);
        }
        if ($request->has('send_email') && $request->send_email == "true") {
            $data['email'] = '';
        }
        if ($request->has('send_sms') && $request->send_sms == "true") {
            $data['sms'] = $this->send_multiple_sms($request);
        }

        return response(compact('data'), 200);
    }
    //??=========================================================================================================
    public function send_whatsapp($request)
    {
        $result = [];
        $sender = Sender::find($request->sender);
        foreach ($request->receivers as $receiver) {
            array_push($result,$receiver['phone_code'] . $receiver['phone']);
        }
        $result = $this->send_message($sender, $request->message, implode(',',$result));

        return $result;
    }
    //??=========================================================================================================
    public function send_multiple_sms($request)
    {
        $result = [];
        $sender = Sender::find($request->sender);
        foreach ($request->receivers as $receiver) {
            array_push($result,$this->send_sms($sender, $request->message, $receiver['phone'], $receiver['phone_code']));
        }

        return $result;
    }
    //??=========================================================================================================
}
