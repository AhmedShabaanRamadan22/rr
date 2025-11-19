<?php

namespace App\Http\Services;

use App\Mail\GeneralMail;
use App\Models\Organization;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * @param array $users
     * @param string $subject
     * @param $content
     * @param string|null $bcc
     * @param array|null $raw_attachments
     * @param array|null $attachments
     */
    public function sendMailToUsers(
        array $receivers_array,
        string $topic,
        $content,
        Organization $organization,
        string $bcc = 'bcc.rakaya@gmail.com',
        array $raw_attachments = [],
        array $path_attachments = [],
    ) {
        foreach ($raw_attachments as $key => $attachment) {
            $raw_attachments[$key]['content'] = base64_encode($attachment['content']);
        }

        foreach ($receivers_array as $receivers) {
            Mail::to($receivers)
                ->bcc($bcc)
                ->queue(new GeneralMail($topic, $content, $organization, $raw_attachments, $path_attachments));
        }
    }
}
