<?php

namespace App\Traits;

use App\Models\Country;

trait SmsTrait
{
    public static function call_sms_curl($phone_sender_id, $phone_app_sid, $message, $phone, $phone_code, $sender_name)
    {
        $params = array(
            'AppSid' => $phone_app_sid, //j309Js01vRZTAxleJC7SDnWQbQkCFW',
            'SenderID' => $phone_sender_id,
            'Body' => urldecode(str_replace('\n', '%0A', $message)),
            // 'Body'=> urldecode('الفريق العظيم،%0Aوقت الاستراحة والراحة. %0Aنراكم بنشاط وحماس بداية الأسبوع القادم! انتم علبة 🥫%0A%0Aحالة 3 للجميع 🌹'),  
            'Recipient' => str_replace('+', '', $phone_code) . $phone,
            'async' => 'false'
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://el.cloud.unifonic.com/rest/SMS/messages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params), //. "&Body=" . urlencode(str_replace('\\n','\n',$message)),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return  "cURL Error #:" . $err . '\n instance' . $phone_sender_id . '\n sender name:' . $sender_name;
        } else {
            return  [$response, $phone_sender_id, $sender_name];
        }
    }

    function send_sms($sender, $message, $phone, $phone_code)
    {
        if (config('app.sms_flag')) {
            $sender_name = $sender->name ?? 'Etqan';
            $sender_slug = $sender->organization->slug ?? 'ETQ';
            $sender_phone_sender_id = $sender->phone_sender_id ?? config('app.etqan_phone_sender_id', 'RAKAYA');
            $sender_phone_app_sid = $sender->phone_app_sid ?? config('app.etqan_phone_app_sid', 'j309Js01vRZTAxleJC7SDnWQbQkCFW');

            if(isset($sender) && !($sender->able_to_send_sms)){
                return 'sms stopped by sender';
            }
            // dd($organization->whatsapp_instance_id,$message . '.\n From ' . $organization->name_ar,$phone,$phone_code);
            $result = $this->call_sms_curl($sender_phone_sender_id, $sender_phone_app_sid, $this->customMessageCode($message, $sender_slug), $phone, $phone_code, $sender_name);
            return $result;
        }
        return "sms stopped temporarily";

    }

    function customMessageCode($message, $sender_slug)
    {
        return $message .= "\n\n $sender_slug" . time();
    }
}
