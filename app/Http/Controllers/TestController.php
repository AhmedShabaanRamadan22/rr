<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailRequest;
use App\Http\Services\MailService;
use App\Models\Danger;
use App\Models\Organization;
use App\Models\Status;
use App\Models\Ticket;
use App\Traits\PdfTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestController extends Controller
{
    use PdfTrait;

    public function sendEmail(SendEmailRequest $request)
    {
        $data = $request->safe()->all();
        $ticket = Ticket::orderBy('created_at', 'desc')->first();
        $statuses = Status::where('type', 'tickets')->get();
        $danger_levels = Danger::get();
        $organization = Organization::where('slug', 'RWM')->first();
        $this->setPdfData([
            'attachment_label' => 'تقرير بلاغ',
            'organization_data' => $organization,
            'sector' => $ticket->order_sector->sector,
            'body_content' => $ticket,
            'statuses' => $statuses,
            'danger_levels' => $danger_levels
        ]);
        $mpdf = $this->mPdfInit('ticket.ticket-template');
        $pdf = $mpdf->Output(dest: 'S');
        $testReceivers = array_merge(['a.bahmeed@rakaya.co', 'o.khan@rakaya.co', 'o.jehni@rakaya.co'], $data['emails']);
        $receivers = config('app.send_email_to_chairmans') ? ['chairmans@test.co'] : [$testReceivers];
        (new MailService())->sendMailToUsers(
            receivers_array: $receivers,
            topic: 'بلاغ - اختبار',
            content: 'اختبار محتوى البريد',
            organization: $organization,
            raw_attachments: [
                [
                    'content' => $pdf,
                    'name' => $ticket->code . ' - ' . $ticket->order_sector->order->facility->name . ' - ' . Carbon::now() . '.pdf'
                ]
            ],
            path_attachments: [
                // '/test.pdf' // get these urls from the storage either the local driver or the S3
            ],
        );
        return response([
            'message' => 'the email was sent successfully'
        ]);
    }
}
