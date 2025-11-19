<?php

namespace App\Traits;

use App\Models\Organization;
use App\Models\Sender;

trait WhatsappTrait
{

    use OrganizationTrait;

    function call_curl($instance_id, $token, $message, $phone_with_phone_code, $sender_name)
    {
        $params = array(
            'token' => $token,
            'to' => $phone_with_phone_code,
            'body' => $message
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/" . $instance_id . "/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return  "cURL Error #:" . $err . '\n instance' . $instance_id . '\n sender name:' . $sender_name;
        } else {
            return  [$response, $instance_id,$sender_name];
        }
    }

    function send_message($sender, $message, $phone_with_phone_code)
    {
        if (config('app.whatsapp_flag')) {

            $sender_name = $sender->name ?? 'Etqan';
            $sender_whatsapp_instance_id = $sender->whatsapp_instance_id ?? config('app.default_whatsapp_instance_id');
            $sender_whatsapp_token = $sender->whatsapp_token ?? config('app.default_whatsapp_token');

            // dd($organization->whatsapp_instance_id,$message . '.\n From ' . $organization->name_ar,$phone,$phone_code);
            $result = $this->call_curl($sender_whatsapp_instance_id, $sender_whatsapp_token, $message, $phone_with_phone_code, $sender_name);

            return $result;
        }
        return "whatsapp stopped temporarily";
    }

    function send_message_with_file()
    {
        //
    }
}
