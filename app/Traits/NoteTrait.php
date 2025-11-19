<?php

namespace App\Traits;

use App\Http\Requests\NoteRequest;
use App\Models\User;
use Illuminate\Http\Request;

trait NoteTrait
{
    use WhatsappTrait;
    use SmsTrait;
    
    //* this function used in NoteController for admin and api
    public function store(NoteRequest $request)
    {
        $sending_responses = $this->store_note(
            $this->find_real_model($request->model,$request->id), 
            $request->notes, 
            auth()->user()->id
        );
        return response(['message' => 'Note was added successfuly!', 'alert-type' => 'success', 'sending_responses' => $sending_responses]);
    }

    public function store_note($model, $content, $user_id)
    {
        $by_user = User::findOrFail($user_id);
        $note_db = $model->notes()->create([
            'user_id' => $user_id,
            'content' => $content,
        ]);

        $array = ['Support', 'Ticket'];

        $class_name = class_basename($model);
        $user = $model->user;

        if (in_array($class_name, $array)) {
            $monitors = $model->order_sector->monitor_order_sectors()->get();

            $responses = [];
            foreach ($monitors as $sector_monitor) {

                $user = $sector_monitor->monitor->user;

                if ($class_name == 'Support') {
                    $class_name = $class_name . ' ' . $model->type_name;
                }
                $sending_responses = $this->whatsapp_message($model, $user, $content, $class_name, $by_user, null );// $model->order_sector->order->organization);
                array_push($responses,$sending_responses);
            }
            return $responses;
        }
        $sending_responses = $this->whatsapp_message($model, $user, $content, $class_name, $by_user, $model->organization);
        return $sending_responses;
        // return $note_db;
    }

    public function whatsapp_message($model, $user, $content, $class_name, $by_user, $organization)
    {
        $message = trans('translation.send-whatsapp-create-new-note', [
            'name' => $user->name,
            'code' => $model->code, 
            'model' => trans('translation.' . $class_name),
            'content' => $content,
            'byName' => $by_user->name,
        ]);
        $whatsapp_response = $this->send_message($organization?->sender, $message, $user->phone_code . $user->phone);
        $sms_response = $this->send_sms($organization?->sender, $message, $user->phone, $user->phone_code );
        return compact('whatsapp_response','sms_response');
    }

    public function find_real_model($model,$id){
        $model = app('App\Models\\' . ucwords($model))->find($id);
        return $model;
    }
}
